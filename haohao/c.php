<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Smartivr json builder</title>
	<meta name="keywords" content="Smartivr json builder" />
	<meta name="description" content="Smartivr json builder." />
	<link href="http://ssdb.io/docs/css/bootstrap.min.css" rel="stylesheet" />
	<link href="http://ssdb.io/docs/css/style.css" rel="stylesheet" />
	<style type="text/css">
		div.bb {padding-left: 25px;}
		div.bb2 {padding-left: 25px;display: none;}
		div.bb3 {padding-left: 25px;}
	</style>
</head>
<body>

<!-- Fixed navbar -->
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<a class="navbar-brand" href="javascript:;">Smartivr json builder</a>
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
<p><span class="label label-warning" style="font-size: 120%;">注意</span></p>
<div class="alert alert-danger">
	要启用哪个设置就勾选配置项前的复选框, 系统只会生成勾选的配置项的内容
</div>
<hr />

<h2 id="c-asr"><input type="checkbox" id="asrMain"> ASR配置</h2>
<div class="bb asr-div">
	<h3 id="_3"><input type="checkbox" id="asr-xfyun"> 科大讯飞 webapi引擎</h3>
	<div class="bb2 asr-xfyun-div">
		<form id="asr-xfyun-form">			
			<div class="form-group">
			    <label for="asr-xfyun-error_thresholds">错误阈值: </label>
			    <input type="text" class="form-control" id="asr-xfyun-error_thresholds" placeholder="APPID连续失败多少次就停用这个账号。默认0：永不停用">
			</div>
			<div class="form-group">
				<label for="asr-xfyun-mode">keylist账户选择模式: </label>
				<span class="label label-warning">顺序就是第一个账户停用（连续失败次数超过阈值）的时候才会使用第二个账号</span>
				<select class="form-control" id="asr-xfyun-mode">
				  <option value="0">顺序</option>
				  <option value="1">轮询</option>
				</select>
			</div>
			<div class="form-group">
				<label for="asr-xfyun-allowdisable">允许禁用: </label>
				<span class="label label-warning">当所有账户连续失败次数超过阈值，是否停用这个ASR引擎。如果只配置了一个ASR引擎千万不要设置true，默认false</span>
				<select class="form-control" id="asr-xfyun-allowdisable">
				  <option value="1">是</option>
				  <option value="0" selected="selected">否</option>
				</select>
			</div>
			<div class="form-group">
			    <label for="asr-xfyun-connecttimeout">连接超时: </label>
			    <input type="text" class="form-control" id="asr-xfyun-connecttimeout" placeholder="">
			</div>
			<div class="form-group">
			    <label for="asr-xfyun-responsetimeout">等待识别超时: </label>
			    <input type="text" class="form-control" id="asr-xfyun-responsetimeout" placeholder="">
			</div>
			<div class="form-group">
			    <label for="asr-xfyun-scene">情景模式: </label>
			    <input type="text" class="form-control" id="asr-xfyun-scene" placeholder="">
			</div>
			<div class="form-group">
				<label for="asr-xfyun-semantic">是否使用语音语义接口: </label>
				<select class="form-control" id="asr-xfyun-semantic">
				  <option value="1">是</option>
				  <option value="0" selected="selected">否</option>
				</select>
			</div>
			<div class="form-group">
				<label for="asr-xfyun-aiui">是否使用aiui接口: </label>
				<select class="form-control" id="asr-xfyun-aiui">
				  <option value="1">是</option>
				  <option value="0" selected="selected">否</option>
				</select>
			</div>
			<div class="form-group">
			    <label for="asr-xfyun-engine">webaki用于方言支持: </label>
			    <input type="text" class="form-control" id="asr-xfyun-engine" placeholder="需要先讯飞开通  比如sms8k 普通话 lmz8k 四川话">
			</div>
			<div class="form-group">
				<label for="asr-xfyun-keylist">keylist: </label>
				<table class="table table-hover">
					<thead>
						<tr>
				          <th>#</th>
				          <th>id</th>
				          <th>secret</th>
				          <th><a class="btn btn-default v-keylist-rowadd" href="javascript:;" role="button">添加</a></th>
				        </tr>
					</thead>
					<tbody id="asr-xfyun-keylist">
						
					</tbody>
				</table>
			</div>
		</form>
	</div>

	<h3 id="_3"><input type="checkbox" id="asr-baidu"> 百度语音识别</h3>
	<div class="bb2 asr-baidu-div">
		<form id="asr-baidu-form">			
			<div class="form-group">
			    <label for="asr-baidu-error_thresholds">错误阈值: </label>
			    <input type="text" class="form-control" id="asr-baidu-error_thresholds" placeholder="APPID连续失败多少次就停用这个账号。默认0：永不停用">
			</div>
			<div class="form-group">
				<label for="asr-baidu-mode">keylist账户选择模式: </label>
				<span class="label label-warning">顺序就是第一个账户停用（连续失败次数超过阈值）的时候才会使用第二个账号</span>
				<select class="form-control" id="asr-baidu-mode">
				  <option value="0">顺序</option>
				  <option value="1">轮询</option>
				</select>
			</div>
			<div class="form-group">
				<label for="asr-baidu-allowdisable">允许禁用: </label>
				<span class="label label-warning">当所有账户连续失败次数超过阈值，是否停用这个ASR引擎。如果只配置了一个ASR引擎千万不要设置true，默认false</span>
				<select class="form-control" id="asr-baidu-allowdisable">
				  <option value="1">是</option>
				  <option value="0" selected="selected">否</option>
				</select>
			</div>
			<div class="form-group">
			    <label for="asr-baidu-connecttimeout">连接超时: </label>
			    <input type="text" class="form-control" id="asr-baidu-connecttimeout" placeholder="">
			</div>
			<div class="form-group">
			    <label for="asr-baidu-responsetimeout">等待识别超时: </label>
			    <input type="text" class="form-control" id="asr-baidu-responsetimeout" placeholder="">
			</div>
			<div class="form-group">
			    <label for="asr-baidu-cuid">cuid: </label>
			    <input type="text" class="form-control" id="asr-baidu-cuid" placeholder="随便写一个百度后台统计用的">
			</div>
			<div class="form-group">
				<label for="asr-baidu-keylist">keylist: </label>
				<table class="table table-hover">
					<thead>
						<tr>
				          <th>#</th>
				          <th>id</th>
				          <th>secret</th>
				          <th><a class="btn btn-default v-keylist-rowadd" href="javascript:;" role="button">添加</a></th>
				        </tr>
					</thead>
					<tbody id="asr-baidu-keylist">
						
					</tbody>
				</table>
			</div>
		</form>
	</div>

	<h3 id="_3"><input type="checkbox" id="asr-aliyun"> 阿里云识别引擎</h3>
	<div class="bb2 asr-aliyun-div">
		<form id="asr-aliyun-form">			
			<div class="form-group">
			    <label for="asr-aliyun-error_thresholds">错误阈值: </label>
			    <input type="text" class="form-control" id="asr-aliyun-error_thresholds" placeholder="APPID连续失败多少次就停用这个账号。默认0：永不停用">
			</div>
			
			<div class="form-group">
				<label for="asr-aliyun-allowdisable">允许禁用: </label>
				<span class="label label-warning">当所有账户连续失败次数超过阈值，是否停用这个ASR引擎。如果只配置了一个ASR引擎千万不要设置true，默认false</span>
				<select class="form-control" id="asr-aliyun-allowdisable">
				  <option value="1">是</option>
				  <option value="0" selected="selected">否</option>
				</select>
			</div>
			<div class="form-group">
				<label for="asr-aliyun-mode">keylist账户选择模式: </label>
				<span class="label label-warning">顺序就是第一个账户停用（连续失败次数超过阈值）的时候才会使用第二个账号</span>
				<select class="form-control" id="asr-aliyun-mode">
				  <option value="0">顺序</option>
				  <option value="1">轮询</option>
				</select>
			</div>
			<div class="form-group">
			    <label for="asr-aliyun-connecttimeout">连接超时: </label>
			    <input type="text" class="form-control" id="asr-aliyun-connecttimeout" placeholder="">
			</div>
			<div class="form-group">
			    <label for="asr-aliyun-responsetimeout">等待识别超时: </label>
			    <input type="text" class="form-control" id="asr-aliyun-responsetimeout" placeholder="">
			</div>
			<div class="form-group">
				<label for="asr-aliyun-keylist">keylist: </label>
				<table class="table table-hover">
					<thead>
						<tr>
				          <th>#</th>
				          <th>id</th>
				          <th>secret</th>
				          <th><a class="btn btn-default v-keylist-rowadd" href="javascript:;" role="button">添加</a></th>
				        </tr>
					</thead>
					<tbody id="asr-aliyun-keylist">
						
					</tbody>
				</table>
			</div>
		</form>
	</div>

	<h3 id="_3"><input type="checkbox" id="asr-iflytek"> 讯飞SDK ASR接口</h3>
	<div class="bb2 asr-iflytek-div">
		<form id="asr-iflytek-form">			
			<div class="form-group">
				<label for="asr-iflytek-mode">keylist账户选择模式: </label>
				<span class="label label-warning">顺序就是第一个账户停用（连续失败次数超过阈值）的时候才会使用第二个账号</span>
				<select class="form-control" id="asr-iflytek-mode">
				  <option value="0">顺序</option>
				  <option value="1">轮询</option>
				</select>
			</div>
			<div class="form-group">
			    <label for="asr-iflytek-workdir">iflytek程序所在目录: </label>
			    <input type="text" class="form-control" id="asr-iflytek-workdir" placeholder="libmsc.so 也必须放这个目录 （libmsc.so和appid必须对应）">
			</div>
			<div class="form-group">
			    <label for="asr-iflytek-responsetimeout">等待识别超时: </label>
			    <input type="text" class="form-control" id="asr-iflytek-responsetimeout" placeholder="">
			</div>
			
			<div class="form-group">
				<div class="bs-callout bs-callout-info" id="callout-xref-input-group">
				    <h4>ASR程序路径和参数</h4>
				    <p>
				    	<figure class="highlight">
				    		<pre>
				    			<code class="language-html" data-lang="html">
		//ASR程序路径和参数
		//参数说明
		//language: 语言
		//       zh_cn：简体中文
		//       zh_tw：繁体中文
		//        en_us：英文
		//       默认值：zh_cn
		//accent:   方言
		//       mandarin：普通话
		//       cantonese：粤语
		//       lmz：四川话
		//       默认值：mandarin	
				    			</code>
				    		</pre>
				    	</figure>	
				    </p>
				</div>
				<textarea class="form-control" rows="3" id="asr-iflytek-command" placeholder="./iflytek type=${type} appid=${appid} encoding=utf8 language=zh_cn accent=mandarin"></textarea>
			</div>
			<div class="form-group">
				<label for="asr-iflytek-keylist">keylist: </label>
				<table class="table table-hover">
					<thead>
						<tr>
				          <th>#</th>
				          <th>id</th>
				          <th>
				          	<a class="btn btn-default v-keylist2-rowadd" href="javascript:;" role="button">添加</a>
				          </th>
				        </tr>
					</thead>
					<tbody id="asr-iflytek-keylist">
						
					</tbody>
				</table>
			</div>
		</form>
	</div>

	<h3 id="_3"><input type="checkbox" id="asr-iflytek2"> 讯飞SDK ASR接口2</h3>
	<div class="bb2 asr-iflytek2-div">
		<form id="asr-iflytek2-form">			
			<div class="form-group">
				<label for="asr-iflytek2-mode">keylist账户选择模式: </label>
				<span class="label label-warning">顺序就是第一个账户停用（连续失败次数超过阈值）的时候才会使用第二个账号</span>
				<select class="form-control" id="asr-iflytek2-mode">
				  <option value="0">顺序</option>
				  <option value="1">轮询</option>
				</select>
			</div>
			<div class="form-group">
			    <label for="asr-iflytek2-workdir">iflytek程序所在目录: </label>
			    <input type="text" class="form-control" id="asr-iflytek2-workdir" placeholder="libmsc.so 也必须放这个目录 （libmsc.so和appid必须对应）">
			</div>
			<div class="form-group">
			    <label for="asr-iflytek2-responsetimeout">等待识别超时: </label>
			    <input type="text" class="form-control" id="asr-iflytek2-responsetimeout" placeholder="">
			</div>
			
			<div class="form-group">
				<div class="bs-callout bs-callout-info" id="callout-xref-input-group">
				    <h4>ASR程序路径和参数</h4>
				    <p>
				    	<figure class="highlight">
				    		<pre>
				    			<code class="language-html" data-lang="html">
		//ASR程序路径和参数
		//参数说明
		//language: 语言
		//       zh_cn：简体中文
		//       zh_tw：繁体中文
		//        en_us：英文
		//       默认值：zh_cn
		//accent:   方言
		//       mandarin：普通话
		//       cantonese：粤语
		//       lmz：四川话
		//       默认值：mandarin	
				    			</code>
				    		</pre>
				    	</figure>	
				    </p>
				</div>
				<textarea class="form-control" rows="3" id="asr-iflytek2-command" placeholder="./iflytek type=${type} appid=${appid} encoding=utf8 language=zh_cn accent=mandarin"></textarea>
			</div>
			<div class="form-group">
				<label for="asr-iflytek2-keylist">keylist: </label>
				<table class="table table-hover">
					<thead>
						<tr>
				          <th>#</th>
				          <th>id</th>
				          <th>
				          	<a class="btn btn-default v-keylist2-rowadd" href="javascript:;" role="button">添加</a>
				          </th>
				        </tr>
					</thead>
					<tbody id="asr-iflytek2-keylist">
						
					</tbody>
				</table>
			</div>
		</form>
	</div>

	<h3 id="_3"><input type="checkbox" id="asr-iflytek3"> 讯飞SDK ASR接口3</h3>
	<div class="bb2 asr-iflytek3-div">
		<form id="asr-iflytek3-form">			
			<div class="form-group">
				<label for="asr-iflytek3-mode">keylist账户选择模式: </label>
				<span class="label label-warning">顺序就是第一个账户停用（连续失败次数超过阈值）的时候才会使用第二个账号</span>
				<select class="form-control" id="asr-iflytek3-mode">
				  <option value="0">顺序</option>
				  <option value="1">轮询</option>
				</select>
			</div>
			<div class="form-group">
			    <label for="asr-iflytek3-workdir">iflytek程序所在目录: </label>
			    <input type="text" class="form-control" id="asr-iflytek3-workdir" placeholder="libmsc.so 也必须放这个目录 （libmsc.so和appid必须对应）">
			</div>
			<div class="form-group">
			    <label for="asr-iflytek3-responsetimeout">等待识别超时: </label>
			    <input type="text" class="form-control" id="asr-iflytek3-responsetimeout" placeholder="">
			</div>
			
			<div class="form-group">
				<div class="bs-callout bs-callout-info" id="callout-xref-input-group">
				    <h4>ASR程序路径和参数</h4>
				    <p>
				    	<figure class="highlight">
				    		<pre>
				    			<code class="language-html" data-lang="html">
		//ASR程序路径和参数
		//参数说明
		//language: 语言
		//       zh_cn：简体中文
		//       zh_tw：繁体中文
		//        en_us：英文
		//       默认值：zh_cn
		//accent:   方言
		//       mandarin：普通话
		//       cantonese：粤语
		//       lmz：四川话
		//       默认值：mandarin	
				    			</code>
				    		</pre>
				    	</figure>	
				    </p>
				</div>
				<textarea class="form-control" rows="3" id="asr-iflytek3-command" placeholder="./iflytek type=${type} appid=${appid} encoding=utf8 language=zh_cn accent=mandarin"></textarea>
			</div>
			<div class="form-group">
				<label for="asr-iflytek3-keylist">keylist: </label>
				<table class="table table-hover">
					<thead>
						<tr>
				          <th>#</th>
				          <th>id</th>
				          <th>
				          	<a class="btn btn-default v-keylist2-rowadd" href="javascript:;" role="button">添加</a>
				          </th>
				        </tr>
					</thead>
					<tbody id="asr-iflytek3-keylist">
						
					</tbody>
				</table>
			</div>
		</form>
	</div>

	<h3 id="_3"><input type="checkbox" id="asr-iflytek4"> 讯飞SDK ASR接口4</h3>
	<div class="bb2 asr-iflytek4-div">
		<form id="asr-iflytek4-form">			
			<div class="form-group">
				<label for="asr-iflytek4-mode">keylist账户选择模式: </label>
				<span class="label label-warning">顺序就是第一个账户停用（连续失败次数超过阈值）的时候才会使用第二个账号</span>
				<select class="form-control" id="asr-iflytek4-mode">
				  <option value="0">顺序</option>
				  <option value="1">轮询</option>
				</select>
			</div>
			<div class="form-group">
			    <label for="asr-iflytek4-workdir">iflytek程序所在目录: </label>
			    <input type="text" class="form-control" id="asr-iflytek4-workdir" placeholder="libmsc.so 也必须放这个目录 （libmsc.so和appid必须对应）">
			</div>
			<div class="form-group">
			    <label for="asr-iflytek4-responsetimeout">等待识别超时: </label>
			    <input type="text" class="form-control" id="asr-iflytek4-responsetimeout" placeholder="">
			</div>
			
			<div class="form-group">
				<div class="bs-callout bs-callout-info" id="callout-xref-input-group">
				    <h4>ASR程序路径和参数</h4>
				    <p>
				    	<figure class="highlight">
				    		<pre>
				    			<code class="language-html" data-lang="html">
		//ASR程序路径和参数
		//参数说明
		//language: 语言
		//       zh_cn：简体中文
		//       zh_tw：繁体中文
		//        en_us：英文
		//       默认值：zh_cn
		//accent:   方言
		//       mandarin：普通话
		//       cantonese：粤语
		//       lmz：四川话
		//       默认值：mandarin	
				    			</code>
				    		</pre>
				    	</figure>	
				    </p>
				</div>
				<textarea class="form-control" rows="3" id="asr-iflytek4-command" placeholder="./iflytek type=${type} appid=${appid} encoding=utf8 language=zh_cn accent=mandarin"></textarea>
			</div>
			<div class="form-group">
				<label for="asr-iflytek4-keylist">keylist: </label>
				<table class="table table-hover">
					<thead>
						<tr>
				          <th>#</th>
				          <th>id</th>
				          <th>
				          	<a class="btn btn-default v-keylist2-rowadd" href="javascript:;" role="button">添加</a>
				          </th>
				        </tr>
					</thead>
					<tbody id="asr-iflytek4-keylist">
						
					</tbody>
				</table>
			</div>
		</form>
	</div>

	<h3 id="_3"><input type="checkbox" id="asr-iflytek5"> 讯飞SDK ASR接口5</h3>
	<div class="bb2 asr-iflytek5-div">
		<form id="asr-iflytek5-form">			
			<div class="form-group">
				<label for="asr-iflytek5-mode">keylist账户选择模式: </label>
				<span class="label label-warning">顺序就是第一个账户停用（连续失败次数超过阈值）的时候才会使用第二个账号</span>
				<select class="form-control" id="asr-iflytek5-mode">
				  <option value="0">顺序</option>
				  <option value="1">轮询</option>
				</select>
			</div>
			<div class="form-group">
			    <label for="asr-iflytek5-workdir">iflytek程序所在目录: </label>
			    <input type="text" class="form-control" id="asr-iflytek5-workdir" placeholder="libmsc.so 也必须放这个目录 （libmsc.so和appid必须对应）">
			</div>
			<div class="form-group">
			    <label for="asr-iflytek5-responsetimeout">等待识别超时: </label>
			    <input type="text" class="form-control" id="asr-iflytek5-responsetimeout" placeholder="">
			</div>
			
			<div class="form-group">
				<div class="bs-callout bs-callout-info" id="callout-xref-input-group">
				    <h4>ASR程序路径和参数</h4>
				    <p>
				    	<figure class="highlight">
				    		<pre>
				    			<code class="language-html" data-lang="html">
		//ASR程序路径和参数
		//参数说明
		//language: 语言
		//       zh_cn：简体中文
		//       zh_tw：繁体中文
		//        en_us：英文
		//       默认值：zh_cn
		//accent:   方言
		//       mandarin：普通话
		//       cantonese：粤语
		//       lmz：四川话
		//       默认值：mandarin	
				    			</code>
				    		</pre>
				    	</figure>	
				    </p>
				</div>
				<textarea class="form-control" rows="3" id="asr-iflytek5-command" placeholder="./iflytek type=${type} appid=${appid} encoding=utf8 language=zh_cn accent=mandarin"></textarea>
			</div>
			<div class="form-group">
				<label for="asr-iflytek5-keylist">keylist: </label>
				<table class="table table-hover">
					<thead>
						<tr>
				          <th>#</th>
				          <th>id</th>
				          <th>
				          	<a class="btn btn-default v-keylist2-rowadd" href="javascript:;" role="button">添加</a>
				          </th>
				        </tr>
					</thead>
					<tbody id="asr-iflytek5-keylist">
						
					</tbody>
				</table>
			</div>
		</form>
	</div>

	<h3 id="_3"><input type="checkbox" id="asr-iflytek6"> 讯飞SDK ASR接口6</h3>
	<div class="bb2 asr-iflytek6-div">
		<form id="asr-iflytek6-form">			
			<div class="form-group">
				<label for="asr-iflytek6-mode">keylist账户选择模式: </label>
				<span class="label label-warning">顺序就是第一个账户停用（连续失败次数超过阈值）的时候才会使用第二个账号</span>
				<select class="form-control" id="asr-iflytek6-mode">
				  <option value="0">顺序</option>
				  <option value="1">轮询</option>
				</select>
			</div>
			<div class="form-group">
			    <label for="asr-iflytek6-workdir">iflytek程序所在目录: </label>
			    <input type="text" class="form-control" id="asr-iflytek6-workdir" placeholder="libmsc.so 也必须放这个目录 （libmsc.so和appid必须对应）">
			</div>
			<div class="form-group">
			    <label for="asr-iflytek6-responsetimeout">等待识别超时: </label>
			    <input type="text" class="form-control" id="asr-iflytek6-responsetimeout" placeholder="">
			</div>
			
			<div class="form-group">
				<div class="bs-callout bs-callout-info" id="callout-xref-input-group">
				    <h4>ASR程序路径和参数</h4>
				    <p>
				    	<figure class="highlight">
				    		<pre>
				    			<code class="language-html" data-lang="html">
		//ASR程序路径和参数
		//参数说明
		//language: 语言
		//       zh_cn：简体中文
		//       zh_tw：繁体中文
		//        en_us：英文
		//       默认值：zh_cn
		//accent:   方言
		//       mandarin：普通话
		//       cantonese：粤语
		//       lmz：四川话
		//       默认值：mandarin	
				    			</code>
				    		</pre>
				    	</figure>	
				    </p>
				</div>
				<textarea class="form-control" rows="3" id="asr-iflytek6-command" placeholder="./iflytek type=${type} appid=${appid} encoding=utf8 language=zh_cn accent=mandarin"></textarea>
			</div>
			<div class="form-group">
				<label for="asr-iflytek6-keylist">keylist: </label>
				<table class="table table-hover">
					<thead>
						<tr>
				          <th>#</th>
				          <th>id</th>
				          <th>
				          	<a class="btn btn-default v-keylist2-rowadd" href="javascript:;" role="button">添加</a>
				          </th>
				        </tr>
					</thead>
					<tbody id="asr-iflytek6-keylist">
						
					</tbody>
				</table>
			</div>
		</form>
	</div>

	<h3 id="_3"><input type="checkbox" id="asr-iflytek7"> 讯飞SDK ASR接口7</h3>
	<div class="bb2 asr-iflytek7-div">
		<form id="asr-iflytek7-form">			
			<div class="form-group">
				<label for="asr-iflytek7-mode">keylist账户选择模式: </label>
				<span class="label label-warning">顺序就是第一个账户停用（连续失败次数超过阈值）的时候才会使用第二个账号</span>
				<select class="form-control" id="asr-iflytek7-mode">
				  <option value="0">顺序</option>
				  <option value="1">轮询</option>
				</select>
			</div>
			<div class="form-group">
			    <label for="asr-iflytek7-workdir">iflytek程序所在目录: </label>
			    <input type="text" class="form-control" id="asr-iflytek7-workdir" placeholder="libmsc.so 也必须放这个目录 （libmsc.so和appid必须对应）">
			</div>
			<div class="form-group">
			    <label for="asr-iflytek7-responsetimeout">等待识别超时: </label>
			    <input type="text" class="form-control" id="asr-iflytek7-responsetimeout" placeholder="">
			</div>
			
			<div class="form-group">
				<div class="bs-callout bs-callout-info" id="callout-xref-input-group">
				    <h4>ASR程序路径和参数</h4>
				    <p>
				    	<figure class="highlight">
				    		<pre>
				    			<code class="language-html" data-lang="html">
		//ASR程序路径和参数
		//参数说明
		//language: 语言
		//       zh_cn：简体中文
		//       zh_tw：繁体中文
		//        en_us：英文
		//       默认值：zh_cn
		//accent:   方言
		//       mandarin：普通话
		//       cantonese：粤语
		//       lmz：四川话
		//       默认值：mandarin	
				    			</code>
				    		</pre>
				    	</figure>	
				    </p>
				</div>
				<textarea class="form-control" rows="3" id="asr-iflytek7-command" placeholder="./iflytek type=${type} appid=${appid} encoding=utf8 language=zh_cn accent=mandarin"></textarea>
			</div>
			<div class="form-group">
				<label for="asr-iflytek7-keylist">keylist: </label>
				<table class="table table-hover">
					<thead>
						<tr>
				          <th>#</th>
				          <th>id</th>
				          <th>
				          	<a class="btn btn-default v-keylist2-rowadd" href="javascript:;" role="button">添加</a>
				          </th>
				        </tr>
					</thead>
					<tbody id="asr-iflytek7-keylist">
						
					</tbody>
				</table>
			</div>
		</form>
	</div>
	
	<h3 id="_3"><input type="checkbox" id="asr-iflytek8"> 讯飞SDK ASR接口8</h3>
	<div class="bb2 asr-iflytek8-div">
		<form id="asr-iflytek8-form">			
			<div class="form-group">
				<label for="asr-iflytek8-mode">keylist账户选择模式: </label>
				<span class="label label-warning">顺序就是第一个账户停用（连续失败次数超过阈值）的时候才会使用第二个账号</span>
				<select class="form-control" id="asr-iflytek8-mode">
				  <option value="0">顺序</option>
				  <option value="1">轮询</option>
				</select>
			</div>
			<div class="form-group">
			    <label for="asr-iflytek8-workdir">iflytek程序所在目录: </label>
			    <input type="text" class="form-control" id="asr-iflytek8-workdir" placeholder="libmsc.so 也必须放这个目录 （libmsc.so和appid必须对应）">
			</div>
			<div class="form-group">
			    <label for="asr-iflytek8-responsetimeout">等待识别超时: </label>
			    <input type="text" class="form-control" id="asr-iflytek8-responsetimeout" placeholder="">
			</div>
			
			<div class="form-group">
				<div class="bs-callout bs-callout-info" id="callout-xref-input-group">
				    <h4>ASR程序路径和参数</h4>
				    <p>
				    	<figure class="highlight">
				    		<pre>
				    			<code class="language-html" data-lang="html">
		//ASR程序路径和参数
		//参数说明
		//language: 语言
		//       zh_cn：简体中文
		//       zh_tw：繁体中文
		//        en_us：英文
		//       默认值：zh_cn
		//accent:   方言
		//       mandarin：普通话
		//       cantonese：粤语
		//       lmz：四川话
		//       默认值：mandarin	
				    			</code>
				    		</pre>
				    	</figure>	
				    </p>
				</div>
				<textarea class="form-control" rows="3" id="asr-iflytek8-command" placeholder="./iflytek type=${type} appid=${appid} encoding=utf8 language=zh_cn accent=mandarin"></textarea>
			</div>
			<div class="form-group">
				<label for="asr-iflytek8-keylist">keylist: </label>
				<table class="table table-hover">
					<thead>
						<tr>
				          <th>#</th>
				          <th>id</th>
				          <th>
				          	<a class="btn btn-default v-keylist2-rowadd" href="javascript:;" role="button">添加</a>
				          </th>
				        </tr>
					</thead>
					<tbody id="asr-iflytek8-keylist">
						
					</tbody>
				</table>
			</div>
		</form>
	</div>
	
	<h3 id="_3"><input type="checkbox" id="asr-iflytek9"> 讯飞SDK ASR接口9</h3>
	<div class="bb2 asr-iflytek9-div">
		<form id="asr-iflytek9-form">			
			<div class="form-group">
				<label for="asr-iflytek9-mode">keylist账户选择模式: </label>
				<span class="label label-warning">顺序就是第一个账户停用（连续失败次数超过阈值）的时候才会使用第二个账号</span>
				<select class="form-control" id="asr-iflytek9-mode">
				  <option value="0">顺序</option>
				  <option value="1">轮询</option>
				</select>
			</div>
			<div class="form-group">
			    <label for="asr-iflytek9-workdir">iflytek程序所在目录: </label>
			    <input type="text" class="form-control" id="asr-iflytek9-workdir" placeholder="libmsc.so 也必须放这个目录 （libmsc.so和appid必须对应）">
			</div>
			<div class="form-group">
			    <label for="asr-iflytek9-responsetimeout">等待识别超时: </label>
			    <input type="text" class="form-control" id="asr-iflytek9-responsetimeout" placeholder="">
			</div>
			
			<div class="form-group">
				<div class="bs-callout bs-callout-info" id="callout-xref-input-group">
				    <h4>ASR程序路径和参数</h4>
				    <p>
				    	<figure class="highlight">
				    		<pre>
				    			<code class="language-html" data-lang="html">
		//ASR程序路径和参数
		//参数说明
		//language: 语言
		//       zh_cn：简体中文
		//       zh_tw：繁体中文
		//        en_us：英文
		//       默认值：zh_cn
		//accent:   方言
		//       mandarin：普通话
		//       cantonese：粤语
		//       lmz：四川话
		//       默认值：mandarin	
				    			</code>
				    		</pre>
				    	</figure>	
				    </p>
				</div>
				<textarea class="form-control" rows="3" id="asr-iflytek9-command" placeholder="./iflytek type=${type} appid=${appid} encoding=utf9 language=zh_cn accent=mandarin"></textarea>
			</div>
			<div class="form-group">
				<label for="asr-iflytek9-keylist">keylist: </label>
				<table class="table table-hover">
					<thead>
						<tr>
				          <th>#</th>
				          <th>id</th>
				          <th>
				          	<a class="btn btn-default v-keylist2-rowadd" href="javascript:;" role="button">添加</a>
				          </th>
				        </tr>
					</thead>
					<tbody id="asr-iflytek9-keylist">
						
					</tbody>
				</table>
			</div>
		</form>
	</div>

	<h3>enable列表: </h3>
	<input type="checkbox" name="asr-enable" value="xfyun"> 科大讯飞
	<input type="checkbox" name="asr-enable" value="baidu"> 百度语音识别
	<input type="checkbox" name="asr-enable" value="aliyun"> 阿里云识别引擎
	<input type="checkbox" name="asr-enable" value="iflytek"> 讯飞SDK ASR接口
	<input type="checkbox" name="asr-enable" value="iflytek2"> 讯飞SDK ASR接口2
	<input type="checkbox" name="asr-enable" value="iflytek3"> 讯飞SDK ASR接口3
	<input type="checkbox" name="asr-enable" value="iflytek4"> 讯飞SDK ASR接口4
	<input type="checkbox" name="asr-enable" value="iflytek5"> 讯飞SDK ASR接口5
	<input type="checkbox" name="asr-enable" value="iflytek6"> 讯飞SDK ASR接口6
	<input type="checkbox" name="asr-enable" value="iflytek7"> 讯飞SDK ASR接口7
	<input type="checkbox" name="asr-enable" value="iflytek8"> 讯飞SDK ASR接口8
	<input type="checkbox" name="asr-enable" value="iflytek9"> 讯飞SDK ASR接口9

	<h3>enable列表里账户选择模式: </h3>
		<div class="label label-warning">这个mode 指的是 enable列表里账户选择模式。 顺序：就是第一个ASR识别失败的时候才使用下一个</div>
	<select class="form-control" id="asr-enable-mode">
	  <option value="0">顺序</option>
	  <option value="1">轮询</option>
	</select>	
