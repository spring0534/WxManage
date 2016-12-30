<div class="explain-col">
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'htmlOptions' => array(
		'style' => 'margin: 0'
	),
)); ?>



	<?php echo $form->label($model,'keyword'); ?>: &nbsp;
	<?php echo $form->textField($model,'keyword',array('class'=>'input-text')); ?>



	<?php echo $form->label($model,'status'); ?>: &nbsp;

	<?php 	echo $form->dropDownList($model,'status',array(0=>'禁用',1=>'正常'),array('class'=>'input-text','empty'=>'所有'));?>

	<?php echo $form->label($model,'msg_type'); ?>: &nbsp;
	<?php echo $form->textField($model,'msg_type',array('class'=>'input-text')); ?>

	<button class="btn primary-bg medium" style="margin-left: 50px;">
		<span class="button-content"><i class="glyph-icon icon-search"></i>查询</span>
	</button>

<?php $this->endWidget(); ?>

</div>
<!-- search-form -->
