<?php

/**
 * This is the model class for table "sys_attachment".
 *
 * The followings are the available columns in table 'sys_attachment':
 * @property string $id
 * @property string $filename
 * @property string $filepath
 * @property string $url
 * @property string $filesize
 * @property string $fileext
 * @property integer $isimage
 * @property integer $userid
 * @property string $ghid
 * @property string $ctm
 * @property string $uploadip
 * @property integer $status
 * @property string $authcode
 */
class SysAttachment extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sys_attachment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('filename, filepath', 'required'),
			array('isimage, userid, status', 'numerical', 'integerOnly'=>true),
			array('filename, ghid', 'length', 'max'=>50),
			array('filepath', 'length', 'max'=>200),
			array('url', 'length', 'max'=>255),
			array('filesize,imagesize, fileext, ctm', 'length', 'max'=>10),
			array('uploadip', 'length', 'max'=>15),
			array('authcode', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, filename, filepath,imagesize, url, filesize, fileext, isimage, userid, ghid, ctm, uploadip, status, authcode', 'safe', 'on'=>'search'),
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
			'id' => '附件ID',
			'filename' => '上传附件名称',
			'filepath' => '附件路径',
			'url' => 'URL 地址',
			'filesize' => '附件大小',
			'fileext' => '附件扩展名',
			'isimage' => '是否为图片 1为图片',
			'userid' => '上传用户ID',
			'ghid' => 'Ghid',
			'ctm' => '上传时间',
			'uploadip' => '上传ip',
			'status' => '附件使用状态',
			'authcode' => '附件路径MD5值',
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
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('filepath',$this->filepath,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('filesize',$this->filesize,true);
		$criteria->compare('fileext',$this->fileext,true);
		$criteria->compare('isimage',$this->isimage);
		$criteria->compare('userid',$this->userid);
		$criteria->compare('ghid',$this->ghid,true);
		$criteria->compare('ctm',$this->ctm,true);
		$criteria->compare('uploadip',$this->uploadip,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('authcode',$this->authcode,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	function getlist(){
		$criteria=new CDbCriteria;
		
		if(empty(gh()->ghid)){
			$criteria->compare('ghid','null');
		}else{
			$criteria->compare('ghid',gh()->ghid);
		}
		
		$criteria->compare('isimage',1);
		$criteria->addCondition('isimage=1');
		$criteria->order='ctm desc';
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>12
			),
		));

	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SysAttachment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
