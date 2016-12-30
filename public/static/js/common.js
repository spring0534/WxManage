//去掉左边空格
function LTrim(str) { 
	var i; 
	for(i=0;i<str.length;i++){ if(str.charAt(i)!=" "&&str.charAt(i)!=" ")break; } 
	str=str.substring(i,str.length); 
	return str; 
} 
//去掉右边空格
function RTrim(str) { 
	var i; 
	for(i=str.length-1;i>=0;i--) { if(str.charAt(i)!=" "&&str.charAt(i)!=" ")break; } 
	str=str.substring(0,i+1); 
	return str; 
} 
function Trim(str) { 
	return LTrim(RTrim(str)); 
} 
function redirect(url) {
	location.href = url;
}
function confirmurl(url,message) {
	if(confirm(message)) redirect(url);
}
function openwinx(url,name,w,h) {
	if(!w) w=screen.width-4;
	if(!h) h=screen.height-95;
	url = url+'&pc_hash='+pc_hash;
    window.open(url,name,"top=100,left=400,width=" + w + ",height=" + h + ",toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=no");
}

//分组显示
function liston(who,td,div,oncss,outcss){
	var index=$(who).index();
	$(td).children().each(function(){
		if($(this).index()==index){
			$(this).attr("class",oncss);
			$(div).children().eq($(this).index()).show();
			}else{
			if($(this).attr("class")!="more"){
			$(this).attr("class",outcss);
			}
			$(div).children().eq($(this).index()).hide();
			}
	});
	
}
//图片缩略图效果
function DrawImage(ImgD,FitWidth,FitHeight,iscenter){
    var image=new Image();
	image.src=ImgD.src;
	if(image.width>0 && image.height>0){
		if(image.width/image.height>= FitWidth/FitHeight){
			if(image.width>FitWidth){
				ImgD.width=FitWidth;
				ImgD.height=(image.height*FitWidth)/image.width;
			}else{
				ImgD.width=image.width; 
				ImgD.height=image.height;
			}
		}else{
			if(image.height>FitHeight){
				ImgD.height=FitHeight;
				ImgD.width=(image.width*FitHeight)/image.height;
			}else{
				ImgD.width=image.width; 
				ImgD.height=image.height;
			} 
		}
	}
	if(iscenter==true){
	//图片高小于设定的边框高时，让图片上下居中 padding 为内边距
	if(ImgD.height < FitHeight ){
		var paddH = parseInt((FitHeight - ImgD.height)/2);
		ImgD.style.paddingTop    = paddH+"px";
		ImgD.style.paddingBottom = paddH+"px";
	}
	//图片宽小于设定的边框宽时，让图片左右居中 padding 为内边距
	if(ImgD.width < FitWidth ){
		var paddW = parseInt((FitWidth - ImgD.width)/2);
		ImgD.style.paddingRight = paddW+"px";
		ImgD.style.paddingLeft  = paddW+"px";
	}
	}
	
 }


//通过传递ids勾选多选框样式  ids形式  1,2,5,8 ,inputname 多选框name名称 
function init_selectpos(ids,inputname){
	$("input[name='"+inputname+"[]']").each(function(index, element) {
        var mpos=ids.indexOf($(this).val());
		if(mpos!=-1){
			$(this).attr('checked',true);	
		}
    });
}

/**
 * 全选checkbox,注意：标识checkbox id固定为为check_box
 * @param string name 列表check名称,如 uid[]
 */
function selectall(name) {
		$("input[name='"+name+"']").each(function() {
			this.checked=!this.checked;
		});
	
} 
/*限制输入长度提示
txttag 文本框 
tiptag 提示数
$(".alertmsg ul.c textarea").blur(function(){
	stoplen($(this),'.alertmsg ul.txt span',100);
	});
*/
function stoplen(txttag,tiptag,maxlen){
	var nowlen=0;
	var str=txttag.val();
	nowlen=str.length;
	if(nowlen>maxlen){
		txttag.html(str.substring(0,maxlen));
	}
	$(tiptag).html(maxlen-nowlen);
}

