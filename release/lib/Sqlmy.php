<?php
if (!function_exists('sqlmyInit'))
{
function sqlmyInit($config)
{
	sqlmyMS::init($config);
}

function sqlmyMaster()
{
	return sqlmyMS::master();
}

function sqlmySlaver()
{
	return sqlmyMS::slaver();
}
}

/**
 * sqlmy 数据库操作类
 */
class sqlmy
{

	/**
	 * 读 记录集
	 */
	const MODE_READ_GETALL = 1;
	
	/**
	 * 读 第一条记录
	 */
	const MODE_READ_GETROW = 2;
	
	/**
	 * 读 第一条记录的第一个字段
	 */
	const MODE_READ_GETONE = 3;
	
	/**
	 * 读 记录集的指定列
	 */
	const MODE_READ_GETCOL = 4;
	
	/**
	 * 读 记录集的总个数
	 */
	const MODE_READ_ALLCOUNT = 5;
	
	/**
	 * 写 (插入) 操作
	 */
	const MODE_WRITE_INSERT = 11;
	
	/**
	 * 写 (更新) 操作
	 */
	const MODE_WRITE_UPDATE = 12;
	
	/**
	 * 写 (删除) 操作
	 */
	const MODE_WRITE_DELETE = 13;

	/**
	 * 得到 sqlmyDataSource 对象
	 * 
	 * @param  array  $dsn 
	 * @return sqlmyDataSource
	 */
	static function ds(array $dsn)
	{
		static $list = array();
		$dsn = sqlmyDataSource::dsn($dsn);
		$id = $dsn['id'];
		if ( empty( $list[$id] ) )
		{
			$list[$id] = new sqlmyDataSource($dsn);
		}
		return $list[$id];
	}

	/**
	 * 得到 sqlmyAssistant 对象
	 * 
	 * @param  sqlmyDataSource  $ds
	 * @return sqlmyAssistant
	 */
	static function assistant(sqlmyDataSource $ds)
	{
		return sqlmyAssistant::instance($ds);
	}

	/**
	 * 执行 读 操作
	 * 
	 * @param sqlmyDataSource $ds
	 * @param string $mode 模式 [MODE_READ_GETALL,MODE_READ_GETROW,MODE_READ_GETONE,MODE_READ_GETCOL]
	 * @param mixed $args 参数[不同模式参数不同,缺省为sqlmy字符串]
	 * @param callback $cb 查询记录集的回调处理函数
	 * 
	 * @return mixed
	 */
	static function read(sqlmyDataSource $ds, $mode, $args, $cb=NULL)
	{
		$args = (array) $args;
		$sql = array_shift($args);// 缺省第一个参数是sql字符串
		
		switch ($mode)
		{
			case self::MODE_READ_GETALL: // array(sql,limit,counted),如果sql里面带了limit则不能使用counted
				$limit = array_shift($args);
				$counted = array_shift($args);
				
				$result = null;
				if ($counted)
				{
					$result = array(
						'total' => $ds->count($sql),
					);
				}
				if ($limit) $sql = $ds->sql_limit($sql, $limit);
				
				if (is_array($result))
				{
					$result['rows'] = ($result['total'] == 0) ? array() : $ds->all($sql);
				}
				else
				{
					$result = $ds->all($sql);
				}
				break;
			case self::MODE_READ_GETCOL:// array(sqlmy,col,limit,counted) col 下标从 0开始 为第一列
				$col = (int) array_shift($args);
				$limit = array_shift($args);
				$counted = array_shift($args);
				
				$result = null;
				if ($counted)
				{
					$result = array(
						'total' => $ds->count($sql),
					);
				}
				if ($limit) $sql = $ds->sql_limit($sql, $limit);
				if (is_array($result))
				{
					$result['rows'] = ($result['total'] == 0) ? array() : $ds->col($sql,$col);
				}
				else
				{
					$result = $ds->col($sql,$col);
				}
				break;
			case self::MODE_READ_GETROW:
				$result = $ds->row($sql);
				break;
			case self::MODE_READ_GETONE:
				$result = $ds->one($sql);
				break;			
			case self::MODE_READ_ALLCOUNT:
				$result = $ds->count($sql);
				break;
			default:
				throw new sqlmyError("invalid read mode: {$mode}");
		}
		
		return (empty($cb) || !is_callable($cb)) ? $result : call_user_func_array($cb,array($result));
	}
	
