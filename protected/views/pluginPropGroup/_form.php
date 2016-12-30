
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'plugin-prop-group-form',
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
			<label for=""><?php echo $form->labelEx($model,'name'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>
	<?php
	if($model->isNewRecord){?>
		<?php echo CHtml::hiddenField(get_class($model).'[ptype]',$ptype);?>
	<?php }else{?>
		<?php echo $form->hiddenField($model,'ptype'); ?>
	<?php }
	?>



	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'img'); ?> </label>
		</div>
		<div class="form-input col-md-10">
		<?php  echo imageUpdoad(get_class($model).'[img]',$model->img,'img',array('id'=>'img'),'image')?>

		</div>
	</div>


	<div class="button-pane">
		<div class="form-input col-md-10 col-md-offset-2">
			<button class="btn primary-bg medium"
				onclick="javascript:return $('#plugin-prop-group-form').parsley( 'validate' );">
				<span class="button-content"><i class="glyph-icon icon-check float-left"></i><?php echo $model->isNewRecord ? '提交' : '保存'; ?>
</span>
			</button>
			<a
				href="javascript:return $('#plugin-prop-group-form').reset();"
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
