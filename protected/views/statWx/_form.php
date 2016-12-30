
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'stat-wx-form',
	'htmlOptions' =>array('class' => 'col-md-10 center-margin'),
	'enableAjaxValidation'=>false,
)); ?>

<div class="infobox warning-bg" id='msgtip' style='display: none'>
		<p>
			<i class="glyph-icon icon-exclamation mrg10R"></i><?php echo $form->errorSummary($model); ?>
</p>
	</div>

	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'day'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'day',array($htmlOptions)); ?>
		</div>
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
			<label for=""><?php echo $form->labelEx($model,'sub'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'sub',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'unsub'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'unsub',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'receive_num'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'receive_num',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'send_num'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'send_num',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'msg_num'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'msg_num',array($htmlOptions)); ?>
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
		<div class="form-input col-md-10 col-md-offset-2">
			<button class="btn primary-bg medium"
				onclick="javascript:return $('#stat-wx-form').parsley( 'validate' );">
				<span class="button-content"><?php echo $model->isNewRecord ? '提交' : '保存'; ?>
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