	/**
	 * 执行 更新/删除 操作
	 *
	 * @param sqlmyDataSource $ds
	 * @param string $mode 模式 [MODE_WRITE_INSERT,MODE_WRITE_UPDATE,MODE_WRITE_DELETE]
	 * @param mixed $args 参数[不同模式参数不同,缺省为sqlmy字符串]
	 * @param callback $cb 查询结果集的回调处理函数
	 * 
	 * @return mixed
	 */
	static function write(sqlmyDataSource $ds, $mode, $args, $cb=NULL)
	{
		$args = (array) $args;		
		$sql = array_shift($args);// 缺省第一个参数是sql字符串
		
		$ds->execute($sql);
		
		switch ($mode)
		{			
			case self::MODE_WRITE_INSERT: // 插入操作可选 得到主键标识
				$id = array_shift($args);
				$result = $id ? $ds->insert_id() : $ds->affected_rows();
				break;
			case self::MODE_WRITE_UPDATE:
			case self::MODE_WRITE_DELETE:
				$result = $ds->affected_rows();
				break;
			default:
				throw new sqlmyError("invalid write mode: {$mode}");
		}
		
		return (empty($cb) || !is_callable($cb)) ? $result : call_user_func_array($cb,array($result));
	}

}

class sqlmyError extends Exception {}

/**
 * sqlmy 数据源类
 *
 * 配置信息说明
 *
 * 1. type = mysql/mariadb 
 * {
 * 		dbpath: mysql:host=${host};port=${port};dbname=${database}
 * 		initcmd: [
 * 			SET NAMES '${charset}',
 * 		]
 * }
 * 
 * 2. type = pgsql 
 * {
 * 		dbpath: pgsql:host=${host};port=${port};dbname=${database}
 * 		initcmd: [
 * 			SET NAMES '${charset}',
 * 		]
 * }
 *
 * 3. type = sybase 
 * {
 * 		dbpath: sybase:host=${host};port=${port};dbname=${database}
 * 		initcmd: [
 * 			SET NAMES '${charset}',
 * 		]
 * }
 *
 * 4. type = sqlite 
 * {
 * 		dbpath: sqlite:${file}
 * 		initcmd: [
 * 			
 * 		]
 * }
 *
 * 5. type = mssql 
 * {
 * 		Windows:
 * 		dbpath: sqlsrv:server=${host};port=${port};database=${database}
 *
 * 		Linux:
 * 		dbpath: dblib:host=${host};port=${port};dbname=${database}
 * 		
 * 		initcmd: [
 * 			SET QUOTED_IDENTIFIER ON,
 * 			SET NAMES '${charset}',
 * 		]
 * }
 *
 * 如果要使用持久连接,可以配置 attr 参数
 *
 * attr: [
 * 		PDO::ATTR_PERSISTENT => TRUE,
 * ]
 *
 */
class sqlmyDataSource
{
    
    /**
     * @var int
     */
    private $query_count = 0;

    /**
     * @var PDO
     */
    private $db;
    
    /**
     * @var int
     */
    private $affected_rows = 0;

    function __construct(array $dsn)
    {
    	$this->dsn = $dsn;
        $this->connected = false;
        $dsn = null;
    }

