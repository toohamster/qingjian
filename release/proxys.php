<?php
require_once dirname(__FILE__) .'/lib/Arrays.php';
require_once dirname(__FILE__) .'/lib/Unirest.php';
require_once dirname(__FILE__) .'/lib/Sqlmy.php';

class P
{

	const COM_NUM = 100;
	static $debugIs = false;

    static $clientIp = '127.0.0.1';
	static $clientIpGet = '';

	static $aliveProxyData = array();
	static $htCacheData = array();

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
        else {
            Unirest_Request::proxy(false);
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

        return $response;
    }

    static function sqlMonitor($sql, $dsnId)
	{
        // 只有在 命令模式下才监测 sql
        if (PHP_SAPI === 'cli') {
            self::outputln("[sql]: {$sql}");
        }		
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
    		$response = self::curl(false, 'get', $url, $params, array(), 10);

    		$body = $response->raw_body;

    		if (!empty($body)) {
    			$data = Arrays::normalize($body, "\n");
    		}

    		self::outputln($body);
    	}
    	catch(Exception $ex) {
    		self::outputln(__METHOD__. ':' . __LINE__);
    		self::outputln("Error: curl {$url}" . $ex->getMessage());
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

    static function cleanHtCache()
    {
    	self::$htCacheData = array();
    }

    static function remove6hData()
    {
    	$ltTime = time() - 21600;
    	$ltDate = date('Y-m-d H:i:s', $ltTime);

    	$cond = array(
    			'status'	=> 1,
    			'updated_at'	=> array($ltDate, '<')
    		);

    	sqlmyMaster()->del('qingjianproxys', $cond);
    }

    static function execProxyCrawel()
    {

    	// 首先清除掉 6个小时之前的数据
    	self::remove6hData();

    	// 检查 self::$aliveProxyData 缺少的规则对应的代理数量
    	// 从而实现自动专用增补支持

    	// 先计算出需要增补的规则
    	$addRulers = array();
    	foreach (self::$aliveProxyData as $ruleid => $pc) {
    		if ($pc < P::COM_NUM) {
    			$addRulers[$ruleid] = $pc;
    		}
    	}

    	if (!empty($addRulers)) {

    		$proxyHosts = self::fetchProxys(200);

    		// 清除 ht缓存数据
    		self::cleanHtCache();

    		foreach ($proxyHosts as $host) {

    			if (empty($addRulers)){
    				self::outputln('null addRulers');
    				break;
    			}

    			foreach ($addRulers as $ruleid => $pc) {

    				$bool = self::crawel($host, $ruleid);
    				if ($bool == true) {
    					$addRulers[$ruleid] = $pc + 1;
    				}

    				if (P::COM_NUM <= $pc) {
    					self::outputln("unset addRulers[$ruleid]");
    					unset($addRulers[$ruleid]);
    				}
    			}

			}

    	}
    }

    static function assertHttpResponse($response, $assert)
    {
    	$assert = (array) $assert;

    	if (empty($assert)) return true;

    	$part = Arrays::val($assert, 'part', 'httpbody');
    	$type = Arrays::val($assert, 'type', 'text');
    	$expression = Arrays::val($assert, 'expression', 'contain');
    	$content = Arrays::val($assert, 'content', 'SUCCESS');
    	if ($part == 'httpbody') {

    		$httpbody = $response->raw_body;
    		if ($type == 'text') {

    			if ($expression == 'contain') {

    				if (strpos($httpbody, $content) !== false){
    					return true;
    				}
    			}
    			else {
    				// 扩展使用 其它的比较方法
    			}
    		}
    		else {
    			// 扩展使用 诸如 xml,json之类的
    		}
    	}

    	return false;
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

    		if (empty(self::$htCacheData[$host])) {
    			$response = self::curl($proxy, 'get', P::$clientIpGet, array(), array(), 15);

    			$httpbody = $response->raw_body;

	    		$httpbody = trim($httpbody);

	    		self::outputln("{$httpbody} == {$proxyAdderss}");

	    		if ($httpbody == $proxyAdderss) {
	    			$ht = 1; // 匿名代理
	    		}
	    		else if (self::$clientIp == $httpbody) {
	    			$ht = 2;
	    		}
	    		else {
	    			$ht = -1;	
	    		}

	    		self::$htCacheData[$host] = $ht;
    		}

    		$ht = self::$htCacheData[$host];

    		$headers = array();
    		$headers['User-Agent'] = 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.143 Safari/537.36';
	    	$headers['X-Requested-With'] = 'XMLHttpRequest';
	    	$headers['Accept'] = 'application/json, text/javascript, */*; q=0.01';

	    	$httpOptions = Arrays::val(self::$rulers[$ruleid], 'httpOptions', array());	    	
	    	
	    	if (!empty($httpOptions['refer'])) {
	    		$headers['Referer'] = $httpOptions['refer'];
	    	}

	    	if (!empty($httpOptions['cookie'])) {
	    		Unirest_Request::cookie($httpOptions['cookie']);
	    	}

	    	// self::outputln($httpOptions['cookie']);
	    	
    		$response = self::curl($proxy, 'get', self::$rulers[$ruleid]['url'], array(), $headers, 30);

    		$httpbody = $response->raw_body;
    		self::outputln("{$httpbody}");

    		// 重置 cookies
    		Unirest_Request::cookie(null);

    		$assert = Arrays::val(self::$rulers[$ruleid], 'assert', array());    		
    		// assert body
    		if (self::assertHttpResponse($response, $assert) == true) {
    			self::outputln("assert(SUCCESS)");

    			// 入库
    			$cond = array(
    				'host'	=> $host,
    				'ruleid'	=> $ruleid,
    			);

    			$isExist = sqlmyMaster()->count('qingjianproxys', $cond, 'id') > 0;

    			if ($isExist) {
    				// 更新
    				$data = array(
    						'ht'	=> $ht,
    						'status'	=> 1,
    						'updated_at'	=> date('Y-m-d H:i:s', time()),
    					);

    				sqlmyMaster()->update('qingjianproxys', $data, $cond);
    			}
    			else {
    				// 添加
    				$data = array(
    						'host'	=> $host,
    						'ruleid'	=> $ruleid,
    						'ht'	=> $ht,
    						'status'	=> 1,
    						'updated_at'	=> date('Y-m-d H:i:s', time()),
    					);

    				sqlmyMaster()->insert('qingjianproxys', $data);
    			}

    			return true;
    		}
    		else {
    			$cond = array(
    				'host'	=> $host,
    				'ruleid'	=> $ruleid,
    			);
    			sqlmyMaster()->del('qingjianproxys', $cond);
    		}
    	}
    	catch(Exception $ex){
    		self::outputln(__METHOD__. ':' . __LINE__);
    		self::outputln('Error: ' . $ex->getMessage());
    	}

    	return false;
    }

