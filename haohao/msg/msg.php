<?php

require_once __DIR__ .'/Unirest.php';

class Msg
{

	static $debugIs = false;

    static function curl($method, $url, $params = array(), $headers = array(), $timeout = 10)
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

            if (Msg::$debugIs)
            {
                Msg::dump($debugInfo);
            }

            throw new Exception($ex->getMessage());
        }

        $debugInfo['response'] = $response;

        if (Msg::$debugIs)
        {
            Msg::dump($debugInfo);
        }

        return $response;
    }

    static function normalize($input, $delimiter = ',')
    {
        if (!is_array($input))
        {
            $input = explode($delimiter, $input);
        }
        $input = array_map('trim', $input);
        return array_filter($input, 'strlen');
    }

    static function dump($vars, $label = '', $return = false)
	{
	    $content = "\n";
	    if ($label != '') {
	        $content .= "[{$label}] :\n";
	    }
	    $content .= print_r($vars, true);
	    $content .= "\n\n";

	    if ($return) {
	        return $content;
	    }
	    echo $content;
	}

	static function cliMain()
	{
		#login
		$url = 'https://server02.dmcld.com:3000/doLogin?username=duoju&password=123456';
		$r = self::curl('get', $url);

		$t = $r->headers['set-cookie'];
		$t = Msg::normalize($t, ';');
		$t = Msg::normalize($t[0], '=');
		$ssss = 'DB38-0316-2016-0089';
		$ccccc = "rmsessionid={$t[1]};devckie=$ssss";
		$url = 'https://server02.dmcld.com:3000/remoteWeb?product_sns=' . $ssss;
		Unirest_Request::cookie($ccccc);
		$r = self::curl('get', $url, array());
		$r = json_decode($r->raw_body,true);
        $b = Msg::normalize($r['url'], '?');
		$p='{"text":"我是#param#仓鼠","port":0,"param":[{"number":"18616256919","text_param":["香港"]},{"number":"13901645759","text_param":["日本"]}]}';
		$h = array('Content-Type' => 'application/json');
		$url = $b[0] . '/api/send_sms';
		$r = self::curl('post', $url, $p, $h);

		return;
	}

}


if (PHP_SAPI === 'cli') {

	Msg::cliMain();

}
else {
	die('Only run cli!');
}