    /**
     * 解析 DSN 配置信息并返回标识ID
     * 
     * @param  array  $dsn
     * @return string
     */
    static function dsn(array $dsn)
    {
    	foreach (array('type','dbpath','login','password') as $key)
        {
        	if ( empty($dsn[$key]) )
        	{
        		throw new sqlmyError("db config invalid: {$key}");
        	}
        }

        if ( empty( $dsn['attr'] ) || !is_array( $dsn['attr'] ) )
    	{
    		$dsn['attr'] = array();
    	}

    	# force use ASSOC array
    	$dsn['attr'][PDO::ATTR_DEFAULT_FETCH_MODE] = PDO::FETCH_ASSOC;

    	if ( empty( $dsn['initcmd'] ) || !is_array( $dsn['initcmd'] ) )
    	{
    		$dsn['initcmd'] = array();
    	}
    	
    	# sqlmy monitor
    	$dsn['monitor'] = empty( $dsn['monitor'] ) || !is_callable( $dsn['monitor'] ) ? false : $dsn['monitor'];

    	$dsn['id'] = "{$dsn['dbpath']}@{$dsn['login']}";

        return $dsn;
    }
    
	function id()
	{
		return $this->dsn['id'];
	}
	
	function connect()
    {
        if ($this->connected) return;
        
        $this->db = new PDO($this->dsn['dbpath'], $this->dsn['login'], $this->dsn['password'], $this->dsn['attr']);
        
        if ($this->db === FALSE)
        {
        	throw new sqlmyError("db connect failed: " . $this->dsn['id']);
        }
        foreach ($this->dsn['initcmd'] as $cmd)
        {
	        $result = $this->db->exec($cmd);
	        if ($result === false)
	        {
	        	$error = $this->db->errorInfo();
	        	throw new sqlmyError("db query failed: " . print_r(array($this->dsn['id'], $cmd, $error),true));
	        }
        }
        $this->connected = true;
    }
    
    function close()
    {
        if (empty($this->dsn['attr'][PDO::ATTR_PERSISTENT]) && !is_null($this->db))
        {
            $this->db = null;
            $this->connected = false;
            $this->query_count = 0;
        }
    }

    function begin()
    {
    	if (!$this->connected) $this->connect();
        $this->db->beginTransaction();
    }

    function commit()
    {
    	if ($this->connected) return $this->db->commit();
    	throw new sqlmyError("db connected lost: {$this->dsn['id']}");        
    }

    function rollback()
    {
    	if ($this->connected) return $this->db->rollBack();
    	throw new sqlmyError("db connected lost: {$this->dsn['id']}");
    }
	
    function qstr($value)
	{
		if (is_int($value) || is_float($value)) { return $value; }
		if (is_bool($value)) { return $value ? 1 : 0; }
		if (is_null($value)) { return 'NULL'; }
		
		if (!$this->connected) $this->connect();
		return $this->db->quote($value);
	}
	
	function insert_id()
	{
		if ($this->connected) return $this->db->lastInsertId();
    	throw new sqlmyError("db connected lost: {$this->dsn['id']}");
	}
	
    function affected_rows()
    {
    	return $this->affected_rows;
    }

    private function monitor($sql)
    {
    	if ( $this->dsn['monitor'] ){
        	call_user_func_array($this->dsn['monitor'], array($sql, $this->dsn['id']));
        }
    }
	
    function execute($sql, array $args = null)
    {
    	$this->affected_rows = 0;
    	
       	if (!empty($args)) {
       		$sql = sqlmyHelper::bind($this, $sql, $args);
		}

        if (!$this->connected) $this->connect();
        
        $result = $this->db->exec($sql);
        $this->monitor($sql);
        $this->query_count++;

        if ($result === false)
        {
        	$error = $this->db->errorInfo();
        	
        	$this->db_error($error);
        	throw new sqlmyError("db query failed: " . print_r(array($this->dsn['id'], $sql, $error),true));
        }
        $this->affected_rows = $result;    	
    }

    private function db_error($error)
    {
    	if (is_array($error)) {
    		$err_text = "error: " . print_r($error, true);
    		$this->monitor($err_text);
    		
    		// 额外处理数据库链接丢失
    		$n2 = empty($error[1]) ? '' : $error[1];
    		$n3 = empty($error[2]) ? '' : strtolower(trim($error[2]));

    		if ($n3 == 'mysql server has gone away') {
    			$this->close();
    		}
    	}
    }
    
