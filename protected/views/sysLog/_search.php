<div class="explain-col">
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'htmlOptions' => array(
		'style' => 'margin: 0'
	),
)); ?>
	

	<?php echo $form->label($model,'level'); ?>: &nbsp;
	<?php echo $form->textField($model,'level',array('class'=>'input-text')); ?>
	
	
	<?php echo $form->label($model,'category'); ?>: &nbsp;
	<?php echo $form->textField($model,'category',array('class'=>'input-text')); ?>
	
	
	<?php echo $form->label($model,'ghid'); ?>: &nbsp;
	<?php echo $form->textField($model,'ghid',array('class'=>'input-text')); ?>
	
	
	操作用户 : &nbsp;
	<?php echo $form->textField($model,'uid',array($htmlOptions)); ?>
	
	

	
	<?php echo $form->label($model,'ip'); ?>: &nbsp;
	<?php echo $form->textField($model,'ip',array('class'=>'input-text')); ?>
	
	
	<?php echo $form->label($model,'logtime'); ?>: &nbsp;
	<?php echo $form->textField($model,'logtime',array($htmlOptions)); ?>
	

	<button class="btn primary-bg medium" style="margin-left: 50px;">
		<span class="button-content"><i class="glyph-icon icon-search"></i>查询</span>
	</button>

<?php $this->endWidget(); ?>

</div>
<!-- search-form -->
