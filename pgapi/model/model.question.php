<?php
defined('IN_WEB') or die('Include Error!');

class ModelQuestion extends tables{
	//private $tbl_questions = 'local_ddd.questions'; 
	//private $tbl_options = 'local_ddd.question_options';           
	
	/**
	 * 获取题库,优先从缓存取
	 * @param int $aid 活动id
	 * @param int $update 1-强制刷新缓存,一般用于后台编辑完数据
	 * @return array
	 */
	public function getAllByAid($aid, $update = 0){
// 		$aid = functions::uint($aid);
// 		$key = okeys::mkQuestions($aid);
// 		$cache = ocache::minfo ($aid)->get($key);
// 		if(!$cache|| $update){
			//获取所有题目
			$sql = 'SELECT * FROM '. $this->tblquestions() .' WHERE aid='. $aid;
		    $ret = odb::db()->getAll($sql, MYSQL_ASSOC);
			if (!$ret) return array();
			$new = array();
			foreach($ret as $val){
				$new[$val['id']] = $val;
			}
			//获取题目对应的选项	
			$ids = implode(',', array_keys($new));
			$sql = 'SELECT * FROM '. $this->tbloptions();
			$sql .= ' WHERE qid IN('. rtrim($ids,',') .')';
			$ret =  odb::db()->getAll($sql, MYSQL_ASSOC);
			foreach($ret as $val){
				$new[$val['qid']]['options'][$val['seq']-1] = $val;
			}
// 			ocache::minfo ($aid)->set($key, $new);
			return $new;
// 		}		
// 		return $cache;
	}
	
	/**
	 * 从题库随机获取题目
	 * @param int $aid  活动id
	 * @param int $nums 题目数量  
	 * @return array
	 */
	public function getRandAll($aid, $nums){
		$aid = functions::uint($aid);
		$nums = functions::uint($nums);
		$questions = $this->getAllByAid($aid);
		if(!$questions) return array();
		shuffle($questions);  //打乱顺序
		$questions = array_slice($questions, 0, $nums);
		return array_values($questions);
	}
	
	/**
	 * 获取选项详情,优先从缓存取
	 * @param int $update 1-强制刷新缓存,一般用于后台编辑完数据
	 * @param int $id 选项ID
	 */
	public function getOptionById($id, $update = 0){
		$id = functions::uint($id);
// 		$key = okeys::mkoneoption($id);
		$sql = 'SELECT * FROM '. $this->tbloptions() .' WHERE id='. (int)$id;
		$sql .= ' LIMIT 1';
		$ret = odb::db()->getOne($sql,  MYSQL_ASSOC);
// 		$ret = odb::db()->getCacheOne($sql, MYSQL_ASSOC, $key, $update);
		return $ret;
	}
	
	/**
	 * 选项点击数＋1,以便后台统计显示
	 * @param int $id 选项ID
	 */
	public function updateOptionClicksById($id){
		$id = functions::uint($id);
		$sql = 'UPDATE '. $this->tbloptions() .' SET clicks=clicks+1';
		$sql .= ' WHERE id='. (int)$id;
		odb::db()->query($sql);
	}
	
}