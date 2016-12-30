<?php

class SysuserController extends BaseController{
	
	/**
	 *
	 * @return array action filters
	 */
	/*
	 * public function filters()
	 * {
	 * return array(
	 * 'accessControl', // perform access control for CRUD operations
	 * 'postOnly + delete', // we only allow deletion via POST request
	 * );
	 * }
	 */
	
	/**
	 * Displays a particular model.
	 * @param integer $id
	 * the ID of the model to be displayed
	 */
	/*
	 * public function actionView($id)
	 * {
	 * $this->render('view',array(
	 * 'model'=>$this->loadModel($id),
	 * ));
	 * }
	 */
	function getErrStr($errs=array()){
		foreach ($errs as $kk=>$vv){
			$str.=$vv[0];
		}
		return $str;
	}
	/**
	 * 添加用户
	 * @date: 2015-3-5
	 * @author : 佚名
	 */
	public function actionCreate(){
		$model=new SysUser();
		if (isset($_POST['SysUser'])){
			$model->attributes=$_POST['SysUser'];
			$model->password=$model->hashPassword($model->password);
			if ($model->save())
				$this->success('添加成功!', $this->createUrl('index'));
			    $this->error('操作失败！'.$this->getErrStr($model->errors));
		}
		
		$this->render('create', array(
			'model'=>$model,
			'title'=>'添加用户',
			'form'=>'_form'
		));
	}
	/**
	 * 更新用户
	 * @date: 2015-3-5
	 * @author : 佚名
	 * @param 用户 id $id
	 */
	public function actionUpdate($id){
		if ($id==1&&user()->id!=1){
			$this->error('拒绝操作！');
		}
		$model=$this->loadModel($id);
		if (isset($_POST['SysUser'])){
			$oldpwd=$model->password;
			if (!empty($_POST['SysUser']['username']))
				$this->error('access');
			$model->attributes=$_POST['SysUser'];
			if (empty($_POST['SysUser']['password'])){
				$model->password=$oldpwd;
			}else{
				if ($_POST['SysUser']['password']!=$_POST['info']['pwdagain']){
					$this->error('两次输入的密码不一样！');
				}
				$model->password=$model->hashPassword($model->password);
			}
			if ($model->save())
				$this->success('更新成功!');
		}
		$this->render('update', array(
			'model'=>$model
		));
	}
	
	/**
	 * 添加商户
	 * @date: 2015-3-5
	 * @author : 佚名
	 */
	public function actionCreateMerchant(){
		if(user()->groupid!=3){
			$this->error('您不是代理商，无权操作！');
		}
		$model=new SysUser();
		if (isset($_POST['SysUser'])){
			$model->attributes=$_POST['SysUser'];
			$model->password=$model->hashPassword($model->password);
			$model->pid=user()->id;
			$model->ghid='';
			$model->groupid=4;
			if ($model->save()){
				$this->redirect(U('&bindingGh/uid/'.$model->attributes['id']));
			}else{
				
				$this->error('保存失败！'.$this->getErrStr($model->errors));
			}
		}
		$this->render('merchant_form', array(
			'model'=>$model
		));
	}
	
