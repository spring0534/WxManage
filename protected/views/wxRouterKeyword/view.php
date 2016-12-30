<?php
/* @var $this WxRouterKeywordController */
/* @var $model WxRouterKeyword */

$this->breadcrumbs=array(
	'Wx Router Keywords'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List WxRouterKeyword', 'url'=>array('index')),
	array('label'=>'Create WxRouterKeyword', 'url'=>array('create')),
	array('label'=>'Update WxRouterKeyword', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete WxRouterKeyword', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WxRouterKeyword', 'url'=>array('admin')),
);
?>

<h1>View WxRouterKeyword #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'ghid',
		'keyword',
		'match_mode',
		'reply_type',
		'reply_id',
		'status',
		'note',
		'ctm',
		'utm',
		'tenant_id',
		'uid',
		'msg_type',
	),
)); ?>
