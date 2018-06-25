<?php

$t = '<em class="ired" title="">亚马逊美国</em><em class="ired" title="">家居用品</em><em class="iorange" title="">货比1家</em><em class="inone" title="产品到货后评论时间">马上评论</em><em class="inone" title="">指定评价</em><em class="iorange msg" title="发布方给您的提醒">留言提醒asdfasdf23412341234dswf314324f1q34f</em>';


function p($t) {
	$t = iconv('UTF-8', 'GBK', $t);
	fwrite(STDOUT, $t.PHP_EOL);
}

p($t);

$re = '/<em class="iorange msg" title="发布方给您的提醒"\>(.*)<\\/em\>/';

$t = preg_replace($re, '', $t);

p($t);


