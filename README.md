# qingjian
a demo for proxy tiny task!

http://tvp.daxiangdaili.com/ip/?tid=559913587683584&num=10&filter=on&longlife=200
这是获取代理ip列表的网址（没有经过我们验证的）

http://www.daxiangdaili.com/api

```
1、检查代理端口是否开通
2、检查代理匿名类别（就是对方显示的ip是代理的还是我们的）
3、检查访问指定url能否返回指定格式的数据（多条规则）
4、定制执行，记录检查状态和时间，无效的删除，结果不够时从代理ip提供网址下载新的数据（比如固定要有100条可用的）
5、方便调用检查结果（你先放你服务器上，或者我给你个服务器，你给我一条http: url加参数调用返回ip的方式和 php 内部调用的方式）

url规则一:
https://h5api.m.taobao.com/h5/mtop.taobao.detail.getdetail/6.0/?jsv=2.4.11&appKey=12574478&t=1523935666735&sign=&api=mtop.taobao.detail.getdetail&v=6.0&ttid=2017%40htao_h5_1.0.0&type=jsonp&dataType=jsonp&callback=mtopjsonp1&data=%7B%22exParams%22%3A%22%7B%5C%22countryCode%5C%22%3A%5C%22CN%5C%22%7D%22%2C%22itemNumId%22%3A%2216204910274%22%7D

包括字符串：
SUCCESS

```
\
```
CREATE TABLE `qingjianproxys` (
   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
   `host` varchar(100) NOT NULL COMMENT '代理主机',
   `ht` tinyint(8) NOT NULL COMMENT '代理类型: 1 不显示客户端Ip;2 显示客户端Ip',
   `ruleid` varchar(100) NOT NULL COMMENT '规则标识',
   `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否可用',
   `updated_at` datetime DEFAULT NULL COMMENT '最后一次操作时间',
   PRIMARY KEY (`id`),
   KEY `ruleid288ehddsfsd4` (`ruleid`),
   KEY `statsdsinx877jjy7` (`status`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```