	/**
	 * 商户基本信息更新
	 * @date: 2015-3-9
	 * @author: 佚名
	 */
	public function actionUpdateMerchant($id){
		if(user()->groupid!=3){
			$this->error('您不是代理商，无权操作！');
		}
		if ($id==1)
			$this->error('access');
		$model=$this->loadModel($id);
		$pid=$model->pid;
		$ghid=$model->ghid;
		$groupid=$model->groupid;
		if(empty($model))$this->error('参数错误!');
		$this->isMyUser($model->id,user()->id);
		if (isset($_POST['SysUser'])){
			$oldpwd=$model->password;
			if (!empty($_POST['SysUser']['username']))
				$this->error('access');
			$model->attributes=$_POST['SysUser'];
			if (empty($_POST['SysUser']['password'])){
				$model->password=$oldpwd;
			}else{
				if ($_POST['SysUser']['password']!=$_POST['info']['pwdagain']){
					$this->error('两次输入的密码不一样！');
				}
				$model->password=$model->hashPassword($model->password);
			}
			$model->pid=$pid;
			$model->ghid=$ghid;
			$model->groupid=$groupid;
			if ($model->save())
				$this->success('更新成功!', $this->createUrl('merchants'));
		}
	
		$this->render('merchant_form', array(
			'model'=>$model
		));
	}
	/**
	 * 商户公众号绑定
	 * @date: 2015-3-9
	 * @author: 佚名
	 */
	public function actionBindingGh(){
		$uid=intval($_GET['uid']);
		if(empty($uid))$this->error('参数错误！');
		
		$user=SysUser::model()->findByPk($uid);
		if(empty($user))$this->error('参数错误!');
		$this->isMyUser($user->id,user()->id);

		$model=new SysUserGh();
		if (isset($_POST['SysUserGh'])){
			$model->attributes=$_POST['SysUserGh'];
			if (SysUserGh::model()->find("ghid='".$model->ghid."'")){
				$this->error('该公众号已经添加过,不能再添加!');
			}
			$model->tenant_id=$uid;
			$model->ctm=date('Y-m-d H:i:s');
			if ($model->save()){
				/*$model_user=user();
				$model_user->ghid=$model->ghid;
				$model_user->save();*/
				$user->ghid=$model->ghid;
				$user->save();
				$this->success('操作成功', $this->createUrl('merchants'));
			}else{
			}
		}
		if(empty($user)||$user->pid!=user()->id){
			$this->error('参数错误!');
		}
		$this->render('info', array(
			'model'=>$model
		));
			
	}
	/**
	 * 商户公众号更新
	 * @date: 2015-3-9
	 * @author: 佚名
	 * @param 用户id $uid
	 */
	public function actionUpdateInfo($uid){
		$uid=intval($uid);
		if(empty($uid))$this->error('参数错误！');
		$user=$this->loadModel($uid);
		if(empty($user))$this->error('参数错误!');
		$this->isMyUser($user->id,user()->id);
		$model=SysUserGh::model()->findByAttributes(array('tenant_id'=>$user->id));
		if(empty($model))$this->error('参数错误!');
		
		if(empty($model->ghid)){
			$this->redirect(U('&bindingGh/uid/'.$model->id));
			exit();
		}
		if (isset($_POST['SysUserGh'])){
			$model->attributes=$_POST['SysUserGh'];
			// 不可更改下列
			unset($_POST['SysUserGh']['api_url']);
			unset($_POST['SysUserGh']['api_token']);
			unset($_POST['SysUserGh']['ghid']);
			$model->utm=date('Y-m-d H:i:s');
			if ($model->save())
				$this->success('操作成功',$this->createUrl('merchants'));
		}
	
		$this->render('update_info', array(
			'model'=>$model
		));
	}
	/**
	 * 获取所有的父ID
	 * @date: 2015-3-9
	 * @author: 佚名
	 * @param 用户 ID $uid
	 * @param array $pids
	 * @return array
	 */
	function getPids($uid,&$pids){
		$vo=Yii::app()->db->createCommand()
		->select('id,pid,mid')
		->from('sys_user')
		->where('id='.$uid)
		->queryRow();
		
		if($vo['pid']>0){
			$pids[]=$vo['pid'];
			$this->getPids($vo['pid'], $pids);
		}
		return $pids;
	}
	/**
	 * 判断用户是否为自己的子用户（包括子用户的子用户），并作权限判断
	 * @date: 2015-3-9
	 * @author: 佚名
	 * @param  $model_user
	 */
	function isMyUser($uid,$myId){
		$pids=$this->getPids($uid, $pids);
		if(empty($pids))$this->error('参数错误！');
		if(!in_array($myId, $pids)){
			$this->error('无权限操作！');
		}
	}
	/**
	 * 添加客服
	 * @date: 2015-3-5
	 * @author : 佚名
	 */
	public function actionCreateCustomer(){
		$model=new SysUser();
		if (isset($_POST['SysUser'])){
			$model->attributes=$_POST['SysUser'];
			$model->password=$model->hashPassword($model->password);
			$model->mid=user()->id;
			$model->pid=user()->id;
			$model->ghid='';
			$model->groupid=5;
			if ($model->save())
				$this->success('添加成功!', $this->createUrl('merchants'));
			$this->error('操作失败！'.$this->getErrStr($model->errors));
		}
		
		$this->render('customer_form', array(
			'model'=>$model
		));
	}
	/**
	 * 编辑客服
	 * @date: 2015-3-5
	 * @author : 佚名
	 */
	public function actionUpdateCustomer($id){
		$uid=intval($id);
		if(empty($uid))$this->error('参数错误！');
		$model=SysUser::model()->findByPk($uid);
		if(empty($model))$this->error('参数错误!');
		$this->isMyUser($model->id,user()->id);
		$mid=$model->mid;
		$pid=$model->pid;
		$ghid=$model->ghid;
		$groupid=$model->groupid;
		if (isset($_POST['SysUser'])){
			$model->attributes=$_POST['SysUser'];
			$model->password=$model->hashPassword($model->password);
			$model->mid=$mid;
			$model->pid=$pid;
			$model->ghid=$ghid;
			$model->groupid=$groupid;
			if ($model->save())
				$this->success('操作成功',$this->createUrl('customer'));
		}
		
		$this->render('update_customer', array(
			'model'=>$model
		));
	}
	/**
	 * 删除
	 * @date: 2015-3-5
	 * @author : 佚名
	 * @param unknown $id
	 */
	public function actionDeleteCustomer($id){
		$uid=intval($id);
		if(empty($id))$this->error('参数错误！');
		$model=SysUser::model()->findByPk($uid);
		if(empty($model))$this->error('参数错误!');
		$this->isMyUser($model->id,user()->id);
		$this->loadModel($id)->delete();
		if (!isset($_GET['ajax']))
			$this->success('删除成功!');
	}
	/**
	 * 添加下级代理
	 * @date: 2015-3-5
	 * @author : 佚名
	 */
	public function actionCreateAgent(){
		if(user()->groupid!=3){
			$this->error('您不是代理，无权操作！');
		}
		$m_level=user()->m_level+1;
		if($m_level>=5){
			$this->error('由于级别限制，你不能再开代理！');
		}
		$model=new SysUser();
		if (isset($_POST['SysUser'])){
			$model->attributes=$_POST['SysUser'];
			$model->password=$model->hashPassword($model->password);
			$model->mid=user()->id;
			$model->pid=user()->id;
			$model->ghid='';
			$model->groupid=3;
			$model->m_level=$m_level;
			if ($model->save())
				$this->success('添加成功!', $this->createUrl('merchants'));
			$this->error('操作失败！'.$this->getErrStr($model->errors));
		}
		$this->render('agent_form', array(
			'model'=>$model
		));
	}
	/**
	 * 更新代理
	 * @date: 2015-3-9
	 * @author: 佚名
	 * @param unknown $uid
	 */
	public function actionUpdateAgent($id){
		if(user()->groupid!=3){
			$this->error('您不是代理，无权操作！');
		}
		$uid=intval($id);
		if(empty($uid))$this->error('参数错误！');
		$model=SysUser::model()->findByPk($uid);
		if(empty($model))$this->error('参数错误!');
		$this->isMyUser($model->id,user()->id);
		$mid=$model->mid;
		$pid=$model->pid;
		$ghid=$model->ghid;
		$groupid=$model->groupid;
		if (isset($_POST['SysUser'])){
			$model->attributes=$_POST['SysUser'];
			$model->password=$model->hashPassword($model->password);
			$model->mid=$mid;
			$model->pid=$pid;
			$model->ghid=$ghid;
			$model->groupid=$groupid;
			if ($model->save())
				$this->success('操作成功',$this->createUrl('agent'));
		}
	
		$this->render('update_agent', array(
			'model'=>$model
		));
	}
	/**
	 * 模拟登录
	 * @date: 2015-3-9
	 * @author: 佚名
	 * @param  $uid
	 */
	function actionSwitchLogin($id){
		$user=SysUser::model()->findByPk($id);
		if (!empty($user)){
				$this->isMyUser($user->id, user()->id);
				
				$user->last_login_time=time();
				$user->last_login_ip=Yii::app()->request->userHostAddress;
				$user->login_count=$user->login_count+1;
				$user->save();
				yii::app()->session['admin']=$user;
				yii::app()->session['gh']=SysUserGh::model()->find("ghid='".$user->ghid."'");
				//cookie('admin_local', null);
				$this->success('正在登录,请稍后...', 'top_refresh', 0.5);
			
		}
		
	}
	/**
	 * 删除
	 * @date: 2015-3-5
	 * @author : 佚名
	 * @param unknown $id 
	 */
	public function actionDelete($id){
		$this->loadModel($id)->delete();
		if (!isset($_GET['ajax']))
			$this->success('删除成功!');
	}
	
