<?php

/**SN码管理(微应用公共模块)
* CommonSnController.php
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2014-12-16
* 
*/
class CommonSnController extends BaseController{
	public function actionView($id){
		$this->render('view', array(
			'model'=>$this->loadModel($id)
		));
	}
	public function actionCreate(){
		$model=new CommonSn();
		if (isset($_POST['CommonSn'])){
			$model->attributes=$_POST['CommonSn'];
			if (Activity::model()->findByPk($model->aid)->ghid==gh()->ghid){
				if (!CommonSn::model()->findByAttributes(array(
					'aid'=>$model->aid,
					'sncode'=>$model->sncode
				))){
					if ($model->save()){
						$this->success('添加成功',U('&admin',array('aid'=>$model->aid)));
					}else{
						$this->error('添加失败');
					}
				}else{
					$this->error('已存在房价SN码，SN码不允许重复！');
				}
				
			}
		}
		
		$this->render('create', array(
			'model'=>$model, 
			'aid'=>intval($_GET['aid'])
		));
	}
	public function actionUpdate($id){
		/* $model=$this->loadModel($id);
		if (isset($_POST['CommonSn'])){
			$model->attributes=$_POST['CommonSn'];
			if (Activity::model()->findByPk($model->aid)->ghid==gh()->ghid){
				
				if ($model->save())
					$this->success('操作成功');
			}
		}
		if (Activity::model()->findByPk($this->loadModel($id)->aid)->ghid==gh()->ghid){
			$this->render('update', array(
				'model'=>$model
			));
		} */
	}
	/**
	 * 导入SN码
	 * @date: 2014-12-16
	 * @author : 佚名
	 */
	function actionImportExcel(){
		Yii::import('ext.UploadFile');
		$upload=new UploadFile(); // 实例化上传类
		$upload->maxSize=3145728; // 设置附件上传大小
		$upload->allowExts=array(
			'xls', 
			'xlsx'
		); // 设置附件上传类型
		$upload->saveRule='time';
		$upload->savePath=ROOT_PATH.'/uploads/'.base64_encode(gh()->ghid).'/files/'; // 设置附件上传目录
		if (!$upload->upload()){ // 上传错误提示错误信息
			$this->error($upload->getErrorMsg());
		}else{ // 上传成功 获取上传文件信息
			$info=$upload->getUploadFileInfo();
			// 操作Excel
			$filepath=ROOT_PATH.'/uploads/'.base64_encode(gh()->ghid).'/files/'.$info[0]['savename'];
			Yii::$enableIncludePath=false;
			Yii::import('ext.Excel.PHPExcel', 1);
			$phpexcel=new PHPExcel();
			try{
				$excelReader=PHPExcel_IOFactory::createReader('Excel5');
				$phpexcel=$excelReader->load($filepath)->getSheet(0); // 载入文件并获取第一个sheet
			}catch (Exception $e){
				$this->error('读取文件失败，请联系管理员');
			}
			
			$total_line=$phpexcel->getHighestRow(); // 取得一共有多少行
			$total_column=$phpexcel->getHighestColumn();
			header("Content-type: text/html; charset=utf-8");
			$aid=intval($_POST['aid']);
			$success=$error=$notadd=0;
			/*set_time_limit (0);
			for ($row=2; $row<=$total_line; $row++){
				$data=array();
				for ($column='A'; $column<=$total_column; $column++){
					$data[]=trim($phpexcel->getCell($column.$row)->getValue());
				}
				
				$model=new SysUser();
				$model->username='chinanet_'.$data[0];
				$model->nickname=$data[2];
				$model->company=$data[4];
				$model->password=$model->hashPassword('chinanet_888');
				$model->pid=32;
				$model->ghid=$data[8];
				$model->groupid=4;
				
				
				if ($model->save()){
					$user=SysUser::model()->findByPk($model->attributes['id']);
					$model2=new SysUserGh();
					//array(0=>'普通订阅号',1=>'认证订阅号',2=>'普通服务号',3=>'认证服务号')
					
						
						$model2->ghid=$data[8];
						$model2->name=$data[4];
						$model2->company=$data[2];
						$model2->wxh=$data[5];
							
						if($data[6]=='订阅号'){
							if($data[7]=='是'){
								$model2->type=1;
							}else{
								$model2->type=0;
							}
						}else{
							if($data[7]=='是'){
								$model2->type=3;
							}else{
								$model2->type=2;
							}
						}
							
							
						$model2->desc='厅店运营负责人姓名:'.$data[12].'厅店运营负责人联系电话'.$data[13];
						$model2->notes=$data[1];
						$model2->appid=$data[9];
						$model2->appsecret=$data[10];
						$model2->oauth=1;
						$model2->jsapi=1;
						if (!SysUserGh::model()->find("ghid='".$model->ghid."'")){
							$model2->tenant_id=$model->attributes['id'];
							$model2->ctm=date('Y-m-d H:i:s');
							if ($model2->save()){
								$success++;
							}else{
								$error++;
							}
						}
					
				}else{						
					$error++;
				}
			
			}
			Yii::$enableIncludePath=true;
			$this->success('导入完成！成功导入'.$success.'条，错误'.$error.'条,略过'.$notadd.'条', U('&admin', array(
				'aid'=>$aid
			)), 5000);
			*/
			if (!empty($aid)&&Activity::model()->findByPk($aid)->ghid==gh()->ghid){
				for ($row=2; $row<=$total_line; $row++){
					$data=array();
					for ($column='A'; $column<=$total_column; $column++){
						$data[]=trim($phpexcel->getCell($column.$row)->getValue());
					}
					$model=new CommonSn();
					$model->aid=$aid;
					$model->sncode=$data[0];
					$model->snpwd=$data[1];
					$model->grade=intval($data[2]);
					$model->note=$data[3];
					$model->status=1;
					if (!CommonSn::model()->findByAttributes(array(
						'aid'=>$model->aid, 
						'sncode'=>$model->sncode
					))){
						if ($model->save()){
							$success++;
						}else{
							$error++;
						}
					}else{
						$notadd++;
					}
				}
				Yii::$enableIncludePath=true;
				$this->success('导入完成！成功导入'.$success.'条，错误'.$error.'条,略过'.$notadd.'条', U('&admin', array(
					'aid'=>$aid
				)), 5000);
			}else{
				$this->error('非法操作！');
			}
		}
	}
	public function actionDelete($id){
		if (Activity::model()->findByPk($this->loadModel($id)->aid)->ghid==gh()->ghid){
			$this->loadModel($id)->delete();
			if (!isset($_GET['ajax'])){
				$this->success('操作成功');
			}else{
				$this->error('删除失败！');
			}
		}
	}
	/**
	 * 批量删除
	 * @date: 2014-12-15
	 * @author : 佚名
	 * @throws CHttpException
	 */
	public function actionDelall(){
		if (Yii::app()->request->isPostRequest){
			if (Activity::model()->findByPk(intval($_POST['aid']))->ghid==gh()->ghid){
				$criteria=new CDbCriteria();
				$criteria->addInCondition('id', $_POST['selectdel']);
				$criteria->addCondition('aid='.$_POST['aid']);
				CommonSn::model()->deleteAll($criteria); // Words换成你的模型
				if (isset(Yii::app()->request->isAjaxRequest)){
					echo 'ok';
				}else
					$this->success('删除成功');
			}
		}else
			throw new CHttpException(400, 'Invalid request. Please do not repeat this request again. no');
	}
	public function actionIndex(){
		$dataProvider=new CActiveDataProvider('CommonSn');
		$this->render('index', array(
			'dataProvider'=>$dataProvider
		));
	}
	public function actionAdmin($aid){
		$aid=intval($_GET['aid']);
		if (empty($aid))
			$this->error('参数错误！');
		$m=Activity::model()->findByPk($aid);
		if ($m&&$m->ghid==gh()->ghid){
			$model=new CommonSn('search');
			$model->unsetAttributes();
			if (isset($_GET['CommonSn']))
				$model->attributes=$_GET['CommonSn'];
			$this->render('admin', array(
				'model'=>$model, 
				'aid'=>$aid
			));
		}
	}
	public function loadModel($id){
		$model=CommonSn::model()->findByPk($id);
		if ($model===null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}
	
	/**
	 * AJAX验证
	 * @param CommonSn $model
	 * 模型验证
	 */
	protected function performAjaxValidation($model){
		if (isset($_POST['ajax'])&&$_POST['ajax']==='common-sn-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
