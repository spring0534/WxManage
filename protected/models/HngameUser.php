<?php

/**
 * This is the model class for table "hngame_user".
 *
 * The followings are the available columns in table 'hngame_user':
 * @property string $id
 * @property string $openid
 * @property string $nickname
 * @property string $headimgurl
 * @property string $realname
 * @property string $phone
 * @property string $company
 * @property string $position
 * @property string $address
 * @property string $qq
 * @property string $wxname
 * @property string $email
 * @property string $resource
 * @property string $demand
 * @property string $remark
 */
class HngameUser extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'hngame_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('realname', 'required'),
			array('openid, company, address', 'length', 'max'=>50),
			array('nickname, realname, phone, wxname', 'length', 'max'=>20),
			array('headimgurl', 'length', 'max'=>200),
			array('position, email', 'length', 'max'=>30),
			array('qq', 'length', 'max'=>12),
			array('resource, demand, remark', 'length', 'max'=>1000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, openid, nickname, headimgurl, realname, phone, company, position, address, qq, wxname, email, resource, demand, remark', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'openid' => 'Openid',
			'nickname' => '微信昵称',
			'headimgurl' => '微信头像',
			'realname' => '姓名',
			'phone' => '手机',
			'company' => '公司',
			'position' => '职位',
			'address' => '家乡',
			'qq' => 'QQ',
			'wxname' => '微信号',
			'email' => '邮箱',
			'resource' => '资源',
			'demand' => '需求',
			'remark' => '备注',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('openid',$this->openid,true);
		$criteria->compare('nickname',$this->nickname,true);
		$criteria->compare('headimgurl',$this->headimgurl,true);
		$criteria->compare('realname',$this->realname,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('company',$this->company,true);
		$criteria->compare('position',$this->position,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('qq',$this->qq,true);
		$criteria->compare('wxname',$this->wxname,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('resource',$this->resource,true);
		$criteria->compare('demand',$this->demand,true);
		$criteria->compare('remark',$this->remark,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db2;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HngameUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
