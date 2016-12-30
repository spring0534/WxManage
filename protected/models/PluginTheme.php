<?php

/**
 * This is the model class for table "plugin_theme".
 *
 * The followings are the available columns in table 'plugin_theme':
 * @property integer $id
 * @property string $ptype
 * @property string $name
 * @property string $simple_memo
 * @property string $detail_memo
 * @property string $pic1
 * @property string $pic2
 * @property string $pic3
 * @property string $scr_theme
 * @property string $wx_theme
 * @property integer $status
 * @property string $ctm
 * @property string $utm
 * @property integer $tenant_id
 * @property integer $scope
 * @property string $ghid
 * @property integer $uid
 */
class PluginTheme extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'plugin_theme';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ptype, name, simple_memo,', 'required'),
			array('status, tenant_id, scope, uid', 'numerical', 'integerOnly'=>true),
			array('ptype', 'length', 'max'=>20),
			array('name, ghid', 'length', 'max'=>50),
			array('simple_memo, pic1, pic2, pic3', 'length', 'max'=>200),
			array('detail_memo', 'length', 'max'=>1000),
			array('scr_theme, wx_theme', 'length', 'max'=>255),
			array('ctm', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ptype, name, simple_memo, detail_memo, pic1, pic2, pic3, scr_theme, wx_theme, status, ctm, utm, tenant_id, scope, ghid, uid', 'safe', 'on'=>'search'),
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
			'ptype' => '归属微应用类型',
			'name' => '模板名称',
			'simple_memo' => '模版简介',
			'detail_memo' => '模版详细介绍',
			'pic1' => '大屏幕预览图',
			'pic2' => '微信预览图',
			'pic3' => 'Pic3',
			'scr_theme' => '大屏幕模板文件',
			'wx_theme' => '微信页面模板文件',
			'status' => '可用状态',//。1可用0禁用
			'ctm' => 'Ctm',
			'utm' => '最近修改时间',
			'tenant_id' => '归属的商户。0表示适用所有商户，大于0的值表示商户ID',
			'scope' => '适用范围',//。1盒子，2微信机
			'ghid' => '哪个公众号所有',
			'uid' => '操作人',
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
		$criteria->compare('ptype',$this->ptype,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('simple_memo',$this->simple_memo,true);
		$criteria->compare('scr_theme',$this->scr_theme,true);
		$criteria->compare('wx_theme',$this->wx_theme,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('scope',$this->scope);
		$criteria->compare('ghid',$this->ghid,true);
		$criteria->compare('uid',$this->uid);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PluginTheme the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
