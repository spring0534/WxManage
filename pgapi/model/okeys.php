<?php
defined( 'IN_WEB') or die( 'Include Error!');
class okeys {
	//敏感词词库
	static function mkSensitiveWords(){
		return 'MK_SENS_WORDS';   
	}
	//测试用例
	static function mkInfo($id){
		return 'MK_INFOBYID'.$id;
	}
	//one活动的所有问题
	static function mkQuestions($aid){
		return 'MK_QUESTIONS'.$aid;
	}
	//问题选项
	static function mkoneoption($id){
		return 'MK_ONE_OPTION'.$id;
	}
}
