<?php

/**互动微应用
* PluginController.php
* ----------------------------------------------
* 版权所有 2014-2015
* ----------------------------------------------
* @date: 2014-12-4
*
*/
class PluginController extends BaseController{
	public $defaultField=array(
		'shareTitle'=>'分享标题',
		'shareDesc'=>'分享描述',
		'shareIcon'=>'分享小图标',
		'customWxCss'=>'自定义CSS',
		'statJs'=>'第三方统计代码',
		'isSubscribe'=>'是否关注',
		'SubscribeUrl'=>'关注链接',
		'rankingTop'=>'排行榜显示条数',
		'activityDesc'=>'活动说明',
		'activityClose'=>'活动关闭时间',
		'suspendActivity'=>'是否暂停活动',
		'suspendNotice'=>'暂停活动公告说明',
		'temporaryNotice'=>'临时通告',
		'temporaryNoticeShow'=>'是否显示临时通告'
	);
	public function actionView($id){
		$this->render('view', array(
			'model'=>$this->loadModel($id)
		));
	}
	public function actionCreate(){
		$model=new Plugin();
		if (isset($_POST['Plugin'])){
			$model->attributes=$_POST['Plugin'];
			$model->setting=serialize($_POST['setting']);
			$model->screenshots=serialize($_POST['uploadImages']['screenshots']);
			$model->ctm=date('Y-m-d H:i:s');
			if (empty($model->uid))
				$this->error('请选择微应用开发者！');
			$model->setting=serialize($_POST['setting']);
			$ptype=$model->ptype;
			$preconfig=$_POST['setting']['preconfig'];

			if (Plugin::model()->findByAttributes(array(
				'ptype'=>$model->ptype
			)))
				$this->error('该微应用类型已经存在，请不要重复添加！');
			if ($model->save()){
				// 添加预设属性
				if (!empty($preconfig)){
					foreach ($preconfig as $k=>$v){
						$vo=Yii::app()->db->createCommand()
							->select('propname,proptitle,proptype,setting,required,minlength,maxlength,seq,memo,pattern,issystem,isshow,editable')
							->from('plugin_prop_default')
							->where("propname='".$v."'")
							->queryRow();
						if ($vo){
							if (!PluginProp::model()->findByAttributes(array(
								'ptype'=>$ptype,
								'propname'=>$v
							))){
								$model_p=new PluginProp();
								$model_p->attributes=$vo;
								$model_p->ctm=date('Y-m-d H:i:s');
								$model_p->ptype=$ptype;
								$model_p->save();
								unset($vo);
							}
						}
					}
				}
				$this->success('添加成功', U('&admin'));
			}else{
				$this->error('操作失败');
			}
		}

		$this->render('create', array(
			'model'=>$model
		));
	}
	public function actionUpdate($id){
		$model=$this->loadModel($id);
		$model->setting=unserialize($model->setting);
		$set=$model->setting['preconfig'];
		cookie('oldset', $set);
		if ($set){
			foreach ($set as $kk=>$vv){
				if (!PluginProp::model()->findByAttributes(array(
					'propname'=>$vv,
					'ptype'=>$model->ptype
				))){
					unset($set[$kk]);
				}
			}
			$arr=$model->setting;
			$arr['preconfig']=$set;
			$model->setting=$arr;
		}
		if (isset($_POST['Plugin'])){
			$ptype=$model->ptype;
			$model->attributes=$_POST['Plugin'];
			$model->screenshots=serialize($_POST['uploadImages']['screenshots']);
			$model->ptype=$ptype;
			$model->utm=date('Y-m-d H:i:s');
			$model->setting=serialize($_POST['setting']);
			$ptype=$model->ptype;
			$preconfig=$_POST['setting']['preconfig'];
			if ($model->save()){
				// 编辑预设属性
				$oldset=cookie('oldset');
				if (!empty($preconfig)){
					foreach ($preconfig as $k=>$v){
						$vo=Yii::app()->db->createCommand()
							->select('propname,proptitle,proptype,setting,required,minlength,maxlength,seq,memo,pattern,issystem,isshow,editable')
							->from('plugin_prop_default')
							->where("propname='".$v."'")
							->queryRow();
						if ($vo){
							if (!PluginProp::model()->findByAttributes(array(
								'ptype'=>$ptype,
								'propname'=>$v
							))){

								$model_p=new PluginProp();
								$model_p->attributes=$vo;
								$model_p->ctm=date('Y-m-d H:i:s');
								$model_p->ptype=$ptype;
								$model_p->save();
								unset($vo);
							}
						}
					}
					// 删除操作
					foreach ((array) $oldset as $kkk=>$vvv){
						if (!in_array($vvv, $preconfig)||empty($preconfig)){
							$model=PluginProp::model()->findByAttributes(array(
								'propname'=>$vvv,
								'ptype'=>$ptype
							));
							if ($model){
								PluginProp::model()->findByPk($model->id)->delete();
							}
						}
					}
				}else{
					foreach ((array) $oldset as $kkkk=>$vvvv){
						$model=PluginProp::model()->findByAttributes(array(
							'propname'=>$vvvv,
							'ptype'=>$ptype
						));
						if ($model){

							PluginProp::model()->findByPk($model->id)->delete();
						}
					}
				}
				cookie('oldset', null);
				$this->success('操作成功', U('&admin'));
			}else{
				$this->error('操作失败！');
			}
		}

		$this->render('update', array(
			'model'=>$model
		));
	}
	public function actionDelete($id){
		$this->loadModel($id)->delete();
		// 如果是AJAX请求删除,请取消跳转
		if (!isset($_GET['ajax']))
			$this->success('操作成功', U('&admin'));
	}
	public function actionIndex(){
		$model=new Plugin('index');
		$model->unsetAttributes();
		if (isset($_GET['Plugin']))
			$model->attributes=$_GET['Plugin'];
		$m=$model->index();
		$data=$m->data;
		if (!empty($data)){
			foreach ($data as $k=>$v){
				$data[$k]['puse']=PluginGhUse::model()->findByAttributes(array(
					'ptype'=>$v['ptype'],
					'ghid'=>gh()->ghid
				));
			}
		}
		$pages['pages']=$m->getPagination();
		$this->render('index', array(
			'list'=>$data,
			'pages'=>$pages,
			'model'=>$model
		));
	}
	public function actionAdmin(){
		$model=new Plugin('search');
		$model->unsetAttributes();
		if (isset($_GET['Plugin']))
			$model->attributes=$_GET['Plugin'];
		$this->render('admin', array(
			'model'=>$model
		));
	}
	function actionOpenPlugn($ghid_control){
		if (empty($ghid_control))
			$this->error('请先绑定公众号！');
		$model=new Plugin('search2');
		$model->unsetAttributes();
		if (isset($_GET['Plugin']))
			$model->attributes=$_GET['Plugin'];
		$this->render('pluginlist', array(
			'model'=>$model,
			'gh'=>SysUserGh::model()->findByAttributes(array(
				'ghid'=>$ghid_control
			))
		));
	}
	function actionOpenselect(){
		$data=$_POST['data'];
		$ghid=$_POST['ghid'];
		if (!empty($data)&&!empty($ghid)){
			foreach ((array) $data as $v){
				list ($ptype, $time, $maxnum)=explode(',', $v);
				$model=PluginGhUse::model()->findByAttributes(array(
					'ghid'=>$ghid,
					'ptype'=>$ptype
				));
				if ($model){
					$model->endtime=$time;
					$model->status=1;
					$model->maxnum=$maxnum ? $maxnum : 1;
					$model->save();
				}else{
					$m=new PluginGhUse();
					$m->ghid=$ghid;
					$m->starttime=date('Y-m-d H:i:s');
					$m->status=1;
					$m->ptype=$ptype;
					$m->ctm=date('Y-m-d H:i:s');
					$m->endtime=$time ? $time : date('Y-m-d H:i:s', time()+30*24*60*60);
					$m->maxnum=$maxnum ? $maxnum : 1;
					$m->save();
				}
			}
			echo 'ok';
			exit();
		}
	}

