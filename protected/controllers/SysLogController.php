<?php

class SysLogController extends BaseController
{
	

	/**
	 * @return array action filters
	 */
	/*public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}
	*/
	

	
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	
	public function actionCreate()
	{
		/*$model=new SysLog;

		// $this->performAjaxValidation($model);

		if(isset($_POST['SysLog']))
		{
			$model->attributes=$_POST['SysLog'];
			if($model->save())
				$this->success('添加成功');
		}

		$this->render('create',array(
			'model'=>$model,
		));*/
	}


	public function actionUpdate($id)
	{
		/*$model=$this->loadModel($id);
		// $this->performAjaxValidation($model);
		if(isset($_POST['SysLog']))
		{
			$model->attributes=$_POST['SysLog'];
			if($model->save())
				$this->success('操作成功');
		}

		$this->render('update',array(
			'model'=>$model,
		));*/
	}


	public function actionDelete($id)
	{
		/*$this->loadModel($id)->delete();
		// 如果是AJAX请求删除,请取消跳转
		if(!isset($_GET['ajax']))
			$this->success('操作成功');*/
	}

	
	public function actionIndex()
	{
		/*$dataProvider=new CActiveDataProvider('SysLog');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));*/
	}

	
	public function actionAdmin()
	{
		$model=new SysLog('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SysLog']))
			$model->attributes=$_GET['SysLog'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	
	public function loadModel($id)
	{
		$model=SysLog::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * AJAX验证
	 * @param SysLog $model 模型验证
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='sys-log-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
