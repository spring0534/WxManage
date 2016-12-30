<?php

/**
 * This is the model class for table "redpack_task".
 *
 * The followings are the available columns in table 'redpack_task':
 * @property string $id
 * @property integer $aid
 * @property string $tb_order_no
 * @property string $openid
 * @property string $nickname
 * @property string $headimgurl
 * @property integer $amount
 * @property string $redpack_billno
 * @property integer $status
 * @property string $errmsg
 * @property string $ctm
 * @property string $utm
 */
class RedpackTask extends CActiveRecord
{
	public $result;  //审核结果
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'redpack_task';
	}
	
	public function getDbConnection() {
		return Yii::app()->db2;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('aid, tb_order_no, openid', 'required'),
			array('aid, amount, status', 'numerical', 'integerOnly'=>true),
			array('tb_order_no', 'length', 'max'=>30),
			array('openid, redpack_billno', 'length', 'max'=>50),
			array('nickname', 'length', 'max'=>20),
			array('headimgurl', 'length', 'max'=>200),
			array('errmsg', 'length', 'max'=>100),
			array('ctm, utm', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, aid, tb_order_no, openid, nickname, headimgurl, amount, redpack_billno, status, errmsg, ctm, utm', 'safe', 'on'=>'search'),
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
			'aid' => '活动aid',
			'tb_order_no' => '淘宝订单号',
			'openid' => '用户openid',
			'nickname' => '微信昵称',
			'headimgurl' => '微信头像',
			'amount' => '金额  单位：分',
			'redpack_billno' => '红包订单号',
			'status' => '状态 0-无效 1-等待审核 2-派发成功 3-派发失败',
			'errmsg' => '派发结果',
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
		$criteria->compare('id',$this->id,true);
//		$criteria->compare('aid',$this->aid);
		$criteria->compare('tb_order_no',$this->tb_order_no,true);
		$criteria->compare('openid',$this->openid,true);
		$criteria->compare('nickname',$this->nickname,true);
		$criteria->compare('headimgurl',$this->headimgurl,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('redpack_billno',$this->redpack_billno,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('errmsg',$this->errmsg,true);
		$criteria->compare('ctm',$this->ctm,true);
		$criteria->compare('utm',$this->utm,true);
        $criteria->addCondition("aid in (select aid from wxos_admin.activity where ghid='".gh()->ghid."')");
        if(!empty($this->status) && $this->status == 1){
            $criteria->order= 'ctm asc';
        }else{
            $criteria->order= 'ctm desc';
        }
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
// 			'pagination'=>array(
// 				'pageSize'=>15,
// 			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RedpackTask the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
