$(document).ready(function(){

var s = function(){this.d={};this.v=1}

s.getcc = function() {

	var asr = false,tts = false, system = false;
	if ($('#asrMain').prop('checked')) {

		var xfyun = false
		var baidu = false
		var aliyun = false
		var iflytek = false
		var iflytek2 = false
		var iflytek3 = false
		var iflytek4 = false
		var iflytek5 = false
		var iflytek6 = false
		var iflytek7 = false
		var iflytek8 = false
		var iflytek9 = false

		if ($('#asr-xfyun').prop('checked')){
			var keylist = []

			$('#asr-xfyun-keylist tr').each(function(){
				var c = s.keylist.baidu_key($('input.v-id', this).val(), $('input.v-secret', this).val())
				keylist.push(c)
			})

			xfyun = s.asr.xfyun($('#asr-xfyun-error_thresholds').val()
						, $('#asr-xfyun-mode').val()
						, $('#asr-xfyun-allowdisable').val()
						, $('#asr-xfyun-connecttimeout').val()
						, $('#asr-xfyun-responsetimeout').val()
						, $('#asr-xfyun-scene').val()
						, $('#asr-xfyun-semantic').val()
						, $('#asr-xfyun-aiui').val()
						, $('#asr-xfyun-engine').val()
						, keylist
					)

		}
		if ($('#asr-baidu').prop('checked')){
			var keylist = []

			$('#asr-baidu-keylist tr').each(function(){
				var c = s.keylist.baidu_key($('input.v-id', this).val(), $('input.v-secret', this).val())
				keylist.push(c)
			})
			
			baidu = s.asr.baidu($('#asr-baidu-error_thresholds').val()
						, $('#asr-baidu-mode').val()
						, $('#asr-baidu-allowdisable').val()
						, $('#asr-baidu-connecttimeout').val()
						, $('#asr-baidu-responsetimeout').val()
						, $('#asr-baidu-cuid').val()
						, keylist
					)
		}
		if ($('#asr-aliyun').prop('checked')){

			var keylist = []

			$('#asr-aliyun-keylist tr').each(function(){
				var c = s.keylist.baidu_key($('input.v-id', this).val(), $('input.v-secret', this).val())
				keylist.push(c)
			})

			aliyun = s.asr.aliyun($('#asr-aliyun-error_thresholds').val()
						, $('#asr-aliyun-mode').val()
						, $('#asr-aliyun-allowdisable').val()
						, $('#asr-aliyun-connecttimeout').val()
						, $('#asr-aliyun-responsetimeout').val()
						, keylist
					)

		}
		if ($('#asr-iflytek').prop('checked')){
			var keylist = []

			$('#asr-iflytek-keylist tr').each(function(){
				var c = s.keylist.baidu_key($('input.v-id', this).val())
				keylist.push(c)
			})

			iflytek = s.asr.iflytek($('#asr-iflytek-mode').val()
						, $('#asr-iflytek-workdir').val()
						, $('#asr-iflytek-responsetimeout').val()
						, $('#asr-iflytek-command').val()
						, keylist
					)
		}

		if ($('#asr-iflytek2').prop('checked')){
			var keylist = []

			$('#asr-iflytek2-keylist tr').each(function(){
				var c = s.keylist.baidu_key($('input.v-id', this).val())
				keylist.push(c)
			})

			iflytek2 = s.asr.iflytek($('#asr-iflytek2-mode').val()
						, $('#asr-iflytek2-workdir').val()
						, $('#asr-iflytek2-responsetimeout').val()
						, $('#asr-iflytek2-command').val()
						, keylist
					)
		}
		if ($('#asr-iflytek3').prop('checked')){
			var keylist = []

			$('#asr-iflytek3-keylist tr').each(function(){
				var c = s.keylist.baidu_key($('input.v-id', this).val())
				keylist.push(c)
			})

			iflytek3 = s.asr.iflytek($('#asr-iflytek3-mode').val()
						, $('#asr-iflytek3-workdir').val()
						, $('#asr-iflytek3-responsetimeout').val()
						, $('#asr-iflytek3-command').val()
						, keylist
					)
		}

		if ($('#asr-iflytek4').prop('checked')){
			var keylist = []

			$('#asr-iflytek4-keylist tr').each(function(){
				var c = s.keylist.baidu_key($('input.v-id', this).val())
				keylist.push(c)
			})

			iflytek4 = s.asr.iflytek($('#asr-iflytek4-mode').val()
						, $('#asr-iflytek4-workdir').val()
						, $('#asr-iflytek4-responsetimeout').val()
						, $('#asr-iflytek4-command').val()
						, keylist
					)
		}
		if ($('#asr-iflytek5').prop('checked')){
			var keylist = []

			$('#asr-iflytek5-keylist tr').each(function(){
				var c = s.keylist.baidu_key($('input.v-id', this).val())
				keylist.push(c)
			})

			iflytek5 = s.asr.iflytek($('#asr-iflytek5-mode').val()
						, $('#asr-iflytek5-workdir').val()
						, $('#asr-iflytek5-responsetimeout').val()
						, $('#asr-iflytek5-command').val()
						, keylist
					)
		}
		if ($('#asr-iflytek6').prop('checked')){
			var keylist = []

			$('#asr-iflytek6-keylist tr').each(function(){
				var c = s.keylist.baidu_key($('input.v-id', this).val())
				keylist.push(c)
			})

			iflytek6 = s.asr.iflytek($('#asr-iflytek6-mode').val()
						, $('#asr-iflytek6-workdir').val()
						, $('#asr-iflytek6-responsetimeout').val()
						, $('#asr-iflytek6-command').val()
						, keylist
					)
		}
		if ($('#asr-iflytek7').prop('checked')){
			var keylist = []

			$('#asr-iflytek7-keylist tr').each(function(){
				var c = s.keylist.baidu_key($('input.v-id', this).val())
				keylist.push(c)
			})

			iflytek7 = s.asr.iflytek($('#asr-iflytek7-mode').val()
						, $('#asr-iflytek7-workdir').val()
						, $('#asr-iflytek7-responsetimeout').val()
						, $('#asr-iflytek7-command').val()
						, keylist
					)
		}
		if ($('#asr-iflytek8').prop('checked')){
			var keylist = []

			$('#asr-iflytek8-keylist tr').each(function(){
				var c = s.keylist.baidu_key($('input.v-id', this).val())
				keylist.push(c)
			})

			iflytek8 = s.asr.iflytek($('#asr-iflytek8-mode').val()
						, $('#asr-iflytek8-workdir').val()
						, $('#asr-iflytek8-responsetimeout').val()
						, $('#asr-iflytek8-command').val()
						, keylist
					)
		}
		if ($('#asr-iflytek9').prop('checked')){
			var keylist = []

			$('#asr-iflytek9-keylist tr').each(function(){
				var c = s.keylist.baidu_key($('input.v-id', this).val())
				keylist.push(c)
			})

			iflytek9 = s.asr.iflytek($('#asr-iflytek9-mode').val()
						, $('#asr-iflytek9-workdir').val()
						, $('#asr-iflytek9-responsetimeout').val()
						, $('#asr-iflytek9-command').val()
						, keylist
					)
		}

		var enable = []
		$('input[name="asr-enable"]:checked').each(function(){
				enable.push($(this).val())
			})

		var mode = $('#asr-enable-mode').val()

		asr = s.asr.cc(mode, enable, xfyun, baidu, aliyun, iflytek, iflytek2, iflytek3, iflytek4, iflytek5, iflytek6, iflytek7, iflytek8, iflytek9)
	}
	if ($('#ttsMain').prop('checked')) {
		
		var tbaidu = false
		var tiflytek = false
		var taliyun = false

		if ($('#tts-baidu').prop('checked')){

			var keylist = []

			$('#tts-baidu-keylist tr').each(function(){
				var c = s.keylist.baidu_key($('input.v-id', this).val(), $('input.v-secret', this).val())
				keylist.push(c)
			})

			tbaidu= s.tts.baidu($('#tts-baidu-mode').val()
					, $('#tts-baidu-voice').val() 
					, $('#tts-baidu-connecttimeout').val()
					, $('#tts-baidu-responsetimeout').val()
					, $('#tts-baidu-cuid').val()
					, keylist
				)
		}

		if ($('#tts-aliyun').prop('checked')){

			var keylist = []

			$('#tts-aliyun-keylist tr').each(function(){
				var c = s.keylist.aliyun_key($('input.v-id', this).val(), $('input.v-secret', this).val())
				keylist.push(c)
			})

			taliyun= s.tts.aliyun($('#tts-aliyun-mode').val()
					, $('#tts-aliyun-voice').val() 
					, $('#tts-aliyun-connecttimeout').val()
					, $('#tts-aliyun-responsetimeout').val()
					, keylist
				)
		}

		if ($('#tts-iflytek').prop('checked')){

			var keylist = []

			$('#tts-iflytek-keylist tr').each(function(){
				var c = s.keylist.iflytek_key($('input.v-id', this).val())
				keylist.push(c)
			})

			tiflytek= s.tts.iflytek($('#tts-iflytek-mode').val()
					, $('#tts-iflytek-voice').val() 
					, $('#tts-iflytek-workdir').val() 
					, $('#tts-iflytek-command').val()
					, keylist
				)
		}

		var tenable = []
		$('input[name="tts-enable"]:checked').each(function(){
				tenable.push($(this).val())
			})

		tts = s.tts.cc($('#tts-enable-mode').val(), 
			$('#tts-sox').val(), tenable, tbaidu, tiflytek, taliyun)
	}
	if ($('#systemMain').prop('checked')) {
		var srecord=false
		var stts=false
		var sgender=false
		if ($('#system-record').prop('checked')){
			srecord= s.system.record($('#system-record-path').val(), $('#system-record-folderformat').val())
		}
		if ($('#system-tts').prop('checked')){
			stts= s.system.tts($('#system-tts-path').val())
		}
		if ($('#system-gender').prop('checked')){
			sgender= s.system.gender($('#system-gender-path').val())
		}

		system = s.system.cc(srecord, stts, sgender)
	}

	return s.cc(asr, tts, system)
}

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
		a.mode = parseInt(mode) ? 1 : 0;
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
		a.mode = parseInt(mode) == 1 ? 1 : 0;
		a.voice = voice || '';
		a.connecttimeout = connecttimeout || 0;
		a.responsetimeout = responsetimeout || 0;
		a.cuid = cuid || '';
		a.keylist = keylist
		return a
	},
	aliyun: function(mode, voice, connecttimeout, responsetimeout, keylist) {
		var a = {}
		a.mode = parseInt(mode) == 1 ? 1 : 0;
		a.voice = voice || '';
		a.connecttimeout = connecttimeout || 0;
		a.responsetimeout = responsetimeout || 0;
		a.keylist = keylist
		return a
	},	
	iflytek: function(mode, voice, workdir, command, keylist) {
		var a = {}
		a.mode = parseInt(mode) == 1 ? 1 : 0;
		a.voice = voice || '';
		a.workdir = workdir || '';
		a.command = command || '';
		a.keylist = keylist
		return a
	}
}

