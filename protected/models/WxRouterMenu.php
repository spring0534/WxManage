<?php

/**
 * This is the model class for table "wx_router_menu".
 *
 * The followings are the available columns in table 'wx_router_menu':
 * @property integer $id
 * @property string $ghid
 * @property string $name
 * @property integer $parent_id
 * @property integer $seq
 * @property string $menu_type
 * @property string $url
 * @property string $event_key
 * @property integer $reply_type
 * @property integer $reply_id
 * @property integer $status
 * @property string $note
 * @property string $ctm
 * @property string $utm
 */
class WxRouterMenu extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'wx_router_menu';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ghid, ctm,parent_id', 'required'),
			array('parent_id, seq, reply_type, reply_id, status', 'numerical', 'integerOnly'=>true),
			array('ghid', 'length', 'max'=>50),
			array('name', 'length', 'max'=>20),
			array('menu_type', 'length', 'max'=>10),
			array('url', 'length', 'max'=>200),
			array('event_key', 'length', 'max'=>30),
			array('note', 'length', 'max'=>100),
			array('utm', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ghid, name, parent_id, seq, menu_type, url, event_key, reply_type, reply_id, status, note, ctm, utm', 'safe', 'on'=>'search'),
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
			'id' => '主键id',
			'ghid' => '公众号ID。用户只能使用自己ghid的或者公共的。公共的此字段值为public',
			'name' => '菜单名字',
			'parent_id' => '父菜单:0为一级菜单',
			'seq' => '顺序',
			'menu_type' => '菜单类型:{{select;view-访问网页,click-点击事件}} ',
			'url' => '网页url',
			'event_key' => '事件key',
			'reply_type' => '回复方式:{{select;1-回复素材库的内容,2-第三方接口}}',
			'reply_id' => '返回内容ID',
			'status' => '状态:{{select;1-有效,0-无效}} ',
			'note' => '备注',
			'ctm' => '创建时间',
			'utm' => '更新时间',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('seq',$this->seq);
		$criteria->compare('menu_type',$this->menu_type,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('event_key',$this->event_key,true);
		$criteria->compare('reply_type',$this->reply_type);
		$criteria->compare('reply_id',$this->reply_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('note',$this->note,true);
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
	 * @return WxRouterMenu the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
