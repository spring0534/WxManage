
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-reg-form',
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
			<label for=""><?php echo $form->labelEx($model,'aid'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'aid',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'wxid'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'wxid',array('size'=>50,'maxlength'=>50,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'src_openid'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'src_openid',array('size'=>50,'maxlength'=>50,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'ghid'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'ghid',array('size'=>20,'maxlength'=>20,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'username'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'username',array('size'=>20,'maxlength'=>20,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'phone'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'phone',array('size'=>20,'maxlength'=>20,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'company'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'company',array('size'=>40,'maxlength'=>40,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'prize'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'prize',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'relate_aid'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'relate_aid',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'sncode'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'sncode',array('size'=>50,'maxlength'=>50,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'qrcode'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'qrcode',array('size'=>60,'maxlength'=>200,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'qrcode_small'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'qrcode_small',array('size'=>60,'maxlength'=>200,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'score'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'score',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'total_time'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'total_time',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'ext_info'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'ext_info',array('size'=>60,'maxlength'=>5000,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'status'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'status',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'ip'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'ip',array('size'=>50,'maxlength'=>50,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'ua'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'ua',array('size'=>60,'maxlength'=>500,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'ctm'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'ctm',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'utm'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'utm',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'tags'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textArea($model,'tags',array('rows'=>6, 'cols'=>50)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'notes'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textArea($model,'notes',array('rows'=>6, 'cols'=>50)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'flag'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'flag',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'form_id'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'form_id',array($htmlOptions)); ?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-input col-md-10 col-md-offset-2">
			<button class="btn primary-bg medium"
				onclick="javascript:return $('#user-reg-form').parsley( 'validate' );">
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
