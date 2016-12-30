<?php
defined('IN_WEB') or die('Include Error!');

class ModelPrize extends tables{
	//private $tbl_prizeconf = 'local_ddd.prize_conf';  //奖品配置表
    //重写活动奖品表
	function tblprizeconf(){
		if (ENV_VAL == 10){
			return parent::tblprizeconf();
		} else {
			return  'wxos_admin.common_prize';
		}
	}
	
	/**
	 * 获取活动所有有效奖品配置
	 * @param int $aid 活动ID
	 * @return array
	 */
	public function getAllByAid($aid){
		$sql = 'SELECT * FROM '.$this->tblprizeconf();
		$sql .= ' WHERE aid='. (int)$aid .' AND status=1';
		$ret = odb::db()->getAll($sql, MYSQL_ASSOC);
		if (!$ret) return $ret;
		$new = array();
		foreach($ret as $val){
			$new[$val['level']] = $val;
		}
		return $new;
	}
	
	/**
	 * 根据奖品等级获取一条奖品信息
	 * @param unknown_type $aid
	 * @param unknown_type $level
	 */
	public function getNoPrize($aid, $level=0){
		$sql = 'SELECT * FROM '.$this->tblprizeconf();
		$sql .= ' WHERE aid='. (int)$aid .' AND level='.$level;
		$sql .= ' LIMIT 1';
		$ret = odb::db()->getOne($sql, MYSQL_ASSOC);
		return $ret;
	}
	
	/** 
	 * 根据概率抽奖
	 * @param int $aid true-100%中奖  false-否
	 * @return array 奖品信息
	 */
	public function getPrize($aid, $flag=false){
		$all = $this->getAllByAid($aid);
		if(!$all) return 0;
		$rate = array();
		foreach($all as $level=>$val){
			$rate[$level] = floatval($val['rate']);
		}
		$level = $this->getRandPrizeItem($rate, $flag);
		return $all[$level];
	}

	/**
	 * 更新奖品数量、状态
	 * @param int $aid 活动ID
	 * @param array  $prize 奖品信息
	 * @return boolean
	 */
	public function updatePrize($aid, $prize){
		if(!$prize) return false;
		$level = intval($prize['level']);
		$setval = 'gain_num=gain_num+1';
		if ($prize['num'] && $prize['gain_num'] + 1 >= $prize['num']){//最后一个奖品
			$setval .= ',status=2';
		}
	    $sql = 'UPDATE '. $this->tblprizeconf() .' SET '. $setval;
	    $sql .= ' WHERE aid='. (int)$aid .' AND level='. $level;
	    $sql .= ' LIMIT 1';
	    return odb::db()->query($sql);
	} 
	
	/**
	 * 根据概率抽奖，随机获取奖项
	 * @param $proArr 概率数组，如$proArr=array(1=>'0.01',2=>'10',3=>'0.1');//0.01表示0.01%，10表示10%等
	 * @param $zoom 总概率精度,默认为100W
	 * @param $flag 是否100%中奖，false-否 true-是
	 * @return int 0-没中奖  1-奖项1，  2-奖项2等。
	 */
	public function getRandPrizeItem($proArr, $flag = false, $zoom = 1000000) {
		$result = 0;
		foreach ($proArr as $k=>$v){
			$proArr[$k] = trim($v) * $zoom/100;
		}
		$proSum = array_sum($proArr);
		if ($proSum == 0 ){
			return $result;
		}else if ($proSum < $zoom){
			if(!$flag){
				$proArr[$result] = $zoom-$proSum;
				$proSum = $zoom;
			}
		}
		foreach ($proArr as $key => $proCur) {
			$randNum = mt_rand(1, $proSum);
			if ($randNum <= $proCur) {
				$result = $key;
				break;
			} else {
				$proSum -= $proCur;
			}
		}
		unset ($proArr);
		return $result;
	}
	
}