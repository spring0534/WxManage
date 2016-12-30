<?php
/* @var $this WxRouterMenuController */
/* @var $data WxRouterMenu */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ghid')); ?>:</b>
	<?php echo CHtml::encode($data->ghid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('parent_id')); ?>:</b>
	<?php echo CHtml::encode($data->parent_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('seq')); ?>:</b>
	<?php echo CHtml::encode($data->seq); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('menu_type')); ?>:</b>
	<?php echo CHtml::encode($data->menu_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('url')); ?>:</b>
	<?php echo CHtml::encode($data->url); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('event_key')); ?>:</b>
	<?php echo CHtml::encode($data->event_key); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reply_type')); ?>:</b>
	<?php echo CHtml::encode($data->reply_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reply_id')); ?>:</b>
	<?php echo CHtml::encode($data->reply_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('note')); ?>:</b>
	<?php echo CHtml::encode($data->note); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ctm')); ?>:</b>
	<?php echo CHtml::encode($data->ctm); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('utm')); ?>:</b>
	<?php echo CHtml::encode($data->utm); ?>
	<br />

	*/ ?>

</div>