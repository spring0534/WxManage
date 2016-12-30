<?php

/**
* BaseinfoController.php
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2014-11-18
* mankio <546234549@qq.com>
*/
class BaseinfoController extends BaseController {
	/**
	 * 调用基本信息编辑模板
	 */
	public function actionIndex() {
		$this->render('info',array(
			    'info' => user(),					
			));
	}
	/**
	 * 调用密码修改模板
	 */
	public function actionModifypwd(){
		$this->render('modifypwd');
	}
	/**
	 * 修改基本信息和session数据
	 */
	public function actionUpdateInfo(){
		$info = $_POST['info'];  //接收表单数据
		$save =array();
		if (!empty($info)){
			//过滤表单数据
			$save['nickname'] = $info['nickname'];
			$save['phone'] = $info['phone'];
			$save['qq'] = $info['qq'];
			$save['email'] = $info['email'];
			$save['headimg'] = $info['headimg'];
		    $res = Yii::app()->db->createCommand()->update('sys_user',$save,'id=:id',array(':id'=>user()->id));
		    unset($info);
		    if ($res !== false){
		    	//更新session数据
		        $secache = user();
		    	$secache->nickname = $save['nickname'];
		    	$secache->phone = $save['phone'];
		    	$secache->qq = $save['qq'];
		    	$secache->email = $save['email'];
		    	$secache->headimg = $save['headimg'];
		    	unset($save);
		    	$this->success("保存成功！");
		    } else {
		    	unset($save);
		    	$this->error("保存失败！", $_SERVER['HTTP_REFERER']);
		    } 
		} 
	}
	/**
	 * 保存新密码
	 */
	public function actionSaveNewpwd(){
		$info = $_POST['info'];  //接收表单数据
		if (!empty($info)){
			//判断输入旧密码是否正确
			if(crypt($info['oldpwd'],user()->password) === user()->password){
				$newpwd = SysUser::model()->hashPassword($info['newpwd']);//新密码加密后的结果
				$efc = Yii::app()->db->createCommand()->update('sys_user',array('password'=>$newpwd),'id=:id',array(':id'=>user()->id));
				unset($info);
				if ($efc > 0){
					user()->password = $newpwd;
					unset($newpwd);
					$this->success("修改成功！");
				} else {
					$this->error("修改失败！");
				}
			} else {
				unset($info);
				$this->error("你输入的原始密码不正确!");
			}
		}
	}
	/**
	 * 查看操作日志
	 */
	public function actionOperlog(){
		$idArr = $this->getAdminid(user()->id);
		if (!empty($_GET['condition']) && !empty($_GET['content'])){
			if($_GET['condition'] == 'adminid'){
				$cont = Yii::app()->db->createCommand()
				     ->select('id')
				     ->from('sys_user')
				     ->where('nickname=:nickname',array(':nickname'=>$_GET['content']))
				     ->queryScalar();
				//查询总记录数
				$count = Yii::app()->db->createCommand()
						->select('count(*)')
						->from('sys_operation_log')
						->where(array('and',array('in', 'adminid', $idArr),"adminid=:cont"),array(':cont'=>$cont))
						->queryScalar();
				$pages = new CPagination($count);
				$pages->pageSize = 15;//每页显示15条记录
				$criteria = new CDbCriteria;
				$pages->applylimit($criteria);
				$log = Yii::app()->db->createCommand()
						->select('l.*,u.nickname')
						->from('sys_operation_log l')
						->join('sys_user u','l.adminid=u.id')
						->where(array('and',array('in', 'l.adminid', $idArr),"l.adminid=:cont"),array(':cont'=>$cont))
						->limit($pages->pageSize)
						->offset($pages->currentPage*$pages->pageSize)
						->order('l.optime desc')
						->queryAll();
			} else {
				$cont1 = strtotime($_GET['content']);
				$cont2 = strtotime($_GET['content'])+24*3600;
				//查询总记录数
				$count = Yii::app()->db->createCommand()
						->select('count(*)')
						->from('sys_operation_log')
						->where(array('and',array('in', 'adminid', $idArr),"optime>=:cont1 and optime<=:cont2"),array(':cont1'=>$cont1,':cont2'=>$cont2))
						->queryScalar();
				$pages = new CPagination($count);
				$pages->pageSize = 15;//每页显示15条记录
				$criteria = new CDbCriteria;
				$pages->applylimit($criteria);
				$log = Yii::app()->db->createCommand()
						->select('l.*,u.nickname')
						->from('sys_operation_log l')
						->join('sys_user u','l.adminid=u.id')
						->where(array('and',array('in', 'l.adminid', $idArr),"l.optime>=:cont1 and l.optime<=:cont2"),array(':cont1'=>$cont1,':cont2'=>$cont2))
						->limit($pages->pageSize)
						->offset($pages->currentPage*$pages->pageSize)
						->order('l.optime desc')
						->queryAll();
			}
			$condition = $_GET['condition'];
			$content = $_GET['content'];
		} else {
			//查询总记录数
			$count = Yii::app()->db->createCommand()
					->select('count(*)')
					->from('sys_operation_log')
					->where(array('in', 'adminid', $idArr))
					->queryScalar();
			$pages = new CPagination($count);
			$pages->pageSize = 15;//每页显示15条记录
			$criteria = new CDbCriteria;
			$pages->applylimit($criteria);
			$log = Yii::app()->db->createCommand()
		             ->select('l.*,u.nickname')
		             ->from('sys_operation_log l')
		             ->join('sys_user u','l.adminid=u.id')
		             ->where(array('in', 'l.adminid', $idArr))
		             ->limit($pages->pageSize)
		             ->offset($pages->currentPage*$pages->pageSize)
		             ->order('l.optime desc')
			         ->queryAll();
			$condition = $content = null;
		}
		$loglist = "";
		foreach ($log as $val){
			$tr = "<tr>";
			$tr .= "<td>".$val['id']."</td>";
			$tr .= "<td>".$val['nickname']."</td>";
			$tr .= "<td>".$val['m']."</td>";
			$tr .= "<td>".$val['a']."</td>";
			$tr .= "<td>".date('Y-m-d H:i:s',$val['optime'])."</td>";
			$tr .= "</tr>";
			$loglist .= $tr;
		}
		$this->render('operlog',array(
			'loglist' => $loglist,
			'pages' => $pages,
			'cond' => $condition,
			'cont' => $content,	
			'count' => $count,
		));
	}
	/**
	 * 无限极查出某组全部成员的id
	 * @param $pid 父id
	 * @return array 返回此管理员即下面成员的id集合，数组格式，如array(1,2,3)
	 * 
	 */
	private function getAdminid($pid, &$arr="") {
	    if(empty($arr)) {
	        $arr = array();
	    }
	    $user = Yii::app()->db->createCommand()
	             ->select('id,pid')
	             ->from('sys_user')
	             ->where('pid='.$pid)
	             ->queryAll();
	    foreach ($user as $val) {
	        if(!empty($val)) {
	            $arr[] = $val;
	            $this->getAdminid($val['id'], $arr);
	        }
	    }
	    $id = array();
	    foreach ($arr as $val){
	    	$id[] = $val['id'];
	    }
	    unset($arr);
	    array_unshift($id,user()->id);
	    return $id;
	}

}