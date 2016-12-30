<div class="explain-col">
<?php
$form = $this->beginWidget('CActiveForm', array(
	'action' => Yii::app()->createUrl($this->route),
	'method' => 'post',
	'htmlOptions' => array(
		'style' => "margin: 0"
	),
));
?>
		<?php echo $form->label($model,'title'); ?>: &nbsp;
		<?php echo $form->textField($model,'title',array('class'=>'input-text')); ?>

		<?php echo $form->label($model,'msg_type'); ?>: &nbsp;
		<?php echo $form->dropDownList($model,'msg_type',Yii::app()->params['msg_type'],array('empty'=>'所有'));?>

		<?php echo CHtml::label('创建时间','ooo'); ?>: &nbsp;
		<?php echo calendar(chtmlName($model, 'ctm').'[1]','', 'YYYY-MM-DD','180px');?>--
		<?php echo calendar(chtmlName($model, 'ctm').'[2]','', 'YYYY-MM-DD','180px');?>
		
		<?php echo $form->label($model,'status'); ?>: &nbsp;
		<?php 	echo $form->dropDownList($model,'status',array(0=>'禁用',1=>'正常'),array('class'=>'input-text','empty'=>'所有'));?>
		<button class="btn primary-bg medium" style="margin-left: 50px;">
			<span class="button-content"><i class="glyph-icon icon-search"></i>查询</span>
		</button>
<?php $this->endWidget(); ?>		
</div>
<!-- search-form -->