//art.dialog工厂
function artdialog_factory(title,id,callback_fun,zindex){
var art_obj;
// Wind.use("artDialog","iframeTools",function(){});
  art_obj=top.art.dialog(	{title:title,id:id,lock:true,padding: 0,zIndex:zindex,opacity: 0.27,content:'<div style="padding:30px"><img src="static/images/loader-dark.gif" style="border:0px;width:35px;height:35px;vertical-align: middle;"> 正在加载中,请稍后...</div>',
					button:[
						{
						name: '确定',
						focus:true,
						callback: function () {
							if(typeof(callback_fun)== "function"){
								var resfun=callback_fun;
								resfun();
							}
						}
						}
					]});
return art_obj;
}



//弹出对话框
function omnipotent(id, linkurl, title, close_type, w, h) {
    if (!w) w = 700;
    if (!h) h = 500;
    Wind.use("artDialog","iframeTools",function(){
        art.dialog.open(linkurl, {
        id: id,
        title: title,
        width: w,
        height: h,
        lock: true,
        fixed: true,
        background:"#CCCCCC",
        opacity:0,
        button: [{
            name: '确定',
            callback: function () {
                if (close_type == 1) {
                    return true;
                } else {
                    var d = this.iframe.contentWindow;
                    var form = d.document.getElementById('dosubmit');
                    form.click();
                }
                return false;
           },
           focus: true
        }]
    });
    });
    
}

/**
swf上传完回调方法
uploadid dialog id
name dialog名称
textareaid 最后数据返回插入的容器id
funcName 回调函数
args 参数

**/
function flashupload(uploadid, name, textareaid, funcName, args) {
    var args = args ? '&args=' + args : '';
	Wind.use("artDialog", "iframeTools", function () {
		art.dialog.open(WEBURL + '/attachments/swfupload?' + args, {
			title: name,
			id: uploadid,
			width: '650px',
			height: '420px',
			lock: true,
			fixed: true,
		   /* background: "#CCCCCC",*/
			opacity: 0,
			ok: function () {
			
				if (funcName) {
					funcName.apply(this, [this, textareaid]);
				} else {
					submit_ckeditor(this, textareaid);
				}
			},
			cancel: true
		});
	});
    
}

//多图上传，SWF回调函数
function change_images(uploadid, returnid) {
    var d = uploadid.iframe.contentWindow;
    var in_content = d.$("#att-status").html().substring(1);
    var in_filename = d.$("#att-name").html().substring(1);
    var str = $('#' + returnid).html();
    var contents = in_content.split('|');
    var filenames = in_filename.split('|');
    $('#' + returnid + '_tips').css('display', 'none');
    if (contents == '') return true;
    $.each(contents, function(i, n) {
        var ids = parseInt(Math.random() * 10000 + 10 * i);
        var filename = filenames[i].substr(0, filenames[i].indexOf('.'));
        str += "<li id='image" + ids + "'><i class='glyph-icon icon-resize-vertical piclist_move'></i><input type='text' name='uploadImages[" + returnid + "][" + ids + "][url]' value='" + n + "' style='width:310px;' ondblclick='image_priview(this.value);' class='input'><input type='text' name='uploadImages[" + returnid + "][" + ids + "][alt]' value='" + filename + "' style='width:160px;' class='input' onfocus=\"if(this.value == this.defaultValue) this.value = ''\" onblur=\"if(this.value.replace(' ','') == '') this.value = this.defaultValue;\"><a href=\"javascript:;\" class=\" medium radius-all-2  btn popover-button-default\"  data-trigger=\"hover\" data-placement=\"right\" data-original-title='" + filename + "' data-content=\"<img width='250px' src='" + n + "' >\"> <span class=\"button-content text-center float-none font-size-11 text-transform-upr\">预览</span></a> <a href=\"javascript:remove_div('image" + ids + "')\">移除</a> </li>";
    });

    $('#' + returnid).html(str);
	 $(".popover-button-default").popover({
			container: "body",
			html: !0,
			animation: !0
		}).click(function(a) {
			a.preventDefault()
		});
	
}

