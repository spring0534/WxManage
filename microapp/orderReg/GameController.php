<?php
class GameController extends appController{
	private $isSubscribe=2; //是否已关注
	private $setting;       //活动配置
	private $aid;           //活动id
	private $openid;        //用户的openid
	private $ver=1.0;       //版本控制
	private $gametimes=10000; //游戏次数
	
	function init(){
		if (empty($this->userinfo)) {
			$this->error('网络超时，请稍后再试！');
		}
		$this->jssdk_debug = FALSE;
		$this->setting = $this->getActivityConfig();
		/* if($this->setting['isSubscribe'] == 1){
			$this->isSubscribe = empty($this->checkSubscribe()) ? 0 : 1;
		} */
		$this->aid = $this->activity['aid'];
		$this->openid = $this->userinfo['openid'];
		$this->ver = empty ( $this->setting['verControl'] ) ? '?ver='. $this->ver : '?ver='. trim ($this->setting['verControl']);
	}
	
	function actionIndex(){
		$this->renderPartial('/game/index', array(
	        'settings' => $this->setting,
	        'userinfo' => $this->userinfo,
	        'isSubscribe'=>$this->isSubscribe,
		 	'ver' => $this->ver,
        ));
	}
	
	//保存用户信息
	function actionSave(){
	    $tb_sncode = functions::escape($_POST['tb_sncode']);
	    if(!preg_match("/^[\d]{10}$/",$tb_sncode)){
	        $this->ajaxReturn(-1, '兑奖码('.$tb_order_no.')不正确！');
	    }
	    
	    //查询日志，判断是否超出输入错误次数
	    $redpackLogs = Yii::app ()->db2->createCommand()->select('*')->from('redpack_log')
	    ->where('openid=:openid and date(ctm) =:ctm',array(
	    		':openid' => $this->userinfo['openid'],
	    		'ctm'=> date('Y-m-d',time())
	    ))->queryAll();
	    if(isset($redpackLogs) && count($redpackLogs) >= 3){
	    	$this->ajaxReturn(-1, '当天输入兑奖码次数达到上限！');
	    }
	    
	    $redpackSncode = Yii::app ()->db2->createCommand()->select('*')->from('redpack_sncode')
	    ->where('aid=:aid and sncode=:tb_sncode',array(
	        ':aid' => $this->activity['aid'],
	        'tb_sncode'=>$tb_sncode
	    ))->queryRow();
	    
	    //保存日志
	    $sncodeStatus = -1;
	    if($redpackSncode) $sncodeStatus = 1;
	    Yii::app ()->db2->createCommand()->insert('redpack_log', array(
    		'aid' => $this->activity['aid'],
    		'openid' => $this->userinfo['openid'],
    		'content' => $tb_sncode,
    		'status' => $sncodeStatus
	    ));
	    
	    if($sncodeStatus == 1){
	    	$amount = rand(1, 200); //1-200分随机一个金额
	    	$send_redpack_url = 'http://127.0.0.1:8888/wx/wx/cgi/SendRedPack.do?ghid=%s&openid=%s&send_name=%s&billno=%s&amount=%s&wishing=%s&act_name=%s';
	    	$url = sprintf($send_redpack_url,'gh_10c28910fc87',$this->userinfo['openid'],urlencode('相遇互动'),$tb_sncode,$amount,urlencode('感谢您的惠顾，欢迎再来!'),urlencode('拆包裹奖'));
	    	$jsonStr = HttpUtil::getPage($url);
	    	$json = json_decode($jsonStr); //{"action":"","errorcode":"0","message":"success","datastr":""}
	    	if(isset($json)){
	    		if($json->errorcode == 0){
	    			$row = Yii::app ()->db2->createCommand()->insert('redpack_task', array(
		    			'aid' => $this->activity['aid'],
		    			'tb_order_no' => $tb_sncode,
		    			'openid' => $this->userinfo['openid'],
		    			'nickname' => $this->userinfo['nickname'],
		    			'headimgurl' => $this->userinfo['headimgurl'],
		    			'amount' => $amount,
		    			'status' => 2, //派发成功
		    			'errmsg' => $json->message,
		    			'ctm' => date('Y-m-d H:i:s',time()),
		    			'utm' => date('Y-m-d H:i:s',time())
			    	));
	    			if($row > 0){
	    				$this->ajaxReturn(-1, '红包派发成功，请在公众号查收领取。');
	    			}
	    		}
	    	}
	    	$this->ajaxReturn(-1, '红包派发失败，请联系微信客服！');
	    }else{
	        $this->ajaxReturn(-1, '兑奖码('.$tb_sncode.')不正确！');
	    }
	}
	
