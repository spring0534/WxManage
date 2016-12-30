if(!Array.prototype.map)
Array.prototype.map = function(fn,scope) {
  var result = [],ri = 0;
  for (var i = 0,n = this.length; i < n; i++){
	if(i in this){
	  result[ri++]  = fn.call(scope ,this[i],i,this);
	}
  }
return result;
};
var getWindowSize = function(){
return ["Height","Width"].map(function(name){
  return window["inner"+name] ||
	document.compatMode === "CSS1Compat" && document.documentElement[ "client" + name ] || document.body[ "client" + name ]
});
}
$(function(){
$(window).resize(function(){  
wSize();
});
});


function wSize(){
	//这是一字符串
	var str=getWindowSize();
	var strs= new Array(); //定义一数组
	strs=str.toString().split(","); //字符分割
	var heights = strs[0]-60,Body = $('body');$('#rightMain').height(heights);   
	/*iframe.height = strs[0]-66;*/
	if(strs[1]<980){
		$('#content').css('width',980+'px');
		Body.attr('scroll','');
		Body.removeClass('objbody');
	}else{
		$('#content').css('width','auto');
		Body.attr('scroll','no');
		Body.addClass('objbody');
		Body.css('overflow','hidden');
	}
	
}
wSize();
function _M(menuid,targetUrl) {
	$("#menuid").val(menuid);
	$("#bigid").val(menuid);
	$('.top_menu').removeClass("on");
	$('#_M'+menuid).addClass("on");

}
function _MP(menuid,targetUrl) {
	$("#menuid").val(menuid);
	$("#rightMain").attr('src', targetUrl);
	$('.current-page').removeClass("current-page");
	$('#_MP'+menuid).addClass("current-page");
	stopEvent();
	//event=event?event:window.event; event.stopPropagation();
}
function stopEvent(){ //阻止冒泡事件
	
	 //取消事件冒泡 
	 var e=arguments.callee.caller.arguments[0]||event; //若省略此句，下面的e改为event，IE运行可以，但是其他浏览器就不兼容
	 if (e && e.stopPropagation) { 
	  // this code is for Mozilla and Opera
	  e.stopPropagation(); 
	 } else if (window.event) { 
	  // this code is for IE 
	  window.event.cancelBubble = true; 
	 } 
	
}
var $E = function(){var c=$E.caller; while(c.caller)c=c.caller; return c.arguments[0]};
__defineGetter__("event", $E);

