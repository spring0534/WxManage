<?php

/**
 * This is the model class for table "sys_user".
 *
 * The followings are the available columns in table 'sys_user':
 * @property string $id
 * @property integer $pid
 * @property string $username
 * @property string $nickname
 * @property string $password
 * @property string $salt
 * @property string $phone
 * @property string $qq
 * @property string $email
 * @property string $last_login_time
 * @property string $last_login_ip
 * @property integer $login_count
 * @property string $create_time
 * @property integer $status
 * @property integer $groupid
 */
class SysUser extends CActiveRecord{
	public $rememberMe;
	/**
	 *
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'sys_user';
	}
	
	/**
	 *
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		return array(
			array(
				'create_time', 
				'length', 
				'max'=>20
			), 
			
			// array('timestamp','default','value'=>date('Y-m-d H:i:s')),
			array(
				'pid, login_count, status, groupid,mid,m_level', 
				'numerical', 
				'integerOnly'=>true
			), 
			array(
				'username, email', 
				'length', 
				'max'=>50
			), 
			array('username', 'unique','caseSensitive'=>false,'className'=>'SysUser','message'=>'用户名"{value}"已经被注册，请更换'), 
			array(
				'nickname', 
				'length', 
				'max'=>25
			), 
			array(
				'company',
				'length',
				'max'=>50
			),
			
			array(
				'password', 
				'length', 
				'max'=>128
			), 
			array(
				'headimg', 
				'length', 
				'max'=>200
			), 
			array(
				'pids',
				'length',
				'max'=>255
			),
			array(
				'phone', 
				'length', 
				'max'=>12
			), 
			array(
				'qq', 
				'length', 
				'max'=>15
			), 
			array(
				'last_login_time', 
				'length', 
				'max'=>11
			), 
			array(
				'last_login_ip', 
				'length', 
				'max'=>40
			), 
			array(
				'username', 
				'required'
			), 
			array(
				'password', 
				'required'
			), 
			array(
				'nickname', 
				'required'
			), 
			array(
				'ghid', 
				'length', 
				'max'=>50
			), 
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array(
				'id, pid, username, nickname,  phone, qq, email, last_login_time, last_login_ip, login_count, create_time, status, groupid', 
				'safe', 
				'on'=>'search'
			)
		);
	}
	
	/**
	 *
	 * @return array relational rules.
	 */
	public function relations(){
		//return array();
		/*return array(
			'groupid' => array(self::HAS_MANY, 'DealDetail', 'deal_id'),
		);*/
		return array(
			'group'=>array(self::HAS_ONE,'SysUsergroup',array('id'=>'groupid'),'select'=>'groupname'),
			'puser'=>array(self::HAS_ONE,'SysUser',array('id'=>'mid'),'select'=>'username,nickname'),
			'parentUser'=>array(self::HAS_ONE,'SysUser',array('id'=>'pid'),'select'=>'username,nickname')
		);
	}
	
