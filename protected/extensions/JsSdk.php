<?php

/**
* JsSdk.php
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2015-1-22
* 
*/
class JsSdk{
	public function getSignPackage($ghid){
		$protocol=(!empty($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=='off'||$_SERVER['SERVER_PORT']==443) ? "https://" : "http://";
		$url="$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$nonceStr=$this->createNonceStr();
		$row=Yii::app()->db->createCommand()
			->select('appid,appsecret,access_token,at_expires,jsapi_ticket,jsapi_ticket_expire,jsapi,dev_oauth_ghid,dev_jsapi_ghid')
			->from('sys_user_gh')
			->where('ghid=:ghid', array(
			'ghid'=>$ghid
		))
			->queryRow();
		if ($row){	
			if(!empty($row['dev_jsapi_ghid'])){
				$row=Yii::app()->db->createCommand()
				->select('appid,appsecret,access_token,at_expires,jsapi_ticket,jsapi_ticket_expire,jsapi')
				->from('sys_user_gh')
				->where('ghid=:ghid', array(
					'ghid'=>$row['dev_jsapi_ghid']
				))
				->queryRow();
			}else{
				switch ($row['jsapi']){
					// 微营
					case 1:
						$row=Yii::app()->db->createCommand()
							->select('appid,appsecret,access_token,at_expires,jsapi_ticket,jsapi_ticket_expire,jsapi')
							->from('sys_user_gh')
							->where('ghid=:ghid', array(
							'ghid'=>'gh_30d2a3e4dc51'
						))
							->queryRow();
						break;
					// 微营2
					case 2:
						$row=Yii::app()->db->createCommand()
							->select('appid,appsecret,access_token,at_expires,jsapi_ticket,jsapi_ticket_expire,jsapi')
							->from('sys_user_gh')
							->where('ghid=:ghid', array(
							'ghid'=>'gh_1d413409fdfb'
						))
							->queryRow();
						break;
						// 采用自己的分享或者腾讯微购物分享都是采用自己的配置
				}
			}
			$account=array(
				'AppId'=>$row['appid'], 
				'AppSecret'=>$row['appsecret'], 
				'access_token'=>$row['access_token'], 
				'expire'=>$row['at_expires'], 
				'jsapi_ticket'=>$row['jsapi_ticket'], 
				'jsapi_ticket_expire'=>$row['jsapi_ticket_expire'], 
				'jsapi'=>$row['jsapi']
			);
			if (empty ( $account ['AppId'] ) || empty ( $account ['AppSecret'] )) {
				header("Content-type: text/html; charset=utf-8");
				exit ( '请填写公众号的appid及appsecret！' );
			}
			yii::import('ext.Weixinapi');
			$t=new Weixinapi($account);
			$t->debug=true;
			$signPackage=$t->getSignPackage($url, $nonceStr);
			return $signPackage;
		}
	}
	private function createNonceStr($length=16){
		$chars="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str="";
		for ($i=0; $i<$length; $i++){
			$str.=substr($chars, mt_rand(0, strlen($chars)-1), 1);
		}
		return $str;
	}
}

