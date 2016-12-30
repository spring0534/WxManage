<?php
/* @var $this WxEventLogController */
/* @var $model WxEventLog */

$this->breadcrumbs=array(
	'Wx Event Logs'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List WxEventLog', 'url'=>array('index')),
	array('label'=>'Create WxEventLog', 'url'=>array('create')),
	array('label'=>'Update WxEventLog', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete WxEventLog', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WxEventLog', 'url'=>array('admin')),
);
?>

<h1>View WxEventLog #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'wx_id',
		'wx_ghid',
		'keyword',
		'category',
		'item',
		'content',
		'tm',
	),
)); ?>
