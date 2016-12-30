<?php
/**
 * 异常处理类：返回异常
 * ----------------------------------------------
 * 版权所有 2014-2015 联众互动
 * ----------------------------------------------
 * @date: 2014-11-25
 * @author: mankio <546234549@qq.com>
 *
 */
class  SDKRuntimeException extends Exception {
	/**
	 * 返回错误
	 */
	public function errorMessage()
	{
		return $this->getMessage();
	}
}

?>