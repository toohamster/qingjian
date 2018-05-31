<?php
return array(

	// 客户端Ip
	'clientIp'	=> '101.254.234.163',

	// IP地址探测的url 
	'clientIpGet'	=> 'http://quick.lianzh.com/_tmp/clientip.php',

	// 数据库配置
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

			'monitor'	=> 'P::sqlMonitor',
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
			'monitor'	=> 'P::sqlMonitor',
		),		
	),

	// 验证的规则
	'ruler'	=> array(

		array(
			// 规则Id
			'id'	=> 'guize001',
			// 请求url
			'url'	=> 'https://h5api.m.taobao.com/h5/mtop.taobao.detail.getdetail/6.0/?jsv=2.4.11&appKey=12574478&t=1523935666735&sign=&api=mtop.taobao.detail.getdetail&v=6.0&ttid=2017%40htao_h5_1.0.0&type=jsonp&dataType=jsonp&callback=mtopjsonp1&data=%7B%22exParams%22%3A%22%7B%5C%22countryCode%5C%22%3A%5C%22CN%5C%22%7D%22%2C%22itemNumId%22%3A%2216204910274%22%7D',

			// http 选项,可以设置 refer 和 cookie
			'httpOptions'	=> array(
				'refer'	=> 'https://item.taobao.com/item.htm?id=41104924035',
				'cookie'	=> 't=b5c1c7f39a2e7ade3ab14e23a87d7f9d; thw=cn; cna=bilGE9us2XQCAbZn93M22zJD; tg=0; l=AtDQjb1lxNLCKISlRKiWWMwQIBUisbTj; ali_ab=182.100.69.84.1523272421793.5; UM_distinctid=162ae2221fa1bc-023cd3e6218d27-683b0f7f-13c680-162ae2221fb138; miid=7585220751448887461; hng=CN%7Czh-CN%7CCNY%7C156; enc=%2B5gllcM4dyfiSlGO4UVFPZRIbsb4mqiFQct8aU61cJOcmEvPRZSXYRiexcCcD5dVrkeQOfVLcSH3%2FWv%2FQoYSyw%3D%3D; lgc=lxq73061; tracknick=lxq73061; cookie2=21590e525efec75c5908be7679bdc574; v=0; _tb_token_=757333761b373; dnk=lxq73061; mt=np=&ci=67_1; _cc_=VT5L2FSpdA%3D%3D; _mw_us_time_=1526558443172; _m_h5_tk=5b749c3645f7cc5e989c466e8f5868da_1526560965078; _m_h5_tk_enc=41718aa35722929a1e7b28d065787c36; uc3=nk2=D9syBSLMJ2g%3D&id2=Vy0WpgKoUho%3D&vt3=F8dBz499zqErEeB2RHc%3D&lg2=UtASsssmOIJ0bQ%3D%3D; existShop=MTUyNjU1ODQ0Nw%3D%3D; sg=187; csg=4f11742f; cookie1=AiLU5buHwFeM5qqCC%2Bjl2NVNJQI7aGneBufNfrI5MZA%3D; unb=41572778; skt=9d647bb039b59bbc; _l_g_=Ug%3D%3D; _nk_=lxq73061; cookie17=Vy0WpgKoUho%3D; isg=BNDQjB3Jvq7onWKVi3ps5Z6eoRjiMXORjNNjJcqhnCv-BXCvcqmEcya32c3l1Wy7; Hm_lvt_ba7c84ce230944c13900faeba642b2b4=1526537576,1526558449; Hm_lpvt_ba7c84ce230944c13900faeba642b2b4=1526558449; uc1=cookie16=VT5L2FSpNgq6fDudInPRgavC%2BQ%3D%3D&cookie21=W5iHLLyFeYFnNZKBCYQf&cookie15=V32FPkk%2Fw0dUvg%3D%3D&existShop=true&pas=0&cookie14=UoTeOLwBY7utPQ%3D%3D&cart_m=0&tag=8&lng=zh_CN;',
			),
			
			// 断言: 比对请求相应的结果是否有效
			'assert'	=> array(
				// 比对响应中的数据, httpbody 是消息体, 后续可以扩展出 响应头等
				'part'	=> 'httpbody',
				// 比对的数据是什么格式, text 指字符串, 后续可扩展出 json,xml 可以根据自己的需求去扩展
				'type'	=> 'text',

				// 比较表达式: contain 表示包含 使用 strpos来验证, 后续可扩展出 正则或者 jsonpath,xmlpath之类的
				'expression'	=> 'contain',
				// 比对的内容 : 根据表达式来配合使用
				'content'	=> 'SUCCESS',
			),

		),

		array(

			'id'	=> 'guize002',
			'url'	=> 'http://portal.yacebao.com/forget.html',

			'httpOptions'	=> array(
			),
			
			'assert'	=> array(
				'part'	=> 'httpbody',
				'type'	=> 'text',
				'expression'	=> 'contain',
				'content'	=> '发送重置密码邮件',
			),

		),
	),
);