<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#wx-forward-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div id="page-title">
	<h3>
		微信微应用服务
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
	<div class="tabs ui-tabs ui-widget ui-widget-content ui-corner-all">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<!-- <li onclick="location.href='<?php U('index')?>'">
				<a href="#icon-only-tabs-2">
					<i class="glyph-icon icon-trello float-left opacity-80"></i>
					微信微应用开通
				</a>
			</li>
			 -->
			<li onclick="location.href='<?php U('admin')?>'" class=" ui-tabs-active ui-state-active">
				<a href="#icon-only-tabs-3">
					<i class="glyph-icon icon-bar-chart float-left opacity-80"></i>
					第三方接口
				</a>
			</li>
		</ul>
		<div id="icon-only-tabs-3" aria-labelledby="ui-id-3" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-expanded="false" aria-hidden="true" style="display: block;">
			<div style="padding-bottom: 10px">
				<a href="<?php U('create')?>" class="btn medium primary-bg ">
					<span class="button-content">
						<i class="glyph-icon icon-plus float-left "></i>
						添加第三方接口
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
	'id'=>'wx-forward-grid',
	'template'=>'<div class="content-box"> <table class="table table-hover text-center"><tbody>{items} </tbody></table></div><div class="summary" style="float: left;">{summary}</div><div class="pager">{pager}</div>',
	'dataProvider'=>$model->search(),
	/*'filter'=>$model,*/
	'columns'=>array(
		'id',
		'name',
		'type',
		'keyword',
		'match_mode',
		'url',
		/*
		'token',
		'cache_minutes',
		'ghid',
		'status',
		'note',
		'ctm',
		'utm',
		'tenant_id',
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
			'buttons'=>array(
				'delete'=>array(
					'imageUrl'=>__PUBLIC__.'/images/icon/del.png'
				),  // 改变删除按钮的图片 如果设为null （ 'imageUrl'=>null） 则显示文字 'deleteButtonOptions'=>array('title'=>'删除'),,
				'update'=>array(
					'imageUrl'=>__PUBLIC__.'/images/icon/m_2.png'
				)
			)
		)
	)
));
?>
</div>
	</div>
</div>
