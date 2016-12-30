<?php
/* @var $this UserRegController */
/* @var $model UserReg */

$this->breadcrumbs=array(
	'User Regs'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List UserReg', 'url'=>array('index')),
	array('label'=>'Create UserReg', 'url'=>array('create')),
	array('label'=>'Update UserReg', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete UserReg', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UserReg', 'url'=>array('admin')),
);
?>

<h1>View UserReg #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'aid',
		'wxid',
		'src_openid',
		'ghid',
		'username',
		'phone',
		'company',
		'prize',
		'relate_aid',
		'sncode',
		'qrcode',
		'qrcode_small',
		'score',
		'total_time',
		'ext_info',
		'status',
		'ip',
		'ua',
		'ctm',
		'utm',
		'tags',
		'notes',
		'flag',
		'form_id',
	),
)); ?>
