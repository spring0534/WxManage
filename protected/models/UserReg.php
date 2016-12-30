<?php

/**
 * This is the model class for table "common_record".
 *
 * The followings are the available columns in table 'common_record':
 * @property integer $id
 * @property integer $aid
 * @property string $wxid
 * @property string $src_openid
 * @property string $ghid
 * @property string $username
 * @property string $phone
 * @property string $company
 * @property integer $prize
 * @property integer $relate_aid
 * @property string $sncode
 * @property string $qrcode
 * @property string $qrcode_small
 * @property integer $score
 * @property integer $total_time
 * @property string $ext_info
 * @property integer $status
 * @property string $ip
 * @property string $ua
 * @property string $ctm
 * @property string $utm
 * @property string $tags
 * @property string $notes
 * @property integer $flag
 * @property integer $form_id
 */
class UserReg extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'common_record';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('utm', 'required'),
			array('aid, prize, relate_aid, score, total_time, status, flag, form_id', 'numerical', 'integerOnly'=>true),
			array('wxid, src_openid, sncode, ip', 'length', 'max'=>50),
			array('ghid, username, phone', 'length', 'max'=>20),
			array('company', 'length', 'max'=>40),
			array('qrcode, qrcode_small', 'length', 'max'=>200),
			array('ext_info', 'length', 'max'=>5000),
			array('ua', 'length', 'max'=>500),
			array('ctm, tags, notes', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, aid, wxid, src_openid, ghid, username, phone, company, prize, relate_aid, sncode, qrcode, qrcode_small, score, total_time, ext_info, status, ip, ua, ctm, utm, tags, notes, flag, form_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'wxuser'=>array(self::HAS_ONE,'WxUserInfo',array('openid'=>'wxid','ghid'=>'ghid'),'select'=>'nickname,headimgurl'),
			'act'=>array(self::HAS_ONE,'Activity',array('aid'=>'aid'),'select'=>'title'),
			'ghinfo'=>array(self::HAS_ONE,'SysUserGh',array('ghid'=>'ghid'),'select'=>'name'),

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '主键id',
			'aid' => '活动',
			'wxid' => '微信id',
			'src_openid' => 'Src Openid',
			'ghid' => '活动公众号',
			'username' => '登记姓名',
			'phone' => '登记电话号码',
			'company' => '公司',
			'prize' => '中奖级别',//:1.一等奖 2.二等奖 3.三等奖
			'relate_aid' => 'Relate Aid',
			'sncode' => '兑奖SN码',
			'qrcode' => '二维码图片地址-大，可在大屏幕显示',
			'qrcode_small' => '二维码图片-小，可在微信显示',
			'score' => '得分',
			'total_time' => '用时：单位秒',
			'ext_info' => '额外信息',//：如性别，年龄等，万能表单格式为json [{"title":"身份证","name":"idCard","value":"111"},...]
			'status' => '状态',//：1-有效，2-无效,3-后续状态：已兑奖或已团购等
			'ip' => 'ip',
			'ua' => 'ua',
			'ctm' => '时间',
			'utm' => '最近修改时间',
			'tags' => '用户标签。多个用逗号分隔',
			'notes' => '备注。由商家录入',
			'flag' => '内定中奖',//。0正常，1内定中奖，-1黑名单
			'form_id' => '表单ID',
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
	public function search($pageSize=15)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('aid',intval($_GET['aid'])?intval($_GET['aid']):$this->aid);
		$criteria->compare('wxid',$this->wxid,true);
		//$criteria->compare('src_openid',$this->src_openid,true);
		if(empty(gh()))exit();
		$criteria->compare('ghid',gh()->ghid);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('company',$this->company,true);
		$criteria->compare('prize',$this->prize);
		//$criteria->compare('relate_aid',$this->relate_aid);
		$criteria->compare('sncode',$this->sncode,true);
		//$criteria->compare('qrcode',$this->qrcode,true);
		//$criteria->compare('qrcode_small',$this->qrcode_small,true);
		//$criteria->compare('score',$this->score);
		//$criteria->compare('total_time',$this->total_time);
		//$criteria->compare('ext_info',$this->ext_info,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('utm',$this->utm,true);
		//$criteria->compare('tags',$this->tags,true);
		//$criteria->compare('notes',$this->notes,true);
		//$criteria->compare('flag',$this->flag);
		//$criteria->compare('form_id',$this->form_id);
		if(!empty($_GET['UserReg']['ctm'][1]))$criteria->addCondition("ctm>='".$_GET['UserReg']['ctm'][1]."'");
		if(!empty($_GET['UserReg']['ctm'][2]))$criteria->addCondition("ctm<'".$_GET['UserReg']['ctm'][2]."'");
		if(!empty($_GET['info'])){
			$cinfo=intval($_GET['info']);
			if($cinfo==1){
				$criteria->addCondition("username is not null and username!=''");
			}else{
				$criteria->addCondition("username is null or username=''");
			}

		}
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>$pageSize,
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserReg the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
