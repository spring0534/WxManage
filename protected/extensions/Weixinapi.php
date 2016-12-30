<?php
class Weixinapi {
	/*$account=array(
		'AppId'=>'wxb8e65acf9fefe176',
		'AppSecret'=>'c35ccbd15908820df25a0279cdf53ed6',
		'access_token'=>'',
		'expire'=>''
		);*/
	private $account = null;
	public $debug = FALSE;
	public $error;
	public function __construct($uniAccount) {
		$this->account = $uniAccount;

		if (empty ( $this->account )) {
			exit ( 'error uniAccount id, can not construct ' );
		}
    	 if(empty($this->account ['access_token'])){
    	     //$access=Yii::app()->cache->get($this->account ['AppId'] );
    	     $access= Yii::app ()->db->createCommand()->select('access_token,at_expires')->from('sys_user_gh')->where('appid=:appid and appsecret=:appsecret',array(':appid'=>$this->account['AppId'],':appsecret'=>$this->account['AppSecret']))->queryRow();
    	     if(!empty($access)&&!empty($access['access_token'])&&!empty($access['at_expires'])&&strtotime($access['at_expires'])>time()){
    	         $this->account ['access_token']=$access['access_token'];
        	     $this->account ['expire']=$access['at_expires'];
    	     }
    	 }

    	 
	}

	public function fetchAccountInfo() {
		return $this->account;
	}
	/**
	 * 查询当前公号支持的统一消息类型, 当前支持的类型包括: text, image, voice, video, location, link, [subscribe, unsubscribe, qr, trace, click, view, enter]
	 * @return array 当前公号支持的消息类型集合
	 */
	public function queryAvailableMessages() {
		$messages = array ('text', 'image', 'voice', 'video', 'location', 'link', 'subscribe', 'unsubscribe' );
		if (! empty ( $this->account ['AppId'] ) && ! empty ( $this->account ['AppSecret'] )) {
			$messages [] = 'click';
			$messages [] = 'view';
			if (! empty ( $this->account ['AppId'] )) {
				$messages [] = 'qr';
				$messages [] = 'trace';
			}
		}
		return $messages;
	}
	/**
	 * 查询当前公号支持的统一响应结构
	 * @return array 当前公号支持的响应结构集合, 当前支持的类型包括: text, image, voice, video, music, news, link, card
	 */
	public function queryAvailablePackets() {
		$packets = array ('text', 'music', 'news' );
		if (! empty ( $this->account ['AppId'] ) && ! empty ( $this->account ['AppSecret'] )) {
			if (! empty ( $this->account ['AppId'] )) {
				$packets [] = 'image';
				$packets [] = 'voice';
				$packets [] = 'video';
			}
		}
		return $packets;
	}
	/**
	 * 是否支持自定义菜单
	 *
	 */
	public function isMenuSupported() {
		return ! empty ( $this->account ['AppId'] ) && ! empty ( $this->account ['AppSecret'] );
	}