    /**
     * @return PDOStatement
     */
    private function query($sql)
    {    	
    	if (!$this->connected) $this->connect();
    	
    	$statement = $this->db->query($sql);
        $this->monitor($sql);
        $this->query_count++;
        
        if ($statement !== false) return $statement;
        
    	$error = $this->db->errorInfo();
    	$this->db_error($error);
    	throw new sqlmyError("db query failed: " . print_r(array($this->dsn['id'], $sql, $error),true));
    }
    
	function all($sql)
    {
        $res = $this->query($sql);
        /* @var $res PDOStatement */
        
        $val = $res->fetchAll(PDO::FETCH_ASSOC);
        $res = null;
        return $val;
    }
	
    function one($sql)
    {
    	$res = $this->query($sql);
        /* @var $res PDOStatement */
    	
    	$val = $res->fetchColumn(0);
    	$res = null;
        return $val;
    }
    
    function row($sql)
    {
    	$res = $this->query($sql);
        /* @var $res PDOStatement */
    	
    	$val = $res->fetch(PDO::FETCH_ASSOC);
    	
        $res = null;
        return $val;
    }
	
    function col($sql, $col=0)
    {
        $res = $this->query($sql);
        /* @var $res PDOStatement */
        
        $val = $res->fetchAll(PDO::FETCH_COLUMN,$col);
        $res = null;
        
        return $val;
    }

	function count($sql)
	{
		return (int) $this->one("SELECT COUNT(*) FROM ( $sql ) AS t");
	}
	
	function sql_limit($sql, $limit)
	{
		if (empty($limit)) return $sql;

		if (is_array($limit))
		{
			list($skip, $l) = $limit;
	        $skip = intval($skip);
          	$limit = intval($l);
	    }
	    else
	    {
	      	$skip = 0;
	       	$limit = intval($limit);
	    }
		
	    switch ( $this->dsn['type'] )
    	{
    		case 'sqlmyite':
    		case 'mariadb':
    		case 'mysql':
    			return "{$sql} LIMIT {$skip}, {$limit}";
    		case 'pgsql':
    			return "{$sql} LIMIT {$limit} OFFSET {$skip}";
    		case 'sybase':
    		case 'mssql':
    			return $sql;
    	}
	}
	
}

class sqlmyHelper
{
	
	static function bind(sqlmyDataSource $ds, $sql, array $inputarr)
	{
		$arr = explode('?', $sql);
        $sqlmy = array_shift($arr);
        foreach ($inputarr as $value) {
            if (isset($arr[0])) {
                $sqlmy .= $ds->qstr($value) . array_shift($arr);
            }
        }
        return $sqlmy;
	}
	