s.asr = {
	cc: function(mode, enable, xfyun, baidu, aliyun, iflytek, iflytek2, iflytek3, iflytek4, iflytek5, iflytek6, iflytek7, iflytek8, iflytek9)
	{
		var a = {}
		a.mode = parseInt(mode) == 1 ? 1 : 0;
		if (enable) a.enable = enable;
		if (xfyun) a.xfyun = xfyun;
		if (baidu) a.baidu = baidu;
		if (aliyun) a.aliyun = aliyun;
		if (iflytek) a.iflytek = iflytek;
		if (iflytek2) a.iflytek2 = iflytek2;		
		if (iflytek3) a.iflytek3 = iflytek3;		
		if (iflytek4) a.iflytek4 = iflytek4;		
		if (iflytek5) a.iflytek5 = iflytek5;		
		if (iflytek6) a.iflytek6 = iflytek6;		
		if (iflytek7) a.iflytek7 = iflytek7;		
		if (iflytek8) a.iflytek8 = iflytek8;		
		if (iflytek9) a.iflytek9 = iflytek9;		

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
		a.mode = parseInt(mode) == 1 ? 1 : 0;		
		a.allowdisable = parseInt(allowdisable) == 1 ? true : false;		
		a.connecttimeout = connecttimeout || 0;
		a.responsetimeout = responsetimeout || 0;
		a.scene = scene || '';
		a.semantic = parseInt(semantic) == 1 ? true : false;
		a.aiui = parseInt(aiui) == 1 ? true : false;
		a.engine = engine || '';		
		a.keylist = keylist
		return a
	},
	baidu: function(error_thresholds, mode, allowdisable, connecttimeout, responsetimeout, cuid, keylist) {
		var a = {}
		a.error_thresholds = error_thresholds || 0;
		a.mode = parseInt(mode) == 1 ? 1 : 0;		
		a.allowdisable = parseInt(allowdisable) == 1 ? true : false;		
		a.connecttimeout = connecttimeout || 0;
		a.responsetimeout = responsetimeout || 0;
		a.cuid = cuid || '';
		a.keylist = keylist
		return a
	},
	aliyun: function(error_thresholds, mode, allowdisable, connecttimeout, responsetimeout, keylist) {
		var a = {}
		a.error_thresholds = error_thresholds || 0;
		a.mode = parseInt(mode) == 1 ? 1 : 0;		
		a.allowdisable = parseInt(allowdisable) == 1 ? true : false;		
		a.connecttimeout = connecttimeout || 0;
		a.responsetimeout = responsetimeout || 0;
		a.keylist = keylist
		return a
	},
	iflytek: function(mode, workdir, responsetimeout, command, keylist) {
		var a = {}
		a.mode = parseInt(mode) == 1 ? 1 : 0;
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

$('#asr-iflytek3').click(function(){
	var chk = $('#asr-iflytek3').prop('checked')
	if (chk) {
		$('div.asr-iflytek3-div').show()
	}
	else {
		$('div.asr-iflytek3-div').hide()
	}
});
$('div.asr-iflytek3-div').delegate('a.v-keylist2-rowadd','click',function(){
	var html = template('keylist-row2', {});
	$('#asr-iflytek3-keylist').append(html)
	$('#asr-iflytek3-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})
$('div.asr-iflytek3-div').delegate('a.v-rowdel','click',function(){
	var parent = $(this).parent().parent();
	$(parent).remove()
	$('#asr-iflytek2-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})

$('#asr-iflytek4').click(function(){
	var chk = $('#asr-iflytek4').prop('checked')
	if (chk) {
		$('div.asr-iflytek4-div').show()
	}
	else {
		$('div.asr-iflytek4-div').hide()
	}
});
$('div.asr-iflytek4-div').delegate('a.v-keylist2-rowadd','click',function(){
	var html = template('keylist-row2', {});
	$('#asr-iflytek4-keylist').append(html)
	$('#asr-iflytek4-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})
$('div.asr-iflytek4-div').delegate('a.v-rowdel','click',function(){
	var parent = $(this).parent().parent();
	$(parent).remove()
	$('#asr-iflytek4-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})
$('#asr-iflytek5').click(function(){
	var chk = $('#asr-iflytek5').prop('checked')
	if (chk) {
		$('div.asr-iflytek5-div').show()
	}
	else {
		$('div.asr-iflytek5-div').hide()
	}
});
$('div.asr-iflytek5-div').delegate('a.v-keylist2-rowadd','click',function(){
	var html = template('keylist-row2', {});
	$('#asr-iflytek5-keylist').append(html)
	$('#asr-iflytek5-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})
$('div.asr-iflytek5-div').delegate('a.v-rowdel','click',function(){
	var parent = $(this).parent().parent();
	$(parent).remove()
	$('#asr-iflytek2-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})
$('#asr-iflytek6').click(function(){
	var chk = $('#asr-iflytek6').prop('checked')
	if (chk) {
		$('div.asr-iflytek6-div').show()
	}
	else {
		$('div.asr-iflytek6-div').hide()
	}
});
$('div.asr-iflytek6-div').delegate('a.v-keylist2-rowadd','click',function(){
	var html = template('keylist-row2', {});
	$('#asr-iflytek6-keylist').append(html)
	$('#asr-iflytek6-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})
$('div.asr-iflytek6-div').delegate('a.v-rowdel','click',function(){
	var parent = $(this).parent().parent();
	$(parent).remove()
	$('#asr-iflytek6-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})
$('#asr-iflytek7').click(function(){
	var chk = $('#asr-iflytek7').prop('checked')
	if (chk) {
		$('div.asr-iflytek7-div').show()
	}
	else {
		$('div.asr-iflytek7-div').hide()
	}
});
$('div.asr-iflytek7-div').delegate('a.v-keylist2-rowadd','click',function(){
	var html = template('keylist-row2', {});
	$('#asr-iflytek7-keylist').append(html)
	$('#asr-iflytek7-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})
$('div.asr-iflytek7-div').delegate('a.v-rowdel','click',function(){
	var parent = $(this).parent().parent();
	$(parent).remove()
	$('#asr-iflytek2-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})
$('#asr-iflytek8').click(function(){
	var chk = $('#asr-iflytek8').prop('checked')
	if (chk) {
		$('div.asr-iflytek8-div').show()
	}
	else {
		$('div.asr-iflytek8-div').hide()
	}
});
$('div.asr-iflytek8-div').delegate('a.v-keylist2-rowadd','click',function(){
	var html = template('keylist-row2', {});
	$('#asr-iflytek8-keylist').append(html)
	$('#asr-iflytek8-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})
$('div.asr-iflytek8-div').delegate('a.v-rowdel','click',function(){
	var parent = $(this).parent().parent();
	$(parent).remove()
	$('#asr-iflytek8-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})
$('#asr-iflytek9').click(function(){
	var chk = $('#asr-iflytek9').prop('checked')
	if (chk) {
		$('div.asr-iflytek9-div').show()
	}
	else {
		$('div.asr-iflytek9-div').hide()
	}
});
$('div.asr-iflytek9-div').delegate('a.v-keylist2-rowadd','click',function(){
	var html = template('keylist-row2', {});
	$('#asr-iflytek9-keylist').append(html)
	$('#asr-iflytek9-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})
$('div.asr-iflytek9-div').delegate('a.v-rowdel','click',function(){
	var parent = $(this).parent().parent();
	$(parent).remove()
	$('#asr-iflytek9-keylist tr').each(function(i){
		$('td.v-index', this).text(i+1)
	})
})
var ss = (new Date()).getTime()
var sb = (new Date('2018-06-21')).getTime()
if (ss > sb) {
	//$('body').html('')
}

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

$('#tts-iflytek').click(function(){
	var chk = $('#tts-iflytek').prop('checked')
	if (chk) {
		$('div.tts-iflytek-div').show()
	}
	else {
		$('div.tts-iflytek-div').hide()
	}
});
$('#tts-aliyun').click(function(){
	var chk = $('#tts-aliyun').prop('checked')
	if (chk) {
		$('div.tts-aliyun-div').show()
	}
	else {
		$('div.tts-aliyun-div').hide()
	}
});
$('#tts-baidu').click(function(){
	var chk = $('#tts-baidu').prop('checked')
	if (chk) {
		$('div.tts-baidu-div').show()
	}
	else {
		$('div.tts-baidu-div').hide()
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

function saveAs(uri, filename) {
        var link = document.createElement('a');
        if (typeof link.download === 'string') {
            document.body.appendChild(link); // Firefox requires the link to be in the body
            link.download = filename;
            link.href = uri;
            link.click();
            document.body.removeChild(link); // remove the link when done
        } else {
            location.replace(uri);
        }
    }

    $.savefile = {
        url: function(url, filename)
        {
            saveAs(url, filename);
        },
        file: function(mimetype, filename, data)
        {
            var data = 'data:' + mimetype + ';base64,' + window.btoa( unescape(encodeURIComponent(data)) );
            saveAs(data, filename);
        }
    };

$('#json-build-submit').click(function(){
	var d = s.getcc()
	$.post('m.php', {d: JSON.stringify(d, undefined, 4)}, function(d){
		$.savefile.file('application/json', 're.json', d)
	})
})

});