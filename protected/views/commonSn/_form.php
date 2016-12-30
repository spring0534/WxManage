
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'common-sn-form',
	'htmlOptions' =>array('class' => 'col-md-10 center-margin'),
	'enableAjaxValidation'=>false,
)); ?>

<div class="infobox warning-bg" id='msgtip' style='display: none'>
		<p>
			<i class="glyph-icon icon-exclamation mrg10R"></i><?php echo $form->errorSummary($model); ?>
</p>
	</div>
	<?php echo CHtml::hiddenField(chtmlName($model, 'aid'),$aid);?>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'sncode'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'sncode',array('size'=>20,'maxlength'=>20,'data-required'=>'true','data-trigger'=>'change')); ?>
		</div>
	</div>

	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'snpwd'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'snpwd',array('size'=>50,'maxlength'=>50)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'status'); ?> </label>
		</div>
		<div class="form-input col-md-10">

			<?php echo CHtml::groupButton(chtmlName($model, 'status'), 1, array(0=>'无效',1=>'有交',2=>'已领取',3=>'已使用'))?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'grade'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'grade',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for="">备注</label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'note',array('size'=>20,'maxlength'=>20,)); ?>
		</div>
	</div>


	<div class="button-pane">
		<div class="form-input col-md-10 col-md-offset-2">
			<button class="btn primary-bg medium"
				onclick="javascript:return $('#common-sn-form').parsley( 'validate' );">
				<span class="button-content"><i class="glyph-icon icon-check float-left"></i><?php echo $model->isNewRecord ? '提交' : '保存'; ?>
</span>
			</button>
			<button type="reset" class="btn medium primary-bg text-transform-upr font-size-11" id="demo-form-valid" >
						<span class="button-content"><i class="glyph-icon icon-undo float-left"></i>重置</span>
					</button>
		</div>
	</div>
<?php $this->endWidget(); ?>

</div>
<!-- form -->
