<?php

/**
* WCButtonColumn.php
* ----------------------------------------------
* 版权所有 2014-2015
* ----------------------------------------------
* @date: 2014-12-15
*
*/
class WCMButtonColumn extends CButtonColumn{
	public $buttons=array(
		'link'=>array(
			'imageUrl'=>null,
			'label'=>'<i class="glyph-icon icon-paperclip"></i>链接',
			'url'=>' "javascript:showlink(\'".Yii::app()->params["appUrl"]."qrcode/".createQRC($data->akey,Yii::app()->params["appUrl"].$data->akey,$type=1)."\',\'".Yii::app()->params["appUrl"].$data->akey."\');"',
			'options'=>array(
				'class'=>'link ext-cbtn tooltip-button',
				'title'=>'',
				'data-original-title'=>"链接"
			)
		)
		,
		'config'=>array(
			'label'=>'<i class="glyph-icon icon-cogs"></i>管理商户',
			'options'=>array(
				'class'=>'ext-cbtn tooltip-button',
				'title'=>'',
				'data-original-title'=>"管理商户"
			),
			/*'url'=>'Yii::app()->createUrl("/pluginProp/config", array("aid"=>$data->aid))'*/
			'url'=>'Yii::app()->createUrl("/sysUserGh/switch", array("ghid"=>$data->ghid))'
		)
	)
	;
	public $template='{link}{config}';
	function getButtons($data){
		$addtpl='';
		$addbtn=array();
		$setting=unserialize($data->plugin->setting);
		$sn=$setting['sn'];
		$prize=$setting['prize'];

		$template=$addtpl.$this->template;
		$buttons=array_merge($this->buttons,$addbtn);
		unset($addbtn);unset($addtpl);
		return array($template,$buttons);

	}
	protected function renderDataCellContent($row, $data){
		list($template,$buttons)=$this->getButtons($data);
		$tr=array();
		ob_start();
		foreach ($buttons as $id=>$button){
			$this->renderButton($id, $button, $row, $data);
			$tr['{'.$id.'}']=ob_get_contents();
			ob_clean();
		}
		ob_end_clean();
		echo strtr($template, $tr);
	}

}
