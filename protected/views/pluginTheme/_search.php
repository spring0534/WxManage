<div class="explain-col">
<?php
$form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route), 
	'method'=>'get', 
	'htmlOptions'=>array(
		'style'=>'margin: 0'
	)
));
?>
	

	
	<?php echo $form->label($model,'name'); ?>: &nbsp;
	<?php echo $form->textField($model,'name',array('class'=>'input-text')); ?>
	
	<?php echo $form->label($model,'scr_theme'); ?>: &nbsp;
	<?php echo $form->textField($model,'scr_theme',array('class'=>'input-text')); ?>
	
	<?php echo $form->label($model,'wx_theme'); ?>: &nbsp;
	<?php echo $form->textField($model,'wx_theme',array('class'=>'input-text')); ?>
	
	<?php echo $form->label($model,'status'); ?>: &nbsp;
	<?php echo $form->textField($model,'status',array($htmlOptions)); ?>
	
	<button class="btn primary-bg medium" style="margin-left: 50px;">
		<span class="button-content"><i class="glyph-icon icon-search"></i>查询</span>
	</button>

<?php $this->endWidget(); ?>

</div>
<!-- search-form -->