    static function reloadRulers()
    {
        // 重新加载规则
        $config = require(dirname(__FILE__).'/config.php');

        self::setRulers($config['ruler']);
    }

	static function cliMain()
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
		self::$clientIpGet = $config['clientIpGet'];

		for(;;) {
			try {
                self::outputln('OOOO: ' . date('Y-m-d H:i:s'));

                // 重新加载 验证规则
                self::reloadRulers();
                
				// 查询db中活着的代理地址的数量
				self::fetchAliveProxyData();
				if (self::checkMustGetAliveProxy()) {
					self::execProxyCrawel();
				}

			}
			catch(Exception $ex) {
				self::outputln('Error: ' . $ex->getMessage());
			}

			sleep(60);			
		}
		
	}

	static function webMain()
	{
        $config = require(dirname(__FILE__).'/config.php');
        sqlmyInit($config['db']);

        self::setRulers($config['ruler']);

        self::$clientIp = $config['clientIp'];
        self::$clientIpGet = $config['clientIpGet'];

        $ruleId = Arrays::val($_GET, 'ruleId', '');
        $ht = (int) Arrays::val($_GET, 'ht', 0);
        $length = (int) Arrays::val($_GET, 'length', 0);

        $response = array(
                'code'  => -1,
                'msg'   => '出错了',
                'data'   => array(),
            );

        do {
            if (empty(self::$rulers[$ruleId])){
                $response['msg'] = "出错了: ruleId `{$ruleId}` 未定义!";
                break;
            }

            try {
                $data = self::getCanUseProxys($ruleId, $ht, $length);

                $response['code']   = 0;
                $response['data']   = $data;
                $response['msg']   = 'SUCCESS';

                break;
            }
            catch(Exception $ex)
            {
                $response['msg'] = "出错了: " . $ex->getMessage();
            }

        } while(false);

        echo json_encode($response);
	}

    static function apiCall($ruleId, $ht=0, $length=5)
    {
        static $noInit = true;
        if ($noInit) {
            $noInit = false;

            $config = require(dirname(__FILE__).'/config.php');
            sqlmyInit($config['db']);

            self::setRulers($config['ruler']);

            self::$clientIp = $config['clientIp'];
            self::$clientIpGet = $config['clientIpGet'];
        }

        $response = array(
                'code'  => -1,
                'msg'   => '出错了',
                'data'   => array(),
            );

        do {
            if (empty(self::$rulers[$ruleId])){
                $response['msg'] = "出错了: ruleId `{$ruleId}` 未定义!";
                break;
            }

            try {
                $data = self::getCanUseProxys($ruleId, $ht, $length);

                $response['code']   = 0;
                $response['data']   = $data;
                $response['msg']   = 'SUCCESS';

                break;
            }
            catch(Exception $ex)
            {
                $response['msg'] = "出错了: " . $ex->getMessage();
            }

        } while(false);

        return $response;
    }

    /**
     * 返回 可供使用的代理主机列表
     * 
     * @param  string $ruleId 规则id
     * @param  int $ht     代理类型: 1 为不显示客户端Ip; 2为显示客户端Ip; -1 表示未知; 0 代表不检查
     * @param  int $length 数量
     * 
     * @return array
     */
	static function getCanUseProxys($ruleId, $ht=0, $length=5)
    {
        $cond = array(
                    'ruleid'    => $ruleId,
                    'status'    => 1,
                );

        if ($ht != 0) {
            $cond['ht'] = $ht;
        }

        if ($length < 1) $length = 5;

        $rows = sqlmyMaster()->select('qingjianproxys', $cond, 'ruleid,host,ht,updated_at', 'updated_at DESC', $length);

        return $rows;
    }

}
