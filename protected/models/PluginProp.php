<?php

/**
 * This is the model class for table "plugin_prop".
 *
 * The followings are the available columns in table 'plugin_prop':
 * @property integer $id
 * @property string $ptype
 * @property string $propname
 * @property string $proptitle
 * @property string $proptype
 * @property string $setting
 * @property integer $required
 * @property integer $minlength
 * @property integer $maxlength
 * @property integer $seq
 * @property string $memo
 * @property string $pattern
 * @property integer $issystem
 * @property integer $isshow
 * @property integer $editable
 * @property string $ctm
 * @property string $utm
 */
class PluginProp extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'plugin_prop';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ptype, propname, proptitle, ctm', 'required'),
			array('required, minlength, maxlength,pgroup, seq, issystem, isshow, editable', 'numerical', 'integerOnly'=>true),
			array('ptype, propname', 'length', 'max'=>20),
			array('proptitle', 'length', 'max'=>50),
			array('memo', 'length', 'max'=>500),
			array('proptype, pattern', 'length', 'max'=>255),
			array('setting', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ptype, propname, proptitle,  proptype,  setting, required, minlength,pgroup, maxlength, seq, memo, pattern, issystem, isshow, editable, ctm, utm', 'safe', 'on'=>'search'),
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
			'pgroups'=>array(self::HAS_ONE,'PluginPropGroup',array('ptype'=>'ptype','id'=>'pgroup'),'select'=>'name,img')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '主键id',
			'ptype' => '微应用类型',
			'pgroup'=>'分组',
			'propname' => '属性名',//。只允许英文、数字和下划线。
			'proptitle' => '属性标题',//。用于在后台的微应用配置页面中显示。
			'proptype' => '属性类型',//。img,text,textarea,texthtml,checkbox,select-act,select,onoff
			'setting' => 'Setting',
			'required' => '是否必填',//。0不必填。1必填
			'minlength' => 'Minlength',
			'maxlength' => '值的最大长度。',
			'seq' => '排序',//配置页面里的显示顺序。
			'memo' => '属性说明',
			'pattern' => '正则',
			'issystem' => '是否系统属性1是0否',
			'isshow' => '商户是否可见',//0不可见但管理可见  1可见
			'editable' => '是否可编辑',//。0不可编辑，1可编辑
			'ctm' => 'Ctm',
			'utm' => '最近修改时间',
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


		$criteria->compare('ptype',$_GET['ptype']);
		$criteria->compare('propname',$this->propname,true);
		$criteria->compare('proptitle',$this->proptitle,true);
		$criteria->compare('proptype',$this->proptype,true);
		$criteria->compare('required',$this->required==3?'':$this->required);
		$criteria->compare('maxlength',$this->maxlength);
		$criteria->compare('seq',$this->seq);
		$criteria->compare('memo',$this->memo,true);
		$criteria->compare('editable',$this->editable==3?'':$this->editable);
		$criteria->compare('ctm',$this->ctm,true);
		$criteria->compare('utm',$this->utm,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'seq', //设置默认排序
				//'defaultOrder'=>'t.id DESC', //当有join连接查询时，需要添加表别名
			),
			'pagination'=>array(
						'pageSize'=>200
				),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PluginProp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
