<?php
defined('IN_WEB') or die('Include Error!');

class ModelUser extends tables{
// 	private $tbl_userinfo = 'local_ddd.user_info';         //用户信息表
// 	private $tbl_useractivity = 'local_ddd.user_activity'; //用户活动表
// 	private $tbl_userprize = 'local_ddd.user_prize';       //用户奖品表
// 	private $tbl_userlog = 'local_ddd.userlog';           //用户日志表
// 	private $tbl_shareopen = 'local_ddd.share_open';       //分享点击记录表
	
	//===============================用户信息===============================
	/**
	 * 获取用户信息
	 * @param int $aid 活动ID
	 * @param string $openid  用户openid
	 * @return array
	 */
	public function getUserInfoOne($aid, $openid){
		$sql = 'SELECT * FROM '. $this->tbluserinfo();
		$sql .= ' WHERE aid='. (int)$aid .' AND openid="'. $openid .'"';
		$sql .= ' LIMIT 1';
		$row = odb::db()->getOne($sql, MYSQL_ASSOC); 
		if (empty($row)) return array();
		return $row;
	}
	
	/**
	 * 检查用户信息是否存在
	 * @param int $aid 活动ID
	 * @param string $ainfo 检查的字段信息
	 * @return boolean true-存在  false-不存在
	 */
	public function exitsUserInfo($aid, $ainfo){
		$sql = 'SELECT * FROM '. $this->tbluserinfo();
		$sql .= ' WHERE aid='. (int)$aid;
		if(isset( $ainfo['username'])) $sql .=  ' AND username="'. $ainfo['username'] .'"';
		if(isset( $ainfo['phone'])) $sql .=  ' AND phone="'. $ainfo['phone'] .'"';
		if(isset( $ainfo['address'])) $sql .=  ' AND address="'. $ainfo['address'] .'"';
		$sql .= ' LIMIT 1';
		$row = odb::db()->getOne($sql, MYSQL_ASSOC);
		return empty($row) ? false : true;
	}
	
	
	
	/**
	 * 判断用户是否注册：1-已注册 0-未注册
	 * @return number
	 */
	public function checkUserInfo($aid, $openid, $onlyphone=false, $address=false){
		$info = $this->getUserInfoOne($aid, $openid);
		if($onlyphone) return (!empty($info['phone'])) ? 1 : 0;
		if($address) return (!empty($info['username']) && !empty($info['address'])) ? 1 : 0;
		return (!empty($info['username']) && !empty($info['phone'])) ? 1 : 0;
		
	}
	
	/**
	 * 插入用户信息
	 * @return number
	 */
	public function insertUserInfo($aid, $openid, $dataArr){
		$base = array(
				'aid' => (int)$aid,
				'openid' =>  $openid,
				'score' => 0,
				'enable' => 1,
				'ip' => functions::getip(),
				'ctm' => date('Y-m-d H:i:s',time()),
		);
		$insertData = array_merge($base, $dataArr);
		return odb::db()->insert($this->tbluserinfo(), $insertData);
	}
	
	/**
	 * 获取用户排行榜
	 * @return array
	 */
	public function getRankList($aid, $condition=array()){
		$limit = isset($condition['limit']) ? (int)$condition['limit'] : 50;
		$sql = 'SELECT nickname,headimgurl,score FROM '. $this->tbluserinfo();
		$sql .= ' WHERE aid='. (int)$aid .' AND enable=1';
		$sql .= ' ORDER BY score desc,ctm asc';
		if(isset($condition['pageno']) && isset($condition['pagesize'])){
			$sql .= ' LIMIT '. (($condition['pageno'] - 1) * $condition['pagesize']) .','. $condition['pagesize'];
		} else {
			$sql .= ' LIMIT '. $limit;
		}
		return odb::db()->getAll($sql,  MYSQL_ASSOC);
	}
	
	/**
	 * 获取用户总记录数
	 * @return array
	 */
	public function getRankCount($aid){
		$sql = 'SELECT count(*) AS total FROM '. $this->tbluserinfo();
		$sql .= ' WHERE aid='. (int)$aid .' AND enable=1';
		$ret = odb::db()->getOne($sql,  MYSQL_ASSOC);
		
		return $ret['total'];
	}
	
