<?php
if(strpos($_SERVER['REMOTE_ADDR'],'127.0.0')!==FALSE||strpos($_SERVER['REMOTE_ADDR'],'192.168')!==FALSE||strpos($_SERVER['REMOTE_ADDR'],'::1')!==FALSE) {
	define ( 'DEBUG_URL',$_SERVER['REMOTE_ADDR'] );
}else{
	define ( 'DEBUG_URL',"debugapp.wxos.com" );
}
define ( 'API_URL',"wxapi.wxos.com" );
define ( 'OAUTH_URL',"wxoauth.wxos.com" );
define ( 'OAUTH_URL_CORE','http://'.OAUTH_URL.'/authorize' );