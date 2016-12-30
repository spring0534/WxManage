<?php
/**
 * 公共函数类：定义支付接口中一些常用的功能
 * ----------------------------------------------
 * 版权所有 2014-2015 联众互动
 * ----------------------------------------------
 * @date: 2014-11-25
 * @author: mankio <546234549@qq.com>
 * 
 */
class CommonUtil{
	/**
	 * 连接URL与参数
	 * @param string $toURL  待处理的URL
	 * @param string $paras  URL后面待拼接的参数
	 * @return string
	 */
	function genAllUrl($toURL, $paras) {
		$allUrl = null;
		if(null == $toURL){
			die("toURL is null");
		}
		if (strripos($toURL,"?") =="") {
			$allUrl = $toURL . "?" . $paras;
		}else {
			$allUrl = $toURL . "&" . $paras;
		}
		return $allUrl;
	}
	/**
	 * 默认产生随机16位字符串
	 * @param int $length 生成随机字符串的长度
	 * @return string
	 */
	function create_noncestr( $length = 16 ) {  
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";  
		$str ="";  
		for ( $i = 0; $i < $length; $i++ )  {  
			$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
		}  
		return $str;  
	}
	/**
	 * 
	 * 没有用到该函数
	 * @param src
	 * @param token
	 * @return
	 */
	function splitParaStr($src, $token) {
		$resMap = array();
		$items = explode($token,$src);
		foreach ($items as $item){
			$paraAndValue = explode("=",$item);
			if ($paraAndValue != "") {
				$resMap[$paraAndValue[0]] = $parameterValue[1];
			}
		}
		return $resMap;
	}
	
	/**
	 *  
	 * 去除空白字符串
	 * @param string $value
	 * @return 
	 */
	public static function trimString($value){
		$ret = null;
		if (null != $value) {
			$ret = $value;
			if (strlen($ret) == 0) {
				$ret = null;
			}
		}
		return $ret;
	}
	/**
	 * 将参数按字典序排序，使用URL键值对的格式拼接成字符串
	 * @param array $paraMap需要排序的数组
	 * @param boolean $urlencode是否需要urlencode()编码
	 * @return string  
	 */
	function formatQueryParaMap($paraMap, $urlencode){
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v){
			if (null != $v && "null" != $v && "sign" != $k) {
			    if($urlencode){
				   $v = urlencode($v);
				}
				$buff .= $k . "=" . $v . "&";
			}
		}
		$reqPar;
		if (strlen($buff) > 0) {
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}
	/**
	 * 将参数按字典序排序，使用URL键值对的格式拼接成字符串，字符串转小写形式
     * @param array $paraMap需要排序的数组
	 * @param boolean $urlencode是否需要urlencode()编码
	 * @return string  
	 */
	function formatBizQueryParaMap($paraMap, $urlencode){
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v){
		    if($urlencode){
			   $v = urlencode($v);
			}
			$buff .= strtolower($k) . "=" . $v . "&";
		}
		$reqPar;
		if (strlen($buff) > 0) {
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}
	/**
	 * 将数组转换为XML格式
	 * @param array $arr 待转换的数组
	 * @return string
	 */
	function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val){
        	 if (is_numeric($val)){
        	 	$xml.="<".$key.">".$val."</".$key.">"; 
        	 } else {
        	 	$xml.="<".$key."><![CDATA[".$val."]]></".$key.">"; 
        	 } 
        }
        $xml.="</xml>";
        return $xml; 
    }
    /**
     * 	作用：以post方式提交json到对应的接口url
     */
    function postJsonCurl($json,$url,$second=30){
    	//初始化curl
    	$ch = curl_init();
    	//设置超时
    	curl_setopt($ch, CURLOPT_TIMEOUT, $second);
    	//这里设置代理，如果有的话
    	//curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
    	//curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
    	curl_setopt($ch,CURLOPT_URL, $url);
    	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
    	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
    	//设置header
    	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	    	'Content-Type: application/json',
	    	'Content-Length: ' . strlen($json))
    	);
    	//要求结果为字符串且输出到屏幕上
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    	//post提交方式
    	curl_setopt($ch, CURLOPT_POST, TRUE);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    	//运行curl
    	$data = curl_exec($ch);
    	//curl_close($ch);
    	//返回结果
    	if($data){
    		curl_close($ch);
    		return $data;
    	}
    	else {
    		$error = curl_errno($ch);
    		echo "curl出错，错误码:$error"."<br>";
    		echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
    		curl_close($ch);
    		return false;
    	}
    }
    
}

?>