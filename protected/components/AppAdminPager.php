<?php

class AppAdminPager extends CLinkPager{


	public $params;
	public $pageVar='page';
	public $route='';
	public function createPageUrl($page){
		$params=$this->params===null ? $_GET : $this->params;
		if ($page>0){
			$params[$this->pageVar]=$page+1;
		}else{
			unset($params[$this->pageVar]);
		}
		unset($params['_akey']);
		unset($params['scr']);
		unset($params['interface']);
		$action=$params['_action'] ? $params['_action'] : 'index';
		unset($params['admin']);
		$this->route='/appAdmin/'.$_GET['_akey'].'/'.$params['_controller'].'/'.$action;
		unset($params['_controller']);
		unset($params['_action']);
		return Yii::app()->createUrl($this->route, $params, '&');
		// return AAU('/page/'.$page);
	}

}