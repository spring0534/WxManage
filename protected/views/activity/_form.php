
<div class="form-wizard">
	<ul class="anchor">
		<li>
			<a href="#step-1" class="selected" isdone="1" rel="1">
				<label class="wizard-step">1</label>
				<span class="wizard-description">

					<?php if($model->isNewRecord){?>
					1、添加活动
					<?php }else{?>
						1、编辑活动
					<?php }?>

					<small>填写创建活动的相关配置</small>
				</span>
			</a>
		</li>
		<li>
			<a href="#step-2" class="disabled" isdone="1" rel="2">
				<label class="wizard-step">2</label>
				<span class="wizard-description">
					2、配置活动
					<small>为创建的活动配置自定义属性</small>
				</span>
			</a>
		</li>
	</ul>
	<div class="stepContainer" style="height: 297px;">
		<div id="step-1" class="content">
			<div class="form">

<?php

$form=$this->beginWidget('CActiveForm', array(
	'id'=>'activity-form',
	'htmlOptions'=>array(
		'class'=>'col-md-6 center-margin'
	),
	'enableAjaxValidation'=>false
));
?>

<div class="infobox warning-bg" id='msgtip' style='display: none'>
					<p>
						<i class="glyph-icon icon-exclamation mrg10R"></i><?php echo $form->errorSummary($model); ?>
</p>
				</div>
	<?php echo $form->hiddenField($model,'type',array('size'=>32,'maxlength'=>32)); ?>
	<div class="form-row">
					<div class="form-label col-md-3">
						<label for=""><?php echo $form->labelEx($model,'type'); ?></label>
					</div>
					<div class="form-input col-md-8">
			<?php if($model->isNewRecord){?>
			<?php   echo $form->dropDownList($model, 'type', CHtml::listData($plugin, 'ptype', 'plugin.name'),array('data-trigger'=>"change", 'data-required'=>"true",'empty'=>'请选择微应用类型'));?>
			<?php }else{?>
			<?php   echo $form->dropDownList($model, 'type', CHtml::listData($plugin, 'ptype', 'plugin.name'),array('data-trigger'=>"change", 'data-required'=>"true",'empty'=>'请选择微应用类型','disabled'=>'disabled'));?>
			<?php }?>
		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-3">
			<?php echo $form->labelEx($model,'akey'); ?>
		</div>
					<div class="form-input col-md-8">
					<?php if($model->isNewRecord){?>
					<?php echo $form->textField($model,'akey',array('size'=>50,'maxlength'=>50,'data-type'=>'letter','placeholder'=>'不输入则自动生成(不支持中文)')); ?>

					<?php }else{?>
						<?php echo $form->textField($model,'akey',array('size'=>50,'maxlength'=>50,'disabled'=>'disabled')); ?>
					<?php }?>
		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-3">
						<label for=""><?php echo $form->labelEx($model,'title'); ?></label>
					</div>
					<div class="form-input col-md-8">
			<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255,'data-trigger'=>"change", 'data-required'=>"true")); ?>
		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-3">
			<?php echo $form->labelEx($model,'description'); ?>
		</div>
					<div class="form-input col-md-8">
			<?php echo $form->textArea($model,'description',array('size'=>60,'maxlength'=>500,)); ?>
		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-3">
			<?php echo $form->labelEx($model,'starttime'); ?>
		</div>
					<div class="form-input col-md-8">
			<?php echo calendar(chtmlName($model, 'starttime'),isset($model->starttime)?$model->starttime:'','YYYY-MM-DD hh:mm:ss',"240px","data-trigger='change' data-required='true'");?>
		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-3">
			<?php echo $form->labelEx($model,'endtime'); ?>
		</div>
					<div class="form-input col-md-8">
			<?php echo calendar(chtmlName($model, 'endtime'),isset($model->endtime)?$model->endtime:'','YYYY-MM-DD hh:mm:ss');?>
		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-3">
			<?php echo $form->labelEx($model,'status'); ?>
		</div>
					<div class="form-input col-md-8">
			<?php echo CHtml::switchButton(chtmlName($model, 'status'), isset($model->status)?$model->status:1, array('1'=>'YES','0'=>'NO'))?>
		</div>
				</div>
				<div class="button-pane text-center">

					<button type="reset" class="btn medium primary-bg text-transform-upr font-size-11" id="demo-form-valid" >
						<span class="button-content"><i class="glyph-icon icon-undo float-left"></i>重置</span>
					</button>
					<button onclick="javascript:return $('#activity-form').parsley( 'validate' );" type="submit" class="btn medium primary-bg text-transform-upr font-size-11" id="demo-form-valid" >
						<span class="button-content"><?php echo $model->isNewRecord ? '<i class="glyph-icon icon-plus float-left"></i>创建活动' : '<i class="glyph-icon icon-save float-left"></i>保存'; ?></span>
					</button>
				</div>

<?php $this->endWidget(); ?>

</div>
		</div>
	</div>
	<!-- form -->
</div>
<script>
if($('.errorSummary').length>0){
	$('#msgtip').slideDown();

}
$('body').click(function(){
	$('#msgtip').slideUp();
});
</script>