<?php
/* @var $this WxEventLogController */
/* @var $data WxEventLog */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wx_id')); ?>:</b>
	<?php echo CHtml::encode($data->wx_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wx_ghid')); ?>:</b>
	<?php echo CHtml::encode($data->wx_ghid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('keyword')); ?>:</b>
	<?php echo CHtml::encode($data->keyword); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('category')); ?>:</b>
	<?php echo CHtml::encode($data->category); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('item')); ?>:</b>
	<?php echo CHtml::encode($data->item); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('content')); ?>:</b>
	<?php echo CHtml::encode($data->content); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('tm')); ?>:</b>
	<?php echo CHtml::encode($data->tm); ?>
	<br />

	*/ ?>

</div>