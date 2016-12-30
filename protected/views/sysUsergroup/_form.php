
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sys-usergroup-form',
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
			<label for=""><?php echo $form->labelEx($model,'groupname'); ?> </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'groupname',array('size'=>20,'maxlength'=>20,'data-trigger'=>"change",'data-required'=>"true")); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'listorder'); ?> </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'listorder',array($htmlOptions)); ?>
		</div>
	</div>





	<div class="form-row">
		<div class="form-input col-md-10 col-md-offset-2">
			<button class="btn primary-bg medium"
				onclick="javascript:return $('#sys-usergroup-form').parsley( 'validate' );">
				<span class="button-content"><i class="glyph-icon icon-check float-left"></i><?php echo $model->isNewRecord ? '提交' : '保存'; ?>
</span>
			</button>
			<a
				href="javascript:javascript:location.href='<?php echo $this->createUrl('admin');?>'"
				class="btn medium primary-bg">
				<span class="button-content">
					<i class="glyph-icon icon-undo float-left"></i>
					取消
				</span>
			</a>
		</div>
	</div>
<?php $this->endWidget(); ?>

</div>
<!-- form -->