	//呼啦圈排行榜专属方法
	public function getRankListSpecial($aid, $condition=array()){
		$limit = isset($condition['limit']) ? (int)$condition['limit'] : 50;
		$sql = 'SELECT u.nickname,u.headimgurl,u.score FROM '. $this->tbluserinfo();
		$sql .= ' AS u INNER JOIN '. $this->tbluseractivity() .' AS a ON u.aid=a.aid AND u.openid=a.openid';
		$sql .= ' WHERE u.aid='. (int)$aid .' AND u.enable=1 AND a.share_clicks>=10';
		$sql .= ' ORDER BY u.score desc,a.share_clicks desc,u.ctm asc';
		if(isset($condition['pageno']) && isset($condition['pagesize'])){
			$sql .= ' LIMIT '. (($condition['pageno'] - 1) * $condition['pagesize']) .','. $condition['pagesize'];
		} else {
			$sql .= ' LIMIT '. $limit;
		}
		return odb::db()->getAll($sql,  MYSQL_ASSOC);
	}
	
	/**
	 * 获取排行榜列表
	 * @return array
	 */
	public function getUserRank($aid, $openid){
		$cmmonsql = 'SELECT count(*) AS t FROM '. $this->tbluserinfo() ; 
		$sql = $cmmonsql .' WHERE aid='. (int)$aid;
		$tol = odb::db()->getOne($sql, MYSQL_ASSOC);
	
		$ret = $this->getUserInfoOne($aid, $openid); //我的活动信息
		$sql = $cmmonsql .' WHERE aid='. (int)$aid .' AND score<'. (int)$ret['score'];
		$lts =  odb::db()->getOne($sql, MYSQL_ASSOC);
		
		$sql =  $cmmonsql .' WHERE aid='. (int)$aid .' AND score='. (int)$ret['score'] .' AND ctm>"'. $ret['ctm'] .'"';
        $eq = odb::db()->getOne($sql, MYSQL_ASSOC);
		return array(
				'rank' => (int)($tol['t'] - $lts['t'] -$eq['t']),
				'nickname' =>$ret['nickname'],
				'headimgurl' =>$ret['headimgurl'],
				'score' => $ret['score'],
		);
	}
	
	/**
	 * 获取参与的用户总数
	 * @param int $aid  活动id
	 * @return int
	 */
	public function getUserCount($aid){
		$sql = 'SELECT count(*) AS t FROM '. $this->tbluserinfo() ; 
		$sql .= ' WHERE aid='. (int)$aid;
		$tol = odb::db()->getOne($sql, MYSQL_ASSOC);
		return $tol['t'];
	}
	
	/**
	 * 更新用户信息
	 * @return number
	 */
	public function updateUserInfo($aid, $openid, $dataArr){
		$common = array(
			'ip'=>functions::getip(),
		);
		$updateData = array_merge($common, $dataArr);
		return odb::db()->update($this->tbluserinfo(), $updateData, array('aid'=>$aid, 'openid'=>$openid));
	}
	
	//===============================用户活动===============================
	/**
	 * 获取用户活动信息
	 * @param int $aid 活动ID
	 * @param string $openid  用户openid
	 * @return array
	 */
	public function getUserActivityOne($aid, $openid){
		$sql = 'SELECT * FROM '. $this->tbluseractivity();
		$sql .= ' WHERE aid='. (int)$aid .' AND openid="'. $openid .'"';
		$sql .= ' LIMIT 1';
		$row = odb::db()->getOne($sql, MYSQL_ASSOC);
		if (empty($row)) return array();
		return $row;
	}
	
	/**
	 * 插入用户活动数据
	 * @return number
	 */
	public function insertUserData($aid, $openid, $dataArr){
		$base = array(
			'aid'  => $aid,
			'openid' => $openid,
			'firstshared' =>0,
			'atten_past'  =>0,
			'atten_now'	  =>0,
			'times_firstshare' =>0,
			'times_attention'  =>0,
			'times_share'      =>0,
			'times_fixeduse'   =>0,
			'times_other' =>0,
			'score'	      =>0,
			'score_tmp'   =>0,
			'share_clicks'=>0,
			'help_clicks' =>0,
			'prizetimes'  =>0,
			'times_history'     =>0,
			'prizetimes_history'=>0,
			'helpclicks_history'=>0,
			'attentimes_get'    =>0,
			'shareclicks_get'   =>0,
			'popshow'   => 0,
			'ctm' => date('Y-m-d H:i:s',time()),
		);
		$insertData = array_merge($base, $dataArr);
		return odb::db()->insert($this->tbluseractivity(), $insertData);
	}
	
	/**
	 * 更新用户活动数据
	 * @return Database_Query_Builder_Update
	 */
	public function updateUserData($aid, $openid, $dataArr){
		return odb::db()->update($this->tbluseractivity(), $dataArr, array('aid'=>$aid, 'openid'=>$openid));
	} 
	
