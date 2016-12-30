<?php

/**公众账号
* SysUserGhController.php
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2014-12-9
* 
*/
class SysUserGhController extends BaseController{
	public function actionView($id){
		$this->render('view', array(
			'model'=>$this->loadModel($id)
		));
	}
	public function actionUpdate(){
		$model=SysUserGh::model()->findByAttributes(array(
			'ghid'=>gh()->ghid
		));
		if (isset($_POST['SysUserGh'])){
			
			// 不可更改下列
			unset($_POST['SysUserGh']['api_url']);
			unset($_POST['SysUserGh']['api_token']);
			unset($_POST['SysUserGh']['ghid']);
			$model->attributes=$_POST['SysUserGh'];
			$model->utm=date('Y-m-d H:i:s');
			if ($model->save())
				$this->success('操作成功');
		}
		
		$this->render('update', array(
			'model'=>$model
		));
	}
	function actionTranspond(){
		$model=SysUserGh::model()->findByAttributes(array(
			'ghid'=>gh()->ghid
		));
		if (isset($_POST['SysUserGh'])){
			
			// 不可更改下列
			unset($_POST['SysUserGh']['api_url']);
			unset($_POST['SysUserGh']['api_token']);
			unset($_POST['SysUserGh']['ghid']);
			$model->attributes=$_POST['SysUserGh'];
			$model->utm=date('Y-m-d H:i:s');
			if ($model->save())
				$this->success('操作成功');
		}
		
		$this->render('transpond', array(
			'model'=>$model
		));
	}
	public function actionInfo(){
		
		if (!gh()->ghid){
			if (!empty(user()->ghid)){
				$this->redirect(U('&update'));
				exit();
			}else{
				$model=new SysUserGh();
				if (isset($_POST['SysUserGh'])){
					
					$model->attributes=$_POST['SysUserGh'];
					if (SysUserGh::model()->find("ghid='".$model->ghid."'")){
						$this->error('该公众号已经添加过,不能再添加!');
					}
					$model->tenant_id=user()->id;
					$model->ctm=date('Y-m-d H:i:s');
					if ($model->save()){
						$model_user=user();
						$model_user->ghid=$model->ghid;
						yii::app()->session['admin']=$model_user;
						resetGh($model->ghid);
						$model_user->save();
						$this->success('操作成功', 'top_refresh');
					}else{
						
						
						$this->error('操作失败！'.getErrStr($model->errors));
					}
				}
				$this->render('info', array(
					'model'=>$model
				));
			}
		}else{
			$this->redirect(U('&update'));
			exit();
		}
	}
	/**
	 * 切换管理
	 * @date: 2014-12-17
	 * @author: 佚名
	 * @param unknown $ghid
	 */
	function actionSwitch($ghid){
		if(empty($ghid))$this->error('请先绑定公众号');
		if(user()->groupid==1||user()->groupid==2||user()->id==1||$ghid==user()->ghid){
			
		}else{
			//验证该商户是否在自己的管理范围内
			$row=Yii::app()->db->createCommand()
				->select('tenant_id')
				->from('sys_user_gh')
				->where("ghid='".$ghid."'")
				->queryRow();
			if(!$row)$this->error('参数错误');
			$gh_uid=$row['tenant_id'];
			$pid=user()->groupid==5?user()->mid:user()->id;
			$this->isMyUser($gh_uid, $pid);
		}
		resetGh($ghid); 
		$this->success('正在处理,请稍后...', 'top_refresh', 0.5);
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
	 * 一键绑定
	 * @date: 2014-12-10
	 * @author : 佚名
	 */
	function actionAccountOps(){
		$post=$_POST;
		if (!empty($post['uname'])&&!empty($post['pwd'])){
			yii::import('ext.Weixin');
			$wei=new Weixin();
			$loginstatus=$wei->account_weixin_login($post['uname'], md5($post['pwd']), $post['imgcode']);
			if (!$loginstatus)
				$this->error('操作失败！');
			if ($loginstatus&&is_array($loginstatus)){
				$this->error($loginstatus['err_msg'].'code:'.$loginstatus['code']);
			}
			$basicinfo=$wei->account_weixin_basic($post['uname']);
			$data['username']=$post['uname'];
			$data['password']=md5($post['pwd']);
			$data['lastupdate']=time();
			$data['company']=$basicinfo['name'];
			$data['name']=$basicinfo['account'];
			$data['ghid']=$basicinfo['original'];
			$data['desc']=$basicinfo['signature'];
			$data['notes']=$basicinfo['addr'];
			$data['appid']=$basicinfo['key'];
			$data['appsecret']=$basicinfo['secret'];
			if (!empty($basicinfo['headimg'])){
				$headimg=UPLOAD_PATH.'/download/images/'.'headimg_'.substr(md5($data['username']), 16).'.jpg';
				file_write($headimg, $basicinfo['headimg']);
				$data['headimg']='/download/images/'.'headimg_'.substr(md5($data['username']), 16).'.jpg';
			}
			if (!empty($basicinfo['qrcode'])){
				$qrcode=UPLOAD_PATH.'/download/images/'.'qrcode_'.substr(md5($data['username']), 16).'.jpg';
				file_write($qrcode, $basicinfo['qrcode']);
				$data['qrcode']='/download/images/'.'qrcode_'.substr(md5($data['username']), 16).'.jpg';
			}
			if (!empty($post['url'])&&!empty($post['token'])){
				$res=$wei->account_weixin_interface($post['uname'], $post['url'], $post['token']);
				$$data['config_interface_result']=$res;
			}
			if (SysUserGh::model()->find("ghid='".$data['ghid']."'")){
				$this->error('该公众号已经添加过,不能再添加!');
			}
			$model=new SysUserGh();
			$model->attributes=$data;
			$model->tenant_id=user()->id;
			$model->ctm=date('Y-m-d H:i:s');
			if ($model->save()){
				$model_user=user();
				$model_user->ghid=$data['ghid'];
				yii::app()->session['admin']=$model_user;
				resetGh($data['ghid']);
				$model_user->save();
				
				$this->success('操作成功', 'top_refresh');
			}else{
				$this->error('操作失败！');
			}
		}
		$this->error('操作失败,请手动添加.');
	}
	public function loadModel($id){
		$model=SysUserGh::model()->findByPk($id);
		if ($model===null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}
	public function actionAdmin(){
		$model=new SysUserGh('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['SysUserGh']))
			$model->attributes=$_GET['SysUserGh'];
		
		$this->render('admin', array(
			'model'=>$model
		));
	}
	/**
	 * AJAX验证
	 * @param SysUserGh $model
	 * 模型验证
	 */
	protected function performAjaxValidation($model){
		if (isset($_POST['ajax'])&&$_POST['ajax']==='sys-user-gh-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
