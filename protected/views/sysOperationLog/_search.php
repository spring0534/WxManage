<div class="explain-col">
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'htmlOptions' => array(
		'style' => 'margin: 0'
	),
)); ?>
	

	<?php echo $form->label($model,'uid'); ?>: &nbsp;
	<?php echo $form->textField($model,'uid',array($htmlOptions)); ?>
	
	
	<?php echo $form->label($model,'ghid'); ?>: &nbsp;
	<?php echo $form->textField($model,'ghid',array($htmlOptions)); ?>
	
	
	<?php echo $form->label($model,'action'); ?>: &nbsp;
	<?php echo $form->textField($model,'action',array('class'=>'input-text')); ?>
	
	
	<?php echo $form->label($model,'controller'); ?>: &nbsp;
	<?php echo $form->textField($model,'controller',array('class'=>'input-text')); ?>
	
	
	<?php echo $form->label($model,'module'); ?>: &nbsp;
	<?php echo $form->textField($model,'module',array('class'=>'input-text')); ?>
	
	
	<?php echo $form->label($model,'optime'); ?>: &nbsp;
	<?php echo $form->textField($model,'optime',array($htmlOptions)); ?>
	
	<button class="btn primary-bg medium" style="margin-left: 50px;">
		<span class="button-content"><i class="glyph-icon icon-search"></i>查询</span>
	</button>

<?php $this->endWidget(); ?>

</div>
<!-- search-form -->
