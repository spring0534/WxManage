<?php
ini_set('date.timezone','Asia/Shanghai');
$envirment = (strpos($_SERVER['REMOTE_ADDR'],'127.0.0')!==FALSE||strpos($_SERVER['REMOTE_ADDR'],'192.168')!==FALSE||strpos($_SERVER['REMOTE_ADDR'],'::1')!==FALSE) ? 10 : 20;
define ('ENV_VAL', $envirment); //10-本地环境  20-生产环境
//define ('ENV_VAL', 20); //10-本地环境  20-生产环境
if(ENV_VAL == 10) {
	require_once(dirname(__FILE__).'/config/url_locahost.php');
}else{
	require_once(dirname(__FILE__).'/config/url.php');
}
define ( 'ROOT_PATH', dirname ( __FILE__ ));
define ( 'WEB_ROOT',"" );//网站文件夹 如无则为空不用加/扛
define ( 'WEB_HOST',"http://".$_SERVER ['HTTP_HOST'] );//网址
define ( 'WEB_URL', WEB_HOST.WEB_ROOT );//
define ( "__PUBLIC__", WEB_URL . "/public/static" );
define ( 'UPLOAD_PATH',ROOT_PATH."/public/uploads" );
define ( 'QR_PATH',ROOT_PATH."/public/qrcode/" );
error_reporting(E_ALL^E_NOTICE);
$yii=dirname(__FILE__).'/core/yii.php';
if(ENV_VAL == 10) {
	ini_set("display_errors", "On");
	defined('YII_DEBUG') or define('YII_DEBUG',true);
	defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
	$config=dirname(__FILE__).'/protected/config/development.php';
}else{
	$config=dirname(__FILE__).'/protected/config/main.php';
}
define('IN_WEB', true);   //定义web内访问标志
require_once  ROOT_PATH .'/pgapi/common.php';
require_once($yii);
require_once(dirname(__FILE__).'/protected/config/common.php');
Yii::createWebApplication($config)->run();