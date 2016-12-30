<?php
/* @var $this WxForwardController */
/* @var $model WxForward */

$this->breadcrumbs=array(
	'Wx Forwards'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List WxForward', 'url'=>array('index')),
	array('label'=>'Create WxForward', 'url'=>array('create')),
	array('label'=>'Update WxForward', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete WxForward', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WxForward', 'url'=>array('admin')),
);
?>

<h1>View WxForward #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'type',
		'keyword',
		'match_mode',
		'url',
		'token',
		'cache_minutes',
		'ghid',
		'status',
		'note',
		'ctm',
		'utm',
		'tenant_id',
		'uid',
	),
)); ?>
