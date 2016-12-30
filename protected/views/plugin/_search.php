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

	<?php echo $form->label($model,'ptype'); ?>: &nbsp;
	<?php echo $form->textField($model,'ptype',array('class'=>'input-text')); ?>
	<?php echo $form->label($model,'name'); ?>: &nbsp;
	<?php echo $form->textField($model,'name',array('class'=>'input-text')); ?>
	<?php echo $form->label($model,'status'); ?>: &nbsp;
	<?php echo CHtml::dropDownList(chtmlName($model, 'status'), $model->status, array('1'=>'有效','0'=>'无效'),array('empty'=>'全部'))?>
	<?php echo $form->label($model,'cate'); ?>: &nbsp;
	<?php echo CHtml::dropDownList(chtmlName($model, 'cate'), $model->cate,Yii::app()->params['plugin_cate'],array('empty'=>'全部') )?>
	<button class="btn primary-bg medium" style="margin-left: 50px;">
		<span class="button-content"><i class="glyph-icon icon-search"></i>查询</span>
	</button>

<?php $this->endWidget(); ?>

</div>
<!-- search-form -->
