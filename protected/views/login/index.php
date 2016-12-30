<!DOCTYPE html>
<html lang="zh_CN" class="chrome42 filereader csstransforms cssgradients formdata">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>微营OS - 最懂你的微信营销</title>
<script type="text/javascript">if(top!=self)if(self!=top) top.location=self.location;</script>
<meta http-equiv="X-Frame-Options" content="DENY">
<script type="text/javascript">var WEB_URL='<?php echo WEB_URL;?>',statics='<?php echo __PUBLIC__;?>';</script>
<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/minified/aui-production.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->assets(); ?>/css/login.css">
<script>
    window.VERSION_CONTROL = window.MicloudVersion;
  </script>
<!--[if lte IE 9]>
    <script>
      $SUPPORT_CSS3 = false;
    </script>
  <![endif]-->
<!--[if lt IE 9]>
    <script>
      $SUPPORT_JQUERY2 = false;
    </script>
  <![endif]-->
<!--[if lt IE 8]>
    <script>
      IS_SUPPORT = false;
      OLD = true;
    </script>
  <![endif]-->
<style type="text/css"></style>
</head>
<body lang="zh_CN" ip_locale="zh_CN" langloaded="micloud--lang--zh_cn">
	<div id="item-error">
		<span></span>
	</div>
	<div class="login_up-browser-layout" id="oldie" style="display: none">
		<div class="login_up-browser-content">
			<div class="login_up-browser-bg"></div>
			<div class="login_up-browser-container">
				<h1>请升级您的浏览器</h1>
				<p class="up-browser-intro">您当前使用的浏览器太过陈旧（或者设置不正确，比如IE开启了兼容模式）!</p>
				<p class="login_up-browser-title">继续使用小米服务, 请升级浏览器吧！您可以选择</p>
				<div class="login_up-browser-check">
					<ul>
						<li class="login_up-browser-ch">
							<a href="http://www.google.cn/intl/zh-CN/chrome/browser/" target="_blank">Google Chrome</a>
						</li>
						<li class="login_up-browser-ff">
							<a href="http://www.firefox.com.cn/download/" target="_blank">Firefox</a>
						</li>
						<li class="login_up-browser-ie">
							<a href="http://windows.microsoft.com/zh-CN/internet-explorer/downloads/ie" target="_blank">IE 8+</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="login-container animated fadeInDown" style="" id="micloud_login_frame">
		<div class="layout-login">
			<div class="ll-book-container">
				<div class="ll-book-top">
					<div class="ll-logo-area">
						<p class="logo-con">
							<span class="m-logo-con">微营OS</span>
						</p>
						<p class="logo-intro" id="logo-intro">欢迎使用微营OS,请先登录</p>

						<br>
						<br>
						<form id="login-form">
							<div class="login a-bounceinB" style="text-align: center; line-height: 50px;">
								<ul class="line">
									<li class="u"></li>
									<li >
										<input class="txt" placeholder=请输入账号 name="SysUser[username]" id="username" type="text" value="" maxlength="20" >
									</li>
								</ul>
								<ul class="">
									<li class="p"></li>
									<li >
										<input class="txt" placeholder=请输入密码 name="SysUser[password]" id="password" type="password" value="" maxlength="20" >
									</li>
								</ul>
								<div class="checker" id="uniform-remember-password" style="display: none;">
									<span class="ui-state-default btn radius-all-4">
										<input class="custom-checkbox" id="remember-password" style="display: none" type="checkbox" value="1" name="rememberMe">
										<i class="glyph-icon icon-ok"></i>
									</span>
								</div>
							</div>
							<div class="form-container" style="height: 280px" id="form_container">
								<div class="loginButton btn">
									<a id="loginLink" href="javascript:;">登录</a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="copy-rights">
			<span id="rights_reserved">Copyright © 2015 - wxos</span>
		</div>
	</div>
	<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/login.js"></script>
</body>
</html>