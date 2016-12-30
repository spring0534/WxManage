<?php

/**
* AttachmentsController.php
* ----------------------------------------------
* 版权所有 2014-2015
* ----------------------------------------------
* @date: 2014-12-8
*
*/
class AttachmentsController extends Controller{
	public $layout='main';
	function assets(){
		return __PUBLIC__;
	}
	function actionIndex(){
	}

	/**
	 * 调出文件上传页面
	 * @date: 2014-12-9
	 * @author : 佚名
	 */
	public function ActionSwfupload(){
		// 上传个数,允许上传的文件类型,是否允许从已上传中选择,图片高度,图片高度,是否添加水印1是
		$args=I('get.args');
		// 具体配置参数
		$info=explode(",", $args);
		// 参数补充完整
		$gconfig=''; // get_websetup_config();
		$gconfig["attach_maxsize"]="4";
		$gconfig["attach_allowext"]="jpg|jpeg|gif|png|doc|docx|rar|zip|swf|mp3|xls|xlsx|ico";
		if (empty($info[1])){
			$info[1]=$gconfig['attach_allowext'];
		}

		if (empty($info[6])){
			$info[6]=$gconfig['attach_maxsize'];
		}

		$this->render('swfupload', array(
			"initupload"=>initupload($info),
			"file_types"=>implode(",", explode("|", $info[1])),
			"file_size_limit"=>intval($info[6])*1024,
			"file_upload_limit"=>(int) $info[0],
			"thumb_div"=>$info[7],
			"online_div"=>$info[8],
			"res_div"=>$info[9],
			"watermark_enable"=>(int) $info[5]
		));
	}

	/**
	 * 加载图片库
	 * @date: 2014-12-8
	 * @author : 佚名
	 */
	public function actionPublic_album_load(){
		$filename=I('get.filename', '', '');
		$args=I('get.args', '', '');
		$args=explode(",", $args);
		// 搜索暂时没时间弄
		/*
		 * empty($filename) ? : $where['filename']=array(
		 * 'like',
		 * '%'.$filename.'%'
		 * );
		 * $uploadtime=I('get.uploadtime', '', '');
		 * if (!empty($uploadtime)){
		 * $start_uploadtime=strtotime($uploadtime.' 00:00:00');
		 * $stop_uploadtime=strtotime($uploadtime.' 23:59:59');
		 * if ($start_uploadtime){
		 * $where['uploadtime']=array(
		 * 'EGT',
		 * $start_uploadtime
		 * );
		 * }
		 * if ($stop_uploadtime){
		 * $where['uploadtime']=array(
		 * array(
		 * 'EGT',
		 * $start_uploadtime
		 * ),
		 * array(
		 * 'ELT',
		 * $stop_uploadtime
		 * ),
		 * 'and'
		 * );
		 * }
		 * }
		 */
		// 启用分页
		$model=new SysAttachment('getlist');
		$model->unsetAttributes(); // clear any default values
		$m=$model->getlist();
		$data=$m->data;
		$pages['pages']=$m->getPagination();
		$this->render('showthumbs', array(
			'data'=>$data,
			'pages'=>$pages,
			'file_upload_limit'=>$args[0]
		));
	}
	function actionPublic_wxm_load(){

		exit('未提供');
	}
}