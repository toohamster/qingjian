<?php
/**
 * secache
 * MTI licence
 * pure-php coded key-value database engine created by shopex.
 * @version $Id$
 *
 * @link https://github.com/shopex/secache
 */
defined('SECACHE_SIZE') or define('SECACHE_SIZE','15M');

/**
 * 文件级缓存 Secache增强
 *
 * 类变量命名遵循驼峰
 * 函数参数及内部变量命名使用下划线分隔
 *
 * @author vb2005xu@qq.com
 */
class Secache
{

    const DEFAULT_SIZE_15MBYTES = 15728640; // 15M 的字节数
    const MIN_SIZE_10MBYTES = 10485760; // 10M 的字节数    
    const MIN_SIZE_10MKBYTES = 10240; // 10M 换算后的千字节数

    private $idxNodeSize = 40;
	private $idxNodeBase = 0;
    private $dataBasePos = 262588; //40+20+24*16+16*16*16*16*4;
    private $schemaItemSize = 24;
    private $headerPadding = 20; //保留空间 放置php标记防止下载
    private $infoSize = 20; //保留空间 4+16 maxsize|ver

    //40起 添加20字节保留区域
    private $idxSeqPos = 40; //id 计数器节点地址
    private $dfileCurPos = 44; //id 计数器节点地址
    private $idxFreePos = 48; //id 空闲链表入口地址

    private $idxBasePos = 444; //40+20+24*16
    // protected $minSize = 10240; //10M最小值
    private $maxSize; //最大值
    private $schemaStruct = ['size','free','lru_head','lru_tail','hits','miss'];
    private $ver = '$Rev$';

    protected $file = null; // 缓存文件路径
    private $rs = null; // 文件句柄
    private $bsizeList = [];
    private $nodeStruct = [];
    private $blockSizeList = [];

    public $name = 'Secache3';

    public function workat($file)
    {
        $this->file = $file.'.php';
        $this->bsizeList = [
            512 => 10,   // 512       512B
            3<<10 => 10, // 3*1024    3k
            8<<10 => 10, // 8*1024    8k
            20<<10 => 4, // 20*1024   20k
            30<<10 => 2, // 30*1024   30k
            50<<10 => 2, // 50*1024   50k
            80<<10 => 2, // 80*1024   80k
            96<<10 => 2, // 96*1024   96k
            128<<10 => 2,// 128*1024  128k
            224<<10 => 2,// 224*1024  224k
            256<<10 => 2,// 256*1024  256k
            512<<10 => 1,// 512*1024  512k
            1024<<10=> 1,// 1024*1024 1024k
        ];

        $this->nodeStruct = [
            'next' => [0,'V'],
            'prev' => [4,'V'],
            'data' => [8,'V'],
            'size' => [12,'V'],
            'lru_right' => [16,'V'],
            'lru_left'=> [20,'V'],
            'key' => [24,'H*'],
        ];

        if(!file_exists($this->file)){
            $this->create();
        }else{
            $this->rs = fopen($this->file,'rb+') or $this->trigger_error('Can\'t open the cachefile: '.realpath($this->file),E_USER_ERROR);
            $this->seek($this->headerPadding);
            $info = unpack('V1max_size/a*ver', fread($this->rs, $this->infoSize));
			$info['ver'] = trim($info['ver']);
            if($info['ver'] != $this->ver){
                $this->format(true);
            }else{
                $this->maxSize = $info['max_size'];
            }
        }

        $this->idxNodeBase = $this->dataBasePos + $this->maxSize;
        $this->blockSizeList = array_keys($this->bsizeList);
        sort($this->blockSizeList);
        return true;
    }

    private function create()
    {
        $this->rs = fopen($this->file, 'wb+') or $this->trigger_error('Can\'t open the cachefile: '.realpath($this->file), E_USER_ERROR);
        fseek($this->rs, 0);
        fputs($this->rs, '<?php exit()?>');
        return $this->format(false);
    }

    private function puts($offset,$data)
    {
        if($offset < $this->maxSize*1.5){
            $this->seek($offset);
            return fputs($this->rs, $data);
        }

        $this->trigger_error("Offset over quota: {$offset}", E_USER_ERROR);
    }

    private function seek($offset)
    {
        return fseek($this->rs, $offset);
    }

    public function clear()
    {
        return $this->format(true);
    }