	/**
	 *
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'id'=>'ID', 
			'pid'=>'父ID', 
			'username'=>'用户名', 
			'nickname'=>'昵称', 
			'password'=>'密码', 
			'phone'=>'手机号', 
			'qq'=>'QQ', 
			'email'=>'邮箱', 
			'last_login_time'=>'最后登录时间', 
			'last_login_ip'=>'最后登录Ip', 
			'login_count'=>'登录次数', 
			'create_time'=>'创建时间', 
			'status'=>'状态', 
			'groupid'=>'角色组', 
			'headimg'=>'头像', 
			'ghid'=>'绑定公众账号',
			'mid'=>'所属代理商ID',
			'm_level'=>'代理商等级',
			'company'=>'企业名称'
		);
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search(){
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria();
		
		$criteria->compare('id', $this->id, true);
		/*$criteria->compare('pid', $this->pid);*/
		$criteria->compare('username', $this->username, true);
		$criteria->compare('nickname', $this->nickname, true);
		$criteria->compare('phone', $this->phone, true);
		$criteria->compare('qq', $this->qq, true);
		$criteria->compare('email', $this->email, true);
		$criteria->compare('last_login_time', $this->last_login_time, true);
		$criteria->compare('last_login_ip', $this->last_login_ip, true);
		$criteria->compare('login_count', $this->login_count);
		$criteria->compare('create_time', $this->create_time, true);
		$criteria->compare('status', $this->status);
		$criteria->compare('groupid', $this->groupid);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>15,
			),
		));
	}
	function getTemplate(){
		$tpl='{ghid}{update}';
		if(!empty($this->ghid)){
			$tpl='{switch}{open}'.$tpl;
		}
		if(user()->groupid==5){
			//客服和的菜单
			$tpl='{switch}';
		}
		return  $tpl;
	}
	function getTemplate2(){
		$tpl='{login}{update}';
		if(!empty($this->ghid)){
			$tpl='{switch}{open}'.$tpl;
		}
		return  $tpl;
	}

	public function search_merchants(){
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria();
	
		
		/*$criteria->compare('pid', $this->pid);*/
		$criteria->compare('username', $this->username, true);
		$criteria->compare('nickname', $this->nickname, true);
		$criteria->compare('last_login_time', $this->last_login_time, true);
		$criteria->compare('last_login_ip', $this->last_login_ip, true);
		$criteria->compare('login_count', $this->login_count);
		$criteria->compare('create_time', $this->create_time, true);
		$criteria->compare('status', $this->status);
		$criteria->compare('company', $this->company,true);
		//$criteria->compare('groupid', $this->groupid);
		//$criteria->addCondition('status>0');
		if(user()->groupid==1||user()->id==1){
			$criteria->addCondition("groupid =4");
		}else{
			
			//$idsarr=array();
			$pid=user()->groupid==5?user()->mid:user()->id;
			$criteria->addCondition("FIND_IN_SET('".$pid."',pids)");
			$criteria->addCondition("groupid =4");
			/*$ids=$this->getChildUserId($pid, $idsarr,"",4);
			if(!empty($ids)){
				$idstr=implode(',', $ids);
				$criteria->addCondition("id in(".$idstr.")");
			}else{
				$criteria->addCondition("id=0");
			}*/
		}
		//如果代理商也绑定了公众号，则把代理商自己也显示为商户
		/*if(user()->groupid==3){
			$criteria->addCondition("id=".user()->id." and ghid!=''",'or');
		}*/
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>15,
			),
			'sort'=>array(
				'defaultOrder'=>'create_time desc,id ASC,pid ASC', //设置默认排序是createTime倒序
			),
		));
	}
	public function search_merchants_open($pageSize=15){
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria();
		$criteria->addCondition("groupid =4");
		$criteria->addCondition("FIND_IN_SET('".gh()->tenant_id."',pids)");
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>$pageSize,
			),
			'sort'=>array(
				'defaultOrder'=>'id ASC,pid ASC', //设置默认排序是createTime倒序
			),
		));
	}
	public function search_customer(){
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria();
	
		$criteria->compare('id', $this->id, true);
		/*$criteria->compare('pid', $this->pid);*/
		$criteria->compare('username', $this->username, true);
		$criteria->compare('nickname', $this->nickname, true);
		$criteria->compare('last_login_time', $this->last_login_time, true);
		$criteria->compare('last_login_ip', $this->last_login_ip, true);
		$criteria->compare('login_count', $this->login_count);
		$criteria->compare('create_time', $this->create_time, true);
		$criteria->compare('status', $this->status);
		$criteria->compare('groupid', 5);
		//$criteria->addCondition('status>0');

		$criteria->addCondition("pid in(".user()->id.")");
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>15,
			),
		));
	}
	public function search_agent(){
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria();
	
		$criteria->compare('id', $this->id, true);
		/*$criteria->compare('pid', user()->id);*/
		$criteria->compare('username', $this->username, true);
		$criteria->compare('nickname', $this->nickname, true);
		$criteria->compare('last_login_time', $this->last_login_time, true);
		$criteria->compare('last_login_ip', $this->last_login_ip, true);
		$criteria->compare('login_count', $this->login_count);
		$criteria->compare('create_time', $this->create_time, true);
		$criteria->compare('status', $this->status);
		//$criteria->compare('groupid', $this->groupid);
		//$criteria->addCondition('status>0');
		$idsarr=array();
		$ids=$this->getChildId(user()->id, $idsarr);
		if(!empty($ids)){
			$idstr=implode(',', $ids);
			$criteria->addCondition("id in(".$idstr.")");
		}else{
			$criteria->addCondition("id=0");
		}
		$criteria->addCondition("groupid in(3)");
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>15,
			),
		));
	}
	/**
	 * 获取下级所有代理的ID
	 * @date: 2015-3-9
	 * @author: 佚名
	 * @param 用户id $id
	 * @param array $ids
	 * @return array
	 */
	function getChildId($id,&$ids){
		$list=Yii::app()->db->createCommand()
		->select('id,pid,mid')
		->from('sys_user')
		->where('groupid=3 and mid='.$id)
		->queryAll();
		foreach ($list as $k=>$v){
				$ids[]=$v['id'];
				if($v['m_level']<=4){
					$this->getChildId($v['id'], $ids);
				}
		}
		return $ids;
		
	}
	/**
	 * 获取所有的子用户id(当用户量达到一定程度时会非常耗时，此方法仅作为前期的解决方案)
	 * @date: 2015-3-9
	 * @author: 佚名
	 * @param  $id
	 * @param  $ids
	 * @return 
	 */
	function getChildUserId($id,&$ids,$groupid_sql='',$limit_result_groupid=0){
		$where=$groupid_sql?'pid='.$id.' and '.$groupid_sql:'pid='.$id;
		$list=Yii::app()->db->createCommand()
		->select('*')
		->from('sys_user')
		->where($where)
		->queryAll();
		foreach ($list as $k=>$v){
			if(!empty($limit_result_groupid)){
				if($v['groupid']==$limit_result_groupid){
					$ids[]=$v['id'];
				}
			}else{
				$ids[]=$v['id'];
			}
			$this->getChildUserId($v['id'], $ids,$groupid_sql,$limit_result_groupid);
		}
		return $ids;
	
	}
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	public function validatePassword($password){
		return crypt($password, $this->password)===$this->password;
	}
	public function hashPassword($password){
		return crypt($password, $this->generateSalt());
	}
	public function getGrouplist(){
		$list=SysUsergroup::model()->findAll();
		foreach ($list as $v){
			$data[]=array(
				'groupid'=>$v->id, 
				'groupname'=>$v->groupname
			);
		}
		return $data;
	}
	protected function generateSalt($cost=10){
		if (!is_numeric($cost)||$cost<4||$cost>31){
			throw new CException(Yii::t('Cost parameter must be between 4 and 31.'));
		}
		$rand='';
		for ($i=0; $i<8; ++$i)
			$rand.=pack('S', mt_rand(0, 0xffff));
		$rand.=microtime();
		$rand=sha1($rand, true);
		$salt='$2a$'.str_pad((int) $cost, 2, '0', STR_PAD_RIGHT).'$';
		$salt.=strtr(substr(base64_encode($rand), 0, 22), array(
			'+'=>'.'
		));
		return $salt;
	}
	public function login(){
		$user=SysUser::model()->find('LOWER(username)=?', array(
			$this->username
		));
		if (!empty($user)){
			if (!$user->validatePassword($this->password))
				return false;
			else{
				$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
				$user->last_login_time=time();
				$user->last_login_ip=Yii::app()->request->userHostAddress;
				$user->login_count=$user->login_count+1;
				$user->save();
				yii::app()->session['admin']=$user;
				yii::app()->session['gh']=SysUserGh::model()->find("ghid='".$user->ghid."'");
				if ($this->rememberMe){
					cookie('admin_local', array(
						'username'=>$this->username, 
						'password'=>authcode($this->password, 'ENCODE')
					), $duration);
				}else{
					cookie('admin_local', null);
				}
				return true;
			}
		}
	}

	function gePids($id,&$ids){
		$list=Yii::app()->db->createCommand()
		->select('id,pid,mid')
		->from('sys_user')
		->where('id='.$id)
		->queryRow();
		$ids[]=$list['pid'];
		if($list['pid']!=0){
			$this->gePids($list['pid'], $ids);
		}
		return $ids;
	
	}
	protected function beforeSave(){
		if ($this->isNewRecord){
			$this->create_time=date('Y-m-d H:i:s');
			
			
		}else{
		}
		if(!empty($this->pid)){
			$arr[]=$this->pid;
			$array=$this->gePids($this->pid,$arr);
			$str=implode(',', $array);
			$this->pids=$str;
		}else{
			$this->pids=0;
		}
		return parent::beforeSave();
	}
	/*protected function afterSave(){
		$array=$this->gePids($this->id,$arr);
		$str=implode(',', $array);
		/*$model=SysUser::model()->findByPk($this->id);
		$model->pids=$str;
		$model->save();
		$this->pids=$str;
		//$this->save();
		return parent::afterSave();
	}*/
}
