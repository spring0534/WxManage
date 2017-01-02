<?php
/**
 * 全局公共函数1
 * globals.php
 * ----------------------------------------------
 * 版权所有 2014-2015
 * ----------------------------------------------
 * @date: 2014-12-8
 *
 */
function dump($var, $echo=true, $label=null, $strict=true){
	$label=($label===null) ? '' : rtrim($label).' ';
	if (!$strict){
		if (ini_get('html_errors')){
			$output=print_r($var, true);
			$output='<pre>'.$label.htmlspecialchars($output, ENT_QUOTES).'</pre>';
		}else{
			$output=$label.print_r($var, true);
		}
	}else{
		ob_start();
		var_dump($var);
		$output=ob_get_clean();
		if (!extension_loaded('xdebug')){
			$output=preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
			$output='<pre>'.$label.htmlspecialchars($output, ENT_QUOTES).'</pre>';
		}
	}
	if ($echo){
		echo ($output);
		return null;
	}else
		return $output;
}
/**
 * 字符串加密函数
 * @param string $string
 * 原文或者密文
 * @param string $operation
 * 操作(ENCODE | DECODE), 默认为 DECODE
 * @param string $key
 * 密钥
 * @param int $expiry
 * 密文有效期, 加密时候有效， 单位 秒，0 为永久有效
 * @return string 处理后的 原文或者 经过 base64_encode 处理后的密文
 * @example $a = authcode('abc', 'ENCODE', 'key');
 * $b = authcode($a, 'DECODE', 'key'); // $b(abc)
 * $a = authcode('abc', 'ENCODE', 'key', 3600);// 密文一个小时内生效
 * $b = authcode('abc', 'DECODE', 'key'); // 在一个小时内，$b(abc)，否则 $b 为空
 */
function authcode($string, $operation='DECODE', $key='hfgh654hf6g4htrr5h4fgh45', $expiry=0){
	// 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
	$ckey_length=4;
	// 随机密钥长度 取值 0-32;
	// 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
	// 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
	// 当此值为 0 时，则不产生随机密钥
	// 密匙a会参与加解密
	$keya=md5(substr($key, 0, 16));
	// 密匙b会用来做数据完整性验证
	$keyb=md5(substr($key, 16, 16));

	// 密匙c用于变化生成的密文
	$keyc=$ckey_length ? ($operation=='DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';
	// 参与运算的密匙
	$cryptkey=$keya.md5($keya.$keyc);

	$key_length=strlen($cryptkey);
	// 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
	// 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
	$string=$operation=='DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry+time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length=strlen($string);

	$result='';
	$box=range(0, 255);

	$rndkey=array();
	// 产生密匙簿
	for ($i=0; $i<=255; $i++){
		$rndkey[$i]=ord($cryptkey[$i%$key_length]);
	}
	// 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
	for ($j=$i=0; $i<256; $i++){
		$j=($j+$box[$i]+$rndkey[$i])%256;
		$tmp=$box[$i];
		$box[$i]=$box[$j];
		$box[$j]=$tmp;
	}
	// 核心加解密部分
	for ($a=$j=$i=0; $i<$string_length; $i++){
		$a=($a+1)%256;
		$j=($j+$box[$a])%256;
		$tmp=$box[$a];
		$box[$a]=$box[$j];
		$box[$j]=$tmp;
		// 从密匙簿得出密匙进行异或，再转成字符
		$result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256]));
	}

	if ($operation=='DECODE'){
		// substr($result, 0, 10) == 0 验证数据有效性
		// substr($result, 0, 10) - time() > 0 验证数据有效性
		// substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性
		// 验证数据有效性，请看未加密明文的格式
		if ((substr($result, 0, 10)==0||substr($result, 0, 10)-time()>0)&&substr($result, 10, 16)==substr(md5(substr($result, 26).$keyb), 0, 16)){
			return substr($result, 26);
		}else{
			return '';
		}
	}else{
		// 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
		// 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}
/**
 * 加密方法，让authcode加密方法支持对数组进行加密;
 * @方法名：endecode
 * @param
 * $mix
 * @param
 * $operation
 * @param
 * $key
 * @param
 * $expiry
 * @author 佚名
 * @2012-11-5下午01:29:31
 */
function endecode($mix, $operation='DECODE', $key='', $expiry=0){
	if ($operation=='DECODE'){
		return json_decode(authcode($mix, $operation, $key, $expiry), true);
	}else{
		return authcode(json_encode($mix), $operation, $key, $expiry);
	}
}

/**
 * 将数据写入某文件，如果文件或目录不存在，则创建
 * @param string $filename
 * 要写入的目标
 * @param string $data
 * 要写入的数据
 * @return bool
 */
function file_write($filename, $data){
	mkdirs(dirname($filename));
	file_put_contents($filename, $data);
	return is_file($filename);
}
/**
 * 递归创建目录树
 * @param string $path
 * 目录树
 * @return bool
 */
function mkdirs($path){
	if (!is_dir($path)){
		mkdirs(dirname($path));
		mkdir($path);
	}
	return is_dir($path);
}

/**
 * 删除目录（递归删除内容）
 * @param string $path
 * 目录位置
 * @param bool $clean
 * 不删除目录，仅删除目录内文件
 * @return bool
 */
function rmdirs($path, $clean=false){
	if (!is_dir($path)){
		return false;
	}
	$files=glob($path.'/*');
	if ($files){
		foreach ($files as $file){
			is_dir($file) ? rmdirs($file) : @unlink($file);
		}
	}
	return $clean ? true : @rmdir($path);
}

/**
 * 是否包含子串
 */
function strexists($string, $find){
	return !(strpos($string, $find)===FALSE);
}
/**
 * 简化 Yii::app()
 *
 * @return CWebApplication
 */
function app(){
	return Yii::app();
}

/**
 * Yii::app()->clientScript
 *
 * @return CClientScript
 */
function cs(){
	return Yii::app()->getClientScript();
}

/**
 * 微应用URL生成
 *
 * @param string $route
 * @param array $params
 * @param string $ampersand
 * @return string
 */
function AU($route, $params=array(), $ampersand='&'){
	return Yii::app()->createUrl($_GET['_akey'].'/'.ltrim($route, '/'), $params, $ampersand);
}
/**
 * 微应用后台URL生成
 *
 * @param string $route
 * @param array $params
 * @param string $ampersand
 * @return string
 */
function AAU($route, $params=array(), $ampersand='&'){
	return Yii::app()->createUrl('appAdmin/'.$_GET['_akey'].'/'.ltrim($route, '/'), $params, $ampersand);
}
function MAU($route){
	return $_GET['_akey'].'/admin/'.ltrim($route, '/');
}

/**
 * This is the shortcut to CHtml::encode
 *
 * @param string $text
 * @return string
 */
function h($text){
	return htmlspecialchars($text, ENT_QUOTES, Yii::app()->charset);
}

/*
 * PHP截取中英文字符串，不按字符数而是按宽度来截取
 * 仅针对UTF-8字符
 */
function msubstr($str, $start=0, $length, $suffix=true, $charset="utf-8"){
	if (StrLenW($str)<$length){
		return $str;
	}
	if (function_exists("mb_substr"))
		$slice=mb_substr($str, $start, $length, $charset);
	elseif (function_exists('iconv_substr')){
		$slice=iconv_substr($str, $start, $length, $charset);
		if (false===$slice){
			$slice='';
		}
	}else{
		$re['utf-8']="/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312']="/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']="/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']="/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		$slice=join("", array_slice($match[0], $start, $length));
	}
	return $suffix ? $slice.'...' : $slice;
}
/**
 * 准确判断字符串长度，UTF8 需在php.ini中加载了php_mbstring.dll扩展
 * @方法名：StrLenW
 * @param unknown_type $str
 * @author 佚名
 * @2013-1-16下午04:09:47
 */
function StrLenW($str){
	return mb_strlen($str, 'UTF8');
}
function xml2array(&$xml, $isnormal=FALSE){
	yii::import('ext.XMLparse');
	$xml_parser=new XMLparse($isnormal);
	$data=$xml_parser->parse($xml);
	$xml_parser->destruct();
	return $data;
}
function array2xml($arr, $htmlon=TRUE, $isnormal=FALSE, $level=1){
	$s=$level==1 ? "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n<root>\r\n" : '';
	$space=str_repeat("\t", $level);
	foreach ($arr as $k=>$v){
		if (!is_array($v)){
			$s.=$space."<item id=\"$k\">".($htmlon ? '<![CDATA[' : '').$v.($htmlon ? ']]>' : '')."</item>\r\n";
		}else{
			$s.=$space."<item id=\"$k\">\r\n".array2xml($v, $htmlon, $isnormal, $level+1).$space."</item>\r\n";
		}
	}
	$s=preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);
	return $level==1 ? $s."</root>" : $s;
}
/**
 * Cookie 设置、获取、删除 默认前缀为app_
 * cookie('a','b') 设置cookie a值为b，cookie('a','b',60)设置时间为1分钟，cookie('a',null)删除cookie a，cookie(null)删除所有cookie,
 * cookie(null,'app_')删除事有app_前缀的所有cookie，cookie('a')获取cookie a
 * @param string $name
 * cookie名称
 * @param mixed $value
 * cookie值
 * @param mixed $options
 * cookie参数
 * @return mixed
 */
