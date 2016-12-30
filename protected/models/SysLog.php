<?php

/**
 * This is the model class for table "sys_log".
 *
 * The followings are the available columns in table 'sys_log':
 * @property integer $id
 * @property string $level
 * @property string $category
 * @property string $ghid
 * @property integer $uid
 * @property string $request_url
 * @property string $ip
 * @property integer $logtime
 * @property string $message
 */
class SysLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sys_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, logtime', 'numerical', 'integerOnly'=>true),
			array('level, category, ghid', 'length', 'max'=>128),
			array('request_url', 'length', 'max'=>250),
			array('ip', 'length', 'max'=>32),
			array('message', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, level, category, ghid, uid, request_url, ip, logtime, message', 'safe', 'on'=>'search'),
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
			'user'=>array(self::HAS_ONE,'SysUser',array('id'=>'uid'),'select'=>'id,username,nickname')
			//'user'=>array(self::HAS_ONE,'Plugin',array('ptype'=>'type'),'select'=>'id,ptype,name')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'level' => '错误类型',
			'category' => 'Category',
			'ghid' => '公众账号',
			'uid' => '用户ID',
			'request_url' => '访问URL',
			'ip' => 'ip地址',
			'logtime' => '时间',
			'message' => '错误信息',
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

		
		$criteria->compare('level',$this->level,true);
		$criteria->compare('category',$this->category,true);
		$criteria->compare('ghid',$this->ghid,true);
		$criteria->compare('uid',$this->uid);
		$criteria->compare('request_url',$this->request_url,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('logtime',$this->logtime);
		$criteria->compare('message',$this->message,true);
		$criteria->with='user';
		$criteria->order='logtime desc';
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>15
			),
			'sort'=>array(
				'defaultOrder'=>'logtime DESC', //设置默认排序
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SysLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
