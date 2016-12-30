<?php

/**
 * 微信支付类：定义微信支付过程中的函数及方法
 * ----------------------------------------------
 * 版权所有 2014-2015 联众互动
 * ----------------------------------------------
 * @date: 2014-11-25
 * @author: mankio <546234549@qq.com>
 * 
 */
class WxPayHelperController extends Controller{
	var $parameters; //配置参数
	/**
	 * 构造函数
	 */
	function __construct(){

	}
	/**
	 * 设置参数
	 * @param string $parameter  参数名
	 * @param string $parameterValue  参数值
	 */
	function setParameter($parameter, $parameterValue) {
		Yii::import("ext.CommonUtil");
		$this->parameters[CommonUtil::trimString($parameter)] = CommonUtil::trimString($parameterValue);
	}
	/**
	 * 获取参数
	 * @param string $parameter 参数名
	 * @return string  返回参数的值
	 */
	function getParameter($parameter) {
		return $this->parameters[$parameter];
	}
	/**
	 * 生成16位随机字符串
	 * @param int $length  生成随机字符串的长度
	 * @return string 返回16位随机字符串
	 */
	protected function create_noncestr( $length = 16 ) {  
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";  
		$str ="";  
		for ( $i = 0; $i < $length; $i++ )  {  
			$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
		}  
		return $str;  
	}
	/**
	 * 检查配置参数
	 * @return boolean true:参数配置正确      false:配置参数不完整
	 */
	function check_cft_parameters(){
		if($this->parameters["bank_type"] == null || $this->parameters["body"] == null || $this->parameters["partner"] == null || 
			$this->parameters["out_trade_no"] == null || $this->parameters["total_fee"] == null || $this->parameters["fee_type"] == null ||
			$this->parameters["notify_url"] == null || $this->parameters["spbill_create_ip"] == null || $this->parameters["input_charset"] == null
			){
			return false;
		}
		return true;
	}
	/**
	 * 生成package订单详情
	 * @throws SDKRuntimeException
	 * @return string
	 */
	protected function get_cft_package(){
		Yii::import("ext.SDKRuntimeException");
		try {
			if (null == $GLOBALS['accountinfo']['partnerkey'] || "" == $GLOBALS['accountinfo']['partnerkey'] ) {
				throw new SDKRuntimeException("密钥不能为空！" . "<br>");
			}
			Yii::import("ext.CommonUtil");
			$commonUtil = new CommonUtil();
			ksort($this->parameters);
			$unSignParaString = $commonUtil->formatQueryParaMap($this->parameters, false);
			$paraString = $commonUtil->formatQueryParaMap($this->parameters, true);
			Yii::import("ext.MD5SignUtil");
			$md5SignUtil = new MD5SignUtil();
			return $paraString . "&sign=" . $md5SignUtil->sign($unSignParaString,$commonUtil->trimString($GLOBALS['accountinfo']['partnerkey']));
		}catch (SDKRuntimeException $e){
			die($e->errorMessage());
		}
	}
	/**
	 * 生成支付签名，对JS API的支付行为进行鉴权
	 * @param array $bizObj
	 * @throws SDKRuntimeException
	 */
	public function get_biz_sign($bizObj){
		 foreach ($bizObj as $k => $v){
			 $bizParameters[strtolower($k)] = $v;
		 }
		 Yii::import("ext.SDKRuntimeException");
		 try {
		 	if($GLOBALS['accountinfo']['appkey'] == ""){
		 			throw new SDKRuntimeException("APPKEY为空！" . "<br>");
		 	}
		 	$bizParameters["appkey"] = $GLOBALS['accountinfo']['appkey'];
		 	ksort($bizParameters);
		 	Yii::import("ext.CommonUtil");
		 	$commonUtil = new CommonUtil();
		 	$bizString = $commonUtil->formatBizQueryParaMap($bizParameters, false);
		 	return sha1($bizString);
		 }catch (SDKRuntimeException $e){
			die($e->errorMessage());
		 }
	}
	/**
	 * 生成app支付请求json
	     {
	 	"appid":"wwwwb4f85f3a797777",
		"traceid":"crestxu",
		"noncestr":"111112222233333",
		"package":"bank_type=WX&body=XXX&fee_type=1&input_charset=GBK&notify_url=http%3a%2f%2f
			www.qq.com&out_trade_no=16642817866003386000&partner=1900000109&spbill_create_ip=127.0.0.1&total_fee=1&sign=BEEF37AD19575D92E191C1E4B1474CA9",
		"timestamp":1381405298,
		"app_signature":"53cca9d47b883bd4a5c85a9300df3da0cb48565c",
		"sign_method":"sha1"
		}
	*/
	function create_app_package($traceid=""){
		Yii::import("ext.SDKRuntimeException");
        try {
		   if($this->check_cft_parameters() == false) {
			   throw new SDKRuntimeException("生成package参数缺失！" . "<br>");
		    }
		    $nativeObj["appid"] = $GLOBALS['accountinfo']['appid'];
		    $nativeObj["package"] = $this->get_cft_package();
		    $nativeObj["timestamp"] = time();
		    $nativeObj["traceid"] = $traceid;
		    $nativeObj["noncestr"] = $this->create_noncestr();
		    $nativeObj["app_signature"] = $this->get_biz_sign($nativeObj);
		    $nativeObj["sign_method"] = $GLOBALS['accountinfo']['signtype'];
		    return   json_encode($nativeObj);
		}catch (SDKRuntimeException $e){
			die($e->errorMessage());
		}		
	}
	/**
	 * 生成js API支付请求json
		{
			"appId" : "wxf8b4f85f3a794e77", //公众号名称，由商户传入
			"timeStamp" : "189026618",      //时间戳这里随意使用了一个值
			"nonceStr" : "adssdasssd13d",   //随机串
			"package" : "bank_type=WX&body=XXX&fee_type=1&input_charset=GBK&notify_url=http%3a%2f
			%2fwww.qq.com&out_trade_no=16642817866003386000&partner=1900000109&spbill_create_i
			p=127.0.0.1&total_fee=1&sign=BEEF37AD19575D92E191C1E4B1474CA9",
			//扩展字段，由商户传入
			"signType" : "SHA1",   //微信签名方式:sha1
			"paySign" : "7717231c335a05165b1874658306fa431fe9a0de" //微信签名
		}
	*/
	function create_biz_package(){
		 Yii::import("ext.SDKRuntimeException");
		 try {
			if($this->check_cft_parameters() == false) {   
			   throw new SDKRuntimeException("生成package参数缺失！" . "<br>");
		    }
		    $nativeObj["appId"] = $GLOBALS['accountinfo']['appid'];
		    $nativeObj["package"] = $this->get_cft_package();
		    $nativeObj["timeStamp"] = (string)time();
		    $nativeObj["nonceStr"] = $this->create_noncestr();
		    $nativeObj["paySign"] = $this->get_biz_sign($nativeObj);
		    $nativeObj["signType"] = $GLOBALS['accountinfo']['signtype'];
		    return   json_encode($nativeObj);
		}catch (SDKRuntimeException $e){
			die($e->errorMessage());
		}		   
	}
	/**
	 * 生成原生支付url
	 * weixin://wxpay/bizpayurl?sign=XXXXX&appid=XXXXXX&productid=XXXXXX&timestamp=XXXXXX&noncestr=XXXXXX
	 */
	function create_native_url($productid){
		Yii::import("ext.CommonUtil");
		$commonUtil = new CommonUtil();
	    $nativeObj["appid"] = $GLOBALS['accountinfo']['appid'];
	    $nativeObj["productid"] = urlencode($productid);
	    $nativeObj["timestamp"] = time();
	    $nativeObj["noncestr"] = $this->create_noncestr();
	    $nativeObj["sign"] = $this->get_biz_sign($nativeObj);
	    $bizString = $commonUtil->formatBizQueryParaMap($nativeObj, false);
	    return "weixin://wxpay/bizpayurl?".$bizString;  
	}
	/**
	 * 生成原生支付请求XML
		<xml>
	    <AppId><![CDATA[wwwwb4f85f3a797777]]></AppId>
	    <Package><![CDATA[a=1&url=http%3A%2F%2Fwww.qq.com]]></Package>
	    <TimeStamp> 1369745073</TimeStamp>
	    <NonceStr><![CDATA[iuytxA0cH6PyTAVISB28]]></NonceStr>
	    <RetCode>0</RetCode>
	    <RetErrMsg><![CDATA[ok]]></ RetErrMsg>
	    <AppSignature><![CDATA[53cca9d47b883bd4a5c85a9300df3da0cb48565c]]>
	    </AppSignature>
	    <SignMethod><![CDATA[sha1]]></ SignMethod >
	    </xml>
	*/
	function create_native_package($retcode = 0, $reterrmsg = "ok"){
		 Yii::import("ext.SDKRuntimeException");
		 try {
		    if($this->check_cft_parameters() == false && $retcode == 0) {   //如果是正常的返回， 检查财付通的参数
			   throw new SDKRuntimeException("生成package参数缺失！" . "<br>");
		    }
		    $nativeObj["AppId"] = $GLOBALS['accountinfo']['appid'];
		    $nativeObj["Package"] = $this->get_cft_package();
		    $nativeObj["TimeStamp"] = time();
		    $nativeObj["NonceStr"] = $this->create_noncestr();
		    $nativeObj["RetCode"] = $retcode;
		    $nativeObj["RetErrMsg"] = $reterrmsg;
		    $nativeObj["AppSignature"] = $this->get_biz_sign($nativeObj);
		    $nativeObj["SignMethod"] = $GLOBALS['accountinfo']['signtype'];
		    Yii::import("ext.CommonUtil");
		    $commonUtil = new CommonUtil();
		    return  $commonUtil->arrayToXml($nativeObj);
		}catch (SDKRuntimeException $e){
			die($e->errorMessage());
		}		
	}
}

?>