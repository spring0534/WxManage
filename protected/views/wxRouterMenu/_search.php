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
	
	
	<?php echo $form->label($model,'ghid'); ?>: &nbsp;
	<?php echo $form->textField($model,'ghid',array('class'=>'input-text')); ?>
	
	
	<?php echo $form->label($model,'name'); ?>: &nbsp;
	<?php echo $form->textField($model,'name',array('class'=>'input-text')); ?>
	
	
	<?php echo $form->label($model,'parent_id'); ?>: &nbsp;
	<?php echo $form->textField($model,'parent_id',array($htmlOptions)); ?>
	
	
	<?php echo $form->label($model,'seq'); ?>: &nbsp;
	<?php echo $form->textField($model,'seq',array($htmlOptions)); ?>
	
	
	<?php echo $form->label($model,'menu_type'); ?>: &nbsp;
	<?php echo $form->textField($model,'menu_type',array('class'=>'input-text')); ?>
	
	
	<?php echo $form->label($model,'url'); ?>: &nbsp;
	<?php echo $form->textField($model,'url',array('class'=>'input-text')); ?>
	
	
	<?php echo $form->label($model,'event_key'); ?>: &nbsp;
	<?php echo $form->textField($model,'event_key',array('class'=>'input-text')); ?>
	
	
	<?php echo $form->label($model,'reply_type'); ?>: &nbsp;
	<?php echo $form->textField($model,'reply_type',array($htmlOptions)); ?>
	
	
	<?php echo $form->label($model,'reply_id'); ?>: &nbsp;
	<?php echo $form->textField($model,'reply_id',array($htmlOptions)); ?>
	
	
	<?php echo $form->label($model,'status'); ?>: &nbsp;
	<?php echo $form->textField($model,'status',array($htmlOptions)); ?>
	
	
	<?php echo $form->label($model,'note'); ?>: &nbsp;
	<?php echo $form->textField($model,'note',array('class'=>'input-text')); ?>
	
	
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
