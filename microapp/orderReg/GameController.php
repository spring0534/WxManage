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
		$tb_order_no = functions::escape($_POST['tb_order_no']);
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
//			    kf_send_text_msg(gh()->ghid, 'ovJ-Jwx2YtnAxq5EB-FSxk-zp3GY,ovJ-Jw2HUJohmIoW2Y16A0gAzKfw', $this->userinfo["nickname"].'提交了订单编号['.$tb_order_no.']请审核！');  //发送指定人员提醒消息
				$this->ajaxReturn(0, empty($this->setting['submitTips']) ? '订单审核中，请确认评价晒图哦，晒图后才能发奖呢！一般当天完成审核发放，请及时关注！' : $this->setting['submitTips']);
			}
		}else{
			$this->ajaxReturn(-1, '订单编号('.$tb_order_no.')已提交过，不能重复提交！');
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