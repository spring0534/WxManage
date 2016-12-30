<?php
/* @var $this WxRouterController */
/* @var $data WxRouter */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ghid')); ?>:</b>
	<?php echo CHtml::encode($data->ghid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('msg_type')); ?>:</b>
	<?php echo CHtml::encode($data->msg_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('event')); ?>:</b>
	<?php echo CHtml::encode($data->event); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('event_key')); ?>:</b>
	<?php echo CHtml::encode($data->event_key); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('keyword')); ?>:</b>
	<?php echo CHtml::encode($data->keyword); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('match_mode')); ?>:</b>
	<?php echo CHtml::encode($data->match_mode); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('reply_type')); ?>:</b>
	<?php echo CHtml::encode($data->reply_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reply_id')); ?>:</b>
	<?php echo CHtml::encode($data->reply_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ctm')); ?>:</b>
	<?php echo CHtml::encode($data->ctm); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('utm')); ?>:</b>
	<?php echo CHtml::encode($data->utm); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('note')); ?>:</b>
	<?php echo CHtml::encode($data->note); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('operator_uid')); ?>:</b>
	<?php echo CHtml::encode($data->operator_uid); ?>
	<br />

	*/ ?>

</div>