</div>
<hr />

<h2 id="c-tts"><input type="checkbox" id="ttsMain"> TTS配置</h2>
<div class="bb tts-div">
	<h3 id="_3"><input type="checkbox" id="tts-baidu"> 百度TTS接口</h3>
	<div class="bb2 tts-baidu-div">
		<form id="tts-baidu-form">
			<div class="form-group">
				<label for="tts-baidu-mode">keylist账户选择模式: </label>
				<span class="label label-warning">顺序就是第一个账户停用（连续失败次数超过阈值）的时候才会使用第二个账号</span>
				<select class="form-control" id="tts-baidu-mode">
				  <option value="0">顺序</option>
				  <option value="1">轮询</option>
				</select>
			</div>				
			<div class="form-group">
			    <label for="tts-baidu-voice">发音: </label>
			    <input type="text" class="form-control" id="tts-baidu-voice" placeholder="xiaogang - 男，xiaoyun - 女">
			</div>
			
			<div class="form-group">
			    <label for="tts-baidu-connecttimeout">连接超时: </label>
			    <input type="text" class="form-control" id="tts-baidu-connecttimeout" placeholder="">
			</div>
			<div class="form-group">
			    <label for="tts-baidu-responsetimeout">等待识别超时: </label>
			    <input type="text" class="form-control" id="tts-baidu-responsetimeout" placeholder="">
			</div>
			<div class="form-group">
			    <label for="tts-baidu-cuid">cuid: </label>
			    <input type="text" class="form-control" id="tts-baidu-cuid" placeholder="随便写一个百度后台统计用的">
			</div>
			<div class="form-group">
				<label for="tts-baidu-keylist">keylist: </label>
				<table class="table table-hover">
					<thead>
						<tr>
				          <th>#</th>
				          <th>id</th>
				          <th>secret</th>
				          <th><a class="btn btn-default v-keylist-rowadd" href="javascript:;" role="button">添加</a></th>
				        </tr>
					</thead>
					<tbody id="tts-baidu-keylist">
						
					</tbody>
				</table>
			</div>
		</form>
	</div>

	<h3 id="_3"><input type="checkbox" id="tts-iflytek"> 讯飞SDK TTS接口</h3>
	<div class="bb2 tts-iflytek-div">
		<form id="tts-iflytek-form">
			<div class="form-group">
				<label for="tts-iflytek-mode">keylist账户选择模式: </label>
				<span class="label label-warning">顺序就是第一个账户停用（连续失败次数超过阈值）的时候才会使用第二个账号</span>
				<select class="form-control" id="tts-iflytek-mode">
				  <option value="0">顺序</option>
				  <option value="1">轮询</option>
				</select>
			</div>
			<div class="form-group">
			    <label for="tts-iflytek-workdir">iflytek程序所在目录: </label>
			    <input type="text" class="form-control" id="tts-iflytek-workdir" placeholder="libmsc.so 也必须放这个目录 （libmsc.so和appid必须对应）">
			</div>				
			<div class="form-group">
			    <label for="tts-iflytek-voice">发音: </label>
			    <input type="text" class="form-control" id="tts-iflytek-voice" placeholder="xiaogang - 男，xiaoyun - 女">
			</div>
			<div class="form-group">
			    <label for="tts-iflytek-command">TTS程序路径和参数: </label>
			    <input type="text" class="form-control" id="tts-iflytek-command" placeholder="iflytek type=${type} appid=${appid} speed=50 encoding=utf8 file=${file} voice=${voice}">
			</div>			
			<div class="form-group">
				<label for="tts-iflytek-keylist">keylist: </label>
				<table class="table table-hover">
					<thead>
						<tr>
				          <th>#</th>
				          <th>id</th>
				          <th>
				          	<a class="btn btn-default v-keylist2-rowadd" href="javascript:;" role="button">添加</a>
				          </th>
				        </tr>
					</thead>
					<tbody id="tts-iflytek-keylist">
						
					</tbody>
				</table>
			</div>
		</form>
	</div>

	<h3 id="_3"><input type="checkbox" id="tts-aliyun"> 阿里云TTS接口</h3>
	<div class="bb2 tts-aliyun-div">
		<form id="tts-aliyun-form">
			<div class="form-group">
				<label for="tts-aliyun-mode">keylist账户选择模式: </label>
				<span class="label label-warning">顺序就是第一个账户停用（连续失败次数超过阈值）的时候才会使用第二个账号</span>
				<select class="form-control" id="tts-aliyun-mode">
				  <option value="0">顺序</option>
				  <option value="1">轮询</option>
				</select>
			</div>				
			<div class="form-group">
			    <label for="tts-aliyun-voice">发音: </label>
			    <input type="text" class="form-control" id="tts-aliyun-voice" placeholder="xiaogang - 男，xiaoyun - 女">
			</div>
			
			<div class="form-group">
			    <label for="tts-aliyun-connecttimeout">连接超时: </label>
			    <input type="text" class="form-control" id="tts-aliyun-connecttimeout" placeholder="">
			</div>
			<div class="form-group">
			    <label for="tts-aliyun-responsetimeout">等待识别超时: </label>
			    <input type="text" class="form-control" id="tts-aliyun-responsetimeout" placeholder="">
			</div>
			<div class="form-group">
				<label for="tts-aliyun-keylist">keylist: </label>
				<table class="table table-hover">
					<thead>
						<tr>
				          <th>#</th>
				          <th>id</th>
				          <th>secret</th>
				          <th><a class="btn btn-default v-keylist-rowadd" href="javascript:;" role="button">添加</a></th>
				        </tr>
					</thead>
					<tbody id="tts-aliyun-keylist">
						
					</tbody>
				</table>
			</div>
		</form>
	</div>

	<h3>sox程序路径: </h3>
	<label for="tts-sox">路径: </label>
    <input type="text" class="form-control" id="tts-sox" placeholder="baidu返回的是mp3格式，用sox命令转换成wav，其他TTS不需要">

	<h3>enable列表: </h3>
	<input type="checkbox" name="tts-enable" value="baidu"> 百度TTS接口
	<input type="checkbox" name="tts-enable" value="iflytek"> 讯飞SDK TTS接口
	<input type="checkbox" name="tts-enable" value="aliyun"> 阿里云TTS接口

	<h3>enable列表里账户选择模式: </h3>
		<div class="label label-warning">这个mode 指的是 enable列表里账户选择模式。 顺序：就是第一个ASR识别失败的时候才使用下一个</div>
	<select class="form-control" id="tts-enable-mode">
	  <option value="0">顺序</option>
	  <option value="1">轮询</option>
	</select>
