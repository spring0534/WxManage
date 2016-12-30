<?php

/**
* WxRouterController.php
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2014-12-9
* 
*/
class WxRouterController extends BaseGhController{
	public function actionView($id){
		$this->render('view', array(
			'model'=>$this->loadModel($id)
		));
	}
	public function actionCreate(){
		$model=new WxRouter();

		// $this->performAjaxValidation($model);

		if (isset($_POST['WxRouter'])){
			$model->attributes=$_POST['WxRouter'];
			if ($model->save())
				$this->success('添加成功');
		}

		$this->render('create', array(
			'model'=>$model
		));
	}
	public function actionUpdate($id){
		$model=$this->loadModel($id);
		// $this->performAjaxValidation($model);
		if (isset($_POST['WxRouter'])){
			$model->attributes=$_POST['WxRouter'];
			if ($model->save())
				$this->success('操作成功');
		}

		$this->render('update', array(
			'model'=>$model
		));
	}
	public function actionDelete($id){
		$this->loadModel($id)->delete();
		// 如果是AJAX请求删除,请取消跳转
		if (!isset($_GET['ajax']))
			$this->success('操作成功');
	}
	public function actionIndex(){
		$dataProvider=new CActiveDataProvider('WxRouter');
		$this->render('index', array(
			'dataProvider'=>$dataProvider
		));
	}
	public function actionAdmin(){
		$model=new WxRouter('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['WxRouter']))
			$model->attributes=$_GET['WxRouter'];

		$this->render('admin', array(
			'model'=>$model
		));
	}
	public function loadModel($id){
		$model=WxRouter::model()->findByPk($id);
		if ($model===null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * AJAX验证
	 * @param WxRouter $model
	 * 模型验证
	 */
	protected function performAjaxValidation($model){
		if (isset($_POST['ajax'])&&$_POST['ajax']==='wx-router-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
