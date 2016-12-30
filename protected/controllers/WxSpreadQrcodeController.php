<?php

/**推广二维码
* WxSpreadQrcodeController.php
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2014-12-9
* 
*/
class WxSpreadQrcodeController extends BaseGhController{
	public function actionView($id){
		$this->render('view', array(
			'model'=>$this->loadModel($id)
		));
	}
	public function actionCreate(){
		$model=new WxSpreadQrcode();

		// $this->performAjaxValidation($model);

		if (isset($_POST['WxSpreadQrcode'])){
			$model->attributes=$_POST['WxSpreadQrcode'];
			$model->ghid=gh()->ghid;
			$model->uid=user()->id;
			$row=Yii::app()->db->createCommand()
			->select('appid,appsecret,access_token,at_expires,jsapi_ticket,jsapi_ticket_expire,jsapi')
			->from('sys_user_gh')
			->where('ghid=:ghid', array(
				'ghid'=>gh()->ghid
			))
			->queryRow();
			$account=array(
				'AppId'=>$row['appid'],
				'AppSecret'=>$row['appsecret'],
				'access_token'=>$row['access_token'],
				'expire'=>$row['at_expires'],
				'jsapi_ticket'=>$row['jsapi_ticket'],
				'jsapi_ticket_expire'=>$row['jsapi_ticket_expire'],
				'jsapi'=>$row['jsapi']
			);
			if (empty ( $account ['AppId'] ) || empty ( $account ['AppSecret'] )) {
				$this->error( '请填写公众号的appid及appsecret！' );
			}
			yii::import('ext.Weixinapi');
			$wex=new Weixinapi($account);
			$wex->debug=true;
			
			//{"expire_seconds": 1800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 123}}}
			//{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": 123}}}
			$row=Yii::app()->db->createCommand()
			->select('scene_id')
			->from('wx_spread_qrcode')
			->where('ghid=:ghid and qtype=:qtype', array(
				':ghid'=>gh()->ghid,':qtype'=>$model->qtype
			))
			->queryScalar();
			if(empty($row)){
				$model->scene_id=1;
			}
			$model->scene_id=$row+1;
			if($model->qtype==1){
				$model->expire=date('Y-m-d H:i:s',time()+1700);
				$data=array('expire_seconds'=>1800,'action_name'=>'QR_SCENE','action_info'=>array('scene'=>array('scene_id'=>$model->scene_id)));
			}else{
				$data=array('action_name'=>'QR_LIMIT_SCENE','action_info'=>array('scene'=>array('scene_id'=>$model->scene_id)));
			}
			$re=$wex->qrcodeCreate($data);
			if ($re &&is_array($re)&& !empty($re ['ticket'])) {
				$model->url='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($re ['ticket']);
				$model->ticket=$re ['ticket'];
			}else{
				$this->error($wex->error);exit;
			}
			//dump($re);exit;
			if ($model->save())
				$this->success('添加成功');
		}

		$this->render('create', array(
			'model'=>$model
		));
	}
	public function actionUpdate($id){
		$model=WxSpreadQrcode::model()->findByAttributes(array(
			'ghid'=>gh()->ghid,
			'id'=>$id
		));
		if (!$model)
			$this->error('操作失败');
			// $this->performAjaxValidation($model);
		if (isset($_POST['WxSpreadQrcode'])){
			$ghid=$model->ghid;
			$scene_id=$model->scene_id;
			$qtype=$model->qtype;
			$model->attributes=$_POST['WxSpreadQrcode'];
			$model->ghid=$ghid;
			$model->scene_id=$scene_id;
			$model->qtype=$qtype;
			if ($model->save())
				$this->success('操作成功');
		}

		$this->render('update', array(
			'model'=>$model
		));
	}
	public function actionDelete($id){
		$model=WxSpreadQrcode::model()->findByAttributes(array(
			'ghid'=>gh()->ghid,
			'id'=>$id
		));
		if (!$model)
			$this->error('操作失败');
		$this->loadModel($model->id)->delete();
		// 如果是AJAX请求删除,请取消跳转
		if (!isset($_GET['ajax']))
			$this->success('操作成功');
	}
	public function actionIndex(){
		$dataProvider=new CActiveDataProvider('WxSpreadQrcode');
		$this->render('index', array(
			'dataProvider'=>$dataProvider
		));
	}
	public function actionAdmin(){
		if(!in_array(gh()->type, array(1,3))){
			$this->error('你的公众账号类型没有此权限！','',10000);
		}
		//dump(json_decode('{"expire_seconds": 1800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 123}}}',true));
		$model=new WxSpreadQrcode('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['WxSpreadQrcode']))
			$model->attributes=$_GET['WxSpreadQrcode'];

		$this->render('admin', array(
			'model'=>$model
		));
	}
	public function loadModel($id){
		$model=WxSpreadQrcode::model()->findByPk($id);
		if ($model===null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * AJAX验证
	 * @param WxSpreadQrcode $model
	 * 模型验证
	 */
	protected function performAjaxValidation($model){
		if (isset($_POST['ajax'])&&$_POST['ajax']==='wx-spread-qrcode-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