</div>
<hr />

<h2 id="c-system"><input type="checkbox" id="systemMain"> 系统配置</h2>
<div class="alert alert-info">
	系统配置只有默认配置文件里面的才有效。start_asr,custom_playback指定的配置文件不会读取的system里面内容的。
</div>
<div class="bb system-div">
	<h3 id="_3"><input type="checkbox" id="system-record"> record</h3>
	<div class="bb2 system-record-div">
		<form id="system-record-form">			
			<div class="form-group">
			    <label for="system-record-path">路径: </label>
			    <input type="text" class="form-control" id="system-record-path" placeholder="把提交到ASR识别的书保存录音文件路径,不需要录音就不要设置">
			</div>
			<div class="form-group">
			    <label for="system-record-folderformat">每天创建的目录格式: </label>
			    <input type="text" class="form-control" id="system-record-folderformat" placeholder="%Y%m%d">
			</div>
		</form>
	</div>

	<h3 id="_3"><input type="checkbox" id="system-tts"> tts</h3>
	<div class="bb2 system-tts-div">
		<form id="system-tts-form">			
			<div class="form-group">
			    <label for="system-tts-path">路径: </label>
			    <input type="text" class="form-control" id="system-tts-path" placeholder="windows 路径不要用\用/比如 c:/ttsdir">
			</div>
		</form>
	</div>

	<h3 id="_3"><input type="checkbox" id="system-gender"> gender</h3>
	<div class="bb2 system-gender-div">
		<form id="system-gender-form">			
			<div class="form-group">
			    <label for="system-gender-path">路径: </label>
			    <input type="text" class="form-control" id="system-gender-path" placeholder="配置男女识别接口，如果不需要这个功能就留空">
			</div>
		</form>
	</div>