//编辑器ue附件上传
function ueAttachment(uploadid, returnid){
    var d = uploadid.iframe.contentWindow;
    var in_content = d.$("#att-status").html().substring(1);
    if (in_content == ''){
        return false;
    }
    in_content = in_content.split("|");
    var i;
    var in_filename = d.$("#att-name").html().substring(1);
    var filenames = in_filename.split('|');

    eval("var ue = editor"+ returnid);
    
    for(i=0; i<in_content.length; i++){
        ue.execCommand('inserthtml', '<a href="'+in_content[i]+'" target="_blank">附件：'+filenames[i]+'</a>');
    }
    
}

//多文件上传回调
function change_multifile(uploadid, returnid) {
    var d = uploadid.iframe.contentWindow;
    var in_content = d.$("#att-status").html().substring(1);
    var in_filename = d.$("#att-name").html().substring(1);
    var str = '';
    var contents = in_content.split('|');
    var filenames = in_filename.split('|');
    $('#' + returnid + '_tips').css('display', 'none');
    if (contents == '') return true;
    $.each(contents, function(i, n) {
        var ids = parseInt(Math.random() * 10000 + 10 * i);
        var filename = filenames[i].substr(0, filenames[i].indexOf('.'));
        str += "<li id='multifile" + ids + "'><input type='text' name='" + returnid + "_fileurl[]' value='" + n + "' style='width:310px;' class='input'> <input type='text' name='" + returnid + "_filename[]' value='" + filename + "' style='width:160px;' class='input' onfocus=\"if(this.value == this.defaultValue) this.value = ''\" onblur=\"if(this.value.replace(' ','') == '') this.value = this.defaultValue;\"> <a href=\"javascript:remove_div('multifile" + ids + "')\">移除</a> </li>";
    });
    $('#' + returnid).append(str);
}

//缩图上传回调
function thumb_images(uploadid, returnid) {
    //取得iframe对象
    var d = uploadid.iframe.contentWindow;
    //取得选择的图片
    var in_content = d.$("#att-status").html().substring(1);
    if (in_content == '') return false;
    /*if (!IsImg(in_content)) {
        alert('选择的类型必须为图片类型！');
        return false;
    }
*/
    if ($('#' + returnid + '_preview').attr('src')) {
        $('#' + returnid + '_preview').attr('src', in_content);
    }
    $('#' + returnid).val(in_content);
}
//音乐上传回调
function thumb_music(uploadid, returnid) {
    //取得iframe对象
    var d = uploadid.iframe.contentWindow;
    //取得选择的图片
    var in_content = d.$("#att-status").html().substring(1);
    if (in_content == '') return false;
    if ($('#' + returnid + '_preview').attr('data-path')) {
        $('#' + returnid + '_preview').attr('data-path', in_content);
    }
    $('#' + returnid).val(in_content);
}
//提示框 alert
function alert(content,icon){
    if(content == ''){
        return;
    }
    icon = icon|| "warning";
    Wind.use("artDialog",function(){
        top.art.dialog({
            id:icon,
            icon: icon,
            fixed: true,
            lock: true,
           /* background:"#CCCCCC",*/
            opacity:0.27,
            content: content,
            cancelVal: '确定',
            cancel: true
        });
    });
}

//图片使用dialog查看
//图片使用dialog查看
function image_priview(img) {
    if(img == ''){
        return;
    }
    /*if (!IsImg(img)) {
        isalert('选择的类型必须为图片类型！');
        return false;
    }*/
    Wind.use("artDialog",function(){
        var dialog_pr=top.art.dialog({
            title: '图片查看',
			//title:false,
            /*fixed: true,*/
			padding:0,
			top: '50%',
            id:"image_priview",
            lock: true,
           /* background:"#CCCCCC",*/
            opacity:0.3,
			content: '<div style="padding:30px"><img src="/static/images/loader-dark.gif" style="border:0px;width:35px;height:35px;vertical-align: middle;"> 加载中...</div>'
        });
		var image=new Image();
		image.src=img;
		image.onload = function(){
		var h=$(top.window).height()-150,  
		    w=$(top.window).width()-80,
			iw='width='+image.width,
			ih='height='+image.height; 
			if(image.width>w && image.height<h){
				 iw='width='+w,
				 ih=''; 
			}else if(image.width<w && image.height>h){
				iw='',
				ih='height='+h; 
			}else if(image.width>w && image.height>h){
				
				if(image.width>image.height){
					var nh=image.height*(image.width/w);
					if(nh>h){
						var nnh=nh-h;
						w=w-nnh;
					}
					iw='width='+w,
					ih='';
				}else{
					iw='',
					ih='height='+h; 
					
				}
			}
			dialog_pr.content('<div class="priview_img"><a href="'+img+'" target="_blank" title="点击在新窗口中打开"><img src="'+img+'" '+iw+' '+ih+' /></a></div>');
		
		};
    });
}


