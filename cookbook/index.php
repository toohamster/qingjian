<?php
error_reporting(E_ALL | E_STRICT);
date_default_timezone_set('Asia/Shanghai');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset='utf-8'>
	<title>CookBook for cx1934!</title>
	<meta content='always' name='referrer'>
	<meta content='IE=edge' http-equiv='X-UA-Compatible'>
	<style type="text/css">
	#sty {
		margin: 20px auto;
		padding: 20px;
		background: #efc;
	}
	</style>
</head>
<body>
<center>	
<h3><?php echo date('Y年第W周')?> 食谱</h3>
</center>
<hr>
<div id="sty">

<pre>
<?php
$code_dir = '/data/softs/CookBook/bin';

$commands = array(		
	"cd {$code_dir}",
	"java cookbook.Cookbook_week",
);

$commands = array_map('escapeshellcmd', $commands);

// 可以使用 ; 或者 && 来联立指令
$cmd_line = implode('&&', $commands);

system($cmd_line, $re);
?>
</pre>
</div>
</body>
</html>