	static function parse_cond(sqlmyDataSource $ds, $cond, $dash=false)
	{
		static $equal_in = array('=','IN','NOT IN');
        static $between_and = array('BETWEEN_AND','NOT_BETWEEN_AND');
        
		if (empty($cond)) return '';
 		
		// 如果是字符串，则假定为自定义条件
        if (is_string($cond)) return $cond;
	
        // 如果不是数组，说明提供的查询条件有误
        if (!is_array($cond)) return '';
        
        
 		$where = '';$expr = '';
 		
 		/**
         * 不过何种条件形式，一律为  字段名 => (值, 操作, 连接运算符, 值是否是sqlmy命令) 的形式
         */
 		foreach ($cond as $field => $d) 
 		{
 			
 			$expr = 'AND';
            
 			if (!is_string($field)) {
 				continue;
 			}
 			if (!is_array($d)) {
                // 字段名 => 值
            	$d = array($d);
            }
            reset($d);
            // 第一个元素是值
 			if (!isset($d[1])) { $d[1] = '='; }
            if (!isset($d[2])) { $d[2] = $expr; }
            if (!isset($d[3])) { $d[3] = false; }
			
            list($value, $op, $expr, $is_cmd) = $d;
            
            $op = strtoupper(trim($op));            
            $expr = strtoupper(trim($expr));
            
            if (is_array($value))
            {
 				
 				do {
 					if (in_array($op, $equal_in)){
 						if ($op == '=') $op = 'IN';
 						$value = '(' . implode(',',array_map(array($ds, 'qstr'),$value)) . ')';
 						break;
 					} 					
 					
	 				if (in_array($op, $between_and)){	 					
	 					$between = array_shift($value);
	 					
	 					$and = array_shift($value);
	 					$value = sprintf('BETWEEN %s AND %s',$ds->qstr($between),$ds->qstr($and));
	 					$op = 'NOT_BETWEEN_AND' == $op ? 'NOT' : '';// 此处已经串在 $value 中了
	 					break;
	 				}
 					
	 				// 一个字段对应 多组条件 的实现,比如 a > 15 OR a < 5 and a != 32
	 				// 'a' => array(  array( array(15,'>','OR'),array(5,'<','AND'), array(32,'!=') ) , 'FIELD_GROUP')
 					if ($op == 'FIELD_GROUP'){
 						$kv = array();
 						foreach($value as $k => $v){
 							$kv[":+{$k}+:"] = $v;
 						}
 						$value = self::parse_cond($ds,$kv,true);
 						
 						foreach(array_keys($kv) as $k){
 							$value = str_ireplace($k,$field,$value);
 						}
 						
 						$field = $op = '';// 此处已经串在 $value 中了
	 					break;
 					}
 					
 				} while(false);
 				
 				$is_cmd = true;
 			}
 			
 			if (!$is_cmd) {
				$value = $ds->qstr($value);
			}
			$where .= "{$field} {$op} {$value} {$expr} ";
 		}
 		
        $where = substr($where, 0, - (strlen($expr) + 2));
        return $dash ? "({$where})" : $where;
	}
		
	static function qtable($table)
	{
		return "`{$table}`";
	}
	
	static function qfield($field, $table = null)
	{
		$field = ($field == '*') ? '*' : "`{$field}`";
		return $table != '' ? self::qtable($table) . '.' . $field : $field;
	}
		
    static function qfields($fields, $table = null, $get_arr = false)
    {
        if (!is_array($fields)) {
            $fields = explode(',', $fields);
            $fields = array_map('trim', $fields);
        }
        $result = array();
        foreach ($fields as $field) {
            $result[] = self::qfield($field, $table);
        }
       
        return $get_arr ? $result : implode(', ', $result);
    }
	
    static function placeholder(&$inputarr, $fields = null)
    {
        $holders = array();
        $values = array();
        if (is_array($fields)) {
            $fields = array_change_key_case(array_flip($fields), CASE_LOWER);
            foreach (array_keys($inputarr) as $key) {
                if (!isset($fields[strtolower($key)])) { continue; }
                $holders[] = '?';
                $values[$key] =&$inputarr[$key];
            }
        } else {
            foreach (array_keys($inputarr) as $key) {
                $holders[] = '?';
                $values[$key] =&$inputarr[$key];
            }
        }
        return array($holders, $values);
    }
    
    static function placeholder_pair(&$inputarr, $fields = null)
    {
        $pairs = array();
        $values = array();
        if (is_array($fields)) {
            $fields = array_change_key_case(array_flip($fields), CASE_LOWER);
            foreach (array_keys($inputarr) as $key) {
                if (!isset($fields[strtolower($key)])) { continue; }
                $qkey = self::qfield($key);
                $pairs[] = "{$qkey}=?";
                $values[$key] =&$inputarr[$key];
            }
        } else {
            foreach (array_keys($inputarr) as $key) {
                $qkey = self::qfield($key);
                $pairs[] = "{$qkey}=?";
                $values[$key] =&$inputarr[$key];
            }
        }
        return array($pairs, $values);
    }

    static function timestamp($timestamp)
    {
        return date('Y-m-d H:i:s', (int) $timestamp);
    }
    
}

