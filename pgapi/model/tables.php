<?php
defined('IN_WEB') or die('Include Error!');
/**
 * 数据库表的集合类
 * @author WangJun<546234549@qq.com>
 * @date 2015-11-04
 */
class tables{
	private $indexdb = '';
	
	function tables(){
		$this->indexdb = (ENV_VAL == 10) ? 'wxos_app' : 'wxos_app';
	}
	//奖品配置表
	function tblprizeconf(){
		return $this->indexdb.'.prize_conf';
	}
	
	//问题表
	function tblquestions(){
		return $this->indexdb.'.questions';
	}
	
	//问题选项表
	function tbloptions(){
		return $this->indexdb.'.question_options';
	}
	
	//用户信息表
	function tbluserinfo(){
		return $this->indexdb.'.user_info';
	}
	
	//用户活动表
	function tbluseractivity(){
		return $this->indexdb.'.user_activity';
	}
	
	//用户奖品表
	function tbluserprize(){
		return $this->indexdb.'.user_prize';
	}
	
	//用户日志表
	function tbluserlog($aid){
		return $this->indexdb .'.userlog_'. $aid;
	}
	
	//分享点击记录表
	function tblshareopen(){
		return $this->indexdb .'.share_open';
	}
	
    //商品表
	function tblgoodsconf(){
		return $this->indexdb .'.goods_conf';
	}
	
	//商品订单表
	function tblgoodsorder(){
		return $this->indexdb .'.goods_order';
	}
	
	//派发红包表
	function tblredpacktask(){
		return $this->indexdb .'.redpack_task';
	}
	
}