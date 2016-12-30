<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#wx-spread-qrcode-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div id="page-title">
	<h3>
		二维码管理
		<small> &gt;&gt;管理 </small>
	</h3>
	<div id="breadcrumb-right">
		<div class="float-right">

			<a href="<?php U('create');?>" class="btn large primary-bg ">
			<span class="button-content">
				<i class="glyph-icon icon-plus float-left "></i>
				添加二维码
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
	'id'=>'wx-spread-qrcode-grid',
	'template'=>'<div class="content-box"> <table class="table table-hover text-center"><tbody>{items} </tbody></table></div><div class="summary" style="float: left;">{summary}</div><div class="pager">{pager}</div>',
	'dataProvider'=>$model->search(),
	/*'filter'=>$model,*/
	'columns'=>array(
		'id',

		array('name'=>'二维码类型','value'=>'$data->qtype==1?临时:永久'),
		'name',
		array('name'=>'回复素材','value'=>'$data->wxm->title'),
		'qcount',
		'ucount',
		'expire',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}{update}',
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
				"width"=>'30%'
			),
			'buttons'=>array(
				'delete'=>array(
					'imageUrl'=>null,
					'label'=>'<i class="glyph-icon icon-remove"></i>删除',
					'options'=>array(
						'class'=>'ext-cbtn delete tooltip-button',
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
					'title'=>'',
					'label'=>'<i class="glyph-icon icon-edit"></i>查看二维码',
					'url'=>'$aa="javascript:image_priview(\'".$data->url."\')";return $aa;',
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
