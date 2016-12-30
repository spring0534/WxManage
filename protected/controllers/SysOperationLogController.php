<?php

/**
* SysOperationLogController.php
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2014-12-9
* 
*/
class SysOperationLogController extends BaseController{
	public function actionView($id){
		$this->render('view', array(
			'model'=>$this->loadModel($id)
		));
	}
	public function actionAdmin(){
		$model=new SysOperationLog('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['SysOperationLog']))
			$model->attributes=$_GET['SysOperationLog'];
		
		$this->render('admin', array(
			'model'=>$model
		));
	}
	public function actionIndex(){
		$model=new SysOperationLog('index');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['SysOperationLog']))
			$model->attributes=$_GET['SysOperationLog'];
	
		$this->render('index', array(
			'model'=>$model
		));
	}
	public function loadModel($id){
		$model=SysOperationLog::model()->findByPk($id);
		if ($model===null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}
	
	/**
	 * AJAX验证
	 * @param SysOperationLog $model
	 * 模型验证
	 */
	protected function performAjaxValidation($model){
		if (isset($_POST['ajax'])&&$_POST['ajax']==='sys-operation-log-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
