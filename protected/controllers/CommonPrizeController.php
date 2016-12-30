<?php
/**奖项管理（微应用公共模块）
* CommonPrizeController.php
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2014-12-16
* 
*/
class CommonPrizeController extends BaseController{
	public function actionView($id){
		$this->render('view', array(
			'model'=>$this->loadModel($id)
		));
	}
	public function actionCreate(){
		$model=new CommonPrize();
		if (isset($_POST['CommonPrize'])){
			$model->attributes=$_POST['CommonPrize'];
			if (Activity::model()->findByPk($model->aid)->ghid==gh()->ghid){
				$model->rate=floatval($model->rate);
				if ($model->save()){
					$this->success('添加成功',U('&admin',array('aid'=>$model->aid)));
				}else{
					$this->error('添加失败!');
				}
			}
		}
		
		$this->render('create', array(
			'model'=>$model,
			'aid'=>intval($_GET['aid'])
		));
	}
	public function actionUpdate($id){
		$model=$this->loadModel($id);
		if (isset($_POST['CommonPrize'])){
			$model->attributes=$_POST['CommonPrize'];
			if (Activity::model()->findByPk($model->aid)->ghid==gh()->ghid){
				if ($model->save()){
					$this->success('操作成功',U('&admin',array('aid'=>$model->aid)));
				}else{
					$this->error('更新失败!');
				}
			}
		}
		if (Activity::model()->findByPk($this->loadModel($id)->aid)->ghid==gh()->ghid){
			$this->render('update', array(
				'model'=>$model,
				'aid'=>$this->loadModel($id)->aid
			));
		}
	}
	public function actionDelete($id){
		if (Activity::model()->findByPk($this->loadModel($id)->aid)->ghid==gh()->ghid){
			$this->loadModel($id)->delete();
			if (!isset($_GET['ajax']))
				$this->success('操作成功');
		}
	}
	/*
	 * public function actionIndex(){
	 * $dataProvider=new CActiveDataProvider('CommonPrize');
	 * $this->render('index', array(
	 * 'dataProvider'=>$dataProvider
	 * ));
	 * }
	 */
	public function actionAdmin($aid){
		$aid=intval($_GET['aid']);
		if (empty($aid))
			$this->error('参数错误！');
		$m=Activity::model()->findByPk($aid);
		if ($m&&$m->ghid==gh()->ghid){
			$model=new CommonPrize('search');
			$model->unsetAttributes(); // clear any default values
			if (isset($_GET['CommonPrize']))
				$model->attributes=$_GET['CommonPrize'];
			
			$this->render('admin', array(
				'model'=>$model, 
				'aid'=>$aid
			));
		}
	}
	public function loadModel($id){
		$model=CommonPrize::model()->findByPk($id);
		if ($model===null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}
	
	/**
	 * AJAX验证
	 * @param CommonPrize $model
	 * 模型验证
	 */
	protected function performAjaxValidation($model){
		if (isset($_POST['ajax'])&&$_POST['ajax']==='common-prize-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
