<?php
/* @var $this WxSpreadQrcodeController */
/* @var $model WxSpreadQrcode */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'wx-spread-qrcode-form',
	'htmlOptions' =>array('class' => 'col-md-10 center-margin'),
	'enableAjaxValidation'=>false,
)); ?>

<div class="infobox warning-bg" id='msgtip' style='display: none'>
    <p><i class="glyph-icon icon-exclamation mrg10R"></i><?php echo $form->errorSummary($model); ?>
</p>
</div>

	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'qtype'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php if($model->isNewRecord){
				echo CHtml::dropDownList(chtmlName($model, qtype), $model->qtype, array(1=>'临时二维码',2=>'永久二维码'),array('class'=>'col-md-6'));
			}else{
				echo CHtml::dropDownList(chtmlName($model, qtype), $model->qtype, array(1=>'临时二维码',2=>'永久二维码'),array('class'=>'col-md-6','disabled'=>'true'));
			}?>

		</div>
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
			<label for=""><?php echo $form->labelEx($model,'reply_id'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo CHtml::dropDownList(chtmlName($model, reply_id), $model->reply_id, CHtml::listData(WxMaterial::model()->findAllByAttributes(array('ghid'=>gh()->ghid)),'id', 'title'),array('empty'=>'不触发回复','class'=>'col-md-6 float-left r-select'))?>
		</div>
	</div>


	<div class="button-pane">
		<div class="form-input col-md-10 col-md-offset-2">
			<button class="btn primary-bg medium"
				onclick="javascript:return $('#wx-spread-qrcode-form').parsley( 'validate' );">
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