    public function fetch($key, &$return)
    {
        if($this->lock(false)){
            $locked = true;
        }

        if($this->search($key, $offset)){
            $info = $this->getNode($offset);
            $schema_id = $this->getSizeSchemaId($info['size']);
            if($schema_id === false){
                if($locked) $this->unlock();
                return false;
            }

            $this->seek($info['data']);
            $data = fread($this->rs, $info['size']);
            $return = unserialize($data);

            if($return === false){
                if($locked) $this->unlock();
                return false;
            }

            if($locked){
                $this->lruPush($schema_id, $info['offset']);
                $this->setSchema($schema_id, 'hits', $this->getSchema($schema_id, 'hits')+1);
                return $this->unlock();
            }

            return true;
        }

        if($locked) $this->unlock();

        return false;
    }

    /**
     * lock
     * 
     * 如果flock不管用，请继承本类，并重载此方法
     *
     * @param mixed $is_block 是否阻塞
     * 
     * @return bool
     */
    protected function lock($is_block, $whatever=false)
    {
        ignore_user_abort(1);
        return flock($this->rs, $is_block ? LOCK_EX : LOCK_EX + LOCK_NB);
    }

    /**
     * unlock
     * 
     * 如果flock不管用，请继承本类，并重载此方法
     * 
     * @return bool
     */
    protected function unlock()
    {
        ignore_user_abort(0);
        return flock($this->rs, LOCK_UN);
    }

    public function delete($key, $pos=false)
    {
        if($pos || $this->search($key, $pos)){
            if($info = $this->getNode($pos)){
                //删除data区域
                if($info['prev']){
                    $this->setNode($info['prev'], 'next', $info['next']);
                    $this->setNode($info['next'], 'prev', $info['prev']);
                }else{ //改入口位置
                    $this->setNode($info['next'], 'prev', 0);
                    $this->setNodeRoot($key, $info['next']);
                }
                $this->freeDSpace($info['size'], $info['data']);
                $this->lruDelete($info);
                $this->freeNode($pos);
                return $info['prev'];
            }
        }
        return false;
    }

    public function store($key,$value)
    {
        if($this->lock(true)){
            //save data
            $data = serialize($value);
            $size = strlen($data);

            //get list_idx
            $has_key = $this->search($key,$list_idx_offset);
            $schema_id = $this->getSizeSchemaId($size);
            if($schema_id===false){
                $this->unlock();
                return false;
            }
            if($has_key){
                $hdseq = $list_idx_offset;

                $info = $this->getNode($hdseq);
                if($schema_id == $this->getSizeSchemaId($info['size'])){
                    $dataoffset = $info['data'];
                }else{
                    //破掉原有lru
                    $this->lruDelete($info);
                    if(!($dataoffset = $this->dalloc($schema_id))){
                        $this->unlock();
                        return false;
                    }
                    $this->freeDSpace($info['size'], $info['data']);
                    $this->setNode($hdseq, 'lru_left', 0);
                    $this->setNode($hdseq, 'lru_right', 0);
                }

                $this->setNode($hdseq, 'size', $size);
                $this->setNode($hdseq, 'data', $dataoffset);
            }else{

                if(!($dataoffset = $this->dalloc($schema_id))){
                    $this->unlock();
                    return false;
                }
                $hdseq = $this->allocIdx([
                        'next'=>0,
                        'prev'=>$list_idx_offset,
                        'data'=>$dataoffset,
                        'size'=>$size,
                        'lru_right'=>0,
                        'lru_left'=>0,
                        'key'=>$key,
                    ]);

                if($list_idx_offset>0){
                    $this->setNode($list_idx_offset, 'next', $hdseq);
                }else{
                    $this->setNodeRoot($key, $hdseq);
                }
            }

            if($dataoffset > $this->maxSize){
                $this->trigger_error('alloc datasize:'.$dataoffset, E_USER_WARNING);
                return false;
            }
            $this->puts($dataoffset, $data);

            $this->setSchema($schema_id, 'miss', $this->getSchema($schema_id, 'miss')+1);

            $this->lruPush($schema_id, $hdseq);
            $this->unlock();
            return true;
        }else{
            $this->trigger_error("Couldn't lock the file !", E_USER_WARNING);
            return false;
        }
    }

    /**
     * 查找指定的key
     * 
     * 找到节点则$pos=节点本身 返回true , 否则 $pos=树的末端 返回false
     *
     * @param mixed $key
     *
     * @return bool
     */
    public function search($key, &$pos)
    {
        return $this->getPosByKey($this->getNodeRoot($key), $key, $pos);
    }

