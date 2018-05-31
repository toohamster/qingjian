<?php

class Unirest_File
{
    /**
     * Prepares a file for upload. To be used inside the parameters declaration for a request.
     * @param string $path The file path
     */
    public static function add($filename, $mimetype = '', $postname = '')
    {
        if (function_exists('curl_file_create')) {
            return curl_file_create($filename, $mimetype = '', $postname = '');
        } else {
            return sprintf('@%s;filename=%s;type=%s', $filename, $postname ?: basename($filename), $mimetype);
        }
    }
}

interface Unirest_Method
{
    // RFC7231
    const GET = 'GET';
    const HEAD = 'HEAD';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';
    const CONNECT = 'CONNECT';
    const OPTIONS = 'OPTIONS';
    const TRACE = 'TRACE';

    // RFC3253
    const BASELINE = 'BASELINE';

    // RFC2068
    const LINK = 'LINK';
    const UNLINK = 'UNLINK';

    // RFC3253
    const MERGE = 'MERGE';
    const BASELINECONTROL = 'BASELINE-CONTROL';
    const MKACTIVITY = 'MKACTIVITY';
    const VERSIONCONTROL = 'VERSION-CONTROL';
    const REPORT = 'REPORT';
    const CHECKOUT = 'CHECKOUT';
    const CHECKIN = 'CHECKIN';
    const UNCHECKOUT = 'UNCHECKOUT';
    const MKWORKSPACE = 'MKWORKSPACE';
    const UPDATE = 'UPDATE';
    const LABEL = 'LABEL';

    // RFC3648
    const ORDERPATCH = 'ORDERPATCH';

    // RFC3744
    const ACL = 'ACL';

    // RFC4437
    const MKREDIRECTREF = 'MKREDIRECTREF';
    const UPDATEREDIRECTREF = 'UPDATEREDIRECTREF';

    // RFC4791
    const MKCALENDAR = 'MKCALENDAR';

    // RFC4918
    const PROPFIND = 'PROPFIND';
    const LOCK = 'LOCK';
    const UNLOCK = 'UNLOCK';
    const PROPPATCH = 'PROPPATCH';
    const MKCOL = 'MKCOL';
    const COPY = 'COPY';
    const MOVE = 'MOVE';

    // RFC5323
    const SEARCH = 'SEARCH';

    // RFC5789
    const PATCH = 'PATCH';

    // RFC5842
    const BIND = 'BIND';
    const UNBIND = 'UNBIND';
    const REBIND = 'REBIND';
}

class Unirest_Response
{
    public $code;
    public $raw_body;
    public $body;
    public $headers;

    /**
     * @param int $code response code of the cURL request
     * @param string $raw_body the raw body of the cURL response
     * @param string $headers raw header string from cURL response
     * @param array $json_args arguments to pass to json_decode function
     */
    public function __construct($code, $raw_body, $headers, $json_args = array())
    {
        $this->code     = $code;
        $this->headers  = $this->parseHeaders($headers);
        $this->raw_body = $raw_body;
    }

    /**
     * if PECL_HTTP is not available use a fall back function
     *
     * thanks to ricardovermeltfoort@gmail.com
     * http://php.net/manual/en/function.http-parse-headers.php#112986
     */
    private function parseHeaders($raw_headers)
    {
        if (function_exists('http_parse_headers')) {
            return http_parse_headers($raw_headers);
        } else {
            $key = '';
            $headers = array();

            foreach (explode("\n", $raw_headers) as $i => $h) {
                $h = explode(':', $h, 2);

                if (isset($h[1])) {
                    if (!isset($headers[$h[0]])) {
                        $headers[$h[0]] = trim($h[1]);
                    } elseif (is_array($headers[$h[0]])) {
                        $headers[$h[0]] = array_merge($headers[$h[0]], array(trim($h[1])));
                    } else {
                        $headers[$h[0]] = array_merge(array($headers[$h[0]]), array(trim($h[1])));
                    }

                    $key = $h[0];
                } else {
                    if (substr($h[0], 0, 1) == "\t") {
                        $headers[$key] .= "\r\n\t".trim($h[0]);
                    } elseif (!$key) {
                        $headers[0] = trim($h[0]);
                    }
                }
            }

            return $headers;
        }
    }
}

