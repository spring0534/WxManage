<?php
/* @var $this WxMaterialController */
/* @var $model WxMaterial */

$this->breadcrumbs=array(
	'Wx Materials'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List WxMaterial', 'url'=>array('index')),
	array('label'=>'Create WxMaterial', 'url'=>array('create')),
	array('label'=>'Update WxMaterial', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete WxMaterial', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WxMaterial', 'url'=>array('admin')),
);
?>

<h1>View WxMaterial #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'msg_type',
		'content',
		'ghid',
		'ctm',
		'utm',
		'status',
		'operator_uid',
	),
)); ?>