    private function getSizeSchemaId($size)
    {
        foreach($this->blockSizeList as $k => $block_size){
            if($size <= $block_size){
                return $k;
            }
        }

        return false;
    }

    /**
     * 解析并返回字节数长度
     * 
     * @param  string $str_size 长度表达式
     * @param  int $default  字节数
     * 
     * @return int 字节数
     */
    private function parseStrSize($str_size,$default)
    {
        if(preg_match('/^([0-9]+)\s*([gmk]|)$/i', $str_size, $match)){
            switch(strtolower($match[2])){
            case 'g':
                if($match[1]>1){
                    $this->trigger_error('Max cache size 1G', E_USER_ERROR);
                }
                $size = $match[1]<<30;  // * 1024 * 1024 * 1024
                break;
            case 'm':
                $size = $match[1]<<20;  // * 1024 * 1024
                break;
            case 'k':
                $size = $match[1]<<10;  // * 1024
                break;
            default:
                $size = $match[1];
            }
            if($size<=0){
                $this->trigger_error('Error cache size '.$str_size, E_USER_ERROR);
                return false;
            }elseif($size < self::MIN_SIZE_10MBYTES){
                // 最小是 10M
                return self::MIN_SIZE_10MBYTES;
            }else{
                return $size;
            }
        }

        return $default;
    }

    private function format($truncate=false)
    {
        if($this->lock(true, true)){

            if($truncate){
                $this->seek(0);
                ftruncate($this->rs, $this->idxNodeBase);// $this->idxNodeBase <=> 0
            }

            $this->maxSize = $this->parseStrSize(SECACHE_SIZE, self::DEFAULT_SIZE_15MBYTES); //default:15m
            $this->puts($this->headerPadding, pack('V1a*', $this->maxSize, $this->ver));

            ksort($this->bsizeList);
            $ds_offset = $this->dataBasePos; // $this->dataBasePos <=> 40+20+24*16+16*16*16*16*4 <=> 262588
            $i=0;
            //floor => 返回不大于 value 的最接近的整数，舍去小数部分取整
            $min_vv = min(3, floor($this->maxSize / self::MIN_SIZE_10MBYTES));
            foreach($this->bsizeList as $size => $count){

                //将预分配的空间注册到free链表里
                $count *= $min_vv;
                $next_free_node = 0;
                for($j=0; $j<$count; $j++){
                    $this->puts($ds_offset,pack('V', $next_free_node)); //V 无符号长整型(32位，小端字节序)
                    $next_free_node = $ds_offset;
                    $ds_offset += intval($size);
                }
                // $this->schemaStruct <=> ['size','free','lru_head','lru_tail','hits','miss']
                $packParams = str_repeat('V1', count($this->schemaStruct));
                $code = pack($packParams, $size, $next_free_node, 0, 0, 0, 0);

                // schemaItemSize <=> 24
                $this->puts(60 + $i * $this->schemaItemSize, $code);
                $i++;
            }
            $this->setDcurPos($ds_offset);

            $this->puts($this->idxBasePos, str_repeat("\0", 262144));
            $this->puts($this->idxSeqPos, pack('V',1));
            $this->unlock();

            return true;
        }

        $this->trigger_error("Couldn't lock the file !", E_USER_ERROR);
        return false;
    }

    private function getNodeRoot($key)
    {
        $this->seek(hexdec(substr($key, 0, 4))*4+$this->idxBasePos);
        $a= fread($this->rs, 4);
        list(,$offset) = unpack('V', $a);
        return $offset;
    }

    private function setNodeRoot($key,$value)
    {
        return $this->puts(hexdec(substr($key, 0, 4))*4+$this->idxBasePos, pack('V', $value));
    }

    private function setNode($pos, $key, $value)
    {
        if($pos){
            if(isset($this->nodeStruct[$key])){
                $c = $pos*$this->idxNodeSize+$this->idxNodeBase+$this->nodeStruct[$key][0];
                return $this->puts($c, pack($this->nodeStruct[$key][1], $value));
            }
        }

        return false;
    }

    private function getPosByKey($offset, $key, &$pos)
    {
        if(!$offset){
            $pos = 0;
            return false;
        }

        $info = $this->getNode($offset);

        if($info['key'] == $key){
            $pos = $info['offset'];
            return true;
        }
        elseif($info['next'] && $info['next'] != $offset){
            return $this->getPosByKey($info['next'], $key, $pos);
        }

        $pos = $offset;        
        return false;
    }

