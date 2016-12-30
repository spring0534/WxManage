<?php

/**
* StatReportController.php
* ----------------------------------------------
*/
class StatReportController extends BaseGhController{
	/*
	 * public function actionDelete($id){
	 * $this->loadModel($id)->delete();
	 * // 如果是AJAX请求删除,请取消跳转
	 * if (!isset($_GET['ajax']))
	 * $this->success('操作成功');
	 * }
	 * public function actionIndex(){
	 * $dataProvider=new CActiveDataProvider('StatReport');
	 * $this->render('index', array(
	 * 'dataProvider'=>$dataProvider
	 * ));
	 * }
	 * public function actionAdmin(){
	 * $model=new StatReport('search');
	 * $model->unsetAttributes(); // clear any default values
	 * if (isset($_GET['StatReport']))
	 * $model->attributes=$_GET['StatReport'];
	 *
	 * $this->render('admin', array(
	 * 'model'=>$model
	 * ));
	 * }
	 */

	/**
	 * 流量统计
	 * @date: 2015-3-30
	 * @author :
	 */
	function actionFlow(){
		set_time_limit(120); // 最长两分钟的执行时间
		/**
		 * **************************************************
		 */
		if (!Yii::app()->cache->get('_cache_flow')){
			// echo 'sss';
			Yii::app()->cache->set('_cache_flow', '1', 60); // 60秒更新
			                                              // 统计今天
			$d=0;
			$t=date('Y-m-d', strtotime("$d day"));
			$t2=date('Y-m-d', strtotime(($d+1)." day"));
			// 总pv,总uv,Ip数
			$data1=Yii::app()->db->createCommand()
				->select('SUM(pv) as pv, COUNT( DISTINCT wxid ) as uv,COUNT( DISTINCT ip ) as ip,aid,ghid')
				->from('stat_data')
				->where(" ghid='".gh()->ghid."' and rtime>'".$t."' and rtime<'".$t2."'")
				->group('ghid,aid')
				->queryAll();
			// 新用户，
			$adduData=Yii::app()->db->createCommand()
				->select('COUNT( DISTINCT wxid )  as addu')
				->from('stat_data')
				->where(" ghid='".gh()->ghid."' and rtime>'".$t."' and rtime<'".$t2."' and cid=1 ")
				->group('ghid,aid')
				->queryAll();
			// dump($data1);exit;
			// 1分享到好友 2分享到朋友圈3分享到QQ4分享到微博
			$data2=Yii::app()->db->createCommand()
				->select('count(id) num,aid,ghid,shareType')
				->from('stat_data')
				->where("(shareType=1 or shareType=2 or shareType=3 or shareType=4) and ghid='".gh()->ghid."' and rtime>'".$t."' and rtime<'".$t2."'")
				->group('ghid,shareType,aid')
				->queryAll();
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
		/**
		 * **************************************************
		 */

		$model=new StatReport('search_flow');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['StatReport']))
			$model->attributes=$_GET['StatReport'];
			// $m=$model->search_flow();
			// dump($m->data);exit;
		$this->render('flow', array(
			'model'=>$model,
			'r1'=>$_GET['StatReport']['day'][1],
			'r2'=>$_GET['StatReport']['day'][2]
		));
	}
	/**
	 * 移动设备
	 * @date: 2015-3-31
	 * @author :
	 */
	function actionMobile(){
		$model=new StatData('search_detail');
		$model->unsetAttributes();
		if (isset($_GET['StatData']))
			$model->attributes=$_GET['StatData'];
		$where='';
		if (!empty($_GET['StatData']['aid']))
			$where.=' and aid='.intval($_GET['StatData']['aid']);
		if (!empty($_GET['StatData']['rtime'][1]))
			$where.=" and rtime>='".$_GET['StatData']['rtime'][1]."'";
		if (!empty($_GET['StatData']['rtime'][2]))
			$where.=" and rtime<='".$_GET['StatData']['rtime'][2]."'";
		if (empty($_GET['StatData']['rtime'][1])&&empty($_GET['StatData']['rtime'][2])){
			$where.=" and rtime>='".date('Y-m-d', strtotime("-7 day"))."'";
			// $where.=" and rtime<='".date('Y-m-d')."'";
		}

		$os=Yii::app()->db->createCommand()
			->select('count(id) num,os')
			->from('stat_data')
			->where("ghid='".gh()->ghid."'  $where")
			->group('os')
			->queryAll();
		// select count(id) num,mobile from stat_data group by mobile ORDER BY num desc LIMIT 15;
		$mobile=Yii::app()->db->createCommand()
			->select('count(id) num,mobile')
			->from('stat_data')
			->where("ghid='".gh()->ghid."'  $where")
			->group('mobile')
			->order('num desc')
			->limit(15)
			->queryAll();
		// dump($os);
		$cc=array(
			'os'=>$os,
			'mobile'=>$mobile,
			'model'=>$model,
			'r1'=>$_GET['StatData']['rtime'][1],
			'r2'=>$_GET['StatData']['rtime'][2]
		);

		$this->render('mobile', $cc);
	}
	/**
	 * 访问详细
	 * @date: 2015-3-31
	 * @author :
	 */
	function actionDetail(){
		if ($_GET['export']){
			set_time_limit(600); // 最多10分钟超时
			$criteria=new CDbCriteria();
			$model=new StatData('search_detail');
			$model->unsetAttributes();
			if (isset($_GET['StatData']))
				 $model->attributes=$_GET['StatData'];
			$count=$model->search_detail($criteria)->totalItemCount;
			//if ($count>10000){
				$page=ceil($count/10000);
				header("Pragma: public");
				header("Expires: 0");
				header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
				header("Content-Type:application/force-download");
				header("Content-Type:application/vnd.ms-execl");
				header("Content-Type:application/octet-stream");
				header("Content-Type:application/download");
				header('Content-Type: application/vnd.ms-excel');
				header('Cache-Control: max-age=0');
				header("Content-Disposition:attachment;filename=访问详情.csv");
				header("Content-Transfer-Encoding:binary");
				$head = array(/*'微信昵称', '登记姓名', '登记手机号',*/ '页面标题', '屏幕辨率分', '微信版本', '网络类型', '操作系统', '系统版本', '手机型号', '所在在省', '所在城市', '网络运营商', '访问时间');
				$fp=fopen('php://output', 'a');
				// 输出Excel列名信息
				foreach ($head as $i=>$v){
					// CSV的Excel支持GBK编码，一定要转换，否则乱码
					$head[$i]=iconv("UTF-8", "gb2312//IGNORE", $v);
				}
				// 将数据通过fputcsv写到文件句柄
				fputcsv($fp, $head);
				for ($i=0; $i<$page; $i++){
					$m=$model->search_detail($criteria,$i,10000);
					$data=$m->data;
					if ($data){
						// 计数器
						$cnt=0;
						// 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
						$limit=100000;
						$pcount=count($data);
						// 逐行取出数据，不浪费内存
						for ($t=0; $t<$pcount; $t++){
							$cnt++;
							if ($limit==$cnt){ // 刷新一下输出buffer，防止由于数据过多造成问题
								ob_flush();
								flush();
								$cnt=0;
							}
							$row=$data[$t];
							/*$tpm[]=iconv("UTF-8", "gb2312//IGNORE", $row['wxuser']->nickname);
							$tpm[]=iconv("UTF-8", "gb2312//IGNORE", $row['wxuserReg']->username);
							$tpm[]=iconv("UTF-8", "gb2312//IGNORE", $row['wxuserReg']->phone."\t");*/
							$tpm[]=iconv("UTF-8", "gb2312//IGNORE", $row['title']);
							$tpm[]=iconv("UTF-8", "gb2312//IGNORE", $row['screen']."\t");
							$tpm[]=iconv("UTF-8", "gb2312//IGNORE", $row['brvsub']."\t");
							$tpm[]=iconv("UTF-8", "gb2312//IGNORE", $row['netType']);
							$tpm[]=iconv("UTF-8", "gb2312//IGNORE", $row['os']."\t");
							$tpm[]=iconv("UTF-8", "gb2312//IGNORE", $row['osv']."\t");
							$tpm[]=iconv("UTF-8", "gb2312//IGNORE", $row['mobile']."\t");
							$tpm[]=iconv("UTF-8", "gb2312//IGNORE", $row['region']."\t");
							$tpm[]=iconv("UTF-8", "gb2312//IGNORE", $row['city']."\t");
							$tpm[]=iconv("UTF-8", "gb2312//IGNORE", $row['isp']."\t");
							$tpm[]=iconv("UTF-8", "gb2312//IGNORE", $row['rtime']."\t");
							fputcsv($fp, $tpm);
							unset($row);
							unset($tpm);
						}
						unset($data);
					}
				}
			//}

			exit();
		}

		$model=new StatData('search_detail');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['StatData']))
			$model->attributes=$_GET['StatData'];
			// dump($model->search_detail(5000)->data);
		$this->render('detail', array(
			'model'=>$model
		));
	}
	/**
	 * 新增独立用户
	 * @date: 2015-3-31
	 * @author : wintrue<328945440@qq.com>
	 */
	function actionNouser(){
		$model=new StatData('search_detail');
		$model->unsetAttributes();
		if (isset($_GET['StatData']))
			$model->attributes=$_GET['StatData'];
		$where='';
		if (!empty($_GET['StatData']['aid']))
			$where.=' and aid='.intval($_GET['StatData']['aid']);
		if (!empty($_GET['StatData']['rtime'][1]))
			$where.=" and rtime>='".$_GET['StatData']['rtime'][1]."'";
		if (!empty($_GET['StatData']['rtime'][2]))
			$where.=" and rtime<='".$_GET['StatData']['rtime'][2]."'";
		if (empty($_GET['StatData']['rtime'][1])&&empty($_GET['StatData']['rtime'][2])){
			$where.=" and rtime>='".date('Y-m-d', strtotime("-7 day"))."'";
			// $where.=" and rtime<='".date('Y-m-d')."'";
		}
		$data1=Yii::app()->db->createCommand()
			->select('DATE_FORMAT(rtime,"%Y%m%d") days,COUNT( DISTINCT wxid ) as cv')
			->from('stat_data')
			->where("ghid='".gh()->ghid."' $where and cid=1 ")
			->group('days')
			->queryAll();
		$this->render('nouser', array(
			'data1'=>$data1,
			'model'=>$model,
			'r1'=>$_GET['StatData']['rtime'][1],
			'r2'=>$_GET['StatData']['rtime'][2]
		));
	}
	/**
	 * 地区分布
	 * @date: 2015-3-31
	 * @author : wintrue<328945440@qq.com>
	 */
	function actionAisp(){
		$model=new StatData('search_detail');
		$model->unsetAttributes();
		if (isset($_GET['StatData']))
			$model->attributes=$_GET['StatData'];
		$where='';
		if (!empty($_GET['StatData']['aid']))
			$where.=' and aid='.intval($_GET['StatData']['aid']);
		if (!empty($_GET['StatData']['rtime'][1]))
			$where.=" and rtime>='".$_GET['StatData']['rtime'][1]."'";
		if (!empty($_GET['StatData']['rtime'][2]))
			$where.=" and rtime<='".$_GET['StatData']['rtime'][2]."'";
		if (empty($_GET['StatData']['rtime'][1])&&empty($_GET['StatData']['rtime'][2])){
			$where.=" and rtime>='".date('Y-m-d', strtotime("-7 day"))."'";
			// $where.=" and rtime<='".date('Y-m-d')."'";
		}

		$field='region';
		$data1=Yii::app()->db->createCommand()
			->select("DISTINCT  $field, count(*) num")
			->from('stat_data')
			->where("ghid='".gh()->ghid."' $where ")
			->group("$field")
			->order('num desc')
			->limit(10)
			->queryAll();
		$this->render('aisp', array(
			'field'=>$field,
			'data1'=>$data1,
			'model'=>$model,
			'r1'=>$_GET['StatData']['rtime'][1],
			'r2'=>$_GET['StatData']['rtime'][2]
		));
	}
	/**
	 * 网络运营商
	 * @date: 2015-3-31
	 * @author : wintrue<328945440@qq.com>
	 */
	function actionAisp2(){
		$model=new StatData('search_detail');
		$model->unsetAttributes();
		if (isset($_GET['StatData']))
			$model->attributes=$_GET['StatData'];
		$where='';
		if (!empty($_GET['StatData']['aid']))
			$where.=' and aid='.intval($_GET['StatData']['aid']);
		if (!empty($_GET['StatData']['rtime'][1]))
			$where.=" and rtime>='".$_GET['StatData']['rtime'][1]."'";
		if (!empty($_GET['StatData']['rtime'][2]))
			$where.=" and rtime<='".$_GET['StatData']['rtime'][2]."'";
		if (empty($_GET['StatData']['rtime'][1])&&empty($_GET['StatData']['rtime'][2])){
			$where.=" and rtime>='".date('Y-m-d', strtotime("-7 day"))."'";
			// $where.=" and rtime<='".date('Y-m-d')."'";
		}

		$field='isp';
		$data1=Yii::app()->db->createCommand()
		->select("DISTINCT  $field, count(*) num")
		->from('stat_data')
		->where("ghid='".gh()->ghid."' $where ")
		->group("$field")
		->order('num desc')
		->limit(10)
		->queryAll();
		$this->render('aisp2', array(
			'field'=>$field,
			'data1'=>$data1,
			'model'=>$model,
			'r1'=>$_GET['StatData']['rtime'][1],
			'r2'=>$_GET['StatData']['rtime'][2]
		));
	}
	/**
	 * 当前在线，以15分钟内为在线
	 * @date: 2015-3-31
	 * @author : wintrue<328945440@qq.com>
	 */
	function actionOnline(){
		$model=new StatData('search_detail');
		$model->unsetAttributes();
		if (isset($_GET['StatData']))
			$model->attributes=$_GET['StatData'];
		$where='';
		if (!empty($_GET['StatData']['aid']))
			$where.=' and aid='.intval($_GET['StatData']['aid']);

		if ($_POST['dynamic']){
			$where.=" and rtime>='".date('Y-m-d H:i:s', time()-15*60)."'";
			$data1=Yii::app()->db->createCommand()
				->select('SUM(pv) as pv, SUM(cid) as cv, COUNT( DISTINCT wxid ) as uv,COUNT( DISTINCT ip ) as ip')
				->from('stat_data')
				->where("ghid='".gh()->ghid."' $where")
				->queryRow();
			foreach ($data1 as $k=>$v){
				$data1[$k]=intval($v);
			}
			echo json_encode($data1);
			exit();
		}else{
			$data1=Yii::app()->db->createCommand("SELECT SUM(pv) as pv, SUM(cid) as cv, COUNT( DISTINCT wxid ) as uv,COUNT( DISTINCT ip ) as ip from stat_data where rtime>=DATE_SUB(NOW(),INTERVAL 15*60+5*8 SECOND) and ghid='".gh()->ghid."' $where union all SELECT SUM(pv) as pv, SUM(cid) as cv, COUNT( DISTINCT wxid ) as uv,COUNT( DISTINCT ip ) as ip from stat_data where rtime>=DATE_SUB(NOW(),INTERVAL 15*60+5*7 SECOND) and ghid='".gh()->ghid."' $where union all SELECT SUM(pv) as pv, SUM(cid) as cv, COUNT( DISTINCT wxid ) as uv,COUNT( DISTINCT ip ) as ip from stat_data where rtime>=DATE_SUB(NOW(),INTERVAL 15*60+5*6 SECOND) and ghid='".gh()->ghid."' $where union all SELECT SUM(pv) as pv, SUM(cid) as cv, COUNT( DISTINCT wxid ) as uv,COUNT( DISTINCT ip ) as ip from stat_data where rtime>=DATE_SUB(NOW(),INTERVAL 15*60+5*5 SECOND) and ghid='".gh()->ghid."' $where union all SELECT SUM(pv) as pv, SUM(cid) as cv, COUNT( DISTINCT wxid ) as uv,COUNT( DISTINCT ip ) as ip from stat_data where rtime>=DATE_SUB(NOW(),INTERVAL 15*60+5*4 SECOND) and ghid='".gh()->ghid."' $where union all SELECT SUM(pv) as pv, SUM(cid) as cv, COUNT( DISTINCT wxid ) as uv,COUNT( DISTINCT ip ) as ip from stat_data where rtime>=DATE_SUB(NOW(),INTERVAL 15*60+5*3 SECOND) and ghid='".gh()->ghid."' $where union all SELECT SUM(pv) as pv, SUM(cid) as cv, COUNT( DISTINCT wxid ) as uv,COUNT( DISTINCT ip ) as ip from stat_data where rtime>=DATE_SUB(NOW(),INTERVAL 15*60+5*2 SECOND) and ghid='".gh()->ghid."' $where union all SELECT SUM(pv) as pv, SUM(cid) as cv, COUNT( DISTINCT wxid ) as uv,COUNT( DISTINCT ip ) as ip from stat_data where rtime>=DATE_SUB(NOW(),INTERVAL 15*60+5*1 SECOND) and ghid='".gh()->ghid."' $where ")->queryAll();
			$this->render('online', array(
				'data1'=>$data1,
				'model'=>$model
			));
		}
	}
	/**
	 * 获取所有活动
	 * @date: 2014-12-10
	 * @author : wintrue<328945440@qq.com>
	 * @return
	 *
	 */
	function getActList(){
		$criteria=new CDbCriteria();
		// $criteria->select='id,ptype';
		$criteria->with='plugin';
		$criteria->addCondition("t.ghid='".gh()->ghid."'"); // 公众号开通的
		return Activity::model()->findAll($criteria); // $params is not needed
	}
	public function loadModel($id){
		$model=StatReport::model()->findByPk($id);
		if ($model===null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * AJAX验证
	 * @param StatReport $model
	 * 模型验证
	 */
	protected function performAjaxValidation($model){
		if (isset($_POST['ajax'])&&$_POST['ajax']==='stat-report-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
