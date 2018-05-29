<?php
require_once dirname(__FILE__) .'/lib/Arrays.php';
require_once dirname(__FILE__) .'/lib/Unirest.php';
require_once dirname(__FILE__) .'/lib/Sqlmy.php';

class P
{

	const COM_NUM = 100;
	const IP_GETURL = 'http://quick.lianzh.com/_tmp/clientip.php';

	static $debugIs = false;

	static $clientIp = '127.0.0.1';

	static $aliveProxyData = array();

	static $rulers = array();

	static function dump($vars, $label = '', $return = false)
	{
	    $content = "\n";
	    if ($label != '') {
	        $content .= "[{$label} :]\n";
	    }
	    $content .= print_r($vars, true);
	    $content .= "\n\n";

	    if ($return) {
	        return $content;
	    }
	    echo $content;
	}

    static function curl($proxy, $method, $url, $params = array(), $headers = array(), $timeout = 10)
    {
    	
        Unirest_Request::verifyPeer(false);
        if ($timeout) {
            Unirest_Request::timeout($timeout);// 修正缺省超时时间
        }
        $method = trim(strtolower($method));

        $debugInfo = array(
            'request' => array(
                'method' => $method,
                'url' => $url,
                'headers' => $headers,
                'params' => $params,
            ),
            'response' => '',
        );

        if (!empty($proxy) && is_array($proxy)) {
        	if (empty($proxy['type'])) $proxy['type'] = CURLPROXY_HTTP;
        	
        	Unirest_Request::proxy($proxy['address'], $proxy['port'], $proxy['type'], $proxy['tunnel']);
        }

        try {
            switch ($method) {
                case 'post':
                    $response = Unirest_Request::post($url, $headers, $params);
                    break;
                default:
                    $response = Unirest_Request::get($url, $headers, $params);
                    break;
            }
        } catch (Exception $ex) {
            $debugInfo['response'] = $ex->getMessage();

            if (P::$debugIs)
            {
                P::dump($debugInfo);
            }

            throw new Exception($ex->getMessage());
        }

        $debugInfo['response'] = $response;

        if (P::$debugIs)
        {
            P::dump($debugInfo);
        }

        return $response->raw_body;
    }

    static function proxyConfig($address, $port = 1080, $type = CURLPROXY_HTTP, $tunnel = false)
    {
    	// type options are CURLPROXY_HTTP, CURLPROXY_HTTP_1_0 CURLPROXY_SOCKS4, CURLPROXY_SOCKS5, CURLPROXY_SOCKS4A and CURLPROXY_SOCKS5_HOSTNAME
    	return array(
    			'address' => $address,
    			'port' => $port,
    			'type' => $type,
    			'tunnel' => $tunnel,
    		);
    }

    static function fetchProxys($length=100, $longlife=20)
    {
    	static $url = 'http://tvp.daxiangdaili.com/ip/';

    	$params = array(
    			'tid'	=> '559913587683584',
    			'num'	=> intval($length),
    			'filter'	=> 'on',
    			'longlife'	=> intval($longlife),

    		);

    	$data = [];

    	try {
    		$body = self::curl(false, 'get', $url, $params, array(), 10);

    		if (!empty($body)) {
    			$data = Arrays::normalize($body, "\n");
    		}

    		self::outputln($body);
    	}
    	catch(Exception $ex) {
    		self::outputln(__METHOD__. ':' . __LINE__);
    		self::outputln('Error: ' . $ex->getMessage());
    	}

    	return $data;
    }

    static function outputln($str)
    {
    	fwrite(STDOUT, $str.PHP_EOL);
    }

    static function setRulers($rulers=array())
    {
    	if (is_array($rulers)) {

    		$data = array();

    		foreach ($rulers as $ruler) {

    			if (empty($ruler['id'])) {
    				$ruler['id'] = md5($ruler['url']);
    			}

    			$data[ $ruler['id'] ] = $ruler;
    		}

    		// 处理规则
    		self::$rulers = $data;
    	}
    }

    static function fetchAliveProxyData()
    {
    	// 从表中提取 活动的代理列表数据
    	// 并将数据详情放置到 self::$aliveProxyData 中
    	
    	static $comTs = 300; // 5分钟之内可用

    	$ltTime = time() - $comTs;
    	$ltDate = date('Y-m-d H:i:s', $ltTime);

    	// 提取出每个规则均符合的代理数量

    	$data = array();

    	foreach (self::$rulers as $ruleid => $ruler) {

    		$cond = array(
    			'ruleid'	=> $ruleid,
    			'status'	=> 1,
    			'updated_at'	=> array($ltDate, '>=')
    		);

    		$count = sqlmyMaster()->count('qingjianproxys', $cond, 'id');

    		$data[$ruleid] = $count;
    	}

    	self::$aliveProxyData = $data;
    }

    static function checkMustGetAliveProxy()
    {
    	// 检查 self::$aliveProxyData 中是否有需要增补的代理列表
    	foreach (self::$aliveProxyData as $ruleid => $pc) {
    		if ($pc < P::COM_NUM) return true;
    	}

    	return false;
    }

    static function execProxyCrawel()
    {
    	// 检查 self::$aliveProxyData 缺少的规则对应的代理数量
    	// 从而实现自动专用增补支持

    	// 先计算出需要增补的规则
    	$addRulers = array();
    	foreach (self::$aliveProxyData as $ruleid => $pc) {
    		if ($pc < P::COM_NUM) {
    			$addRulers[$ruleid] = P::COM_NUM - $pc;
    		}
    	}

    	if (!empty($addRulers)) {

    		$proxyHosts = self::fetchProxys(100);

    		foreach ($proxyHosts as $host) {

    			if (empty($addRulers)) break;

    			foreach ($addRulers as $ruleid => $pc) {

    				$bool = self::crawel($host, $ruleid);
    				if ($bool == true) {
    					$pc ++;
    				}

    				if (P::COM_NUM <= $pc) {
    					unset($addRulers[$ruleid]);
    				}
    			}

			}

    	}
    }

