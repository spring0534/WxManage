<?php
/* @var $this WxSpreadQrcodeController */
/* @var $model WxSpreadQrcode */

$this->breadcrumbs=array(
	'Wx Spread Qrcodes'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List WxSpreadQrcode', 'url'=>array('index')),
	array('label'=>'Create WxSpreadQrcode', 'url'=>array('create')),
	array('label'=>'Update WxSpreadQrcode', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete WxSpreadQrcode', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WxSpreadQrcode', 'url'=>array('admin')),
);
?>

<h1>View WxSpreadQrcode #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'qtype',
		'name',
		'reply_id',
	),
)); ?>
