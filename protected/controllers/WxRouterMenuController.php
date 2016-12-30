<?php

/**自定义菜单
* WxRouterMenuController.php
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2014-11-28
* 
*/
class WxRouterMenuController extends BaseGhController{
	public function actionView($id){
		$this->render('view', array(
			'model'=>$this->loadModel($id)
		));
	}
	public function actionCreate(){
		$model=new WxRouterMenu();

		// $this->performAjaxValidation($model);
		$pid=intval($_GET['pid']);
		if (!empty($pid)){
			if (WxRouterMenu::model()->findByAttributes(array(
				'id'=>$pid
			))->ghid!=gh()->ghid){
				$this->error('参数错误！');
			}
			if (WxRouterMenu::model()->countByAttributes(array(
				'ghid'=>gh()->ghid,
				'status'=>1,
				'parent_id'=>$pid
			))>=5){
				$this->error('子主菜单最多能添加5个！');
			}
		}else{

			if (WxRouterMenu::model()->countByAttributes(array(
				'ghid'=>gh()->ghid,
				'status'=>1,
				'parent_id'=>0
			))>=3){
				$this->error('主菜单最多能添加3个！');
			}
		}
		if (isset($_POST['WxRouterMenu'])){
			$model->attributes=$_POST['WxRouterMenu'];
			$model->ghid=gh()->ghid;
			$model->event_key=substr(md5(time()), 0, 15);
			$model->ctm=date('Y-m-d H:i:s');
			$model->seq=0;
			$model->reply_id=$model->reply_id[$model->reply_type];
			if ($model->save())
				$this->success('添加成功', U('&index'));
		}
		$this->render('create', array(
			'model'=>$model,
			'pid'=>$pid
		));
	}
	public function actionUpdate($id){
		$model=$this->loadModel($id);
		// $this->performAjaxValidation($model);

		if (isset($_POST['WxRouterMenu'])){
			$model->attributes=$_POST['WxRouterMenu'];
			$model->ghid=gh()->ghid;
			$model->utm=date('Y-m-d H:i:s');
			$model->reply_id=$model->reply_id[$model->reply_type];
			if ($model->save())
				$this->success('操作成功', U('&index'));
		}
		$this->render('update', array(
			'model'=>$model
		));
	}
	public function actionDelete($id){
		$model=$this->loadModel($id);
		if (empty($model->parent_id)){
			WxRouterMenu::model()->findByAttributes(array(
				'ghid'=>gh()->ghid,
				'parent_id'=>$id
			))->delete();
			$this->loadModel($id)->delete();
		}else{
			$this->loadModel($id)->delete();
		}

		// 如果是AJAX请求删除,请取消跳转
		if (!isset($_GET['ajax']))
			$this->success('操作成功', U('&index'));
	}
	function actionListorder(){
		$ids=$_POST['listorders'];
		foreach ($ids as $key=>$r){
			$model=WxRouterMenu::model()->findByAttributes(array(
				'id'=>$key,
				'ghid'=>gh()->ghid
			));
			if ($model){
				$model->seq=$r;
				$model->save();
			}
		}
		echo json_encode(array(
			'code'=>1
		));
		exit();
	}
	/**
	 * 拉取
	 * @date: 2015-1-6
	 * @author: 佚名
	 */
	public function actionMenuD(){
		yii::import ( 'ext.Weixinapi' );
		$account=array(
			'AppId'=>gh()->appid,
			'AppSecret'=>gh()->appsecret,
			'access_token'=>gh()->access_token,
			'expire'=>gh()->at_expires
		);
		$t = new Weixinapi ( $account );
		$t->debug = true;
		$menu=json_decode($t->menuQuery(),true);
		if($menu){
			foreach ($menu['menu']['button'] as $key=>$value){
				
			}
		}else{
			echo json_encode(array('result'=>-1,'msg'=>'微信服务器上无任何菜单！'));
		}
		
	}
	function actionUpload(){
		$data=Yii::app()->db->createCommand()
			->select('*')
			->from('wx_router_menu')
			->where('status=1'." and ghid='".gh()->ghid."'")
			->order("seq")
			->queryAll();
		yii::import('ext.Tree');
		$tree=new Tree();
		$tree->pid='parent_id';
		$tree->init($data);
		$tarr=$tree->getAll_childlist(0);
		foreach ($tarr as $k=>$v){
			if(empty($v['sublist'])){
				$arr['type']=$v['menu_type'];
				$arr['name']=$v['name'];
				if($v['menu_type']=='click'){
					$arr['key']=$v['event_key'];
				}else{
					$arr['url']=$v['url'];
				}
				$dat['button'][]=$arr;
				unset($arr);
			}else{
				$arr['name']=$v['name'];
				foreach ($v['sublist'] as $kk=>$vv){
					$arr2['type']=$vv['menu_type'];
					$arr2['name']=$vv['name'];
					if($vv['menu_type']=='click'){
						$arr2['key']=$vv['event_key'];
					}else{
						$arr2['url']=$vv['url'];
					}
					$d[]=$arr2;
					unset($arr2);
				}
				$arr['sub_button']=$d;
				$dat['button'][]=$arr;
				unset($arr);unset($d);
			}
		}
		yii::import ( 'ext.Weixinapi' );
		$account=array(
			'AppId'=>gh()->appid,
			'AppSecret'=>gh()->appsecret,
			'access_token'=>gh()->access_token,
			'expire'=>gh()->at_expires
		);
		$t = new Weixinapi ( $account );
		$t->debug = true;
		$re=$t->menuCreate($dat);
		if($re==false||!empty($re['errcode'])){
			echo json_encode(array(
				'code'=>'err',
				'msg'=>$re['errmsg']?$re['errmsg']:$t->error
			));
		}else{
			echo json_encode(array(
				'code'=>1
			));
		}
		exit();
	} 
	public function actionIndex(){
		
		if(!in_array(gh()->type, array(1,2,3))){
			$this->error('你的公众账号类型没有自定义菜单的权限！','',10000);
		}
		$data=Yii::app()->db->createCommand()
			->select('*')
			->from('wx_router_menu')
			->where('status=1'." and ghid='".gh()->ghid."'")
			->order("seq")
			->queryAll();
		yii::import('ext.Tree');
		$tree=new Tree();
		$tree->pid='parent_id';
		$tree->init($data);
		$tarr=$tree->getAll_childlist(0);
		$this->render('index', array(
			'dataProvider'=>$tarr
		));
	}
	/*
	 * public function actionAdmin(){
	 * $model=new WxRouterMenu('search');
	 * $model->unsetAttributes(); // clear any default values
	 * if (isset($_GET['WxRouterMenu']))
	 * $model->attributes=$_GET['WxRouterMenu'];
	 *
	 * $this->render('admin', array(
	 * 'model'=>$model
	 * ));
	 * }
	 */
	public function loadModel($id){
		$model=WxRouterMenu::model()->findByAttributes(array(
			'id'=>$id,
			'ghid'=>gh()->ghid
		));
		if ($model===null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * AJAX验证
	 * @param WxRouterMenu $model
	 * 模型验证
	 */
	protected function performAjaxValidation($model){
		if (isset($_POST['ajax'])&&$_POST['ajax']==='wx-router-menu-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
