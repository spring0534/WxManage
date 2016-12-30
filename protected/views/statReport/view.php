<?php
/* @var $this StatReportController */
/* @var $model StatReport */

$this->breadcrumbs=array(
	'Stat Reports'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List StatReport', 'url'=>array('index')),
	array('label'=>'Create StatReport', 'url'=>array('create')),
	array('label'=>'Update StatReport', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete StatReport', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage StatReport', 'url'=>array('admin')),
);
?>

<h1>View StatReport #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'day',
		'aid',
		'ghid',
		/*'pv',*/
		'uv',
		'cv',
		'ip',
		's1',
		's2',
		's3',
		's4',
		'sub',
		'unsub',
		'msg',
	),
)); ?>
