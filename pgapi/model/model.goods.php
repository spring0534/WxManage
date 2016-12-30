<?php
defined('IN_WEB') or die('Include Error!');

class ModelGoods extends tables{

	/**
	 * 获取活动所有上架商品配置
	 * @param int $aid 活动ID
	 * @return array
	 */
	public function getGoodsByAid($aid){
		$sql = 'SELECT * FROM '.$this->tblgoodsconf();
		$sql .= ' WHERE aid='. (int)$aid;
		$ret = odb::db()->getAll($sql, MYSQL_ASSOC);
		if (!$ret) return $ret;
		$new = array();
		foreach($ret as $val){
			$new[$val['id']] = $val;
		}
		return $new;
	}
	
	/**
	 * 根据商品ID获取一条商品信息
	 * @param int $goodsid 商品ID
	 */
	public function getGoodsByGid($goodsid){
		$sql = 'SELECT * FROM '.$this->tblgoodsconf();
		$sql .= ' WHERE id='.$goodsid;
		$sql .= ' LIMIT 1';
		$ret = odb::db()->getOne($sql, MYSQL_ASSOC);
		return $ret;
	}
	
	/**
	 * 更新商品数量、状态
	 * @param array  $ginfo 商品信息
	 * @return boolean
	 */
	public function updateGoods($ginfo){
		if(!$ginfo) return false;
		$id = intval($ginfo['id']);
		$setval = 'sale_num=sale_num+1';
		if ($ginfo['num'] && $ginfo['sale_num'] + 1 >= $ginfo['num']){//最后一个商品
			$setval .= ',status=2';
		}
	    $sql = 'UPDATE '. $this->tblgoodsconf() .' SET '. $setval;
	    $sql .= ' WHERE id='.$id;
	    $sql .= ' LIMIT 1';
	    
	    return odb::db()->query($sql);
	} 

}