<?php

/**项目基类
* BController.php
* ----------------------------------------------
* 版权所有 2014-2015
* ----------------------------------------------
* @date: 2014-12-18
*
*/
class BController extends Controller{
	/**
	 * 错误提示
	 * @date: 2014-11-18
	 * @author : 佚名
	 * @param unknown $message
	 * @param string $jumpUrl
	 * @param number $time
	 * @param string $ajax
	 */
	function error($message, $jumpUrl='', $time=3, $ajax=false){
		$this->tip('操作失败', $message, 2, $jumpUrl, $time, $ajax);
	}
	/**
	 * 成功提示
	 * @date: 2014-11-18
	 * @author : 佚名
	 * @param unknown $message
	 * @param string $jumpUrl
	 * @param number $time
	 * @param string $ajax
	 */
	function success($message, $jumpUrl='', $time=1, $ajax=false){
		$this->tip('操作成功', $message, 1, $jumpUrl, $time, $ajax);
	}
	/**
	 * 提示信息
	 * @date: 2014-11-18
	 * @author : 佚名
	 * @param unknown $msgTitle
	 * @param unknown $message
	 * @param number $status
	 * @param string $jumpUrl
	 * @param number $waitSecond
	 * @param string $ajax
	 */
	function tip($msgTitle, $message, $status=1, $jumpUrl='', $waitSecond=1, $ajax=false){
		$c='success_cont';

		if ($jumpUrl=='top_refresh'){
			$jumpUrl="top.location.href='".WEB_URL."'";
		}else{
			if (empty($jumpUrl))
				$jumpUrl="history.back(-1);";
			else
				$jumpUrl="location.href='$jumpUrl'";
		}
		if ($status==2)
			$c='error_cont';
		$wtime=$waitSecond*1000;
		$path=__PUBLIC__.'/css';
		echo <<<JOT
		<!doctype html>
		<html>
		<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<title>提示信息</title>
		<link href="$path/msgtip.css" rel="stylesheet">
		</head>
		<body>
		<div class="wrap">
		<div id="error_tips">
		<h2>$msgTitle</h2>
		<div class="$c">
		<ul>
		<li>$message</li>
		</ul>
		<div class="error_return"><a href="javascript:;" onclick="$jumpUrl" class="btn">返回</a></div>
		</div>
		</div>
		</div>
		<script language="javascript">
		setTimeout(function(){
			$jumpUrl;
		},$wtime);
		</script>
		</body>
		</html>
JOT;
		exit();
	}
}