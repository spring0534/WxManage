<?php

/**自动回复
* WxRouterKeywordController.php
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2014-12-9
* 
*/
class WxRouterKeywordController extends BaseGhController{
	public function actionView($id){
		$this->render('view', array(
			'model'=>$this->loadModel($id)
		));
	}
	public function actionCreate(){
		$model=new WxRouterKeyword();

		// $this->performAjaxValidation($model);

		if (isset($_POST['WxRouterKeyword'])){
			$model->attributes=$_POST['WxRouterKeyword'];
			$model->ghid=gh()->ghid;
			$model->ctm=date('Y-m-d H:i:s');
			switch ($model->msg_type){
				case 'text':

					break;
				case 'image' or 'voice' or 'video' or 'subscribe' or 'other':
					if (WxRouterKeyword::model()->findByAttributes(array(
						'msg_type'=>$model->msg_type,
						'ghid'=>gh()->ghid
					))){
						throw new CHttpException(404, 'The requested page does not exist.');
					}
					$model->keyword='---'.$model->msg_type.'---';
					if ($model->msg_type=='other'){
						$model->match_mode=3; // 默认匹配
					}else{
						$model->match_mode=1;
					}
					break;
				default:
					throw new CHttpException(404, 'The requested page does not exist.');
					break;
			}
			switch ($model->reply_type){
				case '1' or '2':
					$model->reply_id=$model->reply_id[$model->reply_type];
					break;
				default:
					$this->error('参数错误！');
					break;
			}
			if ($model->save())
				$this->success('添加成功', U('&admin'));
		}
		$msg_type='text';
		switch ($_GET['msg_type']){
			case 'text':
				$msg_type=$_GET['msg_type'];
				break;
			case 'subscribe' or 'image' or 'voice' or 'video' or 'other':
				$msg_type=$_GET['msg_type'];
				if ($row=WxRouterKeyword::model()->findByAttributes(array(
					'msg_type'=>$msg_type,
					'ghid'=>gh()->ghid
				))){
					// $this->actionUpdate ( $row->id );
					$this->redirect(U('&update/id/'.$row->id));
					exit();
				}
				$msg_type=$_GET['msg_type'];
				break;
			default:
				throw new CHttpException(404, 'The requested page does not exist.');
				break;
		}
		$this->render('create', array(
			'model'=>$model,
			'msg_type'=>$msg_type
		));
	}
	public function actionUpdate($id){ // msg_type

		$model=WxRouterKeyword::model()->findByAttributes(array('ghid'=>gh()->ghid,'id'=>$id));
		if(!$model)$this->error('非法操作');
		// $this->performAjaxValidation($model);
		if (isset($_POST['WxRouterKeyword'])){
			$model->attributes=$_POST['WxRouterKeyword'];
			switch ($model->msg_type){
				case 'text':

					break;
				case 'image' or 'voice' or 'video' or 'subscribe' or 'other':
					$model->keyword='----'.$model->msg_type.'---';
					if ($model->msg_type=='other'){
						$model->match_mode=3; // 默认匹配
					}else{
						$model->match_mode=1;
					}
					break;
				default:
					throw new CHttpException(404, 'The requested page does not exist.');
					break;
			}
			switch ($model->reply_type){
				case '1' or '2':
					$model->reply_id=$model->reply_id[$model->reply_type];
					break;
				default:
					$this->error('参数错误！');
					break;
			}
			$model->ghid=gh()->ghid;
			if ($model->save())
				$this->success('操作成功', U('&admin'));
		}

		$this->render('update', array(
			'model'=>$model
		));
	}
	public function actionDelete($id){
		$model=WxRouterKeyword::model()->findByAttributes(array('ghid'=>gh()->ghid,'id'=>$id));
		if(!$model)$this->error('非法操作');
		$this->loadModel($model->id)->delete();
		// 如果是AJAX请求删除,请取消跳转
		if (!isset($_GET['ajax']))
			$this->success('操作成功');
	}
	public function actionIndex(){
		$dataProvider=new CActiveDataProvider('WxRouterKeyword');
		$this->render('index', array(
			'dataProvider'=>$dataProvider
		));
	}
	public function actionAdmin(){
		$model=new WxRouterKeyword('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['WxRouterKeyword']))
			$model->attributes=$_GET['WxRouterKeyword'];

		$this->render('admin', array(
			'model'=>$model
		));
	}
	public function loadModel($id){
		$model=WxRouterKeyword::model()->findByAttributes(array(
			'id'=>$id,
			'ghid'=>gh()->ghid
		));

		if ($model===null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * AJAX验证
	 *
	 * @param WxRouterKeyword $model
	 * 模型验证
	 */
	protected function performAjaxValidation($model){
		if (isset($_POST['ajax'])&&$_POST['ajax']==='wx-router-keyword-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
