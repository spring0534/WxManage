<?php

/**
 * 微信统计
* StatWxController.php
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2015-4-7
* 
*/
class StatWxController extends BaseGhController{
	function actionSub(){
		$model=new StatWx('search');
		$model->unsetAttributes();
		if (isset($_GET['StatWx']))
			$model->attributes=$_GET['StatWx'];
		$where="";
		if (!empty($_GET['StatWx']['day'][1]))
			$where.=" and day>='".$_GET['StatWx']['day'][1]."'";
		if (!empty($_GET['StatWx']['day'][2]))
			$where.=" and day<='".$_GET['StatWx']['day'][2]."'";
		if (empty($_GET['StatWx']['day'][1])&&empty($_GET['StatWx']['day'][2])){
			$where.=" and day>='".date('Y-m-d', strtotime("-7 day"))."'";
			$where.=" and day<='".date('Y-m-d')."'";
		}
		$field=$_GET['type']=='unsub' ? 'unsub' : 'sub';
		$data1=Yii::app()->db->createCommand()
			->select("*")
			->from('stat_wx')
			->where("ghid='".gh()->ghid."' $where ")
			->order('day desc')
			->queryAll();
		$this->render('sub', array(
			'field'=>$field, 
			'data1'=>$data1, 
			'model'=>$model, 
			'r1'=>$_GET['StatWx']['day'][1], 
			'r2'=>$_GET['StatWx']['day'][2]
		));
	}
	function actionMsg(){
		$model=new StatWx('search');
		$model->unsetAttributes();
		if (isset($_GET['StatWx']))
			$model->attributes=$_GET['StatWx'];
		$where="";
		if (!empty($_GET['StatWx']['day'][1]))
			$where.=" and day>='".$_GET['StatWx']['day'][1]."'";
		if (!empty($_GET['StatWx']['day'][2]))
			$where.=" and day<='".$_GET['StatWx']['day'][2]."'";
		if (empty($_GET['StatWx']['day'][1])&&empty($_GET['StatWx']['day'][2])){
			$where.=" and day>='".date('Y-m-d', strtotime("-7 day"))."'";
			$where.=" and day<='".date('Y-m-d')."'";
		}
		$field=$_GET['type']=='send' ? 'send' : 'receive';
		$data1=Yii::app()->db->createCommand()
		->select("*")
		->from('stat_wx')
		->where("ghid='".gh()->ghid."' $where ")
		->order('day desc')
		->queryAll();
		$this->render('msg', array(
			'field'=>$field,
			'data1'=>$data1,
			'model'=>$model,
			'r1'=>$_GET['StatWx']['day'][1],
			'r2'=>$_GET['StatWx']['day'][2]
		));
	}
	/*public function actionAdmin(){
		$model=new StatWx('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['StatWx']))
			$model->attributes=$_GET['StatWx'];
		
		$this->render('admin', array(
			'model'=>$model
		));
	}*/
	public function loadModel($id){
		$model=StatWx::model()->findByPk($id);
		if ($model===null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}
	
	/**
	 * AJAX验证
	 * @param StatWx $model
	 * 模型验证
	 */
	protected function performAjaxValidation($model){
		if (isset($_POST['ajax'])&&$_POST['ajax']==='stat-wx-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