    //保存用户信息
	function actionSave2(){
		$tb_order_no = functions::escape($_POST['tb_order_no']);
		if(!preg_match("/^[\d]{16}$/",$tb_order_no)){
		    $this->ajaxReturn(-1, '订单编号('.$tb_order_no.')不正确！');
		}
		$task = Yii::app ()->db2->createCommand()->select('*')->from('redpack_task')
				->where('aid=:aid and tb_order_no=:tb_order_no',array(
					':aid' => $this->activity['aid'],
					'tb_order_no'=>$tb_order_no
				))->queryRow();
		if(!$task){
			$row = Yii::app ()->db2->createCommand()->insert('redpack_task', array(
					'aid' => $this->activity['aid'],
					'tb_order_no' => $tb_order_no,
					'openid' => $this->userinfo['openid'],
					'nickname' => $this->userinfo['nickname'],
					'headimgurl' => $this->userinfo['headimgurl'],
					'ctm' => date('Y-m-d H:i:s',time()),
					'utm' => date('Y-m-d H:i:s',time())
			));
			if($row > 0){
				kf_send_text_msg($this->activity['ghid'], 'ovJ-Jw2HUJohmIoW2Y16A0gAzKfw', $this->userinfo["nickname"].'，提交了订单编号'.$tb_order_no.'，请审核！');  //发送指定人员提醒消息
				$this->ajaxReturn(0, empty($this->setting['submitTips']) ? '订单审核中，请确认评价晒图哦，晒图后才能发奖呢！一般当天完成审核发放，请及时关注！' : $this->setting['submitTips']);
			}
		}else{
			if($task['status'] == 0){ //审核失败状态，订单可以重新提交并更新为待审核状态
				$row = Yii::app()->db2->createCommand()->update('redpack_task', array(
						'status' => 1,
						'utm' => date('Y-m-d H:i:s',time())
				), 'aid =:aid and tb_order_no =:tb_order_no',array(
						':aid' => $this->activity['aid'],
						':tb_order_no' => $tb_order_no
				));
				kf_send_text_msg($this->activity['ghid'], 'ovJ-Jw2HUJohmIoW2Y16A0gAzKfw', $this->userinfo["nickname"].'，重新提交了订单编号'.$tb_order_no.'，请审核！');  //发送指定人员提醒消息
				$this->ajaxReturn(0, empty($this->setting['submitTips']) ? '订单重新审核中，请确认评价晒图哦，晒图后才能发奖呢！一般当天完成审核发放，请及时关注！' : $this->setting['submitTips']);
			}else{
				$this->ajaxReturn(-1, '订单编号('.$tb_order_no.')正在审核中...请勿重复提交！');
			}
		}
	}
	
	//查检用户状态
	function isForbidden($openid = null){
		if($openid == null){
			$u = oo::user()->getUserInfoOne($this->aid, $this->openid);
			if (!$u ['enable']) {
				$this->error('系统检测到你的游戏数据异常，已经作封号处理!');
				exit();
			}
		} else {
			$u = oo::user()->getUserInfoOne($this->aid, $openid);
			return !$u ['enable'] ? true : false;
		}
	}
	
	//ajax数据统一返回
	function ajaxReturn($result_code, $msg='', $data=''){
		echo json_encode(array(
			'result_code'=>$result_code,
			'result_msg'=>$msg,
			'result_data'=>$data
		));
		exit();
	}
	
	function SURL($fullpath = FALSE) {
		// $cdn='http://1251012119.cdn.myqcloud.com/1251012119/jump';
		$cdn = ''; // 暂用本地资源
		if (! empty($cdn)) {
			return $cdn;
		} else {
			if ($fullpath) {
				return Yii::app()->request->getHostInfo() . Yii::app()->theme->baseUrl;
			} else {
				return Yii::app()->theme->baseUrl;
			}
		}
	}
}
?>