	/**
	 * Lists all models.
	 */
	/*
	 * public function actionIndex()
	 * {
	 * $dataProvider=new CActiveDataProvider('SysUser');
	 * $this->render('index',array(
	 * 'dataProvider'=>$dataProvider,
	 * ));
	 * }
	 */
	function getGroupName($id){
		return SysUsergroup::model()->findByPk($id)->groupname;
	}
	/**
	 * 所有用户 管理
	 * @date: 2015-3-9
	 * @author: 佚名
	 */
	public function actionAdmin(){
		$model=new SysUser('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['SysUser']))
			$model->attributes=$_GET['SysUser'];
		
		$this->render('admin', array(
			'model'=>$model
		));
	}
	/**
	 * 商户管理
	 * @date: 2015-3-9
	 * @author: 佚名
	 */
	public function actionMerchants(){
		$model=new SysUser('search_merchants');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['SysUser']))
			$model->attributes=$_GET['SysUser'];
		
		$this->render('merchants', array(
			
			'model'=>$model
		));
	}
	/**
	 * 批量开通微应用
	 * @date: 2015-5-14
	 * @author: 佚名
	 */
	function actionBatchOpen(){
		
		
		$model=new SysUser('search_merchants_open');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['SysUser']))
			$model->attributes=$_GET['SysUser'];
		$m=$model->search_merchants_open(100);
		$data=$m->data;
		$pages['pages']=$m->getPagination();
		$this->render('batch_open', array(
			'model'=>$model,
			'data'=>$data,
			'pages'=>$pages
		));
		
		
	}
	function actionBatchClone(){
		$model=new SysUser('search_merchants_open');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['SysUser']))
			$model->attributes=$_GET['SysUser'];
		$m=$model->search_merchants_open(100);
		$data=$m->data;
		$pages['pages']=$m->getPagination();
		$this->render('batch_clone', array(
			'model'=>$model,
			'data'=>$data,
			'pages'=>$pages
		));
	
	
	}
	
	function actionTemp(){
		//批量更新关注链接
		/*set_time_limit(0);
		$model=new SysUser('search_merchants_open');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['SysUser']))
			$model->attributes=$_GET['SysUser'];
		$m=$model->search_merchants_open(500);
		$data=$m->data;
		foreach ($data as $k=>$v){
			$alist=Yii::app()->db->createCommand()
					->select('*')
					->from('activity')
					->where("ghid='".$v->ghid."'")
					->order('aid asc')
					->queryAll();
			
			if(!empty($alist)&&count($alist)>1){
				$command = Yii::app()->db->createCommand("update activity_settings  set propvalue = (  select propvalue from (   select * from activity_settings   ) as x   where aid=".$alist[0]['aid']." and propname='attentionUrl') where aid=".$alist[1]['aid']." and propname='attentionUrl'");
				$command->query();
				
			}
			//dump($alist);
		}
		$this->success('操作成功！');*/
	}
	/**
	 * 获取已开通微应用列表
	 * @date: 2014-12-10
	 * @author : 佚名
	 * @return
	 *
	 */
	function getActList($selectAll=false){
		$criteria=new CDbCriteria();
		// $criteria->select='id,ptype';
		$criteria->with='plugin';
		if(!$selectAll){
			$criteria->addCondition("t.ghid='".gh()->ghid."'"); // 公众号开通的
			$criteria->addCondition('t.status=1'); // 可用
			$criteria->addCondition("t.starttime<'".date('Y-m-d H:i:s'."'")); //
			$criteria->addCondition("t.endtime>'".date('Y-m-d H:i:s'."'")); // 未过期
		}
		// dump(PluginGhUse::model()->findAll($criteria));exit;
		return PluginGhUse::model()->findAll($criteria); // $params is not needed
	}
	/**
	 * 客服管理
	 * @date: 2015-3-9
	 * @author: 佚名
	 */
	public function actionCustomer(){
		$model=new SysUser('search_customer');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['SysUser']))
			$model->attributes=$_GET['SysUser'];
	
		$this->render('customer', array(
				
			'model'=>$model
		));
	}
	/**
	 * 代理商管理
	 * @date: 2015-3-9
	 * @author: 佚名
	 */
	public function actionAgent(){
		$model=new SysUser('search_agent');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['SysUser']))
			$model->attributes=$_GET['SysUser'];
	
		$this->render('agent', array(
				
			'model'=>$model
		));
	}

	public function loadModel($id){
		$model=SysUser::model()->findByPk($id);
		if ($model===null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}
	
	protected function performAjaxValidation($model){
		if (isset($_POST['ajax'])&&$_POST['ajax']==='sys-user-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
