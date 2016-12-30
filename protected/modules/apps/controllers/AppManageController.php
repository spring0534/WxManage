<?php
/**
 * AppController.php 所有微应用的基类
 * ----------------------------------------------
 * 版权所有 2014-2015
 * ----------------------------------------------
 * @date: 2014-8-14
 *
 */

class AppManageController extends Controller {
	public $layout = 'main';
	protected $activity;
	public $controllerMap=array();
	private $_appName;
	private $_controllerPath;
	private $_controller;
	public $controllerNamespace;
	function __construct() {
		parent::__construct ( $_GET ['_controller'], $this->module );
		YiiBase::setPathOfAlias ( 'microapp', Yii::getPathOfAlias ( 'webroot' ) . '/microapp/' );
		Yii::app()->name='后台管理';
		if (Yii::app ()->session [$_GET ['_akey']])	$this->activity = Yii::app ()->session [$_GET ['_akey']];
	}
	function  redirect($url,$terminate=true,$statusCode=302){
		parent::redirect(Yii::app()->baseUrl .'/appAdmin/'.$_GET['_akey'].'/'.ltrim($url,'/'),$terminate,$statusCode);

	}
	/**
	 * 微应用入口
	 * @date: 2014-9-15
	 * @author: 佚名
	 */
	function actionEntry() {
		if (! empty ( $_GET ['_akey'] )) {
			$row = Yii::app ()->db->createCommand ()->select ( '*' )->from ( 'activity' )->where ( 'akey=:akey', array (':akey' => $_GET ['_akey'] ) )->queryRow ();
			if ($row) {
				$this->activity = Yii::app ()->session [$_GET ['_akey']] = $row;
				$pu=Yii::app ()->db->createCommand ()->select ( '*' )->from ( 'plugin' )->where ( 'ptype=:ptype', array (':ptype' => $row['type'] ) )->queryRow ();
				list ( $appdir, $controller, $action ) = explode ( '.', $pu ['processor_class'] );
				$this->_appName=$_apps = $appdir;
				$_controller = $_GET ['_controller']?$_GET ['_controller']:'default';
				$_action = $_GET ['_action'] ? $_GET ['_action'] : 'index';
				if (! empty ( $_apps ) && ! empty ( $_controller ) && ! empty ( $_action )) {
					Yii::app()->setViewPath(Yii::getPathOfAlias ( 'webroot' ) . '/microapp/' . $_apps . '/admin/views/');
					Yii::app ()->themeManager->setBaseUrl ( Yii::app ()->baseUrl . '/microapp/' . $_apps  );
					yii::app ()->theme = 'admin';
					$methodName = 'action' . ucfirst ( $_action );
					$className = ucfirst ( $_controller ) . 'Controller';
					if (! file_exists ( YiiBase::getPathOfAlias ( "microapp.$_apps.admin.$className" ) . '.php' )) {
						$this->error ( '你访问的页面不存在！');//controller不存在
					}
					Yii::import ( "microapp.$_apps.models.*" );
					$this->runController($_controller.'/'.$_action);
				} else {
					$this->error ( '参数错误，错误代码x002' );//
				}

			} else {
				$this->error ( '不存在的活动！' );
			}
		} else {
			$this->error ( 'access denied！' );
		}
	}

	public function getControllerPath()
	{
		return $this->_controllerPath= YiiBase::getPathOfAlias ( "microapp.".$this->_appName.'/admin' );
	}
	public function runController($route)
	{
		if(($ca=$this->createController($route))!==null)
		{
			list($controller,$actionID)=$ca;
			$oldController=$this->_controller;
			$this->_controller=$controller;
			Yii::app ()->session ['controllerobj']=$controller;
			$controller->init();
			$controller->run($actionID);
			$this->_controller=$oldController;
		}
		else
			throw new CHttpException(404,Yii::t('yii','Unable to resolve the request "{route}".',
				array('{route}'=>$route===''?$this->defaultController:$route)));
	}