//图片上传回调,直接输入图片网址时
function submit_images(uploadid, returnid) {
    var d = uploadid.iframe.contentWindow;
    var in_content = d.$("#att-status").html().substring(1);
    var in_content = in_content.split('|');
	//alert(in_content);
    IsImg(in_content[0]) ? $('#' + returnid).attr("value", in_content[0]) : alert('选择的类型必须为图片类型');
}

//单文件上传回调
function submit_attachment(uploadid, returnid) {
    var d = uploadid.iframe.contentWindow;
    var in_content = d.$("#att-status").html().substring(1);
    var in_content = in_content.split('|');
   $('#' + returnid).attr("value", in_content[0]);
}

//移除ID
function remove_id(id) {
    $('#' + id).remove();
}

//输入长度提示
function strlen_verify(obj, checklen, maxlen) {
    var v = obj.value,
        charlen = 0,
        maxlen = !maxlen ? 200 : maxlen,
        curlen = maxlen,
        len = strlen(v);
    var charset = 'utf-8';
    for (var i = 0; i < v.length; i++) {
        if (v.charCodeAt(i) < 0 || v.charCodeAt(i) > 255) {
            curlen -= charset == 'utf-8' ? 2 : 1;
        }
    }
    if (curlen >= len) {
        $('#' + checklen).html(curlen - len);
    } else {
        obj.value = mb_cutstr(v, maxlen, true);
    }
}

//长度统计
function strlen(str) {
    return ($.browser.msie && str.indexOf('\n') != -1) ? str.replace(/\r?\n/g, '_').length : str.length;
}
function mb_cutstr(str, maxlen, dot) {
    var len = 0;
    var ret = '';
    var dot = !dot ? '...' : '';

    maxlen = maxlen - dot.length;
    for (var i = 0; i < str.length; i++) {
        len += str.charCodeAt(i) < 0 || str.charCodeAt(i) > 255 ? (charset == 'utf-8' ? 3 : 2) : 1;
        if (len > maxlen) {
            ret += dot;
            break;
        }
        ret += str.substr(i, 1);
    }
    return ret;
}

//移除指定id内容
function remove_div(id) {
    $('#' + id).remove();
}


//验证地址是否为图片
function IsImg(url) {
    var sTemp;
    var b = false;
    var opt = "jpg|gif|png|bmp|jpeg";
    var s = opt.toUpperCase().split("|");
    for (var i = 0; i < s.length; i++) {
        sTemp = url.substr(url.length - s[i].length - 1);
        sTemp = sTemp.toUpperCase();
        s[i] = "." + s[i];
        if (s[i] == sTemp) {
            b = true;
            break;
        }
    }
    return b;
}

//验证地址是否为Flash
function IsSwf(url) {
    var sTemp;
    var b = false;
    var opt = "swf";
    var s = opt.toUpperCase().split("|");
    for (var i = 0; i < s.length; i++) {
        sTemp = url.substr(url.length - s[i].length - 1);
        sTemp = sTemp.toUpperCase();
        s[i] = "." + s[i];
        if (s[i] == sTemp) {
            b = true;
            break;
        }
    }
    return b;
}

//添加地址
function add_multifile(returnid) {
    var ids = parseInt(Math.random() * 10000);
    var str = "<li id='multifile" + ids + "'><input type='text' name='" + returnid + "_fileurl[]' value='' style='width:310px;' class='input'> <input type='text' name='" + returnid + "_filename[]' value='附件说明' style='width:160px;' class='input'> <a href=\"javascript:remove_div('multifile" + ids + "')\">移除</a> </li>";
    $('#' + returnid).append(str);
}

