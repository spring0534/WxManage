<?php

function smarty_function_widget($params, $template) {
	if (empty($params['class'])) {
		throw new Exception('请指定Widget的类名称.');
	}
	$className=$params['class'];unset($params['class']);
	$widget=Yii::app()->getController()->createWidget($className,$params);
	$widget->run();

}