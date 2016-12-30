<div class="explain-col">
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'htmlOptions' => array(
		'style' => 'margin: 0'
	),
)); ?>
	

	<?php echo $form->label($model,'sncode'); ?>: &nbsp;
	<?php echo $form->textField($model,'sncode',array('class'=>'input-text')); ?>
	
	
	<?php echo $form->label($model,'snpwd'); ?>: &nbsp;
	<?php echo $form->textField($model,'snpwd',array('class'=>'input-text')); ?>
	

	<?php echo $form->label($model,'status'); ?>: &nbsp;
	<?php echo $form->textField($model,'status',array($htmlOptions)); ?>
	

	<button class="btn primary-bg medium" style="margin-left: 50px;">
		<span class="button-content"><i class="glyph-icon icon-search"></i>查询</span>
	</button>

<?php $this->endWidget(); ?>

</div>
<!-- search-form -->
