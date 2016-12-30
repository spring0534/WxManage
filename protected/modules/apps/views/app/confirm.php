<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta charset="UTF-8">
    <title>登录提示</title>
    <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
<style type="text/css">
* {
	margin: 0;
	padding: 0
}
html {
	overflow-x: auto
}
body {
	position: absolute;
	width: 100%;
	min-height: 100%;
	background: url('http://imgcache.qq.com/ptlogin/v4/style/35/images/bg_1.png?v=2013031101');
	background-size: 5px 5px;
	font-family: '微软雅黑','Helvetica Neue',Helvetica,Arial,sans-serif;
	font-size: 16px;
	color: #555;
	-webkit-user-select: none;
	-webkit-user-drag: none;
	-webkit-text-size-adjust: none
}
a {
	text-decoration: none
}
button {
	border: 0;
	background: transparent;
	font-family: '微软雅黑','Helvetica Neue',Helvetica,Arial,sans-serif;
	font-size: 16px
}
input,button,.api_list,.control {
	-webkit-tap-highlight-color: rgba(255,255,255,0)
}
li {
	list-style: none
}
.hide {
	display: none
}
.input_wrap {
	border: 1px solid rgba(0,0,0,0.15);
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	background: white;
	-webkit-box-shadow: inset 0 1px 3px rgba(215,215,215,0.75),0 1px rgba(255,255,255,0.2);
	-moz-box-shadow: inset 0 1px 3px rgba(215,215,215,0.75),0 1px rgba(255,255,255,0.2);
	box-shadow: inset 0 1px 3px rgba(215,215,215,0.75),0 1px rgba(255,255,255,0.2);
	overflow: hidden
}
.inner_wrap {
	position: relative;
	padding-left: 16px
}
.inner_wrap.show_clear {
	padding-left: 44px
}
.show_clear input {
	margin-left: -44px;
	padding-right: 36px;
	pointer-events: none
}
.show_clear .clear {
	display: block
}
input {
	margin-left: -16px;
	border: 0;
	padding: 9px 8px 9px;
	width: 100%;
	height: 22px;
	background: transparent;
	font-size: 16px;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
	box-shadow: none;
	-webkit-appearance: none;
	font-family: '微软雅黑','Helvetica Neue',Helvetica,Arial,sans-serif;
	color: #555;
	outline: 0
}
#verifycode {
	margin-left: 0
}
.input_pwd {
	border-top: 1px solid #d7d7d7;
	-webkit-border-radius: 0;
	-moz-border-radius: 0;
	border-radius: 0
}

.active .clear {
	background-color: #9b9b9b
}
.input_wrap_code {
	display: inline-block;
	width: 30%
}
.verify_code {
	margin-top: 13px
}
.verify_show {
	float: right;
	display: inline-block;
	margin-right: 5px
}
#imgVerify {
	display: inline-block;
	width: 103px;
	height: 42px;
	vertical-align: bottom
}
#verifytip {
	font-size: 14px;
	color: #418cf0
}
#verifytip.active {
	color: #1670eb
}
.page_header {
	position: relative;
	padding: 18px 0 15px 15px
}
.auth_logo {
	display: inline-block;
	width: 31px;
	height: 34px;
	vertical-align: top;
	background: url("/static/icon.png?v=2013031101") no-repeat;
	background-size: 35px 84px;
	background-position: 0 -50px;

}
.page_header h1 {
	display: inline-block;
	margin-left: 5px;
	font-size: 18px;
	font-weight: normal;
	line-height: 34px;
	color: #232323;
margin-bottom: 13px;
}
.page_header p {
	position: absolute;
	right: 10px;
	bottom: 10px;
	font-size: 10px
}
.page_content {
	padding: 0 10px
}
.cookie_login {
	position: relative;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border: 1px solid #c6c6c6;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	padding: 14px;
	background: white;
	-webkit-box-shadow: 0 1px 1px #d9d9d9;
	-moz-box-shadow: 0 1px 1px #d9d9d9;
	box-shadow: 0 1px 1px #d9d9d9
}
.useravatar {
	display: inline-block;
	border: 1px solid #d9d9d9;
	padding: 1px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	vertical-align: top
}
.useravatar img {
	display: block;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	width: 49px;
	height: 49px
}
.userinfo {
	display: inline-block;
	margin-left: 5px
}
.userqq {
	margin-top: 3px;
	color: #bbb
}
.usernick,.userqq {
	line-height: 20px;
	word-wrap: break-word;
	word-break: break-all
}
.switch {
	position: absolute;
	right: 14px;
	line-height: 16px;
	color: #418cf0;
	top: 50%;
	margin-top: -8px
}
.switch.active {
	color: #1670eb
}
.switch span {
	display: inline-block;
	width: 18px;
	height: 16px;
	background: url("http://imgcache.qq.com/ptlogin/v4/style/35/images/icon.png?v=2013031101");
	background-size: 35px 84px;
	background-position: -17px 0;
	margin-right: 7px;
	vertical-align: top
}
.switch.active span {
	background-position: -17px -16px
}

