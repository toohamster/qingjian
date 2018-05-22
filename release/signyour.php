<?php
class SignYour
{

	public static function strlen()
	{
		static $func = null;
		if (is_null($func)) {
			
			$func = 'strlen';
			if (function_exists('mb_strlen')) {
				$func = 'mb_strlen';
			}else if (function_exists('iconv_strlen')) {
				$func = 'iconv_strlen';
			}
		}

		return call_user_func_array($func, func_get_args());
	}

	public static function substr()
	{
		static $func = null;
		if (is_null($func)) {
			
			$func = 'substr';
			if (function_exists('mb_substr')) {
				$func = 'mb_substr';
			}else if (function_exists('iconv_substr')) {
				$func = 'iconv_substr';
			}
		}

		return call_user_func_array($func, func_get_args());
	}

	public static function ord($str, $offset=false)
	{
		if (!$offset) $offset = 0;
		return self::ordutf8($str, $offset);
	}

	private static function ordutf8($string, &$offset)
	{
	    $code = ord(substr($string, $offset,1)); 
	    if ($code >= 128) {        //otherwise 0xxxxxxx
	        if ($code < 224) $bytesnumber = 2;                //110xxxxx
	        else if ($code < 240) $bytesnumber = 3;        //1110xxxx
	        else if ($code < 248) $bytesnumber = 4;    //11110xxx
	        $codetemp = $code - 192 - ($bytesnumber > 2 ? 32 : 0) - ($bytesnumber > 3 ? 16 : 0);
	        for ($i = 2; $i <= $bytesnumber; $i++) {
	            $offset ++;
	            $code2 = ord(substr($string, $offset, 1)) - 128;        //10xxxxxx
	            $codetemp = $codetemp*64 + $code2;
	        }
	        $code = $codetemp;
	    }
	    $offset += 1;
	    if ($offset >= strlen($string)) $offset = -1;
	    return $code;
	}

	public static function unsignedRightShift($a, $b)
    {
        return (
            $a >= 0
            ? $a >> $b
            : (($a & 0x7fffffff) >> $b) | (0x40000000 >> ($b - 1))
        );
    }

    public static function charAt($a,$i)
    {
	    return self::substr($a,$i,1);
	}


}

class Yourcode
{

	public static $C = null;

	public static function a($R,$O) {
		$ol = SignYour::strlen($O);
		for($t = 0; $t < $ol;$t += 3) {
			$a = $O{$t + 2};
			$a = (strcmp($a, "a") >= 0) ? SignYour::ord($a) - 87 : intval($a);
			$a = '+' === $O{$t + 1} ? SignYour::unsignedRightShift($R, $a) : $R << $a;
			$R = '+' === $O{$t} ? $R + $a & 4294967295 : $R ^ $a;
		}

		return $R;
	}

	public static function hash($r, $_gtk) 
	{

		$r_len = SignYour::strlen($r);

		if ($r_len > 30) {
			$c1 = SignYour::substr($r, 0 , 10);
			$c2 = floor($r_len / 2) - 5;
			$c3 = SignYour::substr($r, $c2 , 10);
			$c4 = SignYour::substr($r, -10 , 10);
			$r = "{$c1}{$c3}{c4}";
		}
		if (Yourcode::$C === null) {
			Yourcode::$C = $_gtk;
		}

		$t = Yourcode::$C !== null ? Yourcode::$C : "";
		$e = explode(".", $t);
		$h = intval($e[0]);
		if (!$h) $h = 0;
		$i = intval($e[1]);
		if (!$i) $i = 0;	
		$d = [];
		$f = 0;
		$g = 0;
		for (; $g < $r_len; $g ++) {

			$char = SignYour::charAt($r,$g);
	        $m = SignYour::ord($char);

	        if (128 > $m) {
	        	$d[$f++] = $m;
	        }
	        else {

	        	if (2048 > $m) {
	        		$d[$f++] = $m >> 6 | 192;
	        	}
	        	else {
	        		$char0 = SignYour::charAt($r,$g+1);
	        		$rcode0 = SignYour::ord($char0);

	        		$xxx = 55296 === (64512 & $m) && $g + 1 < r_len && 56320 === (64512 & $rcode0);

	        		if ($xxx) {
	        			$char1 = SignYour::charAt($r,++$g);
	        			$rcode1 = SignYour::ord($char1);

		        		$m = 65536 + ((1023 & $m) << 10) + (1023 & $rcode1);
					    $d[$f++] = $m >> 18 | 240;
					    $d[$f++] = $m >> 12 & 63 | 128;
	        		}
	        		else {
	        			$d[$f++] = $m >> 12 | 224;
	        		}

	        		$d[$f++] = $m >> 6 & 63 | 128;
	        	}

		        $d[$f++] = 63 & $m | 128;
		    }
		}
		$S = $h;
		$u = "+-a^+6";
		$l = "+-3^+b+-f";
		$s = 0;

		for (; $s < count($d); $s++) {
			$S += $d[$s];
	        $S = Yourcode::a($S, $u);
		}
		$S = (string) $S;
		$S = Yourcode::a($S, $l);
		$S ^= $i;
		if (0 > $S) {
			$S = (2147483647 & $S) + 2147483648;
		}
	    $S = bcmod("{$S}", 1e6);
	    $vvv = $S ^ $h;
	    $cc = (string) $S;

	    $gg = $cc . '.' . $vvv;
		return $gg;
	}
}
