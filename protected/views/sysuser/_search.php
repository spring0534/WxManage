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
	
	
	
	<?php echo $form->label($model,'username'); ?>: &nbsp;
	<?php echo $form->textField($model,'username',array('class'=>'input-text')); ?>
	
	
	<?php echo $form->label($model,'nickname'); ?>: &nbsp;
	<?php echo $form->textField($model,'nickname',array('class'=>'input-text')); ?>
	
		
	
	<?php echo $form->label($model,'groupid'); ?>: &nbsp;
	<?php echo CHtml::dropDownList(chtmlName($model, 'groupid'), 0,CHtml::listData($model->getGrouplist(), 'groupid', 'groupname'),array('empty'=>'所有') )?>
	<button class="btn primary-bg medium" style="margin-left: 50px;">
		<span class="button-content"><i class="glyph-icon icon-search"></i>查询</span>
	</button>

<?php $this->endWidget(); ?>

</div>
<!-- search-form -->
