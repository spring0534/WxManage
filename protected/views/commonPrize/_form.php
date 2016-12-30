
<div class="form">

<?php
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'common-prize-form',
	'htmlOptions'=>array(
		'class'=>'col-md-10 center-margin'
	),
	'enableAjaxValidation'=>false
));
?>

<div class="infobox warning-bg" id='msgtip' style='display: none'>
		<p>
			<i class="glyph-icon icon-exclamation mrg10R"></i><?php echo $form->errorSummary($model); ?>
</p>
	</div>
	<?php if($model->isNewRecord){?>
	<?php echo CHtml::hiddenField(chtmlName($model, 'aid'),$aid)?>
	<?php }else{?>
	<?php echo  $form->hiddenField($model,'aid') ?>
	<?php }?>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'name'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50,'class'=>'col-md-6 float-left','data-required'=>'true','data-trigger'=>'change')); ?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'level'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'level',array('class'=>'col-md-6 float-left')); ?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'num'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'num',array('class'=>'col-md-6 float-left')); ?>
		</div>
	</div>
		<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'rate'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'rate',array('size'=>10,'maxlength'=>10,'class'=>'col-md-6 float-left')); ?>
			<p>中奖概率，不超过1,此处以小数点表示,如概率为2%,则填写0.2</p>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'pic'); ?> </label>
		</div>
		<div class="form-input col-md-10">
		<?php echo imageUpdoad(chtmlName($model, 'pic'),$model->pic, 'pic', array('id'=>'pic'))?>

		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'note'); ?> </label>
		</div>
		<div class="form-input col-md-10">
		<?php echo ueditor(chtmlName($model, 'note'),$model->note,'note')?>

		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'gain_num'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'gain_num',array('class'=>'col-md-6 float-left')); ?>
		</div>
	</div>

	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'status'); ?> </label>
		</div>
		<div class="form-input col-md-10">
		<?php echo CHtml::groupButton(chtmlName($model, 'status'), $model->isNewRecord?1:$model->status, array(0=>'无效',1=>'有效',2=>'数量已使用完'))?>

		</div>
	</div>

	<div class="button-pane">
		<div class="form-input col-md-10 col-md-offset-2">
			<button class="btn primary-bg medium" onclick="javascript:return $('#common-prize-form').parsley( 'validate' );">
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
