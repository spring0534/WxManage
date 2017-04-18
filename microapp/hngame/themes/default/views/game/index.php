<!DOCTYPE html>
<html lang="zh-CN"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>湖南游戏老乡会</title>
<style type="text/css">
a,abbr,acronym,address,applet,article,aside,audio,big,blockquote,body,canvas,caption,center,cite,code,dd,del,details,dfn,div,dl,dt,em,embed,fieldset,figcaption,figure,footer,form,h1,h2,h3,h4,h5,h6,header,hgroup,html,i,iframe,img,ins,kbd,label,legend,li,mark,menu,nav,object,ol,output,p,pre,q,ruby,s,samp,section,small,span,strike,strong,sub,summary,sup,table,tbody,td,tfoot,th,thead,time,tr,tt,u,ul,var,video{margin:0;padding:0;border:0;font-size:100%;font:inherit;vertical-align:baseline}
article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section{display:block}
body{line-height:1}
ol,ul{list-style:none}
blockquote,q{quotes:none}
blockquote:after,blockquote:before,q:after,q:before{content:'';content:none}
table{border-collapse:collapse;border-spacing:0}
body{background:#f8f8f8;font-family:"Microsoft YaHei","Microsoft JhengHei",STHeiti,MingLiu;text-align:center;color:#fff}
.login-container{margin:10% auto 0 auto}
.register-container{margin:6% auto 0 auto}
h1{font-size:30px;font-weight:700;text-shadow:0 1px 4px rgba(0,0,0,.2)}
form{position:relative;width:305px;margin:15px auto 0 auto;text-align:center}
input{width:270px;height:42px;line-height:42px;margin-top:8px;padding:0 15px;background:#2d2d2d;background:rgba(45,45,45,.15);-moz-border-radius:6px;-webkit-border-radius:6px;border-radius:6px;border:1px solid #3d3d3d;border:1px solid rgba(255,255,255,.15);-moz-box-shadow:0 2px 3px 0 rgba(0,0,0,.1) inset;-webkit-box-shadow:0 2px 3px 0 rgba(0,0,0,.1) inset;box-shadow:0 2px 3px 0 rgba(0,0,0,.1) inset;font-family:"Microsoft YaHei",Helvetica,Arial,sans-serif;font-size:14px;color:#fff;text-shadow:0 1px 2px rgba(0,0,0,.1);-o-transition:all .2s;-moz-transition:all .2s;-webkit-transition:all .2s;-ms-transition:all .2s}
input:-moz-placeholder{color:#fff}
input:-ms-input-placeholder{color:#fff}
input::-webkit-input-placeholder{color:#fff}
input:focus{outline:0;-moz-box-shadow:0 2px 3px 0 rgba(0,0,0,.1) inset,0 2px 7px 0 rgba(0,0,0,.2);-webkit-box-shadow:0 2px 3px 0 rgba(0,0,0,.1) inset,0 2px 7px 0 rgba(0,0,0,.2);box-shadow:0 2px 3px 0 rgba(0,0,0,.1) inset,0 2px 7px 0 rgba(0,0,0,.2)}
button{cursor:pointer;width:300px;height:44px;margin-top:25px;padding:0;  background: rgba(6, 127, 228, 0.71);-moz-border-radius:6px;-webkit-border-radius:6px;border-radius:6px;border:0;-moz-box-shadow:0 15px 30px 0 rgba(255,255,255,.25) inset,0 2px 7px 0 rgba(0,0,0,.2);font-family:"Microsoft YaHei",Helvetica,Arial,sans-serif;font-size:14px;font-weight:700;color:#fff;text-shadow:0 1px 2px rgba(0,0,0,.1);-o-transition:all .2s;-moz-transition:all .2s;-webkit-transition:all .2s;-ms-transition:all .2s}
button:hover{-moz-box-shadow:0 15px 30px 0 rgba(255,255,255,.15) inset,0 2px 7px 0 rgba(0,0,0,.2);-webkit-box-shadow:0 15px 30px 0 rgba(255,255,255,.15) inset,0 2px 7px 0 rgba(0,0,0,.2);box-shadow:0 15px 30px 0 rgba(255,255,255,.15) inset,0 2px 7px 0 rgba(0,0,0,.2)}
button:active{-moz-box-shadow:0 15px 30px 0 rgba(255,255,255,.15) inset,0 2px 7px 0 rgba(0,0,0,.2);-webkit-box-shadow:0 15px 30px 0 rgba(255,255,255,.15) inset,0 2px 7px 0 rgba(0,0,0,.2);box-shadow:0 5px 8px 0 rgba(0,0,0,.1) inset,0 1px 4px 0 rgba(0,0,0,.1);border:0 solid #016FCB}
*{margin:0;padding:0}
body{background:#333;height:100%}
img{border:none}
.box-line{height: 0; overflow: hidden; border-top: 1px #c2c2c2 solid; margin-top: 20px;}
label.error{display: block; -webkit-transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out; transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out; border-radius: 0px 0px 5px 5px; width: 300px; height: 34px; border: 1px solid rgba(255,255,255,.15); line-height: 31px; box-shadow: 0 2px 3px 0 rgba(0,0,0,.1) inset; text-shadow: 0 1px 2px rgba(0,0,0,.1); background: rgba(245, 26, 26, 0.81); -moz-border-radius: 6px; margin: 0 auto;}
</style>
</head>
<body>
<div class="register-container">
	<h1>录入通讯录</h1>
	<form action="<?php echo AU('game/save');?>" method="post" id="registerForm" novalidate="novalidate">
		<div>
			<input type="text" name="realname" id="realname" placeholder="姓名">
		</div>
		<div>
			<input type="text" name="company" id="company" placeholder="公司">
		</div>
		<div>
			<input type="text" name="position" id="position" placeholder="职位">
		</div>
		<div>
			<input type="text" name="address" id="address" placeholder="家乡">
		</div>
		<div>
			<input type="text" name="qq" id="qq" placeholder="QQ">
		</div>
		<div>
			<input type="text" name="wxname" id="wxname" placeholder="微信号">
		</div>
		<div>
			<input type="text" name="phone" id="phone" placeholder="手机号">
		</div>
		<div>
			<input type="text" name="email" id="email" placeholder="邮箱">
		</div>
		<div>
			<input type="text" name="resource" id="resource" placeholder="资源">
		</div>
		<div>
			<input type="text" name="demand" id="demand" placeholder="需求">
		</div>
		<button id="submit" type="button" class="subbtn">保   存</button>
	</form>
</div>
<script src="<?php echo $this->assets(); ?>/js/jquery.min.js"></script>
<script src="<?php echo $this->assets(); ?>/js/jquery.validate.js"></script> <!--表单验证-->
<script src="<?php echo $this->SURL(); ?>/js/alert.js"></script>
<script type="text/javascript">
//jquery.validate表单验证
$(document).ready(function(){
	//注册表单验证
	$("#registerForm").validate({
		rules:{
			realname:{
				required:true,//必填
				minlength:2, //最少2个字符
				maxlength:20,//最多20个字符
			},
			phone:{
				phone_number:true,//自定义的规则
				digits:true,//整数
			},
			email:{
				email:true,
			}
		},
		//错误信息提示
		messages:{
			realname:{
				required:"必须填写姓名",
				minlength:"请输入正确的姓名",
				maxlength:"请输入正确的姓名",
			},
			email:{
				email: "请输入正确的邮箱"
			},
			phone_number:{
				digits:"请输入正确的手机号",
			},
		
		},
	});
	//添加自定义验证规则
	jQuery.validator.addMethod("phone_number", function(value, element) { 
		var length = value.length; 
		var phone_number = /(^(([0\+]\d{2,3}-)?(0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$)|(^0{0,1}1[3|4|5|6|7|8|9][0-9]{9}$)/; 
		return this.optional(element) || (length == 11 && phone_number.test(value)); 
	}, "手机号码格式错误"); 

	var isRunning = false;
	$("#registerForm").validate();
	$("#submit").click(function(){
	    if($("#registerForm").valid()){
	        if(isRunning) return;
	        isRunning = true;
	        $.ajax({  
	            type : "POST",  
	            url : "<?php echo AU('game/save');?>",
	            data : {  
	                "realname" : $("#realname").val(),
	                "company" : $("#company").val(),
	                "position" : $("#position").val(),
	                "address" : $("#address").val(),
	                "qq" : $("#qq").val(),
	                "wxname" : $("#wxname").val(),
	                "phone" : $("#phone").val(),
	                "email" : $("#email").val(),
	                "resource" : $("#resource").val(),
	                "demand" : $("#demand").val(),
	            },//数据，这里使用的是Json格式进行传输  
	            dataType:'json',
	            success : function(result) {//返回数据根据结果进行相应的处理  
	                if(result.result_code == 0){
	                	$("input[type='text']").val("");
	                	alert(result.result_msg);
	                }
	                isRunning = false;
	            },
	            error:function(xhr){
	            	isRunning = false;
	            },
	        });  
	 	}
	});
});
</script>
</body>
</html>