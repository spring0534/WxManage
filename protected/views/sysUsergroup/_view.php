<?php
/* @var $this SysUsergroupController */
/* @var $data SysUsergroup */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('groupname')); ?>:</b>
	<?php echo CHtml::encode($data->groupname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('listorder')); ?>:</b>
	<?php echo CHtml::encode($data->listorder); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('flagarr')); ?>:</b>
	<?php echo CHtml::encode($data->flagarr); ?>
	<br />


</div>