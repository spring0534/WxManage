<?php

/**
* 微应用机制核心类
* appCore.php
* ----------------------------------------------
* 版权所有 2014-2015
* ----------------------------------------------
* @date: 2015-3-11
*
*/
yii::import ( 'ext.AppRuntime' );
class AppCore extends AppRuntime{
	public $userinfo; //当前访问用户的基本资料及access_token
	public $activity;//当前活动信息
	public $discontinue=0;
	public $signPackage;
	protected $_defaultHeadimg='/public/static/images/gravatar3.jpg';
	public $fromwx;
	public $fromType=0;
	private $_cookieUser='_userloc';
	private $_cookieTime=86400;//24*60*60
	public $jssdk_debug=false;
	function __construct() {
		parent::__construct ();
		$this->preEntry();
		if(yii::app ()->session ['app_userinfo'.$_GET ['_akey']]){
			$this->userinfo = yii::app ()->session ['app_userinfo'.$_GET ['_akey']];
		}
		if(Yii::app ()->session [$_GET ['_akey']]){
			$this->activity = Yii::app ()->session [$_GET ['_akey']];
			if(!empty($this->activity)){
				$this->signPackage=$this->getSignPackage();
			}
		}
	}
	function preEntry(){
		//快速登录、重新授权操作
		switch ($_GET['_controller']){
			case 'Resetlogin20140926';
			$this->Resetlogin($_GET['c_url'],$_GET['_ac']);
			break;
			case 'Quicklogin20140926';
			$this->Quicklogin($_GET['c_url'],$_GET['_ac']);
			break;
		}
		//ofIPfjq7_Te6wwb2xA-KgEyMixwo 线上调试号

		if($_SERVER['HTTP_HOST']==DEBUG_URL){
			$row=Yii::app ()->db->createCommand ()->select ( '*' )->from ( 'sys_wxuser' )->where ( 'openid=:openid', array (':openid' => 'ovJ-Jwx2YtnAxq5EB-FSxk-zp3GY') )->queryRow ();
			yii::app ()->session ['app_userinfo'.$_GET ['_akey']] = $row;
			$this->saveCookieUser($row);
		}

		//手动清除会话存储
		if(!empty($_GET['r-id'])){
			unset($_GET['r-id']);
			Yii::app ()->session ['app_userinfo'.$_GET ['_akey']]=null;
			cookie(null);
		}
		if(!empty($_GET['_fromwx']))$this->fromwx=$_GET['_fromwx'];
		$type=$_GET['from'];
		//groupmessage来源群组
		if(empty($type)) {	// 1 公众号
			$this->fromType = "1";
		} else if($type=="timeline") {	// 2 朋友圈
			$this->fromType = "2";
		} else if($type=="singlemessage") {	// 3 好友
			$this->fromType = "3";
		} else {
			$this->fromType = "0";
		}

	}

	/**
	 * 浏览器过滤
	 * @date: 2014-9-29
	 * @author: 佚名
	 * @param  $activity
	 */
	function browerfilter($activity){
		if(empty($this->userinfo)){
			if(!checkWeixinbrower()){
				//statJs
				//输出活动第三方统计代码,以便于统计方的验证
				//$this->msgTip('亲，请在微信浏览器中打开。',2,$activity['statJs']);
				$this->inpc($activity['statJs'],$_GET ['_akey']);
			}
		}
	}

