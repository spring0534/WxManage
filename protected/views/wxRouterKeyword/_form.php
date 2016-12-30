<?php
/* @var $this WxRouterKeywordController */
/* @var $model WxRouterKeyword */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'wx-router-keyword-form',
	'htmlOptions' =>array('class' => 'col-md-10 center-margin'),
	'enableAjaxValidation'=>false,
)); ?>

<div class="infobox warning-bg" id='msgtip' style='display: none'>
    <p><i class="glyph-icon icon-exclamation mrg10R"></i><?php echo $form->errorSummary($model); ?>
</p>
</div>

<?php if(($msg_type=='text'&&$model->isNewRecord)||($model->msg_type=='text'&&!$model->isNewRecord)){?>
<div class="msg_text" >
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'keyword'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'keyword',array('size'=>20,'maxlength'=>20,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'match_mode'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo CHtml::dropDownList(chtmlName($model,'match_mode'), $model->match_mode, array(1=>'完全匹配',2=>'包含匹配'),array('class'=>'col-md-6 float-left'))?>
		</div>
	</div>

</div>
<?php }?>
  <div class="menu_type_div"  >

	    <div class="form-row">
			<div class="form-label col-md-2">
				<label for=""><?php echo $form->labelEx($model,'reply_type'); ?> </label>
			</div>
			<div class="form-input col-md-10">
				<?php echo CHtml::dropDownList(get_class($model)."[reply_type]", $model->reply_type, array(1=>'素材库内容',2=>'第三方接口'),array('class'=>'col-md-6 float-left r-select'))?>
			</div>
		</div>

		<div class="form-row"  <?php if($model->reply_type!=1&&!empty($model->reply_type))echo 'style="display:none"';?> >
			<div class="form-label col-md-2" >
				<label for="">请选择素材 </label>
			</div>
			<div class="form-input col-md-10">

				<?php echo CHtml::dropDownList(get_class($model)."[reply_id][1]", $model->reply_id, CHtml::listData(WxMaterial::model()->findAllByAttributes(array('ghid'=>gh()->ghid)),'id', 'title'),array('class'=>'col-md-6 float-left r-select'))?>
			</div>
		</div>
		<div class="form-row"  <?php if($model->reply_type!=2&&!empty($model->reply_type))echo 'style="display:none"';?>>
			<div class="form-label col-md-2">
				<label for="">请选择第三方接口</label>
			</div>
			<div class="form-input col-md-10">
				<?php echo CHtml::dropDownList(get_class($model)."[reply_id][2]", $model->reply_id,CHtml::listData(WxForward::getMylist(), 'id', 'name'),array('class'=>'col-md-6 float-left r-select'))?>
			</div>
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


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'note'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'note',array('size'=>60,'maxlength'=>100,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>
	<?php echo $form->hiddenField($model,'msg_type',array('value'=>$msg_type)); ?>


	<div class="button-pane">
		<div class="form-input col-md-10 col-md-offset-2">
			<button class="btn primary-bg medium"
				onclick="javascript:return $('#wx-router-keyword-form').parsley( 'validate' );">
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
$('.r-select').change(function(){
	$(this).closest('div.form-row').nextAll('div.form-row').slideUp().eq($(this).get(0).selectedIndex).slideDown();
});

</script>