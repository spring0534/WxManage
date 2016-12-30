<?php

/**
* StatDataController.php
* ----------------------------------------------
*/
class StatDataController extends BaseGhController{
	public function actionView($id)
	{
		$model=StatData::model()->findByAttributes(array('id'=>$id));

		if ($model===null)
			throw new CHttpException(404, 'The requested page does not exist.');
		$this->render('view',array(
			'model'=>$model,
		));
	}
	/**
	 * 统计概况
	 * @date: 2015-3-30
	 * @author :
	 */
	function actionProfile(){
		// Yii::app()->cache->set(gh()->ghid.'profile', null);
		$cc=Yii::app()->cache->get(gh()->ghid.'profile_01');
		if (empty($cc)){
			//昨天微信统计
			$wx_data=Yii::app()->db->createCommand()
				->select('day,sum(sub) sub,sum(unsub) unsub,sum(receive_num) receive_num,sum(send_num) send_num,sum(msg_num) msg_num')
				->from('stat_wx')
				->where("ghid='".gh()->ghid."' and day>'".date('Y-m-d', strtotime("-1 day"))."' and day<'".date('Y-m-d')."'")
				->group('day')
				->queryRow();
			//昨天  总pv,总uv,新用户，Ip数
			$dataY=Yii::app()->db->createCommand()
				->select('day,sum(pv) pv,sum(uv) uv,sum(cv) cv,sum(ip) ip,sum(s1) s1,sum(s2) s2,sum(s3) s3,sum(s4) s4,sum(sub) sub,sum(unsub) unsub')
				->from('stat_report')
				->where("ghid='".gh()->ghid."' and day='".date('Y-m-d', strtotime("-1 day"))."'")
				->group('day')
				->queryRow();
			//一周  总pv,总uv,新用户，Ip数
			$dataM=Yii::app()->db->createCommand()
			->select('day,sum(pv) pv,sum(uv) uv,sum(cv) cv,sum(ip) ip,sum(s1) s1,sum(s2) s2,sum(s3) s3,sum(s4) s4,sum(sub) sub,sum(unsub) unsub')
			->from('stat_report')
			->where("ghid='".gh()->ghid."' and day>='".date('Y-m-d', strtotime("-7 day"))."' and day<'".date('Y-m-d')."'")
			->group('day')
			->queryAll();
			//dump($dataM);exit;
			//一周操作系统
			$os=Yii::app()->db->createCommand()
				->select('count(id) num,os')
				->from('stat_data')
				->where("ghid='".gh()->ghid."' and rtime>='".date('Y-m-d', strtotime("-7 day"))."' and rtime<'".date('Y-m-d')."'")
				->group('os')
				->queryAll();
			//一周手机 select count(id) num,mobile from stat_data group by mobile ORDER BY num desc LIMIT 15;
			$mobile=Yii::app()->db->createCommand()
				->select('count(id) num,mobile')
				->from('stat_data')
				->where("ghid='".gh()->ghid."' and rtime>='".date('Y-m-d', strtotime("-7 day"))."' and rtime<'".date('Y-m-d')."'")
				->group('mobile')
				->order('num desc')
				->limit(15)
				->queryAll();
			// dump($os);
			$cc=array(
				'data'=>$wx_data,
				'dataY'=>$dataY,
				'dataM'=>$dataM,
				'os'=>$os,
				'mobile'=>$mobile
			);
			Yii::app()->cache->set(gh()->ghid.'profile_01', $cc, 12*60*60);
		}
		$this->render('profile', $cc);
	}

	/*public function actionAdmin(){
		$model=new StatData('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['StatData']))
			$model->attributes=$_GET['StatData'];

		$this->render('admin', array(
			'model'=>$model
		));
	}*/


	/**
	 * AJAX验证
	 * @param StatData $model
	 * 模型验证
	 */
	protected function performAjaxValidation($model){
		if (isset($_POST['ajax'])&&$_POST['ajax']==='user-reg-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	/**
	 * 计划任务， 数据报表统计
	 * @date: 2015-3-30
	 * @author :
	 */
	function actionTask(){
		set_time_limit(120); // 最长两分钟的执行时间
		// 统计昨天
		$d=-1;
		$t=date('Y-m-d', strtotime("$d day"));
		$t2=date('Y-m-d', strtotime(($d+1)." day"));
		// 总pv,总uv,新用户，Ip数
		$data1=Yii::app()->db->createCommand()
			->select('SUM(pv) as pv, SUM(cid) as addu, COUNT( DISTINCT wxid ) as uv,COUNT( DISTINCT ip ) as ip,aid,ghid')
			->from('stat_data')
			->where(" rtime>'".$t."' and rtime<'".$t2."'")
			->group('ghid')
			->queryAll();
		//dump($data1);exit;
		// 1分享到好友 2分享到朋友圈3分享到QQ4分享到微博
		$data2=Yii::app()->db->createCommand()
			->select('count(id) num,aid,ghid,shareType')
			->from('stat_data')
			->where("(shareType=1 or shareType=2 or shareType=3 or shareType=4)  and rtime>'".$t."' and rtime<'".$t2."'")
			->group('ghid,shareType')
			->queryAll();
		//dump($data1);
		/*
		 * $os=Yii::app()->db->createCommand()
		 * ->select('count(id) num,os')
		 * ->from('stat_data')
		 * ->where("ghid='".gh()->ghid."' and rtime>'".date('Y-m-d', strtotime("-7 day"))."' and rtime<'".date('Y-m-d')."'")
		 * ->group('os')
		 * ->queryAll();
		 * // select count(id) num,mobile from stat_data group by mobile ORDER BY num desc LIMIT 15;
		 * $mobile=Yii::app()->db->createCommand()
		 * ->select('count(id) num,mobile')
		 * ->from('stat_data')
		 * ->where("ghid='".gh()->ghid."' and rtime>'".date('Y-m-d', strtotime("-7 day"))."' and rtime<'".date('Y-m-d')."'")
		 * ->group('mobile')
		 * ->order('num desc')
		 * ->limit(15)
		 * ->queryAll();
		 * // dump($os);
		 */
		foreach ($data1 as $k=>$v){
			$m=StatReport::model()->findByAttributes(array(
				'aid'=>$v['aid'],
				'ghid'=>$v['ghid'],
				'day'=>$t
			));
			if (empty($m)){
				$model=new StatReport();
				$model->day=$t;
				$model->aid=$v['aid'];
				$model->ghid=$v['ghid'];
				$model->pv=$v['pv'];
				$model->uv=$v['uv'];
				$model->cv=$v['addu'];
				$model->ip=$v['ip'];
				$model->save();

			}else{

				$m->pv=$v['pv'];
				$m->uv=$v['uv'];
				$m->cv=$v['addu'];
				$m->ip=$v['ip'];
				$m->save();
			}
		}
		foreach ($data2 as $kk=>$vv){
			$m=StatReport::model()->findByAttributes(array(
				'aid'=>$vv['aid'],
				'ghid'=>$vv['ghid'],
				'day'=>$t
			));
			if (empty($m)){
				$model=new StatReport();
				$model->day=$t;
				$model->aid=$vv['aid'];
				$model->ghid=$vv['ghid'];
				$st='s'.$vv['shareType'];
				$model->$st=$vv['num'];
				$model->save();
			}else{
				$st='s'.$vv['shareType'];
				$m->$st=$vv['num'];
				$m->save();
			}
		}
	}
}