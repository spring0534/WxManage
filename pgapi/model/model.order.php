<?php
defined('IN_WEB') or die('Include Error!');

class ModelOrder extends tables{
	
	/**
	 * 创建订单
	 * @param array $order 订单信息
	 * @return int  最后插入的订单ID
	 */
	public function createOrder($order){
		if(!$order) return false;
		
		return odb::db()->insert($this->tblgoodsorder(), $order);
	}
	/**
	 * 根据用户获取订单信息
	 * @param int $aid 活动ID
	 * @param string $openid  用户openid
	 *  @param int $goodsid 商品ID
	 * @return array
	 */
	public function getOrderByUser($aid, $openid, $goodsid = false){
		$sql = 'SELECT * FROM '.$this->tblgoodsorder();
		$sql .= ' WHERE aid='. (int)$aid.' AND openid="'. $openid .'"';
		if($goodsid){
			$sql .=  ' AND goodsid='. (int)$goodsid;
			$ret = odb::db()->getOne($sql, MYSQL_ASSOC);
		} else{
			$ret = odb::db()->getAll($sql, MYSQL_ASSOC);
		}
		
		
		return $ret;
	}
	
	/**
	 * 根据订单号获取订单信息
	 * @param int $id 订单号
	 */
	public function getOrderById($id){
		$sql = 'SELECT * FROM '.$this->tblgoodsorder();
		$sql .= ' WHERE id='. (int)$id;
		$ret = odb::db()->getOne($sql, MYSQL_ASSOC);
		
		return $ret;
	}
	
	/**
	 * 更新订单信息
	 * @param array  $data 订单数据
	 * @param array  $where 更新条件
	 * @return boolean
	 */
	public function updateOrder($data, $where){
		if(!$data || !$where) return false;
	
	    return odb::db()->update($this->tblgoodsorder(), $data, $where);
	} 

}