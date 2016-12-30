<?php
/**
 * MD5签名类：生成MD5签名及校验签名
 * ----------------------------------------------
 * 版权所有 2014-2015 联众互动
 * ----------------------------------------------
 * @date: 2014-11-25
 * @author: mankio <546234549@qq.com>
 * 
 */
class MD5SignUtil {
	/**
	 * 连接参数，MD5签名后再转成大写形式
	 * @param string $content  财付通签名内容
	 * @param string $key 财付通签名key
	 * @return string 返回MD5签名字符串
	 * @throws SDKRuntimeException
	 */
	function sign($content, $key) {
	    try {
		    if (null == $key) {
			   throw new SDKRuntimeException("财付通签名key不能为空！" . "<br>");
		    }
			if (null == $content) {
			   throw new SDKRuntimeException("财付通签名内容不能为空" . "<br>");
		    }
		    $signStr = $content . "&key=" . $key;
		    return strtoupper(md5($signStr));
		}catch (SDKRuntimeException $e)
		{
			die($e->errorMessage());
		}
	}
	/**
	 * 验证MD5签名
	 * @param string $content  财付通签名内容
	 * @param string $sign 待比较签名档
	 * @param string $md5Key 财付通签名key
	 * @return boolean  true:校验成功  false:校验失败
	 */
	function verifySignature($content, $sign, $md5Key) {
		$signStr = $content . "&key=" . $md5Key;
		$calculateSign = strtolower(md5($signStr));
		$tenpaySign = strtolower($sign);
		return $calculateSign == $tenpaySign;
	}
}

?>