<?php
/* @var $this StatWxController */
/* @var $model StatWx */

$this->breadcrumbs=array(
	'Stat Wxes'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List StatWx', 'url'=>array('index')),
	array('label'=>'Create StatWx', 'url'=>array('create')),
	array('label'=>'Update StatWx', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete StatWx', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage StatWx', 'url'=>array('admin')),
);
?>

<h1>View StatWx #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'day',
		'ghid',
		'sub',
		'unsub',
		'receive_num',
		'send_num',
		'msg_num',
		'ctm',
		'utm',
	),
)); ?>
