
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sys-log-form',
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
			<label for=""><?php echo $form->labelEx($model,'level'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'level',array('size'=>60,'maxlength'=>128,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'category'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'category',array('size'=>60,'maxlength'=>128,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'ghid'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'ghid',array('size'=>60,'maxlength'=>128,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'uid'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'uid',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'request_url'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'request_url',array('size'=>60,'maxlength'=>100,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'ip'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'ip',array('size'=>32,'maxlength'=>32,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'logtime'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'logtime',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'message'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textArea($model,'message',array('rows'=>6, 'cols'=>50)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-input col-md-10 col-md-offset-2">
			<button class="btn primary-bg medium"
				onclick="javascript:return $('#sys-log-form').parsley( 'validate' );">
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
