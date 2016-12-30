<?php

/**
 * This is the model class for table "wx_spread_qrcode".
 *
 * The followings are the available columns in table 'wx_spread_qrcode':
 * @property integer $id
 * @property string $ghid
 * @property integer $qtype
 * @property string $name
 * @property integer $reply_id
 * @property integer $scene_id
 * @property string $expire
 * @property string $ctm
 * @property integer $uid
 */
class WxSpreadQrcode extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'wx_spread_qrcode';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ghid, name, scene_id', 'required'),
			array('qtype, reply_id, scene_id, uid,qcount,ucount', 'numerical', 'integerOnly'=>true),
			array('ghid, name', 'length', 'max'=>50),
			array('url', 'length', 'max'=>200),
			array('ticket', 'length', 'max'=>255),
			
			array('expire, ctm', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ghid, qtype, name, reply_id, scene_id, expire, ctm, uid', 'safe', 'on'=>'search'),
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
			'wxm'=>array(self::HAS_ONE,'WxMaterial',array('id'=>'reply_id'),'select'=>'title')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'ghid' => '所属公众号',
			'qtype' => '二给码类型',// 1临时2永久
			'name' => '推广名称',
			'reply_id' => '触发回复',// 0无回复，其它则是从素材表中的id
			'scene_id' => 'Scene',
			'expire' => '到期时间',
			'ctm' => '创建时间',
			'uid' => '操作人ID',
			'url'=>'二维码地址',
			'ticket'=>'ticket',
			'qcount'=>'扫描次数',
			'ucount'=>'扫描人数'
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
		$criteria->compare('ghid',gh()->ghid);
		$criteria->compare('qtype',$this->qtype);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('reply_id',$this->reply_id);
		$criteria->compare('scene_id',$this->scene_id);
		$criteria->compare('expire',$this->expire,true);
		$criteria->compare('ctm',$this->ctm,true);
		$criteria->compare('uid',$this->uid);

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
	 * @return WxSpreadQrcode the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
