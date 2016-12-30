<?php
/* @var $this SysUserGhController */
/* @var $model SysUserGh */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sys-user-gh-form',
	'htmlOptions' =>array('class' => 'col-md-10 center-margin'),
	'enableAjaxValidation'=>false,
)); ?>

<div class="infobox warning-bg" id='msgtip' style='display: none'>
    <p><i class="glyph-icon icon-exclamation mrg10R"></i><?php echo $form->errorSummary($model); ?>
</p>
</div>

	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'ghid'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'ghid',array('size'=>50,'maxlength'=>50,'placeholder'=>'ghid')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'name'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50,'placeholder'=>'name')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'icon_url'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'icon_url',array('size'=>60,'maxlength'=>200,'placeholder'=>'icon_url')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'qrcode'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'qrcode',array('size'=>60,'maxlength'=>200,'placeholder'=>'qrcode')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'qrcode_small'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'qrcode_small',array('size'=>60,'maxlength'=>200,'placeholder'=>'qrcode_small')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'type'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'type',array('placeholder'=>'type')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'wxh'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'wxh',array('size'=>50,'maxlength'=>50,'placeholder'=>'wxh')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'company'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'company',array('size'=>60,'maxlength'=>100,'placeholder'=>'company')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'desc'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'desc',array('size'=>60,'maxlength'=>200,'placeholder'=>'desc')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'tenancy'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'tenancy',array('placeholder'=>'tenancy')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'login_name'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'login_name',array('size'=>50,'maxlength'=>50,'placeholder'=>'login_name')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'login_pwd'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'login_pwd',array('size'=>50,'maxlength'=>50,'placeholder'=>'login_pwd')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'api_url'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'api_url',array('size'=>60,'maxlength'=>200,'placeholder'=>'api_url')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'api_token'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'api_token',array('size'=>60,'maxlength'=>100,'placeholder'=>'api_token')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'zf_api_url'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'zf_api_url',array('size'=>60,'maxlength'=>200,'placeholder'=>'zf_api_url')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'zf_api_token'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'zf_api_token',array('size'=>60,'maxlength'=>200,'placeholder'=>'zf_api_token')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'appid'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'appid',array('size'=>50,'maxlength'=>50,'placeholder'=>'appid')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'appsecret'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'appsecret',array('size'=>50,'maxlength'=>50,'placeholder'=>'appsecret')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'notes'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'notes',array('size'=>50,'maxlength'=>50,'placeholder'=>'notes')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'status'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'status',array('placeholder'=>'status')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'open_portal'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'open_portal',array('placeholder'=>'open_portal')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'open_msite'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'open_msite',array('placeholder'=>'open_msite')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'ctm'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'ctm',array('placeholder'=>'ctm')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'utm'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'utm',array('placeholder'=>'utm')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'operator_uid'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'operator_uid',array('placeholder'=>'operator_uid')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'interact'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'interact',array('placeholder'=>'interact')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'tenant_id'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'tenant_id',array('placeholder'=>'tenant_id')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'ec_cid'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'ec_cid',array('size'=>50,'maxlength'=>50,'placeholder'=>'ec_cid')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'oauth'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'oauth',array('placeholder'=>'oauth')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'access_token'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'access_token',array('size'=>60,'maxlength'=>200,'placeholder'=>'access_token')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'at_expires'); ?>:: </label>
		</div>
		<div class="form-input col-md-8">
			<?php echo $form->textField($model,'at_expires',array('placeholder'=>'at_expires')); ?>
		</div>
	</div>


	<div class="button-pane">
		<div class="form-input col-md-8 col-md-offset-2">
			<button class="btn primary-bg medium"
				onclick="javascript:return $('#sys-user-gh-form').parsley( 'validate' );">
				<span class="button-content"><i class="glyph-icon icon-check float-left"></i><?php echo $model->isNewRecord ? '提交' : '保存'; ?>
</span>
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