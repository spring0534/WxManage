<?php
/**
* WeixinTransmit.php 微信回复消息格式转换类
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2014-9-16
* 
*/
class WeixinTransmit {
	/**
	 * 回复文本消息
	 * @date: 2014-9-16
	 * @author: 佚名
	 * @param 微信消息对象 $object
	 * @param string $content
	 */
	public static function transmitText($object, $content) {
		$xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";
		$result = sprintf ( $xmlTpl, $object->FromUserName, $object->ToUserName, time (), $content );
		return $result;
	}
	
	/**
	 * 回复图片消息
	 * @date: 2014-9-16
	 * @author: 佚名
	 * @param 微信消息对象 $object
	 * @param array $imageArray
	 */
	public static function transmitImage($object, $imageArray) {
		$itemTpl = "<Image>
    <MediaId><![CDATA[%s]]></MediaId>
</Image>";
		$item_str = sprintf ( $itemTpl, $imageArray ['MediaId'] );
		$xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[image]]></MsgType>
$item_str
</xml>";
		$result = sprintf ( $xmlTpl, $object->FromUserName, $object->ToUserName, time () );
		return $result;
	}
	
	/**
	 * 回复语音消息
	 * @date: 2014-9-16
	 * @author: 佚名
	 * @param 微信消息对象 $object
	 * @param array $voiceArray
	 */
	public static function transmitVoice($object, $voiceArray) {
		$itemTpl = "<Voice>
    <MediaId><![CDATA[%s]]></MediaId>
</Voice>";
		$item_str = sprintf ( $itemTpl, $voiceArray ['MediaId'] );
		$xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[voice]]></MsgType>
$item_str
</xml>";
		
		$result = sprintf ( $xmlTpl, $object->FromUserName, $object->ToUserName, time () );
		return $result;
	}
	
	/**
	 * 回复视频消息
	 * @date: 2014-9-16
	 * @author: 佚名
	 * @param 微信消息对象 $object
	 * @param array $videoArray
	 */
	public static function transmitVideo($object, $videoArray) {
		$itemTpl = "<Video>
    <MediaId><![CDATA[%s]]></MediaId>
    <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
</Video>";
		$item_str = sprintf ( $itemTpl, $videoArray ['MediaId'], $videoArray ['ThumbMediaId'], $videoArray ['title'], $videoArray ['note'] );
		$xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[video]]></MsgType>
$item_str
</xml>";
		$result = sprintf ( $xmlTpl, $object->FromUserName, $object->ToUserName, time () );
		return $result;
	}

	/**
	 * 回复图文消息
	 * @date: 2014-9-16
	 * @author: 佚名
	 * @param 微信消息对象 $object
	 * @param array $newsArray
	 */
	public static function transmitNews($object, $newsArray) {
		if (! is_array ( $newsArray )) {
			return;
		}
		$itemTpl = "    <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
    </item>
";
		$item_str = "";
		foreach ( $newsArray as $item ) {
			$item_str .= sprintf ( $itemTpl, $item ['title'], $item ['note'], $item ['pic'], $item ['url'] );
		}
		$xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>%s</ArticleCount>
<Articles>
$item_str</Articles>
</xml>";
		$result = sprintf ( $xmlTpl, $object->FromUserName, $object->ToUserName, time (), count ( $newsArray ) );
		return $result;
	}
	
	/**
	 * 回复音乐消息
	 * @date: 2014-9-16
	 * @author: 佚名
	 * @param 微信消息对象 $object
	 * @param array $musicArray
	 */
	public static function transmitMusic($object, $musicArray) {
		$itemTpl = "<Music>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
    <MusicUrl><![CDATA[%s]]></MusicUrl>
    <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
</Music>";
		$item_str = sprintf ( $itemTpl, $musicArray ['title'], $musicArray ['note'], $musicArray ['MusicUrl'], $musicArray ['HQMusicUrl'] );
		$xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[music]]></MsgType>
$item_str
</xml>";
		$result = sprintf ( $xmlTpl, $object->FromUserName, $object->ToUserName, time () );
		return $result;
	}
	
	/**
	 * 回复多客服消息
	 * @date: 2014-9-16
	 * @author: 佚名
	 * @param 微信消息对象 $object
	 */
	public static function transmitService($object) {
		$xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[transfer_customer_service]]></MsgType>
</xml>";
		$result = sprintf ( $xmlTpl, $object->FromUserName, $object->ToUserName, time () );
		return $result;
	}

}