	/**
	 * 所有微应用入口
	 * @date: 2014-9-28
	 * @author: 佚名
	 */
	function actionEntry() {
		if (! empty ( $_GET ['_akey'] )) {
			//获取当前活动的信息
			$row = Yii::app ()->db->createCommand ()->select ( '*' )->from ( 'activity' )->where ( 'akey=:akey', array (':akey' => $_GET ['_akey'] ) )->queryRow ();
			if ($row) {
				//将当前活动信息存入session中
				$this->activity=Yii::app ()->session [$_GET ['_akey']] = $row;
				$this->browerfilter($this->activity);
				$pu=Yii::app ()->db->createCommand ()->select ( '*' )->from ( 'plugin' )->where ( 'ptype=:ptype', array (':ptype' => $row['type'] ) )->queryRow ();
				//$pu ['processor_class'] //微应用运行类，创建微应用的时候填写的，就是默认运行的类，采用文件夹名称和controller一起联合命名，如跳跳跳游戏的文件夹是ddd,controller是gameController,则运行类命名规则为ddd.game,如果指定action，则命名为ddd.game.index
				//通过运行类，得到微应用目录及相关controller,action
				list ( $appdir, $controller, $action ) = explode ( '.', $pu ['processor_class'] );
				$this->_appName=$_apps = $appdir;
				$_controller = $_GET ['_controller'] ? $_GET ['_controller'] : ($controller ? $controller : $appdir);
				$_action = $_GET ['_action'] ? $_GET ['_action'] : ($action ? $action : 'index');
				if (! empty ( $_apps ) && ! empty ( $_controller ) && ! empty ( $_action )) {
					$GLOBALS['_appName']=$_apps;
					$GLOBALS['_controller']=$_controller;
					$GLOBALS['_action']=$_action;
					//重新配置yii框架的主题路径和主题URL为当前微应用的主题文件夹
					yii::app ()->themeManager->setBasePath ( Yii::getPathOfAlias ( 'webroot' ) . '/microapp/' . $_apps . '/themes/' );
					Yii::app ()->themeManager->setBaseUrl ( Yii::app ()->baseUrl . '/microapp/' . $_apps . '/themes/' );
					$methodName = 'action' . ucfirst ( $_action );
					$className = ucfirst ( $_controller ) . 'Controller';
					if (! file_exists ( YiiBase::getPathOfAlias ( "microapp.$_apps.$className" ) . '.php' )) {
						$this->error ( '参数错误，错误代码x001');
					}
					$oauth_ghid=$this->activity['ghid'];
					$config = $this->getActivityConfig ();
					yii::app ()->theme = 'default'; //默认使用default
					if($theme=Yii::app()->db->createCommand()->select('wx_theme')->from('plugin_theme')->where('id=' . $this->activity['themeid'])->queryScalar()){
						yii::app ()->theme = $theme; //使用配置主题
					}
					//从配置中读取配置字段is_oauth（限定为这个字段名，如果要配置这个配置项，请配置为is_oauth属性），判断是否需要网页授权
					//约定是否oauth授权字段为is_oauth,没有该字段，默认为snsapi_userinfo授权，值3为不授权，1为snsapi_userinfo，2为snsapi_base
					$is_oauth=1;
					if(!empty($config ['is_oauth']))$is_oauth=$config ['is_oauth'];
					if(empty($this->userinfo)){
						$this->readCookieUser(Yii::app ()->request->hostInfo . $this->createUrl ( $_GET ['_akey'] . '/' . $_controller ),$_action .'?'.http_build_query($_GET) );
					}
					if ($is_oauth!=3) {

						if (empty ( $this->userinfo )) {
							//处理天虹的私有授权gh_204c2da17409
							if($oauth_ghid=='rainbow'){
								if(!empty($_GET['openid'])&&!empty($_GET['refer'])&&$_GET['refer']=='tianhong'){
									unset($_GET['share_url']);
									unset($_GET['refer']);
									$reload=Yii::app ()->request->hostInfo . $this->createUrl ( $_GET ['_akey'] . '/' . $_controller . '/' . $_action .'/tx/'.time().'?'.http_build_query($_GET) );
									$this->oauthRedirect ( $_GET['openid'], $oauth_ghid,'xxx','','Location: '.$reload,100);
							
								}else{
									$tid=$config['oauth_id'];
									header ( 'Location: http://wechat.tianhong.cn/third?id='.$tid);exit();
								}
							} 
							//处理智慧同行的授权
							if ($oauth_ghid=='gh_7234d5064693'){
								if(!empty($_GET['code'])&&!empty($_GET['refer'])&&$_GET['refer']=='zhihuitongxing'){
									$_GET['openid'] = $_GET['code'];
									unset($_GET['refer'], $_GET['code']);
									$reload=Yii::app ()->request->hostInfo . $this->createUrl ( $_GET ['_akey'] . '/' . $_controller . '/' . $_action .'/tx/'.time().'?'.http_build_query($_GET) );
									$this->oauthRedirect ( $_GET['openid'], $oauth_ghid,'xxx','','Location: '.$reload,101);
										
								}else{
									//$tid=$config['oauth_id'];
									//header ( 'Location: http://m.zmqzy.com/Zhtx/zhs');exit();
									$param = '';
									if(isset($_GET['_fromwx'])) $param = '?_fromwx='. $_GET['_fromwx'];
									//echo $param; exit;
									header ( 'Location: http://m.zmqzy.com/Zhtx/zhs'. $param);exit();
								}
							}
							//从公众号表中读取类型
							$ghiduser = Yii::app ()->db->createCommand ()->select ( 'appid,appsecret,oauth,dev_oauth_ghid,dev_jsapi_ghid' )->from ( 'sys_user_gh' )->where ( 'ghid=:ghid', array ('ghid' => $oauth_ghid ) )->queryRow ();
							//开发者的配置授权
							if(!empty($ghiduser['dev_oauth_ghid'])){
								$scope='&scope=1';
								if($is_oauth==2)$scope='';
								$location=OAUTH_URL_CORE.'?returnUrl='.urlencode(Yii::app ()->request->hostInfo . $this->createUrl ( $_GET ['_akey'] . '/' . $_controller . '/' . $_action .'?'.http_build_query($_GET) )).'&ghid='.$ghiduser['dev_oauth_ghid'].$scope;
								if (! empty ( $_GET ['uuauthtoken'] )) {
									$uuauthtoken=$_GET ['uuauthtoken'];
									$_GET['uuauthtoken']='';
									unset($_GET['uuauthtoken']);
									$reload=Yii::app ()->request->hostInfo . $this->createUrl ( $_GET ['_akey'] . '/' . $_controller . '/' . $_action .'?'.http_build_query($_GET) );

									$this->oauthRedirect ( '','',$uuauthtoken,'Location: '.$location,'Location: '.$reload);
								} else {
									header ( 'Location: '.$location);exit();
								}
							}else{
								switch ($ghiduser ['oauth']){
									case 3://腾讯微购物百货授权
										$scope='&scope=1';
										if($is_oauth==2)$scope='';
										$location=OAUTH_URL_CORE.'?returnUrl='.urlencode(Yii::app ()->request->hostInfo . $this->createUrl ( $_GET ['_akey'] . '/' . $_controller . '/' . $_action .'?'.http_build_query($_GET) )).'&ghid='.$oauth_ghid.$scope;
										if (! empty ( $_GET ['uuauthtoken'] )) {
											$uuauthtoken=$_GET ['uuauthtoken'];
											$_GET['uuauthtoken']='';
											unset($_GET['uuauthtoken']);
											$reload=Yii::app ()->request->hostInfo . $this->createUrl ( $_GET ['_akey'] . '/' . $_controller . '/' . $_action .'/tx/'.time().'?'.http_build_query($_GET) );

											$this->oauthRedirect ( '','',$uuauthtoken,'Location: '.$location,'Location: '.$reload);
										} else {
											header ( 'Location: '.$location);exit();
										}
										break;
									case 4://扫货帮接口授权，王府井在用
										$scope='&scope=1';
										if($is_oauth==2)$scope='';
										$location=OAUTH_URL_CORE.'?returnUrl='.urlencode(Yii::app ()->request->hostInfo . $this->createUrl ( $_GET ['_akey'] . '/' . $_controller . '/' . $_action .'?'.http_build_query($_GET) )).'&ghid='.$oauth_ghid.$scope;

										if (! empty ( $_GET ['uuauthtoken'] )) {
											$uuauthtoken=$_GET ['uuauthtoken'];
											$_GET['uuauthtoken']='';
											unset($_GET['uuauthtoken']);
											$reload=Yii::app ()->request->hostInfo . $this->createUrl ( $_GET ['_akey'] . '/' . $_controller . '/' . $_action .'/tx/'.time().'?'.http_build_query($_GET) );

											$this->oauthRedirect ( '','',$uuauthtoken,'Location: '.$location,'Location: '.$reload);
										} else {
											header ( 'Location: '.$location);exit();
										}
										break;
									case 5://万科接口授权，万科专用
										$scope='&scope=1';
										if($is_oauth==2)$scope='';
										$location='http://lgsywx.vanke.com/interface/index.php?r=authorize&redirect_uri='.urlencode(Yii::app ()->request->hostInfo . $this->createUrl ( $_GET ['_akey'] . '/' . $_controller . '/' . $_action .'?'.http_build_query($_GET) )).'&'.$scope;
										if (! empty ( $_GET ['uuauthtoken'] )) {
											$uuauthtoken=$_GET ['uuauthtoken'];
											$_GET['uuauthtoken']='';
											unset($_GET['uuauthtoken']);
											$reload=Yii::app ()->request->hostInfo . $this->createUrl ( $_GET ['_akey'] . '/' . $_controller . '/' . $_action .'/tx/'.time().'?'.http_build_query($_GET) );

											$this->oauthRedirect ( '',$oauth_ghid,$uuauthtoken,'Location: '.$location,'Location: '.$reload,5,$is_oauth);
										} else {
											header ( 'Location: '.$location);exit();
										}
										break;
									default:// 1、微营1、2、微营2、100公众号自己
										$scope='&scope=1';
									    if($is_oauth==2)$scope='';
										$location=OAUTH_URL_CORE.'?returnUrl='.urlencode(Yii::app ()->request->hostInfo . $this->createUrl ( $_GET ['_akey'] . '/' . $_controller . '/' . $_action .'?'.http_build_query($_GET) )).'&ghid='.$oauth_ghid.$scope;
										if (! empty ( $_GET ['uuauthtoken'] )) {
											$uuauthtoken=$_GET ['uuauthtoken'];
											$_GET['uuauthtoken']='';
											unset($_GET['uuauthtoken']);
											$reload=Yii::app ()->request->hostInfo . $this->createUrl ( $_GET ['_akey'] . '/' . $_controller . '/' . $_action .'?'.http_build_query($_GET) );

											$this->oauthRedirect ( '','',$uuauthtoken,'Location: '.$location,'Location: '.$reload);
										} else {
											header ( 'Location: '.$location);exit();
										}
										break;
								}
							}
						}
					}

					Yii::import ( "microapp.$_apps.models.*" );
					$this->runController($_controller.'/'.$_action,$row,$config);
				} else {
					$this->log('运行类错误');
					$this->error ( '参数错误，错误代码x002' );//运行类错误
				}
			} else {
				$this->msgTip ( '不存在的活动！' );
			}
		} else {
			$this->msgTip ( '您访问的路径不存在！' );
		}
	}
	/**
	 * 活动未开始处理函数，可在自己的微应用中重写函数，自己处理,注意，这个函数是在活动未开始前才会执行，在自己微应用中重写的函数也是活动未开始前执行，请注意配置活动开始时间
	 * @date: 2014-9-16
	 * @author: 佚名
	 */
	protected  function  preStart(){
		//$this->msgTip('活动还未开始！',2);
		$this->expire();
	}
	/**
	 * 活动结束处理函数，可在自己的微应用中重写函数，自己处理，注意，这个函数是在活动结束后才会执行，在自己微应用中重写的函数也是活动结束后执行，请注意配置活动结束时间
	 * @date: 2014-9-16
	 * @author: 佚名
	 */
	protected function preEnd(){
		//$this->msgTip('活动已经结束！');
		$this->expire();
	}

