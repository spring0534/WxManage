<div class="form">
<?php
$form = $this->beginWidget('CActiveForm', array(
	'id' => 'sys-user-form',
	'htmlOptions' => array(
		'class' => "col-md-10 center-margin"
	),
	'enableAjaxValidation' => false
));
?>
<div class="infobox warning-bg" id='msgtip' style='display: none'>
    <p><i class="glyph-icon icon-exclamation mrg10R"></i><?php echo $form->errorSummary($model); ?></p>
</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""> 姓名: </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'realname',array('size'=>25,'maxlength'=>25,'class'=>"col-md-6 float-left",'data-trigger'=>"change",'data-required'=>"true"));?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""> 手机号: </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'phone',array('size'=>25,'maxlength'=>25,'class'=>"col-md-6 float-left"));?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""> 公司: </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'company',array('size'=>25,'maxlength'=>25,'class'=>"col-md-6 float-left"));?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""> 职位: </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'position',array('size'=>50,'maxlength'=>50,'class'=>"col-md-6 float-left"));?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""> 家乡: </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'address',array('size'=>50,'maxlength'=>50,'class'=>"col-md-6 float-left"));?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""> QQ: </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'qq',array('size'=>50,'maxlength'=>50,'class'=>"col-md-6 float-left"));?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""> 微信号: </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'wxname',array('size'=>50,'maxlength'=>50,'class'=>"col-md-6 float-left"));?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""> 邮箱: </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'email',array('size'=>50,'maxlength'=>50,'class'=>"col-md-6 float-left"));?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""> 资源: </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'resource',array('size'=>50,'maxlength'=>50,'class'=>"col-md-6 float-left"));?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""> 需求: </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'demand',array('size'=>50,'maxlength'=>50,'class'=>"col-md-6 float-left"));?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-input col-md-10 col-md-offset-2">
			<button class="btn primary-bg medium"
				onclick="javascript:return $('#sys-user-form').parsley( 'validate' );">
				<span class="button-content"><i class="glyph-icon icon-check float-left"></i><?php echo $model->isNewRecord ? '提交' : '保存'; ?></span>
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