class Unirest_Request
{
    private static $cookie = null;
    private static $cookieFile = null;
    private static $curlOpts = array();
    private static $defaultHeaders = array();
    private static $handle = null;
    private static $jsonOpts = array();
    private static $socketTimeout = null;
    private static $verifyPeer = true;
    private static $verifyHost = true;

    private static $auth = array (
        'user' => '',
        'pass' => '',
        'method' => CURLAUTH_BASIC
    );

    private static $proxy = array(
        'port' => false,
        'tunnel' => false,
        'address' => false,
        'type' => CURLPROXY_HTTP,
        'auth' => array (
            'user' => '',
            'pass' => '',
            'method' => CURLAUTH_BASIC
        )
    );

    /**
     * Set JSON decode mode
     *
     * @param bool $assoc When TRUE, returned objects will be converted into associative arrays.
     * @param bool $depth User specified recursion depth.
     * @param bool $options Bitmask of JSON decode options. Currently only JSON_BIGINT_AS_STRING is supported (default is to cast large integers as floats)
     */
    public static function jsonOpts($assoc = false, $depth = 512, $options = 0)
    {
        return self::$jsonOpts = array($assoc, $depth, $options);
    }

    /**
     * Verify SSL peer
     *
     * @param bool $enabled enable SSL verification, by default is true
     */
    public static function verifyPeer($enabled)
    {
        return self::$verifyPeer = $enabled;
    }
    
    /**
     * Verify SSL host
     *
     * @param bool $enabled enable SSL host verification, by default is true
     */
    public static function verifyHost($enabled)
    {
        return self::$verifyHost = $enabled;
    }

    /**
     * Set a timeout
     *
     * @param integer $seconds timeout value in seconds
     */
    public static function timeout($seconds)
    {
        return self::$socketTimeout = $seconds;
    }

    /**
     * Set default headers to send on every request
     *
     * @param array $headers headers array
     */
    public static function defaultHeaders($headers)
    {
        return array_merge(self::$defaultHeaders, $headers);
    }

    /**
     * Set a new default header to send on every request
     *
     * @param string $name header name
     * @param string $value header value
     */
    public static function defaultHeader($name, $value)
    {
        return self::$defaultHeaders[$name] = $value;
    }

    /**
     * Clear all the default headers
     */
    public static function clearDefaultHeaders()
    {
        return self::$defaultHeaders = array();
    }

    /**
     * Set curl options to send on every request
     *
     * @param array $options options array
     */
    public static function curlOpts($opts)
    {
        return array_merge(self::$curlOpts, $opts);
    }

    /**
     * Set a new default header to send on every request
     *
     * @param string $name header name
     * @param string $value header value
     */
    public static function curlOpt($name, $value)
    {
        return self::$curlOpts[$name] = $value;
    }

    /**
     * Clear all the default headers
     */
    public static function clearCurlOpts()
    {
        return self::$curlOpts = array();
    }

    /**
     * Set a Mashape key to send on every request as a header
     * Obtain your Mashape key by browsing one of your Mashape applications on https://www.mashape.com
     *
     * Note: Mashape provides 2 keys for each application: a 'Testing' and a 'Production' one.
     *       Be aware of which key you are using and do not share your Production key.
     *
     * @param string $key Mashape key
     */
    public static function setMashapeKey($key)
    {
        return self::defaultHeader('X-Mashape-Key', $key);
    }

    /**
     * Set a coockie string for enabling coockie handling
     *
     * @param string $cookie
     */
    public static function cookie($cookie)
    {
        self::$cookie = $cookie;
    }

