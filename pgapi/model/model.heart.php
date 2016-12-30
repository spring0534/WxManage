<?php
defined('IN_WEB') or die('Include Error!');

class ModelHeart extends tables{
	private $tbl_heart = 'wxos_app.paint_heart';
	
	/**
	 * 获取我画的心信息
	 */
	public function getOneByOpenid($aid, $openid){
		$sql = 'SELECT drawurl, startpoint, endpoint FROM '. $this->tbl_heart;
		$sql .= ' WHERE aid='. (int)$aid;
		$sql .= ' AND openid="'. $openid .'"';
		$sql .= ' AND drawurl !=""';
		$sql .= ' LIMIT 1' ;
		$row = odb::db()->getOne($sql, MYSQL_ASSOC);
		if (empty($row)) return array();
		return $row;
	}
	
	/**
	 * 获取朋友画的心信息
	 */
	public function getOneShare($aid, $openid, $fromwx){
		$sql = 'SELECT drawurl_friend FROM '. $this->tbl_heart;
		$sql .= ' WHERE aid='. (int)$aid;
		$sql .= ' AND openid="'. $openid .'"';
		$sql .= ' AND fromwxid="'. $fromwx .'"';
		$sql .= ' LIMIT 1' ;
		$row = odb::db()->getOne($sql, MYSQL_ASSOC);
		if (empty($row)) return array();
		return $row['drawurl_friend'];
	}
	
	 /**
	  * 获取与我匹配最大的朋友昵称和百分比
	  */
	public function getMaxInfo($aid, $openid){
		$sql = 'SELECT fromnickname, percent FROM '. $this->tbl_heart;
		$sql .= ' WHERE aid='. (int)$aid;
		$sql .= ' AND fromwxid="'. $openid .'"';
		$sql .= ' ORDER BY percent desc LIMIT 1';
		$data = odb::db()->getAll($sql,  MYSQL_ASSOC);
		if(empty($data)) return array('fromnickname'=>'', 'percent'=>0);
		return $data[0];
	}
	
	/**
	 * 判断我是否完成
	 */
	public function isComplete($aid, $openid){
		$info = $this->getOneByOpenid($aid, $openid);
		if(empty($info)) return 0;
		if(empty($info['drawurl']) || empty($info['startpoint']) || empty($info['endpoint'])) return 0;
		return 1;
	}
	
	/**
	 * 判断朋友是否完成
	 */
	public function isCompleteShare($aid, $openid, $fromwx){
		$info = $this->getOneShare($aid, $openid, $fromwx);
		if(empty($info)) return 0;
		return empty($info['drawurl']) ? 0 : 1;
	}
	
	/**
	 * 获取和我画心的所有用户
	 * @return array
	 */
	public function getRankList($aid, $openid, $condition=array()){
		$limit = isset($condition['limit']) ? (int)$condition['limit'] : 50;
		$sql = 'SELECT fromheadimg,fromnickname,percent FROM '. $this->tbl_heart;
		$sql .= ' WHERE aid='. (int)$aid;
		$sql .= ' AND fromwxid="'. $openid .'"';
		$sql .= ' ORDER BY id desc';
		if(isset($condition['pageno']) && isset($condition['pagesize'])){
			$sql .= ' LIMIT '. (($condition['pageno'] - 1) * $condition['pagesize']) .','. $condition['pagesize'];
		} else {
			$sql .= ' LIMIT '. $limit;
		}
		return odb::db()->getAll($sql,  MYSQL_ASSOC);
	}
	
	/**
	 * 自己完成画心
	 */
	public function completeOne($aid, $openid, $data){
		//$friendInfo = $this->getUserInfoOne($aid, $fromwxid);
		$data = array(
				'aid' =>  (int)	$aid,
				'openid'  =>  $openid,
				'drawurl' => $data['img'],
				'startpoint' => $data['spoint'],
				'endpoint' => $data['epoint'],
				//'fromwxid' =>  $from['openid'],
				//'fromheadimg' => $from['headimgurl'],
				//'fromnickname'=> $from['nickname'],
				'ip'=>functions::getip(),
				'ctm' => date('Y-m-d H:i:s',time()),
		);
		return odb::db()->insert($this->tbl_heart, $data);
	}
	
	/**
	 * 朋友完成画心
	 */
	public function completeOneShare($aid, $openid, $from){
		//$friendInfo = $this->getUserInfoOne($aid, $fromwxid);
		$data = array(
				'aid' =>  (int)	$aid,
				'openid'  =>  $openid,
				'fromwxid' =>  $from['fromwxid'],
				'fromheadimg' => $from['headimgurl'],
				'fromnickname'=> $from['nickname'],
				'drawurl_friend' => $from['img'],
				'percent'=> $from['percent'],
				'ip'=>functions::getip(),
				'ctm' => date('Y-m-d H:i:s',time()),
		);
		return odb::db()->insert($this->tbl_heart, $data);
	}
	
	/**
	 * 更新一条分享信息
	 */
	public function updateShareOne($aid, $openid, $fromwxid, $shareArr){
		return odb::db()->update($this->tbl_heart, $shareArr, array('aid'=>$aid, 'openid'=>$openid, 'fromwxid'=>$fromwxid));
	}
	
}