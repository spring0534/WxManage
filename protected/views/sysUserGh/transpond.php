
<div id="page-title">
	<h3>
		公众号设置
		<small> 消息转发配置 </small>
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
	<div class="infobox notice-bg">
		<h4 class="infobox-title">说明</h4>
		<p>启用消息转后，所有微信发来的消息都会转发到配置的接口</p>


	</div>

	<!-- 页面标题 -->
	<div>
<?php
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'sys-user-gh-form',
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
			<div class="form-label col-md-2">
			<?php echo $form->labelEx($model,'zf_api_url'); ?>
		</div>
			<div class="form-input col-md-6">
			<?php echo $form->textField($model, 'zf_api_url',array( 'size'=>60,'maxlength'=>200)); ?>

		</div>
		</div>
		<div class="form-row">
			<div class="form-label col-md-2">
				<?php echo $form->labelEx($model,'zf_api_token'); ?>
		</div>
			<div class="form-input col-md-6">
			<?php echo $form->textField($model,'zf_api_token',array('size'=>60,'maxlength'=>100)); ?>

		</div>
		</div>
		<div class="form-row">
			<div class="form-label col-md-2">
				<label for=""><?php echo $form->labelEx($model,'transpond'); ?> </label>
			</div>
			<div class="form-input col-md-10">
			<?php echo CHtml::switchButton(chtmlName($model, 'transpond'), $model->isNewRecord?0:$model->transpond,array(1=>'启用',0=>'禁用'));?>
		</div>
		</div>

		<div class="button-pane">
			<div class="form-input col-md-6 col-md-offset-2">
				<button class="btn primary-bg medium" onclick="javascript:return $('#sys-user-gh-form').parsley( 'validate' );">
					<span class="button-content">
						<i class="glyph-icon icon-check float-left"></i><?php echo $model->isNewRecord ? '提交' : '保存'; ?>
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
</div>