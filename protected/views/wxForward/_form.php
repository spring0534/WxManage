<?php
/* @var $this WxForwardController */
/* @var $model WxForward */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'wx-forward-form',
	'htmlOptions' =>array('class' => 'col-md-10 center-margin'),
	'enableAjaxValidation'=>false,
)); ?>

<div class="infobox warning-bg" id='msgtip' style='display: none'>
    <p><i class="glyph-icon icon-exclamation mrg10R"></i><?php echo $form->errorSummary($model); ?>
</p>
</div>

	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'name'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50,'class'=>'col-md-6 float-left','data-required'=>'true')); ?>
		</div>
	</div>




	<!--



	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'match_mode'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'match_mode',array('class'=>'col-md-6 float-left')); ?>
		</div>
	</div>
 -->

	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'url'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'url',array('size'=>60,'maxlength'=>100,'class'=>'col-md-6 float-left','data-required'=>'true')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'token'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'token',array('size'=>60,'maxlength'=>100,'class'=>'col-md-6 float-left','data-required'=>'true')); ?>
		</div>
	</div>

<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'keyword'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'keyword',array('size'=>20,'maxlength'=>20,'class'=>'col-md-6 float-left','placeholder'=>"如果是固定关键词请填写关键词")); ?>
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
			<label for="">微应用类型 </label>
		</div>
		<div class="form-input col-md-10">
		<?php echo CHtml::groupButton(chtmlName($model, 'ghid'), $model->isNewRecord?gh()->ghid:$model->ghid, array('public'=>'公共',gh()->ghid=>'私有'))?>

		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'status'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo CHtml::switchButton(chtmlName($model, 'status'), $model->status,array(1=>'正常',0=>'禁用'));?>
		</div>
	</div>



	<div class="button-pane">
		<div class="form-input col-md-10 col-md-offset-2">
			<button class="btn primary-bg medium"
				onclick="javascript:return $('#wx-forward-form').parsley( 'validate' );">
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