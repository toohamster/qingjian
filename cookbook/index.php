<?php
error_reporting(E_ALL | E_STRICT);
date_default_timezone_set('Asia/Shanghai');
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>CookBook for cx1934!</title>
	<meta name="keywords" content="CookBook for cx1934!" />
	<meta name="description" content="CookBook for cx1934!" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="http://ssdb.io/docs/css/bootstrap.min.css" rel="stylesheet" media="all"/>
	<link href="http://ssdb.io/docs/css/style.css" rel="stylesheet" media="all"/>
	<style type="text/css">
		div.bb {padding-left: 25px;}
		div.bb2 {padding-left: 25px;}
		div.bb3 {padding-left: 25px;}
	</style>
	<link href="autoc/l.css" rel="stylesheet" type="text/css" media="all">
</head>
<body>

<!-- Fixed navbar -->
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<a class="navbar-brand" href="javascript:;"><?php echo date('Y年第W周')?> 食谱</a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right" style="margin-top: 9px;">
				<div class="btn-group">
					<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
						当前语言: 简体中文
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						<li><a href="javascript:;">简体中文</a></li>
					</ul>
				</div>
			</ul>
		</div><!--/.nav-collapse -->
	</div>
</div>

<div class="container">
<hr />

<?php

function normalize($input, $delimiter = ',')
{
    if (!is_array($input))
    {
        $input = explode($delimiter, $input);
    }
    $input = array_map('trim', $input);
    $input = array_filter($input, 'strlen');
    reset($input);
    return $input;
}

$file = __DIR__ . '/db.dat';
if (file_exists($file)) {
	$lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
} else {
	$lines = [];
}



$weekFoods = [];

// initVal
$dayFoods = [];
$dayName = '';

foreach ($lines as $line) {
	$line = trim($line);

	if (empty($line)) continue;
	if ($line === '================') {
		// 分段
		if (!empty($dayName)) {
			$weekFoods[$dayName] = $dayFoods;
		}
		// reset initVal
		$dayFoods = [];
		$dayName = '';
		continue;
	}

	if (preg_match('/^周(\d|六|日)食谱/i', $line, $matchs)) {
		$dayName = $line;
		continue;
	}

	// 处理食材
	$items = normalize($line, ',');

	$foodName = array_shift($items); // 名称 
	$foodType = array_shift($items); // 类型
	
	// 处理菜谱
	$foodMenu = [];
	$temp = array_shift($items); 
	$temps = normalize($temp, '：');
	$temp = array_shift($temps);

	$foodNature = '';

	if ($temp === '菜谱') {
		$temp = array_shift($temps);
		$foodMenu = normalize($temp, ';');
		$foodMenu = array_filter($foodMenu, function($var){
			return $var === '无' ? false : true;
		});
		$foodNature = array_shift($foodMenu);
		reset($foodMenu);
	}

	// 处理相克的实物
	$foodConflict = [];
	$temp = array_shift($items);
	$temps = normalize($temp, '：');
	$temp = array_shift($temps);

	if ($temp === '相克的食物') {
		$temp = array_shift($temps);
		if ($temp !== '未统计') {
			$foodConflict = normalize($temp, ';');
			$foodConflict = array_filter($foodConflict, function($var){
				return $var === '无' ? false : true;
			});
		}
		reset($foodConflict);
	}


	$dayFoods[] = [
		'name'	=> $foodName,
		'type'	=> $foodType,
		'nature'	=> $foodNature,
		'menus'	=> $foodMenu,
		'conflicts'	=> $foodConflict,
	];
}

if (!empty($dayName)) {
	$weekFoods[$dayName] = $dayFoods;
}
?>

<?php foreach($weekFoods as $dayName => $dayFoods):?>

	<h2 id="c-asr"><?php echo $dayName;?></h2>

	<div class="bb asr-div">

	<?php foreach($dayFoods as $foods):?>

		<h3 id="_3"><?php echo $foods['name'];?></h3>
		<div class="bb2 asr-xfyun-div">
			
			<form id="asr-xfyun-form">			
			<div class="form-group">
			    <label>类型: </label>
			    <span class="label label-primary"><?php echo $foods['type'];?></span>
			</div>
			<div class="form-group">
			    <label>性质: </label>
			    <span class="label label-info"><?php echo $foods['nature'];?></span>
			</div>
		<?php if(!empty($foods['menus'])):?>
			<div class="form-group">
				<label>菜谱: </label>
				<table class="table table-hover">
					<tbody id="asr-xfyun-keylist">
					<?php foreach($foods['menus'] as $menu):?>
						<tr>
				          <td><?php echo $menu;?></td>
				        </tr>
					<?php endforeach;?>
					</tbody>
				</table>
			</div>
		<?php endif;?>
		<?php if(!empty($foods['conflicts'])):?>
			<div class="form-group">
				<label>相克的食物: </label>
				<table class="table table-hover">
					<tbody id="asr-xfyun-keylist">
					<?php foreach($foods['conflicts'] as $conflict):?>
						<tr>
				          <td><span class="label label-danger"><?php echo $conflict;?></span></td>
				        </tr>
					<?php endforeach;?>
					</tbody>
				</table>
			</div>
		<?php endif;?>
		</form>

		</div>

	<?php endforeach;?>

	</div>


<?php endforeach;?>

<p>&nbsp;</p>

<div class="footer">
	Copyright &copy; 2018 - 2028 <a href="http://quick.lianzh.com">牵猪的仓鼠&贪吃的耗子</a>. All rights reserved.
	Updated: 2019-04-27 15:39:48 +0800	</div>

</div> <!-- /container -->

<script src="http://ssdb.io/docs/js/jquery-1.9.1.min.js"></script>
<script src="http://ssdb.io/docs/js/bootstrap.min.js"></script>
<script type="text/javascript" src="autoc/l.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	AutocJS.init({
	    article: '.container',
	    anchors: 'h2,h3',
	    prefix: 'div.asr-div'
	});
});
</script>
</body>
</html>
