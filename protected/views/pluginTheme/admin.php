<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#plugin-theme-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div id="page-title">
	<h3>
		微应用主题
		<small> >>主题模板管理 </small>
	</h3>
	<div id="breadcrumb-right">
		<div class="float-right">
			<a href="<?php echo WEB_URL.Yii::app()->request->getUrl();?>" class="btn medium bg-white tooltip-button black-modal-60 mrg5R" data-placement="bottom" data-original-title="刷新">
				<span class="button-content">
					<i class="glyph-icon icon-refresh"></i>
				</span>
			</a>
		</div>
	</div>
</div>
<div id="page-content">
	<div style="padding-bottom: 10px">
		<a href="<?php echo Yii::app()->createUrl('/plugin/admin');?>" class="btn medium primary-bg ">
			<span class="button-content">
				<i class="glyph-icon icon-mail-reply-all float-left "></i>
				返回
			</span>
		</a>
		<a href="<?php U('create',array('ptype'=>$_GET['PluginTheme']['ptype']));?>" class="btn medium primary-bg ">
			<span class="button-content">
				<i class="glyph-icon icon-plus float-left "></i>
				添加主题模板
			</span>
		</a>
	</div>
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
	'id'=>'plugin-theme-grid', 
	'template'=>'<div class="content-box">
		<table class="table table-hover text-center">
			<tbody>{items}
			</tbody>
		</table>
	</div>
	<div class="summary" style="float: left;">{summary}</div>
	<div class="pager">{pager}</div>', 
	'dataProvider'=>$model->search(),
	/*'filter'=>$model,*/
	'columns'=>array(
		'id', 
		'ptype', 
		'name', 
		'scr_theme', 
		'wx_theme', 
		'status',
		/*
		'pic2',
		'pic3',
		'scr_theme',
		'wx_theme',
		'status',
		'ctm',
		'utm',
		'tenant_id',
		'scope',
		'uid',
		*/
			array(
			'class'=>'CButtonColumn', 
			'template'=>'{update}{delete}', 
			'viewButtonOptions'=>array(
				'title'=>'查看', 
				'style'=>'padding:10px'
			), 
			'updateButtonOptions'=>array(
				'title'=>'修改', 
				'style'=>'padding:10px'
			), 
			'header'=>'管理操作', 
			'htmlOptions'=>array(
				"width"=>'10%'
			), 
			'buttons'=>array(
				'delete'=>array(
					'imageUrl'=>null, 
					'label'=>'<i class="glyph-icon icon-remove"></i>删除', 
					'options'=>array(
						'class'=>'ext-cbtn delete tooltip-button', 
						'title'=>'', 
						'data-original-title'=>"编辑"
					)
				), 
				'update'=>array(
					'imageUrl'=>null, 
					'title'=>'', 
					'label'=>'<i class="glyph-icon icon-edit"></i>编辑', 
					'options'=>array(
						'class'=>'ext-cbtn'
					)
				)
			)
		)
		
	)
));
?>
</div>
