<?php
class functions{
	public $config;
    private $runStart;
	public function __construct( ){
		global $config;
		$this->config = $config;

		$this->runStart = $this->time( true);
	}

	/**
	 * 设置COOKIE
	 * @param unknown_type $name
	 * @param unknown_type $value
	 * @param unknown_type $time 过期时间,�?则关闭浏览器失效
	 */
	public function setCookie($name, $value, $time=0){
	    $expires = $time ? $this->time()+(int)$time : 0;
	    setcookie($name, $value, $expires, '/');
    }

    public function header(){
        header("Content-Type:text/html;charset=utf-8");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: X-TUNNEL-VERIFY");
    }

    public function nocache(){
        header("Pragma:no-cache");
        header("Cache-Type:no-cache, must-revalidate");
        header("Expires: -1");
    }

    public function dp3p(){
	   header("P3P:CP='ALL DSP CURa ADMa DEVa CONi OUT DELa IND PHY ONL PUR COM NAV DEM CNT STA PRE'");
    }

    public static function getip(){
    	if($_SERVER['REMOTE_ADDR']) {
    		$ip = $_SERVER['REMOTE_ADDR'];
    	}else if($_SERVER['HTTP_CLIENT_IP']){
    		$ip = $_SERVER['HTTP_CLIENT_IP'];
    	}else if($_SERVER['HTTP_X_FORWARDED_FOR']){
    		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    	}
    	return $ip;
    }
    
    /**
     * 返回浏览器信�?ver为版本号,nav为浏览器�?     */
    function getbrowser(){
    	$browsers = "mozilla msie gecko firefox ";
		$browsers.= "konqueror safari netscape navigator ";
		$browsers.= "opera mosaic lynx amaya omniweb";
		$browsers = split(" ", $browsers);
		$nua = strToLower( $_SERVER['HTTP_USER_AGENT']);
		$l = strlen($nua);
		for ($i=0; $i<count($browsers); $i++){
		  $browser = $browsers[$i];
		  $n = stristr($nua, $browser);
		  if(strlen($n)>0){
		   $arrInfo["ver"] = "";
		   $arrInfo["nav"] = $browser;
		   $j=strpos($nua, $arrInfo["nav"])+$n+strlen($arrInfo["nav"])+1;
		   for (; $j<=$l; $j++){
		     $s = substr ($nua, $j, 1);
		     if(is_numeric($arrInfo["ver"].$s) )
		     $arrInfo["ver"] .= $s;
		     else
		     break;
		   }
		  }
		}
		return $arrInfo;
    }


    public function magic_quote( $mixVar){
        if( get_magic_quotes_gpc()){
            if(is_array( $mixVar)){
                foreach ( $mixVar as $key => $value){
                    $temp[$key] = $this->magic_quote( $value);
                }
            }else{
                $temp = stripslashes( $mixVar);
            }
            return $temp;
        }else{
        	return $mixVar;
        }
    }
	
    /**
     * 数组分页
     * @param unknown_type $array
     * @param unknown_type $num 每页显示个数
     * @param unknown_type $now 当前页码,0开�?     * @param unknown_type $url 除去p=后的url
     * @return unknown
     */
    function apart_page( $array, $num, $now, $url){
		$count = count( $array);
		$now = min($now, floor($count/$num));
		if($count < $num){
			return array($array, '');
		}else{
			if($now!=0){
				$str .= '<a href="'.$url.'">|&lt;</a> <a href="'.$url.'?p='.($now-1).'">&lt;</a> ';
			}
			$str .= $num*$now+1;
			$str .= "~";
			$str .= ($num*$now)+$num;
			if($now!=floor($count/$num)){
				$str .= '<a href="'.$url.'?p='.($now+1).'">&gt;</a> <a href="'.$url.'?p='.floor($count/$num).'">&gt;|</a> ';
			}
			return array(array_slice($array, $num*$now, $num), $str);
		}
	}

