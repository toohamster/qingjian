<?php
function get_client_ip()
{  
  if ( getenv('HTTP_CLIENT_IP') ) return getenv('HTTP_CLIENT_IP');
  if ( getenv('HTTP_X_FORWARDED_FOR') ) {
  	$clientIp = explode(',', getenv('HTTP_X_FORWARDED_FOR'), 2);
    return isset($clientIp[0]) ? trim($clientIp[0]) : '';
  }

  return getenv('REMOTE_ADDR');
}

echo get_client_ip();