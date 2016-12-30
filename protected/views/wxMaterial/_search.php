<div class="explain-col">
<?php
$form = $this->beginWidget('CActiveForm', array(
	'action' => Yii::app()->createUrl($this->route),
	'method' => 'get',
	'htmlOptions' => array(
		'style' => "margin: 0"
	),
));
?>
		<?php echo $form->label($model,'id'); ?>: &nbsp;
		<?php echo $form->textField($model,'id',array('class'=>'input-text')); ?>

		<?php echo $form->label($model,'title'); ?>: &nbsp;
		<?php echo $form->textField($model,'title',array('class'=>'input-text')); ?>

		<?php echo $form->label($model,'msg_type'); ?>: &nbsp;
		<?php echo $form->textField($model,'msg_type',array('class'=>'input-text')); ?>

		<?php echo $form->label($model,'ghid'); ?>: &nbsp;
		<?php echo $form->textField($model,'ghid',array('class'=>'input-text')); ?>

		<?php echo $form->label($model,'ctm'); ?>: &nbsp;
		<?php echo $form->textField($model,'ctm',array('class'=>'input-text')); ?>

		<?php echo $form->label($model,'utm'); ?>: &nbsp;
		<?php echo $form->textField($model,'utm',array('class'=>'input-text')); ?>

		<?php echo $form->label($model,'status'); ?>: &nbsp;
		<?php echo $form->textField($model,'status',array('class'=>'input-text')); ?>
		<button class="btn primary-bg medium" style="margin-left: 50px;">
			<span class="button-content"><i class="glyph-icon icon-search"></i>查询</span>
		</button>
<?php $this->endWidget(); ?>		
</div>
<!-- search-form -->

</style>