<?php

/**
 * This is the model class for table "wx_router_keyword".
 *
 * The followings are the available columns in table 'wx_router_keyword':
 * @property integer $id
 * @property string $ghid
 * @property string $keyword
 * @property integer $match_mode
 * @property integer $reply_type
 * @property integer $reply_id
 * @property integer $status
 * @property string $note
 * @property string $ctm
 * @property string $utm
 * @property integer $tenant_id
 * @property integer $uid
 * @property string $msg_type
 */
class WxRouterKeyword extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'wx_router_keyword';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ghid, keyword, reply_id', 'required'),
			array('match_mode, reply_type, reply_id, status, tenant_id, uid', 'numerical', 'integerOnly'=>true),
			array('ghid, msg_type', 'length', 'max'=>50),
			array('keyword', 'length', 'max'=>20),
			array('note', 'length', 'max'=>100),
			array('ctm, utm', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ghid, keyword, match_mode, reply_type, reply_id, status, note, ctm, utm, tenant_id, uid, msg_type', 'safe', 'on'=>'search'),
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
			'ghid' => '公众号ID',
			'keyword' => '关键词',
			'match_mode' => '匹配模式',
			'reply_type' => '回复方式',
			'reply_id' => '回复内容',//返回内容ID
			'status' => '状态',
			'note' => '备注',
			'ctm' => '创建时间',
			'utm' => '更新时间',
			'tenant_id' => '商户ID',
			'uid' => '操作人ID',
			'msg_type' => '消息类型',
		);
	}
	function getReplyByid($id,$rid){
		switch ($rid){
			case 1:

			    return WxMaterial::model()->findByAttributes(array('id'=>$id,'ghid'=>gh()->ghid))->title;
			    break;
			case 2:
			    return 'yyyy';
			    break;
		}
		return 'ssss';
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
		$criteria->compare('keyword',$this->keyword,true);
		$criteria->compare('match_mode',$this->match_mode);
		$criteria->compare('reply_type',$this->reply_type);
		$criteria->compare('reply_id',$this->reply_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('ctm',$this->ctm,true);
		$criteria->compare('utm',$this->utm,true);
		//$criteria->compare("id","<1");
		$criteria->compare('msg_type',$this->msg_type,true);
		$criteria->compare('ghid',gh()->ghid,true);
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
	 * @return WxRouterKeyword the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
