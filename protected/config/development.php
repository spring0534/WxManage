<?php
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'微营OS',
	'preload'=>array('log'),
	'language'=>'zh_cn',
	'import'=>array(
		'application.widgets.*',
		'application.models.*',
		'application.components.*',
	),
	'modules'=>array(
		'admin'=>array(
			//'class'=>'AdminModule'
		),
		'oauth'=>array(
			//'class'=>'AdminModule'
		),
		'interface'=>array(
			//'class'=>'AdminModule'
		),

		'apps'=>array(
			//'class'=>'AdminModule'
		),
	    'sites'=>array(
	        //
	    ),
		'stat'=>array(),
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'admin',
		 	'connectionID' => 'db',
			// 可登录的IP
			'ipFilters'=>array('127.0.0.1'),

		),
	),

	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'smarty'=>array(
			'class'=>'ext.CSmarty',
		),
		// 文件缓存
		/*'cache' => array (
			'class' =>'system.caching.CFileCache'
		),*/
		//memcache内存缓存
		'cache'=>array(
                'class'=>'system.caching.CMemCache',
                'servers'=>array(
                    array('host'=>'127.0.0.1', 'port'=>11211),
                ),
         ),
		'session' => array (
            'sessionName' => 'PHPSESSID',
            'class'=> 'CCacheHttpSession',
			'timeout'=>600*10
           // 'cacheID' => 'mcache',
            //'autoStart' => true,
            //'cookieMode' => 'only',

        ),
		'urlManager'=>array(
			'urlFormat'=>'path',
			//'urlSuffix' => '.html',
			'showScriptName'=>false,
			'rules'=>require(ROOT_PATH.'/config/locahost_rules.php'),
		),

		//admin
		'db'=>array(
			'connectionString' => 'mysql:host=127.0.0.1;dbname=wxos_admin',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '123456',
			'charset' => 'utf8',
		),
		//app
		'db2'=>array(
			'class'            => 'CDbConnection' ,
			'connectionString' => 'mysql:host=127.0.0.1;dbname=wxos_app',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '123456',
			'charset' => 'utf8',
		),
		'errorHandler'=>array(
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				array(
					'class'=>'WDbLogRoute',
					'autoCreateLogTable'=>true,
					'categories'=>'system.*,exception.CDbException,exception.CException,php',
					'connectionID'=>'db',
					'logTableName'=>'sys_log',
					'levels'=>'error, warning',
				),
				/*array(
                    'class'=>'CDbLogRoute',
                    'autoCreateLogTable'=>true,
                    'categories'=>'system.*,exception.CDbException,exception.CException,php',
					'connectionID'=>'db2',
					'logTableName'=>'app_system_log',
					'levels'=>'error, warning',
                ),*/
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),
	//使用方法 Yii::app()->params['paramName']
	'params'=>array(
		'adminEmail'=>'webmaster@example.com',
		'title'=> '微营OS后台管理',
		'homeUrl'=>'http://wx.wxos.com',
		'preImageUrl'=>'http://res.wxos.com',
		'appUrl'=>'http://h5.wxos.com/',
		'wxapiBaseUrl'=>'http://api.wxos.com/wxapi/',
		'defautlUploadImg'=>__PUBLIC__ . '/images/icon/ooopic.png',
	    'ghtype'=>array(0=>'普通订阅号',1=>'认证订阅号',2=>'普通服务号',3=>'认证服务号'),
	    'oauthtype'=>array(1=>'微营',2=>'微营2',100=>'自己'),
		'msg_type'=>array('text'=>'文本','news_1'=>'单图文','news_n'=>'多图文','image'=>'图片'),
		'sys_keyword'=>array('门户','微站'),
		'plugin_cate'=>array('1'=>'现场','2'=>'社交','3'=>'抽奖','4'=>'促销','5'=>'游戏','6'=>'应用')
	),
);