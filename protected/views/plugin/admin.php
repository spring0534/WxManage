<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#plugin-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div id="page-title">
	<h3>
		微应用
		<small> &gt;&gt;管理 </small>
	</h3>
		<div id="breadcrumb-right">


                    <a href="<?php U('create');?>" class="btn btn large primary-bg " data-placement="left">
						<span class="button-content">
							<i class="glyph-icon icon-plus float-left "></i>
							增加新应用
						</span>
					</a>
	</div>

</div>
<div id="page-content">

	<div class="search-form">
<?php

$this->renderPartial('_search', array(
	'model'=>$model
));
?>
</div>
	<!-- search-form -->

<?php

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'plugin-grid',
	'template'=>'<div class="content-box"> <table class="table table-hover text-center"><tbody>{items} </tbody></table></div><div class="summary" style="float: left;">{summary}</div><div class="pager">{pager}</div>',
	'dataProvider'=>$model->search(),
	/*'filter'=>$model,*/
	'columns'=>array(
		array(
			'name'=>'icon_url',
			'value'=>'CHtml::image($data->icon_url,"",
				array(
				"height"=>150,
				"class"=>"medium radius-all-2",
				)
			)',  // 这里显示图片
			'type'=>'raw',  // 这里是原型输出
			'htmlOptions'=>array(
				'width'=>'200',
				'style'=>'text-align:center'
			)
		),
		'ptype',
		'name',
		'simple_memo',

		array(
			'name'=>'cate',
			'value'=>'Yii::app()->params["plugin_cate"][$data->cate]'
		),
		array(
			'name'=>'status',
			'value'=>'$data->status?"其它":"不可用"'
		),
		/*
		'usage',
		'promote',
		'hot',
		'status',
		'need_reset_url',
		'need_scr_url',
		'ctm',
		'utm',
		'processor_class',
		'dtype',
		'screenshots',
		'menus',
		'price_month',
		'price_year',
		'tenant_id',
		'uid',
		'cate',
		*/
		array(
			'class'=>'CButtonColumn',
			'template'=>'{config}{theme}{update}{upc}',
			'header'=>'管理操作',
			'htmlOptions'=>array(
				"width"=>'250px'
			),
			'buttons'=>array(
				/*'delete'=>array(
					'imageUrl'=>null,
					'label'=>'<i class="glyph-icon icon-remove"></i>删除',
					'options'=>array(
						'class'=>'ext-cbtn delete tooltip-button',
						'title'=>'删除',
						'data-original-title'=>"删除"
					)
				), */
				'update'=>array(
					'imageUrl'=>null,
					'label'=>'<i class="glyph-icon icon-edit"></i>编辑',
					'options'=>array(
						'class'=>'ext-cbtn  tooltip-button',
						'title'=>'',
						'data-original-title'=>"编辑"
					)
				),
				'theme'=>array(
					'label'=>'<i class="glyph-icon icon-bell"></i>模板',
					'url'=>'Yii::app()->createUrl("/PluginTheme/admin", array("PluginTheme[ptype]"=>$data->ptype))',
					'options'=>array(
						'class'=>'ext-cbtn  tooltip-button',
						'title'=>'',
						'data-original-title'=>"配置模板"
					)
				),
				'config'=>array(
					'label'=>'<i class="glyph-icon icon-cog"></i>属性',
					'options'=>array(
						'class'=>'ext-cbtn  tooltip-button',
						'title'=>'',
						'data-original-title'=>"配置属性"
					),
					'url'=>'Yii::app()->createUrl("/PluginProp/admin", array("ptype"=>$data->ptype))'
				),
				'upc'=>array(
					'label'=>'<i class="glyph-icon icon-refresh"></i>缓存',
					'url'=>'Yii::app()->createUrl("/pluginProp/updateActCache", array("ptype"=>$data->ptype))',
					'options'=>array(
						'class'=>'ext-cbtn  tooltip-button',
						'title'=>'',
						'data-original-title'=>"更新活动缓存"
					)
				),
			)
		)
	)
));
?>
</div>
