<?php
/**
* UserRegController.php
* ----------------------------------------------
* 版权所有 2014-2015
* ----------------------------------------------
* @date: 2015-3-24
*
*/
class UserRegController extends BaseGhController{

	public function actionView($id){
		$this->render('view', array(
			'model'=>$this->loadModel($id)
		));
	}
	/*public function actionCreate(){
		$model=new UserReg();

		// $this->performAjaxValidation($model);

		if (isset($_POST['UserReg'])){
			$model->attributes=$_POST['UserReg'];
			if ($model->save())
				$this->success('添加成功');
		}

		$this->render('create', array(
			'model'=>$model
		));
	}*/
	/*public function actionUpdate($id){
		$model=$this->loadModel($id);
		// $this->performAjaxValidation($model);
		if (isset($_POST['UserReg'])){
			$model->attributes=$_POST['UserReg'];
			if ($model->save())
				$this->success('操作成功');
		}

		$this->render('update', array(
			'model'=>$model
		));
	}*/
	public function actionDelete($id){
		$model=UserReg::model()->findByAttributes(array('id'=>$id,'ghid'=>gh()->ghid));
		$model->delete();
		// 如果是AJAX请求删除,请取消跳转
		if (!isset($_GET['ajax']))
			$this->success('操作成功');
	}
	/**
	 * 获取所有开通过的微应用
	 * @date: 2014-12-10
	 * @author : 佚名
	 * @return
	 *
	 */
	/*function getActList(){
		$criteria=new CDbCriteria();
		// $criteria->select='id,ptype';
		$criteria->with='plugin';
		$criteria->addCondition("t.ghid='".gh()->ghid."'"); // 公众号开通的
		return PluginGhUse::model()->findAll($criteria); // $params is not needed
	}*/

