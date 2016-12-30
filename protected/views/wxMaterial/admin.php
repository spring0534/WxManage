<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#wx-material-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div id="page-title">
	<h3>
		管理
		<small> &gt;&gt;管理 </small>
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
		<a href="#" class="btn medium primary-bg ">
			<span class="button-content">
				<i class="glyph-icon icon-mail-reply float-left "></i>
				返回
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
	'id'=>'wx-material-grid', 
	'template'=>'<div class="content-box"> <table class="table table-hover text-left"><tbody>{items} </tbody></table></div><div class="summary" style="float: left;">{summary}</div><div class="pager">{pager}</div>', 
	'dataProvider'=>$model->search(), 
	'enableHistory'=>true,
	'columns'=>array(
		'id', 
		'title', 
		array( // display 'create_time' using an expression
			'name'=>'msg_type', 
			'value'=>'Yii::app()->params["msg_type"][$data->msg_type]'
		), 
		'ghid', 
		'ctm', 
		'utm', 
		array( // display 'author.username' using an expression
			'name'=>'status', 
			'value'=>'$data->status?"正常":"禁用"'
		), 
		'operator_uid', 
		array(
			'class'=>'CButtonColumn'
		)
	)
));
?>
</div>
