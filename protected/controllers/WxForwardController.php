<?php

/**
* WxForwardController.php 微信微应用,第三方接口
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2014-12-1
* 
*/
class WxForwardController extends BaseGhController{
	public function actionView($id){
		$this->render('view', array(
			'model'=>$this->loadModel($id)
		));
	}
	public function actionCreate(){
		$model=new WxForward();

		// $this->performAjaxValidation($model);

		if (isset($_POST['WxForward'])){
			$model->attributes=$_POST['WxForward'];
			if($model->ghid!='public')$model->ghid=gh()->ghid;
			
			if ($model->save())
				$this->success('添加成功',U('&admin'));
		}

		$this->render('create', array(
			'model'=>$model
		));
	}
	public function actionUpdate($id){
		$model=WxForward::model()->findByAttributes(array('ghid'=>gh()->ghid,'id'=>$id));
		if(!$model)$this->error('非法操作！');
		// $this->performAjaxValidation($model);
		if (isset($_POST['WxForward'])){
			$model->attributes=$_POST['WxForward'];
			if ($model->save())
				$this->success('操作成功',U('&admin'));
		}

		$this->render('update', array(
			'model'=>$model
		));
	}
	public function actionDelete($id){
		$model=WxForward::model()->findByAttributes(array('ghid'=>gh()->ghid,'id'=>$id));
		if(!$model)$this->error('非法操作！');
		$this->loadModel($model->id)->delete();
		// 如果是AJAX请求删除,请取消跳转
		if (!isset($_GET['ajax']))
			$this->success('操作成功');
	}
	public function actionIndex(){
		$dataProvider=new CActiveDataProvider('WxForward');
		$this->render('index', array(
			'dataProvider'=>$dataProvider
		));
	}
	public function actionAdmin(){
		$model=new WxForward('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['WxForward']))
			$model->attributes=$_GET['WxForward'];

		$this->render('admin', array(
			'model'=>$model
		));
	}
	public function loadModel($id){
		$model=WxForward::model()->findByPk($id);
		if ($model===null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * AJAX验证
	 * @param WxForward $model
	 * 模型验证
	 */
	protected function performAjaxValidation($model){
		if (isset($_POST['ajax'])&&$_POST['ajax']==='wx-forward-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
