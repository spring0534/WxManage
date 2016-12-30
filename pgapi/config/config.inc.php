<?php
defined('IN_WEB') or die('Include Error!');

$config_api = array(); //配置数组

/*=============================================================================
 *  数据库配置：(数据库IP或地址, 用户名, 密码, 数据库名)   
 *=============================================================================*/           
if(ENV_VAL == 10) //本地环境
	$config_api['dbmaster'] = array(array('127.0.0.1','root', '123456', ''));
else 
// 	$config_api['dbmaster'] = array(array('rdswc74ywo4iblzw2j5r0o.mysql.rds.aliyuncs.com','wxos_remote', 'wxos_remote888', ''));
	$config_api['dbmaster'] = array(array('127.0.0.1','uu_weixin', 'uu_weixin123', ''));
	

/*==============================================================================
 *  memcache配置：支持多个分布式(地址,端口,权重)
 *  $ memcached -d -p 11210  如果是一台服务器，需要在命令行运行左边端口配置命令
 *==============================================================================*/
$config_api['memcache']['base'][] = array(array('127.0.0.1', '11211', 100));
	
$config_api['memcache']['minfo'][][] = array(array('127.0.0.1', 11210, 100));
$config_api['memcache']['minfo'][][] = array(array('127.0.0.1', 11211, 100));
$config_api['memcache']['minfo'][][] = array(array('127.0.0.1', 11212, 100));
$config_api['memcache']['minfo'][][] = array(array('127.0.0.1', 11213, 100));
$config_api['memcache']['minfo'][][] = array(array('127.0.0.1', 11214, 100));

$config_api['memcache']['maccount'][][] = array(array('127.0.0.1', 11210, 100));
$config_api['memcache']['maccount'][][] = array(array('127.0.0.1', 11211, 100));
$config_api['memcache']['maccount'][][] = array(array('127.0.0.1', 11212, 100));
$config_api['memcache']['maccount'][][] = array(array('127.0.0.1', 11213, 100));
$config_api['memcache']['maccount'][][] = array(array('127.0.0.1', 11214, 100));

$config_api['memcache']['mfriend'][][] = array(array('127.0.0.1', 11210, 100));
$config_api['memcache']['mfriend'][][] = array(array('127.0.0.1', 11211, 100));
$config_api['memcache']['mfriend'][][] = array(array('127.0.0.1', 11212, 100));
$config_api['memcache']['mfriend'][][] = array(array('127.0.0.1', 11213, 100));
$config_api['memcache']['mfriend'][][] = array(array('127.0.0.1', 11214, 100));

$config_api['memcache']['mlimit'][][] = array(array('127.0.0.1', 11215, 100));
/*==============================================================================
 *  redis配置：(地址,端口)
 *  $ redis-server & 命令行输入左边命令后台运行redis
 *==============================================================================*/
$config_api['redis'] = array('127.0.0.1', '6379');