    /**
     * Set a coockie file path for enabling coockie handling
     *
     * $cookieFile must be a correct path with write permission
     *
     * @param string $cookieFile - path to file for saving cookie
     */
    public static function cookieFile($cookieFile)
    {
        self::$cookieFile = $cookieFile;
    }

    /**
     * Set authentication method to use
     *
     * @param string $username authentication username
     * @param string $password authentication password
     * @param string $method authentication method
     */
    public static function auth($username = '', $password = '', $method = CURLAUTH_BASIC)
    {
        self::$auth['user'] = $username;
        self::$auth['pass'] = $password;
        self::$auth['method'] = $method;
    }

    /**
     * Set proxy to use
     *
     * @param string $address proxy address
     * @param string $port proxy port
     * @param string $port proxy type (Available options for this are CURLPROXY_HTTP, CURLPROXY_HTTP_1_0 CURLPROXY_SOCKS4, CURLPROXY_SOCKS5, CURLPROXY_SOCKS4A and CURLPROXY_SOCKS5_HOSTNAME)
     * @param string $tunnel enable/disable tunneling
     */
    public static function proxy($address, $port = 1080, $type = CURLPROXY_HTTP, $tunnel = false)
    {
        self::$proxy['type'] = $type;
        self::$proxy['port'] = $port;
        self::$proxy['tunnel'] = $tunnel;
        self::$proxy['address'] = $address;
    }

    /**
     * Set proxy authentication method to use
     *
     * @param string $username authentication username
     * @param string $password authentication password
     * @param string $method authentication method
     * @param string $tunnel enable/disable tunneling
     */
    public static function proxyAuth($username = '', $password = '', $method = CURLAUTH_BASIC)
    {
        self::$proxy['auth']['user'] = $username;
        self::$proxy['auth']['pass'] = $password;
        self::$proxy['auth']['method'] = $method;
    }

    /**
     * Send a GET request to a URL
     *
     * @param string $url URL to send the GET request to
     * @param array $headers additional headers to send
     * @param mixed $parameters parameters to send in the querystring
     * @return Unirest_Response
     */
    public static function get($url, $headers = array(), $parameters = null)
    {
        return self::send(Unirest_Method::GET, $url, $parameters, $headers);
    }

    /**
     * Send a HEAD request to a URL
     * @param string $url URL to send the HEAD request to
     * @param array $headers additional headers to send
     * @param mixed $parameters parameters to send in the querystring
     * @return Unirest_Response
     */
    public static function head($url, $headers = array(), $parameters = null)
    {
        return self::send(Unirest_Method::HEAD, $url, $parameters, $headers);
    }

    /**
     * Send a OPTIONS request to a URL
     * @param string $url URL to send the OPTIONS request to
     * @param array $headers additional headers to send
     * @param mixed $parameters parameters to send in the querystring
     * @return Unirest_Response
     */
    public static function options($url, $headers = array(), $parameters = null)
    {
        return self::send(Unirest_Method::OPTIONS, $url, $parameters, $headers);
    }

    /**
     * Send a CONNECT request to a URL
     * @param string $url URL to send the CONNECT request to
     * @param array $headers additional headers to send
     * @param mixed $parameters parameters to send in the querystring
     * @return Unirest_Response
     */
    public static function connect($url, $headers = array(), $parameters = null)
    {
        return self::send(Unirest_Method::CONNECT, $url, $parameters, $headers);
    }

    /**
     * Send POST request to a URL
     * @param string $url URL to send the POST request to
     * @param array $headers additional headers to send
     * @param mixed $body POST body data
     * @return Unirest_Response response
     */
    public static function post($url, $headers = array(), $body = null)
    {
        return self::send(Unirest_Method::POST, $url, $body, $headers);
    }

    /**
     * Send DELETE request to a URL
     * @param string $url URL to send the DELETE request to
     * @param array $headers additional headers to send
     * @param mixed $body DELETE body data
     * @return Unirest_Response
     */
    public static function delete($url, $headers = array(), $body = null)
    {
        return self::send(Unirest_Method::DELETE, $url, $body, $headers);
    }

