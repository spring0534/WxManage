/***插件公共组件 依赖jquery或者Zepto
 *  wintrue & arvin
 *  version: 1.0
***/
/*addon.widget.focus({
       focusType:'2',  //1强制关注 ，2引导关注，0不关注
	   isSubscribe:0,//是否已关注 关注后将不会显示弹窗
       title:'温馨提示',
       content:'为了方便领奖，请先关注我们',        
	   focusUrl:'http://hao123.com'
    })
*/
/*addon.widget.userForm({
	ajaxUrl:'http://hao123.com',//提交的ajax地址
	show:1,//是否显示 无论是否显示，都会调用callback方法（如果有callback方法）
	userNameField:'uname',//自定义 姓名的name
	userPhoneField:'phone',//自定义 手机的name
	ext:{addr:'地址',card:'身份证'},//扩展信息，支持加添其它需要登记的信息
	callback: function(result){
		alert(result);
	} //callback 回调函数，如果登记成功，则result为true
});*/
(function(w){
	var M=w.M={},addon=w.addon={};
	var widget_zindex=8848;
	var zwWidth = document.body.clientWidth,
        zwscale=zwWidth/640;
	M.alert=function(e){
		var m=new Date(),bid=m.getTime().toString(36),_e=e;;
		if(!e.content){e={};e.content=_e;}
		var defaults = {
			closeId : bid,
			Zindex:widget_zindex,
			type : 'alert',	//alert confirm prompt			
			title:'提示',
			content:'hello world!'			
		};
		var options = $.extend(defaults,e);
            var html = '';
            html += '<section id="'+bid+'" class="zw-popup" style="z-index:'+defaults.Zindex+'">';
            html += '<div class="zw-boxItemDiv" style="-webkit-transform:scale('+zwscale+');transform:scale('+zwscale+');"><div class="zw-boxItem zw-boxItem_alert">';
            html += '<div  class="zw-close btn_'+bid+'" ></div>';
            html += '<div class="zw-title">' + options.title + '</div>';
            html += '<div class="zw-content">';
            html += '<div class="zw-tips">' + options.content + '</div>';
			if (options.type=='alert') {
				 html += '<div  class="zw-btn zw-cancel btn_'+bid+'">确定</div>';
			}else if(options.type='confirm'){
				 html += '<div  class="zw-btn zw-cancel btn_'+bid+'">取消</div>';
				 html += '<div  class="zw-btn zw-confirm btn2_'+bid+'" >确定</div>';
			}else{
				 html += '<div  class="zw-btn zw-cancel btn_'+bid+'">取消</div>';
				 html += '<div  class="zw-btn zw-confirm btn3_'+bid+'" >确定</div>';
			}
            html += '</div>';
			html += '</div>';
            html += '</div>';
            html += '</section>';
            $('body').append(html);
			widget_zindex++;
			$('.zw-boxItem_alert').css({
				position:'absolute',top: ($(window).height() - $('.zw-boxItem_alert').height()-80)/2
			 }).css({'z-index':defaults.Zindex+1});
			$(document).on("touchend",".btn_"+bid, function(ev) {
				options.callback='';closeId(options);
			}).on("touchend",".btn2_"+bid, function(ev) {
				if(typeof(options.callback)=='function'){	var fun=options.callback;	fun();}
				options.callback='';	closeId(options);
			}).on("touchend",".btn2_"+bid, function(ev) {
				if(typeof(options.callback)=='function'){	var fun=options.callback;	fun();}
				options.callback='';closeId(options);
			});
	};
	M.focus=function(e){
		var m=new Date(),bid=m.getTime().toString(36);
		var defaults = {
			closeId : bid,
			Zindex:widget_zindex,
			isSubscribe:0,
			focusType : 2,				
			title:'温馨提示',
			content:'为了方便领奖，请先关注我们'			
		};
		var options = $.extend(defaults,e);
		if(options.isSubscribe)return;
		if (options.focusType == 1 || options.focusType == 2) {
            var html = '';
            html += '<section id="'+bid+'" class="zw-popup" style="z-index:'+defaults.Zindex+'">';
            html += '<div class="zw-boxItemDiv" style="-webkit-transform:scale('+zwscale+');transform:scale('+zwscale+');"><div class="zw-boxItem zw-boxItem_focus">';
            if (options.focusType == 2) {
                html += '<div  class="zw-close btn_'+bid+'" ></div>';
            }
            html += '<div class="zw-title">' + options.title + '</div>';
            html += '<div class="zw-content">';
            html += '<div class="zw-tips">' + options.content + '</div>';
            if (options.focusType == 2) {
                html += '<div  class="zw-btn zw-cancel btn_'+bid+'">我已关注</div>';
            }
            html += '<a href="' + options.focusUrl + '" class="zw-btn zw-confirm" >现在关注</a>';
            html += '</div>';
            html += '</div>';
			 html += '</div>';
            html += '</section>';
            $('body').append(html);
			widget_zindex++;
			$('.zw-boxItem_focus').css({
				position:'absolute',
				top: ($(window).height() - $('.zw-boxItem_focus').height()-80)/2
			}).css({'z-index':defaults.Zindex+1});
			$(document).on("touchend",".btn_"+bid, function(ev) {
				closeId(options);
			});
        }else{
			if(typeof(options.callback)=='function'){
				var fun=options.callback;fun();
			};
		};
	};
	
	M.userForm=function(e){
		var m=new Date(),bid=m.getTime().toString(36);
		var defaults = {
			closeId : 'userForm'+bid,				
			formId : '_userForm'+bid,
			Zindex:widget_zindex,
			userNameField:'userName',
			userPhoneField:'userPhone',
			show : 1,				
			btnText:'提交',		
			animate:false,	
		};
		var options = $.extend(defaults,e);
		if(options.show){
			var html = '';
			html += '<section id="'+options.closeId+'" class="zw-popup"  style="z-index:'+defaults.Zindex+'">';
			html += '<div class="zw-boxItemDiv" style="-webkit-transform:scale('+zwscale+');transform:scale('+zwscale+');"><div class="zw-boxItem zw-boxItem_userForm">';
			html += '<div class="zw-close userForm_close_'+bid+'" ></div>';
			html += '<div class="zw-title">信息登记</div>';
			html += '<div class="zw-content">';
			html += '<div class="zw-form"><form id="'+options.formId+'">';
			html += '<input type="text" name="'+options.userNameField+'" id="'+options.userNameField+'" placeholder="姓名"  >';
			html += '<input type="text" name="'+options.userPhoneField+'" id="'+options.userPhoneField+'" placeholder="手机"  >';
			if(options.ext){
					for(var i in options.ext){
						html += '<input type="text" name="'+i+'" placeholder="'+options.ext[i]+'"  >';
					}
			};
			html += '</form></div>';
			html += '<div  id="sendBtn_'+bid+'" class="zw-btn">';
			html += options.btnText;
			html += '</div> </div> </div> </div></section>';
			$('body').append(html);
			widget_zindex++;
			$('.zw-boxItem_userForm').css({
				position:'absolute',
				top: ($(window).height() - $('.zw-boxItem_userForm').height()-80)/2
			 }).css({'z-index':defaults.Zindex+1});
			$(document).on("touchend",".userForm_close_"+bid, function(ev) {
					closeId(options);
			});
			$(document).on("touchend","#sendBtn_"+bid, function(ev) {
					sendForm(options);
			});
		}else{
			if(typeof(options.callback)=='function'){
				var fun=options.callback;fun();
			}
		};
	};
    function sendForm(e) {
		if($('#'+e.userNameField).val()==''){addon.widget.alert('请输入姓名');return;}
		if($('#'+e.userPhoneField).val()==''){addon.widget.alert('请输入电话号码');return;}
        $.ajax({
            type: 'post',
            url: e.ajaxUrl,
            data: $('#'+e.formId).serialize(),
            dataType: 'json',
            timeout: 15000,
            success: function (data) {
                if (data.result_code == 1) {
                    closeId(e,true)
                } else {
                    addon.widget.alert(data.result_msg);
                }
            },
            error: function (xhr, type) {
                addon.widget.alert('网络超时，请刷新后再试！');
            }
        });
    }
    function closeId(e,result) {
        var focus = document.getElementById(e.closeId);
        focus.style.opacity = '0';
        setTimeout(function () {
           $('#'+e.closeId).remove();
			if(typeof(e.callback)=='function'){
				var fun=e.callback;
				fun(result);
			}
			
        }, 500);
    };
	addon.widget=M;
})(window);