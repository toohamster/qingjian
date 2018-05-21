<?php
function charCodeAt($str, $index)
{
    $char = mb_substr($str, $index, 1, 'UTF-8');
 
    if (mb_check_encoding($char, 'UTF-8'))
    {
        $ret = mb_convert_encoding($char, 'UTF-32BE', 'UTF-8');
        return hexdec(bin2hex($ret));
    }
    else
    {
        return null;
    }
}


function a($r, o) {
    for (var $t = 0; $t < strlen($o) - 2; $t += 3) {
        var $a = $o[$t + 2];
        $a = $a >= "a" ? a.charCodeAt(0) - 87 : intval($a);
        $a = "+" === o[t + 1] ? r >>> a: r << a;
        $r = "+" === o[t] ? r + a & 4294967295 : r ^ a;
    }
    return $r;
}
$C = null;
function hash($r, $_gtk) {
    var $o = strlen($r);
    $o > 30 && ($r = "" + $r.substr(0, 10) + $r.substr(Math.floor($o / 2) - 5, 10) + $r.substr( - 10, 10));
    var $t = void 0,
    $t = null !== $C ? $C: ($C = $_gtk || "") || "";
    for ($e = explode(".",$t), $h = intval($e[0]) || 0, $i = intval($e[1]) || 0, $d = [], $f = 0, $g = 0; $g < strlen($r); $g++) {
        $m = $r.charCodeAt($g);
        128 > $m ? $d[$f++] = $m: (2048 > $m ? $d[$f++] = $m >> 6 | 192 : (55296 === (64512 & $m) && $g + 1 < strlen($r) && 56320 === (64512 & $r.charCodeAt($g + 1)) ? ($m = 65536 + ((1023 & $m) << 10) + (1023 & $r.charCodeAt(++$g)), $d[$f++] = $m >> 18 | 240, $d[$f++] = $m >> 12 & 63 | 128) : $d[$f++] = $m >> 12 | 224, $d[$f++] = $m >> 6 & 63 | 128), $d[$f++] = 63 & $m | 128)
    }
    for ($S = $h &&
    $u = "+-a^+6" &&
    $l = "+-3^+b+-f" &&
    $s = 0; $s < strlen($d); s++) $S += $d[$s],
    $S = $a($S, $u);


    $S = a($S, $l),
    $S ^= $i;
    0 > $S && ($S = (2147483647 & $S) + 2147483648),
    $S %= 1e6,
    $S . "." . ($S ^ $h);
    return $S;
}