	/**
     * �?arr的长和宽等比例缩小至$arrTo resize(array($array['width'],$array['height']), array(160,120))
     * @return unknown
     */
    function resize($arr, $arrTo ){
        $arr[0] = $arr[0]>10 ? $arr[0] : $arrTo[0];
        $arr[1] = $arr[1]>10 ? $arr[1] : $arrTo[1];
        $arrTo[0] = $arrTo[0]<=0 ? 160 : $arrTo[0];
        $arrTo[1] = $arrTo[1]<=0 ? 120 : $arrTo[1];
        $temp = $arr;
        //如果宽度超出
        if( $arr[0] > $arrTo[0]){
            $temp[0] = $arrTo[0];
            $temp[1] = (int)($temp[0]*$arr[1]/$arr[0]);
            if( $temp[1] > $arrTo[1]){
                $temp[1] = $arrTo[1];
                $temp[0] = (int)($arr[0]*$temp[1]/$arr[1]);
            }
        }
        //如果高度超出
        if( $arr[1] > $arrTo[1] ){
            $temp[1] = $arrTo[1];
            $temp[0] = (int)($arr[0]*$temp[1]/$arr[1]);
            if( $temp[0] > $arrTo[0]){
                $temp[0] = $arrTo[0];
                $temp[1] = (int)($temp[0]*$arr[1]/$arr[0]);
            }
        }
        return $temp;
    }

    /**
     * 返回UNIX时间�?     * @param boolen $float 是否精确到微�?     * @return int/float
     */
	public function time( $float=false){
		return $float ? microtime( true) : time();
	}

	public function runTime(){
	    return $this->time( true) - $this->runStart;
	}

	/**
	 * 获取用户头像地址
	 * @param unknown_type $mid 用户ID
	 * @param unknown_type $sid 站点ID
	 * @param unknown_type $typ 头像类型0小头像1中头像2大头像
	 * @param unknown_type $sitemid 用户在平台的ID
	 * @param unknown_type $ver 头像缓存版本,用于squid
	 * @param unknown_type $flag 标志是否直接取地址
	 */
    function getIcon($mid, $sid, $typ, $sitemid, $mstatus=0, $mimg=''){
    	switch ( $sid){
    		case 1: //校内
    		case 19: //雅虎
    			$icon = strlen($mimg)>=10 ? $mimg : $this->config['baseUrl'].'images/avatar.jpg';
    		break;
    		case 32: //百度
    			$icon = "http://himg.baidu.com/sys/portrait/item/" . ($mimg ? $mimg : '000000000000000000000000') . ".jpg";
    		break;
    		default:
    			$icon = "http://uchome.manyou.com/avatar/" . $sitemid . ($typ==0 ? "?thumb" : ($typ==1?"?small":"?normal"));
    		break;
    	}

    	return $icon;
    }

    /**
	 * @param int $uid
	 * @return string
	 */
	function get_avatar($mid, $size=0) {
		$size = in_array($size, array(0, 1, 2)) ? $size : 1;
		$mid = abs(intval($mid));
		$mid = sprintf("%09d", $mid);
		$dir1 = substr($mid, 0, 3);
		$dir2 = substr($mid, 3, 2);
		$dir3 = substr($mid, 5, 2);
		return $dir1.'/'.$dir2.'/'.$dir3.'/'.substr($mid, -2)."$size.jpg";
	}

	//获取个人主页
	function get_site($sid, $sitemid, $url=''){
		if( $sid > 99){ //漫游个人主页地址
			return 'http://uchome.manyou.com/profile/' . $sitemid;
		}elseif ( $sid==19){ //雅虎
            return 'http://guanxi.koubei.com/showprofile.php?uid=0' . $sitemid;
        }elseif ( $sid==1){ //校内
            return 'http://xiaonei.com/profile.do?id=' . $sitemid;
        }
		return 'http://uchome.manyou.com/profile/' . $sitemid;
	}

