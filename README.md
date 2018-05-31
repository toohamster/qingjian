#qingjian
a demo for proxy tiny task!


#### 概述
```
1、检查代理端口是否开通
2、检查代理匿名类别（就是对方显示的ip是代理的还是我们的）
3、检查访问指定url能否返回指定格式的数据（多条规则）
4、定制执行，记录检查状态和时间，无效的删除，结果不够时从代理ip提供网址下载新的数据（比如固定要有100条可用的）
5、方便调用检查结果（你先放你服务器上，或者我给你个服务器，你给我一条http: url加参数调用返回ip的方式和 php 内部调用的方式）

```

#### 代理获取地址
```
http://tvp.daxiangdaili.com/ip/?tid=559913587683584&num=10&filter=on&longlife=200
这是获取代理ip列表的网址（没有经过我们验证的）
http://www.daxiangdaili.com/api
```

#### 表结构
```
CREATE TABLE `qingjianproxys` (
   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
   `host` varchar(100) CHARACTER SET latin1 NOT NULL COMMENT '代理主机',
   `ht` tinyint(8) NOT NULL COMMENT '代理类型: 1 不显示客户端Ip;2 显示客户端Ip',
   `ruleid` varchar(100) CHARACTER SET latin1 NOT NULL COMMENT '规则标识',
   `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否可用',
   `updated_at` datetime DEFAULT NULL COMMENT '最后一次操作时间',
   PRIMARY KEY (`id`),
   UNIQUE KEY `undsjii997ijj` (`host`,`ruleid`),
   KEY `ruleid288ehddsfsd4` (`ruleid`),
   KEY `statsdsinx877jjy7` (`status`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

#### 配置
```
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
```

#### 执行代理抓取进程
```
// 切入到proxyrun.php所在目录下,执行如下命令
php proxyrun.php
```

#### web 接口调用
```
// web 接口调用
http://{yourdomain}/qingjian/proxyrun.php?ruleId={ruleId}&ht={ht}&length={length}

// 参数说明:
@param  string $ruleId 规则id
@param  int $ht     代理类型: 1 为不显示客户端Ip; 2为显示客户端Ip; -1 表示未知; 0 代表不检查
@param  int $length 数量  默认返回最新的5条数据

// 返回结果说明
[
	code: 0 , // 状态码为0表示调用成功, 非0表示调用失败
	msg: '',  // 信息字符串, 成功返回SUCCESS,失败时返回具体错误
	data: [   // 代理主机列表记录集

		{
			ruleid: : '', 	// 规则名称
			host: : '',   	// 代理主机
			ht: : '',		// 类型: 
			updated_at: ''  // 检测时间
		},
		...
	] 
]

```

#### PHP 内部调用
```
// PHP 内部调用
require_once dirname(__FILE__) .'/proxys.php';

$data = P::apiCall($ruleId, $ht, $length);

// 参数说明:
@param  string $ruleId 规则id
@param  int $ht     代理类型: 1 为不显示客户端Ip; 2为显示客户端Ip; -1 表示未知; 0 代表不检查
@param  int $length 数量  默认返回最新的5条数据

// 返回结果说明
array(
	code: 0 , // 状态码为0表示调用成功, 非0表示调用失败
	msg: '',  // 信息字符串, 成功返回SUCCESS,失败时返回具体错误
	data: array(   // 代理主机列表记录集

		array(
			ruleid: : '', 	// 规则名称
			host: : '',   	// 代理主机
			ht: : '',		// 类型: 
			updated_at: ''  // 检测时间
		),
		...
	) 
)
```

#### 演示实例
```
// 当前我在个人VPS上进行测试, 可访问 
// http://quick.lianzh.com/_tmp/qingjian/release/proxyrun.php?ruleId=guize001
// http://quick.lianzh.com/_tmp/qingjian/release/proxyrun.php?ruleId=guize002
来查看数据
```