    /**
     * Send PUT request to a URL
     * @param string $url URL to send the PUT request to
     * @param array $headers additional headers to send
     * @param mixed $body PUT body data
     * @return Unirest_Response
     */
    public static function put($url, $headers = array(), $body = null)
    {
        return self::send(Unirest_Method::PUT, $url, $body, $headers);
    }

    /**
     * Send PATCH request to a URL
     * @param string $url URL to send the PATCH request to
     * @param array $headers additional headers to send
     * @param mixed $body PATCH body data
     * @return Unirest_Response
     */
    public static function patch($url, $headers = array(), $body = null)
    {
        return self::send(Unirest_Method::PATCH, $url, $body, $headers);
    }

    /**
     * Send TRACE request to a URL
     * @param string $url URL to send the TRACE request to
     * @param array $headers additional headers to send
     * @param mixed $body TRACE body data
     * @return Unirest_Response
     */
    public static function trace($url, $headers = array(), $body = null)
    {
        return self::send(Unirest_Method::TRACE, $url, $body, $headers);
    }

    /**
     * This function is useful for serializing multidimensional arrays, and avoid getting
     * the 'Array to string conversion' notice
     */
    public static function buildHTTPCurlQuery($data, $parent = false)
    {
        $result = array();

        if (is_object($data)) {
            $data = get_object_vars($data);
        }

        foreach ($data as $key => $value) {
            if ($parent) {
                $new_key = sprintf('%s[%s]', $parent, $key);
            } else {
                $new_key = $key;
            }

            if (!$value instanceof CURLFile and (is_array($value) or is_object($value))) {
                $result = array_merge($result, self::buildHTTPCurlQuery($value, $new_key));
            } else {
                $result[$new_key] = $value;
            }
        }

        return $result;
    }

    /**
     * Send a cURL request
     * @param Unirest\Method $method HTTP method to use
     * @param string $url URL to send the request to
     * @param mixed $body request body
     * @param array $headers additional headers to send
     * @throws Exception if a cURL error occurs
     * @return Unirest_Response
     */
    public static function send($method, $url, $body = null, $headers = array())
    {
        self::$handle = curl_init();

        // start with default options
        curl_setopt_array(self::$handle, self::$curlOpts);

        if ($method !== Unirest_Method::GET) {
            curl_setopt(self::$handle, CURLOPT_CUSTOMREQUEST, $method);

            if (is_array($body) || ( class_exists('Traversable', false) && $body instanceof Traversable) ) {
                curl_setopt(self::$handle, CURLOPT_POSTFIELDS, self::buildHTTPCurlQuery($body));
            } else {
                curl_setopt(self::$handle, CURLOPT_POSTFIELDS, $body);
            }
        } elseif (is_array($body)) {
            if (strpos($url, '?') !== false) {
                $url .= '&';
            } else {
                $url .= '?';
            }

            $url .= urldecode(http_build_query(self::buildHTTPCurlQuery($body)));
        }

        curl_setopt_array(self::$handle, array(
            CURLOPT_URL => self::encodeUrl($url),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_HTTPHEADER => self::getFormattedHeaders($headers),
            CURLOPT_HEADER => true,
            CURLOPT_SSL_VERIFYPEER => self::$verifyPeer,
            //CURLOPT_SSL_VERIFYHOST accepts only 0 (false) or 2 (true). Future versions of libcurl will treat values 1 and 2 as equals
            CURLOPT_SSL_VERIFYHOST => self::$verifyHost === false ? 0 : 2,
            // If an empty string, '', is set, a header containing all supported encoding types is sent
            CURLOPT_ENCODING => ''
        ));

        if (self::$socketTimeout !== null) {
            curl_setopt(self::$handle, CURLOPT_TIMEOUT, self::$socketTimeout);
        }

        if (self::$cookie) {
            curl_setopt(self::$handle, CURLOPT_COOKIE, self::$cookie);
        }

        if (self::$cookieFile) {
            curl_setopt(self::$handle, CURLOPT_COOKIEFILE, self::$cookieFile);
            curl_setopt(self::$handle, CURLOPT_COOKIEJAR, self::$cookieFile);
        }

        if (!empty(self::$auth['user'])) {
            curl_setopt_array(self::$handle, array(
                CURLOPT_HTTPAUTH    => self::$auth['method'],
                CURLOPT_USERPWD     => self::$auth['user'] . ':' . self::$auth['pass']
            ));
        }

        if (self::$proxy['address'] !== false) {
            curl_setopt_array(self::$handle, array(
                CURLOPT_PROXYTYPE       => self::$proxy['type'],
                CURLOPT_PROXY           => self::$proxy['address'],
                CURLOPT_PROXYPORT       => self::$proxy['port'],
                CURLOPT_HTTPPROXYTUNNEL => self::$proxy['tunnel'],
                CURLOPT_PROXYAUTH       => self::$proxy['auth']['method'],
                CURLOPT_PROXYUSERPWD    => self::$proxy['auth']['user'] . ':' . self::$proxy['auth']['pass']
            ));
        }

        $response   = curl_exec(self::$handle);
        $error      = curl_error(self::$handle);
        $info       = self::getInfo();

        if ($error) {
            throw new Exception($error);
        }

        // Split the full response in its headers and body
        $header_size = $info['header_size'];
        $header      = substr($response, 0, $header_size);
        $body        = substr($response, $header_size);
        $httpCode    = $info['http_code'];

        return new Unirest_Response($httpCode, $body, $header, self::$jsonOpts);
    }

