<?php

/**微应用主题管理
* PluginThemeController.php
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2014-12-10
* 
*/
class PluginThemeController extends BaseController{
	public function actionView($id){
		$this->render('view', array(
			'model'=>$this->loadModel($id)
		));
	}
	public function actionCreate(){
		$model=new PluginTheme();
		
		if (isset($_POST['PluginTheme'])){
			$model->attributes=$_POST['PluginTheme'];
			if ($model->save())
				$this->success('添加成功');
		}
		$this->render('create', array(
			'model'=>$model,
			'ptype'=>$_GET['ptype']
		));
	}
	public function actionUpdate($id){
		$model=$this->loadModel($id);
		
		if (isset($_POST['PluginTheme'])){
			$ptype=$model->ptype;
			$model->attributes=$_POST['PluginTheme'];
			$model->ptype=$ptype;
			if ($model->save())
				$this->success('操作成功');
		}
		
		$this->render('update', array(
			'model'=>$model,
			'ptype'=>$model->ptype
		));
	}
	public function actionDelete($id){
		$this->loadModel($id)->delete();
		if (!isset($_GET['ajax']))
			$this->success('操作成功');
	}
	public function actionIndex(){
		$dataProvider=new CActiveDataProvider('PluginTheme');
		$this->render('index', array(
			'dataProvider'=>$dataProvider
		));
	}
	public function actionAdmin(){
		$model=new PluginTheme('search');
		$model->unsetAttributes(); 
		if (isset($_GET['PluginTheme']))
			$model->attributes=$_GET['PluginTheme'];
		if (empty($model->ptype))
			$this->error('参数错误');
		$this->render('admin', array(
			'model'=>$model
		));
	}
	public function loadModel($id){
		$model=PluginTheme::model()->findByPk($id);
		if ($model===null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}
	
	/**
	 * AJAX验证
	 * @param PluginTheme $model
	 * 模型验证
	 */
	protected function performAjaxValidation($model){
		if (isset($_POST['ajax'])&&$_POST['ajax']==='plugin-theme-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
