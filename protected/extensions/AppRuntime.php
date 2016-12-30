<?php

/**
* 微应用机制核心类
* AppCore.php
* ----------------------------------------------
* 版权所有 2014-2015
* ----------------------------------------------
* @date: 2015-3-11
*
*/
class AppRuntime extends Controller{
	public $layout='/layouts/main';
	public $controllerMap=array();
	protected $_appName;
	protected $_controllerPath;
	protected $_controller;
	protected $controllerNamespace;
	function __construct(){
		parent::__construct($this->getAction(), $this->module);
		YiiBase::setPathOfAlias('microapp', Yii::getPathOfAlias('webroot').'/microapp/');
	}
	/**
	 * 重写redirect，使能在微应用中正常使用
	 * @see CController::redirect()
	 */
	function redirect($url, $terminate=true, $statusCode=302){
		parent::redirect(Yii::app()->baseUrl.'/'.$_GET['_akey'].'/'.ltrim($url, '/'), $terminate, $statusCode);
	}
	/**
	 * 获取控制器路径
	 * @date: 2014-9-16
	 * @author : 佚名
	 */
	public function getControllerPath(){
		return $this->_controllerPath=YiiBase::getPathOfAlias("microapp.".$this->_appName);
	}
	/**
	 * 运行微应用路径下的controller
	 * @date: 2014-9-16
	 * @author : 佚名
	 * @param url路径，pathInfo，支持路由，与YII中的用法一致 $route
	 * @param 当前活动信息 $activity
	 * @throws CHttpException
	 */
	public function runController($route,$activity,$config=''){
		if (($ca=$this->createController($route))!==null){
			list($controller,$actionID)=$ca;
			$oldController=$this->_controller;
			$this->_controller=$controller;
			if (!empty($config['endParticipation'])){
				if(strtotime($config['endParticipation'])>strtotime($activity['endtime'])){
					header("content-type:text/html; charset=utf-8");
					exit('活动停止参与时间必须小于活动结束时间！');
					//$this->log('活动停止参与时间配置错误,停止参与时间必须小于活动结束时间！');
					//$this->expire();//如果时间配置错误，则直接显示活动结束页面。
				}
				if (strtotime($config['endParticipation']) <= time()){
					$controller->discontinue=1;
				}
			}
			//$this->preRunController($controller);
			$controller->init();
			if(!empty($activity['endtime'])){
				if(strtotime($activity['endtime'])<time()){
					$controller->preEnd();
				}
			}
			if(empty($activity['status'])){
				//$this->log('活动状态不可用');
				//$this->expire();//活动状态不可用
				header("content-type:text/html; charset=utf-8");
				exit('活动状态不可用');
			}
			if(strtotime($activity['starttime'])>time()){
				$controller->preStart();
			}


			$controller->run($actionID);
			$this->_controller=$oldController;
		}else
			throw new CHttpException(404, Yii::t('yii', 'Unable to resolve the request "{route}".', array(
				'{route}'=>$route==='' ? $this->defaultController : $route
			)));
	}
	/**
	 * action参数处理
	 * @date: 2014-9-16
	 * @author : 佚名
	 * @param unknown_type $pathInfo
	 */
	protected function parseActionParams($pathInfo){
		if (($pos=strpos($pathInfo, '/'))!==false){
			$manager=Yii::app()->getUrlManager();
			$manager->parsePathInfo((string) substr($pathInfo, $pos+1));
			$actionID=substr($pathInfo, 0, $pos);
			return $manager->caseSensitive ? $actionID : strtolower($actionID);
		}else
			return $pathInfo;
	}
	/**
	 * 创建controller
	 * @date: 2014-9-16
	 * @author : 佚名
	 * @param uri $route
	 * @param
	 * $owner
	 */
	public function createController($route, $owner=null){
		if ($owner===null)
			$owner=$this;
		if (($route=trim($route, '/'))==='')
			$route=$owner->defaultController;
		$caseSensitive=Yii::app()->getUrlManager()->caseSensitive;
		$route.='/';
		while (($pos=strpos($route, '/'))!==false){
			$id=substr($route, 0, $pos);
			if (!preg_match('/^\w+$/', $id))
				return null;
			if (!$caseSensitive)
				$id=strtolower($id);
			$route=(string) substr($route, $pos+1);
			if (!isset($basePath)){
				if (isset($owner->controllerMap[$id])){
					return array(
						Yii::createComponent($owner->controllerMap[$id], $id, $owner===$this ? null : $owner),
						$this->parseActionParams($route)
					);
				}
				if (($module=$owner->getModule($id))!==null)
					return $this->createController($route, $module);
				$basePath=$owner->getControllerPath();
				$controllerID='';
			}else
				$controllerID.='/';
			$className=ucfirst($id).'Controller';
			$classFile=$basePath.DIRECTORY_SEPARATOR.$className.'.php';
			if ($owner->controllerNamespace!==null)
				$className=$owner->controllerNamespace.'\\'.$className;
			if (is_file($classFile)){
				if (!class_exists($className, false))
					require ($classFile);
				if (class_exists($className, false)&&is_subclass_of($className, 'CController')){
					$id[0]=strtolower($id[0]);
					return array(
						new $className($controllerID.$id, $owner===$this ? null : $owner),
						$this->parseActionParams($route)
					);
				}
				return null;
			}
			$controllerID.=$id;

			$basePath.=DIRECTORY_SEPARATOR.$id;
		}
	}
}