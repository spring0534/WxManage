<?php
/* @var $this UserRegController */
/* @var $data UserReg */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('aid')); ?>:</b>
	<?php echo CHtml::encode($data->aid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wxid')); ?>:</b>
	<?php echo CHtml::encode($data->wxid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('src_openid')); ?>:</b>
	<?php echo CHtml::encode($data->src_openid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ghid')); ?>:</b>
	<?php echo CHtml::encode($data->ghid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::encode($data->username); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phone')); ?>:</b>
	<?php echo CHtml::encode($data->phone); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('company')); ?>:</b>
	<?php echo CHtml::encode($data->company); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('prize')); ?>:</b>
	<?php echo CHtml::encode($data->prize); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('relate_aid')); ?>:</b>
	<?php echo CHtml::encode($data->relate_aid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sncode')); ?>:</b>
	<?php echo CHtml::encode($data->sncode); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('qrcode')); ?>:</b>
	<?php echo CHtml::encode($data->qrcode); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('qrcode_small')); ?>:</b>
	<?php echo CHtml::encode($data->qrcode_small); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('score')); ?>:</b>
	<?php echo CHtml::encode($data->score); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('total_time')); ?>:</b>
	<?php echo CHtml::encode($data->total_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ext_info')); ?>:</b>
	<?php echo CHtml::encode($data->ext_info); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ip')); ?>:</b>
	<?php echo CHtml::encode($data->ip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ua')); ?>:</b>
	<?php echo CHtml::encode($data->ua); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ctm')); ?>:</b>
	<?php echo CHtml::encode($data->ctm); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('utm')); ?>:</b>
	<?php echo CHtml::encode($data->utm); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tags')); ?>:</b>
	<?php echo CHtml::encode($data->tags); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('notes')); ?>:</b>
	<?php echo CHtml::encode($data->notes); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('flag')); ?>:</b>
	<?php echo CHtml::encode($data->flag); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('form_id')); ?>:</b>
	<?php echo CHtml::encode($data->form_id); ?>
	<br />

	*/ ?>

</div>