$(function(){

if($('.errorSummary').length>0){
	$('#msgtip').slideDown();

}
$('body').click(function(){
	$('#msgtip').slideUp();
});
$(document).on('click', '.switch',function(){
  var _this=this;
  $(this).find('div').eq(0).toggleClass("switch-off");
  if($(this).attr('data-url')){
		var n=$(this).find('input').eq(0).attr('name');
		var d=$(this).find('input').eq(0).val();
		$.post( 
			$(this).attr('data-url'),
			{n:d},
			function(data){
				var fun=switchCallback;
				if(typeof(fun)=='function'){
					fun(data,_this);
				}
			}
		);
  }else{
	   if( $(this).find('input').eq(0).val()==$(this).attr('data-left')){
		 $(this).find('input').eq(0).val($(this).attr('data-right'))
	   }else{
		 $(this).find('input').eq(0).val($(this).attr('data-left'))
	   }
  }
});
$(document).on('click', '.btn-group>button',function(){
				$(this).siblings('button').removeClass('bg-blue-gbtn');
				$(this).closest('div').find('i').removeClass('icon-check');
				$(this).find('i').eq(0).addClass('icon-check');
				$(this).addClass('bg-blue-gbtn');
				$("select[name='"+$(this).attr('selectid')+"']").val($(this).attr('data')).trigger('change');
			
			});
$(document).on('click', '.multiple-select a',function(){
		if($(this).hasClass("primary-bg")){
			$(this).siblings('select').find('option[value="'+$(this).attr('data')+'"]').removeAttr('selected');
	    }else{
	    	$(this).siblings('select').find('option[value="'+$(this).attr('data')+'"]').attr('selected','selected');
	    }
		$(this).toggleClass('primary-bg');
		$(this).find('i').eq(0).toggleClass('icon-plus').toggleClass('icon-ok');
	});
});

