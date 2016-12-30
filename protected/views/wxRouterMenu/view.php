<?php
/* @var $this WxRouterMenuController */
/* @var $model WxRouterMenu */

$this->breadcrumbs=array(
	'Wx Router Menus'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List WxRouterMenu', 'url'=>array('index')),
	array('label'=>'Create WxRouterMenu', 'url'=>array('create')),
	array('label'=>'Update WxRouterMenu', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete WxRouterMenu', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WxRouterMenu', 'url'=>array('admin')),
);
?>

<h1>View WxRouterMenu #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'ghid',
		'name',
		'parent_id',
		'seq',
		'menu_type',
		'url',
		'event_key',
		'reply_type',
		'reply_id',
		'status',
		'note',
		'ctm',
		'utm',
	),
)); ?>
