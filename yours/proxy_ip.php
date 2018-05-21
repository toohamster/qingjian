<?php
 ini_set('display_errors', '1');

if(defined('E_DEPRECATED'))
error_reporting(E_ALL & ~E_NOTICE^E_DEPRECATED);
else
error_reporting(E_ALL & ~E_NOTICE);

 

//include '../system/library/class.Http_MultiRequst.php';
function curlp($url,$ref,$proxy){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url); 
	
	curl_setopt($ch, CURLOPT_REFERER, $ref);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
	//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
	curl_setopt($ch, CURLOPT_PROXY, $proxy);

	$cookie = 't=b5c1c7f39a2e7ade3ab14e23a87d7f9d; thw=cn; cna=bilGE9us2XQCAbZn93M22zJD; tg=0; l=AtDQjb1lxNLCKISlRKiWWMwQIBUisbTj; ali_ab=182.100.69.84.1523272421793.5; UM_distinctid=162ae2221fa1bc-023cd3e6218d27-683b0f7f-13c680-162ae2221fb138; miid=7585220751448887461; hng=CN%7Czh-CN%7CCNY%7C156; enc=%2B5gllcM4dyfiSlGO4UVFPZRIbsb4mqiFQct8aU61cJOcmEvPRZSXYRiexcCcD5dVrkeQOfVLcSH3%2FWv%2FQoYSyw%3D%3D; lgc=lxq73061; tracknick=lxq73061; cookie2=21590e525efec75c5908be7679bdc574; v=0; _tb_token_=757333761b373; dnk=lxq73061; mt=np=&ci=67_1; _cc_=VT5L2FSpdA%3D%3D; _mw_us_time_=1526558443172; _m_h5_tk=5b749c3645f7cc5e989c466e8f5868da_1526560965078; _m_h5_tk_enc=41718aa35722929a1e7b28d065787c36; uc3=nk2=D9syBSLMJ2g%3D&id2=Vy0WpgKoUho%3D&vt3=F8dBz499zqErEeB2RHc%3D&lg2=UtASsssmOIJ0bQ%3D%3D; existShop=MTUyNjU1ODQ0Nw%3D%3D; sg=187; csg=4f11742f; cookie1=AiLU5buHwFeM5qqCC%2Bjl2NVNJQI7aGneBufNfrI5MZA%3D; unb=41572778; skt=9d647bb039b59bbc; _l_g_=Ug%3D%3D; _nk_=lxq73061; cookie17=Vy0WpgKoUho%3D; isg=BNDQjB3Jvq7onWKVi3ps5Z6eoRjiMXORjNNjJcqhnCv-BXCvcqmEcya32c3l1Wy7; Hm_lvt_ba7c84ce230944c13900faeba642b2b4=1526537576,1526558449; Hm_lpvt_ba7c84ce230944c13900faeba642b2b4=1526558449; uc1=cookie16=VT5L2FSpNgq6fDudInPRgavC%2BQ%3D%3D&cookie21=W5iHLLyFeYFnNZKBCYQf&cookie15=V32FPkk%2Fw0dUvg%3D%3D&existShop=true&pas=0&cookie14=UoTeOLwBY7utPQ%3D%3D&cart_m=0&tag=8&lng=zh_CN;';

	$headers=array();
	$headers[] ='Cookie: '.$cookie;
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	
				
	$result =curl_exec($ch);
	$info = curl_getinfo($ch);
	$other_error='';
	$error = curl_error($ch); 
	curl_close($ch); 
	return $result;
}

function mget($urls){
	$headers=array();
	$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.143 Safari/537.36';
	$headers[] = 'X-Requested-With: XMLHttpRequest';
	$headers[] = 'Accept: application/json, text/javascript, */*; q=0.01';
	$headers[] = 'Referer: https://my.playstation.com/logged-in/trophies/public-trophies/';
		
	$options=array(
		CURLOPT_HTTPHEADER=>$headers,
		CURLOPT_SSL_VERIFYHOST=>FALSE,
		CURLOPT_SSL_VERIFYPEER=>FALSE,
	);
	
	/*$urls2=array();
	while(count($urls))$urls2[] = array_splice($urls,0,$per);		
	*/
	$m = new Http_MultiRequest($options);
	$m->setUrls($urls);
	$data = $m->exec();
	return $data;
}

