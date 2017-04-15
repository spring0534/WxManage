<?php
/**
 * apiController.php 微信oauth授权
 * ----------------------------------------------
 * 版权所有 2014-2015
 * ----------------------------------------------
 * @date: 2014-8-27
 *
 */
header ( "Content-type: text/html; charset=utf-8" );
class AuthorizeController extends Controller {
	private $ghidlist = array ();
	function actionIndex() {
		if (empty ( $_GET ['ghid'] )) {
			echo json_encode ( array ('errcode' => 001, 'errmsg' => 'invalid  ghid' ) );
			exit ();
		}
		//从公众号表中读取类型
		$ghiduser = Yii::app ()->db->createCommand ()->select ( 'appid,appsecret,oauth,dev_oauth_ghid,dev_jsapi_ghid' )->from ( 'sys_user_gh' )->where ( 'ghid=:ghid', array ('ghid' => $_GET ['ghid'] ) )->queryRow ();
		if (! $ghiduser) {
			echo json_encode ( array ('errcode' => 9, 'errmsg' => 'invalid  ghid, the datatable no this ghid ' ) );
			exit ();
		} else {
			switch ($ghiduser ['oauth']) {
				case 1 : //微营
					$this->ghidlist [$_GET ['ghid']] = array ('appid' => 'wx71ca9fa930c44076', 'secret' => '80770632518b150ff2be92734be3f318' );
					break;
				case 2 : //微营2
					$this->ghidlist [$_GET ['ghid']] = array ('appid' => 'wx71ca9fa930c44076', 'secret' => '80770632518b150ff2be92734be3f318' );
					break;
				case 3 : //微购物百货外部商家，回调域名指向cgi.trade.qq.com
					if (empty ( $ghiduser ['appid'] ) || empty ( $ghiduser ['appsecret'] )) {
						echo json_encode ( array ('errcode' => 010, 'errmsg' => 'invalid  ghid, this ghid no full appid and appsecret' ) );
						exit ();
					}
					$this->ghidlist [$_GET ['ghid']] = array ('appid' => $ghiduser ['appid'], 'secret' => $ghiduser ['appsecret'] );
					break;
				case 4 : //扫货帮，王府井第三方公司授权
					if (empty ( $ghiduser ['appid'] ) ) {
						echo json_encode ( array ('errcode' => 010, 'errmsg' => 'invalid  ghid, this ghid no  appid ' ) );
						exit ();
					}
					$this->ghidlist [$_GET ['ghid']] = array ('appid' => $ghiduser ['appid'] );
					break;
				case 100 : //采用公众号自己的授权，必须填写appid和appsecret，并把oauth的授权回调页面域名改为授权接口
					if (empty ( $ghiduser ['appid'] ) || empty ( $ghiduser ['appsecret'] )) {
						echo json_encode ( array ('errcode' => 010, 'errmsg' => 'invalid  ghid, this ghid no full appid and appsecret' ) );
						exit ();
					}
					$this->ghidlist [$_GET ['ghid']] = array ('appid' => $ghiduser ['appid'], 'secret' => $ghiduser ['appsecret'] );
					break;
			}

		}
		if (empty ( $this->ghidlist [$_GET ['ghid']] )) {
			echo json_encode ( array ('errcode' => '002', 'errmsg' => 'invalid  ghid' ) );
			exit ();
		}
		if (empty ( $_GET ['returnUrl'] )) {
			echo json_encode ( array ('errcode' => '003', 'errmsg' => 'invalid  returnUrl' ) );
			exit ();
		}
		$scope = intval ( $_GET ['scope'] ); //不传则为0，snsapi_base授权，1 snsapi_userinfo
		$reurl = Yii::app ()->request->hostInfo . $this->createUrl ( '/authorize/redirect' );
		$state =  md5 ( time () . mt_rand(1,1000000) );
		Yii::app()->cache['reload_'.$state]=Yii::app ()->request->hostInfo . $this->createUrl ( '/authorize' ).'?'.http_build_query($_GET);
		Yii::app ()->cache [$state] = array ('ghid' => $_GET ['ghid'], 'key' => $this->ghidlist [$_GET ['ghid']], 'scope' => $_GET ['scope'], 'returnUrl' => urldecode ( $_GET ['returnUrl'] ),'oauth'=>$ghiduser ['oauth'] );
		//王府井第三方公司授权
		if($ghiduser ['oauth']==4){
			header ( 'Location: http://www.ishop-city.net/mobile/weixin/interface/authorize.php?appid=' . $ghiduser ['appid'] . '&authpage=1&redirect_uri=' . urlencode ( $reurl . '?state=' . $state )  );exit();

		}
		yii::import ( 'ext.WeixinOauth' );
		$weiOauth = new WeixinOauth ( $this->ghidlist [$_GET ['ghid']] ['appid'], $this->ghidlist [$_GET ['ghid']] ['secret'], $scope );
		if($ghiduser ['oauth']==3){
			$weiOauth->location2 ( urlencode($reurl . '?state=' . $state) );
		}else{
			$weiOauth->location ( $reurl, $state );
		}

	}
	/**
	 * 微信回调
	 */
	function actionRedirect() {
		/*$rule_url = '/^https:\/\/open\.weixin\.qq\.com(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/';
		$referreUrl=empty($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_ORIGIN']:$_SERVER['HTTP_REFERER'];
		if (preg_match ( $rule_url, $referreUrl ) !== 1) {
			$this->error('该链接无效X001');
		}*/
		$state = $_GET ['state'];
		$temp = Yii::app ()->cache [$state];
		if (! $temp['key'] ||($temp&&Yii::app ()->cache [$_GET ['state'].'isuse']=='no')) {
			if(!empty($_GET['refresh_token'])){
				$check=Yii::app()->cache['sole_token_'.$_GET['refresh_token']];
			}
			if(!empty($_GET['code'])){
				$check=Yii::app()->cache['sole_token_'.$_GET['code']];
			}
			if(!empty($_GET['openid'])){
				$check=Yii::app()->cache['sole_token_'.$_GET['openid']];
			}
			if(empty($check)){
				$data=Yii::app()->cache['sole_token_'.$state];
				if(!empty($data)){
					$temp = $data;
				}else{
					$this->error('该链接无效X002');
				}
			}else{
				//已使用过的code或者refresh_token,直接重新授权处理
				if(Yii::app()->cache['reload_'.$state]){
					header ( 'Location:'.Yii::app()->cache['reload_'.$state]);
					exit;
				}else{
					//log(x003)
					$this->error('该链接无效X003');//已使用过的code或者refresh_token
				}

			}
		}
		$temp ['code'] = $_GET ['code'];
		Yii::app ()->cache [$state] = $temp;
		$ghidinfo = Yii::app ()->cache [$state];
		if($ghidinfo['oauth']==3){
			if(empty($_GET['refresh_token'])){
				//echo json_encode ( array ('errcode' => 004, 'errmsg' => 'invalid  refresh_token' ) );
				$this->oauthError('invalid  refresh_token');
				exit ();
			}
			$ckey=$_GET['refresh_token'];
		}else{
			if (empty ( $_GET ['code'] ) || $_GET ['code'] == 'authdeny') {
				//echo json_encode ( array ('errcode' => 004, 'errmsg' => 'invalid  授权失败' ) );
				$this->oauthError('授权失败');
				exit ();
			}
			$ckey=$_GET['code'];
		}
		if(!empty($ckey)){
			Yii::app()->cache['sole_token_'.$ckey]='record';//记录使用过的code或者refresh_token，在缓存时间存，防止非法的直接访问回调地址路过授权
		}
		Yii::app()->cache['sole_token_'.$state]=array_merge($ghidinfo,array('code'=>$_GET['code'],'refresh_token'=>$_GET['refresh_token']));

		if(empty($_GET['openid'])){
			//处理非扫货帮接口
		yii::import ( 'ext.WeixinOauth' );
		$weiOauth = new WeixinOauth ( $ghidinfo ['key'] ['appid'], $ghidinfo ['key'] ['secret'], $ghidinfo ['scope'] );
		$weiOauth->debug = true;
		if($ghidinfo['oauth']==3){
			$token=$weiOauth->refreshToken($_GET['refresh_token']);
		}else{
			$token = $weiOauth->getToken ( $ghidinfo ['code'] );
		}
		if(empty( $ghidinfo ['scope'])){
			if($token){
				$userinfo=$token;
				$userinfo ['expires_in'] = $userinfo ['expires_in'] + time ();
				$row = WxUserInfo::model ()->findByAttributes ( array ('openid' => $userinfo ['openid'], 'ghid' => $ghidinfo ['ghid'] ) );
				$temp = Yii::app ()->cache [$state];
				$temp ['openid'] = $userinfo ['openid'];
				$temp ['scope'] = $ghidinfo ['scope']==1?'snsapi_userinfo':'snsapi_base';
				Yii::app ()->cache [$state] = $temp;
				$data=array(
					'accessToken'=> $userinfo ['access_token'],
					'refreshToken'=> $userinfo ['refresh_token'],
					'expires'=> date ( 'Y-m-d H:i:s', $userinfo ['expires_in'] ),
					'scope'=> $ghidinfo ['scope']==1?'snsapi_userinfo':'snsapi_base',
					'ua' => $_SERVER ['HTTP_USER_AGENT'],
					'ghid'=> $ghidinfo ['ghid']);
				if($ghidinfo['oauth']==100){
					$data['srcOpenid'] = $userinfo ['openid'];
				}
				if ($row) {
					$data ['utm']=date ( 'Y-m-d H:i:s' );
					$re=Yii::app ()->db->createCommand()->update('sys_wxuser',$data,'openid=:openid and ghid=:ghid',array(':openid'=>$userinfo['openid'],':ghid'=>$ghidinfo['ghid']));
				} else {
					$data['openid'] =$userinfo ['openid'];
					$data['ctm']= date ( 'Y-m-d H:i:s' );
					Yii::app ()->db->createCommand()->insert('sys_wxuser',$data);

				}

			}else{
				$this->oauthError($weiOauth->error);
			}
		}else{
			$userinfo = $weiOauth->getUserinfo ( $token ['access_token'], $token ['openid'] );
			if ($userinfo && $token) {
				$userinfo = array_merge ( $userinfo, $token );
				$userinfo ['expires_in'] = $userinfo ['expires_in'] + time ();
				$row = WxUserInfo::model ()->findByAttributes ( array ('openid' => $userinfo ['openid'], 'ghid' => $ghidinfo ['ghid'] ) );
				$temp = Yii::app ()->cache [$state];
				$temp ['openid'] = $userinfo ['openid'];
				$temp ['scope'] = $userinfo ['scope'];
				Yii::app ()->cache [$state] = $temp;
				$data=array(
					'headimgurl'=> $userinfo ['headimgurl'],
					'nickname'=> $userinfo ['nickname'],
					'city'=> $userinfo ['city'],
					'sex'=> $userinfo ['sex'],
					'province'=> $userinfo ['province'],
					'privilege'=> serialize ( $userinfo ['privilege'] ),
					'accessToken'=> $userinfo ['access_token'],
					'refreshToken'=> $userinfo ['refresh_token'],
					'expires'=> date ( 'Y-m-d H:i:s', $userinfo ['expires_in'] ),
					'scope'=> $ghidinfo ['scope']==1?'snsapi_userinfo':'snsapi_base',
					'ua' => $_SERVER ['HTTP_USER_AGENT'],
					'ghid'=> $ghidinfo ['ghid']);
				if($ghidinfo['oauth']==100){
					$data['srcOpenid'] = $userinfo ['openid'];
				}
				yii::import ( 'ext.Emoji' );
				$Emoji=new Emoji();
				$data ['nickname']=$Emoji->fileterEmoji($data ['nickname']);
				$rule = '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9_]+$/u';//匹配中文字符，数字，字母的正则表达式
				$re ['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
				if ($row) {
					if ($data ['scope'] == 'snsapi_userinfo') {
						if (empty ( $row ['nickname'] ) || empty ( $row ['headimgurl'] )||( $row ['nickname']!=$data ['nickname'])) {
							$data ['utm']=date ( 'Y-m-d H:i:s' );
							try{
								$re=Yii::app ()->db->createCommand()->update('sys_wxuser',$data,'openid=:openid and ghid=:ghid',array(':openid'=>$userinfo['openid'],':ghid'=>$ghidinfo['ghid']));
							}catch(Exception $e){
								$str2='';
								preg_match_all ( $re ['utf-8'], $data ['nickname'], $match );
								foreach ($match[0] as $k=>$v){
									if(preg_match ( $rule, $v )===1){
										$str2.= $v;
									}
								}
								$data ['nickname']=$str2;
								Yii::app ()->db->createCommand()->update('sys_wxuser',$data,'openid=:openid and ghid=:ghid',array(':openid'=>$userinfo['openid'],':ghid'=>$ghidinfo['ghid']));
							}
						}
					}

				} else {
					$data['openid'] =$userinfo ['openid'];
					$data['ctm']= date ( 'Y-m-d H:i:s' );
					try{
						Yii::app ()->db->createCommand()->insert('sys_wxuser',$data);
					}catch(Exception $e){
						$str2='';
						preg_match_all ( $re ['utf-8'], $data ['nickname'], $match );
						foreach ($match[0] as $k=>$v){
							if(preg_match ( $rule, $v )===1){
								$str2.= $v;
							}
						}
						$data ['nickname']=$str2;
						Yii::app ()->db->createCommand()->insert('sys_wxuser',$data);
					}
				}

			} else {
				//echo $weiOauth->error;
				$this->oauthError($weiOauth->error);
				//exit ();
			}
		}
		}else{
			//处理扫货帮接口

			$row = Wx::model ()->findByAttributes ( array ('openid' => $_GET ['openid'], 'ghid' => $ghidinfo ['ghid'] ) );
			$temp = Yii::app ()->cache [$state];
			$temp ['openid'] = $_GET ['openid'];
			Yii::app ()->cache [$state] = $temp;
			//dump($_GET);exit;
			$data=array(
				'headimgurl'=> $_GET ['headimgurl'],
				'nickname'=> $_GET ['nickname'],
				'city'=> $_GET ['city'],
				'sex'=> $_GET ['sex'],
				'province'=> $_GET ['province'],
				'privilege'=>'',
				'subscribe'=>$_GET['subscribed'],
				'scope'=> $ghidinfo ['scope']==1?'snsapi_userinfo':'snsapi_base',
				'ua' => $_SERVER ['HTTP_USER_AGENT']);
			$data['srcOpenid'] = $_GET ['openid'];
			yii::import ( 'ext.Emoji' );
			$Emoji=new Emoji();
			$data ['nickname']=$Emoji->fileterEmoji($data ['nickname']);
			$rule = '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9_]+$/u';//匹配中文字符，数字，字母的正则表达式
			$re ['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
			if ($row) {
				//if ($data ['scope'] == 'snsapi_userinfo') {
				//if (empty ( $row ['nickname'] ) || empty ( $row ['headimgurl'] )||( $row ['nickname']!=$data ['nickname'])) {
				$data ['utm']=date ( 'Y-m-d H:i:s' );
				$data ['subscribe']=$_GET['subscribed'];
				//dump($data);
				try{
					$re=Yii::app ()->db->createCommand()->update('sys_wxuser',$data,'openid=:openid and ghid=:ghid',array(':openid'=>$_GET ['openid'],':ghid'=>$ghidinfo['ghid']));
					//dump($re);exit;
				}catch(Exception $e){
					$str2='';
					preg_match_all ( $re ['utf-8'], $data ['nickname'], $match );
					foreach ($match[0] as $k=>$v){
						if(preg_match ( $rule, $v )===1){
							$str2.= $v;
						}
					}
					$data ['nickname']=$str2;
					Yii::app ()->db->createCommand()->update('sys_wxuser',$data,'openid=:openid and ghid=:ghid',array(':openid'=>$_GET ['openid'],':ghid'=>$ghidinfo['ghid']));
				}
				//}
				//}

			} else {
				$data['ghid'] =$ghidinfo ['ghid'];
				$data['openid'] =$_GET ['openid'];
				$data['ctm']= date ( 'Y-m-d H:i:s' );
				try{
					Yii::app ()->db->createCommand()->insert('sys_wxuser',$data);
				}catch(Exception $e){
					$str2='';
					preg_match_all ( $re ['utf-8'], $data ['nickname'], $match );
					foreach ($match[0] as $k=>$v){
						if(preg_match ( $rule, $v )===1){
							$str2.= $v;
						}
					}
					$data ['nickname']=$str2;
					Yii::app ()->db->createCommand()->insert('sys_wxuser',$data);
				}
			}




		}
		if (strpos ( $temp ['returnUrl'], '?' )) {
			$url = $temp ['returnUrl'] . '&uuauthtoken=' . $_GET ['state'];
		} else {
			$url = $temp ['returnUrl'] . '?uuauthtoken=' . $_GET ['state'];
		}
		/*echo <<<JOT
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport"><meta content="yes" name="apple-mobile-web-app-capable"><meta content="black" name="apple-mobile-web-app-status-bar-style"><meta content="telephone=no" name="format-detection"><title>正在跳转...</title><style type="text/css">body{line-height:1.6;font-family:"Helvetica Neue",Helvetica,"Microsoft YaHei",Arial,Tahoma,sans-serif;}body,h1,h2,h3,h4{margin:0;}a img{border:0;}.icon {    display: inline-block;    vertical-align: middle;}.icon80_smile{    width: 64px;    height: 64px;    display: inline-block;    vertical-align: middle;    background: transparent url() no-repeat 0 0;}.page_msg {    padding-left: 23px;    padding-right: 23px;    font-size: 16px;    text-align: center;}.page_msg .inner {    padding-top: 40px;    padding-bottom: 40px;}.page_msg .msg_icon_wrp {    display: block;    padding-bottom: 22px;}.page_msg .msg_content h4 {    font-weight: 400;    color: #000000;}.page_msg .msg_content p {    color: #90908E;}</style></head><body><div><div class="page_msg"><div class="inner"><span class="msg_icon_wrp"><i class="icon80_smile"></i></span><div class="msg_content"><h4 id="msg">拼命加载中...</h4></div></div></div></div><script>	function goTo(url) {    var a = document.createElement("a");    if(!a.click) {         window.location = url;        return;    }    a.setAttribute("href", url);    a.style.display = "none";    document.body.appendChild(a);    a.click();}goTo('$url');</script></body></html>
JOT;*/
echo <<<JOT
<!DOCTYPE html PUBLIC"-//W3C//DTD HTML 4.01 Transitional//EN""http://www.w3.org/TR/html4/loose.dtd"><html><head><meta http-equiv="Content-Type"content="text/html; charset=UTF-8"/><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;"name="viewport"/><meta content="yes"name="apple-mobile-web-app-capable"/><meta content="black"name="apple-mobile-web-app-status-bar-style"/><meta content="telephone=no"name="format-detection"/><title>正在返回....</title><style>body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,form,fieldset,input,textarea,p,blockquote,th,td{padding:0;margin:0;font:0/0'Microsoft YaHei';}.loding{background:#F5F5F5;;width:100%;height:100%;position:fixed;z-index:999}.spinner{margin:-30px 0 0 -30px;width:60px;height:60px;top:50%;left:50%;position:absolute;text-align:center;-webkit-animation:rotate 2.0s infinite linear;animation:rotate 2.0s infinite linear}.dot1,.dot2{width:60%;height:60%;display:inline-block;position:absolute;top:0;background-color:#15A838;border-radius:100%;-webkit-animation:bounce 2.0s infinite ease-in-out;animation:bounce 2.0s infinite ease-in-out}.dot2{top:auto;bottom:0;-webkit-animation-delay:-1.0s;animation-delay:-1.0s}@-webkit-keyframes rotate{100%{-webkit-transform:rotate(360deg)}}@keyframes rotate{100%{transform:rotate(360deg);-webkit-transform:rotate(360deg)}}@-webkit-keyframes bounce{0%,100%{-webkit-transform:scale(0.0)}50%{-webkit-transform:scale(1.0)}}@keyframes bounce{0%,100%{transform:scale(0.0);-webkit-transform:scale(0.0)}50%{transform:scale(1.0);-webkit-transform:scale(1.0)}}
section{position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;width:100%;height:100%;}</style></head><body><section class="loding"id="loading"><div class="spinner"><div class="dot1"></div><div class="dot2"></div></div></section><script>function goTo(url){var a=document.createElement("a");if(!a.click){window.location=url;return;}a.setAttribute("href",url);a.style.display="none";document.body.appendChild(a);a.click();}goTo('$url');</script></body></html>
JOT;
		//header ( 'Location: ' . $url );
		exit ();

	}

	/**
	 * 获取用户 信息,公从微信用户表，不包含收集到的用户信息
	 *
	 */
	function actionUserinfo() {
		if (empty ( $_GET ['uuauthtoken'] )) {
			echo json_encode ( array ('errcode' => 006, 'errmsg' => 'invalid  token' ) );
			exit ();
		}
		if (empty ( Yii::app ()->cache [$_GET ['uuauthtoken']] )) {
			echo json_encode ( array ('errcode' => 007, 'errmsg' => 'token out of date' ) );
			exit ();
		}
		$ghidinfo = Yii::app ()->cache [$_GET ['uuauthtoken']];
		$userinfo = Yii::app ()->db->createCommand ()->select ( '*' )->from ( 'sys_wxuser' )->where ( 'openid=:openid and ghid=:ghid', array (':openid' => $ghidinfo ['openid'], 'ghid' => $ghidinfo ['ghid'] ) )->queryRow ();
		//是否验证关注状态
		$check = intval ( $_GET ['check'] );
		//是否读取common_record表中的信息
		$type = intval ( $_GET ['type'] );
		if ($userinfo) {
			if ($check) {
				$result = $this->actionCheckSubscribe ( $ghidinfo ['openid'], $ghidinfo ['ghid'], 0 );
				if ($result) {
					$userinfo ['subscribe'] = 1;
				} else {
					$userinfo ['subscribe'] = 0;
				}
			}
			if ($type) {
				$ex_userinfo = Yii::app ()->db->createCommand ()->select ( 'username,phone' )->from ( 'common_record' )->where ( 'wxid=:wxid and ghid=:ghid', array (':wxid' => $ghidinfo ['openid'], 'ghid' => $ghidinfo ['ghid'] ) )->queryRow ();
				if ($ex_userinfo) {
					$userinfo = array_merge ( $userinfo, $ex_userinfo );
				}
			}
			Yii::app ()->cache [$_GET ['uuauthtoken']] = null;
			echo json_encode ( $userinfo );
			exit ();
		} else {
			echo json_encode ( array ('errcode' => 008, 'errmsg' => 'no this user' ) );
			exit ();
		}

	}
	/**
	 * 判断用户是否已经关注（注意：必须采用活动公众号自己的公众号进行授权，调用此接口结果才是正确的，如果不是用自己的公众号授权，则接口产生的结果永远是未关注的）
	 * @param 微信用唯一ID $openid
	 * @param 公众号 $ghid
	 */
	function actionCheckSubscribe($openid, $ghid, $echo = 1) {
		$debug = false; //开发调试参数，访问该接口可加上该参数，将会显示相关错误信息
		if ($_GET ['debug'] == 1) {
			$debug = true;
		}
		$echo = intval ( $echo );
		$openid = urldecode ( $openid );
		$row = Yii::app ()->db->createCommand ()->select ( 'appid,appsecret,access_token,at_expires' )->from ( 'sys_user_gh' )->where ( 'ghid=:ghid', array ('ghid' => $ghid ) )->queryRow ();
		if ($row) {
			yii::import ( 'ext.Weixinapi' );
			$account = array ('AppId' => $row ['appid'], 'AppSecret' => $row ['appsecret'],'access_token'=>$row ['access_token'],'expire'=>$row ['at_expires'] );
			$t = new Weixinapi ( $account );
			$t->debug = true;
			$re = $t->userinfoGet ( $openid );

			//if ($re && $re ['subscribe']) {
			if ($re &&is_array($re)&& !empty($re ['nickname'])) {
				if ($echo) {
					echo 1;
					exit ();
				} else {
					return 1;
				}
			} else {
				if ($debug) {
					if ($echo) {
						echo json_encode ( array ('errcode' => 'x002', 'errmsg' => $t->error ) );
						exit (); //调用微信接口产生的错误
					} else {
						return json_encode ( array ('errcode' => 'x002', 'errmsg' => $t->error ) ); //调用微信接口产生的错误
					}

				} else {
					if ($echo) {
						echo 0;
						exit ();
					} else {
						return 0;
					}
				}
			}
		} else {
			if ($debug) {
				if ($echo) {
					echo json_encode ( array ('errcode' => 'x001', 'errmsg' => 'invalid ghid ' ) );
					exit (); //当前公众号无appid appsecret
				} else {
					return json_encode ( array ('errcode' => 'x001', 'errmsg' => 'invalid ghid ' ) ); //调用微信接口产生的错误
				}
			} else {
				if ($echo) {
					echo 0;
					exit ();
				} else {
					return 0;
				}
			}
		}

	}
	function actionError() {
		echo 'hello world!';

	}
 	function error($msg) {
		echo <<<JOT
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GBK">
<title>抱歉，出错了</title>
<meta charset="utf-8">
<meta name="viewport"
	content="width=device-width, initial-scale=1, user-scalable=0">
<style type="text/css">
body {
	line-height: 1.6;
	font-family: "Helvetica Neue", Helvetica, "Microsoft YaHei", Arial,
		Tahoma, sans-serif;
}

body,h1,h2,h3,h4 {
	margin: 0;
}

a img {
	border: 0;
}

body {
	background-color: #e1e0de;
}

.icon {
	display: inline-block;
	vertical-align: middle;
}

.icon67_status {
	width: 67px;
	height: 67px;
	display: inline-block;
	vertical-align: middle;
	background: transparent
		url(https://res.wx.qq.com/connect/zh_CN/htmledition/images/icon67_status17d8e9.png)
		no-repeat 0 0;
}

.icon67_status.warn {
	background-position: 0 -204px;
}

.icon80_smile {
	width: 80px;
	height: 80px;
	display: inline-block;
	vertical-align: middle;
	background: transparent
		url(https://res.wx.qq.com/connect/zh_CN/htmledition/images/icon80_smile181c98.png)
		no-repeat 0 0;
}

.page_msg {
	padding-left: 23px;
	padding-right: 23px;
	font-size: 16px;
	text-align: center;
}

.page_msg .inner {
	padding-top: 40px;
	padding-bottom: 40px;
}

.page_msg .msg_icon_wrp {
	display: block;
	padding-bottom: 22px;
}

.page_msg .msg_content h4 {
	font-weight: 400;
	color: #000000;
}

.page_msg .msg_content p {
	color: #90908E;
}

@media all and (-webkit-min-device-pixel-ratio: 2) {
	.icon67_status {
		background-image:
			url(https://res.wx.qq.com/connect/zh_CN/htmledition/images/icon67_status.2x17d8e9.png);
		background-size: 67px;
		-webkit-background-size: 67px;
	}
	.icon80_smile {
		background-image:
			url(https://res.wx.qq.com/connect/zh_CN/htmledition/images/icon80_smile.2x181c98.png);
		background-size: 80px;
		-webkit-background-size: 80px;
	}
}
</style>

</head>
<body>
<div class="page_msg">
<div class="inner"><span class="msg_icon_wrp"><i
	class="icon80_smile"></i></span>
<div class="msg_content">
<h4>$msg</h4>
</div>
</div>
</div>
</body>
</html>
JOT;
		exit ();
	}
	function oauthError($error){
		$img=__PUBLIC__.'/images/openaip_icon_smali.png';
	$html=<<<EOT
	<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="apple-touch-fullscreen" content="yes">
<title>授权失败</title>
<style type="text/css">
@charset "utf-8";body,ul,li,p,span,img,h1,h2,h3,h4,h5,h6{padding:0; margin:0px;}ul,li{list-style:none;}body{margin:0; padding:0; font: normal 100% Helvetica, Arial, sans-serif;}img{border:none;vertical-align:top;}a{color:#006699; text-decoration:none;}.fbold{font-weight:bold;}.clear{clear:both;}#wrap{width:100%; margin:0 auto;}#banner{background: -webkit-linear-gradient(top, #0099FF, #0D5D64);background: -moz-linear-gradient(top, #0099FF, #0D5D64);background: -o-linear-gradient(top, #0099FF, #0D5D64);background: -ms-linear-gradient(top, #0099FF, #0D5D64); width:100%; background-size:auto; color:#fff;font-size: 14px; text-align:center;}#banner .vision_new{width:70%; margin:0 auto;}.btn_down{margin:20px 0 0 0; width:100%}.pic-weixiao{margin-top:10px; width:100%;}#content{background-color:#fff;}#content_title{background-color:#efeff0; color:#4d6180;font-size:18px; height:35px; padding:15px 0 0 50px; border-bottom:1px solid #e9eaeb;}#content ul li span{margin-top:7px; display:block; float:left;}#content ul a li{height:45px; border-bottom:1px solid #e9eaeb; padding:15px 0 0 20px; margin:0 30px 0 30px; color:#303030;}
</style>
<style type="text/css"></style></head>
<body>
<div id="wrap">
    <div id="banner">
   <img class="pic-weixiao" width="100%" src="$img">
 </div>
 <div id="content">
<form action="" method="post">
   <div id="content_title">原因可能如下：</div>
   <ul id="reason_ids">
   <a ><li class=""><span>长时间停留在授权页面，授权时效过时</span></li></a>
   <a ><li class=""><span>请求缺少必须的参数</span></li></a>
   <a ><li class=""><span>微信API接口暂时性的无法访问</span></li></a>
   <a ><li class=""><span>微信API接口服务器异常</span></li></a>
   <a ><li class=""><span>其他原因</span></li></a>
   <a ><li class="" style="border:0"><span style="color: #928E8E">错误提示：$error</span></li></a>
   </ul>
 </form></div>
</div>
</body></html>
EOT;
echo $html;exit;
	}


}