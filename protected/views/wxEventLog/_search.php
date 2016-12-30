<div class="explain-col">
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'htmlOptions' => array(
		'style' => 'margin: 0'
	),
)); ?>
	
	
	
	<?php echo $form->label($model,'wx_id'); ?>: &nbsp;
	<?php echo $form->textField($model,'wx_id',array('class'=>'input-text')); ?>
	
	
	<?php echo $form->label($model,'wx_ghid'); ?>: &nbsp;
	<?php echo $form->textField($model,'wx_ghid',array('class'=>'input-text')); ?>
	
	
	<?php echo $form->label($model,'keyword'); ?>: &nbsp;
	<?php echo $form->textField($model,'keyword',array('class'=>'input-text')); ?>
	
	
	<?php echo $form->label($model,'content'); ?>: &nbsp;
	<?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50)); ?>
	
	
	<?php echo $form->label($model,'tm'); ?>: &nbsp;
	<?php echo $form->textField($model,'tm',array($htmlOptions)); ?>
	
	<button class="btn primary-bg medium" style="margin-left: 50px;">
		<span class="button-content"><i class="glyph-icon icon-search"></i>查询</span>
	</button>

<?php $this->endWidget(); ?>

</div>
<!-- search-form -->
