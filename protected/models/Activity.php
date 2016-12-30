<?php

/**
 * This is the model class for table "activity".
 *
 * The followings are the available columns in table 'activity':
 * @property string $aid
 * @property string $type
 * @property string $akey
 * @property string $wxurl_qrcode
 * @property string $title
 * @property string $description
 * @property integer $uid
 * @property integer $status
 * @property integer $need_attent
 * @property string $starttime
 * @property string $endtime
 * @property string $ctm
 * @property string $ltm
 * @property integer $siteid
 * @property string $token
 * @property string $email
 * @property string $ghid
 * @property integer $themeid
 * @property string $scrurl
 * @property string $reseturl
 * @property integer $tenant_id
 * @property integer $paid
 * @property integer $did
 * @property integer $dtype
 */
class Activity extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'activity';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type,title', 'required'),
			array('uid, status, need_attent, siteid, themeid, tenant_id, paid, did, dtype', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>32),
			array('akey, email, scrurl, reseturl', 'length', 'max'=>50),
			array('wxurl_qrcode', 'length', 'max'=>120),
			array('title', 'length', 'max'=>255),
			array('description', 'length', 'max'=>500),
			array('token', 'length', 'max'=>30),
			array('ghid', 'length', 'max'=>20),
			array('starttime, endtime, ctm', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('aid, type, akey, wxurl_qrcode, title, description, uid, status, need_attent, starttime, endtime, ctm, ltm, siteid, token, email, ghid, themeid, scrurl, reseturl, tenant_id, paid, did, dtype', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		//'plugin'=>array(self::HAS_ONE,'Plugin',array('ptype'=>'ptype'),'select'=>'id,name,icon_url,simple_memo')
		return array(
			'plugin'=>array(self::HAS_ONE,'Plugin',array('ptype'=>'type'),'select'=>'id,ptype,name,setting'),
			'ptheme'=>array(self::HAS_ONE,'PluginTheme',array('ptype'=>'type','id'=>'themeid'),'select'=>'name')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'aid' => '活动ID',
			'type' => '微应用类型',
			'akey' => '活动标识',//Unique key of activity
			'wxurl_qrcode' => '微信页面链接二维码',
			'title' => '活动标题',
			'description' => '活动描述',
			'status' => '活动状态',//。小于等于0为不可用，大于1的值都是可用的，具体含义由活动微应用自己管理，一般1为正常有效状态
			'need_attent' => '是否必须关注才可玩',//：1-必须关注才可玩，2-不用关注就可玩
			'starttime' => '活动开始时间',
			'endtime' => '活动结束时间',
			'ctm' => '创建时间',
			'ltm' => '最近修改时间',
			'siteid' => '微站ID',
			'token' => 'Token',
			'email' => '广告主登录账号',
			'ghid' => '公众号ID',
			'themeid' => '模板ID',
			'scrurl' => '大屏url',
			'reseturl' => '重置url',
			'tenant_id' => 'Tenant',
			'paid' => '父aid。值为0表示为独立活动，没有父aid。值为-1表示公共活动。',
			'did' => '设备ID',
			'dtype' => '设备类型',//。0线上，1活动盒子，2微信机
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('aid',$this->aid,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('akey',$this->akey,true);
		$criteria->compare('wxurl_qrcode',$this->wxurl_qrcode,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);

		//$criteria->compare('status',1);
		//$criteria->addCondition('status>0');
		$criteria->addCondition("ghid='".gh()->ghid."'");
		$criteria->compare('need_attent',$this->need_attent);
		$criteria->compare('starttime',$this->starttime,true);
		$criteria->compare('endtime',$this->endtime,true);
		$criteria->compare('ctm',$this->ctm,true);
		$criteria->compare('ltm',$this->ltm,true);
		$criteria->compare('siteid',$this->siteid);
		$criteria->compare('token',$this->token,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('ghid',gh()->ghid);
		$criteria->compare('themeid',$this->themeid);
		$criteria->compare('scrurl',$this->scrurl,true);
		$criteria->compare('reseturl',$this->reseturl,true);
		$criteria->compare('tenant_id',$this->tenant_id);
		$criteria->compare('paid',$this->paid);
		$criteria->compare('did',$this->did);
		$criteria->compare('dtype',$this->dtype);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
          		'pageSize'=>15,
      		),
			'sort'=>array(
				'defaultOrder'=>'aid DESC,ctm desc', //设置默认排序是createTime倒序
			),
		));
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
	public function search_manage()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('aid',$this->aid,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('akey',$this->akey,true);
		$criteria->compare('wxurl_qrcode',$this->wxurl_qrcode,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);

		//$criteria->compare('status',1);
		/*$criteria->addCondition('status>0');*/
		/*$criteria->addCondition("ghid='".gh()->ghid."'");*/
		$criteria->compare('need_attent',$this->need_attent);
		$criteria->compare('starttime',$this->starttime,true);
		$criteria->compare('endtime',$this->endtime,true);
		$criteria->compare('ctm',$this->ctm,true);
		$criteria->compare('ltm',$this->ltm,true);
		$criteria->compare('siteid',$this->siteid);
		$criteria->compare('token',$this->token,true);
		$criteria->compare('email',$this->email,true);
		/*$criteria->compare('ghid',gh()->ghid);*/
		if(user()->id==1||user()->groupid==1){

		}else{
			if(user()->groupid==3||user()->groupid==5){
				$idsarr=array();
				$pid=user()->groupid==5?user()->mid:user()->id;
				$ids=$this->getChildUserId($pid, $idsarr,"");
				if(!empty($ids)){
					$idstr=implode(',', $ids);
					$criteria->addCondition("tenant_id in(".$idstr.")");
				}else{
					//$criteria->addCondition("id=0");
				}
				//dump($criteria);
			}else{
				exit;
			}
		}
		$criteria->compare('themeid',$this->themeid);
		$criteria->compare('scrurl',$this->scrurl,true);
		$criteria->compare('reseturl',$this->reseturl,true);
		$criteria->compare('tenant_id',$this->tenant_id);
		$criteria->compare('paid',$this->paid);
		$criteria->compare('did',$this->did);
		$criteria->compare('dtype',$this->dtype);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>15,
			),
			'sort'=>array(
				'defaultOrder'=>'aid DESC,ctm desc', //设置默认排序是createTime倒序
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Activity the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	protected function beforeSave(){
		parent::beforeSave();
		if($this->isNewRecord){
			if(empty($this->akey)){
				$this->akey=$this->get_akey();
			}
			$this->ctm=date('Y-m-d H:i:s');
			//$this->ghid=gh()->ghid;
		}
		return true;
	}
	function get_akey(){
		$len=16;
		$akey=rand_string($len,8);
		if($this->findByAttributes(array('akey'=>$akey))){
				return $this->get_akey();
		}
		return $akey;
	}
}
