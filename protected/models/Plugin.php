<?php

/**
 * This is the model class for table "plugin".
 *
 * The followings are the available columns in table 'plugin':
 * @property integer $id
 * @property string $ptype
 * @property string $name
 * @property string $icon_url
 * @property string $simple_memo
 * @property string $detail_memo
 * @property string $usage
 * @property integer $promote
 * @property integer $hot
 * @property integer $status
 * @property integer $need_reset_url
 * @property integer $need_scr_url
 * @property string $ctm
 * @property string $utm
 * @property string $processor_class
 * @property integer $dtype
 * @property string $screenshots
 * @property string $menus
 * @property integer $price_month
 * @property integer $price_year
 * @property integer $tenant_id
 * @property integer $uid
 * @property integer $cate
 * @property string $versions
 */
class Plugin extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'plugin';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ptype, name, simple_memo', 'required'),
			array('promote, hot, status, need_reset_url, need_scr_url, dtype, price_month, price_year, tenant_id, uid, cate', 'numerical', 'integerOnly'=>true),
			array('ptype, name, versions', 'length', 'max'=>20),
			array('icon_url', 'length', 'max'=>200),
			array('simple_memo,setting', 'length', 'max'=>500),
			array('processor_class', 'length', 'max'=>100),
			array('screenshots, menus', 'length', 'max'=>2000),
			array('detail_memo, usage', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ptype, name, icon_url, simple_memo, detail_memo, usage, promote, hot, status, need_reset_url, need_scr_url, ctm, utm, processor_class, dtype, screenshots, menus, price_month, price_year, tenant_id, uid, cate, versions', 'safe', 'on'=>'search'),
		);
	}

	private $swghid='';
	public function relations()
	{
		$this->swghid=htmlentities($_GET['ghid_control']);
		return array(
			'puse'=>array(self::HAS_ONE,'PluginGhUse',array('ptype'=>'ptype'),'select'=>'status,endtime,maxnum','on'=>"puse.ghid='".$this->swghid."'")
		);
	}


	public function attributeLabels()
	{
		return array(
			'id' => '主键id',
			'ptype' => '微应用类型',
			'name' => '微应用名称',
			'icon_url' => '微应用图标',
			'simple_memo' => '微应用简介',
			'detail_memo' => '微应用详细介绍',
			'usage' => 'Usage',
			'promote' => '推荐',//。值越大推荐级别越高
			'hot' => '热度',//。值越大越热
			'status' => '状态',//。0未发布，1已发布，2内部测试，3已废弃
			'need_reset_url' => '是否需要显示重置，大屏url:1-需要,0-不需要',
			'need_scr_url' => '是否需要显示大屏url:1-需要,0-不需要',
			'ctm' => 'Ctm',
			'utm' => '最近修改时间',
			'processor_class' => '活动微应用的处理类名',
			'dtype' => '适用范围',//。1盒子，2微信机，3盒子和微信机，4线上
			'screenshots' => '截图',//。多个图片以逗号分隔
			'menus' => '操作菜单。json格式。微应用只支持一级菜单。每个菜单包含三个字段：name,url,icon，其中icon如果不填写则用默认值',
			'price_month' => '月购买价格',
			'price_year' => '年购买价格',
			'tenant_id' => 'Tenant',
			'uid' => 'Uid',
			'cate' => '分类',//。0现场1社交2抽奖3促销4游戏5应用
			'versions' => '版本',
			'setting'=>'配置'
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

		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('ptype',$this->ptype,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('cate',$this->cate);
		$criteria->compare('versions',$this->versions,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>15
			),
			'sort'=>array(
				'defaultOrder'=>'ctm desc',
			),
		));
	}
	public function search2()
	{
		if(!empty($_GET['ghid_control'])){
	
		$criteria=new CDbCriteria;
		$criteria->compare('ptype',$this->ptype,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('cate',$this->cate);
		$criteria->compare('status',$this->status);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>15
			),
			'sort'=>array(
				'defaultOrder'=>'ctm desc',
			),
		));
		}
	}
	public function index()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		$criteria->compare('ptype',$this->ptype,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('cate',$this->cate);
		$criteria->compare('price_month',$this->price_month);
		$criteria->compare('price_year',$this->price_year);
		$criteria->compare('status',1);
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
				'pagination'=>array(
						'pageSize'=>14
				),
				'sort'=>array(
					'defaultOrder'=>'ctm desc', 
				),
		));
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Plugin the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
