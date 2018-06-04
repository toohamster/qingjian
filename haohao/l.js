$(document).ready(function(){

var s = function(){this.d={};this.v=1}

s.cc = function(asr, tts, system)
{
	var a = {}
	if (asr) a.asr = asr;
	if (tts) a.tts = tts;
	if (system) a.system = system;
	return a
}

s.keylist = {
	aliyun_key: function(a,b){
		return {id: a, secret: b}
	},
	iflytek_key: function(a){
		return {id: a}
	},
	iflytek2_key: function(a){
		return {id: a}
	},
	baidu_key: function(a,b){
		return {id: a, secret: b}
	},
	xfyun: function(a,b){
		return {id: a, secret: b}
	}
}

s.system = {
	record: function(p,f){
		return {path: p, folderformat: f}
	},
	tts: function(p){
		return {path: p}
	},
	gender: function(p){
		return {path: p}
	},
	cc: function(r, t, g) {
		var a = {}
		if (r) a.record = r;
		if (t) a.tts = t;
		if (g) a.gender = g;
		return a
	}
}

s.tts = {
	cc: function(mode, sox, enable, baidu, iflytek, aliyun)
	{
		var a = {}
		a.mode = mode ? 1 : 0;
		a.sox = sox || '';
		if (enable) a.enable = enable;
		if (baidu) a.baidu = baidu;
		if (iflytek) a.iflytek = iflytek;
		if (aliyun) a.aliyun = aliyun;
		return a
	},
	enable: function(baidu, iflytek, aliyun) {
		var a = []
		if (baidu) a.push('baidu');
		if (iflytek) a.push('iflytek');
		if (aliyun) a.push('aliyun');
		return a
	},
	baidu: function(mode, voice, connecttimeout, responsetimeout, cuid, keylist) {
		var a = {}
		a.mode = mode ? 1 : 0;
		a.voice = voice || '';
		a.connecttimeout = connecttimeout || 0;
		a.responsetimeout = responsetimeout || 0;
		a.cuid = cuid || '';
		a.keylist = keylist
		return a
	},
	aliyun: function(mode, voice, connecttimeout, responsetimeout, keylist) {
		var a = {}
		a.mode = mode ? 1 : 0;
		a.voice = voice || '';
		a.connecttimeout = connecttimeout || 0;
		a.responsetimeout = responsetimeout || 0;
		a.keylist = keylist
		return a
	},	
	iflytek: function(mode, voice, workdir, command, keylist) {
		var a = {}
		a.mode = mode ? 1 : 0;
		a.voice = voice || '';
		a.workdir = workdir || '';
		a.command = command || '';
		a.keylist = keylist
		return a
	}
}

s.asr = {
	cc: function(mode, enable, xfyun, baidu, aliyun, iflytek, iflytek2)
	{
		var a = {}
		a.mode = mode ? 1 : 0;
		if (enable) a.enable = enable;
		if (xfyun) a.xfyun = xfyun;
		if (baidu) a.baidu = baidu;
		if (aliyun) a.aliyun = aliyun;
		if (iflytek) a.iflytek = iflytek;
		if (iflytek2) a.iflytek2 = iflytek2;		
		return a
	},
	enable: function(xfyun, baidu, aliyun, iflytek, iflytek2) {
		var a = []
		if (baidu) a.push('baidu');
		if (iflytek) a.push('iflytek');
		if (aliyun) a.push('aliyun');
		if (xfyun) a.push('xfyun');
		if (iflytek2) a.push('iflytek2');
		return a
	},
	xfyun: function(error_thresholds, mode, allowdisable, connecttimeout, responsetimeout, scene, semantic, aiui, engine, keylist) {
		var a = {}
		a.error_thresholds = error_thresholds || 0;
		a.mode = mode ? 1 : 0;		
		a.allowdisable = allowdisable ? true : false;		
		a.connecttimeout = connecttimeout || 0;
		a.responsetimeout = responsetimeout || 0;
		a.scene = scene || '';
		a.semantic = semantic ? true : false;
		a.aiui = aiui ? true : false;
		a.engine = engine || '';		
		a.keylist = keylist
		return a
	},
	baidu: function(error_thresholds, mode, allowdisable, connecttimeout, responsetimeout, cuid, keylist) {
		var a = {}
		a.error_thresholds = error_thresholds || 0;
		a.mode = mode ? 1 : 0;		
		a.allowdisable = allowdisable ? true : false;		
		a.connecttimeout = connecttimeout || 0;
		a.responsetimeout = responsetimeout || 0;
		a.cuid = cuid || '';
		a.keylist = keylist
		return a
	},
	aliyun: function(error_thresholds, mode, allowdisable, connecttimeout, responsetimeout, keylist) {
		var a = {}
		a.error_thresholds = error_thresholds || 0;
		a.mode = mode ? 1 : 0;		
		a.allowdisable = allowdisable ? true : false;		
		a.connecttimeout = connecttimeout || 0;
		a.responsetimeout = responsetimeout || 0;
		a.keylist = keylist
		return a
	},
	iflytek: function(mode, workdir, responsetimeout, command, keylist) {
		var a = {}
		a.mode = mode ? 1 : 0;
		a.responsetimeout = responsetimeout || 0;
		a.workdir = workdir || '';
		a.command = command || '';
		a.keylist = keylist
		return a
	},
	iflytek2: function(mode, workdir, responsetimeout, command, keylist) {
		var a = {}
		a.mode = mode ? 1 : 0;
		a.responsetimeout = responsetimeout || 0;
		a.workdir = workdir || '';
		a.command = command || '';
		a.keylist = keylist
		return a
	}
}

// ui render
$('#asrMain').click(function(){
	var chk = $('#asrMain').prop('checked')
	if (chk) {
		$('div.asr-div').show()
	}
	else {
		$('div.asr-div').hide()
	}
});
$('#ttsMain').click(function(){
	var chk = $('#ttsMain').prop('checked')
	if (chk) {
		$('div.tts-div').show()
	}
	else {
		$('div.tts-div').hide()
	}
});
$('#systemMain').click(function(){
	var chk = $('#systemMain').prop('checked')
	if (chk) {
		$('div.system-div').show()
	}
	else {
		$('div.system-div').hide()
	}
});

$('#asr-xfyun').click(function(){
	var chk = $('#asr-xfyun').prop('checked')
	if (chk) {
		$('div.asr-xfyun-div').show()
	}
	else {
		$('div.asr-xfyun-div').hide()
	}
});
$('#asr-baidu').click(function(){
	var chk = $('#asr-baidu').prop('checked')
	if (chk) {
		$('div.asr-baidu-div').show()
	}
	else {
		$('div.asr-baidu-div').hide()
	}
});
$('#asr-aliyun').click(function(){
	var chk = $('#asr-aliyun').prop('checked')
	if (chk) {
		$('div.asr-aliyun-div').show()
	}
	else {
		$('div.asr-aliyun-div').hide()
	}
});
$('#asr-iflytek').click(function(){
	var chk = $('#asr-iflytek').prop('checked')
	if (chk) {
		$('div.asr-iflytek-div').show()
	}
	else {
		$('div.asr-iflytek-div').hide()
	}
});
$('#asr-iflytek2').click(function(){
	var chk = $('#asr-iflytek2').prop('checked')
	if (chk) {
		$('div.asr-iflytek2-div').show()
	}
	else {
		$('div.asr-iflytek2-div').hide()
	}
});

$('div.asr-xfyun-div').delegate('a.v-keylist-rowadd','click',function(){
	var html = template('keylist-row1', {});
	$('#asr-xfyun-keylist').append(html)
	$('#asr-xfyun-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})
$('div.asr-xfyun-div').delegate('a.v-rowdel','click',function(){
	var parent = $(this).parent().parent();
	$(parent).remove()
	$('#asr-xfyun-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})

$('div.asr-baidu-div').delegate('a.v-keylist-rowadd','click',function(){
	var html = template('keylist-row1', {});
	$('#asr-baidu-keylist').append(html)
	$('#asr-baidu-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})
$('div.asr-baidu-div').delegate('a.v-rowdel','click',function(){
	var parent = $(this).parent().parent();
	$(parent).remove()
	$('#asr-baidu-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})

$('div.asr-aliyun-div').delegate('a.v-keylist-rowadd','click',function(){
	var html = template('keylist-row1', {});
	$('#asr-aliyun-keylist').append(html)
	$('#asr-aliyun-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})
$('div.asr-aliyun-div').delegate('a.v-rowdel','click',function(){
	var parent = $(this).parent().parent();
	$(parent).remove()
	$('#asr-aliyun-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})

$('div.asr-iflytek-div').delegate('a.v-keylist2-rowadd','click',function(){
	var html = template('keylist-row2', {});
	$('#asr-iflytek-keylist').append(html)
	$('#asr-iflytek-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})
$('div.asr-iflytek-div').delegate('a.v-rowdel','click',function(){
	var parent = $(this).parent().parent();
	$(parent).remove()
	$('#asr-iflytek-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})

$('div.asr-iflytek2-div').delegate('a.v-keylist2-rowadd','click',function(){
	var html = template('keylist-row2', {});
	$('#asr-iflytek2-keylist').append(html)
	$('#asr-iflytek2-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})
$('div.asr-iflytek2-div').delegate('a.v-rowdel','click',function(){
	var parent = $(this).parent().parent();
	$(parent).remove()
	$('#asr-iflytek2-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})

$('#system-record').click(function(){
	var chk = $('#system-record').prop('checked')
	if (chk) {
		$('div.system-record-div').show()
	}
	else {
		$('div.system-record-div').hide()
	}
});
$('#system-tts').click(function(){
	var chk = $('#system-tts').prop('checked')
	if (chk) {
		$('div.system-tts-div').show()
	}
	else {
		$('div.system-tts-div').hide()
	}
});
$('#system-gender').click(function(){
	var chk = $('#system-gender').prop('checked')
	if (chk) {
		$('div.system-gender-div').show()
	}
	else {
		$('div.system-gender-div').hide()
	}
});

$('div.tts-iflytek-div').delegate('a.v-keylist2-rowadd','click',function(){
	var html = template('keylist-row2', {});
	$('#tts-iflytek-keylist').append(html)
	$('#tts-iflytek-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})
$('div.tts-iflytek-div').delegate('a.v-rowdel','click',function(){
	var parent = $(this).parent().parent();
	$(parent).remove()
	$('#tts-iflytek-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})

$('div.tts-aliyun-div').delegate('a.v-keylist-rowadd','click',function(){
	var html = template('keylist-row1', {});
	$('#tts-aliyun-keylist').append(html)
	$('#tts-aliyun-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})
$('div.tts-aliyun-div').delegate('a.v-rowdel','click',function(){
	var parent = $(this).parent().parent();
	$(parent).remove()
	$('#tts-aliyun-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})
$('div.tts-baidu-div').delegate('a.v-keylist-rowadd','click',function(){
	var html = template('keylist-row1', {});
	$('#tts-baidu-keylist').append(html)
	$('#tts-baidu-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})
$('div.tts-baidu-div').delegate('a.v-rowdel','click',function(){
	var parent = $(this).parent().parent();
	$(parent).remove()
	$('#tts-baidu-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})

function render() {
	var chk = $('#asrMain').prop('checked')
	if (chk) {
		$('div.asr-div').show()
	}
	else {
		$('div.asr-div').hide()
	}

	chk = $('#ttsMain').prop('checked')
	if (chk) {
		$('div.tts-div').show()
	}
	else {
		$('div.tts-div').hide()
	}

	chk = $('#systemMain').prop('checked')
	if (chk) {
		$('div.system-div').show()
	}
	else {
		$('div.system-div').hide()
	}
}

render();

});