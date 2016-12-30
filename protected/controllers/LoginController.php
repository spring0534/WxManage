<?php

/**用户 登录模块
* LoginController.php
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2014-12-10
* 
*/
class LoginController extends BController{
	function isAjax(){
		return isset($_SERVER ['HTTP_X_REQUESTED_WITH']) && $_SERVER ['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
	}
	public function actionIndex(){
		// 要求crypt的支持
		if (!defined('CRYPT_BLOWFISH')||!CRYPT_BLOWFISH)
			throw new CHttpException(500, "This application requires that PHP was compiled with Blowfish support for crypt().");

		$model=new SysUser();
		if (isset($_POST['SysUser'])){
			$model->attributes=$_POST['SysUser'];
			$model->rememberMe=$_POST['SysUser']['rememberMe'];
			if ($model->login()){
				//$this->success('登录成功！',WEB_URL,0.3);
				$config=get_websetup_config();
				if($config['webclose']==0&&user()->id!=1){
					if($this->isAjax()){
						exit(json_encode(array('code'=>'ajaxerror','msg'=>'系统正在维护中，请稍后再登录！')));
					}else{
						$this->error('系统正在维护中，请稍后再登录！');//网站关闭后仅超级管理员可登录
					}

				}
				if($this->isAjax()){
					exit(json_encode(array('code'=>'ajaxok','msg'=>'success')));
				}else{
					var_dump($_POST['SysUser']);
					exit();
					$this->redirect('/');
				}


			}else{
				if($this->isAjax()){
					exit(json_encode(array('code'=>'ajaxerror','msg'=>'用户名或者密码错误!')));
				}else{
					$this->render('index', array(
						'model'=>$model,
						'error'=>1
					));
					exit();
				}
			}
		}
		$saveData=cookie('admin_local');
		if($saveData){
			$model->username=$saveData['username'];
			$model->password=authcode($saveData['password'],'DECODE');
			$model->rememberMe=1;
		}
		
		$this->render('index', array(
			'model'=>$model

		));
	}
	/**
	 * 退出操作
	 * @date: 2014-12-10
	 * @author : 佚名
	 */
	function actionLogout(){
		Yii::app()->session['admin']=null;
		yii::app()->session['gh']=null;
		$this->redirect('/login');
	}
}