    private function lruDelete($info)
    {
        if($info['lru_right']){
            $this->setNode($info['lru_right'], 'lru_left', $info['lru_left']);
        }else{
            $this->setSchema($this->getSizeSchemaId($info['size']), 'lru_tail', $info['lru_left']);
        }

        if($info['lru_left']){
            $this->setNode($info['lru_left'], 'lru_right', $info['lru_right']);
        }else{
            $this->setSchema($this->getSizeSchemaId($info['size']), 'lru_head', $info['lru_right']);
        }

        return true;
    }

    private function lruPush($schema_id,$offset)
    {
        $lru_head = $this->getSchema($schema_id, 'lru_head');
        $lru_tail = $this->getSchema($schema_id, 'lru_tail');

        if((!$offset) || ($lru_head == $offset)) return;

        $info = $this->getNode($offset);

        $this->setNode($info['lru_right'], 'lru_left', $info['lru_left']);
        $this->setNode($info['lru_left'], 'lru_right', $info['lru_right']);

        $this->setNode($offset, 'lru_right', $lru_head);
        $this->setNode($offset, 'lru_left', 0);

        $this->setNode($lru_head, 'lru_left', $offset);
        $this->setSchema($schema_id, 'lru_head', $offset);

        if($lru_tail==0){
            $this->setSchema($schema_id, 'lru_tail', $offset);
        }elseif($lru_tail==$offset && $info['lru_left']){
            $this->setSchema($schema_id, 'lru_tail', $info['lru_left']);
        }

        return true;
    }

    private function getNode($offset)
    {
        // idxNodeSize <=> 40
        $this->seek($offset * $this->idxNodeSize + $this->idxNodeBase);

        // unpack捷豹指定key，用/分割
        $info = unpack('V1next/V1prev/V1data/V1size/V1lru_right/V1lru_left/H*key', 
            fread($this->rs, $this->idxNodeSize));
        $info['offset'] = $offset;
        return $info;
    }

    private function lruPop($schema_id)
    {
        if($node = $this->getSchema($schema_id,'lru_tail')){
            $info = $this->getNode($node);            

            if($info['data']){                
                $this->delete($info['key'], $info['offset']);
                if(!$this->getSchema($schema_id, 'free')){
                    $this->trigger_error('pop lru,But nothing free...', E_USER_ERROR);
                }
                return $info;                
            }            
        }

        return false;
    }

    private function dalloc($schema_id,$lru_freed=false)
    {
        if($free = $this->getSchema($schema_id,'free')){ //如果lru里有链表
            $this->seek($free);
            list(,$next) = unpack('V',fread($this->rs, 4));
            $this->setSchema($schema_id, 'free', $next);
            return $free;
        }elseif($lru_freed){
            $this->trigger_error('Bat lru poped freesize', E_USER_ERROR);
            return false;
        }else{
            $ds_offset = $this->getDcurPos();
            $size = $this->getSchema($schema_id, 'size');

            if($size+$ds_offset > $this->maxSize){
                if($info = $this->lruPop($schema_id)){
                    return $this->dalloc($schema_id, $info);
                }else{
                    $this->trigger_error('Can\'t alloc dataspace', E_USER_ERROR);
                    return false;
                }
            }else{
                $this->setDcurPos($ds_offset+$size);
                return $ds_offset;
            }
        }
    }

    private function getDcurPos()
    {
        $this->seek($this->dfileCurPos);
        list(,$ds_offset) = unpack('V', fread($this->rs, 4));
        return $ds_offset;
    }

    private function setDcurPos($pos)
    {
        return $this->puts($this->dfileCurPos,pack('V', $pos));
    }

    private function freeDSpace($size, $pos)
    {
        if($pos>$this->maxSize){
            $this->trigger_error('free dspace over quota:'.$pos,E_USER_ERROR);
            return false;
        }

        $schema_id = $this->getSizeSchemaId($size);
        if($free = $this->getSchema($schema_id,'free')){
            $this->puts($free,pack('V1',$pos));
        }else{
            $this->setSchema($schema_id,'free',$pos);
        }
        $this->puts($pos, pack('V1',0));
    }

    private function dfollow($pos, &$c)
    {
        $c++;
        $this->seek($pos);
        list(,$next) = unpack('V1', fread($this->rs,4));
        if($next){
            return $this->dfollow($next,$c);
        }

        return $pos;
    }

