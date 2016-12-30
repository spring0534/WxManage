<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#sys-user-gh-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div id="page-title">
	<h3>
		公众号管理
		<small> >>管理 </small>
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
	'id'=>'sys-user-gh-grid',
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
		/*'id', */
		'name',
		'ghid',
		/*'tenant_id',*/
		array(
			'name'=>'status',
			'value'=>'$data->status?"正常":"禁用"'
		),
		/*
		'type',
		'wxh',
		'company',
		'desc',
		'tenancy',
		'login_name',
		'login_pwd',
		'api_url',
		'api_token',
		'zf_api_url',
		'zf_api_token',
		'appid',
		'appsecret',
		'notes',
		'status',
		'open_portal',
		'open_msite',
		'ctm',
		'utm',
		'operator_uid',
		'interact',
		'tenant_id',
		'ec_cid',
		'oauth',
		'access_token',
		'at_expires',
		*/
			array(
			'class'=>'CButtonColumn',
			'template'=>'{switch}{open}',
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
				'switch'=>array(
					'title'=>'',
					'label'=>'<i class="glyph-icon icon-cogs"></i>管理',
					'options'=>array(
						'class'=>'ext-cbtn'
					),
					'url'=>'Yii::app()->createUrl("/sysUserGh/switch", array("ghid"=>$data->ghid))'
				),
				'open'=>array(
					'title'=>'',
					'label'=>'<i class="glyph-icon icon-cogs"></i>微应用开通',
					'options'=>array(
						'class'=>'ext-cbtn'
					),
					'url'=>'Yii::app()->createUrl("/plugin/openPlugn", array("ghid_control"=>$data->ghid))'
				)
			)

		)
	)
));
?>
</div>
