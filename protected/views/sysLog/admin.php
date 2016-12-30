<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#sys-log-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div id="page-title">
	<h3>
		报警日志
		<small> >> 日志查看</small>
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
	'id'=>'sys-log-grid', 
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
		'level', 
		'category', 
		'ghid', 
		
		// 'uid',
		array(
			'name'=>'操作用户 ', 
			'value'=>'$data->user->username'
		), 
		 
		array('name'=>'操作URL','value'=>'msubstr($data->request_url,0,50,true)'),
		array(
			'name'=>'message', 
			'value'=>'msubstr($data->message,0,20,true)'
		), 
		'ip', 
		array(
			'name'=>'logtime', 
			'value'=>'date("Y-m-d H:i:s",$data->logtime)', 
			'type'=>'html'
		), 
		array(
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
				"width"=>'150px'
			), 
			'buttons'=>array(
				'delete'=>array(
					'imageUrl'=>null, 
					'label'=>'<i class="glyph-icon icon-remove"></i>删除', 
					'options'=>array(
						'class'=>'ext-cbtn delete  tooltip-button', 
						'title'=>'', 
						'data-original-title'=>"删除"
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
		)
	)
));
?>
</div>
