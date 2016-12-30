<?php
defined('IN_WEB') or die('Include Error!');  //禁止直接访问

//常量定义
define('PATH_CFG', ROOT_PATH . '/pgapi/config/');     //配置文件路径
define('PATH_LIB', ROOT_PATH  . '/pgapi/lib/');        //类库路径
define('PATH_MOD', ROOT_PATH  . '/pgapi/model/');      //model路径       

//文件引入
include PATH_CFG . 'config.inc.php';         //配置初始化文件
include PATH_MOD . 'tables.php';             //数据库表文件  
include PATH_MOD . 'odb.php';                //数据库对象处理类  
include PATH_MOD . 'ocache.php';             //缓存处理类(memcached、redis)
include PATH_MOD . 'okeys.php';              //缓存键值管理类   
include PATH_MOD . 'oo.php';                 //oo对象处理类

//初始化
oo::setConfig($config_api);
oo::functions()->header();
oo::functions()->dp3p();

//去掉转义,,functions文件里的静态方法可以类似functions::uint调用
$_GET = oo::functions()->magic_quote( $_GET);  
$_POST = oo::functions()->magic_quote( $_POST);
$_COOKIE = oo::functions()->magic_quote( $_COOKIE);
