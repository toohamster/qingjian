<?php

class P 
{

/**
 * 根据图片路径获取图片指纹
 * @param $src
 * @return bool|string
 */
static  function dHash($src)
{
    if (!file_exists($src)) {
        return false;
    }
    $info = getimagesize($src);
    if ($info === false) {
        return false;
    }
    $w   = 9;  // 采样宽度
    $h   = 8;  // 采样高度
    $dst = imagecreatetruecolor($w, $h);
    $img = imagecreatefromstring(file_get_contents($src));
    // 缩放
    $img && imagecopyresized($dst, $img, 0, 0, 0, 0, $w, $h, $info[0], $info[1]);
    $hash = '';
    for ($y = 0; $y < $h; $y++) {
        $pix = P::getGray(imagecolorat($dst, 0, $y));
        for ($x = 1; $x < $w; $x++) {
            $_pix = P::getGray(imagecolorat($dst, $x, $y));
            $_pix > $pix ? $hash .= '1' : $hash .= '0';
            $pix = $_pix;
        }
    }
    $hash = base_convert($hash, 2, 16);
    //imagejpeg($dst,"$hash.jpg");
    return $hash;
}

/**
 * 获取像素点的灰度值
 * @param $rgb
 * @return int
 */
static  function getGray($rgb)
{
    $r = ($rgb >> 16) & 0xFF;
    $g = ($rgb >> 8) & 0xFF;
    $b = $rgb & 0xFF;
    return intval(($r + $g + $b) / 3) & 0xFF;
}

/**
 * 获取汉明距离
 * @param $dhash_1
 * @param $dhash_2
 */
static  function getLeng($dhash_1, $dhash_2)
{
	// echo $dhash_1 . '   #   ' . $dhash_2 . PHP_EOL;
    $tem = @hex2bin($dhash_1) ^ @hex2bin($dhash_2);
    $tem = bin2hex($tem);
    $tem = base_convert($tem, 16, 2);

    return substr_count($tem, '1');
}

}

$_start = microtime(true);
$hash_1 = P::dHash(__DIR__.'/c00011.jpg');
$hash_2 = P::dHash(__DIR__.'/t002.jpg');
echo "hm: " . (int) P::getLeng($hash_1, $hash_2) . PHP_EOL;
$_end = microtime(true);
echo "haoshi: " . round(($_end - $_start) * 1000, 4) . "ms" . PHP_EOL;
echo "memory: " . round(memory_get_usage() / 1024, 3) . "KB" . PHP_EOL;

echo PHP_EOL;

$_start = microtime(true);
$hash_1 = P::dHash(__DIR__.'/c0002.jpg');
$hash_2 = P::dHash(__DIR__.'/t003.jpg');
echo "hm: " . (int) P::getLeng($hash_1, $hash_2) . PHP_EOL;
$_end = microtime(true);
echo "haoshi: " . round(($_end - $_start) * 1000, 4) . "ms" . PHP_EOL;
echo "memory: " . round(memory_get_usage() / 1024, 3) . "KB" . PHP_EOL;
