<?php
// 在开始加载
Yii::app()->clientScript->coreScriptPosition=CClientScript::POS_BEGIN;
// 这些不加载
Yii::app()->clientScript->scriptMap=array (
	'jquery.js'=>false,
	'jquery.min.js'=>false
);
// 在底部加载自定义JS
// Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/test.js",CClientScript::POS_END);
?>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- Favicons -->
<style type="text/css">
html {
	_overflow-y: scroll;
	overflow-x: hidden;
}
</style>
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $this->assets(); ?>/images/icons/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $this->assets(); ?>/images/icons/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $this->assets(); ?>/images/icons/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="<?php echo $this->assets(); ?>/images/icons/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="<?php echo $this->assets(); ?>/images/icons/favicon.png">
<!--[if lt IE 9]>
          <script src="<?php echo $this->assets(); ?>/js/minified/core/html5shiv.min.js"></script>
          <script src="<?php echo $this->assets(); ?>/js/minified/core/respond.min.js"></script>
 <![endif]-->
<!-- Fides Admin CSS Core -->
<link rel="stylesheet" type="text/css" href="<?php echo $this->assets(); ?>/css/minified/aui-production.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->assets(); ?>/js/skins/default.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->assets(); ?>/js/audioplayer/audio.css" />
<!-- Theme UI -->
<link id="layout-theme" rel="stylesheet" type="text/css" href="<?php echo $this->assets(); ?>/themes/minified/fides/color-schemes/dark-blue.min.css">
<!-- Fides Admin Responsive -->
<link rel="stylesheet" type="text/css" href="<?php echo $this->assets(); ?>/themes/minified/fides/common.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->assets(); ?>/themes/minified/fides/responsive.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->assets(); ?>/css/bootstrap_switch.css">
<!-- Fides Admin JS -->
<script type="text/javascript">
		var WEBURL=webroot='<?php echo WEB_URL;?>',
			statics='<?php echo __PUBLIC__;?>';
		</script>
<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/minified/aui-production.js?k=2"></script>
<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/wind.js?k=1"></script>
<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/common.js?k=20150511"></script>
<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/laydate/laydate.js?v=11"></script>
</head>
<body style="">
	<div id="page-wrapper" class="demo-example">
		<div id="page-content-wrapper" style="margin-left: 0px">
                <?php echo $content; ?>
        </div>
		<!-- #page-main -->
	</div>
	<!-- #page-wrapper -->
</body>
</html>