	/**
	 * 	制作flash参数
	 */
	function getFlashVars( $aLoad) {
		$aVars['mid'] = $aLoad['mid'];
		$aVars['mrole'] = $aLoad['mrole'];
		$aVars['mnick'] = $aLoad['mnick'];
		$aVars['sid'] = $aLoad['sid'];
		$aVars['sesskey'] = $aLoad['sesskey'];
		$aVars['flashUrl'] = $aLoad['flashUrl'];
		$aVars['gateway'] = $aLoad['gateway'];
		$aVars['flashver'] = json_encode($aLoad['flashver']);
		$aVars['security'] = json_encode($aLoad['security']);
		$aVars['urls'] = json_encode($aLoad['urls']);
		$aVars['xml'] = $aLoad['xml'];
		$aVars['time'] = time();
		foreach ( $aVars as $key => $value){
			$flashvars .= $key . '=' . urlencode($value) . '&';
		}

		return substr( $flashvars, 0, -1);
	}
	
	/**
	 * 获得随机值
	 * 刘紫华 
	 * @param int $lowerValue 	属性值下限
	 * @param int $highValue	属性值上限
	 * @return int
	 */
	public function getRandValue($lowerValue,$highValue){
		return rand((int)$lowerValue , max((int)$highValue,(int)$lowerValue));
	}
	
	/**
	 * 随机抽奖函数,输入概率,返回是否中奖
	 * 刘紫华	2010-6-8
	 * @param float 概率 如98.88%,输入的是98.88
	 * @param bool 
	 */
	public function doWin($rate){
		$rate = round($rate*100);
		return $rate >= rand(1,10000);
	}

	public function __destruct(){

	}
	
	public function writeErrorLog($msg){
		echo PATH_LOG;die;
	}
	
	public static function uint( $num){
		return max(0, (int)$num);
	}
	/**
	 * 安全性检测.调用escape存入的,一定要调unescape取出
	 */
	public static function escape( $string){
		return (PHP_VERSION > '5.3.0' ) ? addslashes( trim( $string)) : mysql_escape_string( trim( $string));
	}
	/** 
	 * 将字符串ID转换为对应的数字ID 不可逆  转换后不唯一
	 */
	public function midToNumber( $mid){
		return is_numeric($mid) ? $mid : abs( crc32($mid));
	}

	/**
	 * 获取操作系统所有网卡的IP(除127.0.0.1),优先返回外网IP
	 * 私有IP：A类  10.0.0.0-10.255.255.255
	 * B类  172.16.0.0-172.31.255.255
	 * C类  192.168.0.0-192.168.255.255
	 * 127这个网段是环回地址
	 * @return String 包括所有IP.如果有多个IP则以-分割
	 */
	public static function osip(){
		preg_match_all( '/inet\s+addr:([\d\.]+)/i', `/sbin/ifconfig -a|grep -v '127.0.0.1'`, $matches );

		return implode( '-', array_unique( (array) $matches[1] ) );
	}
	
