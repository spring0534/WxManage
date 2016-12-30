<div class="explain-col">
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'htmlOptions' => array(
		'style' => 'margin: 0'
	),
)); ?>
	
	<?php echo $form->label($model,'id'); ?>: &nbsp;
	<?php echo $form->textField($model,'id',array($htmlOptions)); ?>
	
	
	<?php echo $form->label($model,'day'); ?>: &nbsp;
	<?php echo $form->textField($model,'day',array($htmlOptions)); ?>
	
	
	<?php echo $form->label($model,'ghid'); ?>: &nbsp;
	<?php echo $form->textField($model,'ghid',array('class'=>'input-text')); ?>
	
	
	<?php echo $form->label($model,'sub'); ?>: &nbsp;
	<?php echo $form->textField($model,'sub',array($htmlOptions)); ?>
	
	
	<?php echo $form->label($model,'unsub'); ?>: &nbsp;
	<?php echo $form->textField($model,'unsub',array($htmlOptions)); ?>
	
	
	<?php echo $form->label($model,'receive_num'); ?>: &nbsp;
	<?php echo $form->textField($model,'receive_num',array($htmlOptions)); ?>
	
	
	<?php echo $form->label($model,'send_num'); ?>: &nbsp;
	<?php echo $form->textField($model,'send_num',array($htmlOptions)); ?>
	
	
	<?php echo $form->label($model,'msg_num'); ?>: &nbsp;
	<?php echo $form->textField($model,'msg_num',array($htmlOptions)); ?>
	
	
	<?php echo $form->label($model,'ctm'); ?>: &nbsp;
	<?php echo $form->textField($model,'ctm',array($htmlOptions)); ?>
	
	
	<?php echo $form->label($model,'utm'); ?>: &nbsp;
	<?php echo $form->textField($model,'utm',array($htmlOptions)); ?>
	
	<button class="btn primary-bg medium" style="margin-left: 50px;">
		<span class="button-content"><i class="glyph-icon icon-search"></i>查询</span>
	</button>

<?php $this->endWidget(); ?>

</div>
<!-- search-form -->
