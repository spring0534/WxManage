<?php

/**
* BaseController.php
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2014-11-18
* 
*/
class BaseGhController extends BaseController{
	function init() {
		parent::init();
		if (! gh()->ghid) {
			$this->error('请先绑定公众号!');
		}
		
	}
	
}