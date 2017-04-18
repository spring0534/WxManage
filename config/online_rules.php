<?php   
// $urls=array('wxapp.a-liai.com','wxapp2.a-liai.com');
$urls=array('h5.donglaishuma.com');
$pre_rules=array(
				'gii'=>'gii',
            	'gii/<controller:\w+>'=>'gii/<controller>',
            	'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
				'stat'=>'stat',
				'stat/<controller:\w+>'=>'stat/<controller>',
				'stat/<controller:\w+>/<action:\w+>'=>'stat/<controller>/<action>',
				'http://'.API_URL.'<_q:.\w+>/<_token:\w+>*'=>'interface/<_q>/index/_token/<_token>',
				'http://'.OAUTH_URL.'<_q:.*>/*'=>'oauth/<_q>',
				//微信支付绑定路由
				'http://'.WXPAY_URL.'/<module:wxpay|wxpaytest>/<controller:\w+>/<action:\w+>*'=>'<module>/<controller>/<action>',
				
);
$end_urles=array(
				//app debug--------
				'http://'.DEBUG_URL.'/' => 'apps/app/entry',
				'http://'.DEBUG_URL.'/<_akey:\w+>/<_controller:\w+>/<_action:\w+>*' => 'apps/app/entry',
				'http://'.DEBUG_URL.'/<_akey:\w+>/<_controller:\w+>' => 'apps/app/entry',
				'http://'.DEBUG_URL.'/<_akey:\w+>' => 'apps/app/entry',
				'http://'.DEBUG_URL.'/<controller:\w+>/<action:\w+>'=>'apps/app/entry/<controller>/<action>',
				'/'=>'/default/',
				//app admin--------
				'appAdmin/<_akey:\w+>/<_controller:\w+>/<_action:\w+>*' => 'apps/appManage/entry',
				'appAdmin/<_akey:\w+>/<_controller:\w+>' => 'apps/appManage/entry',
				'appAdmin/<_akey:\w+>' => 'apps/appManage/entry/_controller/default',
				//sys----
				'<controller:\w+>'=>'/<controller>',
				'<controller:\w+>/<action:\w+>*'=>'/<controller>/<action>',
);
foreach($urls as $k=>$v){
				//scr--------
				$addurl['http://'.$v.'/<_akey:\w+>/<_m:scr>/<_controller:\w+>/<_action:\w+>*']='apps/appScr/entry';
				$addurl['http://'.$v.'/<_akey:\w+>/<_m:scr>/<_controller:\w+>']='apps/appScr/entry';
				$addurl['http://'.$v.'/<_akey:\w+>/<_m:scr>/']='apps/appScr/entry/_controller/default';
				//interface--------
				$addurl['http://'.$v.'/<_akey:\w+>/<_m:interface>/<_controller:\w+>/<_action:\w+>*']='apps/appInterface/entry';
				$addurl['http://'.$v.'/<_akey:\w+>/<_m:interface>/<_controller:\w+>']='apps/appInterface/entry';
				$addurl['http://'.$v.'/<_akey:\w+>/<_m:interface>/']='apps/appInterface/entry/_controller/default';
				//app--------
				$addurl['http://'.$v.'/']= 'apps/app/entry';
				$addurl['http://'.$v.'/<_akey:\w+>/<_controller:\w+>/<_action:\w+>*']= 'apps/app/entry';
				$addurl['http://'.$v.'/<_akey:\w+>/<_controller:\w+>']= 'apps/app/entry';
				$addurl['http://'.$v.'/<_akey:\w+>' ]= 'apps/app/entry';
				$addurl['http://'.$v.'/<controller:\w+>/<action:\w+>']='apps/app/entry/<controller>/<action>';
}
return array_merge($pre_rules,$addurl,$end_urles);