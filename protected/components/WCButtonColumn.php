<?php

/**
* WCButtonColumn.php
* ----------------------------------------------
* 版权所有 2014-2015
* ----------------------------------------------
* @date: 2014-12-15
*
*/
class WCButtonColumn extends CButtonColumn{
	public $buttons=array(
		'delete'=>array(
			'imageUrl'=>null,
			'label'=>'<i class="glyph-icon mrg5R icon-remove"></i>删除',
			'options'=>array(

				'title'=>'',
				'style'=>"color: #fff;line-height: 25px;  background-color: #C02C1A;",
				'data-original-title'=>"删除"
			)
		),
		'update'=>array(
			'imageUrl'=>null,
			'label'=>'<i class="glyph-icon mrg5R icon-edit"></i>编辑',
			'options'=>array(

				'title'=>'',
				'data-original-title'=>"编辑"
			)
		),
		'copy'=>array(
			'imageUrl'=>null,
			'label'=>'<i class="glyph-icon mrg5R icon-copy"></i>克隆',
			'url'=>' "javascript:copy(\'".Yii::app()->createUrl("/activity/copy", array("id"=>$data->aid))."\');"',
			'options'=>array(

				'title'=>'',
				'data-original-title'=>"克隆"
			)
		)
		,
				'link'=>array(
			'imageUrl'=>null,
			'label'=>'<i class="glyph-icon icon-paperclip"></i>链接',
			'url'=>' "javascript:showlink(\'".Yii::app()->params["appUrl"]."public/qrcode/".createQRC($data->akey,Yii::app()->params["appUrl"].$data->akey,$type=1)."\',\'".Yii::app()->params["appUrl"].$data->akey."\');"',
			'options'=>array(
				'class'=>'link ext-cbtn tooltip-button',
				'title'=>'',
				'data-original-title'=>"链接"
			)
		)
		,
		'userreg'=>array(
			'imageUrl'=>null,
			'label'=>'<i class="glyph-icon icon-user"></i>用户登记',
			'url'=>'Yii::app()->createUrl("/userReg/admin", array("aid"=>$data->aid))',
			'options'=>array(
				'class'=>'link ext-cbtn tooltip-button',
				'title'=>'',
				'data-original-title'=>"用户登记"
			)
		)
		,

		'config'=>array(
			'label'=>'<i class="glyph-icon icon-cog"></i>配置',
			'options'=>array(
				'class'=>'ext-cbtn tooltip-button',
				'title'=>'',
				'data-original-title'=>"配置"
			),
			'url'=>'Yii::app()->createUrl("/pluginProp/config", array("aid"=>$data->aid))'
		)
	)
	;
	public $template='{link}{config}{copy}{update}{userreg}{delete}';
	function getButtons($data){
		$addtpl='';
		$addbtn=array();
		$setting=unserialize($data->plugin->setting);
		$sn=$setting['sn'];
		$prize=$setting['prize'];
		$advancedManage=$setting['advancedManage'];
		if ($advancedManage){
			$addtpl.='{advancedManage}';
			$addbtn['advancedManage']=array(
				'label'=>'<i class="glyph-icon mrg5R icon-cogs"></i>高级管理',
				'options'=>array(

					'title'=>'',
					'data-original-title'=>"高级管理"
				),
				'url'=>'Yii::app()->createUrl("/appAdmin/".$data->akey)'
			);
		}else{

		}
		if ($sn){
			$addtpl.='{sn}';
			$addbtn['sn']=array(
				'label'=>'<i class="glyph-icon mrg5R icon-cogs"></i>SN配置',
				'options'=>array(

					'title'=>'',
					'data-original-title'=>"SN配置"
				),
				'url'=>'Yii::app()->createUrl("/commonSn/admin", array("aid"=>$data->aid))'
			);
		}else{

		}
		if($prize){
			$addtpl.='{prize}';
			$addbtn['prize']=array(
				'label'=>'<i class="glyph-icon mrg5R icon-cogs"></i>奖品配置',
				'options'=>array(

					'title'=>'',
					'data-original-title'=>"奖品配置"
				),
				'url'=>'Yii::app()->createUrl("/commonPrize/admin", array("aid"=>$data->aid))'
			);
		}else{

		}

		$template=$addtpl.$this->template;
		$buttons=array_merge($this->buttons,$addbtn);
		unset($addbtn);unset($addtpl);
		return array($template,$buttons);

	}
	protected function renderDataCellContent($row, $data){
		list($template,$buttons)=$this->getButtons($data);
		//dump($template);exit;
		$tr=$tr_pre=array();
		ob_start();
		foreach ($buttons as $id=>$button){
			$this->renderButton($id, $button, $row, $data);
			if(in_array($id, array('link','config','userreg'))){
				$tr_pre['{'.$id.'}']=ob_get_contents();
				$template=str_replace('{'.$id.'}', '', $template);
			}else{
				$tr['{'.$id.'}']='<li>'.ob_get_contents().'</li>';
			}
			ob_clean();
		}
		ob_end_clean();

		echo strtr('{link}{config}{userreg}', $tr_pre).'&nbsp;<div class="dropdown">
		<a href="javascript:;" data-original-title="更多操作" class="btn small primary-bg tooltip-button" data-toggle="dropdown">
		<span class="button-content">
		<i class="glyph-icon  font-size-11 icon-cog"></i>
		<i class="glyph-icon  font-size-11 icon-chevron-down"></i>
		</span>
		</a>
		<ul class="dropdown-menu float-right qmenu" style="padding: 0"><div class=" text-transform-upr font-size-12 font-bold  pad10A primary-bg">操作菜单</div>
'.strtr($template, $tr).
		'</ul>
		</div>';
		//echo strtr($template, $tr);
	}

}
