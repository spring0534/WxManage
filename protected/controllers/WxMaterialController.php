<?php

/**
* WxMaterialController.php
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2014-11-24
* 
*/
class WxMaterialController extends BaseGhController{
	public function actionView($id){
		$this->render('view', array(
			'model'=>$this->loadModel($id)
		));
	}
	public function actionCreate(){
		$model=new WxMaterial();
		if (isset($_POST['WxMaterial'])){
			$model->attributes=$_POST['WxMaterial'];
			$model->ctm=time();
			$model->ghid=gh()->ghid;
			if ($model->save())
				$this->success('添加成功',U('&index'));
		}
		$this->render('create', array(
			'model'=>$model
		));
	}
	public function actionAdd(){
		$model=new WxMaterial();
		$msgtype=$_GET['type'];
		if (empty($msgtype))
			$msgtype='text';
		if (isset($_POST['WxMaterial'])){
			$post=$_POST['WxMaterial'];
			$model->attributes=$_POST['WxMaterial'];
			$model->ghid=gh()->ghid;
			$type=$_POST['m_type'];
			// dump($type);exit;
			switch ($type){
				case 'text':
					$model->msg_type='text';
					break;
				case 'news_1' and 'news_n':
					$model->msg_type=$type;
					$data=$_POST['clist'];
					if (is_array($data)){
						foreach ($data as $kk=>$vv){
							// 1=>'跳转到链接',2=>'跳转到活动',3=>'详细内容'
							switch ($vv['event_']){
								case 1:
									$insertData[]=array(
										'title'=>$vv['name'],
										'pic'=>$vv['pic'],
										'url'=>$vv['url'],
										'note'=>$vv['note'],
										'onclick'=>$vv['event_']
									);
									break;
								case 2:
									$akey=Yii::app()->db->createCommand()
										->select('akey')
										->from('activity')
										->where('aid='.$vv['actv'])
										->queryScalar(); // 读取活动URL
									if (empty($akey))
										$this->error('活动不存在！');
									$url=Yii::app()->params['appUrl'].$akey;
									$insertData[]=array(
										'title'=>$vv['name'],
										'pic'=>$vv['pic'],
										'url'=>$url,
										'aid'=>$vv['actv'],
										'note'=>$vv['note'],
										'onclick'=>$vv['event_']
									);
									break;
								case 3:
									$re=Yii::app()->db->createCommand()->insert('wx_material_detail', array(
										'content'=>$vv['detail'],
										'ghid'=>gh()->ghid
									));
									if (empty($re))
										$this->error('操作失败!');
									$insertData[]=array(
										'title'=>$vv['name'],
										'pic'=>$vv['pic'],
										'url'=>$vv['url'],
										'did'=>Yii::app()->db->getLastInsertID(),
										'note'=>$vv['note'],
										'onclick'=>$vv['event_']
									);
									unset($lid);
									break;
								default:
									$this->error('参数错误！');
									break;
							}
						}
					}
					$model->content=json_encode($insertData);
					break;
				case 'image':
					$model->msg_type='image';
					$this->error('该功能正在开发中...');
					break;
				default:
					$this->error('类型错误 !');
					break;
			}

			if ($model->save()){
				$this->success('添加成功',U('&index'));
			}
		}
		$actlist=Yii::app()->db->createCommand()
			->select('aid,title')
			->from('activity')
			->where("ghid='".gh()->ghid."'")
			->queryAll();
		if ($_GET['op']=='tpl'){
			$this->renderPartial('news_n_tpl', array(
				'model'=>$model,
				'actlist'=>$actlist,
				'num'=>intval($_GET['num'])
			));
			exit();
		}
		$this->render('add', array(
			'model'=>$model,
			'actlist'=>$actlist,
			'tpl'=>$msgtype
		));
	}
	public function actionUpdate($id){
		$model=WxMaterial::model()->find("ghid='".gh()->ghid."' and id=".$id);
		if ($model===null)
			throw new CHttpException(404, 'The requested page does not exist.');
			// $this->performAjaxValidation($model);
		$content=json_decode($model->content, true);
		//dump($content);exit;
		if (isset($_POST['WxMaterial'])){
			$model->attributes=$_POST['WxMaterial'];
			// $model->ghid=gh()->ghid;
			$type=$_POST['m_type'];
			// dump($type);exit;
			switch ($type){
				case 'text':
					$model->msg_type='text';
					break;
				case 'news_1' and 'news_n':
					$model->msg_type=$type;
					$data=$_POST['clist'];
					if (is_array($data)){

						foreach ($data as $kk=>$vv){
							// 1=>'跳转到链接',2=>'跳转到活动',3=>'详细内容'
							switch ($vv['event_']){
								case 1:
									$insertData[]=array(
										'title'=>$vv['name'],
										'pic'=>$vv['pic'],
										'url'=>$vv['url'],
										'note'=>$vv['note'],
										'onclick'=>$vv['event_']
									);
									break;
								case 2:
									$akey=Yii::app()->db->createCommand()
										->select('akey')
										->from('activity')
										->where('aid='.$vv['actv'])
										->queryScalar(); // 读取活动URL
									if (empty($akey))
										$this->error('活动不存在！');
									$url=Yii::app()->params['appUrl'].$akey;
									$insertData[]=array(
										'title'=>$vv['name'],
										'pic'=>$vv['pic'],
										'url'=>$url,
										'aid'=>$vv['actv'],
										'note'=>$vv['note'],
										'onclick'=>$vv['event_']
									);
									break;
								case 3:
									$did=intval($vv['did']);
									if (empty($did)){
										if (!Yii::app()->db->createCommand()->insert('wx_material_detail', array(
											'content'=>$vv['detail'],
											'ghid'=>gh()->ghid
										))){
											$this->error('操作失败!');
										}
										;
										$vv['did']=Yii::app()->db->getLastInsertID();
									}else{
										$re=Yii::app()->db->createCommand()->update('wx_material_detail', array(
											'content'=>$vv['detail']
										), "ghid='".gh()->ghid."' and id=".$vv['did']);

										if ($re===false)
											$this->error('操作失败!');
									}
									$insertData[]=array(
										'title'=>$vv['name'],
										'pic'=>$vv['pic'],
										'url'=>$vv['url'],
										'did'=>$vv['did'],
										'note'=>$vv['note'],
										'onclick'=>$vv['event_']
									);
									break;
								default:
									//$this->error('参数错误！');
									break;
							}
						}
					}
					$model->content=json_encode($insertData);
					break;
				case 'image':
					$model->msg_type='image';
					$this->error('该功能正在开发中...');
					break;
				default:
					$this->error('类型错误 !');
					break;
			}
			$model->utm=date('Y-m-d H:i:s');
			if ($model->save())
				$this->success('操作成功',U('&index'));
		}
		$actlist=Yii::app()->db->createCommand()
			->select('aid,title')
			->from('activity')
			->where("ghid='".gh()->ghid."'")
			->queryAll();
		// dump($model);
		// dump(json_decode($model->content,true));exit;
		$this->render('update', array(
			'model'=>$model,
			'actlist'=>$actlist,
			'content'=>$content
		));
	}
	public function actionDelete($id){
		$model=WxMaterial::model()->find("ghid='".gh()->ghid."' and id=".$id);
		if(!$model)$this->error('非法操作！');
		$this->loadModel($model->id)->delete();
		// 如果是AJAX请求删除,请取消跳转
		if (!isset($_GET['ajax']))
			$this->success('操作成功');
	}
	public function actionIndex(){
		$model=new WxMaterial('oneselfSearch');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['WxMaterial']))
			$model->attributes=$_GET['WxMaterial'];

		$model->ghid=gh()->ghid;
		$this->render('index', array(
			'model'=>$model
		));
	}
	/*public function actionAdmin(){
		$model=new WxMaterial('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['WxMaterial']))
			$model->attributes=$_GET['WxMaterial'];

		$this->render('admin', array(
			'model'=>$model
		));
	}*/
	public function loadModel($id){
		$model=WxMaterial::model()->findByPk($id);
		if ($model===null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * AJAX验证
	 * @param WxMaterial $model
	 * 模型验证
	 */
	protected function performAjaxValidation($model){
		if (isset($_POST['ajax'])&&$_POST['ajax']==='wx-material-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
