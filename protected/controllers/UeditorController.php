<?php
/**百度编辑器文件上传
* UeditorController.php
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2014-11-26
* 
*/
class UeditorController extends BaseController {
	function actionImageUp() {
		// 上传图片框中的描述表单名称，
		$title = htmlspecialchars($_POST['pictitle'], ENT_QUOTES);
		$path = htmlspecialchars($_POST['dir'], ENT_QUOTES);
		// 上传配置
		$config = array(
			"allowFiles" => array(
				"gif",
				"png",
				"jpg",
				"jpeg",
				"bmp"
			)
		);
		$isthumb = $_POST["isthumb"];
		$width = $_POST["width"];
		$path = $_POST["path"];
		$height = $_POST["height"];
		
		//$configs = get_websetup_config();
		$watermark_enable = $configs['watermark_enable'];
		if (! empty($watermark_enable)) {
			$iswater = true;
		} else {
			$iswater = false;
		}

		$info = upLoad('', $config["allowFiles"], false);
		if (is_array($info)) {
			echo "{'url':'" . $info[0]['savename'] . "','title':'" . $title . "','original':'" . $info["originalName"] . "','state':'SUCCESS'}";
		} else {
			echo "{'url':'" . $info["url"] . "','title':'" . $title . "','original':'" . $info["originalName"] . "','state':'" . $info . "'}";
		}
	}
	function actionFileUp() {
		Yii::import('ext.Uploader');
		$uppath = UPLOAD_PATH . gh()->ghid . "/"; // 设置附件上传目录
		$config = array(
			"savePath" => $uppath, // 保存路径
			"allowFiles" => array(
				".rar",
				".doc",
				".docx",
				".zip",
				".pdf",
				".txt",
				".swf",
				".wmv"
			), // 文件允许格式
			"maxSize" => 100000
		); // 文件大小限制，单位KB
		$up = new Uploader("upfile", $config);
		$info = $up->getFileInfo();
		echo '{"url":"' . $info["url"] . '","fileType":"' . $info["type"] . '","original":"' . $info["originalName"] . '","state":"' . $info["state"] . '"}';
	}
}