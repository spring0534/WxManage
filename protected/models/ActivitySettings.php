<?php

/**
 * This is the model class for table "activity_settings".
 *
 * The followings are the available columns in table 'activity_settings':
 * @property integer $aid
 * @property string $propname
 * @property string $propvalue
 * @property string $tm
 * @property string $ltm
 */
class ActivitySettings extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ActivitySettings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'activity_settings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('aid, propname', 'required'),
			array('aid', 'numerical', 'integerOnly'=>true),
			array('propname', 'length', 'max'=>30),
			array('tm', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('aid, propname, propvalue,  tm, ltm', 'safe', 'on'=>'search'),
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
			'aid' => 'Aid',
			'propname' => 'Propname',
			'propvalue' => 'Propvalue',
			'tm' => 'Tm',
			'ltm' => 'Ltm',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('aid',$this->aid);
		$criteria->compare('propname',$this->propname,true);
		$criteria->compare('propvalue',$this->propvalue,true);
		$criteria->compare('tm',$this->tm,true);
		$criteria->compare('ltm',$this->ltm,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}