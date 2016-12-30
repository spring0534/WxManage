<?php

/**
 * This is the model class for table "common_prize".
 *
 * The followings are the available columns in table 'common_prize':
 * @property integer $id
 * @property integer $aid
 * @property string $name
 * @property integer $level
 * @property integer $num
 * @property string $pic
 * @property string $note
 * @property string $ctm
 * @property string $utm
 * @property integer $uid
 * @property integer $status
 * @property integer $gain_num
 * @property string $rate
 */
class CommonPrize extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'common_prize';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('aid', 'required'),
			array('aid, level, num, uid, status, gain_num', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
			array('pic, note', 'length', 'max'=>1000),
			array('rate', 'length', 'max'=>10),
			array('ctm, utm', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, aid, name, level, num, pic, note, ctm, utm, uid, status, gain_num, rate', 'safe', 'on'=>'search'),
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
			'aid' => '活动ID',
			'name' => '奖项名称',
			'level' => '奖项等级',//，如1表示一等奖
			'num' => '奖品总数量',
			'pic' => '奖品图片',
			'note' => '奖项描述',
			'ctm' => 'Ctm',
			'utm' => 'Utm',
			'uid' => '操作人ID',
			'status' => '状态',//。0无效，1有效，2奖品已派完（有活动程序自行修改）
			'gain_num' => '已中奖个数',
			'rate' => '中奖概率，百分比',
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
		$criteria->compare('aid',intval($_GET['aid']));
		$criteria->compare('name',$this->name,true);
		$criteria->compare('level',$this->level);
		$criteria->compare('num',$this->num);
		$criteria->compare('pic',$this->pic,true);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('ctm',$this->ctm,true);
		$criteria->compare('utm',$this->utm,true);
		$criteria->compare('uid',$this->uid);
		$criteria->compare('status',$this->status);
		$criteria->compare('gain_num',$this->gain_num);
		$criteria->compare('rate',$this->rate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>15,
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CommonPrize the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