.btn {
	display: block;
	border: 1px solid;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	width: 35%;
	height: 40px;
	overflow: hidden;
	font-family: '微软雅黑','Helvetica Neue',Helvetica,Arial,sans-serif;
	font-size: 16px;
	line-height: 40px;
	color: #fff;
	text-align: center;
	text-decoration: none;
	text-overflow: ellipsis;
	cursor: pointer
}
.btn_lightblue {
	border-color: #40a217;
	background: #71c228;
	background: -webkit-gradient(linear,left top,left bottom,from(#71c228),to(#75c92a));
	background: -webkit-linear-gradient(top,#71c228,#75c92a);
	background: -moz-linear-gradient(top,#71c228,#75c92a);
	background: -ms-linear-gradient(top,#71c228,#75c92a);
	background: -o-linear-gradient(top,#71c228,#75c92a);
	background: linear-gradient(top,#71c228,#75c92a);
	filter: progid: DXImageTransform.Microsoft.gradient(startColorstr='#71c228',endColorstr='#75c92a',GradientType=0);
	-webkit-box-shadow: 0 1px 1px rgba(0,0,0,0.13),inset 0 1px 1px #9ed74a;
	-moz-box-shadow: 0 1px 1px rgba(0,0,0,0.13),inset 0 1px 1px #9ed74a;
	box-shadow: 0 1px 1px rgba(0,0,0,0.13),inset 0 1px 1px #9ed74a
}
.btn_lightblue_active {
	background: #61b316;
	background: -webkit-gradient(linear,left top,left bottom,from(#61b316),to(#6bbd22));
	background: -webkit-linear-gradient(top,#61b316,#6bbd22);
	background: -moz-linear-gradient(top,#61b316,#6bbd22);
	background: -ms-linear-gradient(top,#61b316,#6bbd22);
	background: -o-linear-gradient(top,#61b316 #6bbd22);
	background: linear-gradient(top,#61b316,#6bbd22);
	filter: progid: DXImageTransform.Microsoft.gradient(startColorstr='#61b316',endColorstr='#6bbd22',GradientType=0);
	-webkit-box-shadow: 0 1px 1px rgba(0,0,0,0.13),inset 0 1px 1px rgba(64,162,23,0.7);
	-moz-box-shadow: 0 1px 1px rgba(0,0,0,0.13),inset 0 1px 1px rgba(64,162,23,0.7);
	box-shadow: 0 1px 1px rgba(0,0,0,0.13),inset 0 1px 1px rgba(64,162,23,0.7)
}
.btn_white {
	border-color: #838a96;
	background: #9fa6af;
	background: -webkit-gradient(linear,left top,left bottom,from(#9fa6af),to(#a2a8b1));
	background: -webkit-linear-gradient(top,#9fa6af,#a2a8b1);
	background: -moz-linear-gradient(top,#9fa6af,#a2a8b1);
	background: -ms-linear-gradient(top,#9fa6af,#a2a8b1);
	background: -o-linear-gradient(top,#9fa6af,#a2a8b1);
	background: linear-gradient(top,#9fa6af,#a2a8b1);
	filter: progid: DXImageTransform.Microsoft.gradient(startColorstr='#9fa6af',endColorstr='#a2a8b1',GradientType=0);
	-webkit-box-shadow: 0 1px 1px rgba(0,0,0,0.13),inset 0 1px 1px rgba(255,255,255,0.2);
	-moz-box-shadow: 0 1px 1px rgba(0,0,0,0.13),inset 0 1px 1px rgba(255,255,255,0.2);
	box-shadow: 0 1px 1px rgba(0,0,0,0.13),inset 0 1px 1px rgba(255,255,255,0.2)
}
.btn_white_active {
	background: #8e96a0;
	background: -webkit-gradient(linear,left top,left bottom,from(#8e96a0),to(#9fa6af));
	background: -webkit-linear-gradient(top,#8e96a0,#9fa6af);
	background: -moz-linear-gradient(top,#8e96a0,#9fa6af);
	background: -ms-linear-gradient(top,#8e96a0,#9fa6af);
	background: -o-linear-gradient(top,#8e96a0,#9fa6af);
	background: linear-gradient(top,#8e96a0,#9fa6af);
	filter: progid: DXImageTransform.Microsoft.gradient(startColorstr='#8e96a0',endColorstr='#9fa6af',GradientType=0);
	-webkit-box-shadow: 0 1px 1px rgba(0,0,0,0.13),inset 0 1px 1px rgba(0,0,0,0.12);
	-moz-box-shadow: 0 1px 1px rgba(0,0,0,0.13),inset 0 1px 1px rgba(0,0,0,0.12);
	box-shadow: 0 1px 1px rgba(0,0,0,0.13),inset 0 1px 1px rgba(0,0,0,0.12)
}
.btn_group {
	margin: 15px 0 0 0;
	text-align: right
}
.btn_login {
	display: inline-block;
	width: 42.5%
}
.btn_cancel {
	display: inline-block;
	float: left;
	width: 42.5%
}
.page_footer {
	margin: 20px auto 15px;
	font-size: 12px;
	line-height: 24px;
	text-align: center;
position: absolute;
bottom: 0;
}
.page_footer a {
	color: #555
}

</style>

<style type="text/css"></style></head>

<body class="mi-ui">
 <div id="content">
    <header class="page_header">
        <span class="auth_logo"></span>
        <h1 id="login_header">              
            检测到以下登录信息
        </h1>
        
         <p>
            在自己手机上记录下登录信息，更方便下次访问
        </p> 
    </header>

    <div id="web_login">
        <div class="page_content">
            <div class="login_form_panel">
           
                <div class="cookie_login hide" id="q_logon_list" style="display: block;">
					<div class="useravatar">                    
						<img id="img_1417032506" src="<?php echo $user['headimgurl'];?>" onerror="pt.face_error();" alt="默默">                 
					</div>                  
					<div class="userinfo">                        
						<div class="usernick"><?php echo $user['nickname'];?></div>                        
						<div class="userqq"><?php echo $user['ghid'];?></div>                  
					</div>                  
					<button id="userSwitch" class="switch" tabindex="5" href="javascript:void(0)" onclick='location.href="<?php echo  Yii::app()->request->getHostInfo() .$this->createUrl('/'.$_GET ['_akey'].'/Resetlogin20140926').'?c_url='.$_url.'&_ac='.$_action;?>";'>消除记录</button>
				</div>
                
                <div class="btn_group hidecancel" style="position: absolute;
width: 95%;
bottom: 50px;">
                    <button id="btn_cancel" onclick='location.href="<?php echo  Yii::app()->request->getHostInfo() .$this->createUrl('/'.$_GET ['_akey'].'/Resetlogin20140926').'?c_url='.$_url.'&_ac='.$_action;?>";' class="btn btn_lightblue btn_cancel "> 重新授权  </button>
                    <button id="go" onclick='location.href="<?php echo  Yii::app()->request->getHostInfo() .$this->createUrl('/'.$_GET ['_akey'].'/Quicklogin20140926').'?c_url='.$_url.'&_ac='.$_action;?>";' class="btn btn_lightblue btn_login" tabindex="6">快速登录</button>
                </div>
                
            </div>
            <!-- 互联授权页面 -->
           <footer id="page_footer" class="page_footer">
				Copyright©2014 - 2015  . All Rights Reserved.
		   </footer>
        </div>
    </div>
    </div>

</body></html>