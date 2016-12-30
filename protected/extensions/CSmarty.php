<?php
/**
 * 扩展增加smarty模板
 *
 */
Yii::$enableIncludePath=false;
require_once (Yii::getPathOfAlias('ext.smarty') . DIRECTORY_SEPARATOR . 'Smarty.class.php');
class CSmarty extends Smarty {
	function __construct() {
		parent::__construct();
		$tpl=Yii::app ()->themeManager->getBasePath().'/'.(yii::app ()->theme?yii::app ()->theme->getName().'/':'').'views/';
		$this->setTemplateDir($tpl);
		$cacheDir=str_replace('microapp', 'microapp_cache', Yii::app ()->themeManager->getBasePath().'/'.(yii::app ()->theme?yii::app ()->theme->getName().'/':''));
		$this->setCompileDir($cacheDir. 'template_c');//设置编译目录路径，不设默认"templates_c"
		$this->setCacheDir($cacheDir . 'cache_c');//设置缓存目录路径，不设默认"cache"
		$this -> caching = true;
		if($_SERVER['HTTP_HOST']==DEBUG_URL){
			$this->debugging=true;
		}
		$this-> left_delimiter = '{{';
		$this -> right_delimiter = '}}';
		$this-> cache_lifetime = 0;
	}
	function init(){

	}
}