class sqlmyAssistant
{

	/**
	 * @var sqlmyDataSource
	 */
	private $ds = null;

	private function __construct() {}

	/**
	 * 得到 sqlmyAssistant 对象
	 * 
	 * @param  sqlmyDataSource $ds
	 * @return sqlmyAssistant
	 */
	static function instance(sqlmyDataSource $ds)
	{
		static $obj = null;
		if ( !$obj )
		{
			$obj = new self();
		}
		$obj->ds = $ds;
		return $obj;
	}

	private function ds()
	{
		if ( empty($this->ds) ) throw new sqlmyError("invalid ds obj");
		$ds = $this->ds;
		$this->ds = null;
		return $ds;
	}
    
    /**
     * 从表中检索符合条件的一条记录
     *
     * @param string $table
     * @param mixed $cond
     * @param string $fields
     * @param string $sort
     * 
     * @return array
     */
    function select_row($table ,$cond=null ,$fields='*', $sort=null)
    {
    	$ds = $this->ds();
		$cond = sqlmyHelper::parse_cond($ds,$cond);
		if ($cond) $cond = "WHERE {$cond}";
		if ($sort) $sort = "ORDER BY {$sort}";
		
		$qfields = sqlmyHelper::qfields($fields,$table);
		
		$result = sqlmy::read($ds, sqlmy::MODE_READ_GETROW, array(
				"SELECT {$qfields} FROM {$table} {$cond} {$sort}"
			));
		return $result;
	}
    
	/**
	 * 从表中检索符合条件的多条记录
	 *
	 * @param string $table
	 * @param mixed $cond
	 * @param string $fields
	 * @param string $sort
	 * @param int|array $limit 数组的话遵循格式 ( offset,length ) 
	 * @param bool $calc 计算总个数 
	 * 
	 * @return array
	 */
	function select($table, $cond=null, $fields='*', $sort=null, $limit=null, $calc=false)
	{
		$ds = $this->ds();
		$cond = sqlmyHelper::parse_cond($ds,$cond);
		if ($cond) $cond = "WHERE {$cond}";
		if ($sort) $sort = "ORDER BY {$sort}";
		
		$qfields = sqlmyHelper::qfields($fields,$table);
		$table = sqlmyHelper::qtable($table);
		
		$result = sqlmy::read($ds, sqlmy::MODE_READ_GETALL, array(
				"SELECT {$qfields} FROM {$table} {$cond} {$sort}",
				empty($limit) ? false : $limit,
				$calc
			));
		return $result;
	}

    /**
     * 统计符合条件的记录的总数
     *
     * @param string $table
     * @param mixed $cond
     * @param string|array $fields
     * @param boolean $distinct
     *
     * @return int
     */
    function count($table, $cond=null, $fields='*', $distinct=false)
    {
    	$ds = $this->ds();
    	if ($distinct) $distinct = 'DISTINCT ';
    	
    	$cond = sqlmyHelper::parse_cond($ds,$cond);
    	if ($cond) $cond = "WHERE {$cond}";
		
    	if (is_null($fields) || trim($fields) == '*') {
            $fields = '*';
        } 
        else {
            $fields = sqlmyHelper::qfields($fields,$table);
        }
        
        $table = sqlmyHelper::qtable($table);
        
        $result = (int) sqlmy::read($ds, sqlmy::MODE_READ_GETONE, array(
				"SELECT COUNT({$distinct}{$fields}) FROM {$table} {$cond}"
			));
		return $result;
    }

    /**
     * 插入一条记录
     *
     * @param string $table
     * @param array $row
     * @param bool $pkval 是否获取插入的主键值
     *
     * @return mixed
     */
    function insert($table, array $row, $pkval=false)
    {
    	$ds = $this->ds();
		list($holders, $values) = sqlmyHelper::placeholder($row);
        $holders = implode(',', $holders);
        
        $fields = sqlmyHelper::qfields(array_keys($values));        
        $table = sqlmyHelper::qtable($table);
		
        $result = sqlmy::write($ds, sqlmy::MODE_WRITE_INSERT, array(
				sqlmyHelper::bind($ds, "INSERT INTO {$table} ({$fields}) VALUES ({$holders})", $row),
				$pkval
			));
		return $result;
	}

