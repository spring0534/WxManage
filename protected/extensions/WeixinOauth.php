<?php
/**
 * WeixinOauth.php
 * ----------------------------------------------
 * 版权所有 2014-2015 
 * ----------------------------------------------
 * @date: 2014-8-14
 * 
 */
class WeixinOauth {
	
	private $appid;
	private $secret;
	private $scope;
	public $debug = FALSE;
	public $error;
	/**
	 * @param  $appid
	 * @param  $secret
	 * @param  $scope 默认为snsapi_userinfo （弹出授权页面，可通过openid拿到昵称、性别、所在地。并且，即使在未关注的情况下，只要用户授权，也能获取其信息）
	 * 另一项值snsapi_base （不弹出授权页面，直接跳转，只能获取用户openid）
	 */
	public function __construct($appid, $secret, $scope = 0) {
		if (empty ( $appid ) || empty ( $secret )) {
			exit ( 'openid和secret参数是必须的！' );
		}
		$this->appid = $appid;
		$this->secret = $secret;
		if ($scope == 1) {
			$this->scope = 'snsapi_userinfo';
		} else {
			$this->scope = 'snsapi_base';
		}
	
	}
	/**
	 * 中转授权页
	 *
	 * @param 回调URL $redirect_uri
	 */
	public function location($redirect_uri,$state) {
		
		header ( 'Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->appid . '&redirect_uri=' . urlencode ( $redirect_uri ) . '&response_type=code&scope=' . $this->scope . '&state='.$state.'#wechat_redirect' );exit();
	}
	/**
	 * 中转授权页
	 *
	 * @param 回调URL $redirect_uri
	 */
	public function location2($redirect_uri) {
		//exit('Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->appid . '&redirect_uri=' . urlencode ( 'http://cgi.trade.qq.com/cgi-bin/comm/login/oauth2.fcg?appid=' . $this->appid  ) . '&subs=no&response_type=code&scope=' . $this->scope . '&state='.$redirect_uri.'#wechat_redirect');
		header ( 'Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->appid . '&redirect_uri=' . urlencode ( 'http://cgi.trade.qq.com/cgi-bin/comm/login/oauth2.fcg?appid=' . $this->appid  ) . '&subs=no&response_type=code&scope=' . $this->scope . '&state='.$redirect_uri.'#wechat_redirect' );exit();
		
		//https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx7b2e0acb2f51a16b&redirect_uri=http://cgi.trade.qq.com/cgi-bin/comm/login/oauth2.fcg?appid=wx7b2e0acb2f51a16b&subs=no&response_type=code&scope=snsapi_base&state=http://wap.tenpay.com/#wechat_redirect
	}
	/**
	 * 获取token
	 * 
	 * @param 从授权页返回的code参数 $code
	 */
	function getToken($code) {
		return $this->http_get_result( 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->appid . '&secret=' . $this->secret. '&code='.$code.'&grant_type=authorization_code' );
	
	}
	/**
	 * 
	 * 刷新access_token
	 * @param unknown_type $refresh_token
	 */
	function refreshToken($refresh_token){
	
		return $this->http_get_result( 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$this->appid.'&grant_type=refresh_token&refresh_token='.$refresh_token);
	
	}
	/**
	 *获取用户信息
	 * 
	 * @param unknown_type $access_token
	 * @param unknown_type $openid
	 */
	function getUserinfo($access_token,$openid){
		return $this->http_get_result( "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN");
	
	}
	/**
	 * 检验授权凭证（access_token）是否有效
	 * 
	 * @param unknown_type $access_token
	 * @param unknown_type $openid
	 */
	function checkAccess_token($access_token,$openid){
		return $this->http_get_result( "https://api.weixin.qq.com/sns/auth?access_token=".$access_token."&openid=".$openid);
	
	}
	function http_get_result($url) {
		yii::import ( 'ext.Http' );
		$ihttp = new Http ();
		$content = $ihttp->http_get ( $url );
		return $this->result ( $content );
	}

	
	
	/**
	 * 统一对返回数据处理
	 * @param  $content
	 */
	function result($content) {
		if (is_array ( $content )) {
			$result = json_decode ( $content ['content'], true );
			if (empty ( $result ['errcode'] )) {
				return $result;
			} else {
				if ($this->debug) {
					$this->error = "微信公众平台返回接口错误. \n错误代码为: {$result['errcode']} \n错误信息为: {$result['errmsg']} \n错误描述为: " . $this->error_code ( $result ['errcode'] );
				}
				return false;
			}
		} else {
			if ($this->debug) {
				$this->error = $content;
			}
			return false;
		}
	
	}
	private function error_code($code) {
		$errors = array ('-1' => '系统繁忙', '0' => '请求成功', '40001' => '获取access_token时AppSecret错误，或者access_token无效', '40002' => '不合法的凭证类型', '40003' => '不合法的OpenID', '40004' => '不合法的媒体文件类型', '40005' => '不合法的文件类型', '40006' => '不合法的文件大小', '40007' => '不合法的媒体文件id', '40008' => '不合法的消息类型', '40009' => '不合法的图片文件大小', '40010' => '不合法的语音文件大小', '40011' => '不合法的视频文件大小', '40012' => '不合法的缩略图文件大小', '40013' => '不合法的APPID', '40014' => '不合法的access_token', '40015' => '不合法的菜单类型', '40016' => '不合法的按钮个数', '40017' => '不合法的按钮个数', '40018' => '不合法的按钮名字长度', '40019' => '不合法的按钮KEY长度', '40020' => '不合法的按钮URL长度', '40021' => '不合法的菜单版本号', '40022' => '不合法的子菜单级数', '40023' => '不合法的子菜单按钮个数', '40024' => '不合法的子菜单按钮类型', '40025' => '不合法的子菜单按钮名字长度', '40026' => '不合法的子菜单按钮KEY长度', '40027' => '不合法的子菜单按钮URL长度', '40028' => '不合法的自定义菜单使用用户', '40029' => '不合法的oauth_code', '40030' => '不合法的refresh_token', '40031' => '不合法的openid列表', '40032' => '不合法的openid列表长度', '40033' => '不合法的请求字符，不能包含\uxxxx格式的字符', '40035' => '不合法的参数', '40038' => '不合法的请求格式', '40039' => '不合法的URL长度', '40050' => '不合法的分组id', '40051' => '分组名字不合法', '41001' => '缺少access_token参数', '41002' => '缺少appid参数', '41003' => '缺少refresh_token参数', '41004' => '缺少secret参数', '41005' => '缺少多媒体文件数据', '41006' => '缺少media_id参数', '41007' => '缺少子菜单数据', '41008' => '缺少oauth code', '41009' => '缺少openid', '42001' => 'access_token超时', '42002' => 'refresh_token超时', '42003' => 'oauth_code超时', '43001' => '需要GET请求', '43002' => '需要POST请求', '43003' => '需要HTTPS请求', '43004' => '需要接收者关注', '43005' => '需要好友关系', '44001' => '多媒体文件为空', '44002' => 'POST的数据包为空', '44003' => '图文消息内容为空', '44004' => '文本消息内容为空', '45001' => '多媒体文件大小超过限制', '45002' => '消息内容超过限制', '45003' => '标题字段超过限制', '45004' => '描述字段超过限制', '45005' => '链接字段超过限制', '45006' => '图片链接字段超过限制', '45007' => '语音播放时间超过限制', '45008' => '图文消息超过限制', '45009' => '接口调用超过限制', '45010' => '创建菜单个数超过限制', '45015' => '回复时间超过限制', '45016' => '系统分组，不允许修改', '45017' => '分组名字过长', '45018' => '分组数量超过上限', '46001' => '不存在媒体数据', '46002' => '不存在的菜单版本', '46003' => '不存在的菜单数据', '46004' => '不存在的用户', '47001' => '解析JSON/XML内容错误', '48001' => 'api功能未授权', '50001' => '用户未授权该api' );
		$code = strval ( $code );
		if ($code == '40001') {
			$rec = array ();
			$rec ['access_token'] = '';
			return '微信公众平台授权异常, 系统已修复这个错误, 请刷新页面重试.';
		}
		if ($errors [$code]) {
			return $errors [$code];
		} else {
			return '未知错误';
		}
	}
}