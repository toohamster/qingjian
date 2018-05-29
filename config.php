<?php
return array(

	// 客户端Ip
	'clientIp'	=> '101.254.234.163',

	'db'	=> array(

		'master'	=> array(
			'type' => 'mysql',
			'dbpath'  => "mysql:host=127.0.0.1;port=3306;dbname=test",
			'login'	=> 'root',
			'password' => 'root',

			'initcmd' => array(
					"SET NAMES 'utf8'",
				),

			'attr'	=> array(
					\PDO::ATTR_PERSISTENT => false,
				),
		),

		'slaver'	=> array(
			'type' => 'mysql',
			'dbpath'  => "mysql:host=127.0.0.1;port=3306;dbname=test",
			'login'	=> 'root',
			'password' => 'root',

			'initcmd' => array(
					"SET NAMES 'utf8'",
				),

			'attr'	=> array(
					\PDO::ATTR_PERSISTENT => false,
				),
		),		
	),

	'ruler'	=> array(

		array(

			'id'	=> 'guize001',
			'url'	=> 'https://h5api.m.taobao.com/h5/mtop.taobao.detail.getdetail/6.0/?jsv=2.4.11&appKey=12574478&t=1523935666735&sign=&api=mtop.taobao.detail.getdetail&v=6.0&ttid=2017%40htao_h5_1.0.0&type=jsonp&dataType=jsonp&callback=mtopjsonp1&data=%7B%22exParams%22%3A%22%7B%5C%22countryCode%5C%22%3A%5C%22CN%5C%22%7D%22%2C%22itemNumId%22%3A%2216204910274%22%7D',
			'refer'	=> '',
			'cookie'	=> '',
			'assert'	=> array(
				'type'	=> 'text',
				'part'	=> 'httpbody',
				'expression'	=> 'contain',
				'content'	=> 'SUCCESS',
			),

		),

	),
);