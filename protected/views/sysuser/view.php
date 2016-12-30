<?php
/* @var $this SysuserController */
/* @var $model SysUser */

$this->breadcrumbs=array(
	'Sys Users'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List SysUser', 'url'=>array('index')),
	array('label'=>'Create SysUser', 'url'=>array('create')),
	array('label'=>'Update SysUser', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete SysUser', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SysUser', 'url'=>array('admin')),
);
?>

<h1>View SysUser #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'pid',
		'username',
		'nickname',
		'password',
		'phone',
		'qq',
		'email',
		'last_login_time',
		'last_login_ip',
		'login_count',
		'create_time',
		'status',
		'groupid',
	),
)); ?>