	/**
	 * 创建菜单
	 * @param array $menu
	 * @return bool 是否创建成功
	 */
	public function menuCreate($menu) {
		$dat = decodeUnicode(json_encode ( $menu ));
		$token = $this->get_access_token ();
		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$token}";
		$result= $this->http_post_result ( $url, $dat );
		if($result=='refresh_access_token'){
		  	$dat = decodeUnicode(json_encode ( $menu ));
    		$token = $this->get_access_token ();
    		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$token}";
		    return   $this->http_post_result ( $url, $dat );
		}else{
		    return $result;
		}
	}
	/**
	 * 删除菜单
	 * @return bool 是否删除成功
	 */
	public function menuDelete() {
		$token = $this->get_access_token ();
		$url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token={$token}";
		$result= $this->http_get_result ( $url );
		if($result=='refresh_access_token'){
		    $token = $this->get_access_token ();
		    $url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token={$token}";
		    return  $this->http_get_result ( $url );
		}else{
		    return $result;
		}

	}
	/**
	 * 修改菜单
	 * @param array $menu 统一菜单结构
	 * @return bool 是否修改成功
	 */
	public function menuModify($menu) {
		return $this->menuCreate ( $menu );
	}
	/**
	 * 查询菜单
	 * @return array 统一菜单结构
	 */
	public function menuQuery() {
		$token = $this->get_access_token ();
		$url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token={$token}";
		$result= $this->http_get_result ( $url );
		if($result=='refresh_access_token'){
		    $token = $this->get_access_token ();
		    $url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token={$token}";
		    return  $this->http_get_result ( $url );
		}else{
		    return $result;
		}


	}

	/**
	 * 获取access_token
	 * retunr string access_token
	 */
	public function get_access_token($arr = FALSE) {

		if (! empty ( $this->account ['access_token'] ) && ! empty ( $this->account ['expire'] ) &&strtotime($this->account ['expire']) > time ()) {
		    return $this->account ['access_token'];
		} else {
		   	if (empty ( $this->account ['AppId'] ) || empty ( $this->account ['AppSecret'] )) {
				exit ( '请填写公众号的appid及appsecret！' );
			}
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->account['AppId']}&secret={$this->account['AppSecret']}";
			$token = $this->http_get_result ( $url );
			if (! $token)
				exit ( '获取access_token失败，请查看错误！' );
			$record = array ();
			$record ['access_token'] =  $token ['access_token'];
			$record ['at_expires'] = date('Y-m-d H:i:s',time () + $token ['expires_in']);
			Yii::app ()->db->createCommand()->update('sys_user_gh',$record,'appid=:appid and appsecret=:appsecret',array(':appid'=>$this->account['AppId'],':appsecret'=>$this->account['AppSecret']));
			//Yii::app()->cache->set($this->account['AppId'],$record,7200-60);
			if ($arr) {
				return $record;
			} else {
				return $record ['access_token'];
			}
		}
	}
	/**
	 * 创建带参数二维码
	 * POST数据格式
	 *
	 */
	public function qrcodeCreate($data) {
		$dat = $this->decodeUnicode ( json_encode ( $data ) );
		$dat = trim ( $dat, '[]' );
		$token = $this->get_access_token ();
		$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token={$token}";
		$result=$this->http_post_result ( $url, $dat );
		if($result=='refresh_access_token'){
			$dat = $this->decodeUnicode ( json_encode ( $data ) );
			$dat = trim ( $dat, '[]' );
			$token = $this->get_access_token ();
			$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token={$token}";
			return   $this->http_post_result ( $url, $dat );
		}else{
			return $result;
		}

	}
	/**
	 * 创建分组
	 * POST数据格式：json
	 * POST数据例子：{"group":{"name":"test"}} 原始数据如 array('gorup'=>array('name'=>'test'))
	 *
	 */
	public function groupsCreate($data) {
		$dat = $this->decodeUnicode ( json_encode ( $data ) );
		$dat = trim ( $dat, '[]' );
		$token = $this->get_access_token ();
		$url = "https://api.weixin.qq.com/cgi-bin/groups/create?access_token={$token}";
		$result=$this->http_post_result ( $url, $dat );
		if($result=='refresh_access_token'){
		    $dat = $this->decodeUnicode ( json_encode ( $data ) );
    		$dat = trim ( $dat, '[]' );
    		$token = $this->get_access_token ();
    		$url = "https://api.weixin.qq.com/cgi-bin/groups/create?access_token={$token}";
		    return   $this->http_post_result ( $url, $dat );
		}else{
		    return $result;
		}

	}
	/**
	 * 获取分组
	 *
	 */
	public function groupsGet() {
		$token = $this->get_access_token ();
		$url = "https://api.weixin.qq.com/cgi-bin/groups/get?access_token={$token}";
		$result= $this->http_get_result ( $url );
		if($result=='refresh_access_token'){
		    $token = $this->get_access_token ();
		    $url = "https://api.weixin.qq.com/cgi-bin/groups/get?access_token={$token}";
		    return  $this->http_get_result ( $url );
		}else{
		    return $result;
		}

	}

	/**
	 * 修改分组名
	 * @param array $data 例如 array('group'=>array('id'=>101,name=>'test'))
	 */
	public function groupsUpdate($data) {
		$token = $this->get_access_token ();
		$data = $this->decodeUnicode ( json_encode ( $data ) );
		$dat = trim ( $data, '[]' );
		$url = "https://api.weixin.qq.com/cgi-bin/groups/update?access_token={$token}";
		$result= $this->http_post_result ( $url, $dat );
		if($result=='refresh_access_token'){
    		$token = $this->get_access_token ();
    		$data = $this->decodeUnicode ( json_encode ( $data ) );
    		$dat = trim ( $data, '[]' );
    		$url = "https://api.weixin.qq.com/cgi-bin/groups/update?access_token={$token}";
		    return   $this->http_post_result ( $url, $dat );
		}else{
		    return $result;
		}
	}
	/**
	 * 查询用户所在分组
	 * string $openid
	 */
	public function groupsGetid($openid) {
		$token = $this->get_access_token ();
		$openid = json_encode ( array ('openid' => $openid ) );
		$dat = trim ( $openid, '[]' );
		$url = "https://api.weixin.qq.com/cgi-bin/groups/getid?access_token={$token}";
		$result= $this->http_post_result ( $url, $dat );
		if($result=='refresh_access_token'){
		    $token = $this->get_access_token ();
    		$openid = json_encode ( array ('openid' => $openid ) );
    		$dat = trim ( $openid, '[]' );
    		$url = "https://api.weixin.qq.com/cgi-bin/groups/getid?access_token={$token}";
		    return  $this->http_post_result ( $url, $dat );
		}else{
		    return $result;
		}
	}
	/**
	 * 将一个用移动到另一个分组
	 * string $openid
	 */
	public function groupsMembersUpdate($openid, $to_groupid) {
		$token = $this->get_access_token ();
		$openid = json_encode ( array ('openid' => $openid, 'to_groupid' => $to_groupid ) );
		$dat = trim ( $openid, '[]' );
		$url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token={$token}";
		$result= $this->http_post_result ( $url, $dat );
		if($result=='refresh_access_token'){
		    $token = $this->get_access_token ();
		    $openid = json_encode ( array ('openid' => $openid, 'to_groupid' => $to_groupid ) );
		    $dat = trim ( $openid, '[]' );
		    $url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token={$token}";
		    return  $this->http_post_result ( $url, $dat );
		}else{
		    return $result;
		}
	}
	/**
	 * 获取用户基本信息
	 * @param  $openid
	 */
	public function userinfoGet($openid) {
		$token = $this->get_access_token ();
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$token}&openid={$openid}&lang=zh_CN";
		$result= $this->http_get_result ( $url );
		if($result=='refresh_access_token'){
		    $token = $this->get_access_token ();
		    $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$token}&openid={$openid}&lang=zh_CN";
		    return  $this->http_get_result ( $url );
		}else{
		    return $result;
		}

	}
	/**
	 * 获取目送ticket
	 * @date: 2015-6-10
	 * @author: 佚名
	 * @return unknown
	 */
	public function getCardTicket(){
		if (! empty ( $this->account ['cardapi_ticket'] ) && ! empty ( $this->account ['cardapi_ticket_expire'] ) &&strtotime($this->account ['cardapi_ticket_expire']) > time ()) {
			return $this->account ['cardapi_ticket'];
		} else {
			if (empty ( $this->account ['AppId'] ) || empty ( $this->account ['AppSecret'] )) {
				exit ( '请填写公众号的appid及appsecret！' );
			}
			$accessToken=$this->get_access_token(FALSE);
			// 如果是企业号用以下 URL 获取 ticket
			// $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=wx_card&access_token=$accessToken";
			$res =$this->http_get_result ( $url );
			/*$res=json_decode('{
								 "errcode":0,
								 "errmsg":"ok",
								 "ticket":"bxLdikRXVbTPdHSM05e5u5sUoXNKd8-41ZO3MhKoyN5OfkWITDGgnr2fwJ0m9E8NYzWKVZvdVtaUgWvsdshFKA",
								 "expires_in":7200
								}',true);*/
			if (! $res){
				//exit ( '获取cardApiTicket失败，请查看错误！' );
			}
			
			$ticket = $res['ticket'];
			$record = array ();
			$record ['cardapi_ticket'] =  $ticket;
			$record ['cardapi_ticket_expire'] = date('Y-m-d H:i:s',time () + $res ['expires_in']);
			Yii::app ()->db->createCommand()->update('sys_user_gh',$record,'appid=:appid and appsecret=:appsecret',array(':appid'=>$this->account['AppId'],':appsecret'=>$this->account['AppSecret']));
			return $ticket;
		}

	}
	/**
	 * 获取jssdk jsapi_ticket
	 * @date: 2015-1-22
	 * @author: 佚名
	 * @return unknown
	 */
	private function getJsApiTicket() {

		if (! empty ( $this->account ['jsapi_ticket'] ) && ! empty ( $this->account ['jsapi_ticket_expire'] ) &&strtotime($this->account ['jsapi_ticket_expire']) > time ()) {
			return $this->account ['jsapi_ticket'];
		} else {
			if (empty ( $this->account ['AppId'] ) || empty ( $this->account ['AppSecret'] )) {
				exit ( '请填写公众号的appid及appsecret！' );
			}
			$accessToken=$this->get_access_token(FALSE);
			// 如果是企业号用以下 URL 获取 ticket
			// $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
			$res =$this->http_get_result ( $url );
			/*{
				"errcode":0,
				"errmsg":"ok",
				"ticket":"bxLdikRXVbTPdHSM05e5u5sUoXNKd8-41ZO3MhKoyN5OfkWITDGgnr2fwJ0m9E8NYzWKVZvdVtaUgWvsdshFKA",
				"expires_in":7200
			}*/
			if (! $res)
				exit ( '获取JsApiTicket失败，请查看错误！' );
			$ticket = $res['ticket'];
			$record = array ();
			$record ['jsapi_ticket'] =  $ticket;
			$record ['jsapi_ticket_expire'] = date('Y-m-d H:i:s',time () + $res ['expires_in']);
			Yii::app ()->db->createCommand()->update('sys_user_gh',$record,'appid=:appid and appsecret=:appsecret',array(':appid'=>$this->account['AppId'],':appsecret'=>$this->account['AppSecret']));
			return $ticket;
		}



	}
	public function getSignPackage($url,$nonceStr) {
		$jsapiTicket = $this->getJsApiTicket();
		$timestamp = time();
		// 这里参数的顺序要按照 key 值 ASCII 码升序排序
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
		$signature = sha1($string);
		$signPackage = array(
			"appId"     => $this->account['AppId'],
			"nonceStr"  => $nonceStr,
			"timestamp" => $timestamp,
			"url"       => $url,
			"signature" => $signature,
			"rawString" => $string
		);
		return $signPackage;
	}
	/**
	 * 获取关注者列表
	 * 每次最多拉取1000个关注都，多于1000的关注者请传递next_openid进行拉取
	 * @param  $next_openid
	 */
	public function userlistGet($next_openid = '') {
		$token = $this->get_access_token ();
		$url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=$token&next_openid=$next_openid";
		$result= $this->http_get_result ( $url );
		if($result=='refresh_access_token'){
		    $token = $this->get_access_token ();
		    $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=$token&next_openid=$next_openid";
		    return  $this->http_get_result ( $url );
		}else{
		    return $result;
		}

	}
	function http_get_result($url) {
		yii::import ( 'ext.Http' );
		$ihttp = new Http ();
		$content = $ihttp->http_get ( $url );
		return $this->result ( $content );
	}
	function http_post_result($url, $dat) {
		yii::import ( 'ext.Http' );
		$ihttp = new Http ();
		$content = $ihttp->http_post ( $url, $dat );
		return $this->result ( $content );
	}
	/**
	 * 将unicode中文字符转为中文
	 * @param  $str
	 */
	function decodeUnicode($str) {
		return preg_replace_callback ( '/\\\\u([0-9a-f]{4})/i', create_function ( '$matches', 'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");' ), $str );
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
			    if ($result ['errcode'] == '40001') {
			        //access_token验证失败，重新验证，会一直复制去拿，所以请确保接口访问的准确性
			        Yii::app()->cache[$this->account['AppId']]=null;
			        $this->account ['access_token']='';
			        $this->account ['expire']='';
			        return 'refresh_access_token';


			    }
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
		if ($errors [$code]) {
			return $errors [$code];
		} else {
			return '未知错误';
		}
	}