    /**
	 * 更新表中记录
	 *
	 * @param string $table
	 * @param array $row
	 * @param mixed $cond 条件
	 * 
	 * @return int
	 */
	function update($table, array $row, $cond=null)
	{
		$ds = $this->ds();
		if ( empty($row) ) return false;
		
        list($pairs, $values) = sqlmyHelper::placeholder_pair($row);
        $pairs = implode(',', $pairs);
        
        $table = sqlmyHelper::qtable($table);
		
        $sql = sqlmyHelper::bind($ds, "UPDATE {$table} SET {$pairs}", $row);
        
        $cond = sqlmyHelper::parse_cond($ds, $cond);
        if ($cond) $sql .= " WHERE {$cond}";
        
        $result = sqlmy::write($ds, sqlmy::MODE_WRITE_UPDATE, array(
			 $sql
		));
		return $result;
	}

    /**
	 * 删除 表中记录
	 * 
	 * @param string $table
	 * @param mixed $cond
	 * 
	 * @return int
	 */
	function del($table, $cond=null)
	{
		$ds = $this->ds();
		$cond = sqlmyHelper::parse_cond($ds, $cond);
		$table = sqlmyHelper::qtable($table);
		
		$sql = "DELETE FROM {$table} " . (empty($cond) ? '' : "WHERE {$cond}");
		
		$result = sqlmy::write($ds, sqlmy::MODE_WRITE_DELETE, array(
				$sql
			));
		return $result;
	}

	/**
	 * 向表中 某字段的值做 "加"运算
	 *
	 * @param string $table
	 * @param string $field
	 * @param int $incr
	 * @param mixed $cond
	 * 
	 * @return int
	 */
    function incr_field($table, $field, $incr = 1, $cond=null)
    {
    	$incr = (int)$incr;
        if ($incr == 0) return false;
        
        $ds = $this->ds();
        $field = sqlmyHelper::qfield($field, $table);
        $cond = sqlmyHelper::parse_cond($ds,$cond);
        $sql = "UPDATE {$table} SET {$field} = {$field} + {$incr} " . (empty($cond) ? '' : "WHERE {$cond}");

        $result = sqlmy::write($ds, sqlmy::MODE_WRITE_UPDATE, array(
				$sql
			));
		return $result;
    }

}

/**
 * 简易的 主从 实现
 */
class sqlmyMS
{

	/**
	 * 主库数据源
	 * @var sqlmyDataSource
	 */
	private static $ds_master;

	/**
	 * 从库数据源
	 * @var sqlmyDataSource
	 */
	private static $ds_slaver;

	static function init(array $config)
	{
		self::$ds_master = sqlmy::ds($config['master']);
		self::$ds_slaver = sqlmy::ds($config['slaver']);
	}

	/**
	 * 返回主库对象
	 * 
	 * @return sqlmyMaster
	 */
	static function master()
	{
		static $master = null;
		if (is_null($master)) $master = new sqlmyMaster(self::$ds_master);
		return $master;
	}

	/**
	 * 返回主库对象
	 * 
	 * @return sqlmySlaver
	 */
	static function slaver()
	{
		static $slaver = null;
		if (is_null($slaver)) $slaver = new sqlmySlaver(self::$ds_slaver);
		return $slaver;
	}

}

/**
 * 从库 仅仅承载着 "读" 数据的功能
 */
class sqlmySlaver
{

	function __construct(sqlmyDataSource $ds)
	{
		$this->ds = $ds;
	}

	## 自己封装一些 读操作
	