/*得到精确時間*/
function getmicrotime() {
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
} 
function get_use_time($min=false,$reset=false) {
    global $time_start;
	static $time_start2;
	if(!$time_start2)$time_start2=$time_start;
    $time_end = getmicrotime();
    $times = $time_end - ($reset?$time_start2:$time_start);
    $times = sprintf('%.5f',$times);
    if($min==false) {
        $use_time =  "用时:". $times ."秒";
    }else {
        $use_time = $times;
    }
	$time_start2 = $time_end;
	
    return $use_time;
}
function curl_string ($url,$proxy)
{
    $user_agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; zh- CN; rv:1.9.0.5) Gecko/2008120122 Firefox/3.0.5 FirePHP/0.2.1"; 
    $ch = curl_init();
    curl_setopt ($ch, CURLOPT_PROXY, $proxy);
    curl_setopt ($ch, CURLOPT_URL, $url);//设置要访问的IP
    curl_setopt ($ch, CURLOPT_USERAGENT, $user_agent);//模拟用户使用的浏览器 
    @curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 ); // 使用自动跳转  
    curl_setopt ( $ch, CURLOPT_TIMEOUT, 120 ); //设置超时时间
    curl_setopt ( $ch, CURLOPT_AUTOREFERER, 1 ); // 自动设置Referer  

    //curl_setopt ($ch, CURLOPT_COOKIEJAR, 'c:\cookie.txt');
    curl_setopt ($ch, CURLOPT_HEADER, 1);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt ($ch, CURLOPT_TIMEOUT, 10);
    $result = curl_exec($ch);
	
    // Check if any error occured
    if( $result === false)
    {
        //error_log(date("H:i:s") . ' Curl 失败: ' . curl_error($ch) ." -- ". $proxy."\n", 3, MYMEDIA.'/log/'.date('Y-m-d').'_Err.log');
    }else{
       // error_log(date("H:i:s") . ' Curl 成功: ' . $proxy."\n", 3, MYMEDIA.'/log/'.date('Y-m-d').'_OK.log');
    }
	$result .= print_r(curl_getinfo($ch),true);
	$result .= print_r(curl_errno($ch),true);
	$result .= print_r(curl_error($ch),true);
	
    curl_close($ch);
    return $result;
}




$mtime = explode(' ', microtime());
$time_start = $mtime[1] + $mtime[0];
set_time_limit(0);
header("Content-Type: text/html; charset=utf-8");
if(isset($_GET['ip'])){
	$proxy= $_GET['ip'];
	
$ref='https://item.taobao.com/item.htm?id=41104924035';

//含有：defaultModel
// $url='https://mdskip.taobao.com/core/initItemDetail.htm?queryMemberRight=true&isAreaSell=true&isForbidBuyItem=false&tmallBuySupport=true&isUseInventoryCenter=true&itemId=41104924035&showShopProm=false&cartEnable=true&addressLevel=3&tryBeforeBuy=false&cachedTimestamp=1461969822054&isRegionLevel=true&sellerPreview=false&isSecKill=false&household=false&isApparel=false&service3C=true&offlineShop=false';
//$url ='http://www.panli.com/Crawler.aspx?purl=https%3A%2F%2Fdetail.tmall.com%2Fitem.htm%3Fid%3D41104924035';

$url='https://hws.alicdn.com/cache/wdetail/5.0/?id=557610252878';//含有：wdetail
$url='https://h5api.m.taobao.com/h5/mtop.taobao.detail.getdetail/6.0/?jsv=2.4.11&appKey=12574478&t=1523935666735&sign=&api=mtop.taobao.detail.getdetail&v=6.0&ttid=2017%40htao_h5_1.0.0&type=jsonp&dataType=jsonp&callback=mtopjsonp1&data=%7B%22exParams%22%3A%22%7B%5C%22countryCode%5C%22%3A%5C%22CN%5C%22%7D%22%2C%22itemNumId%22%3A%2216204910274%22%7D';
$url='https://s.taobao.com/search?q=&cat=0&style=list&spm=1&cd=false&tfsid=TB1rPiyNpXXXXaMaXXXXXXXXXXX&app=imgsearch';

	$c =  curlp($url,$ref,$proxy);
	
	if(strpos($c,'wdetail')){
		echo 'ok';
		echo '<script>';
		echo 'parent.ips[parent.ips.length]=\''.$proxy.'\';';
		echo 'parent.document.getElementById("iparea").innerHTML = parent.ips.join("<br>");';
		echo '</script>';
        
	}

	if(strpos($c,'mtopjsonp1')){
		echo 'ok';
		echo '<script>';
		echo 'parent.ips[parent.ips.length]=\''.$proxy.'\';';
		echo 'parent.document.getElementById("iparea").innerHTML = parent.ips.join("<br>");';
		echo '</script>';
        
	}

	if(strpos($c,'panli')){
		$c1 = explode('var crawlProduct =',$c);
		$c2 = explode('</script>',$c1[1]);
		$c = $c2[0];
	}
	echo get_use_time();
	echo '<br>';
	echo $c;
	
exit();	
	
}

$url='http://115.29.136.51/get.php?tid=1043221949580811&num=5000&ports=8088&area_type=1&nport=&speed=100';
$url= 'http://www.hutoudaili.net/get.php?tid=1368677608017827&num=2000&operator=tel&speed=1000&anonymity=3&area_type=1&area=%E6%9D%AD%E5%B7%9E&order=1&style=1';

//while(true){
//	$list = file_get_contents($url);
//	file_put_contents('ip/ip'.time().'.txt',$list);
//	sleep(5);
//}
$url = '';$url_test='';
$list='';
$myip = $_SERVER['REMOTE_ADDR'];
if(!empty($_POST['url'])||!empty($_POST['iplist'])){
	$url = $_POST['url'];
	$iplist = $_POST['iplist'];
	$url_test = $_POST['url_test'];
	
	if($url )$iplist='';
	if($iplist)$list =$iplist;
	else $list = file_get_contents($url);
	//$list = file_get_contents('ip/ip.txt');
	//exit();
	//echo $list;
	ob_start();
	ob_end_flush();
	ob_implicit_flush(true);
	echo str_pad('',4096); 
	flush();

}



