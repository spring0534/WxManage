<?php

/**
* BaseController.php
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2014-11-18
* 
*/
class BaseController extends BController{
	public $layout='main';
	protected $otherinfo;
	protected $otherwhere;
	//权限白名单
	private $access=array(
		'index/*',
		'default/index',
		'fileupload/*',
		'ueditor/imageUp'
	);
	function init(){
		yii::import('ext.Tree');
		parent::init();
		if (!user()){
			$this->redirect('/login');
			exit();
		}
		
		
	}
	protected function beforeAction($action){
		$this->checkflag($this->getAction()->id, $this->getId());
		try {
			$this->log();
		} catch (Exception $e) {
		}
		return parent::beforeAction($action);
	}
	
	/**
	 * 左边菜单
	 * @date: 2014-11-18
	 * @author : 佚名
	 * @return Ambigous <boolean, multitype:unknown , multitype:>
	 */
	public function leftmenu(){
		yii::import('ext.Tree');
		$tree=new Tree();
		$tree->init($this->getmenu('isshow=1 and '));

		foreach ($this->getTopMenu() as $kk=>$vv){

			$list[]=$tree->get_childlist($vv['id']);
		}
		return $list;
	}
	/**
	 * 顶部菜单
	 * @date: 2014-11-18
	 * @author : 佚名
	 */
	function getTopMenu(){
		$allwhere="";
		if (user()->groupid!=0){
			$flagarr=unserialize(Yii::app()->db->createCommand()
				->select('flagarr')
				->from('sys_usergroup')
				->where("id=".user()->groupid)
				->queryScalar());
			if ($flagarr==null){

				$this->error('您所在的用户 组没有任何权限，请叫超级管理员分配您所在的用户 组的权限！', $this->createUrl('/login/logout'), 8000);
			}
			$allwhere=' and id in('.implode(",", $flagarr).')';
		}
		$where['status']=1;
		$list=Yii::app()->db->createCommand()
		->select('*')
		->from('sys_user_menu')
		->where('status=1'.($allwhere ? $allwhere : '').' and pid=0')
		->order("listorder")
		->queryAll();
		return $list;

	}

	/**
	 * 得到权限内的全部菜单
	 * @date: 2014-11-18
	 * @author : 佚名
	 * @return unknown
	 */
	public function getmenu($sql=''){
		$allwhere="";
		if (user()->groupid!=0){
			$flagarr=unserialize(Yii::app()->db->createCommand()
				->select('flagarr')
				->from('sys_usergroup')
				->where("id=".user()->groupid)
				->queryScalar());
			if ($flagarr==null){

				$this->error('您所在的用户 组没有任何权限，请叫超级管理员分配您所在的用户 组的权限！', $this->createUrl('/login/logout'), 8000);
			}
			$allwhere=' and id in('.implode(",", $flagarr).')';
		}
		$list=Yii::app()->db->createCommand()
			->select('*')
			->from('sys_user_menu')
			->where($sql.'status=1'.($allwhere ? $allwhere : ''))
			->order("listorder")
			->queryAll();
		return $list;
	}
	/**
	 * 位置
	 * @date: 2014-10-10
	 * @author : 佚名
	 */
	public function actionPostion(){
		header("Content-type: text/html; charset=utf-8");
		$menuid=isset($_GET["menuid"]) ? $_GET["menuid"] : "";
		$array=Yii::app()->db->createCommand()
			->from('sys_user_menu')
			->order("listorder")
			->queryAll();
		yii::import('ext.Tree');
		$tree=new Tree();
		$tree->init($array);
		$tarr=array();
		$tarr=$tree->get_pid_all($menuid);
		// array_pop($tarr);
		krsort($tarr);
		echo (implode(" > ", $tarr));
	}
	/**
	 * 权限检查
	 * @date: 2014-11-18
	 * @author : 佚名
	 * @return boolean
	 */
	public function checkflag($action, $controller){
		if (user()->id==1)
			return true;
		if (in_array($controller.'/'.$action, $this->access))
			return true;
		if (in_array($controller.'/*', $this->access))
			return true;
		
		yii::import('ext.Tree');
		$tree=new Tree();
		$tree->init($this->getmenu());
		$tarr=$tree->getAll_childlist(0);
		$rules=array();
		$this->getRulelist($tarr, $rules);
		$moduleId=$this->module->id ? $this->module->id : '';
		if (in_array(strtolower(trim($moduleId.'/'.$controller.'/'.$action, '/')), $rules)==false){
			$this->error("您没有权限执行此操作！");
		}
	}
	function getRulelist($tarr, &$rules, $path=''){
		foreach ($tarr as $k=>$v){
			$rule=trim(str_replace("//", "/", $v['modelname']), '/');
			if (!empty($rule)&&!in_array($rule, (array) $rules))
				$rules[]=strtolower($rule);
			if ($v['sublist']){
				$this->getRulelist($v['sublist'], $rules, $path);
			}
		}
	}
	/**
	 * 记录操作日志
	 * @date: 2014-11-18
	 * @author : 佚名
	 */
	function log($msg=''){
		$info=array(
			"uid"=>Yii::app()->session['admin']['id'],
			'ghid'=>gh() ? gh()->ghid : '',
			"action"=>$this->getAction()->id,
			"controller"=>$this->getId(),
			'module'=>$this->getModule()->id,
			'message'=>$msg,
			'ip'=> Yii::app()->request->userHostAddress,
			"optime"=>time()
		);
		Yii::app()->db->createCommand()->insert('sys_operation_log', $info);
	}
	


}