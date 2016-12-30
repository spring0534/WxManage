<?php

/**
* StatDataController.php
* ----------------------------------------------
*/
class CronController extends Controller{
	/**
	 * 计划任务， 数据报表统计
	 * @date: 2015-3-30
	 * @author :
	 */
	function actionTask(){
		set_time_limit(60*30); // 最长两分钟的执行时间
		for($i=1;$i<=1;$i++){
		// 统计昨天
		$d=-$i;
		$t=date('Y-m-d', strtotime("$d day"));
		//dump($t);
		$t2=date('Y-m-d', strtotime(($d+1)." day"));
		// 总pv,总uv,Ip数
		$data1=Yii::app()->db->createCommand()
			->select('SUM(pv) as pv,COUNT( DISTINCT wxid ) as uv,COUNT( DISTINCT ip ) as ip,aid,ghid')
			->from('stat_data')
			->where(" rtime>'".$t."' and rtime<'".$t2."'")
			->group('ghid,aid')
			->queryAll();
		//新用户
		$adduData=Yii::app()->db->createCommand()
		->select('COUNT( DISTINCT wxid )  as addu')
		->from('stat_data')
		->where(" rtime>'".$t."' and rtime<'".$t2."' and cid=1 ")
		->group('ghid,aid')
		->queryAll();

		// dump($data1);exit;
		// 1分享到好友 2分享到朋友圈3分享到QQ4分享到微博
		$data2=Yii::app()->db->createCommand()
			->select('count(id) num,aid,ghid,shareType')
			->from('stat_data')
			->where("(shareType=1 or shareType=2 or shareType=3 or shareType=4)  and rtime>'".$t."' and rtime<'".$t2."'")
			->group('ghid,shareType,aid')
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
				$model->cv=$adduData[$k]['addu'];
				$model->ip=$v['ip'];
				$model->save();
			}else{

				$m->pv=$v['pv'];
				$m->uv=$v['uv'];
				$m->cv=$adduData[$k]['addu'];
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
		exit('success');
	}
}