?>
<form method="post">
  <p>代理IP列表(URL)：
    <input name="url" value="<?php echo $url?>" size="30"><br>
    代理IP列表(空格分隔)：
  <textarea name="iplist"><?php echo $list?></textarea>
  </p>
  <p>测试URL：
    <input name="url_test" value="<?php echo $url_test?>" size="30">
    <br>
    免费代理IP提供网址：http://www.xicidaili.com/  ,http://www.goubanjia.com/(你当前IP:<?php echo $myip?>)
  </p>
  <p>
    <input type="submit">
  </p>
</form>
<div id="iparea"></div>
<script>
ips=[];
</script>
<?php
//
if($list){
$url = 'http://1111.ip138.com/ic.asp';
// $url ='http://s.taobao.com/search?q=%E8%89%BE%E6%96%87%E9%A6%A8&js=1&style=grid&stats_click=search_radio_all%253A1&initiative_id=staobaoz_20150305&dc=1&app=shopsearch&q=%E8%89%BE%E6%96%87%E9%A6%A8&js=1&style=grid&stats_click=search_radio_all%253A1&initiative_id=staobaoz_20150305&dc=1';

// $url ='http://www.panlishop.com/product/detail/521245709864.html';	
$url = 'http://api.onebound.cn/taobao/tip.php';
$url = 'http://api.onebound.cn/taobao/tools/ip.php';



$closed='...';

$opened='<font color=red>此端口目前处于打开状态！</font>';

$close="<font color=\"gray\">关闭</font>";

$open="<font color=green>打开</font>";
		
//$list = explode(" ",trim($list));
		
$list = explode("\r\n",trim($list));
$ok = array();
?><table style='width:100%'>
<thead>
<tr>
<th>IP</th>
<th>端口</th>
<th>端口状态</th>
<th>代理状态</th>
<th>速度(秒)</th>
<th>测试响应</th>
<th>显示IP</th>
<th>PRICE</th>
</tr>
</thead>
<?php 	
			foreach($list as $k=>$proxy){
				list($ip,$port)=explode(':',$proxy);
				$status = @fsockopen($ip, $port, $errno, $errstr, 1);
				
				if (!$status) {
				
				echo  "<tr bgcolor=\"white\"><td align=\"center\">".$ip."</td><td>".$port."</td><td align=\"center\">".$close."</td><td>".$closed."</td><td></td><td></td><td></td></tr>";
				
				} else {
					$s='';
					$rr = curl_string($url,$proxy);
					$rr2 = '';
					$rip = '';
					
					if($url_test )$rr2 = curl_string($url_test,$proxy);
					if(strpos($rr,'"HTTP_HOST":"api.onebound.cn"')!==false){
						$s = ('正常');
						if(strpos($rr,'HTTP_X_FORWARDED_FOR')===false){
							$s .=('优秀');
						}
						$ok[]=$proxy;
					}
					if(strpos($rr,'{"HTTP_HOST"')!==false){
						list(,$j)=explode('{',$rr);
						list($j)=explode('}',$j);
						
						$j=json_decode('{'.$j.'}',true);
						$rip =$j['REMOTE_ADDR'];
						if($j['HTTP_X_FORWARDED_FOR']) $rip .='('.$j['HTTP_X_FORWARDED_FOR'].')';
						//HTTP_X_REAL_IP
					}
					if($rr2)$rr=$rr2."\r\n=====\r\n".$rr;
					$rr = '<textarea style="width:400px">'.htmlspecialchars($rr).'</textarea>';
					if(strpos($rr,'<center>')!==false){
////						$tmp = explode('<center>',$rr);
////						
////						$tmp = explode('</center>',$tmp[1]);
////						// var_dump($tmp);exit();
////						 $rr = iconv('GBK','UTF-8',$tmp[0]);
////							
					}else{
						//$rr='Error';	
					}
					$tt =  get_use_time(true,true);//.'/'.get_use_time(true);
//					
					$rip2 = '<iframe src="?ip='.$ip.':'.$port.'"></iframe>';
				echo  "<tr bgcolor=\"#F4F7F9\"><td align=\"center\">".$ip."</td><td>".$port."</td><td align=\"center\">".$open."</td><td>".$s."</td><td>".$tt."</td><td>".$rr."</td><td>".$rip."</TD><td>".$rip2."</TD></tr>";
//				
				}
				flush();
				
			} 
			echo  "</table>";
?>
<h2>可用的代理IP列表：</h2>
<?php	
$string = '<?php 
return '.var_export($ok,true).';';

$file = '../runtime/cache/config_proxy_https.php';

$len= file_put_contents($file, $string);
echo '写入：'.$len.'<br>';
foreach($ok as $o)
echo 'http://'.$o.'<br>';

}
?>