    /**
     * 从表中检索符合条件的一条记录
     *
     * @param string $table
     * @param mixed $cond
     * @param string $fields
     * @param string $sort
     * 
     * @return array
     */
    function select_row($table, $cond=null, $fields='*', $sort=null)
    {
    	return sqlmy::assistant( $this->ds )->select_row($table, $cond, $fields,  $sort);
    }

    /**
	 * 从表中检索符合条件的多条记录
	 *
	 * @param string $table
	 * @param mixed $cond
	 * @param string $fields
	 * @param string $sort
	 * @param int|array $limit 数组的话遵循格式 ( offset,length ) 
	 * @param bool $calc 计算总个数 
	 * 
	 * @return array
	 */
	function select($table, $cond=null, $fields='*', $sort=null, $limit=null, $calc=false)
	{
		return sqlmy::assistant( $this->ds )->select($table, $cond, $fields, $sort, $limit, $calc);
	}

    /**
     * 统计符合条件的记录的总数
     *
     * @param string $table
     * @param mixed $cond
     * @param string|array $fields
     * @param boolean $distinct
     *
     * @return int
     */
    function count($table, $cond=null, $fields='*', $distinct=false)
    {
    	return sqlmy::assistant( $this->ds )->count($table, $cond, $fields, $distinct);
    }

    /**
	 * 执行 读 操作
	 * 
	 * @param string $mode 模式 [MODE_READ_GETALL,MODE_READ_GETROW,MODE_READ_GETONE,MODE_READ_GETCOL]
	 * @param mixed $args 参数[不同模式参数不同,缺省为sql字符串]
	 * @param callback $cb 查询记录集的回调处理函数
	 * 
	 * @return mixed
	 */
	function read($mode, $args, $cb=NULL)
	{
		return sqlmy::read( $this->ds, $mode, $args, $cb);
	}

}


/**
 * 主库 原则上仅仅承载着 "写" 数据的功能
 * * 但是在同步的过程中由于网络延迟造成数据不同步,避免脏数据的产生
 * * 或者 事务处理过程中也需要 "读" 
 */
class sqlmyMaster extends sqlmySlaver
{

	function __construct(sqlmyDataSource $ds)
	{
		$this->ds = $ds;
	}

	## 自己封装一些 写操作
	    
    /**
     * 插入一条记录
     *
     * @param string $table
     * @param array $row
     * @param bool $pkval 是否获取插入的主键值
     *
     * @return mixed
     */
    function insert($table, array $row, $pkval=false)
    {
    	return sqlmy::assistant( $this->ds )->insert($table, $row, $pkval);
	}

    /**
	 * 更新表中记录
	 *
	 * @param string $table
	 * @param array $row
	 * @param mixed $cond 条件
	 * 
	 * @return int
	 */
	function update($table, array $row, $cond=null)
	{
		return sqlmy::assistant( $this->ds )->update($table, $row, $cond);
	}

    /**
	 * 删除 表中记录
	 * 
	 * @param string $table
	 * @param mixed $cond
	 * 
	 * @return int
	 */
	function del($table, $cond=null)
	{
		return sqlmy::assistant( $this->ds )->del($table, $cond);
	}

	/**
	 * 向表中 某字段的值做 "加"运算
	 *
	 * @param string $table
	 * @param string $field
	 * @param int $incr
	 * @param mixed $cond
	 * 
	 * @return int
	 */
    function incr_field($table, $field, $incr = 1, $cond=null)
    {
    	return sqlmy::assistant( $this->ds )->incr_field($table, $field, $incr, $cond);
    }

    /**
	 * 执行 更新/删除 操作
	 *
	 * @param string $mode 模式 [MODE_WRITE_INSERT,MODE_WRITE_UPDATE,MODE_WRITE_DELETE]
	 * @param mixed $args 参数[不同模式参数不同,缺省为sql字符串]
	 * @param callback $cb 查询结果集的回调处理函数
	 * 
	 * @return mixed
	 */
	function write($mode, $args, $cb=NULL)
	{
		return sqlmy::write( $this->ds, $mode, $args, $cb);
	}

}