	/**
	 * 批量开通微应用
	 * @date: 2015-5-14
	 * @author : 佚名
	 */
	function actionBatchOpen(){
		set_time_limit(120);
		$data=$_POST['Plugin'];
		if (!Yii::app()->db->createCommand()
			->select('id')
			->from('plugin')
			->where("ptype='".$data['ptype']."'")
			->queryRow()){
			$this->error('请选择要开通的微应用！');
		}
		if (!empty($_POST['selectopen'])){
			foreach ($_POST['selectopen'] as $k=>$v){
				if (empty(gh()->ghid))
					$this->error('请先绑定公众号');
					// 验证该商户是否在自己的管理范围内
				$row=Yii::app()->db->createCommand()
					->select('pids,ghid')
					->from('sys_user')
					->where("id=".$v)
					->queryRow();
				if ($row){
					$maxnum=$data['num'];
					$time=$data['etime'];
					if (in_array(gh()->tenant_id, explode(',', $row['pids']))){
						$model=PluginGhUse::model()->findByAttributes(array(
							'ghid'=>$row['ghid'],
							'ptype'=>$data['ptype']
						));
						if ($model){
							$model->endtime=$time;
							$model->status=1;
							$model->maxnum=$maxnum ? $maxnum : 1;
							$model->save();
						}else{
							$m=new PluginGhUse();
							$m->ghid=$row['ghid'];
							$m->starttime=date('Y-m-d H:i:s');
							$m->status=1;
							$m->ptype=$data['ptype'];
							$m->ctm=date('Y-m-d H:i:s');
							$m->endtime=$time ? $time : date('Y-m-d H:i:s', time()+30*24*60*60);
							$m->maxnum=$maxnum ? $maxnum : 1;
							$m->save();
						}
					}
				}
			}
			$this->success('操作成功！');
		}else{
			$this->error('请选择要开通的商户！');
		}
	}
	/**
	 * 批量克隆
	 * @date: 2015-5-15
	 * @author: 佚名
	 */
	function actionBatchCopy(){
		set_time_limit(0);
		$aid=intval($_POST['aid']);
		if(empty($aid)){
			$this->error('参数错误！');
		}
		if (!empty($_POST['selectopen'])){
			foreach ($_POST['selectopen'] as $k=>$v){
				if (empty(gh()->ghid))
					$this->error('请先绑定公众号');
					// 验证该商户是否在自己的管理范围内
				$row=Yii::app()->db->createCommand()
					->select('id,pids,ghid')
					->from('sys_user')
					->where("id=".$v)
					->queryRow();
				if ($row){
					if (in_array(gh()->tenant_id, explode(',', $row['pids']))){
						$ActivityConfig_model=ActivitySettings::model()->findAllByAttributes(array(
							'aid'=>intval($_POST['aid'])
						));
						$Activity_model=Activity::model()->findByPk(intval($_POST['aid']));
						$model=new Activity();
						$model->attributes=$Activity_model->attributes;
						$model->akey='';
						$model->ghid=$row['ghid'];
						if ($model->save()){
							$newAid=$model->aid;
							foreach ($ActivityConfig_model as $k=>$v){
								$as=new ActivitySettings();
								$as->aid=$newAid;
								$as->propname=$v->propname;
								$as->propvalue=$v->propvalue;
								$as->email=$v->email;
								$as->tm=$v->tm;
								$as->ltm=$v->ltm;
								$as->save();
								unset($as);
							}
							unset($model);
						}else{

						}
					}
					unset($row);
				}
			}
			$this->success('操作成功！');
		}else{
			$this->error('请选择要操作的商户！');
		}
	}
	public function loadModel($id){
		$model=Plugin::model()->findByPk($id);
		if ($model===null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * AJAX验证
	 * @param Plugin $model
	 * 模型验证
	 */
	protected function performAjaxValidation($model){
		if (isset($_POST['ajax'])&&$_POST['ajax']==='plugin-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
