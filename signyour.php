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

	/**
     * Performs an unsigned right shift.
     *
     * This is the same as the unsigned right shift operator ">>>" in other
     * languages.
     */
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
		for($t = 0; $t < $ol;$t += 3){
			$a = $O{$t + 2};
			$a = (strcmp($a, "a") >= 0) ? SignYour::ord($a) - 87 : ord($a);
			if ($a == 54) $a = 6; 
			$a = '+' === $O{$t + 1} ? SignYour::unsignedRightShift($R, $a) : $R << $a;
			$R = '+' === $O{$t} ? $R + $a & 4294967295 : $R ^ $a;
		}

		return $R;
	}

	public static function testA() {
		$arr[] = [320533, "+-a^+6", 333147381];
		$arr[] = [333147568, "+-a^+6", -2087426810];
		$arr[] = [-2087426672, "+-a^+6", -696381690];
		$arr[] = [-696381461, "+-a^+6", -846770940];
		$arr[] = [-846770776, "+-a^+6", -384361954];
		$arr[] = [-384361785, "+-a^+6", 1150565916];
		$arr[] = [1150566145, "+-a^+6", -1748631027];
		$arr[] = [-1748630863, "+-a^+6", -1385200213];

		foreach ($arr as $a) {
			$rr = self::a($a[0], $a[1]);
			echo "{$a[0]} | {$a[1]} = [{$a[2]} , $rr]<br>";
		}
	}
}

Yourcode::testA();

function lhash($r, $_gtk) {

	$r_len = SignYour::strlen($r);

	if ($r_len > 30) {
		$c1 = SignYour::substr($r, 0 , 10);
		$c2 = floor($r_len / 2) - 5;
		$c3 = SignYour::substr($r, $c2 , 10);
		$c4 = SignYour::substr($r, -10 , 10);
		$r = "{$c1}{$c3}{c4}";
	}

	echo $r, '<br>';

	if (Yourcode::$C === null) {
		Yourcode::$C = $_gtk;
	}

	$t = Yourcode::$C !== null ? Yourcode::$C : "";

	// echo $t, '|' , Yourcode::$C , '<br>';

	$e = explode(".", $t);
	$h = intval($e[0]);
	if (!$h) $h = 0;
	$i = intval($e[1]);
	if (!$i) $i = 0;	
	$d = [];
	$f = 0;
	$g = 0;

	lo($e,$h,$i,$d,$f,$g);

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

	// lo($d,$f,$m,$r_len,$g);

	$S = $h;
	$u = "+-a^+6";
	$l = "+-3^+b+-f";
	$s = 0;

	for (; $s < count($d); $s++) {
		$S += $d[$s];
        $S = Yourcode::a($S, $u);
	}

	// lo($S,$s,$l,$u);
	
	$S = Yourcode::a($S, $l);
	// -314211432

	lo('m',$S,$l);
	$S ^= $i;
	lo('n',$S,$i);
	if ($S > 0) {
		$S = (2147483647 & $S) + 2147483648;
	}
    $S %= 1e6;

    $vvv = $S ^ $h;
    $cc = (string) $S;

    $gg = $cc . '.' . $vvv;

    lo($gg);

	echo '<hr>';
}

function lo()
{
	$ar = func_get_args();
	foreach ($ar as $a) {
		if (is_array($a)) $a = json_encode($a);
		echo $a , '|';
	}
	echo '<br>';
}

lhash('今天天气怎么样', '320305.131321201');
?>
<script type="text/javascript">
var C = null;
var hash = function(r, _gtk) {
    var o = r.length;
    o > 30 && (r = "" + r.substr(0, 10) + r.substr(Math.floor(o / 2) - 5, 10) + r.substr(-10, 10));
    var t = void 0
      , t = null !== C ? C : (C = _gtk || "") || "";

    // var e = t.split("."), h = Number(e[0]) || 0, i = Number(e[1]) || 0, d = [], f = 0, g = 0;
    // for (; g < r.length; g++) {
    //     var m = r.charCodeAt(g);
    //     128 > m ? d[f++] = m : (2048 > m ? d[f++] = m >> 6 | 192 : (55296 === (64512 & m) && g + 1 < r.length && 56320 === (64512 & r.charCodeAt(g + 1)) ? (m = 65536 + ((1023 & m) << 10) + (1023 & r.charCodeAt(++g)),
    //     d[f++] = m >> 18 | 240,
    //     d[f++] = m >> 12 & 63 | 128) : d[f++] = m >> 12 | 224,
    //     d[f++] = m >> 6 & 63 | 128),
    //     d[f++] = 63 & m | 128)
    // }

    // console.log(d,f,m,o,g)

    var e = t.split(".");
    var h = Number(e[0]) || 0
    var i = Number(e[1]) || 0
    var d = []
    var f = 0
    var g = 0

    // console.log(e,h,i,d,f,g)

    for (; g < r.length; g++) {
        var m = r.charCodeAt(g);

        if (128 > m) {
        	d[f++] = m
        }
        else {

        	if (2048 > m) {
        		d[f++] = m >> 6 | 192
        	}
        	else {

        		var xxx = 55296 === (64512 & m) && g + 1 < r.length && 56320 === (64512 & r.charCodeAt(g + 1))

        		if (xxx) {
	        		m = 65536 + ((1023 & m) << 10) + (1023 & r.charCodeAt(++g))
				    d[f++] = m >> 18 | 240,
				    d[f++] = m >> 12 & 63 | 128
        		}
        		else {
        			d[f++] = m >> 12 | 224
        		}

        		d[f++] = m >> 6 & 63 | 128
        	}

	        d[f++] = 63 & m | 128

	    }
    }
    console.log(d,f,m,o,g)

    for (var S = h, u = "+-a^+6", l = "+-3^+b+-f", s = 0; s < d.length; s++)
        S += d[s],
        S = a(S, u);

    // var ccc = function () {
    // 	return S = a(S, l),
	   //  S ^= i,
	   //  0 > S && (S = (2147483647 & S) + 2147483648),
	   //  S %= 1e6,
	   //  S.toString() + "." + (S ^ h)
    // }

    var ccc = function () {
    	S = a(S, l)
    	console.warn('m',S,l)
	    S ^= i
	    console.warn('n',S,i)
	    0 > S && (S = (2147483647 & S) + 2147483648)
	    console.warn(S)
	    S %= 1e6
	    console.warn(S)

	    v = S.toString() + "." + (S ^ h)
	    console.warn(v, S)
	    return v
    }
    
    mm = ccc();
    console.log(mm, S)

    // res=719145.924184

    return mm
}
function a(r, o) {
    var cc = [r,o];
    for (var t = 0; t < o.length - 2; t += 3) {
        var a = o.charAt(t + 2);
        a = a >= "a" ? a.charCodeAt(0) - 87 : Number(a);
        a = "+" === o.charAt(t + 1) ? r >>> a : r << a;
        r = "+" === o.charAt(t) ? r + a & 4294967295 : r ^ a;
    }
    return r
}

var gtk = '320305.131321201', content = '今天天气怎么样'
var res = hash(content, gtk)
console.log('gtk=' + gtk)
console.log('res=' + res + ', content=' + content)
document.write('gtk=' + gtk + '<br/>content=' + content + '<br/>res=' + res)

</script>
