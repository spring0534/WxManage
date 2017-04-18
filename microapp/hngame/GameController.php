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
		$this->renderPartial('/game/index');
	}
	
	function actionQuery(){
	    $keyword = Yii::app()->request->getParam("keyword","");
	    if(empty($keyword)){
			$this->renderPartial('/game/query');
	    }else{
	    	$hngameUsers = Yii::app()->db2->createCommand()->select("realname,REPLACE(phone, SUBSTR(phone,4,4), '****') phone,company,position,address,qq,wxname,email,resource,demand")
	    	    ->from('hngame_user')
				->where("realname LIKE '%".$keyword."%' 
						OR company LIKE '%".$keyword."%' 
						OR position LIKE '%".$keyword."%' 
						OR address LIKE '%".$keyword."%' 
						OR resource LIKE '%".$keyword."%' 
						OR demand LIKE '%".$keyword."%'")
	    		->queryAll();
			$this->ajaxReturn(0,'success', $hngameUsers);
	    }
	}
	
    //保存用户信息
	function actionSave(){
		$row = Yii::app ()->db2->createCommand()->insert('hngame_user', array(
				'openid' => $this->userinfo['openid'],
				'nickname' => $this->userinfo['nickname'],
				'headimgurl' => $this->userinfo['headimgurl'],
				'realname' => $_POST['realname'],
				'phone' => $_POST['phone'],
				'company' => $_POST['company'],
				'position' => $_POST['position'],
				'address' => $_POST['address'],
				'qq' => $_POST['qq'],
				'wxname' => $_POST['wxname'],
				'email' => $_POST['email'],
				'resource' => $_POST['resource'],
				'demand' => $_POST['demand']
		));
		if($row > 0){
			$this->ajaxReturn(0, '保存成功！');
		}else{
			$this->ajaxReturn(-1, '保存失败！');
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