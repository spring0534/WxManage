<?php

/**
 * This is the model class for table "stat_report".
 *
 * The followings are the available columns in table 'stat_report':
 * @property integer $id
 * @property string $day
 * @property integer $aid
 * @property string $ghid
 * @property integer $pv
 * @property integer $uv
 * @property integer $cv
 * @property integer $ip
 * @property integer $s1
 * @property integer $s2
 * @property integer $s3
 * @property integer $s4
 * @property integer $sub
 * @property integer $unsub
 */
class StatReport extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stat_report';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('day, aid', 'required'),
			array('aid, pv, uv, cv, ip, s1, s2, s3, s4, sub, unsub', 'numerical', 'integerOnly'=>true),
			array('ghid', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, day, aid, ghid', 'safe', 'on'=>'search'),
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
			'day' => '时间',
			'aid' => '活动',
			'ghid' => 'Ghid',
			'pv' => '浏览量(pv)',
			'uv' => '独立访客(UV)',
			'cv' => '新独立访客',
			'ip' => 'ip数',
			's1' => '分享到好友',
			's2' => '分享到朋友圈',
			's3' => '分享到QQ',
			's4' => '分享到微博',
			'sub' => '新增关注数',
			'unsub' => '取消关注数',
			
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
		$criteria->compare('aid',$this->aid);
		$criteria->compare('ghid',gh()->ghid);
		if(!empty($_GET['StatReport']['day'][1]))$criteria->addCondition("day>='".$_GET['StatReport']['day'][1]."'");
		if(!empty($_GET['StatReport']['day'][2]))$criteria->addCondition("day<='".$_GET['StatReport']['day'][2]."'");
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>15
			),
		));
	}
	public function search_flow()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
	
		$criteria=new CDbCriteria;
		$criteria->compare('aid',$this->aid);
		$criteria->compare('ghid',gh()->ghid);
		
		if(!empty($_GET['StatReport']['day'][1]))$criteria->addCondition("day>='".$_GET['StatReport']['day'][1]."'");
		if(!empty($_GET['StatReport']['day'][2]))$criteria->addCondition("day<='".$_GET['StatReport']['day'][2]."'");
		if(empty($_GET['StatReport']['day'][1])&&empty($_GET['StatReport']['day'][2])){
			$criteria->addCondition("day>='".date('Y-m-d', strtotime("-7 day"))."'");
			//$criteria->addCondition("day<='".date('Y-m-d', strtotime("-1 day"))."'");
		}
		$criteria->select = 'day,sum(pv) pv,sum(uv) uv,sum(cv) cv,sum(ip) ip,sum(s1) s1,sum(s2) s2,sum(s3) s3,sum(s4) s4,sum(sub) sub,sum(unsub) unsub'; //代表了要查询的字段，默认select='*';
		
		$criteria->order = 'day ASC' ;//排序条件
		$criteria->group = 'day';
		//dump($criteria);exit;
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>150
			),
			'sort'=>array(
				'defaultOrder'=>'day ASC', //设置默认排序
			),
		));
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StatReport the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
