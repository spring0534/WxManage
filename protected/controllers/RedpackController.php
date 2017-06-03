<?php

/**
* RedpackController.php
* ----------------------------------------------
* 版权所有 2014-2016
* ----------------------------------------------
* @date: 2016-12-5
*
*/
class RedpackController extends BaseController{
	const SEND_REDPACK_URL = 'http://127.0.0.1:8888/wx/wx/cgi/SendRedPack.do?ghid=%s&openid=%s&send_name=%s&billno=%s&amount=%s&wishing=%s&act_name=%s';
	
	protected function beforeAction($action){
		return parent::beforeAction($action);
	}

	public function actionIndex(){
		$model=new RedpackTask('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['RedpackTask']))
			$model->attributes=$_GET['RedpackTask'];
//        if(!isset($model->status)) $model->status = 3;
		$this->render('index', array(
			'model'=>$model,
		));
	}
	
	public function actionSend($id){
		if(isset($_POST['RedpackTask'])){
		    $id=$_POST['RedpackTask']['id'];
		    $amount=$_POST['RedpackTask']['amount'];
		    $status=$_POST['RedpackTask']['status'];
		    $remark=$_POST['RedpackTask']['remark'];
		    $model=$this->loadModel($id);
		    $model->amount = $amount;
		    $model->remark = $remark;
		    $model->status = $status;
		    if($status == 2 && $amount > 0){ //审核通过同时派发红包
		    	$url = sprintf(self::SEND_REDPACK_URL,gh()->ghid,$model->openid,urlencode(gh()->name),$model->tb_order_no,$amount,urlencode('感谢您的惠顾，欢迎再来!'),urlencode('晒图领红包'));
		    	$jsonStr = HttpUtil::getPage($url);
		    	$json = json_decode($jsonStr); //{"action":"","errorcode":"0","message":"success","datastr":""}
		    	if(isset($json)){
		    		if($json->errorcode == 0){
		    			$model->status = 2; //派发成功
		    		}else{
		    			$model->status = 3; //派发失败
		    		}
		    		$model->errmsg=$json->message;
		    		$model->utm=date('Y-m-d H:i:s',time());
		    	}else{
		    		$model->status = 3; //派发失败
		    		$model->errmsg = "调用派发红包接口失败";
		    	}
		    }
		    if(!empty($remark)){
		    	kf_send_text_msg(gh()->ghid, $model->openid, $remark);  //发送指定人员提醒消息
		    }
		    if($model->save()){
		    	$this->success('操作成功', $this->createUrl('redpack/index'));
		    }
		}else{
			$model=$this->loadModel($id);
			$model->id = $id;
			$model->amount = 100;  //默认派发1元
			$this->render('send', array(
					'model'=>$model
			));
		}
	}
	
	function json_result($msg='',$code=-1,$result=''){
		echo json_encode(array('result'=>$result,'code'=>$code,'msg'=>$msg));exit;
	}
	
	public function loadModel($id){
		$model=RedpackTask::model()->findByPk($id);
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
