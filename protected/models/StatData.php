<?php

/**
 * This is the model class for table "stat_data".
 *
 * The followings are the available columns in table 'stat_data':
 * @property integer $id
 * @property integer $aid
 * @property string $wxid
 * @property string $fromWxid
 * @property string $ghid
 * @property integer $cid
 * @property integer $tid
 * @property string $title
 * @property string $url
 * @property string $ip
 * @property integer $pv
 * @property string $screen
 * @property string $referrer
 * @property string $brv
 * @property string $lg
 * @property string $os
 * @property string $osv
 * @property string $mobile
 * @property string $mobileName
 * @property integer $srcType
 * @property integer $logType
 * @property integer $shareType
 * @property string $shareUrl
 * @property string $country
 * @property string $region
 * @property string $city
 * @property string $area
 * @property string $isp
 * @property string $rtime
 * @property string $ua
 */
class StatData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stat_data';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('wxid, ghid, rtime', 'required'),
			array('aid, cid, tid, pv, srcType, logType, shareType', 'numerical', 'integerOnly'=>true),
			array('wxid, fromWxid, title', 'length', 'max'=>200),
			array('ghid, mobile, mobileName,brv', 'length', 'max'=>50),
			array('url, referrer, shareUrl,realUrl', 'length', 'max'=>255),
			array('ip, lg, os, osv', 'length', 'max'=>15),
			array('screen,brvsub', 'length', 'max'=>12),
			array('country, region, city, area, isp,netType', 'length', 'max'=>20),
			array('ua', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, aid, wxid, fromWxid, ghid, cid, tid, title, url, ip, pv, screen, referrer, brv, lg, os, osv, mobile, mobileName, srcType, logType, shareType, shareUrl, country, region, city, area, isp, rtime, ua', 'safe', 'on'=>'search'),
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
			'wxuser'=>array(self::HAS_ONE,'WxUserInfo',array('openid'=>'wxid','ghid'=>'ghid'),'select'=>'nickname,headimgurl'),
			'wxuserReg'=>array(self::HAS_ONE,'UserReg',array('wxid'=>'wxid','ghid'=>'ghid'),'select'=>'username,phone'),
			
			
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'aid' => '活动id',
			'wxid' => '对应公众号的用户ID',
			'fromWxid' => '来源微信ID',
			'ghid' => '公众号',
			'cid' => '是否新访客',
			'tid' => '网站标识',
			'title' => '页面标题',
			'url' => '面页URL',
			'ip' => 'ip地址',
			'pv' => 'PV',
			'screen' => '屏幕辨率分',
			'referrer' => '来源URL',
			'brv' => '微信版本、览浏器版本',
			'brvsub'=>'微信版本',
			'lg' => '浏览器语言',
			'netType'=>'网络类型',
			'os' => '操作系统',
			'osv' => '系统版本',//,仅记录安卓
			'mobile' => '手机型号',
			'mobileName' => '手机名称',
			'srcType' => '来源统计',//1从朋友圈2从好友3从群组
			'logType' => '统计类型',//0页面统计1分享统计
			'shareType' => '分享类型',//1分享到好友 2分享到朋友圈3分享到QQ4分享到微博
			'shareUrl' => '分享的URL',
			'country' => '国家',
			'region' => '省',
			'city' => '城市',
			'area' => '区域',
			'isp' => '网络运营商',
			'rtime' => '访问时间',
			'ua' => 'Ua',
			'realUrl'=>'真实URL',
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
		$criteria->compare('aid',$this->aid);
		$criteria->compare('wxid',$this->wxid,true);
		$criteria->compare('fromWxid',$this->fromWxid,true);
		$criteria->compare('ghid',$this->ghid,true);
		$criteria->compare('cid',$this->cid);
		$criteria->compare('tid',$this->tid);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('pv',$this->pv);
		$criteria->compare('screen',$this->screen,true);
		$criteria->compare('referrer',$this->referrer,true);
		$criteria->compare('brv',$this->brv,true);
		$criteria->compare('lg',$this->lg,true);
		$criteria->compare('os',$this->os,true);
		$criteria->compare('osv',$this->osv,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('mobileName',$this->mobileName,true);
		$criteria->compare('srcType',$this->srcType);
		$criteria->compare('logType',$this->logType);
		$criteria->compare('shareType',$this->shareType);
		$criteria->compare('shareUrl',$this->shareUrl,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('region',$this->region,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('area',$this->area,true);
		$criteria->compare('isp',$this->isp,true);
		$criteria->compare('rtime',$this->rtime,true);
		$criteria->compare('ua',$this->ua,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public function search_detail($limit=15)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
	
		$criteria=new CDbCriteria;
		$criteria->compare('aid',$this->aid);
		$criteria->compare('ghid',gh()->ghid);
		if(!empty($_GET['StatData']['rtime'][1]))$criteria->addCondition("rtime>='".$_GET['StatData']['rtime'][1]."'");
		if(!empty($_GET['StatData']['rtime'][2]))$criteria->addCondition("rtime<='".$_GET['StatData']['rtime'][2]."'");
		if(empty($_GET['StatData']['rtime'][1])&&empty($_GET['StatData']['rtime'][2])){
			$criteria->addCondition("rtime>='".date('Y-m-d', strtotime("-2 day"))."'");
			//$criteria->addCondition("rtime<='".date('Y-m-d')."'");
		}
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>$limit
			),
			'sort'=>array(
				'defaultOrder'=>'rtime DESC', //设置默认排序
			),
		));
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StatData the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	protected function beforeSave(){
		if( strtolower($this->os)=='iphone'){
			switch ($this->screen){
				case '320x480':
					$this->screen='640x960';
					$this->mobile='iPhone 4/4S';
					break;
				case '320x568':
					$this->screen='640x1136';
					$this->mobile='iPhone 5/5S/5C';
					break;
				case '375x667':
					$this->screen='750x1334';
					$this->mobile='iPhone 6';
					break;
				case '414x736':
					$this->screen='1242x2208';
					$this->mobile='iPhone 6Plus';
					break;
			}
		}
		
		return parent::beforeSave();
	}
}
