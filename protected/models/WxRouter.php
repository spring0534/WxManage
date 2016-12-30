<?php

/**
 * This is the model class for table "wx_router".
 *
 * The followings are the available columns in table 'wx_router':
 * @property integer $id
 * @property string $ghid
 * @property string $msg_type
 * @property string $event
 * @property string $event_key
 * @property string $keyword
 * @property integer $match_mode
 * @property integer $reply_type
 * @property integer $reply_id
 * @property integer $status
 * @property string $ctm
 * @property string $utm
 * @property string $note
 * @property integer $operator_uid
 */
class WxRouter extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'wx_router';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ghid, msg_type, reply_id, ctm', 'required'),
			array('match_mode, reply_type, reply_id, status, operator_uid', 'numerical', 'integerOnly'=>true),
			array('ghid, msg_type', 'length', 'max'=>50),
			array('event, keyword', 'length', 'max'=>20),
			array('event_key', 'length', 'max'=>30),
			array('note', 'length', 'max'=>100),
			array('utm', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ghid, msg_type, event, event_key, keyword, match_mode, reply_type, reply_id, status, ctm, utm, note, operator_uid', 'safe', 'on'=>'search'),
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
			'ghid' => '公众号ID。用户只能使用自己ghid的或者公共的。公共的此字段值为public',
			'msg_type' => 'event事件 text文本 image图片 voice语音 video视频 location地理位置 link链接',
			'event' => 'subscribe订阅 unsubscribe取消订阅 CLICK自定义菜单',
			'event_key' => 'Event Key',
			'keyword' => '单个关键词',
			'match_mode' => '匹配模式 1.完全匹配 2.包含匹配 3.雀圣匹配',
			'reply_type' => '1回复素材库的内容2透传到其他第三方接口并返回（含精准分众网络和乐享等）3活动微应用处理',
			'reply_id' => '返回内容ID',
			'status' => '0.无效 1.有效 ',
			'ctm' => '创建时间',
			'utm' => 'Utm',
			'note' => '备注',
			'operator_uid' => '操作人ID',
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
		$criteria->compare('msg_type',$this->msg_type,true);
		$criteria->compare('event',$this->event,true);
		$criteria->compare('event_key',$this->event_key,true);
		$criteria->compare('keyword',$this->keyword,true);
		$criteria->compare('match_mode',$this->match_mode);
		$criteria->compare('reply_type',$this->reply_type);
		$criteria->compare('reply_id',$this->reply_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('ctm',$this->ctm,true);
		$criteria->compare('utm',$this->utm,true);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('operator_uid',$this->operator_uid);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WxRouter the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
