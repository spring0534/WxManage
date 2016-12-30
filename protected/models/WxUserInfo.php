<?php

/**
 * This is the model class for table "sys_wxuser".
 *
 * The followings are the available columns in table 'sys_wxuser':
 * @property integer $id
 * @property string $openid
 * @property string $ghid
 * @property string $srcOpenid
 * @property string $nickname
 * @property string $sex
 * @property string $province
 * @property string $city
 * @property string $headimgurl
 * @property string $privilege
 * @property string $accessToken
 * @property string $refreshToken
 * @property string $scope
 * @property string $ctm
 * @property string $utm
 * @property string $expires
 * @property string $ua
 * @property string $channel
 * @property integer $subscribe
 */
class WxUserInfo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sys_wxuser';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('openid', 'required'),
			array('subscribe', 'numerical', 'integerOnly'=>true),
			array('openid, ghid, srcOpenid, nickname, province, city, scope, channel', 'length', 'max'=>50),
			array('sex', 'length', 'max'=>2),
			array('headimgurl, accessToken, refreshToken', 'length', 'max'=>200),
			array('privilege', 'length', 'max'=>100),
			array('ua', 'length', 'max'=>500),
			array('ctm, utm, expires', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, openid, ghid, srcOpenid, nickname, sex, province, city, headimgurl, privilege, accessToken, refreshToken, scope, ctm, utm, expires, ua, channel, subscribe', 'safe', 'on'=>'search'),
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
			'openid' => 'Openid',
			'ghid' => '公众号ID',
			'srcOpenid' => '产生记录时活动公众号的openid',
			'nickname' => 'Nickname',
			'sex' => 'Sex',
			'province' => 'Province',
			'city' => 'City',
			'headimgurl' => 'Headimgurl',
			'privilege' => 'Privilege',
			'accessToken' => 'Access Token',
			'refreshToken' => 'Refresh Token',
			'scope' => 'Scope',
			'ctm' => 'Ctm',
			'utm' => 'Utm',
			'expires' => 'Expires',
			'ua' => 'Ua',
			'channel' => '来源渠道',
			'subscribe' => '用户是否订阅该公众号。0未订阅，1已订阅，2已取消',
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
		$criteria->compare('openid',$this->openid,true);
		$criteria->compare('ghid',$this->ghid,true);
		$criteria->compare('srcOpenid',$this->srcOpenid,true);
		$criteria->compare('nickname',$this->nickname,true);
		$criteria->compare('sex',$this->sex,true);
		$criteria->compare('province',$this->province,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('headimgurl',$this->headimgurl,true);
		$criteria->compare('privilege',$this->privilege,true);
		$criteria->compare('accessToken',$this->accessToken,true);
		$criteria->compare('refreshToken',$this->refreshToken,true);
		$criteria->compare('scope',$this->scope,true);
		$criteria->compare('ctm',$this->ctm,true);
		$criteria->compare('utm',$this->utm,true);
		$criteria->compare('expires',$this->expires,true);
		$criteria->compare('ua',$this->ua,true);
		$criteria->compare('channel',$this->channel,true);
		$criteria->compare('subscribe',$this->subscribe);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WxUserInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
