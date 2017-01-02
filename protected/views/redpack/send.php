<div id="page-title">
	<h3>
		现金红包
		<small> >>订单审核 </small>
	</h3>
</div>
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
    <?php echo $form->hiddenField($model, 'id');?>
    <div class="form-row">
		<div class="form-label col-md-2">
			<label for="">订单编号</label>
		</div>
		<div class="form-input col-md-10" style="font-size: 20px;">
			<?php echo $model->tb_order_no; ?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for="">金额(元)</label>
		</div>
		<div class="form-input col-md-3">
			<?php echo $form->textField($model,'amount',array('size'=>10,'maxlength'=>10,'class'=>'col-md-6 float-left','onkeyup'=>"this.value=this.value.replace(/[^0-9.]/g,'')")); ?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for="">状态</label>
		</div>
		<div class="form-input col-md-3">
			<?php echo $form->dropDownList($model,'status',array('1'=>'等待审核','2'=>'审核通过','0'=>'审核失败','3'=>'派发失败'))?><span>审核通过同时会给用户派发红包</span>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for="">备注</label>
		</div>
		<div class="form-input col-md-5">
			<?php echo $form->textArea($model,'remark')?><span>备注信息将发送给用户</span>
		</div>
	</div>
	<div class="button-pane">
		<div class="form-input col-md-10 col-md-offset-2">
			<button class="btn primary-bg medium">
				<span class="button-content"><i class="glyph-icon icon-check float-left"></i>保存</span>
			</button>
		</div>
	</div>
<?php $this->endWidget(); ?>
</div>
<!-- form -->
