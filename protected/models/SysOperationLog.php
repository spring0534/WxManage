<?php

/**
 * This is the model class for table "sys_operation_log".
 *
 * The followings are the available columns in table 'sys_operation_log':
 * @property integer $id
 * @property integer $uid
 * @property string $ghid
 * @property string $action
 * @property string $controller
 * @property string $module
 * @property string $message
 * @property integer $optime
 */
class SysOperationLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sys_operation_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, optime', 'numerical', 'integerOnly'=>true),
			array('ghid, action, controller, module', 'length', 'max'=>50),
			array('message', 'length', 'max'=>255),
			array('ip', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ip,uid, ghid, action, controller, module, message, optime', 'safe', 'on'=>'search'),
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
			'user'=>array(self::HAS_ONE,'SysUser',array('id'=>'uid'),'select'=>'id,username,nickname')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'uid' => '用户 ID',
			'ghid' => '公众号',
			'action' => '操作方法',
			'controller' => '操作模块',
			'module' => '操作组',
			'message' => '具体操作内容',
			'optime' => '操作时间',
			'ip' => '操作ip',
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
		$criteria->compare('uid',$this->uid);
		$criteria->compare('ghid',$this->ghid);
		$criteria->compare('action',$this->action,true);
		$criteria->compare('controller',$this->controller,true);
		$criteria->compare('module',$this->module,true);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('optime',$this->optime);
		$criteria->compare('ip',$this->ip,true);
		$criteria->with='user';
		$criteria->order='optime desc';
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>15,
			),
		));
	}
	public function index()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		//$criteria->compare('uid',gh()?$this->uid:user()->id);
		$criteria->compare('uid',user()->id);
		//$criteria->compare('ghid',gh()?gh()->ghid:'');
		$criteria->compare('action',$this->action,true);
		$criteria->compare('controller',$this->controller,true);
		$criteria->compare('module',$this->module,true);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('optime',$this->optime);
		$criteria->compare('ip',$this->ip,true);
		//$criteria->addCondition('uid='.user()->id);
		$criteria->with='user';
		$criteria->order='optime desc';
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
	 * @return SysOperationLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
