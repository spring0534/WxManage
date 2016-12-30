
<div id="page-title">
	<h3>
		微应用管理
		<small> >>对移植过的微应用进行更新 </small>
	</h3>
	<div id="breadcrumb-right">
		<div class="float-right">
			<a href="<?php echo WEB_URL.Yii::app()->request->getUrl();?>" class="btn medium bg-white tooltip-button black-modal-60 mrg5R" data-placement="bottom" data-original-title="刷新">
				<span class="button-content">
					<i class="glyph-icon icon-refresh"></i>
				</span>
			</a>
		</div>
	</div>
</div>
<div id="page-content">
	<div style="padding-bottom: 10px">
		<a href="<?php U('admin');?>" class="btn medium primary-bg btn_back">
			<span class="button-content">
				<i class="glyph-icon icon-mail-reply float-left"></i>
				返回
			</span>
		</a>
	</div>
<div class="form-wizard">
	<div class="stepContainer" style="height: 297px;">
		<div id="step-1" class="content">
			<div class="form">

<?php
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'plugin-form',
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
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for=""><?php echo $form->labelEx($model,'ptype'); ?> </label>
					</div>
					<div class="form-input col-md-10">
					<?php if($model->isNewRecord){?>
					<?php echo $form->textField($model,'ptype',array('size'=>20,'maxlength'=>20,'class'=>'col-md-6 float-left','data-trigger'=>"change", 'data-type'=>'letter','placeholder'=>'只能输入字母','data-required'=>"true")); ?>
					<?php }else{?>
						<?php echo $form->textField($model,'ptype',array('size'=>20,'maxlength'=>20,'class'=>'col-md-6 float-left','data-trigger'=>"change", 'data-type'=>'letter','placeholder'=>'只能输入字母','data-required'=>"true",'disabled'=>'disabled')); ?>
					<?php }?>

		</div>
				</div>

				<div class="form-row">
					<div class="form-label col-md-2">
						<label for=""><?php echo $form->labelEx($model,'status'); ?> </label>
					</div>
					<div class="form-input col-md-10">
			<?php echo CHtml::groupButton(chtmlName($model, 'status'), $model->isNewRecord?2:$model->status, array(0=>'未发布',1=>'已发布',2=>'内部测试',3=>'已废弃'))?>
		</div>
				</div>


				<div class="form-row">
					<div class="form-label col-md-2">
						<label for=""><?php echo $form->labelEx($model,'cate'); ?> </label>
					</div>
					<div class="form-input col-md-10">

			<?php echo CHtml::groupButton(chtmlName($model, 'cate'), $model->isNewRecord?5:$model->cate, Yii::app()->params['plugin_cate'])?>
		</div>
				</div>

				<div class="form-row">
					<div class="form-label col-md-2">
						<label for="">启用SN管理 </label>
					</div>
					<div class="form-input col-md-10">

			<?php echo CHtml::switchButton('setting[sn]', $model->setting['sn'])?>
		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for="">启用奖项管理 </label>
					</div>
					<div class="form-input col-md-10">
			<?php echo CHtml::switchButton('setting[prize]',  $model->setting['prize'])?>
		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for="">启用高级管理 </label>
					</div>
					<div class="form-input col-md-10">
			<?php echo CHtml::switchButton('setting[advancedManage]',  $model->setting['advancedManage'])?>
		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for=""><?php echo $form->labelEx($model,'versions'); ?> </label>
					</div>
					<div class="form-input col-md-10">
			<?php echo CHtml::groupButton(chtmlName($model, 'versions'), $model->isNewRecord?'2':$model->versions, array(2=>'2.0',1=>'1.0'))?>
		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for="">开发者</label>
					</div>
					<div class="form-input col-md-10">
						<?php echo CHtml::groupButton(chtmlName($model, 'uid'), $model->isNewRecord?user()->id:$model->uid, CHtml::listData(SysUser::model()->findAllByAttributes(array('groupid'=>2)), 'id','nickname'))?>

					</div>
				</div>
				<div class="button-pane text-center">
					<button onclick="javascript:return $('#plugin-form').parsley( 'validate' );" type="submit" class="btn large primary-bg text-transform-upr font-size-11" id="demo-form-valid" >
						<span class="button-content"><?php echo $model->isNewRecord ? '->开始更新移植' : '保存'; ?></span>
					</button>
				</div>


<?php $this->endWidget(); ?>

</div>
			<!-- form -->
		</div>
	</div>
</div>
<style>
.multiple-select a {
	margin-bottom: 10px;
}
</style>
<script>
if($('.errorSummary').length>0){
	$('#msgtip').slideDown();

}
$('body').click(function(){
	$('#msgtip').slideUp();
});


</script>
</div>