	/**
	 * 用户游戏次数减1
	 * @return Database_Query_Builder_Update
	 */
	public function reduceOneTimes($fixedtimes, $data){
		$activity = count($data) == 2 ? $this->getUserActivityOne($data['aid'], $data['openid']) : $data;
		$arr = array();
		switch(1){
			case $activity['times_fixeduse']<$fixedtimes :
				$arr['times_fixeduse'] = 'times_fixeduse+1';break;
			case $activity['times_firstshare']>0 :
				$arr['times_firstshare'] = 'times_firstshare-1';break;
			case $activity['times_attention']>0 :
				$arr['times_attention'] = 'times_attention-1';break;
			case $activity['times_share']>0 :
				$arr['times_share'] = 'times_share-1';break;
			case $activity['times_other']>0 :
				$arr['times_other'] = 'times_other-1';break;
			default : break;
		}
		return $this->updateUserData($data['aid'], $data['openid'], $arr);
	}
	
	/**
	 * 获取用户的游戏次数
	 * @return Database_Query_Builder_Update
	 */
	public function getActivityTimes($fixedtimes, $data){
		$activity = count($data) == 2 ? $this->getUserActivityOne($data['aid'], $data['openid']) : $data;
		if(empty($activity)) return 0;
		$times = $fixedtimes-$activity['times_fixeduse'];
		$times += $activity['times_firstshare'];
		$times += $activity['times_attention'];
		$times += $activity['times_share'];
		$times += $activity['times_other'];
		return $times;
	}
	
	/**
	 * 重置用户每天的游戏次数、是否第一次分享等数据
	 * @return Database_Query_Builder_Update
	 */
	public function resetActivityTimes($aid, $openid, $arr){
		$row = $this->getUserActivityOne($aid, $openid);
		$currenttime = date('Y-m-d',time());     
		$lasttime = substr($row['utm'],0,10);  //用户上次活动时间
		$diff = strtotime($lasttime) + 24*3600 - strtotime($currenttime);  
		if($diff<=0){ //一天以后
			return $this->updateUserData($aid, $openid, $arr);
		} 
	}
	
	//===============================用户奖品===============================
	
	/**
	 * 获取用户奖品列表
	 * @return array
	 */
	public function getPrizeList($aid, $openid){
		$sql = 'SELECT * FROM '. $this->tbluserprize();
		$sql .= ' WHERE aid='. (int)$aid .' AND openid="'. $openid .'"' ;
		$data = odb::db()->getAll($sql, MYSQL_ASSOC);
		return $data;
	}
	
	/**
	 * 插入一条用户奖品
	 * @return Database_Query_Builder_Insert
	 */
	public function insertUserPrize($aid, $openid, $prizeInfo){
		$base = array(
			'aid' => (int)$aid,
			'openid' => $openid,
			'level' => $prizeInfo['level'],
			'name' => $prizeInfo['name'],
			'pic' => $prizeInfo['pic'],
			'status' => 0,
			'ctm' => date('Y-m-d H:i:s',time()),	
		);
		//$insertData = array_merge($base, $prizeInfo);
		return odb::db()->insert($this->tbluserprize(), $base);
	}
	
	/**
	 * 更新奖品状态信息
	 * @return Database_Query_Builder_Update
	 */
	public function updateUserPrize($aid, $openid, $prizeArr){
		return odb::db()->update($this->tbluserprize(), $prizeArr, array('aid'=>$aid, 'openid'=>$openid));
	}
	
	//===============================用户分享点击信息===============================
	/**
	 * 获取一条分享点击信息
	 * @return array
	 */
	public function getShareOne($aid, $openid, $fromwxid, $goodsid=0){
		$sql = 'SELECT * FROM '. $this->tblshareopen();
		$sql .= ' WHERE aid='. (int)$aid .' AND openid="'. $openid .'" AND fromwxid="'. $fromwxid .'"' ;
		if($goodsid) $sql .= ' AND goodsid='. (int)$goodsid;
		$sql .= ' LIMIT 1';
		$share = odb::db()->getOne($sql, MYSQL_ASSOC);
		return $share;
	}
	
	/**
	 * 获取分享点击总数
	 * @return array
	 */
	public function getShareCount($aid, $openid, $goodsid=0){
		$sql = 'SELECT count(*) as total  FROM '. $this->tblshareopen();
		$sql .= ' WHERE aid='. (int)$aid .' AND fromwxid="'. $openid .'"' ;
		if($goodsid) $sql .= ' AND goodsid='. (int)$goodsid;
		$ret = odb::db()->getOne($sql, MYSQL_ASSOC);
		return $ret['total'];
	}
	
