<?php

/**
 * This is the model class for table "wx_forward".
 *
 * The followings are the available columns in table 'wx_forward':
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property string $keyword
 * @property integer $match_mode
 * @property string $url
 * @property string $token
 * @property integer $cache_minutes
 * @property string $ghid
 * @property integer $status
 * @property string $note
 * @property string $ctm
 * @property string $utm
 * @property integer $tenant_id
 * @property integer $uid
 */
class WxForward extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'wx_forward';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, url, token, ghid', 'required'),
			array('type, match_mode, cache_minutes, status, tenant_id, uid', 'numerical', 'integerOnly'=>true),
			array('name, ghid', 'length', 'max'=>50),
			array('keyword', 'length', 'max'=>20),
			array('url, token, note', 'length', 'max'=>100),
			array('ctm, utm', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, type, keyword, match_mode, url, token, cache_minutes, ghid, status, note, ctm, utm, tenant_id, uid', 'safe', 'on'=>'search'),
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
			'name' => '接口名字',
			'type' => '类型',//:{{select;1-免费微应用(固定关键字),2-第三方微应用}}
			'keyword' => '关键词',
			'match_mode' => '匹配模式',//:{{select;1-完全匹配,2-包含匹配}}
			'url' => '微信接口url',
			'token' => '微信接口token',
			'cache_minutes' => '缓存分钟数',
			'ghid' => '所属公众号',//。用户只能使用自己ghid的或者公共的。公共的此字段值为public
			'status' => '状态',//:{{select;1-有效,0-无效}}
			'note' => '备注',//备注{{textarea}}
			'ctm' => '创建时间',
			'utm' => '更新时间',
			'tenant_id' => '商户ID',//商户ID。值为0表示公共
			'uid' => '操作人ID',
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

		$criteria->compare('name',$this->name,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('keyword',$this->keyword,true);
		$criteria->compare('match_mode',$this->match_mode);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('ghid',gh()->ghid,true);
		$criteria->addCondition("ghid='public'",'OR');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>15,
			),
		));
	}
	public static function getMylist(){
		$criteria=new CDbCriteria;
		$criteria->compare('status',1);
		$criteria->compare('ghid',gh()->ghid,true);
		$criteria->addCondition("ghid='public'",'OR');
		
		return WxForward::model()->findAll($criteria);
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WxForward the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
