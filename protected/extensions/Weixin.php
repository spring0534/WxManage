<?php
define('WEIXIN_ROOT', 'https://mp.weixin.qq.com');

class Weixin{
	private $http;
	public function __construct(){
		yii::import('ext.Http');
		$this->http=new Http();
	}
	/**
	 * 账号登录
	 */
	function account_weixin_login($username='', $password='', $imgcode=''){
		$token=Yii::app()->cache->get('wxauth:'.$username.':token');
		$cookie=Yii::app()->cache->get('wxauth:'.$username.':cookie');
		if (!empty($token)&&!$cookie){
			$response=$this->http->http_request(WEIXIN_ROOT.'/home?t=home/index&lang=zh_CN&token='.$token, '', array(
				'CURLOPT_REFERER'=>'https://mp.weixin.qq.com/', 
				'CURLOPT_COOKIE'=>$cookie
			));
			if (!($response)){
				return false;
			}
			if (strexists($response['content'], '登录超时')){
				Yii::app()->cache->delete('wxauth:'.$username.':token');
				Yii::app()->cache->delete('wxauth:'.$username.':cookie');
			}
			return true;
		}
		$loginurl=WEIXIN_ROOT.'/cgi-bin/login?lang=zh_CN';
		$post=array(
			'username'=>$username, 
			'pwd'=>$password, 
			'imgcode'=>$imgcode, 
			'f'=>'json'
		);
		$response=$this->http->http_request($loginurl, $post, array(
			'CURLOPT_REFERER'=>'https://mp.weixin.qq.com/'
		));
		if (!$response){
			return false;
		}
		$data=json_decode($response['content'], true);
		if ($data['base_resp']['ret']==0){
			preg_match('/token=([0-9]+)/', $data['redirect_url'], $match);
			Yii::app()->cache->set('wxauth:'.$username.':token', $match[1], 5000);
			Yii::app()->cache->set('wxauth:'.$username.':cookie', implode('; ', $response['headers']['Set-Cookie']), 5000);
		}else{
			return  array(
				'code'=>$data['base_resp']['ret'], 
				'err_msg'=>$data['base_resp']['err_msg']
			);
			
		}
		
		return true;
	}
	/**
	 * 获取微息账号基本信息
	 * Enter description here .
	 * ..
	 * @param unknown_type $username 
	 */
	function account_weixin_basic($username){
		$response=$this->account_weixin_http($username, WEIXIN_ROOT.'/cgi-bin/settingpage?t=setting/index&action=index&lang=zh_CN');
		if (!$response){
			return array();
		}
		$info=array();
		preg_match('/fakeid=([0-9]+)/', $response['content'], $match);
		$fakeid=$match[1];
		$image=$this->account_weixin_http($username, WEIXIN_ROOT.'/misc/getheadimg?fakeid='.$fakeid);
		if (!empty($image['content'])){
			$info['headimg']=$image['content'];
		}
		$image=$this->account_weixin_http($username, WEIXIN_ROOT.'/misc/getqrcode?fakeid='.$fakeid.'&style=1&action=download');
		if (!empty($image['content'])){
			$info['qrcode']=$image['content'];
		}
		preg_match('/(gh_[a-z0-9A-Z]+)/', $response['meta'], $match);
		$info['original']=$match[1];
		preg_match('/名称([\s\S]+?)<\/li>/', $response['content'], $match);
		$info['name']=trim(strip_tags($match[1]));
		preg_match('/微信号([\s\S]+?)<\/li>/', $response['content'], $match);
		$info['account']=trim(strip_tags($match[1]));
		preg_match('/类型([\s\S]+?)<\/li>/', $response['content'], $match);
		$info['accounttype']=trim(strip_tags($match[1]));
		preg_match('/功能介绍([\s\S]+?)meta_content\">([\s\S]+?)<\/li>/', $response['content'], $match);
		$info['signature']=trim(strip_tags($match[2]));
		
		preg_match('/所在地址([\s\S]+?)meta_content\">([\s\S]+?)<\/li>/', $response['content'], $match);
		$info['addr']=trim(strip_tags($match[2]));
		preg_match('/登录邮箱([\s\S]+?)meta_content\">([\s\S]+?)<\/li>/', $response['content'], $match);
		$info['email']=trim(strip_tags($match[2]));
		preg_match('/认证情况([\s\S]+?)meta_content\">([\s\S]+?)<\/li>/', $response['content'], $match);
		$info['isauth']=trim(strip_tags($match[2]));
		if (strexists($response['content'], '服务号')||strexists($response['content'], '微信认证')){
			$authcontent=$this->account_weixin_http($username, WEIXIN_ROOT.'/advanced/advanced?action=dev&t=advanced/dev&lang=zh_CN');
			preg_match_all("/value\:\"(.*?)\"/", $authcontent['content'], $match);
			$info['key']=$match[1][2];
			$info['secret']=$match[1][3];
			unset($match);
		}
		return $info;
	}
	/**
	 * 服务器接口配置
	 */
	function account_weixin_interface($username, $url='', $token=''){
		$response=$this->account_weixin_http($username, WEIXIN_ROOT.'/advanced/callbackprofile?t=ajax-response&lang=zh_CN', array(
			'url'=>$url, 
			'callback_token'=>$token
		));
		if (empty($response)){
			return false;
		}
		$response=json_decode($response['content'], true);
		
		if (!empty($response['ret'])){
			return array(
				$response['ret'], 
				$response['msg']
			);
		}
		return true;
	}
	/**
	 * 获取微信账号的相关已登录状态页面信息
	 */
	function account_weixin_http($username, $url, $post=''){
		$token=Yii::app()->cache->get('wxauth:'.$username.':token');
		$cookie=Yii::app()->cache->get('wxauth:'.$username.':cookie');
		return $this->http->http_request($url.'&token='.$token, $post, array(
			'CURLOPT_COOKIE'=>$cookie, 
			'CURLOPT_REFERER'=>WEIXIN_ROOT.'/advanced/advanced?action=edit&t=advanced/edit&token='.$token
		));
	}
}