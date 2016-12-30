
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'stat-report-form',
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
			<label for=""><?php echo $form->labelEx($model,'aid'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'aid',array($htmlOptions)); ?>
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
			<label for=""><?php echo $form->labelEx($model,'pv'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'pv',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'uv'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'uv',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'cv'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'cv',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'ip'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'ip',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'s1'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'s1',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'s2'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'s2',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'s3'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'s3',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'s4'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'s4',array($htmlOptions)); ?>
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
			<label for=""><?php echo $form->labelEx($model,'msg'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'msg',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-input col-md-10 col-md-offset-2">
			<button class="btn primary-bg medium"
				onclick="javascript:return $('#stat-report-form').parsley( 'validate' );">
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
