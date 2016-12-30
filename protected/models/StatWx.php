<?php

/**
 * This is the model class for table "stat_wx".
 *
 * The followings are the available columns in table 'stat_wx':
 * @property integer $id
 * @property string $day
 * @property string $ghid
 * @property integer $sub
 * @property integer $unsub
 * @property integer $receive_num
 * @property integer $send_num
 * @property integer $msg_num
 * @property string $ctm
 * @property string $utm
 */
class StatWx extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stat_wx';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('day', 'required'),
			array('sub, unsub, receive_num, send_num, msg_num', 'numerical', 'integerOnly'=>true),
			array('ghid', 'length', 'max'=>50),
			array('ctm, utm', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, day, ghid, sub, unsub, receive_num, send_num, msg_num, ctm, utm', 'safe', 'on'=>'search'),
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
			'day' => '时间',
			'ghid' => 'Ghid',
			'sub' => '新增关注数',
			'unsub' => '取消关注数',
			'receive_num' => '接收消息数',
			'send_num' => '发送消息数',
			'msg_num' => '消息总数',
			'ctm' => 'Ctm',
			'utm' => 'Utm',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('day',$this->day,true);
		$criteria->compare('ghid',$this->ghid,true);
		$criteria->compare('sub',$this->sub);
		$criteria->compare('unsub',$this->unsub);
		$criteria->compare('receive_num',$this->receive_num);
		$criteria->compare('send_num',$this->send_num);
		$criteria->compare('msg_num',$this->msg_num);
		$criteria->compare('ctm',$this->ctm,true);
		$criteria->compare('utm',$this->utm,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StatWx the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
