<?php
defined('IN_WEB') or die('Include Error!');
class odb{
	private static $odb = array();
	
	/**
	 * @return DB
	 */
	public static function db(){
		if( ! is_object( self::$odb['dbmaster'])){
		 	include_once PATH_LIB . 'class.db.php';
		 	self::$odb['dbmaster'] = DB::getInstance(oo::$config['dbmaster']); 	
		}
		return self::$odb['dbmaster'];
	}

	/**
	 * @return SDB  数据统计DB   
	 */
// 	public static function sdb(){
// 		if( ! is_object( self::$odb['sdbmaster'])){
// 		 	include_once PATH_LIB . 'class.db.php';
// 		 	self::$odb['sdbmaster'] = new DB( oo::$config['sdbmaster']);		 	
// 		}
// 		return self::$odb['sdbmaster'];
// 	}



}