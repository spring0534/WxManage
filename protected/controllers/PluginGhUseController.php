<?php

/**已开通微应用
* PluginGhUseController.php
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2014-12-9
* 
*/
class PluginGhUseController extends BaseGhController{
	public function actionIndex(){
		$this->render('index');
	}
	public function actionOpenlist(){
		$model=new PluginGhUse('openlist');
		$model->unsetAttributes(); // clear any default values
		$m=$model->openlist();
		$data=$m->data;
		$pages['pages']=$m->getPagination();
		$this->render('openlist', array(
			'list'=>$data, 
			'pages'=>$pages
		));
	}
}