/**
**无刷新上传
**who dom控件 cfun回调函数 _upfun上传方法 
**/
function ajax_up(who,cfun,_upfun,_iswater,_p){
 Wind.use("ajaxupload",function(){
 var tip=false;
	new AjaxUpload(who, {
		// 服务器端上传脚本
		// 注意: 文件不允许上传到另外一个域上
		action: WEBURL + '/commonUpload/ajax_up',
		// 文件上传的名字
		name: 'userfile',
		data: {
			upfun : _upfun,
			iswater : _iswater,
			order_p:_p
		},
		autoSubmit: true,
		responseType: "json",
		onSubmit: function(file , ext) {
			if (! (ext && /^(jpg|png|jpeg|gif|ico|bmp|zip|xls)$/.test(ext))){
				alert('错误：不支持的格式！');
				return false;
			}
			this.disable;
			//if(tip)tip.close();
			$.jGrowl("正在上传文件 <b>"+file+"</b>请不要刷新页面...", {
                sticky: true,
                position: "bottom-right",
                theme: "bg-red btn text-left",
				afterOpen:function(e){
						var _this=this;
						var ttt=setTimeout(function(){
							if(tip){
								$(_this).remove();
								clearTimeout(ttt);
							}
						},200);
						
				}
            });
			
		},
		onComplete: function(file, response) {
			tip=true;
			this.enable;
			if(response.code=="ajaxerror"){
				alert(response.msg);
			}else{
				cfun(response,who);
			}
		}
	});
});
}
/***信息提示框 未压缩版
 * wintrue
 * version:M.js 2.0
***/
(function(w){
	var M=w.M={};
	var msgTip_zindex=8848;
	var msgTip;
	M.alert=function(msg,time,lock,speed,callback_fun){
			 msgTip({type:'alert',timeOut : time|3000,msg : msg,speed :speed|400,lock:lock|false,callback:callback_fun});
	};
	M.error=function(msg,time,lock,speed,callback_fun){
			 msgTip({type:'error',timeOut : time|3000,msg : msg,speed :speed|400,lock:lock|false,callback:callback_fun});
	};
	M.success=function(msg,time,lock,speed,callback_fun){
			 msgTip({type:'success',timeOut : time|3000,msg : msg,speed :speed|400,lock:lock|false,callback:callback_fun});
	};
	M.notice=function(msg,time,lock,speed,callback_fun){
			 msgTip({type:'notice',timeOut : time|3000,msg : msg,speed :speed|400,lock:lock|false,callback:callback_fun});
	};
	M.confirm=function(msg,time,lock,speed,callback_fun){
			 msgTip({type:'confirm',timeOut : time|3000,msg : msg,speed :speed|400,lock:lock|false,callback:callback_fun});
	};
	M.prompt=function(msg,time,lock,speed,callback_fun){
			 msgTip({type:'prompt',timeOut : time|3000,msg : msg,speed :speed|400,lock:lock|false,callback:callback_fun});
	};
	M.progressbar=function(msg,callback_fun){
			return  new msgTip({type:'bar ui_notice',timeOut : 3000000,msg : msg,speed :400,lock:false,callback:callback_fun,animate:false});
	};
	
	M.msgTip=function(options){
			return  msgTip(options);
	};
	msgTip = function(options) {
		var defaults = {
			timeOut : 2000,				//提示层显示的时间
			msg : "wintrue,thank you!",	//显示的消息
			speed : 300,				//滑动速度
			lock:false,					//锁屏
			type : "success",			//提示类型（1、success 2、error 3、warning）
			animate:true,
			callback:"msgTip_callback"		
		};
		var options = $.extend(defaults,options);
		var m=new Date();
		var bid=m.getTime().toString(36);
		var isIE6 = !-[1, ] && !window.XMLHttpRequest;
		if(isIE6){	var style=' _width:0;white-space: nowrap;';		}else{var style=' width:auto;white-space: nowrap;';		}
		var shade='<div id=wttip_shade'+bid+' style="position: absolute; top: 0px; left: 0px; filter: alpha(opacity=20);-moz-opacity: 0.2;opacity: 0.2; width: '+$(document).width()+'px; height: '+$(document).height()+'px; margin: 0px; padding: 0px; background-color: #333;z-index: '+(msgTip_zindex++)+';"></div>';
		var div='<div id=wttip'+bid+' style="position: absolute;'+style+'height: auto;filter: alpha(opacity=0);-moz-opacity: 0;opacity: 0; z-index: '+(msgTip_zindex++)+';" class="ui_'+options.type+'"><p class="ui_msg">'+options.msg+'</p></div>';
		if($('#wttip'+bid).length>0){$('#wttip'+bid).show();}else{	$("body").prepend(options.lock==true?shade+div:div);}	
		if($('#wttip'+bid).width()<150){			$('#wttip'+bid).width(200);		}
		$('#wttip'+bid).css({
		    position:'absolute',
		    left: ($(window).width() - $('#wttip'+bid).width())/2+$(document).scrollLeft()-10,
		    top: ($(window).height() - $('#wttip'+bid).outerHeight())/2 + $(document).scrollTop()-100
		 });
		$('#wttip'+bid).show();
		if(options.animate){
			$('#wttip'+bid).animate({ 
				top:$('#wttip'+bid).offset().top+80, 
				opacity: "1"
			}, options.speed );
		}else{
			$('#wttip'+bid).css('opacity',1).css('top',$('#wttip'+bid).offset().top);
			
		}
		
		var timervo='timer'+bid;
		timervo = setTimeout(function (){
		    $('#wttip_shade'+bid).fadeOut(options.speed);
			
			if(options.animate){
				$('#wttip'+bid).animate({ 
					top: $('#wttip'+bid).offset().top+80, 
					opacity: "0"
				 }, options.speed ,function(){
					 destroy();
				});
			}else{
				destroy();
			}
		}, options.timeOut);
		$('#wttip'+bid).click(function(){
			destroy();
		});
		this.close=function(){
			destroy();
		}
		var destroy=function(){
			$('#wttip'+bid+','+'#wttip_shade'+bid).remove();
			clearTimeout(timervo);
			if(typeof(options.callback)=='function'){
				var fun=options.callback;
				fun();
			}
		}
}
})(window);

