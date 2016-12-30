<?php
defined( 'IN_WEB') or die( 'Include Error!');
class ocache {
	static $ocache = array();
	
	public static function cache(){
		if (!is_object( self::$ocache['cache'])) {
			include_once PATH_LIB . 'class.mucache.php';
			self::$ocache['cache'] = new mucache( oo::$config['memcache']['base']);
		}
		return self::$ocache['cache'];
	}

	/**
	 * @return mucache
	 */
	public static function memcache( $uid, $cname = 'minfo'){
		$len = count(oo::$config['memcache'][$cname]);
		if(!$len) return self::cache();
		$index = intval( $uid % $len);
		$config = oo::$config['memcache'][$cname][$index];
		if( ! is_object( self::$ocache[$cname.$index])){
			include_once PATH_LIB . 'class.mucache.php';
			if (!$config) {
				return self::cache();
			}
			self::$ocache[$cname.$index] = new mucache( $config);
		}
		return self::$ocache[$cname.$index];
	}
	
	public static function redis(){
		if(!is_object(self::$ocache['redis'])){
			include_once PATH_LIB . 'class.muredis.php';
			self::$ocache['redis'] = new muredis( oo::$config['redis']);			
		}	
		return self::$ocache['redis'];
	}

	public static function minfo($uid){
		return self::memcache($uid, "minfo");
	}

	public static function mlimit(){
		return self::memcache(0, "mlimit");
	}

	public static function mactivity($uid){
		return self::memcache($uid, "mactivity");
	}
	
	static function destroy(){
		self::$ocache = array();
	}

	public static function kvs(){

	}
}
