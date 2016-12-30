
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'wx-event-log-form',
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
			<label for=""><?php echo $form->labelEx($model,'wx_id'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'wx_id',array('size'=>50,'maxlength'=>50,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'wx_ghid'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'wx_ghid',array('size'=>50,'maxlength'=>50,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'keyword'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'keyword',array('size'=>50,'maxlength'=>50,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'category'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'category',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'item'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textArea($model,'item',array('rows'=>6, 'cols'=>50)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'content'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'tm'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'tm',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-input col-md-10 col-md-offset-2">
			<button class="btn primary-bg medium"
				onclick="javascript:return $('#wx-event-log-form').parsley( 'validate' );">
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
