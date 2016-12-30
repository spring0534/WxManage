<?php
/* @var $this WxRouterController */
/* @var $model WxRouter */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'wx-router-form',
	'htmlOptions' =>array('class' => 'col-md-10 center-margin'),
	'enableAjaxValidation'=>false,
)); ?>

<div class="infobox warning-bg" id='msgtip' style='display: none'>
    <p><i class="glyph-icon icon-exclamation mrg10R"></i><?php echo $form->errorSummary($model); ?>
</p>
</div>

	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'ghid'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'ghid',array('size'=>50,'maxlength'=>50,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'msg_type'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'msg_type',array('size'=>50,'maxlength'=>50,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'event'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'event',array('size'=>20,'maxlength'=>20,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'event_key'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'event_key',array('size'=>30,'maxlength'=>30,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'keyword'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'keyword',array('size'=>20,'maxlength'=>20,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'match_mode'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'match_mode',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'reply_type'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'reply_type',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'reply_id'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'reply_id',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'status'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'status',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'ctm'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'ctm',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'utm'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'utm',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'note'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'note',array('size'=>60,'maxlength'=>100,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'operator_uid'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'operator_uid',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="button-pane">
		<div class="form-input col-md-10 col-md-offset-2">
			<button class="btn primary-bg medium"
				onclick="javascript:return $('#wx-router-form').parsley( 'validate' );">
				<span class="button-content"><i class="glyph-icon icon-check float-left"></i><?php echo $model->isNewRecord ? '提交' : '保存'; ?>
</span>
			</button>
			<button type="reset" class="btn primary-bg medium" id="demo-form-valid" >
						<span class="button-content"><i class="glyph-icon icon-undo float-left"></i>重置</span>
					</button>
		</div>

	</div>
<?php $this->endWidget(); ?>

</div>
<!-- form -->
<script>
if($('.errorSummary').length>0){
	$('#msgtip').slideDown();

}
$('body').click(function(){
	$('#msgtip').slideUp();
});
</script>