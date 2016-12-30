<?php

/**
* MpPageController.php
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2015-1-26
* 
*/
class MpPageController extends BController{
	public function actionIndex($mid, $id){
		$mid=intval($mid);
		$id=intval($id);
		if (empty($id)||empty($mid))
			$this->error('该页面不存在或者已经删除！', '', 100000);
		$wxmaterial=WxMaterial::model()->findByPk($mid);
		if (empty($wxmaterial))
			$this->error('该页面不存在或者已经删除！', '', 100000);
		$content=json_decode($wxmaterial->content, true);
		if (is_array($content)){
			if (isset($content[0]['pic'])){
				foreach ($content as $k=>$v){
					// 跳转到内容
					if ($v['onclick']==3&&$v['did']==$id){
						$dao=$v;
						break;
					}
				}
			}
		}
		if (empty($dao))
			$this->error('该页面不存在或者已经删除！', '', 100000);
		$pageContent=WxMaterialDetail::model()->findByPk($id);
		$this->render('index', array(
			'page'=>$pageContent, 
			'dao'=>$dao,
			'gh'=>SysUserGh::model()->findByAttributes(array('ghid'=>$wxmaterial->ghid))
		));
	}
}
