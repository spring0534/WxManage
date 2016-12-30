<?php
defined('IN_WEB') or die('Include Error!');

class ModelLog extends tables{
	/**
	 * 创建日志表
	 * @param int $time  时间戳，单位为秒
	 * @date 2015-11-04
	 */
	public function createLogTable($time){
    	 $time = $time ? intval($time) : time();
    	 $basetbl = substr($this->tbllog(), 0, -9);
    	 $logtbl = $basetbl.'_'.date('Ymd', $time);
		 $sql = 'create table if not exists '.$logtbl.' like '.$basetbl;
    	 odb::db()->query($sql);
    	 
    } 
    
    
       
	
	
	
	

}