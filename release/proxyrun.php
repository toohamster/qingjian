<?php
require_once dirname(__FILE__) .'/proxys.php';

if (PHP_SAPI === 'cli') {
	P::cliMain();
}
else
{
	P::webMain();
}