/**
 * 获取用户增减数据
 * @date: 2015-4-7
 * @author: 佚名
 * @param unknown $data
 * @return Ambigous <string, boolean, mixed>
 */
	public function getusersummary($data=array()) {

		$dat = decodeUnicode(json_encode ( $data ));
		$token = $this->get_access_token ();
		$url = "https://api.weixin.qq.com/datacube/getusersummary?access_token={$token}";
		$result= $this->http_post_result ( $url, $dat );
		if($result=='refresh_access_token'){
			$dat = decodeUnicode(json_encode ( $data ));
			$token = $this->get_access_token ();
			$url = "https://api.weixin.qq.com/datacube/getusersummary?access_token={$token}";
			return   $this->http_post_result ( $url, $dat );
		}else{
			return $result;
		}

	}
	/**
	 * 获取累计用户数据
	 * @date: 2015-4-7
	 * @author: 佚名
	 * @param unknown $data
	 * @return unknown
	 */
	public function getusercumulate($data=array()) {

		$dat = decodeUnicode(json_encode ( $data ));
		$token = $this->get_access_token ();
		$url = "https://api.weixin.qq.com/datacube/getusercumulate?access_token={$token}";
		$result= $this->http_post_result ( $url, $dat );
		if($result=='refresh_access_token'){
			$dat = decodeUnicode(json_encode ( $data ));
			$token = $this->get_access_token ();
			$url = "https://api.weixin.qq.com/datacube/getusercumulate?access_token={$token}";
			return   $this->http_post_result ( $url, $dat );
		}else{
			return $result;
		}

	}


}