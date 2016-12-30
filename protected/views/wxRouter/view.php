<?php
/* @var $this WxRouterController */
/* @var $model WxRouter */

$this->breadcrumbs=array(
	'Wx Routers'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List WxRouter', 'url'=>array('index')),
	array('label'=>'Create WxRouter', 'url'=>array('create')),
	array('label'=>'Update WxRouter', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete WxRouter', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WxRouter', 'url'=>array('admin')),
);
?>

<h1>View WxRouter #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'ghid',
		'msg_type',
		'event',
		'event_key',
		'keyword',
		'match_mode',
		'reply_type',
		'reply_id',
		'status',
		'ctm',
		'utm',
		'note',
		'operator_uid',
	),
)); ?>
