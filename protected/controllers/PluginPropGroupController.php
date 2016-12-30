<?php
/**
* PluginPropGroupController.php 微应用属性分组
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2015-6-26
* 
*/
class PluginPropGroupController extends BaseController{
	public function actionView($id){
		$this->render('view', array(
			'model'=>$this->loadModel($id)
		));
	}
	public function actionCreate(){
		$model=new PluginPropGroup();
		
		// $this->performAjaxValidation($model);
		
		if (isset($_POST['PluginPropGroup'])){
			$model->attributes=$_POST['PluginPropGroup'];
			if ($model->save()){
				$this->success('添加成功',$this->createUrl('admin',array('ptype'=>$model->ptype)));
			}else{
				$this->error(getErrStr($model->errors));
			}
		}
		
		$this->render('create', array(
			'model'=>$model,
			'ptype'=>$_GET['ptype']
		));
	}
	public function actionUpdate($id){
		$model=$this->loadModel($id);
		// $this->performAjaxValidation($model);
		if (isset($_POST['PluginPropGroup'])){
			$model->attributes=$_POST['PluginPropGroup'];
			if ($model->save()){
				$this->success('操作成功',$this->createUrl('admin',array('ptype'=>$model->ptype)));
			}else{
				$this->error(getErrStr($model->errors));
			}
		}
		
		$this->render('update', array(
			'model'=>$model,
			'ptype'=>$model->ptype
		));
	}
	public function actionDelete($id){
		$this->loadModel($id)->delete();
		// 如果是AJAX请求删除,请取消跳转
		if (!isset($_GET['ajax']))
			$this->success('操作成功');
	}
	public function actionAdmin(){
		$model=new PluginPropGroup('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['PluginPropGroup']))
			$model->attributes=$_GET['PluginPropGroup'];
		
		$this->render('admin', array(
			'model'=>$model
		));
	}
	public function loadModel($id){
		$model=PluginPropGroup::model()->findByPk($id);
		if ($model===null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}
	
	/**
	 * AJAX验证
	 * @param PluginPropGroup $model
	 * 模型验证
	 */
	protected function performAjaxValidation($model){
		if (isset($_POST['ajax'])&&$_POST['ajax']==='plugin-prop-group-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