	/**
	 * 生成GUID
	 * @param string $separate 分隔符
	 * @param string $prefix 前缀
	 * @return string
	 */
	public static function genGUID($separate='-', $prefix='') {
		$chars = md5(uniqid(mt_rand(), true));
		$aGuid[] = substr($chars, 0, 8);
		$aGuid[] = substr($chars, 8, 4);
		$aGuid[] = substr($chars, 12, 4);
		$aGuid[] = substr($chars, 16, 4);
		$aGuid[] = substr($chars, 20, 12);
		return $prefix.strtoupper(implode((string)$separate, $aGuid));
	}
	
	
	/**
	 * @author url 2014-06-16 
	 * @param  $url
	 * @return
	 */
	public static function curl($postUrl,$param){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $postUrl);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT,30);
		$ret = curl_exec($ch);
		return $ret;
	}
	
	
	
	/**
	 * 验证邮箱
	 * @param string mail 邮箱字符
	 * @return boolean
	 */
	
	public static function checkMail($email){
		if (empty($email) || !preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)){
			return false;
		}
		return true;
	}
	
	/**
	 * 验证手机号码
	 * @param string phone验证手机号码
	 * @return boolean
	 */
	public static function checkPhone($phone){
		if (empty($phone) || !preg_match("/^1[34578]\d{9}$/",$phone)){
			return false;
		}
		return true;
	}
		
	
	/**
 	  * 短信验证 
 	  * @param string 
 	  * @return mix 
	  */
	public static function checksms($api, array $params = array(), $timeout = 30){
		 $ch = curl_init();
		 curl_setopt( $ch, CURLOPT_URL, $api );
		 // 以返回的形式接收信息
		 curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		 // 设置为POST方式
		 curl_setopt( $ch, CURLOPT_POST, 1 );
		 curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params ) );
		 // 不验证https证书
		 curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		 curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		 curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
		 curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
			 'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
			 'Accept: application/json',
		 ) ); 
		 
		 // 发送数据
		 $response = curl_exec( $ch );
		 // 不要忘记释放资源
		 curl_close( $ch );
		 return json_decode($response, true);
	 }
	 
	 
	/**
 	  * 生成邮件随机验证码 
 	  * @author 陈国鑫
 	  * @date 2015-05-11
 	  * @return string
	  */
	 public static function getCode($length = 4, $mode = 0){           //获取随机验证码
	 	 switch ($mode){
	 	 	case '1':
	 	 		$str = '0123456789';                    //纯数字
	 	 		break;
	 	 	case '2':
	 	 		$str = 'abcdefghijklmnopqrstuvwsyz';    //纯小写字母
	 	 		break;
	 	 	case '3':
	 	 		$str = 'ABCDEFGHIJKLMNOPQRSTUVWSYZ';    //纯大写字母
	 	 		break;
	 	 	case '4':
	 	 		$str = 'ABCDEFGHIJKLMNOPQRSTUVWSYZabcdefghijklmnopqrstuvwsyz';    //纯字母
	 	 		break;
	 	 	case '5':
	 			$str = 'ABCDEFGHIJKLMNOPQRSTUVWSYZ0123456789';    //大写字母和数字
	 	 		break;
	 	 	case '6':
	 			$str = 'abcdefghijklmnopqrstuvwsyz0123456789';    //小写字母和数字
	 	 		break;
	 	 	default:
	 			$str = 'ABCDEFGHIJKLMNOPQRSTUVWSYZabcdefghijklmnopqrstuvwsyz0123456789';    //字母和数字
	 	 		break;
	 	 	
	 	 }
	 	 $code = '';
	 	 $len = strlen($str) - 1;
	 	 for($i = 0; $i < $length; $i++){
	 	     $num = mt_rand(0, $len);  
	 	     $code .= $str[$num];
	 	 }
	 	 return $code;
	 }
	 
     
	 	 
	/**
 	  * 生成邮件随机验证码 
 	  * @author 陈国鑫
 	  * @date 2015-05-11
 	  * @return string
	  */
	 
	 public static function sendMobileCode($curlPost, $url){   
	  	  $curl = curl_init();
		  curl_setopt($curl, CURLOPT_URL, $url);
		  curl_setopt($curl, CURLOPT_HEADER, false);
		  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt($curl, CURLOPT_NOBODY, true);
		  curl_setopt($curl, CURLOPT_POST, true);
		  curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
		  $return_str = curl_exec($curl);
		  curl_close($curl);
		  return self::xml_to_array($return_str);
	 }
	 
	
	 public static function xml_to_array($xml){
		$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
		if(preg_match_all($reg, $xml, $matches)){
			$count = count($matches[0]);
			for($i = 0; $i < $count; $i++){
			$subxml= $matches[2][$i];
			$key = $matches[1][$i];
				if(preg_match( $reg, $subxml )){
					$arr[$key] = self::xml_to_array( $subxml );
				}else{
					$arr[$key] = $subxml;
				}
			}
		}
		return $arr;
	}
	 
		 	 
	/**
 	  * get方式的curl 
 	  * @author 陈国鑫
 	  * @date 2015-06-4
 	  * @return array
	  */
	public static function curl_get($url, $param){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url.http_build_query($param));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$output = curl_exec($ch);
		return json_decode($output, true);
	}
	 
	
	/**
 	  * 敏感词过滤
 	  * @author 陈国鑫  
 	  * @date 2015-06-28
	  */
	 public static function filterContent($content, $target='***'){
	 	 $key = okeys::mkSensitiveWords();
	 	 $badwords = ocache::minfo (0)->get($key);
	 	
         if(!$badwords){
         	 $badwords = array();
         	 $filename = PATH_LIB.'filters/sensitive_words.txt';
       		 $file_handle = fopen($filename, "r");
       		 while (!feof($file_handle)) {
            	$line = trim(fgets($file_handle));
            	array_push($badwords, $line);
        	 }
             fclose($file_handle);
			 ocache::minfo (0)->set(okeys::mkSensitiveWords(), $badwords, 10 * 24 * 3600);        
         }
         $newcontent = str_replace(' ','',$content);
         $newcontent = str_replace('.','',$newcontent);
	 	 $newcontent = strtr($newcontent, array_combine($badwords, array_fill(0, count($badwords), $target)));
	 	 return stripos($content, $target) === false && stripos($newcontent, $target) !== false ? $newcontent : $content;
	 }
	 
	 /**
 	  * 上传头像到ypyun
 	  * @author 陈国鑫
 	  * @date 2015-07-08
	  */
	public static function upLoadCdn($localPath, $cdnPath){
	     require_once(PATH_LIB.'sdk/upyun/upyun.class.php');
	     $upyun = new UpYun(oo::$config['upyun']['bucket'], oo::$config['upyun']['user'], oo::$config['upyun']['password']);
	     try {
	     	 //$localPath = PATH_LIB.'sdk/upyun/examples/2.pic.jpg';
			 $fh = fopen($localPath, 'rb');
	    	 $rsp = $upyun->writeFile($cdnPath, $fh, True);        //上传图片，自动创建目录  
	    	 fclose($fh);
	    	 return true;
	     }catch(Exception $e) {
	         return array('code' => $e->getCode(), 'msg' => $e->getMessage());
         }
         
	 }
	 
	 
	/**
	 * 客户端http数据验证
	 * @return bool 是否合法      
	 */
	public static function getXTunnelVerify(){
		static $byteMap = array(
			0xA3,0x47,0x9D,0x98,0x5C,0x43,0xA5,0x6F,0xEF,0x5F,0x3E,0x0D,0x0B,0xEE,0xAA,0x32,
			0xF7,0xD1,0xFB,0x62,0xE9,0x46,0x1F,0x66,0x8A,0xE5,0xA0,0xC3,0xA9,0x7E,0x21,0x41,
			0x19,0xEC,0x05,0xCA,0x31,0xE1,0x8D,0x12,0x3F,0xA2,0xDC,0x40,0xCB,0xB7,0x34,0xC1,
			0x93,0x20,0x26,0x58,0x1D,0xFF,0x11,0x27,0xE8,0x04,0xD3,0xAE,0xA8,0xF3,0x3B,0x9A,
			0x4B,0x7F,0xD9,0xFC,0x37,0x0F,0x38,0x15,0xB0,0x7B,0xDA,0xB1,0x0A,0xD0,0x71,0x9E,
			0xE2,0x5A,0x35,0x17,0xCC,0x81,0x44,0x79,0xA7,0xDE,0xA4,0x83,0xDB,0x10,0x88,0xE3,
			0x3A,0x95,0xEB,0xCF,0x6E,0x63,0x74,0x6A,0x5B,0x16,0xB2,0xF8,0x9B,0xD6,0xE6,0x64,
			0x80,0x68,0x29,0xF9,0xBE,0x94,0x6C,0x5E,0x52,0x54,0x1C,0x51,0xEA,0x30,0x39,0x3D,
			0x2E,0x78,0x4E,0x57,0x69,0x82,0x01,0x77,0xAD,0x1E,0xE4,0x97,0xED,0xC5,0x6D,0x09,
			0x8E,0xF5,0xBF,0x18,0x22,0xB8,0x7A,0x55,0x25,0x2D,0x13,0xF4,0xC9,0x60,0x2A,0x42,
			0x48,0x9F,0x2C,0x89,0x2F,0x73,0x33,0xAC,0xE0,0xF0,0x59,0xBA,0x90,0x5D,0xC8,0xF1,
			0x76,0x3C,0x14,0x53,0x03,0x85,0x45,0xAB,0xD4,0x49,0xC4,0xC0,0xB4,0x7D,0x7C,0x67,
			0x8B,0x87,0x1A,0xD5,0x70,0x99,0x75,0x9C,0x96,0xF6,0x4D,0xB6,0xBB,0xD2,0x56,0x4A,
			0xA1,0xB9,0x1B,0x24,0xFD,0x84,0x61,0x50,0xB5,0x91,0xDF,0xFA,0x2B,0xB3,0x92,0x65,
			0x36,0x72,0xCD,0x4F,0xD7,0xFE,0x08,0x0C,0x28,0x0E,0xCE,0xAF,0xE7,0x8C,0xDD,0x4C,
			0xBC,0x6B,0x07,0x06,0xF2,0xC7,0xBD,0x00,0x86,0xC2,0xA6,0xC6,0x23,0xD8,0x02,0x8F
		);
		!PRODUCTION_SERVER && oo::logs()->debug(array(date('Y-m-d H:i:s'), $_SERVER['HTTP_X_TUNNEL_VERIFY']), 'httpVerify.txt');
		if( empty( $_SERVER['HTTP_X_TUNNEL_VERIFY'] ) ) return false;

		list($version, $seed, $data, $sig) = explode( '&', $_SERVER['HTTP_X_TUNNEL_VERIFY'] );
		if( empty( $version ) || empty( $seed ) || empty( $data ) || empty( $sig ) ) return false;

		$version = floatval( $version );
		$seed = $seed % 256;
		$data = base64_decode( $data );
		// $sig = base64_decode( $sig );
		$datalen = strlen( $data );

		for( $i = 0; $i < $datalen; $i++ ){
			$data{$i} = chr( $byteMap[ord( $data{$i} ) ^ $seed] );
		}

		// if( function_exists( 'mhash' ) ) $hash = mhash( MHASH_SHA1, $data, oo::$config['clientHttpVerifyConfig'] );
		// elseif( function_exists( 'hash_hmac' ) ) $hash = hash_hmac( "sha1", $data, oo::$config['clientHttpVerifyConfig'], true );
		// else return false;
		
		$hash = md5($data.oo::$config['clientHttpVerifyConfig']);
		!PRODUCTION_SERVER && oo::logs()->debug(strcmp($hash, $sig), 'httpVerify.txt');
		if( strcmp( $hash, $sig ) ) return false;

		$data = json_decode( $data, true );
		return $data;
	}
	
	/**
	 * 生成二维数组配置
	 * @param string $path
	 * @param array $ConfigData 二维数组
	 * @param string $primary  生成配置文件的主索引
	 * @param string $conTip 文件配置提示
	 * @return boolean
	 */
	public static function createConfigFile($path, $ConfigData, $primary = null, $conTip='配置文件'){
		if (is_null($primary)) $prefix = '';
		$str = "array(\n";
		foreach ($ConfigData as $v){
			if (!is_null($primary)){
				$prefix =  !is_numeric($v[$primary]) ? "'{$v[$primary]}' =>" : $v[$primary] .' =>';
			}
			$str .= "\t{$prefix} array (";
			foreach ($v as $key => $val){
				$key = !is_numeric($key) ? "'{$key}'" : $key;
				$val = !is_numeric($val) ? "'{$val}'" : $val;
				$str .= "$key => $val,";
			}
			$str .= "),\n";
		}
		unset($ConfigData);
		$str .= ');';
		$attach = "<?php\n/**\n * ". $conTip ."\n * @date ". date('Y-m-d H:i:s',time()) ."\n */\nreturn ";
		$size = file_put_contents($path, $attach.$str);
		//chmod($path, 0777);
		return $size ? true : false;
	}
	
	
}