	/**
	 * 插入一条分享点击信息
	 * @return array
	 */
	public function insertShareOne($aid, $openid, $fromwxid, $friendInfo=false, $nums=1){
		if(!$friendInfo) $friendInfo = $this->getUserInfoOne($aid, $fromwxid);
		$data = array(
			'aid' =>  (int)	$aid,
			'openid'   =>  $openid,
			'fromwxid' =>  $fromwxid,
			'fromheadimg' => $friendInfo['headimgurl'],
			'fromnickname'=> $friendInfo['nickname'],
			'nums' => 1,
			'nums_history' => (int)$nums,
			'ip'=>functions::getip(),
			'ctm' => date('Y-m-d H:i:s',time()),
		);
		if(isset($friendInfo['goodsid'])) $data['goodsid'] =  (int)$friendInfo['goodsid'];
		if(isset($friendInfo['ext_info'])) $data['ext_info'] =  (int)$friendInfo['ext_info'];
		
		return odb::db()->insert($this->tblshareopen(), $data);
	}
	
	/**
	 * 更新一条分享信息
	 */
	public function updateShareOne($aid, $openid, $fromwxid, $shareArr, $goodsid=0){
		$where = array('aid'=>$aid, 'openid'=>$openid, 'fromwxid'=>$fromwxid);
		if($goodsid)$where['goodsid'] = (int) $goodsid;
		
		return odb::db()->update($this->tblshareopen(), $shareArr, $where);
	}
	
	/**
	 * 重置每天帮一个朋友点击的次数
	 * @return Database_Query_Builder_Update
	 */
	public function resetShareClicks($aid, $openid, $fromwxid, $shareArr,$goodsid=0){
		$row = $this->getShareOne($aid, $openid, $fromwxid);
		$currenttime = date('Y-m-d',time());
		$lasttime = substr($row['utm'],0,10);  //用户上次活动时间
		$diff = strtotime($lasttime) + 24*3600 - strtotime($currenttime);
		if($diff<=0){ //一天以后
			return $this->updateShareOne($aid, $openid, $fromwxid, $shareArr, $goodsid);
		}
	}
	
	/**
	 * 获取帮种植的朋友
	 * @return array
	 */
	public function getFriendsList($aid, $openid, $condition=array(), $asc = false){
		$limit = isset($condition['limit']) ? (int)$condition['limit'] : 50;
		$sql = 'SELECT fromheadimg,fromnickname,nums_history AS score  FROM '. $this->tblshareopen();
		$sql .= ' WHERE aid='. (int)$aid .' AND fromwxid="'. $openid .'"' ;
		if(isset($condition['goodsid'])) $sql .= " AND goodsid=". (int) $condition['goodsid'];
		$sql .= $asc ?  ' ORDER BY id asc' : ' ORDER BY id desc';
		if(isset($condition['pageno']) && isset($condition['pagesize'])){
			$sql .= ' LIMIT '. (($condition['pageno'] - 1) * $condition['pagesize']) .','. $condition['pagesize'];
		} else {
			$sql .= ' LIMIT '. $limit;
		}
		return odb::db()->getAll($sql,  MYSQL_ASSOC);
	}
	
	/**
	 * 获取帮种植的朋友
	 * @return array
	 */
	public function getFriendsListCount($aid, $openid, $condition=array()){
		
		$sql = 'SELECT count(*) AS total FROM '. $this->tblshareopen();
		$sql .= ' WHERE aid='. (int)$aid .' AND fromwxid="'. $openid .'"' ;
		if(isset($condition['goodsid'])) $sql .= " AND goodsid=". (int) $condition['goodsid'];
		
		$ret = odb::db()->getOne($sql,  MYSQL_ASSOC);
		
		return $ret['total'];
	}
	
	//=========================插入用户活动日志============================
	/**
	 * 记录用户日志
	 * @return Database_Query_Builder_Insert
	 */
	public function insertUserLog($aid, $openid, $nickname, $logInfo){
		$tbllog = $this->createLogTable($aid);
		$log = array(
			'aid' =>  (int)$aid,
			'openid'   => $openid,
			'nickname' => $nickname, 
			'content' => $logInfo,
			'ip' => functions::getip(),
			'ua' => $_SERVER ['HTTP_USER_AGENT'],
			'ctm' => date('Y-m-d H:i:s',time()),
		);
		return odb::db()->insert($tbllog, $log);
	}
	
	/**
	 * 生成用户日志表
	 * @return string
	 */
	public function createLogTable($aid){
    	$logtbl = $this->tbluserlog($aid);
    	$basetbl = substr($logtbl, 0, strrpos($logtbl, '_'));
		$sql = 'create table if not exists '. $logtbl .' like '. $basetbl;
        odb::db()->query($sql);
        return $logtbl;
    }  
	
}