function cookie($name, $value='', $option=array()){
	// 默认设置
	$config=array(
		'prefix'=>'app_',  // cookie 名称前缀
		'expire'=>0,  // cookie 保存时间
		'path'=>'/',  // cookie 保存路径
		'domain'=>''
	); // cookie 有效域名

	// 参数设置(会覆盖黙认设置)
	if (!empty($option)){
		if (is_numeric($option))
			$option=array(
				'expire'=>$option
			);
			$config=array_merge($config, array_change_key_case($option));
	}
	// 清除指定前缀的所有cookie
	if (is_null($name)){
		if (empty($_COOKIE))
			return;
		// 要删除的cookie前缀，不指定则删除config设置的指定前缀
		$prefix=empty($value) ? $config['prefix'] : $value;
		if (!empty($prefix)){ // 如果前缀为空字符串将不作处理直接返回
			foreach ($_COOKIE as $key=>$val){
				if (0===stripos($key, $prefix)){
					setcookie($key, '', time()-3600, $config['path'], $config['domain']);
					unset($_COOKIE[$key]);
				}
			}
		}
		return;
	}
	$name=$config['prefix'].$name;
	if (''===$value){
		return isset($_COOKIE[$name]) ? json_decode($_COOKIE[$name],true) : null; // 获取指定Cookie
	}else{
		if (is_null($value)){
			setcookie($name, '', time()-3600, $config['path'], $config['domain']);
			unset($_COOKIE[$name]); // 删除指定cookie
		}else{
			// 设置cookie
			$value=json_encode($value);
			$expire=!empty($config['expire']) ? time()+intval($config['expire']) : 0;
			setcookie($name, $value, $expire, $config['path'], $config['domain']);
			$_COOKIE[$name]=$value;
		}
	}
}
/**
 * 判断一维数组中是否存在某个一项，返回true或者该项（判断区分大上写）
 * @date: 2014-10-8
 * @author : 佚名
 * @param
 * $string
 * @param
 * $arr
 * @param
 * $returnvalue
 */
function dstrpos($string, $arr, $returnvalue=false){
	if (empty($string))
		return false;
	foreach ((array) $arr as $v){
		if (strpos($string, $v)!==false){
			$return=$returnvalue ? $v : true;
			return $return;
		}
	}
	return false;
}
/**
 * 把序列化的图片数组转换为分号分割的图片地址
 * @date: 2015-07-09
 * @author : mankio <546234549@qq.com>
 * @param string $serizeImgUrl 序列化的图片字符串
 * @return string 返回处理以后的图片字符串
 */
function dealMulImg($serizeImgUrl){
	$data = unserialize($serizeImgUrl);
	$url = '';
	foreach($data as $v){
		$url .= $v['url'] . ';';
	}
	return rtrim($url,';');
}
/**
 * 判断是否是微信浏览器
 * @date: 2014-10-8
 * @author : 佚名
 */
function checkWeixinbrower(){
	static $browser_list =array('micromessenger', 'windows phone');
	$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
	if( dstrpos($useragent, $browser_list)){
		if( dstrpos($useragent, array('windowswechat'))){
			return false;//禁止在电脑端的微信浏览器打开
		}
		return true;
	}
	/*
	 * $brower = array('mozilla', 'chrome', 'safari', 'opera', 'm3gate', 'winwap', 'openwave', 'myop');
	 * if(dstrpos($useragent, $brower)) {
	 * return false;
	 * }
	 */
}
/**
 * 信息提示
 * @param string $msg
 * @param string $url
 * @param boolean $isAutoGo
 * @param int $time
 */
