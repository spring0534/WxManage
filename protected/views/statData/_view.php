<?php
/* @var $this StatReportController */
/* @var $data StatReport */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('day')); ?>:</b>
	<?php echo CHtml::encode($data->day); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('aid')); ?>:</b>
	<?php echo CHtml::encode($data->aid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ghid')); ?>:</b>
	<?php echo CHtml::encode($data->ghid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pv')); ?>:</b>
	<?php echo CHtml::encode($data->pv); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('uv')); ?>:</b>
	<?php echo CHtml::encode($data->uv); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cv')); ?>:</b>
	<?php echo CHtml::encode($data->cv); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('ip')); ?>:</b>
	<?php echo CHtml::encode($data->ip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('s1')); ?>:</b>
	<?php echo CHtml::encode($data->s1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('s2')); ?>:</b>
	<?php echo CHtml::encode($data->s2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('s3')); ?>:</b>
	<?php echo CHtml::encode($data->s3); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('s4')); ?>:</b>
	<?php echo CHtml::encode($data->s4); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sub')); ?>:</b>
	<?php echo CHtml::encode($data->sub); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('unsub')); ?>:</b>
	<?php echo CHtml::encode($data->unsub); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('msg')); ?>:</b>
	<?php echo CHtml::encode($data->msg); ?>
	<br />

	*/ ?>

</div>