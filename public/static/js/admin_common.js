
function redirect(url) {
	location.href = url;
}
function confirmurl(url,message) {
	if(confirm(message)) redirect(url);
}
//滚动条
$(function(){
	$(":text").addClass('input-text');
})

/**
 * 全选checkbox,注意：标识checkbox id固定为为check_box
 * @param string name 列表check名称,如 uid[]
 */
function selectall(name) {
	
		$("input[name='"+name+"']").each(function() {
			this.checked=!this.checked;
		});
	
}
function openwinx(url,name,w,h) {
	if(!w) w=screen.width-4;
	if(!h) h=screen.height-95;
	url = url+'&pc_hash='+pc_hash;
    window.open(url,name,"top=100,left=400,width=" + w + ",height=" + h + ",toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=no");
}
//弹出对话框
function omnipotent(id,linkurl,title,close_type,w,h) {
	if(!w) w=700;
	if(!h) h=500;
	art.dialog({id:id,iframe:linkurl, title:title, width:w, height:h, lock:true},
	function(){
		if(close_type==1) {
			art.dialog({id:id}).close()
		} else {
			var d = art.dialog({id:id}).data.iframe;
			var form = d.document.getElementById('dosubmit');form.click();
		}
		return false;
	},
	function(){
			art.dialog({id:id}).close()
	});void(0);
}

function chagelinkpage(pid,field){
	$("#"+field+"div").load(APPS+"/Diyform/linksub/pid/"+pid+"/field/"+field);
}

//从父框架中获取子框架 的元素@wintrue
function $p(who){
	return $("#rightMain").contents().find(who);
}

//从子框架中获取父框架中的元素@wintrue
function $c(who){
	return $(window.parent.document).find(who);
	//return $(id,window.parent.document);
}
//滚动到页面指定位置
var slide = function(id,time){
    if($(id).length==0){
        return false;
    }
    $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
    $body.animate({
        scrollTop: $(id).offset().top
    }, time);
}

/**
**无刷新上传
**who dom控件 cfun回调函数 _upfun上传方法 
**/
function ajax_up(who,cfun,_upfun,_iswater,_isthumb,_width,_height){
	new AjaxUpload(who, {
		action: weburl+'/common/Fileupload/ajax_up.html',
		name: 'userfile',
		data: {
			upfun : _upfun,
			iswater : _iswater,
			iswater : _iswater,
			isthumb : _isthumb,
			width : _width,
			height : _height
		},
		autoSubmit: true,
		responseType: "json",
		onSubmit: function(file , ext) {
			if (! (ext && /^(jpg|png|jpeg|gif|ico|bmp)$/.test(ext))){
				alert('错误：不支持的图片格式！');
				return false;
			}
			this.disable;
			
		},
		onComplete: function(file, response) {
			this.enable;
			if(response.code=="ajaxerror"){
				alert(response.msg);
			}else{
				cfun(response,who);
			}
		}
});
}

//获取地区信息
function area_change(who, level, cityid, areaid, nextseltval) {
    $.get(weburl + "/admin/Index/ajax_AreaSel/myidval/" + ($(who).val()) + "/nextseltval/" + nextseltval + webfix, function(msg) {
        
        if (level == 0) {
            $("#" + cityid).html(msg.oplist);
            $("#" + areaid).html("<option value='-1'>请选择...</option>");
        } else {
            $("#" + areaid).html(msg.oplist);
        }
    }, "json");


}