	protected function parseActionParams($pathInfo)
	{
		if(($pos=strpos($pathInfo,'/'))!==false)
		{
			$manager=Yii::app()->getUrlManager();
			$manager->parsePathInfo((string)substr($pathInfo,$pos+1));
			$actionID=substr($pathInfo,0,$pos);
			return $manager->caseSensitive ? $actionID : strtolower($actionID);
		}
		else
			return $pathInfo;
	}
	public function createController($route,$owner=null)
	{
		if($owner===null)
			$owner=$this;
		if(($route=trim($route,'/'))==='')
			$route=$owner->defaultController;

		$caseSensitive=Yii::app()->getUrlManager()->caseSensitive;
		$route.='/';
		while(($pos=strpos($route,'/'))!==false)
		{
			$id=substr($route,0,$pos);
			if(!preg_match('/^\w+$/',$id))
				return null;
			if(!$caseSensitive)
				$id=strtolower($id);
			$route=(string)substr($route,$pos+1);
			if(!isset($basePath))  // first segment
			{
				if(isset($owner->controllerMap[$id]))
				{
					return array(
						Yii::createComponent($owner->controllerMap[$id],$id,$owner===$this?null:$owner),
						$this->parseActionParams($route),
					);
				}
				if(($module=$owner->getModule($id))!==null)
					return $this->createController($route,$module);
				$basePath=$owner->getControllerPath();
				$controllerID='';
			}
			else
				$controllerID.='/';
			$className=ucfirst($id).'Controller';
			$classFile=$basePath.DIRECTORY_SEPARATOR.$className.'.php';
			if($owner->controllerNamespace!==null)
				$className=$owner->controllerNamespace.'\\'.$className;
			if(is_file($classFile))
			{
				if(!class_exists($className,false))
					require($classFile);
				if(class_exists($className,false) && is_subclass_of($className,'CController'))
				{
					$id[0]=strtolower($id[0]);
					return array(
						new $className($controllerID.$id,$owner===$this?null:$owner),
						$this->parseActionParams($route),
					);
				}
				return null;
			}
			$controllerID.=$id;

			$basePath.=DIRECTORY_SEPARATOR.$id;
		}
	}
	/**
	 * 获取微应用配置信息
	 *
	 * @param unknown_type $field
	 */
	function getActivityConfig() {
		$aid = $this->activity ['aid'];
		$row = Yii::app ()->db->createCommand ()->select ( 'propname,propvalue' )->from ( 'activity_settings' )->where ( 'aid=:aid', array (':aid' => $aid ) )->queryAll ();
		foreach ( $row as $key => $val ) {
			$new_row [$val ['propname']] = $val ['propvalue'];
		}
		return $new_row;

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
	 * 信息提示
	 * @param string $msg	信息内容
	 * @param string $url	跳转URL
	 * @param boolean $isAutoGo	是否自动跳转
	 */
	function error($msg, $url = 'javascript:history.back(-1);', $isAutoGo = false) {
		if ($msg == '404') {
			header ( "HTTP/1.1 404 Not Found" );
			$msg = '404 请求页面不存在！';
		}
		echo <<<JOT
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Cache-control" content="no-cache">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<meta name="format-detection" content="telephone=no">
<meta name="keywords" content="">
JOT;
		if ($isAutoGo) {
			echo "<meta http-equiv=\"refresh\" content=\"2;url=$url\" />";
		}
		echo <<<JOT
<title>提示信息 - </title>
<style>
	* { word-wrap: break-word; }
	* { margin:0; padding:0; }
	html,body { height:100%; font:12px/1.6  Microsoft YaHei, Helvetica, sans-serif; color:#4C4C4C; }
	body, ul, ol, li, dl, dd, p, h1, h2, h3, h4, h5, h6, form, fieldset, .pr, .pc { margin: 0; padding: 0; }
	a:link,a:visited,a:hover { color:#4C4C4C; text-decoration:none; }
	.nav { height: 32px; text-align:center; font-size:19px; padding:8px 10px 8px 0; }
	.nav .name {display:inline-block; height:30px; overflow:hidden; white-space:nowrap; width:50%;}
	.jump_c { padding:130px 25px; font-size:15px; }
	.grey { color:#A5A5A5; }
	.jump_c a { color:#2782BA; }
	.footer { text-align:center; line-height:2em; color:#A5A5A5; padding:10px 0 0 0; }
	.footer a { margin:0 6px; color:#A5A5A5; }
	.nav {
		position: relative;
		height: 44px;
		border-top-color: #4a4f57;
		border-top-style: solid;
		border-top-width: 1px;
		background: #353b44;
		padding: 0; /*reset default*/
	}
	.header-tit {
		display: block;
		height: 44px;
		line-height: 44px;
		font-size: 20px;
		font-weight: bold;
		color: #cfdae5;
		text-align: center;
	}
	.header-tit .name {
		display: block;
		width: auto;
		height: 44px;
		margin: 0 50px;
		padding: 0;
		color: inherit;
		text-shadow: 0 2px 3px rgba(0, 0, 0, 0.5);
		overflow: hidden;
		text-overflow: ellipsis;
		/*-webkit-mask: -webkit-gradient(linear, left 25%, left 75%, from(#000000), to(rgba(0, 0, 0, 0.6)));*/
	}
	.header-tit .name.name_narrow {
		margin: 0 100px;
	}
	.jump_c {
		color: #999;
		font-size: 18px;
		text-align: center;
	}

</style>
</head>
<body class="bg">
<header class="header">
    <div class="nav">
        <div class="header-tit">
            <span class="name"></span>
        </div>
        <div class="header-nav">
                          <a class="header-btn" href="javascript:history.back();">
            </a>
        </div>
    </div>
    </header>
<div class="jump_c">
<p style="padding-bottom: 60px;">$msg</p>
    <p><a class="jump_button" href="$url">[ 点击这里返回上一页 ]</a></p>
</div>
<div class="footer">
<p>©  Inc.</p>
</div>
</body></html>
JOT;
		exit ();
	}

}