</div>

<hr />
<button class="btn btn-primary btn-lg btn-block" type="button" id="json-build-submit">提交</button>

<p>&nbsp;</p>

<div class="footer">
	Copyright &copy; 2018 - 2018 <a href="http://vb2005xu.iteye.com">cangshu</a>. All rights reserved.
	Updated: 2018-06-04 12:39:48 +0800	</div>

</div> <!-- /container -->

<script src="http://ssdb.io/docs/js/jquery-1.9.1.min.js"></script>
<script src="http://ssdb.io/docs/js/bootstrap.min.js"></script>
<script src="j.js"></script>
<script src="l.js?<?php echo time()?>"></script>
<script type="text/html" id="keylist-row1">
	<tr class="keyrow">
		<td class="v-index"></td>
		<td>
			<input type="text" class="form-control v-id">
		</td>
		<td>
			<input type="text" class="form-control v-secret">
		</td>
		<td>
			<a class="btn btn-default v-rowdel" href="javascript:;" role="button">删除</a>
		</td>
	</tr>
</script>
<script type="text/html" id="keylist-row2">
	<tr class="keyrow">
		<td class="v-index"></td>
		<td>
			<input type="text" class="form-control v-id">
		</td>
		<td>
			<a class="btn btn-default v-rowdel" href="javascript:;" role="button">删除</a>
		</td>
	</tr>
</script>

</body>
</html>