    private function freeNode($pos)
    {
        $this->seek($this->idxFreePos);
        list(,$prev_free_node) = unpack('V',fread($this->rs,4));
        $this->puts($pos*$this->idxNodeSize + $this->idxNodeBase, 
            pack('V',$prev_free_node).str_repeat("\0",$this->idxNodeSize-4));
        
        return $this->puts($this->idxFreePos,pack('V',$pos));
    }

    private function allocIdx($data)
    {
        $this->seek($this->idxFreePos);
        list(,$list_pos) = unpack('V',fread($this->rs,4));
        if($list_pos){
            $this->seek($list_pos*$this->idxNodeSize+$this->idxNodeBase);
            list(,$prev_free_node) = unpack('V',fread($this->rs,4));
            $this->puts($this->idxFreePos,pack('V',$prev_free_node));
        }else{
            $this->seek($this->idxSeqPos);
            list(,$list_pos) = unpack('V',fread($this->rs,4));
            $this->puts($this->idxSeqPos,pack('V',$list_pos+1));
        }

        return $this->createNode($list_pos,$data);
    }

    private function createNode($pos,$data)
    {
        $this->puts($pos*$this->idxNodeSize + $this->idxNodeBase, 
            pack('V1V1V1V1V1V1H*', $data['next'], $data['prev'], 
                $data['data'], $data['size'], $data['lru_right'], $data['lru_left'], $data['key']));

        return $pos;
    }

    private function setSchema($schema_id, $key, $value)
    {
        $info = array_flip($this->schemaStruct);
        return $this->puts(60+$schema_id*$this->schemaItemSize + $info[$key]*4, pack('V', $value));
    }

    private function getSchema($id,$key)
    {
        $info = array_flip($this->schemaStruct);

        $this->seek(60+$id*$this->schemaItemSize);
        unpack('V1'.implode('/V1',$this->schemaStruct),fread($this->rs, $this->schemaItemSize));

        $this->seek(60+$id*$this->schemaItemSize + $info[$key]*4);
        list(,$value) = unpack('V', fread($this->rs, 4));
        return $value;
    }

    private function allSchemas()
    {
        $schema = [];
        for($i=0;$i<16;$i++){
            $this->seek(60+$i*$this->schemaItemSize);
            $info = unpack('V1'.implode('/V1',$this->schemaStruct), fread($this->rs,$this->schemaItemSize));
            if($info['size']){
                $info['id'] = $i;
                $schema[$i] = $info;
            }else{
                return $schema;
            }
        }
    }

    private function schemaStatus()
    {
        $return = [];
        foreach($this->allSchemas() as $schema_item){
            if($schema_item['free']){
                $this->dfollow($schema_item['free'],$schema_item['freecount']);
            }
            $return[] = $schema_item;
        }
        return $return;
    }

    public function status(&$cur_bytes, &$total_bytes)
    {
        $total_bytes = $cur_bytes = 0;
        $hits = $miss = 0;

        $schema_status = $this->schemaStatus();
        $total_bytes = $this->maxSize;
        $free_bytes = $this->maxSize - $this->getDcurPos();

        foreach($schema_status as $schema){
            $free_bytes+=$schema['freecount']*$schema['size'];
            $miss += $schema['miss'];
            $hits += $schema['hits'];
        }
        $cur_bytes = $total_bytes - $free_bytes;

        $return[] = ['name'=>'Cache Hit','value'=>$hits];
        $return[] = ['name'=>'Cache Miss','value'=>$miss];
        return $return;
    }

    public function trigger_error($errstr,$errno)
    {
        trigger_error($errstr, $errno);
    }

}

class secacheNoFlock extends Secache
{

    public function __construct()
    {
        parent::__construct();
        $this->support_usleep = 20;
    }

    protected function lock($is_block,$whatever=false)
    {
        ignore_user_abort(1);
        $lockfile = $this->file . '.lck';

        if (file_exists($lockfile)) {
            if (time() - filemtime($lockfile) > 5){
               unlink($lockfile);
            }elseif(!$is_block){
                return false;
            }
        }

        $lock_ex = @fopen($lockfile, 'x');
        for ($i=0; ($lock_ex === false) && ($whatever || $i < 20); $i++) {
           clearstatcache();
           if($this->support_usleep==1){
               usleep(rand(9, 999));
           }else{
               sleep(1);
           }
           $lock_ex = @fopen($lockfile, 'x');
        }

        return ($lock_ex !== false);
    }

    protected function unlock()
    {
        ignore_user_abort(0);
        return unlink($this->file.'.lck');
    }
}