
<div class="form">

<?php

$form=$this->beginWidget('CActiveForm', array(
	'id'=>'plugin-theme-form',
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
	<div class="form-row">

		<?php if($model->isNewRecord){?>
		<?php echo CHtml::hiddenField(chtmlName($model, 'ptype'),$ptype)?>
		<?php }else{?>
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'ptype'); ?> </label>
		</div>
		<div class="form-input col-md-10">
		<?php echo $form->textField($model,'ptype',array('size'=>20,'maxlength'=>20,'class'=>'col-md-6 float-left','disabled'=>'disabled')); ?>
		</div>
		<?php }?>


	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'name'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'simple_memo'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'simple_memo',array('size'=>60,'maxlength'=>200,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'detail_memo'); ?> </label>
		</div>
		<div class="form-input col-md-10">
		<?php echo CHtml::textArea(chtmlName($model, 'detail_memo'),$model->detail_memo,array('class'=>'col-md-6 float-left','style'=>"margin: 0px; height: 172px; width: 566px;"))?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'pic1'); ?> </label>
		</div>
		<div class="form-input col-md-10">
		<?php echo imageUpdoad(chtmlName($model, 'pic1'),$model->pic1, 'pic1', array('id'=>'pic1'))?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'pic2'); ?> </label>
		</div>
		<div class="form-input col-md-10">
		<?php echo imageUpdoad(chtmlName($model, 'pic2'),$model->pic2, 'pic2', array('id'=>'pic2'))?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'scr_theme'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'scr_theme',array('size'=>60,'maxlength'=>255,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'wx_theme'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'wx_theme',array('size'=>60,'maxlength'=>255,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>

	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'scope'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo CHtml::groupButton(chtmlName($model, 'scope'), $model->scope, array('1'=>'盒子','2'=>'微信机'))?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'status'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo CHtml::switchButton(chtmlName($model, 'status'),$model->isNewRecord?1:$model->status ,array('1'=>'启用','0'=>'禁用'))?>
		</div>
	</div>


	<div class="button-pane">
		<div class="form-input col-md-10 col-md-offset-2">
			<button class="btn primary-bg medium" onclick="javascript:return $('#plugin-theme-form').parsley( 'validate' );">
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
