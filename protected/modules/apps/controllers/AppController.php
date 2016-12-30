<?php
/**
 * AppController.php 所有微应用的基类
 * ----------------------------------------------
 * 版权所有 2014-2015
 * ----------------------------------------------
 * @date: 2014-8-14
 *
 */
yii::import('ext.AppCore');
class AppController extends AppCore{
	/**
	 * 由于某些微信用户没有头像，因以此函数进行头像处理，没有头像则返回一张默认头像
	 * @date: 2014-9-16
	 * @author : 佚名
	 * @param string $headimgurl
	 */
	function getHeadimgurl($headimgurl){
		return $headimgurl ? $headimgurl : $this->_defaultHeadimg;
	}
	/**
	 * 判断是否是AJAX提交，是根据AJAX特性进行判断，非带参数标识的判断
	 * @date: 2014-9-15
	 * @author : 佚名
	 */
	function isAjax(){
		return isset($_SERVER['HTTP_X_REQUESTED_WITH'])&&$_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest';
	}
	/**
	 * 判断是否关注，对file_get_contents进行超时限定为5秒，超时3次则提示访问接口超时
	 * @date: 2014-9-16
	 * @author : 佚名
	 */
	function checkSubscribe(){
		$opts=array(
			'http'=>array(
				'method'=>"GET",
				'timeout'=>5
			)
		); // 单位秒

		$cnt=0;
		while ($cnt<3&&($result=@file_get_contents(''.OAUTH_URL_CORE.'/checkSubscribe?openid='.$this->userinfo['openid'].'&ghid='.$this->activity['ghid'], false, stream_context_create($opts)))===FALSE){
			$cnt++;
		}
		if ($result===FALSE){
			// $this->log('关注接口访问超时');
			// $this->error('关注接口访问超时,请重新进入应用！',Yii::app ()->request->hostInfo . $this->createUrl ( $_GET ['_akey']));
		}else{
			return intval($result);
		}
	}
	/**
	 * 插入数据common_record公共表
	 * @date: 2014-9-10
	 * @author : 佚名
	 * @param array $dataArr
	 * 例如 array('username'=>$uname,'phone'=>$phone,'ext_info'=>$extinfo) 数据的键名对应数据表的字段名
	 */
	public function saveUserinfo($dataArr){
		$conn=Yii::app()->db;
		$sql="select * from common_record where wxid='".$this->userinfo['openid']."' and aid=".$this->activity['aid']." and ghid='".$this->activity['ghid']."'";
		$command=$conn->createCommand($sql);
		$row=$command->queryRow();
		if (empty($row)){
			$rdata=array(
				'aid'=>$this->activity['aid'],
				'wxid'=>$this->userinfo['openid'],
				'ghid'=>$this->activity['ghid'],
				'ip'=>Yii::app()->request->userHostAddress,
				'ua'=>$_SERVER['HTTP_USER_AGENT'],
				'ctm'=>date('Y-m-d H:i:s', time())
			);
			$insertData=array_merge($rdata, $dataArr);
			$conn->createCommand()->insert('common_record', $insertData);
		}else{
			$rdata=array(
				'ip'=>Yii::app()->request->userHostAddress,
				'ua'=>$_SERVER['HTTP_USER_AGENT']
			);
			$updateData=array_merge($rdata, $dataArr);
			$conn->createCommand()->update('common_record', $updateData, "wxid='".$this->userinfo['openid']."' and aid=".$this->activity['aid']." and ghid='".$this->activity['ghid']."'");
		}
		unset($row);
	}
	public function assign($tpl_var, $value = null, $nocache = false){
		Yii::app() -> smarty -> assign($tpl_var, $value,$nocache);
	}
	public function display($view) {
		// -- 初始全局数据
		Yii::app() -> smarty -> assign('base_url',$this->SURL());
		Yii::app() -> smarty -> assign('this',$this);
		Yii::app() -> smarty -> display($view.'.php');
	}

	/**
	 * 用于调用主题下的资源文件，如果有资源包，则调用资源包,有时CND时配置CDN
	 *
	 * @param 文件路径 $file
	 * @param 是否路径是全网址的（带http） $fullpath
	 */
	function SURL(){
		if (is_dir(ROOT_PATH.'/microapp/'.$GLOBALS['_appName'].'/resources/'.$this->activity['aid'])){
			return Yii::app()->params['preImageUrl'].'/microapp/'.$GLOBALS['_appName'].'/resources/'.$this->activity['aid'];
		}else{
			return Yii::app()->params['preImageUrl'].Yii::app()->theme->baseUrl;
		}

	}
}