	/**
	 * 获取微信分享JS-SDK的签名信息
	 * @date: 2015-1-21
	 * @author: 佚名
	 * @return mixed
	 */
	public function getSignPackage() {

		yii::import ( 'ext.JsSdk' );
		$jd=new JsSdk();
		switch($this->activity ['ghid']){
			case 'gh_7234d5064693':  //智慧同行
			case 'rainbow':          //天虹
				$signPackage=$jd->getSignPackage('gh_30d2a3e4dc51'); break;
			default:
				$signPackage=$jd->getSignPackage($this->activity ['ghid'] );
				break;
		}
		//dump($signPackage);
		return $signPackage;
	}
	/**
	 * 获取微应用配置信息
	 * @param bool $deal_with 是否处理多图参数数据 默认false不处理
	 */
	function getActivityConfig($deal_with=FALSE) {
		$aid = $this->activity ['aid'];
		//兼容旧平台
		if($deal_with && $aa=Yii::app ()->cache->get('ActivityConfig_deal'.$aid)){
			return $aa;
		};
		if(!$deal_with && $aa=Yii::app ()->cache->get('ActivityConfig'.$aid)){
			return $aa;
		};
		$act=Activity::model()->findByPk($aid);
		if (empty($act))
			return null;
		$act_config=ActivitySettings::model()->findAllByAttributes(array(
			'aid'=>$act->aid
		));
		$model=PluginProp::model()->findAll(array(
			'condition'=>'ptype=:ptype',
			'params'=>array(
				':ptype'=>$act->type
			),
			'order'=>'seq'
		));
		foreach ($act_config as $val){
			$config[$val->propname]=$val->propvalue;
		}

		foreach ($model as $v){

			$setting = unserialize($v->setting);
			$data[$v->propname]=$setting['defaultvalue'];//先从属性默认值取值
			if (isset($config[$v->propname])){//如果活动配置中有值则重新取值
				if($deal_with && $v->proptype=='muimage'&&!empty($config[$v->propname])){
					//兼容旧平台
					$data_s = unserialize($config[$v->propname]);
					$url = '';
					foreach($data_s as $v_s){
						if(!empty($v_s['url'])){
							$url .= $v_s['url'] . ';';
						}
					}
					$data[$v->propname]=rtrim($url,';');
				}else{
					$data[$v->propname]=$config[$v->propname];
				}
			}else if(!empty($v->setting)){
				if($deal_with && $v->proptype=='muimage'){
					$data_s = unserialize($v->setting);
					$url = '';
					foreach($data_s as $v_s){
						if(!empty($v_s['url'])){
							$url .= $v_s['url'] . ';';
						}
					}
					$data[$v->propname]=rtrim($url,';');
				}
			}
			unset($setting);
		}
		//兼容旧平台
		if($deal_with)
			Yii::app ()->cache->set('ActivityConfig_deal'.$aid,$data,60*5);
		else
			Yii::app ()->cache->set('ActivityConfig'.$aid,$data,60*5);
		return $data;

	}
	/**
	 * 获取活动公众号的信息
	 *
	 */
	function getWxaccount($field = '*') {
		$ghid = $this->activity ['ghid'];
		return Yii::app ()->db->createCommand ()->select ( $field )->from ( 'sys_user_gh' )->where ( 'ghid=:ghid', array (':ghid' => $ghid ) )->queryRow ();

	}
	/**
	 * 读取本地存储的cookie
	 * @date: 2014-9-28
	 * @author: 佚名
	 * @param  $url
	 * @param  $action
	 */
	function readCookieUser($url,$action){
		$user=json_decode(cookie($_GET['_akey'].$this->_cookieUser),true);
		if(!empty($user)){
			$this->Quicklogin($url, $action);exit;
			/*yii::app ()->themeManager->setBasePath ( Yii::getPathOfAlias ( 'webroot' ) . '/protected/views/' );
			Yii::app ()->themeManager->setBaseUrl ( Yii::app ()->baseUrl . '/protected/views/' );
			$this->renderPartial('/app/confirm',array('user'=>$user,'_url'=>$url,'_action'=>$action));
			exit;*/
		}
	}
	/**
	 * 重新授权登录
	 * @date: 2014-9-28
	 * @author: 佚名
	 * @param  $url
	 * @param  $action
	 */
	function Resetlogin($url,$action){
		yii::app ()->session ['app_userinfo'.$_GET ['_akey']] =null;
		cookie($_GET['_akey'].$this->_cookieUser,null);
		header ( 'Location: '.$url.'/'.$action);
	}
	/**
	 * 快速登录
	 * @date: 2014-9-28
	 * @author: 佚名
	 * @param  $url
	 * @param  $action
	 */
	function Quicklogin($url,$action){
		$user=json_decode(cookie($_GET['_akey'].$this->_cookieUser),true);
		if($user){
			$userinfo = Yii::app ()->db->createCommand ()->select ( '*' )->from ( 'sys_wxuser' )->where ( 'openid=:openid and ghid=:ghid', array (':openid' => $user ['openid'], 'ghid' => $user ['a_ghid'] ) )->queryRow ();
			if($userinfo){
				yii::app ()->session ['app_userinfo'.$_GET ['_akey']] =$user;
				header ( 'Location: '.$url.'/'.$action);
			}else{
			    Yii::app ()->session ['app_userinfo'.$_GET ['_akey']]=null;
			    cookie(null);
				$this->error('用户信息获取失败,正在重新调用授权。。。',$url.'/'.$action,true,3);

			}
		}else{
		    Yii::app ()->session ['app_userinfo'.$_GET ['_akey']]=null;
		    cookie(null);
			$this->error('用户信息获取失败,正在重新授权。。。',$url.'/'.$action,true,3);
		}

	}
	/**
	 * 保存本地cookie信息
	 * @date: 2014-9-28
	 * @author: 佚名
	 * @param  $user
	 */
	function saveCookieUser($user){
		cookie($_GET['_akey'].$this->_cookieUser,json_encode(array('openid'=>$user['openid'],'a_ghid'=>$this->activity['ghid'],'nickname'=>$user['nickname'],'headimgurl'=>$user['headimgurl'],'ghid'=>$user['ghid'])),$this->_cookieTime);

	}
	/**
	 * Oauth认证回调处理
	 * @see CController::redirect()
	 */
	function oauthRedirect($openid, $ghid,$uuauthtoken='',$reurl='',$reload='',$type=0,$scope=1) {
		if($uuauthtoken){
			if($type==5){//处理万科的接口授权
				$row=json_decode($re=@file_get_contents('http://lgsywx.vanke.com/interface/index.php?r=authorize/userinfo&uuauthtoken='.$uuauthtoken),true);
				if($row){
					$user = Yii::app ()->db->createCommand ()->select ( '*' )->from ( 'sys_wxuser' )->where ( 'ghid=:ghid and openid=:openid', array (':ghid' => $ghid,':openid'=>$row['OpenId'] ) )->queryRow ();
					$data=array(
						'scope'=> $scope==1?'snsapi_userinfo':'snsapi_base',
						'ua' => $_SERVER ['HTTP_USER_AGENT'],
						'ghid'=> $ghid,
						'srcOpenid'=>$row['OpenId']
					);
					if($scope==2){
						if ($user) {
							$data ['utm']=date ( 'Y-m-d H:i:s' );
							$re=Yii::app ()->db->createCommand()->update('sys_wxuser',$data,'openid=:openid and ghid=:ghid',array(':openid'=>$row['OpenId'],':ghid'=>$ghid));
						} else {
							$data['openid'] =$row['OpenId'];
							$data['ctm']= date ( 'Y-m-d H:i:s' );
							Yii::app ()->db->createCommand()->insert('sys_wxuser',$data);

						}

					}else if($scope==1){
						yii::import ( 'ext.Emoji' );
						$Emoji=new Emoji();
						$data ['nickname']=$Emoji->fileterEmoji($row ['NickName']);
						$rule = '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9_]+$/u';//匹配中文字符，数字，字母的正则表达式
						$reg = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
						$data2=array(
							'headimgurl'=> $row ['Headimgurl'],
							'city'=> $row ['City'],
							'sex'=> $row ['Sex'],
							'province'=> $row ['Province'],
							'privilege'=>'',
							'ghid'=> $ghid

						);
						if ($user) {
							$data ['utm']=date ( 'Y-m-d H:i:s' );
							$data=array_merge($data,$data2);
							try{
								$result=Yii::app ()->db->createCommand()->update('sys_wxuser',$data,'openid=:openid and ghid=:ghid',array(':openid'=>$row['OpenId'],':ghid'=>$ghid));

							}catch(Exception $e){
								$str2='';
								preg_match_all ( $reg, $data ['nickname'], $match );
								foreach ($match[0] as $k=>$v){
									if(preg_match ( $rule, $v )===1){
										$str2.= $v;
									}
								}
								$data ['nickname']=$str2;
								$re=Yii::app ()->db->createCommand()->update('sys_wxuser',$data,'openid=:openid and ghid=:ghid',array(':openid'=>$row['OpenId'],':ghid'=>$ghid));
							}
						} else {
							$data['openid'] =$row['OpenId'];
							$data['ctm']= date ( 'Y-m-d H:i:s' );
							$data=array_merge($data,$data2);
							try{
								$res=Yii::app ()->db->createCommand()->insert('sys_wxuser',$data);
							}catch(Exception $e){
								$str2='';
								preg_match_all ( $reg, $data ['nickname'], $match );
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
				}
				$row = Yii::app ()->db->createCommand ()->select ( '*' )->from ( 'sys_wxuser' )->where ( 'ghid=:ghid and openid=:openid', array (':ghid' => $ghid,':openid'=>$row['OpenId'] ) )->queryRow ();

			} else if($type==100){//处理天虹授权
				$post=array('openid'=>$openid);
				$ch = curl_init ();
				curl_setopt ( $ch, CURLOPT_URL, 'http://wechat.tianhong.cn/wxuserbaseinfo' );
				curl_setopt ( $ch, CURLOPT_POST, 1 );
				curl_setopt ( $ch, CURLOPT_HEADER, 0 );
				curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post );
				$return = curl_exec ( $ch );
				curl_close ( $ch );
				$userinfo=json_decode($return,true);
				if($userinfo&&empty($userinfo['errcode'])){
					if(empty($userinfo ['headimgurl'])&&empty($userinfo ['nickname'])){
						//$this->error('未关注');
					}
					$row = Yii::app ()->db->createCommand ()->select ( '*' )->from ( 'sys_wxuser' )->where ( 'ghid=:ghid and openid=:openid', array (':ghid' => $ghid,':openid'=>$openid ) )->queryRow ();
				
					$data=array(
							'headimgurl'=> $userinfo ['headimgurl'],
							'nickname'=> $userinfo ['nickname'],
							'city'=> $userinfo ['city'],
							'sex'=> $userinfo ['sex'],
							'province'=> $userinfo ['province'],
							//'scope'=> 'snsapi_userinfo',
							//'ua' => $_SERVER ['HTTP_USER_AGENT'],
							'ghid'=>$ghid);
				
					//$data['srcOpenid'] = $userinfo ['openid'];
					yii::import ( 'ext.Emoji' );
					$Emoji=new Emoji();
					$data ['nickname']=$Emoji->fileterEmoji($data ['nickname']);
					$rule = '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9_]+$/u';//匹配中文字符，数字，字母的正则表达式
					$re ['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
					if ($row) {
						if ($scope == 1) {
							if (empty ( $row ['nickname'] ) || empty ( $row ['headimgurl'] )||( $row ['nickname']!=$data ['nickname'])) {
								$data ['utm']=date ( 'Y-m-d H:i:s' );
								try{
									$re=Yii::app ()->db->createCommand()->update('sys_wxuser',$data,'openid=:openid and ghid=:ghid',array(':openid'=>$userinfo['openid'],':ghid'=>$ghid));
								}catch(Exception $e){
									$str2='';
									preg_match_all ( $re ['utf-8'], $data ['nickname'], $match );
									foreach ($match[0] as $k=>$v){
										if(preg_match ( $rule, $v )===1){
											$str2.= $v;
										}
									}
									$data ['nickname']=$str2;
									Yii::app ()->db->createCommand()->update('sys_wxuser',$data,'openid=:openid and ghid=:ghid',array(':openid'=>$userinfo['openid'],':ghid'=>$ghid));
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
					$row = Yii::app ()->db->createCommand ()->select ( '*' )->from ( 'sys_wxuser' )->where ( 'ghid=:ghid and openid=:openid', array (':ghid' => $ghid,':openid'=>$openid ) )->queryRow ();
				}
			} else if($type==101){ //处理智慧同行授权
				$post=array('code'=>$openid);
				$ch = curl_init ();
				curl_setopt ( $ch, CURLOPT_URL, 'http://m.zmqzy.com/Api/wxUserInfo' );
				curl_setopt ( $ch, CURLOPT_POST, 1 );
				curl_setopt ( $ch, CURLOPT_HEADER, 0 );
				curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post );
				$return = curl_exec ( $ch );
				curl_close ( $ch );
				$userinfo=json_decode($return,true);
				//echo $openid,'<br>';
				//var_dump($userinfo); exit;
				if($userinfo&&empty($userinfo['errcode'])){
					if(empty($userinfo ['headimgurl'])&&empty($userinfo ['nickname'])){
						//$this->error('未关注');
					}
					$row = Yii::app ()->db->createCommand ()->select ( '*' )->from ( 'sys_wxuser' )->where ( 'ghid=:ghid and openid=:openid', array (':ghid' => $ghid,':openid'=>$openid ) )->queryRow ();
				
					$data=array(
							'headimgurl'=> $userinfo ['headimgurl'],
							'nickname'=> $userinfo ['nickname'],
							'city'=> $userinfo ['city'],
							'sex'=> $userinfo ['sex'],
							'province'=> $userinfo ['province'],
							//'scope'=> 'snsapi_userinfo',
							//'ua' => $_SERVER ['HTTP_USER_AGENT'],
							'ghid'=>$ghid);
				
					//$data['srcOpenid'] = $userinfo ['openid'];
					yii::import ( 'ext.Emoji' );
					$Emoji=new Emoji();
					$data ['nickname']=$Emoji->fileterEmoji($data ['nickname']);
					$rule = '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9_]+$/u';//匹配中文字符，数字，字母的正则表达式
					$re ['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
					if ($row) {
						if ($scope == 1) {
							if (empty ( $row ['nickname'] ) || empty ( $row ['headimgurl'] )||( $row ['nickname']!=$data ['nickname'])) {//$userinfo['openid']
								$data ['utm']=date ( 'Y-m-d H:i:s' );
								try{
									$re=Yii::app ()->db->createCommand()->update('sys_wxuser',$data,'openid=:openid and ghid=:ghid',array(':openid'=>$openid,':ghid'=>$ghid));
								}catch(Exception $e){
									$str2='';
									preg_match_all ( $re ['utf-8'], $data ['nickname'], $match );
									foreach ($match[0] as $k=>$v){
										if(preg_match ( $rule, $v )===1){
											$str2.= $v;
										}
									}
									$data ['nickname']=$str2;
									Yii::app ()->db->createCommand()->update('sys_wxuser',$data,'openid=:openid and ghid=:ghid',array(':openid'=>$openid,':ghid'=>$ghid));
								}
							}
						}
				
					} else {
						$data['openid'] = $openid;
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
					$row = Yii::app ()->db->createCommand ()->select ( '*' )->from ( 'sys_wxuser' )->where ( 'ghid=:ghid and openid=:openid', array (':ghid' => $ghid,':openid'=>$openid ) )->queryRow ();
				}
			}else{
				$row=json_decode($re=@file_get_contents(''.OAUTH_URL_CORE.'/userinfo?uuauthtoken='.$uuauthtoken),true);
			}
			if($row&&empty($row['errcode'])){
				yii::app ()->session ['app_userinfo'.$_GET ['_akey']] = $row;
				$this->saveCookieUser($row);
				header($reload);exit;
			}else{
				//$this->log('uuauthtoken失效');
				header($reurl);exit;
				//$this->error('获取用户信息失败，请关闭浏览器重新授权！');
			}
		}else{
			$this->error('操作失败，请稍后再试');
		}

	}

    /**
     * 日志记录
     * @date: 2014-9-28
     * @author: 佚名
     * @param 日志内容 $content
     */
    function log($content){
    	$data=array(
    	'appName'=>$GLOBALS['_appName'],
    	'aid'=>$this->activity['aid'],
    	'controller'=>$GLOBALS['_controller'],
    	'action'=>$GLOBALS['_action'],
    	'content'=>$content,
    	'url'=>$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
    	'referrer'=>empty($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_ORIGIN']:$_SERVER['HTTP_REFERER'],
    	'ip'=>Yii::app()->request->userHostAddress,
    	'ua'=> $_SERVER ['HTTP_USER_AGENT'],
    	);
    	$str='exception ';
    	foreach ($data as $k=>$v){
    		$str.=$k.':'.$v.' ';
    	}
    	Yii::log($str,'error','exception.AppException');
    	//Yii::app ()->db2->createCommand()->insert('app_log',$data);
    }
	/**
	 * 简单信息提示
	 * @date: 2014-9-28
	 * @author: 佚名
	 * @param  $msg
	 * @param  $tiptitle
	 */
    function msgTip($msg,$icon=1,$js=''){
    	$this->error('', 'javascript:history.back(-1);', false,2,$msg,$icon,$js);
    }
	/**
	 * 信息提示
	 * @param string $msg	信息内容
	 * @param string $url	跳转URL
	 * @param boolean $isAutoGo	是否自动跳转
	 * @param int $time	自动设置时间
	 */
	 function error($msg, $url = 'javascript:history.back(-1);', $isAutoGo = false,$time=2,$tiptitle='抱歉，出错了',$icon=1,$js='') {
	 	$this->log($tiptitle);
	 	if(strpos($tiptitle, '结束')!==false||strpos($tiptitle, '关闭')!==false){
	 		$this->expire();exit;
	 	}
	 	//$this->error_page();
		exMsg($msg, $url, $isAutoGo,$time,$tiptitle,$icon,$js);
	}
	/**
	 * 活动到期展示画面
	 * @date: 2015-2-9
	 * @author: 佚名
	 */
	function expire(){
		if(!checkWeixinbrower()){
			$this->inpc('',$_GET ['_akey']);exit;
		}
		Yii::app()->setViewPath(Yii::getPathOfAlias ( 'webroot' ) . '/protected/modules/apps/views/');
		$this->renderPartial('/app/expire');
		exit;
	}
	function error_page(){
		Yii::app()->setViewPath(Yii::getPathOfAlias ( 'webroot' ) . '/protected/modules/apps/views/');
		$this->renderPartial('/app/error');
		exit;
	}
	function inpc($statjs,$akey){
		Yii::app()->setViewPath(Yii::getPathOfAlias ( 'webroot' ) . '/protected/modules/apps/views/');
		$this->renderPartial('/app/pc',array('statjs'=>$statjs,'akey'=>$akey));
		exit;
	}
}