    public static function getInfo($opt = false)
    {
        if ($opt) {
            $info = curl_getinfo(self::$handle, $opt);
        } else {
            $info = curl_getinfo(self::$handle);
        }

        return $info;
    }

    public static function getCurlHandle()
    {
        return self::$handle;
    }

    public static function getFormattedHeaders($headers)
    {
        $formattedHeaders = array();

        $combinedHeaders = array_change_key_case(array_merge((array) $headers, self::$defaultHeaders));

        foreach ($combinedHeaders as $key => $val) {
            $formattedHeaders[] = self::getHeaderString($key, $val);
        }

        if (!array_key_exists('user-agent', $combinedHeaders)) {
            $formattedHeaders[] = 'user-agent: unirest-php/2.0';
        }

        if (!array_key_exists('expect', $combinedHeaders)) {
            $formattedHeaders[] = 'expect:';
        }

        return $formattedHeaders;
    }

    private static function getArrayFromQuerystring($query)
    {
        $query = preg_replace_callback('/(?:^|(?<=&))[^=[]+/', function ($match) {
            return bin2hex(urldecode($match[0]));
        }, $query);

        parse_str($query, $values);

        return array_combine(array_map('hex2bin', array_keys($values)), $values);
    }

    /**
     * Ensure that a URL is encoded and safe to use with cURL
     * @param  string $url URL to encode
     * @return string
     */
    private static function encodeUrl($url)
    {
        $url_parsed = parse_url($url);

        $scheme = $url_parsed['scheme'] . '://';
        $host   = $url_parsed['host'];
        $port   = (isset($url_parsed['port']) ? $url_parsed['port'] : null);
        $path   = (isset($url_parsed['path']) ? $url_parsed['path'] : null);
        $query  = (isset($url_parsed['query']) ? $url_parsed['query'] : null);

        if ($query !== null) {
            $query = '?' . http_build_query(self::getArrayFromQuerystring($query));
        }

        if ($port && $port[0] !== ':') {
            $port = ':' . $port;
        }

        $result = $scheme . $host . $port . $path . $query;
        return $result;
    }

    private static function getHeaderString($key, $val)
    {
        $key = trim(strtolower($key));
        return $key . ': ' . $val;
    }
}
