<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="Cache-Control" content="no-cache">
		<meta equiv="Expires" content="0">
		<title>深圳市科技有限公司</title>
		<script type="text/javascript">
            var phoneWidth = parseInt(window.screen.width),
            	phoneScale = phoneWidth / 640,
            	ua = navigator.userAgent;
        	if (/Android (\d+\.\d+)/.test(ua)) {
                var version = parseFloat(RegExp.$1);
                // andriod 2.3
                if (version > 2.3) {
                    document.write('<meta name="viewport" content="width=640, minimum-scale = ' + phoneScale + ', maximum-scale = ' + phoneScale + ', target-densitydpi=device-dpi">');
                    // andriod 2.3以上
                } else {
                    document.write('<meta name="viewport" content="width=640, target-densitydpi=device-dpi">');
                }
                // 其他系统
            } else {
                document.write('<meta name="viewport" content="width=640, user-scalable=no, target-densitydpi=device-dpi">');
            }

        </script>
		<link rel="stylesheet" type="text/css" href="<?php echo WEB_URL ?>/css/index.css">
	</head>
	<body style="background:url('<?php echo WEB_URL ?>/images/bg.jpg') no-repeat ; background-size: 100% 100%; height: 100%; width: 100%;  ">
    <img src="<?php echo WEB_URL ?>/images/ewm.png" style=" display: block; height: 100%; width: 100%;">
    <a class="phone" href="tel:4008161677"><img src='<?php echo WEB_URL ?>/images/phone.png' style="outline: none;"></a>
	</body>
</html>