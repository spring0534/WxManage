<?php

/**
* DefaultController.php
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2014-11-13
* 
*/
class DefaultController extends BaseController {
	public function actionIndex() {
		$this->renderPartial('index');
	}
}