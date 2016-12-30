<?php

/**
 * This is the model class for table "wx_material".
 *
 * The followings are the available columns in table 'wx_material':
 * @property integer $id
 * @property string $title
 * @property string $msg_type
 * @property string $content
 * @property string $ghid
 * @property string $ctm
 * @property string $utm
 * @property integer $status
 * @property integer $operator_uid
 */
class WxMaterial extends CActiveRecord {
	/**
	 *
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'wx_material';
	}
	// 添加时间戳
	/*public function behaviors()
	{
		return array(
			'CTimestampBehavior'=>array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'updateAttribute' => 'utm',
				'createAttribute' => 'ctm')
		);
	}*/
	/**
	 *
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array(
				'title, msg_type, ghid',
				'required'
			),
			array(
				'status, operator_uid',
				'numerical',
				'integerOnly' => true
			),
			array(
				'title',
				'length',
				'max' => 100
			),
			array(
				'msg_type',
				'length',
				'max' => 20
			),
			array(
				'content',
				'length',
				'max' => 5000
			),
			array(
				'ghid',
				'length',
				'max' => 50
			),
			array(
				'utm',
				'safe'
			),

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array(
				'id, title, msg_type, content, ghid, ctm, utm, status, operator_uid',
				'safe',
				'on' => 'search'
			)
		);
	}

	/**
	 *
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array();
	}
	/*
	 * public function defaultScope()
	 * {
	 * return array(
	 * 'condition'=>"ghid='".gh()->ghid."'",
	 * );
	 * }
	 */
	/**
	 *
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => '主键id',
			'title' => '素材名称',
			'msg_type' => '素材类型',
			'content' => "内容",
			'ghid' => '所属公众号',
			'ctm' => '创建时间',
			'utm' => '更新时间',
			'status' => '状态',
			'operator_uid' => '操作人ID'
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
	public function search() {
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria = new CDbCriteria();

		$criteria->compare('id', $this->id);
		$criteria->compare('title', $this->title, true);
		$criteria->compare('msg_type', $this->msg_type, true);
		$criteria->compare('content', $this->content, true);
		$criteria->compare('ghid', $this->ghid, true);
		$criteria->compare('ctm', $this->ctm, true);
		$criteria->compare('utm', $this->utm, true);
		$criteria->compare('status', $this->status);
		$criteria->compare('operator_uid', $this->operator_uid);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination'=>array(
				'pageSize'=>15,
			),
		));
	}
	public function oneselfSearch() {
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria = new CDbCriteria();
		$criteria->compare('title', $this->title, true);
		$criteria->compare('msg_type', $this->msg_type, true);
		$criteria->compare('content', $this->content, true);
		if(!empty($_GET['WxMaterial']['ctm'][1]))$criteria->addCondition("ctm>='".$_GET['WxMaterial']['ctm'][1]."'");
		if(!empty($_GET['WxMaterial']['ctm'][2]))$criteria->addCondition("ctm<'".$_GET['WxMaterial']['ctm'][2]."'");
		$criteria->compare('utm', $this->utm, true);
		$criteria->compare('status', $this->status);
		$criteria->compare('operator_uid', $this->operator_uid);
		$criteria->compare('ghid',gh()->ghid);
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination'=>array(
				'pageSize'=>15,
			),
			'sort'=>array(
				'defaultOrder'=>'ctm DESC', //设置默认排序
			),
		));
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className
	 * active record class name.
	 * @return WxMaterial the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	protected function beforeSave() {
		parent::beforeSave();
		/*if ($this->isNewRecord) {
			$this->ctm = time();
		}else{
			$this->utm=time();
		}*/
		$this->ghid=gh()->ghid;
		return true;
	}
	/**
	 * 获取详细内容
	 * @date: 2014-11-27
	 * @author: 佚名
	 * @param unknown $id
	 */
	function getdetail($id){
		return Yii::app()->db->createCommand()->select('content')->from('wx_material_detail')->where('id='.intval($id))->queryScalar();
	}
}
