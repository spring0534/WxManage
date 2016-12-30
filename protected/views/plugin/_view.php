<?php
/* @var $this PluginController */
/* @var $data Plugin */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ptype')); ?>:</b>
	<?php echo CHtml::encode($data->ptype); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('icon_url')); ?>:</b>
	<?php echo CHtml::encode($data->icon_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('simple_memo')); ?>:</b>
	<?php echo CHtml::encode($data->simple_memo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('detail_memo')); ?>:</b>
	<?php echo CHtml::encode($data->detail_memo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('usage')); ?>:</b>
	<?php echo CHtml::encode($data->usage); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('promote')); ?>:</b>
	<?php echo CHtml::encode($data->promote); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hot')); ?>:</b>
	<?php echo CHtml::encode($data->hot); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('need_reset_url')); ?>:</b>
	<?php echo CHtml::encode($data->need_reset_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('need_scr_url')); ?>:</b>
	<?php echo CHtml::encode($data->need_scr_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ctm')); ?>:</b>
	<?php echo CHtml::encode($data->ctm); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('utm')); ?>:</b>
	<?php echo CHtml::encode($data->utm); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('processor_class')); ?>:</b>
	<?php echo CHtml::encode($data->processor_class); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dtype')); ?>:</b>
	<?php echo CHtml::encode($data->dtype); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('screenshots')); ?>:</b>
	<?php echo CHtml::encode($data->screenshots); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('menus')); ?>:</b>
	<?php echo CHtml::encode($data->menus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price_month')); ?>:</b>
	<?php echo CHtml::encode($data->price_month); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price_year')); ?>:</b>
	<?php echo CHtml::encode($data->price_year); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tenant_id')); ?>:</b>
	<?php echo CHtml::encode($data->tenant_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('uid')); ?>:</b>
	<?php echo CHtml::encode($data->uid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cate')); ?>:</b>
	<?php echo CHtml::encode($data->cate); ?>
	<br />

	*/ ?>

</div>