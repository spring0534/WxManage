<div class="explain-col">
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'htmlOptions' => array(
		'style' => 'margin: 0'
	),
)); ?>
	
	<?php echo $form->label($model,'id'); ?>: &nbsp;
	<?php echo $form->textField($model,'id',array('class'=>'input-text')); ?>
	
	
	<?php echo $form->label($model,'groupname'); ?>: &nbsp;
	<?php echo $form->textField($model,'groupname',array('class'=>'input-text')); ?>
	
	

	<button class="btn primary-bg medium" style="margin-left: 50px;">
		<span class="button-content"><i class="glyph-icon icon-search"></i>查询</span>
	</button>

<?php $this->endWidget(); ?>

</div>
<!-- search-form -->