    static function crawel($host, $ruleid)
    {
    	self::outputln(__METHOD__ . $host . $ruleid);

    	$its = Arrays::normalize($host, ':');
    	reset($its);
    	if (empty($its[1])) $its[1] = 80;

    	$proxyAdderss = $its[0];
    	$proxyPort = $its[1];

    	$proxy = self::proxyConfig($proxyAdderss, $proxyPort);

    	try {

    		$httpbody = self::curl($proxy, 'get', P::IP_GETURL, array(), array(), 20);

    		$httpbody = trim($httpbody);

    		self::outputln("{$httpbody} == {$proxyAdderss}");

    		if ($httpbody == $proxyAdderss) {
    			$ht = 1; // 匿名代理
    		}
    		else if (self::$clientIp == $httpbody) {
    			$ht = 2;
    		}
    		else {
    			$ht = 0;	
    		}

    		$headers = array();
    		$headers['User-Agent'] = 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.143 Safari/537.36';
	    	$headers['X-Requested-With'] = 'XMLHttpRequest';
	    	$headers['Accept'] = 'application/json, text/javascript, */*; q=0.01';
	    	// $headers['Referer'] = 'https://my.playstation.com/logged-in/trophies/public-trophies/';
	    	$headers['Referer'] = 'https://item.taobao.com/item.htm?id=41104924035';


	    	$cookie = 't=b5c1c7f39a2e7ade3ab14e23a87d7f9d; thw=cn; cna=bilGE9us2XQCAbZn93M22zJD; tg=0; l=AtDQjb1lxNLCKISlRKiWWMwQIBUisbTj; ali_ab=182.100.69.84.1523272421793.5; UM_distinctid=162ae2221fa1bc-023cd3e6218d27-683b0f7f-13c680-162ae2221fb138; miid=7585220751448887461; hng=CN%7Czh-CN%7CCNY%7C156; enc=%2B5gllcM4dyfiSlGO4UVFPZRIbsb4mqiFQct8aU61cJOcmEvPRZSXYRiexcCcD5dVrkeQOfVLcSH3%2FWv%2FQoYSyw%3D%3D; lgc=lxq73061; tracknick=lxq73061; cookie2=21590e525efec75c5908be7679bdc574; v=0; _tb_token_=757333761b373; dnk=lxq73061; mt=np=&ci=67_1; _cc_=VT5L2FSpdA%3D%3D; _mw_us_time_=1526558443172; _m_h5_tk=5b749c3645f7cc5e989c466e8f5868da_1526560965078; _m_h5_tk_enc=41718aa35722929a1e7b28d065787c36; uc3=nk2=D9syBSLMJ2g%3D&id2=Vy0WpgKoUho%3D&vt3=F8dBz499zqErEeB2RHc%3D&lg2=UtASsssmOIJ0bQ%3D%3D; existShop=MTUyNjU1ODQ0Nw%3D%3D; sg=187; csg=4f11742f; cookie1=AiLU5buHwFeM5qqCC%2Bjl2NVNJQI7aGneBufNfrI5MZA%3D; unb=41572778; skt=9d647bb039b59bbc; _l_g_=Ug%3D%3D; _nk_=lxq73061; cookie17=Vy0WpgKoUho%3D; isg=BNDQjB3Jvq7onWKVi3ps5Z6eoRjiMXORjNNjJcqhnCv-BXCvcqmEcya32c3l1Wy7; Hm_lvt_ba7c84ce230944c13900faeba642b2b4=1526537576,1526558449; Hm_lpvt_ba7c84ce230944c13900faeba642b2b4=1526558449; uc1=cookie16=VT5L2FSpNgq6fDudInPRgavC%2BQ%3D%3D&cookie21=W5iHLLyFeYFnNZKBCYQf&cookie15=V32FPkk%2Fw0dUvg%3D%3D&existShop=true&pas=0&cookie14=UoTeOLwBY7utPQ%3D%3D&cart_m=0&tag=8&lng=zh_CN;';

    		Unirest_Request::cookie($cookie);


    		$httpbody = self::curl($proxy, 'get', self::$rulers[$ruleid]['url'], array(), $headers, 30);
    		self::outputln("{$httpbody}");

    		file_put_contents(__DIR__.'/x.txt', $httpbody);
    	}
    	catch(Exception $ex){
    		self::outputln(__METHOD__. ':' . __LINE__);
    		self::outputln('Error: ' . $ex->getMessage());
    	}

    	return false;
    }

	static function main()
	{
		date_default_timezone_set('PRC');

		if (function_exists('set_time_limit')) {
		    set_time_limit(0);
		}

		if (PHP_SAPI !== 'cli') {
			die('Must run in cli!');
		}

		self::outputln('Run at ' . date('Ymd H:i:s'));

		$config = require(dirname(__FILE__).'/config.php');
		sqlmyInit($config['db']);

		self::setRulers($config['ruler']);

		self::$clientIp = $config['clientIp'];

		for(;;) {
			try {
				do {
					// 查询db中活着的代理地址的数量
					self::fetchAliveProxyData();
					if (self::checkMustGetAliveProxy()) {
						self::execProxyCrawel();
					}

				} while (false);

			}
			catch(Exception $ex) {
				self::outputln('Error: ' . $ex->getMessage());
			}			

			sleep(15);			
		}
		
	}

}


P::main();