function exMsg($msg, $url='javascript:history.back(-1);', $isAutoGo=false, $time=2, $tiptitle='抱歉，出错了', $icon=1, $js=''){
	if ($msg=='404'){
		header("HTTP/1.1 404 Not Found");
		$msg='404 请求页面不存在！';
	}
	$icon--;
	$iconarr=array(
		__PUBLIC__.'/images/icon_smali.png',
		__PUBLIC__.'/images/icon_smali2.png'
	);
	$icon=$iconarr[$icon];
	echo <<<JOT
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Cache-control" content="no-cache">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<meta name="format-detection" content="telephone=no">
<meta name="keywords" content="">
JOT;
	if ($isAutoGo){
		echo "<meta http-equiv=\"refresh\" content=\"$time;url=$url\" />";
	}
	echo <<<JOT
<title>提示-</title>
<style>
*{word-wrap: break-word;}*{margin:0; padding:0;}html,body{height:100%; font:12px/1.6 Microsoft YaHei, Helvetica, sans-serif; color:#4C4C4C;}body, ul, ol, li, dl, dd, p, h1, h2, h3, h4, h5, h6, form, fieldset, .pr, .pc{margin: 0; padding: 0;}a:link,a:visited,a:hover{color:#4C4C4C; text-decoration:none;}.jump_c{padding:130px 25px; font-size:15px;}.grey{color:#A5A5A5;}.jump_c a{color:#2782BA;font-size: 14px;}.footer{text-align:center; line-height:2em; color:#A5A5A5; padding:10px 0 0 0; bottom: 10PX;width: 100%; position: fixed;}.footer a{margin:0 6px; color:#A5A5A5;}.nav{position: relative;height: 44px;border-top-color: #4a4f57;border-top-style: solid;border-top-width: 1px;background: rgba(53, 59, 68, 0.09);padding: 0; /*reset default*/}.header-tit{display: block;height: 44px;line-height: 44px;font-size: 20px;font-weight: bold;color: #cfdae5;text-align: center;}.header-tit .name{display: block;width: auto;height: 44px;margin: 0 50px;padding: 0;color: inherit;text-shadow: 0 2px 3px rgba(0, 0, 0, 0.5);overflow: hidden;text-overflow: ellipsis;}.header-tit .name.name_narrow{margin: 0 100px;}body{background: #2d3a4b;}
</style>
</head>
<body class="bg">
<header class="header">
    <div class="nav">
        <div class="header-tit">
            <span class="name"></span>
        </div>

    </div>
</header>
 <div style="width:100%;margin-top: 100px;">
	<div style=" width: 320px; margin-left: auto; margin-right: auto;font-size: 12PX;text-align: center;">
		<img class="pic-weixiao" src="$icon" style="height: 90px;">
	    <div style="text-align: center;margin-top: 10px;color:#FFFFFF">
			<h3 style=" margin-bottom: 15px;padding-top: 8px;">$tiptitle</h3>
JOT;
	if (!empty($msg))
		echo '错误提示：'.$msg;
	echo <<<JOT
		</div>
	</div>
   </div>
<div class="footer">
<p>©wxos Inc</p>
</div>
</body>
$js
</html>
JOT;
	exit();
}
/**
 * 解密x.js加密后传输过来的数据
 * @date: 2014-10-14
 * @author : 佚名
 * @param str $s
 */
function xDecode($s){
	$a=str_split($s, 2);
	$s='%'.implode('%', $a);
	$s=urldecode($s);
	if (strpos($s, session_id())!==false){
		$re=str_replace(session_id(), '', $s);
		return $re;
	}else{
		return false;
	}
}
function success($msg="", $jumpurl="", $wait=3){
	_jump($msg, $jumpurl, $wait, 1);
}
/**
 * 错误提示
 * @param type $msg
 * 提示信息
 * @param type $jumpurl
 * 跳转url
 * @param type $wait
 * 等待时间
 */
function error($msg="", $jumpurl="", $wait=3){
	_jump($msg, $jumpurl, $wait, 0);
}
/**
 * 最终跳转处理
 * @param type $msg
 * 提示信息
 * @param type $jumpurl
 * 跳转url
 * @param type $wait
 * 等待时间
 * @param int $type
 * 消息类型 0或1
 */
function _jump($msg="", $jumpurl="", $wait=3, $type=0){
	$title=($type==1) ? "提示信息" : "错误信息";
	if (empty($jumpurl)){
		if ($type==1){
			$jumpurl=isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "javascript:window.close();";
		}else{
			$jumpurl="javascript:history.back(-1);";
		}
	}
	echo <<<JOT
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>跳转提示</title>
	<style type="text/css">
		*{ padding: 0; margin: 0; }
		body{ background: #fff; font-family: '微软雅黑'; color: #333; font-size: 16px; }
		.system-message{ width:500px;height:100px; margin:auto;border:6px solid #999;text-align:center; position:relative;top:50px;}
		.system-message legend{font-size:24px;font-weight:bold;color:#999;margin:auto;width:100px;}
		.system-message h1{ font-size: 100px; font-weight: normal; line-height: 120px; margin-bottom: 12px; }
		.system-message .jump{ padding-right:10px;height:25px;line-height:25px;font-size:14px;position:absolute;bottom:0px;left:0px;background-color:#e6e6e1 ; display:block;width:490px;text-align:right;}
		.system-message .jump a{ color: #333;}
		.system-message .success,.system-message .error{ line-height: 1.8em; font-size: 15px }
		.system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display:none}
    </style>
    </head>
	<body>
	<fieldset class="system-message">
	    <legend>$title</legend>
	    <div style="text-align:left;padding-left:10px;height:75px;width:490px;  ">
JOT;
	if ($type==1){
		echo "<p class='success'>恭喜^_^!~".$msg."</p>";
	}else{
		echo "<p class='error'>Sorry!~".$msg."</p>";
	}
	echo <<<JOT
	   <p class="detail"></p>
	    </div>
	    <p class="jump">
	        页面自动 <a id="href" href="$jumpurl">跳转</a> 等待时间： <b id="wait">$wait</b>
	    </p>
	</fieldset>
	<script type="text/javascript">

	(function(){
	var wait = document.getElementById('wait'),href = document.getElementById('href').href;
	totaltime=parseInt(wait.innerHTML);
	var interval = setInterval(function(){
		var time = --totaltime;
	        wait.innerHTML=""+time;
		if(time === 0) {
			location.href = href;
			clearInterval(interval);
		};
	}, 1000);
	})();
	</script>
	</body>
	</html>
JOT;
}
/**
 * 数组深度 ,用于tree类的生成
 * @date: 2014-12-8
 * @author: 佚名
 * @param  $id
 * @param  $array
 * @param  $i
 * @return number
 */
function get_level($id, $array=array(), $i=0){
	foreach ($array as $n=>$value){
		if ($value['id']==$id){
			if ($value['pid']=='0')
				return $i;
			$i++;
			return get_level($value['pid'], $array, $i);
		}
	}
}
/**
 * 简化系统登录信息
 * @date: 2014-12-8
 * @author : 佚名
 */
function user(){
	return yii::app()->session['admin'];
}
/**
 * 简化公众账号登录信息
 * @date: 2014-12-8
 * @author : 佚名
 */
function gh(){
	return yii::app()->session['gh'];
}
/**
 * 重置当前登录公众账号信息
 * @date: 2014-12-10
 * @author: 佚名
 */
function resetGh($ghid=''){
	yii::app()->session['gh']=SysUserGh::model()->find("ghid='".$ghid."'");

}
/**
 * 简化URL生成，默认以echo输出结果，如果想以return返回,则以&开头
 * @date: 2014-12-8
 * @author : 佚名
 * @param
 * $route
 * @param
 * $params
 * @param
 * $ampersand
 * @return unknown
 */
function U($route, $params=array(), $ampersand='&'){
	$url=Yii::app()->createUrl(Yii::app()->controller->getModule()->id.'/'.Yii::app()->controller->id.'/'.ltrim($route, '&'), $params, $ampersand);
	if (strpos($route, '&')===0){
		return $url;
	}else{
		echo $url;
	}
}
/**
 * 时间格式化
 * @方法名：toDate
 * @param int $time
 * @param string $format
 * @author 佚名
 * @2012-10-24下午03:49:32
 */
function toDate($time, $format='Y-m-d H:i:s'){
	if (empty($time)){
		return '';
	}

	$format=str_replace('#', ':', $format);
	if (is_numeric($time)==0){

		return date($format, strtotime($time));
	}else{
		return date($format, $time);
	}
}
/**
 * 状态样式，以√和×的形式展示
 * @方法名：showstatu
 * @param int,string $val
 * @author yemaomm
 * @2012-10-24下午03:46:23
 */
function showstatu($val){
	if ($val==1){
		return "<font color=#FF0000>√</font>";
	}
	if ($val==0){
		return "<font color=#0000ff>×</font>";
	}
}

/**
 * 日历控件调用
 * @date: 2014-12-8
 * @author : 佚名
 * @param 控件id $field
 * @param 控件值 $value
 * @param 时间格式 $timeformat
 * @param 文本框长度 $width
 * @param 其它配置 $config
 * @return string
 */
/*function calendar($field, $value='', $timeformat='%Y-%m-%d  %H:%M:%S', $width="255px", $config=''){
	// %Y-%m-%d %H:%M:%S
	$pformat=str_replace('%', '', $timeformat);
	$val='';
	if (!empty($value)){
		if (is_numeric($value)){
			$val=toDate($value, $pformat);
		}else{
			$val=$value;
		}
	}
	;
	return '<div class="form-input-icon icon-right" style="width:'.$width.';display: inline-block;" ><i class="glyph-icon icon-calendar bg-white icon-class" style="right: 5;
width: 10px;"></i><input type="text" '.$config.' name="'.$field.'" id="'.$field.'" value="'.$val.'" autocomplete="off" style="padding:0;width:'.$width.';padding-left:3px;padding-right:35px;"> </div>
    	<script type="text/javascript">
	    	Wind.use("calendar","calendar_len", function () {
		    	Calendar.setup({
					weekNumbers: true,
				    inputField : "'.$field.'",
				    trigger    : "'.$field.'",
				    dateFormat: "'.$timeformat.'",
				    showTime: true,
				    minuteStep: 1,
				    onSelect   : function() {this.hide();}
					});
			 })
        </script>	';
}*/
function calendar($field, $value='', $timeformat='YYYY-MM-DD hh:mm:ss', $width="240px", $config='',$css=''){
	$val='';
	if (!empty($value)){
		if (is_numeric($value)){
			$val=toDate($value, 'Y-m-d H:i:s');
		}else{
			$val=$value;
		}
	}
	if(empty($timeformat))$timeformat='YYYY-MM-DD hh:mm:ss';
	//YYYY-MM-DD  %H:%M:%S
	//Y-m-d H:i:s
	if($timeformat=='%Y-%m-%d'){
		$timeformat='YYYY-MM-DD';
	}else if($timeformat=='%Y-%m-%d %H:%M:%S'){
		$timeformat='YYYY-MM-DD hh:mm:ss';
	}

	//YYYY-MM-DD hh:mm:ss
	return '<div class="form-input-icon icon-right" style="width:'.$width.';display: inline-block;'.$css.'" ><i class="glyph-icon icon-calendar bg-white icon-class" style="right: 5;
width: 10px;"></i><input onclick="laydate({istime: true, format: \''.$timeformat.'\'})" type="text" '.$config.' name="'.$field.'" id="'.$field.'" value="'.$val.'" autocomplete="off" style="padding:0;width:'.$width.';padding-left:3px;padding-right:35px;"> </div> 	';
}
/**
 * 百度编辑器调用
 * @方法名：ueditor
 * @param 控件名 $name
 * @param 控件值 $value
 * @param 控件id $id
 * @param 配置 $config
 * @author 佚名
 * @2013-11-18下午05:31:08
 */
function ueditor($name='', $value='', $id='', $width=550, $height=200, $config=''){
	$width=$width?$width:"'auto'";
	$height=$height?$height:200;
	$imgup=Yii::app()->createUrl('/ueditor/imageUp');
	$fileUp=Yii::app()->createUrl('/aueditor/fileUp');
	$imageManager=Yii::app()->createUrl('/ueditor/imageManager');
	$autoc=empty($value) ? 'true' : 'false';
	$devalue=empty($value) ? '请输入内容' : $value;
	$publicdir=__PUBLIC__;
	$text=<<<WT
	<script type="text/javascript">
		window.UEDITOR_HOME_URL = "$publicdir/js/ueditor/";

	</script>
    <script type="text/plain" id="$name"  name="$name">$devalue</script>
    <script>
    $(function(){
      Wind.use("ueditor_config", "ueditor", function () {
      var editor;
	  editor=UE.getEditor('$name',{
	  	    'enterTag':'' ,
	  		allowDivTransToP: false,
	  		autoFloatEnabled: false,
	    	autoClearinitialContent:$autoc,
	        initialFrameWidth : $width,
	        initialFrameHeight : $height,
	        contextMenu:[],
	        autoHeightEnabled:true,
	        imageUrl:'$imgup',
	        imagePath:'',
	        fileUrl:'$fileUp',
	        filePath:''
	    });
		editor.addListener('fullscreenchanged', function (type, isfullscreen) {
                if (!isfullscreen) {
                    //修复全屏返回后滚动条消失的问题
                   // document.documentElement.style.overflowX = 'hidden';
	        	    //document.documentElement.style.overflowY= 'auto';
                }
            });
      })
    })
	</script>

WT;
	return $text;
}
function dir_path($path){
	$path=str_replace('\\', '/', $path);
	if (substr($path, -1)!='/')
		$path=$path.'/';
	return $path;
}

/**
 *
 * @date: 2014-11-26
 * @author : 佚名
 * @param 文件保存文件夹 $tmppath
 * @param 允许上传的类型 $allowExts
 * @param
 * 是否添加 水印 $iswater
 * @param array $waterconfig
 * 格外配置参数，默认直接读取系统配置，如果要添加额外配置，如使用其它的水印图片，方法如下：$waterconfig['watermark_img']='my.png',其中watermark_img是系统参数的配置名，一定要按系统配置名命名
 * @return string
 */
function upLoad($tmppath, $allowExts="", $iswater=false, $waterconfig='',$savetable=true){
	yii::import('ext.UploadFile');
	$imageExts=array(
		"jpg",
		"gif",
		"jpeg",
		"png",
		"bmp",
		'ico'
	);
	if ($allowExts==""){
		$allowExts=$imageExts;
	}
	$upload=new UploadFile(); // 实例化上传类
	$configs=''; // get_websetup_config ();
	$configs['attach_maxsize']=10;
	$upload->maxSize=intval($configs['attach_maxsize'])*1024*1024; // 设置附件上传大小
	                                                               // dump($configs);
	$upload->allowExts=$allowExts; // 设置附件上传类型
	gh()? $savepath=UPLOAD_PATH."/".base64_encode(gh()->ghid) : $savepath=UPLOAD_PATH;
	$uppath=empty($tmppath) ? $savepath."/" : $savepath."/".$tmppath."/"; // 设置附件上传目录
	if (!file_exists(dir_path($uppath)))
		mkdir(dir_path($uppath), 0755, true);
	$upload->savePath=$uppath;
	if (empty($tmppath)){
		$upload->autoSub=true;
	}else{
		$upload->autoSub=false;
	}
	$upload->subType="date";
	$upload->dateFormat='Ymd';
	if (!$upload->upload()){
		return $upload->getErrorMsg();
	}else{
		$abspath=str_ireplace(ROOT_PATH, "", $upload->savePath);
		$info=$upload->getUploadFileInfo();
		foreach ($info as $keys=>$vals){
			$fileurl=Yii::app()->params['preImageUrl'].$abspath.$vals["savename"];
			$isimage=0;
			if (in_array($vals['extension'], $imageExts)){
				list ($w, $h)=getimagesize($uppath.'/'.$vals["savename"]);
				$imageSize=$w.'x'.$h;
				$isimage=1;
			}
			if($savetable){
			$model=new SysAttachment();
			$model->attributes=array(
				'filename'=>$vals['name'],
				'filepath'=>$abspath.$vals["savename"],
				'url'=>$fileurl,
				'filesize'=>$vals['size'],
				'fileext'=>$vals['extension'],
				'isimage'=>$isimage,
				'imagesize'=>$imageSize,
				'userid'=>user()->id,
				'ghid'=>gh()?gh()->ghid:'',
				'ctm'=>time(),
				'uploadip'=>Yii::app()->request->userHostAddress,
				'status'=>' 1',
				'authcode'=>$vals['hash']
			);
			if (!$model->save()){
				exit('0,上传错误，请联系管理员！');
			}
			;
			}
			$info[$keys]["savename"]=$fileurl;
			$info[$keys]['save_url']=$uppath.'/'.$vals["savename"];
		}
		// 给m_缩略图添加水印, Image::water('原文件名','水印图片地址')
		if ($iswater){
			yii::import('ext.Image');
			$configs=empty($waterconfig) ? $configs : array_merge($configs, $waterconfig);
			foreach ($info as $keys=>$vals){
				Image::watermark(ROOT_PATH.$vals['savename'], '', $configs);
				// $thumbname = $upload->thumbPrefix . basename ( $vals ['savename'] );
				// $info [$keys] ["thumbname"] = str_ireplace ( basename ( $vals ['savename'] ), $thumbname, $vals ['savename'] );
			}
		}
		foreach ($info as $k=>$v){
			unset($info[$k]['savepath']);
		}
		return $info;
	}
}

/**
 * 获取输入参数 支持过滤和默认值
 * 使用方法:
 * <code>
 * I('id',0); 获取id参数 自动判断get或者post
 * I('post.name','','htmlspecialchars'); 获取$_POST['name']
 * I('get.'); 获取$_GET
 * </code>
 * @param string $name
 * 变量的名称 支持指定类型
 * @param mixed $default
 * 不存在的时候默认值
 * @param mixed $filter
 * 参数过滤方法
 * @return mixed
 */
function I($name, $default='', $filter=null){
	if (strpos($name, '.')){ // 指定参数来源
		list ($method, $name)=explode('.', $name, 2);
	}else{ // 默认为自动判断
		$method='param';
	}
	switch (strtolower($method)){
		case 'get':
			$input=& $_GET;
			break;
		case 'post':
			$input=& $_POST;
			break;
		case 'put':
			parse_str(file_get_contents('php://input'), $input);
			break;
		case 'param':
			switch ($_SERVER['REQUEST_METHOD']){
				case 'POST':
					$input=$_POST;
					break;
				case 'PUT':
					parse_str(file_get_contents('php://input'), $input);
					break;
				default:
					$input=$_GET;
			}
			break;
		case 'request':
			$input=& $_REQUEST;
			break;
		case 'session':
			$input=& $_SESSION;
			break;
		case 'cookie':
			$input=& $_COOKIE;
			break;
		case 'server':
			$input=& $_SERVER;
			break;
		case 'globals':
			$input=& $GLOBALS;
			break;
		default:
			return NULL;
	}
	if (empty($name)){ // 获取全部变量
		$data=$input;
		$filters=isset($filter) ? $filter : 'htmlspecialchars';
		if ($filters){
			$filters=explode(',', $filters);
			foreach ($filters as $filter){
				$data=array_map($filter, $data); // 参数过滤
			}
		}
	}elseif (isset($input[$name])){ // 取值操作
		$data=$input[$name];
		$filters=isset($filter) ? $filter : 'htmlspecialchars';
		if ($filters){
			$filters=explode(',', $filters);
			foreach ($filters as $filter){
				if (function_exists($filter)){
					$data=is_array($data) ? array_map($filter, $data) : $filter($data); // 参数过滤
				}else{
					$data=filter_var($data, is_int($filter) ? $filter : filter_id($filter));
					if (false===$data){
						return isset($default) ? $default : NULL;
					}
				}
			}
		}
	}else{ // 变量默认值
		$data=isset($default) ? $default : NULL;
	}
	return $data;
}

/**
 * flash上传初始化
 * 初始化swfupload上传中需要的参数
 * @param $module 模块名称
 * @param $catid 栏目id
 * @param $args 传递参数
 * @param $userid 用户id
 * @param $groupid 用户组id
 * 默认游客
 * @param $isadmin 是否为管理员模式
 */
function initupload($args){
	session_start();
	// 同时允许的上传个数, 允许上传的文件类型, 是否允许从已上传中选择, 图片高度, 图片宽度,是否添加水印1,允许上传的大小
	if (!is_array($args)){
		$args=explode(',', $args);
	}
	$file_size_limit=intval($args[6])*1024;
	if (empty($args[6])){
		$file_size_limit=6*1024; // 暂时写死
	}
	$upload_url=Yii::app()->createUrl('commonUpload/ajax_up');
	// 参数补充完整
	if (empty($args[1])){
		// 如果允许上传的文件类型为空，启用网站配置的 uploadallowext
		$args[1]='gif|jpg|jpeg|png|bmp';
	}
	if(empty($args[10])){
		$upfun='admin';
	}else{
		$upfun=$args[10];
	}
	// 允许上传后缀处理
	$arr_allowext=explode('|', $args[1]);
	foreach ($arr_allowext as $k=>$v){
		$v='*.'.$v;
		$array[$k]=$v;
	}
	$upload_allowext=implode(';', $array);
	// 上传个数
	$file_upload_limit=(int) $args[0] ? (int) $args[0] : 8;
	// swfupload flash 地址
	$flash_url=__PUBLIC__.'/js/swfupload/swfupload.swf';
	$module='Contents';
	$init='var swfu_'.$module.' = \'\';
	$(document).ready(function(){
		Wind.use("swfupload",WEBURL+"/public/static/js/swfupload/handlers.js",function(){
		      swfu_'.$module.' = new SWFUpload({
			flash_url:"'.$flash_url.'?"+Math.random(),
			upload_url:"'.$upload_url.'",
			file_post_name : "Filedata",
			post_params:{
                                    "thumb_width":"'.intval($args[3]).'",
                                    "thumb_height":"'.intval($args[4]).'",
                                    "thumb_div":"'.intval($args[7]).'",//是否显示图库
                                    "online_div":"'.intval($args[8]).'",//是否显示网络文件
                                    "res_div":"'.intval($args[9]).'",//是否显示素材提供
                                    "filetype_post":"'.$args[1].'",
                                    "swf_auth_key":"'.md5(time()).'",
                                    "sid": "'.session_id().'",
                                    "upfun":"'.$upfun.'",
                                    "resfile":"'.$args[11].'",
                                    "ext":"'.$args[12].'",

			},
			file_size_limit:"'.$file_size_limit.'KB",
			file_types:"'.$upload_allowext.'",
			file_types_description:"All Files",
			file_upload_limit:"'.$file_upload_limit.'",
			custom_settings : {progressTarget : "fsUploadProgress",cancelButtonId : "btnCancel"},

			button_image_url: "",
			button_width: 75,
			button_height: 28,
			button_placeholder_id: "buttonPlaceHolder",
			button_text_style: "",
			button_text_top_padding: 3,
			button_text_left_padding: 12,
			button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
			button_cursor: SWFUpload.CURSOR.HAND,

			file_dialog_start_handler : fileDialogStart,
			file_queued_handler : fileQueued,
			file_queue_error_handler:fileQueueError,
			file_dialog_complete_handler:fileDialogComplete,
			upload_progress_handler:uploadProgress,
			upload_error_handler:uploadError,
			upload_success_handler:uploadSuccess,
			upload_complete_handler:uploadComplete
		      });
		});
	})';
	return $init;
}
/**
 *
 * @方法名：thumb
 * @param 图片路径 $f
 * @param 长 $tw
 * @param 高 $th
 * @param 自动截取 $autocat
 * @param 无图片显示的图片 $nopic
 * @param 自定义缩略图名 $t
 * @author 佚名
 * @2014-5-4上午12:45:51
 */
function thumb($f, $tw=300, $th=300, $autocat=0, $nopic='nopic.png', $t=''){
	if (strstr($f, '://'))
		return $f;
	if (empty($f)||!is_file(ROOT_PATH.$f))
		return __PUBLIC__.'/images/'.$nopic;
	$f='.'.str_replace(WEB_URL, '', $f);
	$temp=array(
		1=>'gif',
		2=>'jpeg',
		3=>'png'
	);
	list ($fw, $fh, $tmp)=getimagesize($f);
	if (empty($t)){
		if ($fw>$tw&&$fh>$th){
			$pathinfo=pathinfo($f);
			$t=$pathinfo['dirname'].'/thumb_'.$tw.'_'.$th.'_'.$pathinfo['basename'];
			if (is_file($t)){
				return WEB_URL.substr($t, 1);
			}
		}else{
			return WEB_URL.substr($f, 1);
		}
	}
	if (!$temp[$tmp]){
		return false;
	}
	if ($autocat){
		if ($fw/$tw>$fh/$th){
			$fw=$tw*($fh/$th);
		}else{
			$fh=$th*($fw/$tw);
		}
	}else{
		$scale=min($tw/$fw, $th/$fh); // 计算缩放比例
		if ($scale>=1){
			// 超过原图大小不再缩略
			$tw=$fw;
			$th=$fh;
		}else{
			// 缩略图尺寸
			$tw=(int) ($fw*$scale);
			$th=(int) ($fh*$scale);
		}
	}
	$tmp=$temp[$tmp];
	Yii::import('ext.Image');
	Image::thumb($f, $t,$tmp,$tw,$th);
	return WEB_URL.substr($t, 1);
}

/**
 * 单图片上传控件,注意控件ID不支持id[xx]格式
 * @date: 2014-11-26
 * @author : 佚名
 * @param 控件名称 $name
 * @param 控件初始值 $value
 * @param 弹窗dialogid $uploadid
 * @param $htmlOptions
 * @param 控件显示类型input image $type
 * @param 是否支持修改 $update
 * @return string
 */

function imageUpdoad($name, $value='', $uploadid, $htmlOptions=array(), $type='image', $update=true, $button_text=''){
	$args='1,gif|jpg|jpeg|png|bmp,1,,,0,10,1,1,1';
	$authkey='jfksjdflsjflksjdfkl';
	$module=Yii::app()->controller->id;
	$input=CHtml::textField($name, $value, array_merge(array(
		'style'=>'width:200px;height: 28px',
		'class'=>"col-md-6 float-left",
		'id'=>$name
	), $htmlOptions));
	$id=$htmlOptions['id'] ? $htmlOptions['id'] : $name;
	$input2=CHtml::hiddenField($name, $value, array_merge(array(
		'style'=>'width:200px;height: 28px',
		'class'=>"col-md-6 float-left",
		'type'=>'hidden',
		'id'=>$id
	), $htmlOptions));
	$d=Yii::app()->params['defautlUploadImg'];
	if ($type=='image'){
		$preview_img=$value ? $value : Yii::app()->params['defautlUploadImg'];
		$button_text=$value ? ($button_text ? $button_text : '更换图片') : '上传图片';
		$onclick="flashupload('{$uploadid}', '上传图片','{$id}',thumb_images,'{$args}')";
		if ($value&&!$update)
			$onclick='';
		$htmlTag=<<<EOT
		<div class="rowDiv">
		 	<div class="rightDiv">
		 		<div id="uploadIndexImg" class="webuploader-container">
		 		<div class="webuploader-pick primary-bg medium " onclick="{$onclick}"><i class="glyph-icon icon-cloud-upload float-left"></i>{$button_text}</div>
		 		</div>
		 		{$input2}
		 		<div id="fyfststbDiv" class="fyfststbDiv">
		 			<img src='{$preview_img}' id='{$id}_preview'  onload="DrawImage(this,100,100,true)" onclick="image_priview($('#{$id}_preview').attr('src'))" />
		 			<div class="bottomDiv" onclick="$('#{$id}_preview').attr('src','{$d}');$('#{$id}').val('');">x</div>
		 		</div>
		 	</div>
		</div>

EOT;
	}else{
		$onclick="flashupload('{$uploadid}', '上传图片','{$id}',thumb_images,'{$args}')";
		if ($value&&!$update)
			$onclick='';
		$htmlTag=<<<EOT
	<div class="form-input col-md-10">
	{$input}
	<a class="btn primary-bg medium" href="javascript:;" style="margin: 0 5px 0 5px;" onclick="{$onclick}" >
	<span class="button-content">上传图片</span>
	</a>
	<a class="btn primary-bg medium" href="javascript:;" style="margin: 0 5px 0 5px;" onclick="$('#{$id}').val('');return false;">
	<span class="button-content">取消图片</span>
	</a>
	</div>
EOT;
	}
	return $htmlTag;
}

/**
 * 多图片上传控件
 * @date: 2014-11-26
 * @author : 佚名
 * @param 控件名 $name
 * @param 控件初始值 $value
 * @param
 * 弹窗dialog id $uploadid
 * @return string
 */
function muimageUpload($name, $value='', $uploadid=''){
	$field=$name;
	$uploadid?$uploadid:$uploadid=$field;
	$setting['upload_limit']=10;
	$setting['upload_allowext']='gif|jpg|jpeg|png|bmp';
	$list_str='';
	if (!empty($value)){
		$value=unserialize(html_entity_decode($value, ENT_QUOTES));
		if (is_array($value)){
			foreach ($value as $_k=>$_v){
				$list_str.="<li id='image_{$field}_{$_k}' ><i class='glyph-icon icon-resize-vertical piclist_move'></i><input type='text' name='uploadImages[{$field}][$_k][url]' value='{$_v['url']}' style='width:310px;' ondblclick='image_priview(this.value);' class='input'>
				<input type='text' name='uploadImages[{$field}][$_k][alt]' value='{$_v['alt']}' style='width:160px;' class='input'>
				<a href=\"javascript:;\" class=\" medium radius-all-2  btn popover-button-default\"  data-trigger=\"hover\" data-placement=\"right\" data-original-title='{$_v['alt']}' data-content=\"<img width='250px' src='{$_v['url']}' >\"><span class=\"button-content text-center float-none font-size-11 text-transform-upr\">预览</span></a><a href=\"javascript:remove_div('image_{$field}_{$_k}')\">移除</a></li>";
			}
		}
	}else{
		$list_str.="<center><div class='onShow' id='nameTip'>您最多每次可以同时上传 <font color='red'>{$setting['upload_limit']}</font> 张</div></center>";
	}
	$input="
		<a href=\"javascript:;\" style='margin: 10px;' onclick=\"javascript:flashupload('{$field}_images', '图片上传','{$uploadid}',change_images,'{$setting['upload_limit']},{$setting['upload_allowext']},{$setting['isselectimage']},,,0,10,1,1,1')\" class=\"btn small bg-twitter\">
            <span class=\"glyph-icon icon-separator\">
                <i class=\"glyph-icon icon-plus\"></i>
            </span>
            <span class=\"button-content\">
                添加图片
            </span>
        </a>";
	$string='<fieldset class="blue pad-10" style="text-align:center;"><legend>图片列表</legend><ul class="column-sort picList" id="'.$uploadid.'">';
	$string.=$list_str;
	$string.='</ul>'.$input.'</fieldset>';
	return $string;
}
function musicUpdoad($name, $value='', $uploadid, $htmlOptions=array(), $button_text=''){
	$args='1,mp3|wav,1,,,0,10,0,1,0,music';
	$module=Yii::app()->controller->id;
	$id=$htmlOptions['id'] ? $htmlOptions['id'] : $name;
	if(empty($value)){
		$input=CHtml::textField($name, $value, array_merge(array(
			'style'=>'width:200px;height:30px',
			'class'=>"col-md-6 float-left",
			'id'=>$name
		), $htmlOptions));
	}else{
		$input=CHtml::textField($name, $value, array_merge(array(
			'style'=>'width:200px;height:30px;display:none;',
			'class'=>"col-md-6 float-left",
			'id'=>$name
		), $htmlOptions));
		$html=<<<EOT
		<audio src="$value" preload="auto" controls></audio>
		<script>
	    $(function(){

	      Wind.use("audioplayer", function () {

			$('#clear_$uploadid').click(function(){
					$(this).siblings('.audioplayer').remove();
					$('#$uploadid').show().val('');
				});
			});
	    })
		</script>
EOT;
	}
	if(empty($value)){$button_text='上传音乐';}else{$button_text='更改音乐';}

	$d=Yii::app()->params['defautlUploadImg'];
	$onclick="flashupload('{$uploadid}', '上传音乐','{$id}',thumb_music,'{$args}')";
	$htmlTag=<<<EOT
	<div style="line-height: 30px;">
	{$input}
	{$html}
	<a class="btn primary-bg medium" href="javascript:;" style="margin: 0 5px 0 5px;" onclick="{$onclick}" >
	<span class="button-content">{$button_text}</span>
	</a>
		<a href="javascript:;" class="btn medium bg-gray" id="clear_$uploadid">
            <span class="button-content">清除</span>
        </a>
	</div>
EOT;

	return $htmlTag;
}

/**
 * 获取模型类名并转为YII表单字段名
 * @date: 2014-12-8
 * @author : 佚名
 * @param
 * $model
 * @param
 * $field
 * @return string
 */
function chtmlName($model, $field){
	return get_class($model)."[$field]";
}
function showKeyword($type, $title){
	$arr=array(
		'image'=>'默认图片消息配置',
		'voice'=>'默认语音消息配置',
		'video'=>'默认视频消息配置',
		'subscribe'=>'关注回复配置',
		'other'=>'缺省回复配置'
	);
	if ($arr[$type]){
		return '<span class="label primary-bg tooltip-button" title="" data-original-title="'.$arr[$type].'">'.$arr[$type].'</span>';
	}
	return $title;
}
/**
 * 获取真实的搜索条件
 * @date: 2014-11-24
 * @author : 佚名
 * @param
 * $val
 * @param
 * $arr
 */
function getSearchKey($val, $arr){
	foreach ($arr as $k=>$v){
		if ($val==$v)
			return $k;
	}
	return '';
}
/**
 * +----------------------------------------------------------
 * 产生随机字串，可用来自动生成密码 默认长度6位 字母和数字混合
 * +----------------------------------------------------------
 * @param string $len
 * 长度
 * @param string $type
 * 字串类型
 * 0 字母 1 数字 其它 混合
 * @param string $addChars
 * 额外字符
 * +----------------------------------------------------------
 * @return string +----------------------------------------------------------
 */
function rand_string($len=6, $type='', $addChars=''){
	$str='';
	switch ($type){
		case 0:
			$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.$addChars;
			break;
		case 1:
			$chars=str_repeat('0123456789', 3);
			break;
		case 2:
			$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ'.$addChars;
			break;
		case 3:
			$chars='abcdefghijklmnopqrstuvwxyz'.$addChars;
			break;
		case 4:
			$chars="们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借".$addChars;
			break;
		default:

			// 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
			$chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'.$addChars;
			break;
	}
	if ($len>10){ // 位数过长重复字符串一定次数
		$chars=$type==1 ? str_repeat($chars, $len) : str_repeat($chars, 5);
	}
	if ($type!=4){
		$chars=str_shuffle($chars);
		$str=substr($chars, 0, $len);
	}else{
		// 中文随机字
		for ($i=0; $i<$len; $i++){
			$str.=msubstr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8')-1)), 1);
		}
	}
	return $str;
}

/**
 * 删除整个目录
 * @param $dir
 * @return bool
 */
function delDir( $dir )
{
	//先删除目录下的所有文件：
	if(is_dir($dir)){
	$dh = opendir( $dir );
	while ( $file = readdir( $dh ) ) {
		if ( $file != "." && $file != ".." ) {
			$fullpath = $dir . "/" . $file;
			if ( !is_dir( $fullpath ) ) {
				unlink( $fullpath );
			} else {
				delDir( $fullpath );
			}
		}
	}
	closedir( $dh );
	//删除当前文件夹：
	return rmdir( $dir );
	}
}

/**
 *
 * @date: 2015-1-5
 * @author: 佚名
 * @param string $name 生成图片名，不包含后缀
 * @param string $data 生成二维码数据  电话格式 'tel:15578432595' 文本 'text:你好啊' 链接 'url:http://qq.com'
 * @param number $type 输出类型
 * @param string $level  纠错级别：L、M、Q、H
 * @param string $level  大小：1到10,用于手机端4就可以了
 * @param string $picPath 自定义保存路径，默认为QR_PATH
 * @param string $logo 是否带logo
 * @return string
 */
function  createQRC($name="",$data="",$type=1,$level='L',$size = 4,$picPath="",$logo="logo.png"){
	Yii::$enableIncludePath=false;
	Yii::import('ext.phpqrcode.phpqrcode', 1);
	$QRcode = new QRcode();
	$data = $data?$data:'二维码生成有误，联系管理员处理!';
	$path = $picPath?$picPath:QR_PATH;
	// 生成的文件名
	$fileName =$name.'.png';
	//判断文件是否存在，存在返回二维码图片名字
	$checkFile = $path.$fileName;
	if(file_exists($checkFile)){
		return $fileName;
		Yii::$enableIncludePath=true;
		exit;
	}
	switch ($type){
		case 1 and 3://生成图片并保存，返回文件URL
			$QRCimg= $QRcode->png($data,$checkFile,$level,$size);
			if(!empty($logo)){
				$QR = imagecreatefromstring(file_get_contents($checkFile));
				$logo = imagecreatefromstring(file_get_contents($logo));
				$QR_width = imagesx($QR);
				$QR_height = imagesy($QR);
				$logo_width = imagesx($logo);
				$logo_height = imagesy($logo);
				$logo_qr_width = $QR_width / 5;
				$scale = $logo_width / $logo_qr_width;
				$logo_qr_height = $logo_height / $scale;
				$from_width = ($QR_width - $logo_qr_width) / 2;
				imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
			}
			Yii::$enableIncludePath=true;
			if($type==3){
				echo "<img src='".$checkFile."' />";exit;
			}else{
				//return WEB_URL.'/qrcode/'.$fileName;
				return $fileName;
			}
			break;
		case 2://直接输出图片流
			QRcode::png($data, false, $level, $size);
			Yii::$enableIncludePath=true;
			break;

	}
}

/**
 * 虽说最新的 PHP 5.4 已经良好支持 JSON 中文编码，即通过 JSON_UNESCAPED_UNICODE 参数，例如：
 * json_encode("中文", JSON_UNESCAPED_UNICODE)对于早前 PHP 版本
 * 将unicode中文字符转为中文是作为5.4以下版本兼容
 * @date: 2015-1-6
 * @author: 佚名
 * @param unknown $str
 * @return mixed
 */
 function decodeUnicode($str) {
	return preg_replace_callback ( '/\\\\u([0-9a-f]{4})/i', create_function ( '$matches', 'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");' ), $str );
}

function num2c($num){
	$arr=array(1=>'一',2=>'二',3=>'三',4=>'四',5=>'五',6=>'六',7=>'七',8=>'八',9=>'九',10=>'十');
	return $arr[$num];
}

/**
 * 获取网站设置相关缓存，得到的是一个数组，字段名为数组的键，数据值为数组的值针对COMS用户
 * @方法名：get_websetup_config
 * @author 	佚名
 * @2012-11-1下午04:50:17
 */
function get_websetup_config($recache=false) {
	$config=Yii::app()->cache->get('webconfig');
	if(empty($config)||$recache){
		$mm=Yii::app()->db->createCommand()
		->select('ctitle,cname,cvalue')
		->from('sys_setup')
		->queryAll();
		$list = array ();
		foreach ( $mm as $keys => $vals ) {
			$list [$vals ["cname"]] = $vals ["cvalue"];
		}
		$config=$list;
		Yii::app()->cache->set('webconfig',$config,30*24*60*60);//30天

	}
	return $config;


}
function getErrStr($error){
	foreach ($error as $kk=>$vv){
		if(is_array($vv)){
			foreach ($vv as $kkk=>$vvv){
				$str.=$vvv."\r\n";
			}
		}else{
			$str.=$vv."\r\n";
		}
	}
	return "\r\n".$str;
}
/*
 * 生成短字符串 最长6位
 * @param string $string 要生成的字符串
 * @param string $key	生成规则的key
 * @method create_shortstr
 * @author Jxcent
 * @version 2012-11-27 下午03:08:01
 */
function create_shortstr($string, $key = 'ruitop') {
	$base32 = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$hex = hash ( 'md5', $string . $key );
	$hexLen = strlen ( $hex );
	$subHexLen = $hexLen / 8;
	$subHex = substr ( $hex, 0, 8 );
	$idx = 0x3FFFFFFF & (1 * ('0x' . $subHex));

	for($j = 0; $j < 6; $j ++) {
		$val = 0x0000003D & $idx;
		$out .= $base32 [$val];
		$idx = $idx >> 5;
	}
	return $out;
}


/**
 * 循环创建目录
 * @param string $dir
 * @param string $mode
 * @method mk_dir
 */
function mk_dir($dir, $mode = 0777) {
	if (is_dir ( $dir ) || @mkdir ( $dir, $mode ))
		return true;
	if (! mk_dir ( dirname ( $dir ), $mode ))
		return false;
	return @mkdir ( $dir, $mode );
}

/**
 * 获取图片的信息，如果获取失败返回null
 * @方法名：getImagesInfo
 * @param string $img_path 图片绝对路径
 * @author 	佚名
 * @2013-2-18上午10:09:46
 */
function getImagesInfo($img_path) {
	$arrinfo = null;
	if (getimagesize ( $img_path )) {
		list ( $width, $height, $type, $attr ) = getimagesize ( $img_path );
		$arrinfo ['width'] = $width;
		$arrinfo ['height'] = $height;
		$arrinfo ['type'] = $type;
		$arrinfo ['attr'] = $attr;
	}
	return $arrinfo;
}

/**
 *
 * @方法名：get_client_ip
 * @author 	佚名
 * @2014-1-14上午11:47:44
 */
function get_clientTrue_ip() {
	$ip = $_SERVER ['REMOTE_ADDR'];
	if (isset ( $_SERVER ['HTTP_CDN_SRC_IP'] )) {
		$ip = $_SERVER ['HTTP_CDN_SRC_IP'];
	} elseif (isset ( $_SERVER ['HTTP_CLIENT_IP'] ) && preg_match ( '/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER ['HTTP_CLIENT_IP'] )) {
		$ip = $_SERVER ['HTTP_CLIENT_IP'];
	} elseif (isset ( $_SERVER ['HTTP_X_FORWARDED_FOR'] ) and preg_match_all ( '#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER ['HTTP_X_FORWARDED_FOR'], $matches )) {
		foreach ( $matches [0] as $xip ) {
			if (! preg_match ( '#^(10|172\.16|192\.168)\.#', $xip )) {
				$ip = $xip;
				break;
			}
		}
	}
	return $ip;
}
/**
 * 获取当前分表表名
 * @方法名：get_tablename
 * @param unknown_type $kid
 * @author 	佚名
 * @2014-2-6上午11:00:08
 */
function get_tablename($kid='') {
	if ($kid) {
		return "stat".substr ( $kid, 0, 6 );
	} else {
		$tablename = date ( 'Ym', time () );
		//如果表不存在
		if (count(Yii::app()->db->createCommand("SHOW TABLES LIKE 'stat_data" . $tablename . "'")->query()) < 1) {
			$model_sql = "CREATE TABLE stat_data" . $tablename . " LIKE stat_data;";
			Yii::app()->db->createCommand($model_sql)->execute();
			Yii::app()->db->createCommand()->insert('stat_data_tablename', array (
			'name' => "stat_data" . $tablename,
			'addtime' => time ()
			));
			//仅合并最近6个月的的表
			$tables=Yii::app()->db->createCommand()
			->select('name')
			->from('stat_data_tablename')
			->order ( 'addtime DESC' )
			->limit ( 6 )
			->queryAll();
			foreach ( $tables as $key => $val ) {
				$tablearr [] = $val ['name'];
			}
			$uniontables = implode ( ',', $tablearr );
			$mergeunionsql = "ALTER TABLE stat_fulldata_merge UNION = ($uniontables);"; //最近6个月的聚合表
			Yii::app()->db->createCommand($mergeunionsql)->execute();
		}
		return "stat_data" . $tablename;
	}

}
/**
 * 创建 聚合表
 * @方法名：create_merge_table
 * @param unknown_type $union
 * @author 	佚名
 * @2014-2-6上午11:00:25
 */
function create_merge_table($union) {

	Yii::app()->db->createCommand("CREATE TABLE stat_fulldata_merge" . " LIKE stat_data;")->execute();
	Yii::app()->db->createCommand("ALTER TABLE stat_fulldata_merge ENGINE = MERGE;")->execute();
	Yii::app()->db->createCommand("ALTER TABLE stat_fulldata_merge UNION =($union);")->execute();
}
/**
 * 获致搜索引擎数组ID
 * @方法名：searchengine
 * @param unknown_type $k
 * @author 	佚名
 * @2014-2-6上午10:59:32
 */
function searchengine($k) {
	$searchengine = array ('谷歌', '百度', '360搜索', '搜狗', '腾讯soso', '有道', '微软bing', '雅虎' );
	if ($k) {
		return $searchengine [$k];
	} else {
		return $searchengine;

	}
}
function multineedle_stripos($haystack, $needles, $offset=0) {
	$found=false;
	foreach($needles as $needle) {
		$ff=stripos($haystack, $needle, $offset);
		if(is_int ($ff )){
			$found=$ff;
		}
	}
	return $found;
}
/**
 * 获取搜索引擎来源关键词
 * @方法名：get_sekeyword
 * @param unknown_type $url
 * @author 	佚名
 * @2014-2-6上午10:58:57
 */
function get_sekeyword($url) {

	if (empty ( $url ))
		return '';
	$sk = array ();
	preg_match ( '@^(?:http://)?([^/]+)@i', $url, $matches );
	$domain = $matches [1];
	if ($domain != WEB_HOST . '/') {
		if (stripos  ( $domain, 'google.' ) !== false && preg_match ( '/q=([^&]*)/i', $url, $regs )) {
			$sk ['se'] = '谷歌';
			$sk ['id'] = 0;
			$sk ['w'] = urldecode ( $regs [1] ); // google taiwan
		} elseif (stripos  ( $domain, 'baidu.com' ) !== false && preg_match ( '/(wd|word)=([^&]*)/i', $url, $regs )) {
			$sk ['se'] = '百度';
			$sk ['id'] = 1;
			if (is_int ( multineedle_stripos  ($url, array('ie=utf-8','ie=utf8','bd_page_type=','issp=2','tn=baidulocal','tn=baiidu1','tn=pubgen_pg') ) ) ) {
				$sk ['w'] = urldecode ( $regs [2] );
				//dump(urldecode ( $regs [2] ));
			} else {
				//dump(urldecode ( $regs [2] ));
				$sk ['w'] = iconv ( "GBK", "UTF-8", urldecode ( $regs [2] ) );
			}
		} elseif (stripos  ( $domain, 'soso.com' ) !== false && preg_match ( '/(w|query)=([^&]*)/i', $url, $regs )) {
			$sk ['se'] = '腾讯soso';
			$sk ['id'] = 5;
			$sk ['w'] = urldecode ( $regs [2] );
			if (is_int ( stripos  ( $url, 'utf-8=ie' ) )) {
				$sk ['w'] = urldecode ( $regs [2] );
			} else {
				$sk ['w'] = iconv ( "GBK", "UTF-8", urldecode ( $regs [2] ) );
			}
		} elseif (stripos  ( $domain, 'sogou.com' ) !== false && preg_match ( '/query=([^&]*)/i', $url, $regs )) {
			$sk ['se'] = '搜狗';
			$sk ['id'] = 4;
			if (is_int (multineedle_stripos  ($url, array('ie=utf-8','ie=utf8','bd_page_type=','issp=2','duppid=1') ) )) {
				$sk ['w'] = urldecode ( $regs [1] );
				//dump(urldecode ( $regs [1]));
			} else {
				$sk ['w'] = iconv ( "GBK", "UTF-8", urldecode ( $regs [1] ) );
			}

		} elseif (stripos  ( $domain, 'yodao.com' ) !== false && preg_match ( '/q=([^&]*)/i', $url, $regs )) {
			$sk ['se'] = '有道';
			$sk ['id'] = 6;
			$sk ['w'] = urldecode ( $regs [1] );
		} elseif (stripos  ( $domain, 'yahoo.com' ) !== false && preg_match ( '/p=([^&]*)/i', $url, $regs )) {
			$sk ['se'] = '雅虎';
			$sk ['id'] = 8;
			$sk ['w'] = urldecode ( $regs [1] );
		} elseif (stripos  ( $domain, 'bing.com' ) !== false && preg_match ( '/q=([^&]*)/i', $url, $regs )) {
			$sk ['se'] = '微软bing';
			$sk ['id'] = 7;
			$sk ['w'] = urldecode ( $regs [1] );
		} elseif (stripos  ( $domain, 'so.com' ) !== false && preg_match ( '/q=([^&]*)/i', $url, $regs )) {
			$sk ['se'] = '306搜索';
			$sk ['id'] = 3;
			$sk ['w'] = urldecode ( $regs [1] );
		}
	}
	return $sk;
}
/**
 * 通过IP获取ip对应的区域
 * @方法名：getAddrArea
 * @param unknown_type $ip
 * @author 	佚名
 * @2014-2-6上午10:57:59
 */
function getAddrArea($ip) {
	// 导入IpLocation类
	Yii::import('ext.IpLocation');
	$Ip = new IpLocation (); // 实例化类
	$location = $Ip->getlocation ( $ip ); // 获取某个IP地址所在的位置
	if ($location) {
		return array ('addr' => $location ['country'], 'area' => $location ['area'] );
	}
	return false;
}

/*
 * 获取相应的起始时间戳
 */
function getXtime($type = 'n') {
	switch ($type) {
		case 'n' :
			//php获取今日开始时间戳和结束时间戳
			$beginToday = mktime ( 0, 0, 0, date ( 'm' ), date ( 'd' ), date ( 'Y' ) );
			$endToday = mktime ( 0, 0, 0, date ( 'm' ), date ( 'd' ) + 1, date ( 'Y' ) ) - 1;
			return array ($endToday, $beginToday );
			break;
		case 'ye' :
			//php获取昨日起始时间戳和结束时间戳
			$beginYesterday = mktime ( 0, 0, 0, date ( 'm' ), date ( 'd' ) - 1, date ( 'Y' ) );
			$endYesterday = mktime ( 0, 0, 0, date ( 'm' ), date ( 'd' ), date ( 'Y' ) ) - 1;
			return array ($endYesterday, $beginYesterday );
			break;
		case 'w' :
			//php获取上周起始时间戳和结束时间戳
			$beginLastweek = mktime ( 0, 0, 0, date ( 'm' ), date ( 'd' ) - date ( 'w' ) + 1, date ( 'Y' ) );
			$endLastweek = mktime ( 23, 59, 59, date ( 'm' ), date ( 'd' ) - date ( 'w' ) + 7, date ( 'Y' ) );
			return array ($endLastweek, $beginLastweek );
			break;
		case 'y' :
			//php获取本月起始时间戳和结束时间戳
			$beginThismonth = mktime ( 0, 0, 0, date ( 'm' ), 1, date ( 'Y' ) );
			$endThismonth = mktime ( 23, 59, 59, date ( 'm' ), date ( 't' ), date ( 'Y' ) );
			return array ($endThismonth, $beginThismonth );
			break;
	}

}
/**
 * 对二维数组按某个键值进行排序
 * @方法名：array_sort
 * @param unknown_type $arr
 * @param unknown_type $keys
 * @param unknown_type $type
 * @author 	佚名
 * @2013-7-31下午06:24:54
 */
function array_sort($arr, $keys, $type = 'asc') {
	$keysvalue = $new_array = array ();
	foreach ( $arr as $k => $v ) {
		$keysvalue [$k] = $v [$keys];
	}
	if ($type == 'asc') {
		asort ( $keysvalue );
	} else {
		arsort ( $keysvalue );
	}
	reset ( $keysvalue );
	foreach ( $keysvalue as $k => $v ) {
		$new_array [$k] = $arr [$k];
	}
	return $new_array;
}

/**
 * URL重组  调用方法  GetsUrl($gets,array('aa'=>'123','pp'=>456,'ee'=>''))
 * @方法名：GetsUrl
 * @param $_GET所有参数
 * @param 要添加或者去除的参数
 * @param 自定义url，重组参数自动加到URL后面，如不填写自己获取默认URL
 * @author 	佚名
 * @2014-2-10上午11:21:05
 */
function GetsUrl($gets, $arr, $act = "") {
	$newgets = array (); //新参数数组
	$rearr = array ();
	if (is_array ( $gets )) {
		foreach ( $gets as $keys => $vals ) {
			//原参数中不存在、非P参数、原参数值不为空、原参值数不为数组
			if (($keys != '_URL_') && ($keys != 'p') && (trim ( $vals ) != "") && (! is_array ( $vals )) && $vals != $arr) {
				$newgets [$keys] = $vals;
			}
		}
	} else {
		$exurl = "";
	}
	foreach ( $arr as $kk => $vv ) {
		if ($vv != "") {
			$newgets [$kk] = $vv;
		} else {
			unset ( $newgets [$vv] );
		}
	}
	foreach ( $newgets as $k => $v ) {
		$rearr [] = $k . "=" . urlencode ( $v );
	}
	return U ( $act, implode ( "&", $rearr ) );

}
/**
 * 时间区间查询获取表名
 * @方法名：getTableForTime
 * @param 开始时间 $stime
 * @param unknown_type $etime
 * @author 	佚名
 * @2014-2-10上午11:30:34
 */
function getTableForTime($stime, $etime) {
	$syear = date ( 'Y', $stime );
	$eyear = date ( 'Y', $etime );
	$smonth = date ( 'm', $stime );
	$emonth = date ( 'm', $etime );
	for($i = 0; $i < 4; $i ++) {
		$temp = $syear + $i;
		if ($temp > $eyear) {
			break;
		} else {
			$years [] = $temp;
			foreach ( array ('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12' ) as $ke => $v ) {
				//一年的时间段内
				if ($i == 0 && $temp == $eyear) {
					if ($v >= $smonth && $v <= $emonth) {
						$tables [] = $temp . $v;
					}
				} else {
					//多年时间段内
					if ($i == 0) {
						if ($v >= $smonth) {
							$tables [] = $temp . $v;
						}
					} else if ($temp == $eyear) {

						if ($v <= $emonth) {
							$tables [] = $temp . $v;
						}
					} else {
						$tables [] = $temp . $v;
					}
				}

			}
		}

	}
	//dump($tables);
	return $tables;
}


function kf_send_text_msg($ghid, $openid, $content){
	if(empty(trim($content))){
		return;
	}
    $url = "http://127.0.0.1:8888/wx/gh/msg/SendMsg.do?ghid=%s&openid=%s&body=%s&msgtype=text";
    $body =urlencode('{"touser":"'.$openid.'","msgtype":"text","text":{"content":"'.$content.'"}}');
    $rusut = HttpUtil::getPage(sprintf($url,$ghid,$openid,$body));
    Yii::log($rusut,'info');
}