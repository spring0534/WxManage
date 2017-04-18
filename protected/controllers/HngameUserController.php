<?php

/**
* RedpackController.php
* ----------------------------------------------
* 版权所有 2014-2016
* ----------------------------------------------
* @date: 2016-12-5
*
*/
class HngameUserController extends BaseController{
	public function actionIndex(){
		$model=new HngameUser('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['HngameUser']))
			$model->attributes=$_GET['HngameUser'];
		$this->render('index', array(
			'model'=>$model,
		));
	}
	
	public function actionUpdate($id){
		$model=$this->loadModel($id);
		if (isset($_POST['HngameUser'])){
			$model->attributes=$_POST['HngameUser'];
			if ($model->save())
				$this->success('更新成功!');
		}
		$this->render('update', array(
				'model'=>$model
		));
	}
	
	public function actionDelete($id){
		$this->loadModel($id)->delete();
		if (!isset($_GET['ajax']))
			$this->success('删除成功!');
	}
	
	function json_result($msg='',$code=-1,$result=''){
		echo json_encode(array('result'=>$result,'code'=>$code,'msg'=>$msg));exit;
	}
	
	public function loadModel($id){
		$model=HngameUser::model()->findByPk($id);
		if ($model===null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * AJAX验证
	 * @param Activity $model
	 * 模型验证
	 */
	protected function performAjaxValidation($model){
		if (isset($_POST['ajax'])&&$_POST['ajax']==='activity-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionView($id){
		$this->render('view', array(
			'model'=>$this->loadModel($id)
		));
	}
}
