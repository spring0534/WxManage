<?php

/**
* DatacubeController.php
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2015-4-7
* 
*/
class DatacubeController extends BaseGhController{
	
	function init(){
		if(empty(gh()->appid)||empty(gh()->appsecret)){
			$this->error('要使用此功能，请在公众号配置appid和appsecret!');
		}
		

	}
	function actionUser(){
		
		
		$model=new StatWx('search');
		$model->unsetAttributes();
		if (isset($_GET['StatWx']))
			$model->attributes=$_GET['StatWx'];
		$w=array();
		if (!empty($_GET['StatWx']['day'][1])){
			$w['begin_date']=$_GET['StatWx']['day'][1];
			if(empty($_GET['StatWx']['day'][2])){
				$Days=round((strtotime("-1 day")-strtotime($w['begin_date']))/3600/24);
				if($Days<=7&&$Days>0){
					$w['end_date']=date('Y-m-d', strtotime("-1 day"));
				}else{
					$this->error('时间段设置有误，最大时间跨度只能是7天！');
				}
				
			}else{
				$Days=round((strtotime($_GET['StatWx']['day'][2])-strtotime($w['begin_date']))/3600/24);

				if($Days<=6&&$Days>=0){
					$w['end_date']=$_GET['StatWx']['day'][2];
				}else{
					$this->error('时间段设置有误，最大时间跨度只能是7天！');
				}
			}
		}
		if (!empty($_GET['StatWx']['day'][2])){
			$w['end_date']=$_GET['StatWx']['day'][2];
			$Days=round((time()-strtotime($_GET['StatWx']['day'][2]))/3600/24);
			if($Days<=1){
				$this->error('接口目前只允许查询小于'.date('Y-m-d',strtotime('-1 day')).'的数据！');
			}
			
			if(empty($_GET['StatWx']['day'][1])){
				$w['begin_date']=date('Y-m-d',strtotime($_GET['StatWx']['day'][1])-6*24*60*60);

			}else{
				$Days=round((strtotime($_GET['StatWx']['day'][2])-strtotime($_GET['StatWx']['day'][1]))/3600/24);
				if($Days<=6&&$Days>=0){
					$w['begin_date']=$_GET['StatWx']['day'][1];
				}else{
					$this->error('时间段设置有误，最大时间跨度只能是7天！');
				}
			}
			
		}
		if (empty($_GET['StatWx']['day'][1])&&empty($_GET['StatWx']['day'][2])){
			$w['begin_date']=date('Y-m-d', strtotime("-6 day"));
			$w['end_date']=date('Y-m-d', strtotime("-1 day"));
		}
		$field=$_GET['type']=='2' ? '2' : '1';
		yii::import ( 'ext.Weixinapi' );
		$account=array(
			'AppId'=>gh()->appid,
			'AppSecret'=>gh()->appsecret,
			'access_token'=>gh()->access_token,
			'expire'=>gh()->at_expires
		);
		$t = new Weixinapi ( $account );
		$t->debug = true;
		if($field==1){
			$re=$t->getusersummary($w);
		}else{
			$re=$t->getusercumulate($w);
		}
		
		if($re==false||!empty($re['errcode'])){
			$this->error($re['errmsg']?$re['errmsg']:$t->error,U('&user'),10000);
			
		}
		$this->render('user', array(
			'field'=>$field, 
			'data1'=>$re, 
			'model'=>$model, 
			'r1'=>$w['begin_date'], 
			'r2'=>$w['end_date']
		));
	}
	function actionImage(){
	}
	function actionMsg(){
	}
	function actionInterf(){
	}
}


