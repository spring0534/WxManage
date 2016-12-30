<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="keywords" content="微信  微应用  广告   O2O ">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=yes">
<title>请使用微信扫描二维码打开</title>
<link rel="stylesheet" type="text/css" href="<?php echo WEB_URL ?>/css/pc.css">
<head>
<body>
	<div id="login_container" >
		<div class="main">
			<div class="loginPanel normalPanel">
				<div class="logoTitle title pngBackground">
					<h1>互动微应用</h1>
				</div>
				<div class="waiting panelContent">
					<div class="qrcodeContent" style="position: relative;">
						<div class="qrcodePanel">
							<img class="qrcode lightBorder" id="loginQrCode" style="width: 255px" src="http://qr.liantu.com/api.php?text=<?php echo WEB_URL.'/'.$akey;?>">
						</div>
					</div>
					<div class="info">
						<div class="normlDesc loginTip pngBackground" style="position: relative;">
							<p>请使用微信扫描二维码打开</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="footer">

	</div>
<?php echo $statjs;?>
</body>
</html>