	/**
	 * 获取所有活动
	 * @date: 2014-12-10
	 * @author : 佚名
	 * @return
	 *
	 */
	function getActList(){
		 $criteria=new CDbCriteria();
		 // $criteria->select='id,ptype';
		 $criteria->with='plugin';
		 $criteria->addCondition("t.ghid='".gh()->ghid."'"); // 公众号开通的
		 return Activity::model()->findAll($criteria); // $params is not needed
	 }
	/**
	 * 批量删除
	 * @date: 2015-3-25
	 * @author: 佚名
	 * @param unknown $id
	 */
	public function actionDelAll(){
		$data=$_POST['data'];
		if(!empty($data)){
			foreach ((array)$data as $v){
				$model=UserReg::model()->findByAttributes(array('ghid'=>gh()->ghid,'id'=>$v));
				if($model){
					$model->delete();
				}
			}
			echo 'ok';exit;
		}


	}
	/**
	 * 更新状态
	 * @date: 2015-3-25
	 * @author: 佚名
	 */
	function actionUpdateC(){
		$id=intval($_POST['id']);
		$val=intval($_POST['value']);
		$model=UserReg::model()->findByAttributes(array('id'=>$id,'ghid'=>gh()->ghid));
		if($model){
			$model->status=$val;
			$model->save();
			echo 'ok';exit;
		}else{
			echo 'err';exit;
		}

	}
	/*public function actionIndex(){
		$dataProvider=new CActiveDataProvider('UserReg');
		$this->render('index', array(
			'dataProvider'=>$dataProvider
		));
	}*/
	public function actionAdmin(){
		if($_GET['export']){
			//ini_set('display_errors ', 1);
			/*error_reporting(E_ALL);*/
			set_time_limit (0);
			$model=new UserReg('search');
			$model->unsetAttributes();
			if (isset($_GET['UserReg']))
				$model->attributes=$_GET['UserReg'];
			$m=$model->search(200000);
			$data=$m->data;

			$count = count($data);
			if($count>10000){
				header("Pragma: public");
				header("Expires: 0");
				header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
				header("Content-Type:application/force-download");
				header("Content-Type:application/vnd.ms-execl");
				header("Content-Type:application/octet-stream");
				header("Content-Type:application/download");
				header('Content-Type: application/vnd.ms-excel');
				header('Cache-Control: max-age=0');
				header("Content-Disposition:attachment;filename=注册用户列表.csv");
				header("Content-Transfer-Encoding:binary");

				$fp = fopen('php://output', 'a');
				// 输出Excel列名信息
				$head = array('活动标题', '公众号', '公众号名称', '用户名', '电话号码', '微信昵称', '中奖级别', '兑奖SN码', '得分', '状态', '额外信息', '注册时间', '最后操作时间');
				foreach ($head as $i => $v) {
					// CSV的Excel支持GBK编码，一定要转换，否则乱码
					$head[$i] = iconv("UTF-8", "gb2312//IGNORE",$v);
				}
				// 将数据通过fputcsv写到文件句柄
				fputcsv($fp, $head);
				// 计数器
				$cnt = 0;
				// 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
				$limit = 100000;
				// 逐行取出数据，不浪费内存
				for($t=0;$t<$count;$t++) {
					$cnt ++;
					if ($limit == $cnt) { //刷新一下输出buffer，防止由于数据过多造成问题
						ob_flush();
						flush();
						$cnt = 0;
					}
					$row = $data[$t];
					$tpm[]=iconv("UTF-8", "gb2312//IGNORE",$row['act']->title);
					$tpm[]=iconv("UTF-8", "gb2312//IGNORE",$row['ghid']);
					$tpm[]=iconv("UTF-8", "gb2312//IGNORE",$row['ghinfo']->name);
					$tpm[]=iconv("UTF-8", "gb2312//IGNORE",$row['username']);
					$tpm[]=iconv("UTF-8", "gb2312//IGNORE",' '.$row['phone']."\t");
					$tpm[]=iconv("UTF-8", "gb2312//IGNORE",$row['wxuser']->nickname);
					$tpm[]=iconv("UTF-8", "gb2312//IGNORE",$row['prize']);
					$tpm[]=iconv("UTF-8", "gb2312//IGNORE",$row['sncode']);
					$tpm[]=iconv("UTF-8", "gb2312//IGNORE",' '.$row['score']);
					$tpm[]=iconv("UTF-8", "gb2312//IGNORE",$row['status']==1?'有效':($row['status']==2?'无效':'已兑奖'));
					$tpm[]=iconv("UTF-8", "gb2312//IGNORE",$row['ext_info']);
					$tpm[]=iconv("UTF-8", "gb2312//IGNORE",$row['ctm']."\t");
					$tpm[]=iconv("UTF-8", "gb2312//IGNORE",$row['utm']."\t");
					fputcsv($fp, $tpm);
					unset($row);unset($tpm);
				}
				unset($data);
				exit;
			}else{
				Yii::$enableIncludePath=false;
				Yii::import('ext.Excel.PHPExcel', 1);
				$objPHPExcel = new PHPExcel();
				// Set properties
				$objPHPExcel->getProperties()->setCreator("微营销系统");
				$objPHPExcel->getProperties()->setLastModifiedBy("微营销系统");
				$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX  Document");
				$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Document");
				$objPHPExcel->getProperties()->setDescription("这是微信营销系统导出的数据");

				// 添加数据
				$i=-1;$s=-1;$index=1;
				foreach ($data as $k=>$v){
					$i++;
					$sheet=floor($i/3000);//3000分一页
					if($sheet>$s){
						$s=$sheet;
						if($sheet>0){
							$objPHPExcel->createSheet();
						}
						$index=1;
						$objPHPExcel->setActiveSheetIndex($sheet);
						//标题
						$objPHPExcel->setActiveSheetIndex($sheet)
						->setCellValue('A1', '活动标题')
						->setCellValue('B1', '公众号')
						->setCellValue('C1', '公众号名称')
						->setCellValue('D1', '用户名')
						->setCellValue('E1', '电话号码')
						->setCellValue('F1', '微信昵称')
						->setCellValue('G1', '中奖级别')
						->setCellValue('H1', '兑奖SN码')
						->setCellValue('I1', '得分')
						->setCellValue('J1', '状态')
						->setCellValue('K1', '额外信息')
						->setCellValue('L1', '注册时间')
						->setCellValue('M1', '最后操作时间')	;
						// 重命名 sheet
						$objPHPExcel->getActiveSheet()->setTitle('注册用户第'.($sheet+1).'页');
						//电话设为文本
						$objPHPExcel->getActiveSheet()->getStyle('E')->getNumberFormat()
						->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
						$objPHPExcel->getActiveSheet()->getStyle('I')->getNumberFormat()
						->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
						$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') ->setWidth(40);
						$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') ->setAutoSize(true);
						$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') ->setWidth(30);
						$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') ->setWidth(10);
						$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') ->setAutoSize(true);
						$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') ->setWidth(20);
						$objPHPExcel -> getActiveSheet() -> getColumnDimension('L') ->setAutoSize(true);
						$objPHPExcel -> getActiveSheet() -> getColumnDimension('M') ->setAutoSize(true);
					}
					$index++;
					$objPHPExcel->setActiveSheetIndex($sheet)
					->setCellValue("A$index", $v['act']->title)
					->setCellValue("B$index", $v['ghid'])
					->setCellValue("C$index", $v['ghinfo']->name)
					->setCellValue("D$index", $v['username'])
					->setCellValue("E$index", $v['phone'])
					->setCellValue("F$index", $v['wxuser']->nickname)
					->setCellValue("G$index", $v['prize'])
					->setCellValue("H$index", $v['sncode'])
					->setCellValue("I$index", $v['score'])
					->setCellValue("J$index", $v['status']==1?'有效':($v['status']==2?'无效':'已兑奖'))//1-有效，2-无效,3-后续状态：已兑奖
					->setCellValue("K$index", $v['ext_info'])
					->setCellValue("L$index", $v['ctm'])
					->setCellValue("M$index", $v['utm'])	;

				}
				$objPHPExcel->setActiveSheetIndex(0);
				//保存为 Excel 2007 格式
				//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
				//保存为Excel 2005 格式
				$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
				//$objWriter->save(str_replace('.php', '.xls', __FILE__));保存到本地
				header("Pragma: public");
				header("Expires: 0");
				header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
				header("Content-Type:application/force-download");
				header("Content-Type:application/vnd.ms-execl");
				header("Content-Type:application/octet-stream");
				header("Content-Type:application/download");
				header("Content-Disposition:attachment;filename=注册用户列表.xls");
				header("Content-Transfer-Encoding:binary");
				$objWriter->save("php://output");
				Yii::$enableIncludePath=true;
			}
		}

		$model=new UserReg('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['UserReg']))
			$model->attributes=$_GET['UserReg'];
		$this->render('admin', array(
			'model'=>$model
		));
	}
	public function loadModel($id){
		$model=UserReg::model()->findByPk($id);
		if ($model===null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * AJAX验证
	 * @param UserReg $model
	 * 模型验证
	 */
	protected function performAjaxValidation($model){
		if (isset($_POST['ajax'])&&$_POST['ajax']==='user-reg-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
