<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#sys-operation-log-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div id="page-title">
	<h3>
		操作日志
		<small> >>查看 </small>
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
	'id'=>'sys-operation-log-grid', 
	'template'=>'<div class="content-box">
		<table class="table table-hover text-center">
			<tbody>{items}
			</tbody>
		</table>
	</div>
	<div class="summary" style="float: left;">{summary}</div>
	<div class="pager">{pager}</div>', 
	'dataProvider'=>$model->index(),
	/*'filter'=>$model,*/
	'columns'=>array(
		'id', 
		array(
			'name'=>'操作账号', 
			'value'=>'$data->user->username'
		), 
		
		array(
			'name'=>'module', 
			'value'=>'$data->module?$data->module:"默认"'
		), 
		'controller', 
		'action', 
		array(
			'name'=>'message', 
			'value'=>'$data->message?$data->message:"常规"'
		), 
		'ip', 
		array(
			'name'=>'optime', 
			'value'=>'date("Y-m-d H:i:s",$data->optime)', 
			'type'=>'html'
		),
		/*array(
			'class'=>'CButtonColumn', 
			'template'=>'{view}', 
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
				"width"=>'80px'
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
				), 
				'view'=>array(
					'imageUrl'=>null, 
					'label'=>'<i class="glyph-icon icon-eye-open"></i>查看', 
					'options'=>array(
						'class'=>'ext-cbtn  tooltip-button', 
						'title'=>'', 
						'data-original-title'=>"查看"
					)
				)
			)
		)*/
	)
));
?>
</div>
