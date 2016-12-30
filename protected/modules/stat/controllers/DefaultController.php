<?php

/**
* 数据统计
* DefaultController.php
* ----------------------------------------------
* 版权所有 2014-2015
* ----------------------------------------------
* @date: 2015-3-24
*
*/
class DefaultController extends Controller{
	function _get($var=''){
		if (empty($var))
			return $_GET;
		return $_GET[$var];
	}
	function actionIndex(){
		$data=$this->_get();
		$tid=intval($data['tid']); // 网站标识 为空时默认微应用统计
		switch ($tid){
			case 0: // 微应用统计
				$data['aid']=intval($data['aid']);
				$data['cid']=intval($data['cid']);
				$data['tid']=intval($data['tid']);
				$data['pv']=intval($data['pv']);
				$data['srcType']=intval($data['srcType']);
				$data['logType']=intval($data['logType']);
				$data['shareType']=intval($data['shareType']);
				$data['netType'] = preg_replace("/language.+/", "", $data['netType']);
				$model=new StatData();
				$model->attributes=$data;
				$ac=$activity=Activity::model()->findByPk($model->aid);
				if (empty($ac))
					return;
				$model->ghid=$ac->ghid;
				$model->ip=get_clientTrue_ip();
				//$model->pv=1;
				$model->pv=rand(1, 4);
				$model->ua=$_SERVER['HTTP_USER_AGENT'];
				$gh=Yii::app()->db->createCommand()
					->select('*')
					->from('sys_user_gh')
					->where('ghid=:ghid', array(
					':ghid'=>$model->ghid

				))->queryRow();
				if($gh){
					if($gh['oauth']=='1')
					switch ($gh['oauth']){
						case 1:
							// 科技
							$u=Yii::app()->db->createCommand()
							->select('*')
							->from('sys_wxuser')
							->where('ghid=:ghid and openid=:openid', array(
								':ghid'=>'gh_27764c76d3f6',
								':openid'=>$model->wxid
							))
							->queryRow();
							break;
						case 2:
							//
							$u=Yii::app()->db->createCommand()
							->select('*')
							->from('sys_wxuser')
							->where('ghid=:ghid and openid=:openid', array(
								':ghid'=>'gh_4ee536daed6d',
								':openid'=>$model->wxid
							))
							->queryRow();
							break;
						default:
							// 新老用户，以授权方式的公众号判断
							$u=Yii::app()->db->createCommand()
							->select('*')
							->from('sys_wxuser')
							->where('ghid=:ghid and openid=:openid', array(
								':ghid'=>$model->ghid,
								':openid'=>$model->wxid
							))
							->queryRow();
							break;
					}
					if (empty($u)){
						$model->cid=1;
					}else{
						$model->cid=0;
					}
				}
				// $addrArae = getAddrArea ( $dd ['ip'] );
				$ip=@file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=".$model->ip);
				$ip=json_decode($ip, true);
				if (is_array($ip)){
					$model->country=$ip['data']['country'];
					$model->region=$ip['data']['region'];
					$model->city=$ip['data']['city'];
					$model->area=$ip['data']['area'];
					$model->isp=$ip['data']['isp'];
				}
				foreach ($model->attributes as $k=>$v){

					if ($k=='mobile'){
						$model->$k=str_replace(array(';', $model->lg), '', $model->$k);
					}
					$model->$k=str_replace(';', '', $model->$k);
					if ($model->$k=='undefined'){
						$model->$k='';
					}
					if($k=='os'&&in_array($v, array('iPad','iPod'))){
						$model->mobile=$v;
					}
					$model->$k=trim($model->$k);
				}
				$model->brvsub=substr($model->brv, 0, 5);
				$model->realUrl=$model->url;
				$pattern='/(http.*)(\?)/i';
				preg_match($pattern, $model->url, $matches, PREG_OFFSET_CAPTURE);
				if (!empty($matches[1][0])){
					$model->url=$matches[1][0]; // 去除URL其它参数
				}

				$criteria=new CDbCriteria();
				$criteria->order='rtime DESC';
				foreach ($model->attributes as $kk=>$vv){
					if (!in_array($kk, array(
						'id',
						'cid',
						'pv',
						'rtime'
					))){
						$where[$kk]=$vv;
					}
				}
				$voo=StatData::model()->findByAttributes($where, $criteria);
				if ($voo){
					// 3分钟
					if ((time()-intval(strtotime($voo->rtime)))>300){
						$model->rtime=date('Y-m-d H:i:s');
						$model->save();
					}else{
						$voo->pv=intval($voo->pv)+1;
						$voo->save();
					}
				}else{
					$model->rtime=date('Y-m-d H:i:s');
					$model->save();
				}
				break;
			default:
				// exit ( '-1' );
				break;
		}
		$img=file_get_contents(ROOT_PATH.'/public/static/cssbg.png');
		header('Content-type: image/gif');
		echo $img;
	}
}