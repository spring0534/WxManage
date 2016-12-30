<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#wx-event-log-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div id="page-title">
	<h3>
		微信消息日志
		<small> >>查看 </small>
	</h3>
</div>
<div id="page-content">

	
	<div class="search-form">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div>
	<!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'wx-event-log-grid',
	'template' =>'<div class="content-box">
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
		/*'id',*/
		/*'wx_id',
		'wx_ghid',*/
		'category',
		'keyword',
		array('name'=>'消息内容','value'=>'msubstr($data->item,0,200,true)'),
		/*
		'content',*/
		'tm',
		
			array(
			'class' => 'CButtonColumn',
			'template' => '{view}',
			'viewButtonOptions' => array(
				'title' => '查看',
				'style' => 'padding:10px'
			),

			'header' => '管理操作',
			'htmlOptions' => array(
				"width" => '40px'
			),
			/*'buttons' => array(
				'delete' => array(
					'imageUrl' => null,
					'label' => '<i class="glyph-icon icon-remove"></i>删除',
					'options' => array(
						'class' => 'ext-cbtn delete tooltip-button',
						'title' => '',
						'data-original-title' => "编辑"
					)
				),
				'update' => array(
					'imageUrl' => null,
					'title' => '',
					'label' => '<i class="glyph-icon icon-edit"></i>编辑',
					'options' => array(
						'class' => 'ext-cbtn'
					)
				),
				'config' => array(
					'title' => '',
					'label' => '<i class="glyph-icon icon-cogs"></i>配置',
					'options' => array(
						'class' => 'ext-cbtn'
					),
					'url' => 'Yii::app()->createUrl("/pluginProp/config", array("aid"=>"xxx"))'
				)
			)*/
			
		)
	),
)); ?>
</div>
