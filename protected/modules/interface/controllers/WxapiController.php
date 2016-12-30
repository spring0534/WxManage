<?php
/**
 * WxapiController.php 微信消息请求处理
 * ----------------------------------------------
 * 版权所有 2014-2015 
 * ----------------------------------------------
 * @date: 2014-9-2
 * 
 */
class WxapiController extends Controller {
	public function init() {
		yii::import ( 'ext.WeixinTransmit' );
		//$GLOBALS ["HTTP_RAW_POST_DATA"]= '<xml><ToUserName><![CDATA[gh_27764c76d3f6]]></ToUserName> <FromUserName><![CDATA[ofwoWt8NnTg1KSy2VebwaV0RNjm0]]></FromUserName> <CreateTime>1428662577</CreateTime> <MsgType><![CDATA[text]]></MsgType> <Content><![CDATA[不是吧]]></Content> <MsgId>6136059045440169510</MsgId> </xml>';

	}
	function _encryptMsg($xml,$ghid){
		if($_GET['encrypt_type']=='aes'){
			$row = Yii::app ()->db->createCommand ()->select ( 'encodingAESKey,api_token,appid' )->from ( 'sys_user_gh' )->where ( 'ghid=:ghid and status=1', array (':ghid' => $ghid ) )->queryRow ();
			if ($row) {
					yii::import ( 'ext.wxBizMsgCrypt',true );
					$wxb=new WXBizMsgCrypt($row['api_token'],$row['encodingAESKey'],$row['appid']);
					$timestamp = $_GET ["timestamp"];
					$nonce = $_GET ["nonce"];
					$msgSignature=$_GET ["msg_signature"];
					$encryptMsg = '';
					$errCode=$wxb->encryptMsg($xml, $timestamp, $nonce, $encryptMsg);
					if ($errCode!=0) {
						$this->errlog(array('微信消息接口加密失败'.$errCode,'error','php',time()));

					}else{
						return $encryptMsg;
					}
			}else{
				exit;//退出
			}
		}else{
			return $xml;
		}

	}
	function actionIndex() {
		if (! isset ( $_GET ['echostr'] )) {
			$this->responseMsg ();
		} else {
			$this->valid ();
		}
	}
	/**
	 * 验证签名
	 * @date: 2014-9-17
	 * @author: 佚名
	 */
	public function valid() {
		$_token = Yii::app ()->request->getParam ( '_token' );
		if (! empty ( $_token )) {
			$row = Yii::app ()->db->createCommand ()->select ( 'api_url,api_token,ghid' )->from ( 'sys_user_gh' )->where ( 'api_url=:api_url and status=1', array (':api_url' => $_token ) )->queryRow ();
			if ($row) {
				$echoStr = $_GET ["echostr"];
				$signature = $_GET ["signature"];
				$timestamp = $_GET ["timestamp"];
				$nonce = $_GET ["nonce"];
				$token = $row ['api_token'];
				$tmpArr = array ($token, $timestamp, $nonce );
				sort ( $tmpArr );
				$tmpStr = implode ( $tmpArr );
				$tmpStr = sha1 ( $tmpStr );
				if ($tmpStr == $signature) {
					echo $echoStr;
					exit ();
				}
			}
		}
	}
	function errlog($log){
		Yii::app()->db->createCommand()->insert('sys_log', array(
			'level'=>$log[1],
			'category'=>$log[2],
			'logtime'=>(int) $log[3],
			'message'=>$log[0],
			'ghid'=>gh()?gh()->ghid:'',
			'uid'=>user()->id,
			'request_url'=>Yii::app()->request->getRequestUri(),
			'ip'=>Yii::app()->request->userHostAddress,
		));

	}
	/**
	 * 响应消息
	 * @date: 2014-9-17
	 * @author: 佚名
	 */
	public function responseMsg() {
		$postStr = $GLOBALS ["HTTP_RAW_POST_DATA"];
		if (! empty ( $postStr )) {
			$postObj = simplexml_load_string ( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );

			$ghid = $postObj->ToUserName;
			$this->errlog(array('访问地址：'. http_build_query ( $_GET ),'error','php',time()));
			$row = Yii::app ()->db->createCommand ()->select ( 'encodingAESKey,api_token,appid,transpond,zf_api_url' )->from ( 'sys_user_gh' )->where ( 'ghid=:ghid and status=1', array (':ghid' => $ghid ) )->queryRow ();

			if ($row) {
				if($row['transpond']==1){
					yii::import ( 'ext.Http' );
					$ihttp = new Http ();
					$lingw='?';
					if(strpos($row ['zf_api_url'], '?')!==false){
						$lingw='&';
					}
					$content = $ihttp->http_request (  $row ['zf_api_url'] . $lingw . http_build_query ( $_GET ), $GLOBALS ["HTTP_RAW_POST_DATA"], array ('CURLOPT_HTTPHEADER' => array ('Content-Type: text/xml; charset=utf-8' ) ) );
					//$this->errlog(array('访问地址：'.$row ['zf_api_url'] . $lingw . http_build_query ( $_GET ).'返回内容：'.$content ['content'],'error','php',time()));
					echo $content ['content'];
					exit;
				}
				if($_GET['encrypt_type']=='aes'){
					yii::import ( 'ext.wxBizMsgCrypt',true );
					$wxb=new WXBizMsgCrypt($row['api_token'],$row['encodingAESKey'],$row['appid']);
					$echoStr = $_GET ["echostr"];
					$signature = $_GET ["signature"];
					$timestamp = $_GET ["timestamp"];
					$nonce = $_GET ["nonce"];
					$msgSignature=$_GET ["msg_signature"];
					$pp='';
					$errCode=$wxb->decryptMsg($msgSignature,$timestamp, $nonce, $postStr, $pp);
					if ($errCode!=0) {
						$this->errlog(array('微信消息接口解密失败'.$errCode,'error','php',time()));

					}
					$postObj=simplexml_load_string ( $pp, 'SimpleXMLElement', LIBXML_NOCDATA );
				}

			}else{
				$this->errlog(array('不存在 的公众号接入','log','php',time()));
				exit;//退出
			}

			$RX_TYPE = trim ( $postObj->MsgType );

			$this->log($postObj,$RX_TYPE,'');//日志记录
			//消息统计---------------
			$t=date('Y-m-d');
			$m=StatWx::model()->findByAttributes(array(
				'ghid'=>$ghid,
				'day'=>$t
			));
			if (empty($m)){
				$model=new StatWx();
				$model->day=$t;
				$model->ghid=$ghid;
				$model->ctm=date('Y-m-d H:i:s');
				$model->sub=0;
				$model->unsub=0;
				$model->receive_num=0;
				$model->send_num=0;
				$model->msg_num=0;
				$model->save();
			}else{
				$m->utm=date('Y-m-d H:i:s');
				$m->receive_num=$m->receive_num+1;
				$m->msg_num=$m->msg_num+1;
				$m->save();
			}
			//---------end-------------
			//消息类型分离
			switch ($RX_TYPE) {
				case "event" : //事件
					$result = $this->receiveEvent ( $postObj );
					break;
				case "text" : //文本
					$result = $this->receiveText ( $postObj );
					break;
				case "image" : //图片
					$result = $this->receiveImage ( $postObj );
					break;
				case "location" : //位置
					$result = $this->receiveLocation ( $postObj );
					break;
				case "voice" : //语音
					$result = $this->receiveVoice ( $postObj );
					break;
				case "video" : //视频
					$result = $this->receiveVideo ( $postObj );
					break;
				case "link" : //链接
					$result = $this->receiveLink ( $postObj );
					break;
				default :
					$result = "unknown msg type: " . $RX_TYPE;
					break;
			}
			echo $result;

		}
	}
	private function log($object,$type,$content){
		//1.文本 2.图文 3.地址位置 4.音乐
		$model=new WxEventLog();
		$model->wx_id=$object->FromUserName;
		$model->wx_ghid=$object->ToUserName;
		$model->keyword=$object->Content;
		$model->category=$type;
		$model->item=$GLOBALS ["HTTP_RAW_POST_DATA"];
		$model->content=$content;
		$model->tm=date('Y-m-d H:i:s',time());
		$model->save();
	}
	/**
	 * 接收事件消息
	 * @date: 2014-9-17
	 * @author: 佚名
	 * @param  $object
	 */
	private function receiveEvent($object) {
		$ghid = $object->ToUserName;
		$srcopenid = $object->FromUserName;
		switch ($object->Event) {
			case "subscribe" : //关注事件

				//获取用户的微信号信息，并更新记录到表
				$row = Yii::app ()->db->createCommand ()->select ( 'type,appid,appsecret,access_token,at_expires' )->from ( 'sys_user_gh' )->where ( 'ghid=:ghid', array (':ghid' => $ghid ) )->queryRow ();
				if ($row && $row ['type'] == 3) { //拥有高级接口
					yii::import ( 'ext.Weixinapi' );
					$account = array ('AppId' => $row ['appid'], 'AppSecret' => $row ['appsecret'],'access_token'=>$row ['access_token'],'expire'=>$row ['at_expires'] );
					$t = new Weixinapi ( $account );
					$userinfo = $t->userinfoGet ( $srcopenid );
					if ($userinfo &&is_array($userinfo)&& !empty($userinfo ['nickname'])) {
						$data=array(
							'srcOpenid'=>$srcopenid,
							'headimgurl'=> $userinfo ['headimgurl'],
							'nickname'=> htmlentities($userinfo ['nickname']),
							'city'=> $userinfo ['city'],
							'sex'=> $userinfo ['sex'],
							'province'=> $userinfo ['province'],
							
						);
						yii::import ( 'ext.Emoji' );
						$Emoji=new Emoji();
						$data ['nickname']=$Emoji->fileterEmoji($data ['nickname']);
						$rule = '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9_]+$/u';//匹配中文字符，数字，字母的正则表达式
						$re ['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";//utf8编码字段串
						$userinfo_vo = Yii::app ()->db->createCommand ()->select ( '*' )->from ( 'sys_wxuser' )->where ( 'openid=:openid and ghid=:ghid', array (':openid' => $srcopenid, 'ghid' => $ghid ) )->queryRow ();
						if ($userinfo_vo) {
								$data ['utm']=date ( 'Y-m-d H:i:s' );
								try{
									$re=Yii::app ()->db->createCommand()->update('sys_wxuser',$data,'openid=:openid and ghid=:ghid',array(':openid'=>$userinfo['openid'],':ghid'=>$ghid));
								}catch(Exception $e){
									$str2='';
									preg_match_all ( $re ['utf-8'], $data ['nickname'], $match );
									foreach ($match[0] as $k=>$v){
										if(preg_match ( $rule, $v )===1){
											$str2.= $v;
										}
									}
									$data ['nickname']=$str2;
									Yii::app ()->db->createCommand()->update('sys_wxuser',$data,'openid=:openid and ghid=:ghid',array(':openid'=>$userinfo['openid'],':ghid'=>$ghid));
								}	
							
							
						
						} else {
							$data['openid'] =$userinfo ['openid'];
							$data['ghid'] =$ghid;
							$data['ctm']= date ( 'Y-m-d H:i:s' );
							try{
								Yii::app ()->db->createCommand()->insert('sys_wxuser',$data);
							}catch(Exception $e){
								$str2='';
								preg_match_all ( $re ['utf-8'], $data ['nickname'], $match );
								foreach ($match[0] as $k=>$v){
									if(preg_match ( $rule, $v )===1){
										$str2.= $v;
									}
								}
								$data ['nickname']=$str2;
								Yii::app ()->db->createCommand()->insert('sys_wxuser',$data);
							}
						}
					}
				}
				//关注统计
				$t=date('Y-m-d');
				$m=StatWx::model()->findByAttributes(array(
					'ghid'=>$ghid,
					'day'=>$t
				));
				$m->utm=date('Y-m-d H:i:s');
				$m->sub=$m->sub+1;
				$m->save();

				//查询事件表中有效的事件 记录

				if (empty ( $object->EventKey )) {
					//普通关注
					//$data = Yii::app ()->db->createCommand ()->select ( '*' )->from ( 'wx_router_event' )->where ( 'ghid=:ghid and event=:event and status=1', array (':ghid' => $ghid, ':event' => "subscribe" ) )->queryRow ();
					$ghid = $object->ToUserName;
					$srcopenid = $object->FromUserName;
					$keyword = trim ( $object->Content );
					$sql = "select * from wx_router_keyword where ghid='" . $ghid . "' and status=1 and msg_type='subscribe' ";
					$command = Yii::app ()->db->createCommand ( $sql );
					$row = $command->queryRow ();
					if ($row) {
						return $this->materialParse ( $object, $row );
					}

				} else {
					//来源二维码的关注
					//$data = Yii::app ()->db->createCommand ()->select ( '*' )->from ( 'wx_spread_qrcode' )->where ( 'ghid=:ghid  and scene_id=:scene_id', array (':ghid' => $ghid,  ':scene_id' => str_replace('qrscene_','',$object->EventKey) ) )->queryRow ();

					$model=WxSpreadQrcode::model()->findByAttributes(array('ghid'=>$ghid,'scene_id'=>str_replace('qrscene_', '', $object->EventKey),'ticket'=>$object->Ticket));
					$model->qcount=$model->qcount+1;
					$wxql=WxSpreadQrcodeLog::model()->findByAttributes(array('ghid'=>$ghid,'qid'=>$model->id,'openid'=>$object->FromUserName,'scene_id'=>str_replace('qrscene_', '', $object->EventKey)));
					if(empty($wxql)){
						$model->ucount=$model->ucount+1;
					}
					if($model->ucount==0)$model->ucount=1;
					if(!$model->save()){
						$this->errlog(array(getErrStr($model->errors),'error','php',time()));
					}
					$mm=new WxSpreadQrcodeLog();
					$mm->ghid=$ghid;
					$mm->qid=$model->id;
					$mm->openid=$object->FromUserName;
					$mm->scene_id=str_replace('qrscene_', '', $object->EventKey);
					$mm->ctm=time();

					if(!$mm->save()){
						$this->errlog(array(getErrStr($mm->errors),'error','php',time()));
					}
					$data=$model->attributes;
				}
				if ($data&&!empty($data['reply_id'])) {
					$data ['reply_type']=1;
					return $this->materialParse ( $object, $data );
				}

				break;
			case "unsubscribe" : //取消事件,将关注字段变为未关注
				//取消关注统计
				$t=date('Y-m-d');
				$m=StatWx::model()->findByAttributes(array(
					'ghid'=>$ghid,
					'day'=>$t
				));
				$m->utm=date('Y-m-d H:i:s');
				$m->unsub=$m->unsub+1;
				$m->save();
				$row = Yii::app ()->db->createCommand ()->update ( 'sys_wxuser', array ('subscribe' => 2 ), 'srcOpenid=:srcOpenid and ghid=:ghid', array (':srcOpenid' => $srcopenid, ':ghid' => $ghid ) );

				break;
			case "SCAN" : //扫描带参数二维码事件


				//$data = Yii::app ()->db->createCommand ()->select ( '*' )->from ( 'wx_spread_qrcode' )->where ( 'ghid=:ghid  and scene_id=:scene_id', array (':ghid' => $ghid,  ':scene_id' => $object->EventKey ) )->queryRow ();
				$model=WxSpreadQrcode::model()->findByAttributes(array('ghid'=>$ghid,'scene_id'=>$object->EventKey,'ticket'=>$object->Ticket));
				$model->qcount=$model->qcount+1;
				$wxql=WxSpreadQrcodeLog::model()->findByAttributes(array('ghid'=>$ghid,'qid'=>$model->id,'openid'=>$object->FromUserName,'scene_id'=>$object->EventKey));
				if(empty($wxql)){
						$model->ucount=$model->ucount+1;
				}
				if($model->ucount==0)$model->ucount=1;
				if(!$model->save()){
					$this->errlog(array(getErrStr($model->errors),'error','php',time()));
				}
				$mm=new WxSpreadQrcodeLog();
				$mm->ghid=$ghid;
				$mm->qid=$model->id;
				$mm->openid=$object->FromUserName;
				$mm->scene_id=intval($object->EventKey);
				$mm->ctm=time();

				if(!$mm->save()){
					$this->errlog(array(getErrStr($mm->errors),'error','php',time()));
				}
				$data=$model->attributes;
				if ($data&&!empty($data['reply_id'])) {
					$data ['reply_type']=1;
					return $this->materialParse ( $object, $data );
				}

				break;
			case "CLICK" : //点击菜单
				$data = Yii::app ()->db->createCommand ()->select ( '*' )->from ( 'wx_router_menu' )->where ( 'ghid=:ghid and menu_type=:menu_type  and event_key=:event_key and status=1', array (':ghid' => $ghid, ':event_key' => $object->EventKey, ':menu_type' => 'click' ) )->queryRow ();
				//Yii::app()->db->;
				//dump(array (':ghid' => $ghid, ':event_key' => $object->EventKey, ':menu_type' => 'click' ));
				//dump($data);
				if ($data) {
					return $this->materialParse ( $object, $data );
				}
				break;
			case "LOCATION" :
				//$content = "上传位置:纬度 " . $object->Latitude . ";经度 " . $object->Longitude;
				break;
			case "VIEW" : //点击菜单（菜单为URL链接）
				$data = Yii::app ()->db->createCommand ()->select ( '*' )->from ( 'wx_router_menu' )->where ( 'ghid=:ghid and menu_type=:menu_type  and event_key=:event_key and status=1', array (':ghid' => $ghid, ':event_key' => $object->EventKey, ':menu_type' => 'view' ) )->queryRow ();
				if ($data) {

					return $this->materialParse ( $object, $data );
				}
				break;
			case "MASSSENDJOBFINISH" :
				//$content = "消息ID:" . $object->MsgID . "，结果:" . $object->Status . "，粉丝数:" . $object->TotalCount . "，过滤:" . $object->FilterCount . "，发送成功:" . $object->SentCount . "，发送失败:" . $object->ErrorCount;
				break;
			default :
				//$content = "receive a new event: " . $object->Event;
				break;
		}

	}
	/**
	 * 接收文本消息
	 * @date: 2014-9-17
	 * @author: 佚名
	 * @param  $object
	 */
	private function receiveText($object) {
		$ghid = $object->ToUserName;
		$srcopenid = $object->FromUserName;
		$keyword = trim ( $object->Content );
		$sql = "select * from wx_router_keyword where ghid='" . $ghid . "' and status=1 and msg_type='text' and ((keyword='" . $keyword . "' and match_mode=1) or (instr('" . $keyword . "',keyword)  and match_mode=2))";
		$command = Yii::app ()->db->createCommand ( $sql );
		$row = $command->queryRow ();
		if (! $row) {
			//如果配置不到则查询3模式
			$sql = "select * from wx_router_keyword where ghid='" . $ghid . "' and status=1 and msg_type='other' and  match_mode=3";
			$command = Yii::app ()->db->createCommand ( $sql );
			$row = $command->queryRow ();
		}
		if ($row) {
			return $this->materialParse ( $object, $row );
		}

	}
	/**
	 * 接收图片消息
	 * @date: 2014-9-17
	 * @author: 佚名
	 * @param  $object
	 */
	private function receiveImage($object) {
		$ghid = $object->ToUserName;
		$sql = "select * from wx_router_keyword where ghid='" . $ghid . "' and status=1 and msg_type='image'";
		$command = Yii::app ()->db->createCommand ( $sql );
		$row = $command->queryRow ();
		if ($row) {
			return $this->materialParse ( $object, $row );
		}
	}
	/**
	 * 接收地理位置消息
	 * @date: 2014-9-17
	 * @author: 佚名
	 * @param  $object
	 */
	private function receiveLocation($object) {
		$ghid = $object->ToUserName;
		$sql = "select * from wx_router_keyword where ghid='" . $ghid . "' and status=1 and msg_type='location'";
		$command = Yii::app ()->db->createCommand ( $sql );
		$row = $command->queryRow ();
		if ($row) {
			return $this->materialParse ( $object, $row );
		}
	}
	/**
	 * 接收语音消息
	 * @date: 2014-9-17
	 * @author: 佚名
	 * @param  $object
	 */
	private function receiveVoice($object) {
		//如果存在语音解析，则当作text处理
		if (isset ( $object->Recognition ) && ! empty ( $object->Recognition )) {
			$ghid = $object->ToUserName;
			$keyword = trim ( $object->Recognition );
			$sql = "select * from wx_router_keyword where ghid='" . $ghid . "' and status=1 and msg_type='text' and ((keyword='" . $keyword . "' and match_mode=1) or (instr('" . $keyword . "',keyword)  and match_mode=2))";
			$command = Yii::app ()->db->createCommand ( $sql );
			$row = $command->queryRow ();
			if (! $row) {
				//如果配置不到则查询3模式
				$sql = "select * from wx_router_keyword where ghid='" . $ghid . "' and status=1 and msg_type='order' and  match_mode=3";
				$command = Yii::app ()->db->createCommand ( $sql );
				$row = $command->queryRow ();
			}
			if ($row) {
				return $this->materialParse ( $object, $row );
			}
		} else {
			$ghid = $object->ToUserName;
			$sql = "select * from wx_router_keyword where ghid='" . $ghid . "' and status=1 and msg_type='voice'";
			$command = Yii::app ()->db->createCommand ( $sql );
			$row = $command->queryRow ();
			if ($row) {
				return $this->materialParse ( $object, $row );
			}
		}

	}
	/**
	 * 接收视频消息
	 * @date: 2014-9-17
	 * @author: 佚名
	 * @param  $object
	 */
	private function receiveVideo($object) {
		$ghid = $object->ToUserName;
		$sql = "select * from wx_router_keyword where ghid='" . $ghid . "' and status=1 and msg_type='video'";
		$command = Yii::app ()->db->createCommand ( $sql );
		$row = $command->queryRow ();
		if ($row) {
			return $this->materialParse ( $object, $row );
		}
	}
	/**
	 * 接收URL链接消息
	 * @date: 2014-9-17
	 * @author: 佚名
	 * @param  $object
	 */
	private function receiveLink($object) {
		$ghid = $object->ToUserName;
		$sql = "select * from wx_router_keyword where ghid='" . $ghid . "' and status=1 and msg_type='link'";
		$command = Yii::app ()->db->createCommand ( $sql );
		$row = $command->queryRow ();
		if ($row) {
			return $this->materialParse ( $object, $row );
		}
	}
	function stat_update($object){
		//上行统计，假设都发送成功
		$t=date('Y-m-d');
		$m=StatWx::model()->findByAttributes(array(
			'ghid'=>$object->ToUserName,
			'day'=>$t
		));
		$m->utm=date('Y-m-d H:i:s');
		$m->send_num=$m->send_num+1;
		$m->save();

	}
	/**
	 * 素材处理
	 * @date: 2014-9-16
	 * @author: 佚名
	 * @param  $object
	 * @param  $data
	 */
	private function materialParse($object, $data, $keyword = '') {
		if (! empty ( $data ['reply_id'] )) {

			switch ($data ['reply_type']) {
				case 1 : //素材
					$material = Yii::app ()->db->createCommand ()->select ( '*' )->from ( 'wx_material' )->where ( 'id=:id and status=1', array (':id' => $data ['reply_id'] ) )->queryRow ();
					if ($material) {
						switch ($material ['msg_type']) {
							//素材类型。{{select;news_n-多图文,news_1-单图文,text-文本,image-图片}}
							case 'text' :
								$content = $material ['content'];
								$ghid = $object->ToUserName;
								$srcopenid = $object->FromUserName;
								$userinfo_vo = Yii::app ()->db->createCommand ()->select ( 'openid,nickname,province,city' )->from ( 'sys_wxuser' )->where ( 'openid=:openid and ghid=:ghid', array (':openid' => $srcopenid, 'ghid' => $ghid ) )->queryRow ();
								if(!empty($userinfo_vo)){
									$content=preg_replace(array('/\[openid\]/','/\[nickname\]/','/\[province\]/','/\[city\]/'), array_values($userinfo_vo), $content);
								}
								break;
							case 'news_1' :
								$content = json_decode ( $material ['content'], true );
								break;
							case 'news_n' :
								$content = json_decode ( $material ['content'], true );
								break;
							case 'image' :
								$content = json_decode ( $material ['content'], true );
								break;
						}
						if (is_array ( $content )) {
							foreach ($content as $k=>$v){
								//跳转到内容
								if($v['onclick']==3){
									$content[$k]['url']=Yii::app()->params['homeUrl'].'/mpPage/index/mid/'.$material['id'].'/id/'.$v['did'];
								}
							}
							if (isset ( $content [0] ['pic'] )) { //图文
								$this->stat_update($object);
								return $this->_encryptMsg(WeixinTransmit::transmitNews ( $object, $content ),$object->ToUserName);
							} else if (isset ( $content ['MusicUrl'] )) { //音乐
								$this->stat_update($object);
								return $this->_encryptMsg(WeixinTransmit::transmitMusic ( $object, $content ),$object->ToUserName);
							}
						} else {
							$this->stat_update($object);

							return $this->_encryptMsg(WeixinTransmit::transmitText ( $object, $content ),$object->ToUserName);;

						}
					}

					break;
				case 2 : //第三方接口

					$cache = Yii::app ()->cache->get ( 'wx_forward_' . $data ['reply_id'] );
					if ($cache) {
						$this->stat_update($object);
						return $cache;
					} else {
						$forward = Yii::app ()->db->createCommand ()->select ( '*' )->from ( 'wx_forward' )->where ( 'id=:id and status=1', array (':id' => $data ['reply_id'] ) )->queryRow ();
						if ($forward) {
							/*if(!empty($forward['keyword'])){

							}*/
							yii::import ( 'ext.Http' );
							$ihttp = new Http ();
							//$parm=empty($_GET)?'':'?'.http_build_query ( $_GET );
							//echo($GLOBALS ["HTTP_RAW_POST_DATA"].'&'.http_build_query ( $_GET ));exit;
							$content = $ihttp->http_request ( $forward ['url'] ,$GLOBALS ["HTTP_RAW_POST_DATA"], array ('CURLOPT_HTTPHEADER' => array ('Content-Type: text/xml; charset=utf-8' ) ) );
							if (! empty ( $forward ['cache_minutes'] ) && empty ( $content ['content'] )) {
								Yii::app ()->cache->set ( 'wx_forward_' . $data ['reply_id'], $content ['content'], $forward ['cache_minutes'] * 60 );
							}
							$this->stat_update($object);
							//$this->errlog(array($content ['content'],'error','php',time()));
							//$this->errlog(array($this->_encryptMsg($content ['content'],$object->ToUserName),'error','php',time()));
							//目前返回的数据不能加密
							return  $this->_encryptMsg($content ['content'],$object->ToUserName);
						}
					}

					break;
			}
		}
	}



}
