<?php
defined( 'IN_WEB') or die( 'Include Error!');
/**
 * model处理对象类
 * @author WangJun<546234549@qq.com>
 * @date 2015-11-04
 */
class oo{
	static $oo = array();     //全局对象数组
	static $config = array(); //全局配置数组
	
	//配置变量
	static function setConfig($config){
		self::$config = $config;
	}
	
	//奖品配置类
	static function prizeconf(){
		return self::createModelObj('prize');
	}
	
	//商品配置类
	static function goodsconf(){
		return self::createModelObj('goods');
	}
	
	//商品订单表
	static function goodsorder(){
		return self::createModelObj('order');
	}
	
	//用户活动类
	static function user(){
		return self::createModelObj('user');
	}
	
	//题库类
	static function question(){
		return self::createModelObj('question');
	}
	
	//活动统计类
	static function data(){
		return self::createModelObj('data');
	}
	
	static function heart(){
		return self::createModelObj('heart');
	}
	
	//返回模型操作对象
	private static function createModelObj($table){
		$class = 'Model'. ucfirst($table);
		$okeys = 'o'. $table;
		if( !is_object( self::$oo[$okeys] ) ){
			include_once PATH_MOD . 'model.'. $table .'.php';
			self::$oo[$okeys] = new $class();
		}
		return self::$oo[$okeys];
	}
	//smarty模版
	static function smarty(){
		if (!is_object( self::$oo['Smarty'])) {
			include_once PATH_LIB . 'smarty/Smarty.class.php';
			self::$oo['Smarty'] = new Smarty();
			self::$oo['Smarty']->config_dir = WWWROOT . "configure/"; //配置文件目录
			self::$oo['Smarty']->template_dir = WWWROOT . "templates/"; //模板目录
			self::$oo['Smarty']->compile_dir = WWWROOT . "templates_c/"; //模板编译目录
			self::$oo['Smarty']->cache_dir = WWWROOT . "cache/"; //缓存目录
			//self::$oo['Smarty']->cache_lifetime = 24*3600; //设置为1天
			//self::$oo['Smarty']->caching = true;  //是否进行缓存
			self::$oo['Smarty']->left_delimiter = '<*'; //开始符
			self::$oo['Smarty']->right_delimiter = '*>'; //结束符
			self::$oo['Smarty']->force_compile = PRODUCTION_SERVER ? true : true; //强制重编译,上线后改为false
			self::$oo['Smarty']->compile_check = PRODUCTION_SERVER ? true : true; //检查模板改动,上线后改为false
			self::$oo['Smarty']->debugging = false; //打开调试
			self::$oo['Smarty']->debugging_ctrl = 'URL'; //调试方法
			self::$oo['Smarty']->use_sub_dirs = false; //编译和缓存可以分子目录
		}
		return self::$oo['Smarty'];
	}
	//函数集合类
	static function functions(){
		if (!is_object( self::$oo['functions'])) {
			include_once PATH_LIB . 'class.functions.php';
			self::$oo['functions'] = new functions();
		}
		return self::$oo['functions'];
	}
	//ModelLogs 日志
	static function logs(){
		if( !is_object( self::$oo['logs'] ) ){
			include_once PATH_MOD . 'logs.php';
			self::$oo['logs'] = new ModelLogs();
		}
		return self::$oo['logs'];
	}
	
}