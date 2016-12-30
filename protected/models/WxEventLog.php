<?php

/**
 * This is the model class for table "wx_event_log".
 *
 * The followings are the available columns in table 'wx_event_log':
 * @property integer $id
 * @property string $wx_id
 * @property string $wx_ghid
 * @property string $keyword
 * @property integer $category
 * @property string $item
 * @property string $content
 * @property string $tm
 */
class WxEventLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'wx_event_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('wx_id, wx_ghid, keyword', 'length', 'max'=>50),
			array('category', 'length', 'max'=>50),
			array('item, content, tm', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, wx_id, wx_ghid, keyword, category, item, content, tm', 'safe', 'on'=>'search'),
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
			'id' => '主键ID',
			'wx_id' => '微信号ID',
			'wx_ghid' => '微信公众ID',
			'keyword' => '关键词',
			'category' => '类型 ',
			'item' => '内容项',
			'content' => '回复文本内容',
			'tm' => '创建时间',
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
		$criteria->compare('wx_id',$this->wx_id,true);
		$criteria->compare('wx_ghid',$this->wx_ghid,true);
		$criteria->compare('keyword',$this->keyword,true);
		$criteria->compare('category',$this->category);
		$criteria->compare('item',$this->item,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('tm',$this->tm,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>15
			),
			'sort'=>array(
				'defaultOrder'=>'tm DESC', //设置默认排序
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WxEventLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
