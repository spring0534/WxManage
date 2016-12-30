<?php
/* @var $this SysUsergroupController */
/* @var $model SysUsergroup */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sys-usergroup-SysUsergroupFrom-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// See class documentation of CActiveForm for details on this,
	// you need to use the performAjaxValidation()-method described there.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'listorder'); ?>
		<?php echo $form->textField($model,'listorder'); ?>
		<?php echo $form->error($model,'listorder'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'groupname'); ?>
		<?php echo $form->textField($model,'groupname'); ?>
		<?php echo $form->error($model,'groupname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'flagarr'); ?>
		<?php echo $form->textField($model,'flagarr'); ?>
		<?php echo $form->error($model,'flagarr'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->