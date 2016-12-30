<?php
/* @var $this SysUserGhController */
/* @var $model SysUserGh */

$this->breadcrumbs=array(
	'Sys User Ghs'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List SysUserGh', 'url'=>array('index')),
	array('label'=>'Create SysUserGh', 'url'=>array('create')),
	array('label'=>'Update SysUserGh', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete SysUserGh', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SysUserGh', 'url'=>array('admin')),
);
?>

<h1>View SysUserGh #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'ghid',
		'name',
		'icon_url',
		'qrcode',
		'qrcode_small',
		'type',
		'wxh',
		'company',
		'desc',
		'tenancy',
		'login_name',
		'login_pwd',
		'api_url',
		'api_token',
		'zf_api_url',
		'zf_api_token',
		'appid',
		'appsecret',
		'notes',
		'status',
		'open_portal',
		'open_msite',
		'ctm',
		'utm',
		'operator_uid',
		'interact',
		'tenant_id',
		'ec_cid',
		'oauth',
		'access_token',
		'at_expires',
	),
)); ?>
