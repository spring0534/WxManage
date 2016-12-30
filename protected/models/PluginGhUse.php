<?php

/**
 * This is the model class for table "plugin_gh_use".
 *
 * The followings are the available columns in table 'plugin_gh_use':
 * @property integer $id
 * @property string $ghid
 * @property string $ptype
 * @property string $starttime
 * @property string $endtime
 * @property integer $maxnum
 * @property integer $status
 * @property string $ctm
 * @property string $utm
 * @property integer $tenant_id
 */
class PluginGhUse extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'plugin_gh_use';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ghid, ptype', 'required'),
			array('maxnum, status, tenant_id', 'numerical', 'integerOnly'=>true),
			array('ghid, ptype', 'length', 'max'=>20),
			array('starttime, endtime, ctm, utm', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ghid, ptype, starttime, endtime, maxnum, status, ctm, utm, tenant_id', 'safe', 'on'=>'search'),
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
			'plugin'=>array(self::HAS_ONE,'Plugin',array('ptype'=>'ptype'),'select'=>'id,name,icon_url,simple_memo,ptype')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '主键id',
			'ghid' => 'Ghid',
			'ptype' => 'Ptype',
			'starttime' => '开始日期',
			'endtime' => '结束日期',
			'maxnum' => '最多可创建的活动个数。',
			'status' => '开通状态。1可用，2过期',
			'ctm' => 'Ctm',
			'utm' => 'Utm',
			'tenant_id' => '商户ID',
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
		$criteria->compare('ghid',$this->ghid,true);
		$criteria->compare('ptype',$this->ptype,true);
		$criteria->compare('starttime',$this->starttime,true);
		$criteria->compare('endtime',$this->endtime,true);
		$criteria->compare('maxnum',$this->maxnum);
		$criteria->compare('status',$this->status);
		$criteria->compare('ctm',$this->ctm,true);
		$criteria->compare('utm',$this->utm,true);
		$criteria->compare('tenant_id',$this->tenant_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public function openlist()
	{

		$criteria=new CDbCriteria;
		$criteria->with='plugin';
		$criteria->addCondition("t.ghid='".gh()->ghid."'");
		$criteria->addCondition('t.status=1');
		$criteria->addCondition("t.starttime<'".date('Y-m-d H:i:s'."'"));
		$criteria->addCondition("t.endtime>'".date('Y-m-d H:i:s'."'"));
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>15
			),
		));
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PluginGhUse the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
