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
	
	

	<?php echo $form->label($model,'username'); ?>: 
	<?php echo $form->textField($model,'username',array('class'=>'input-text')); ?>

	<?php echo $form->label($model,'phone'); ?>: 
	<?php echo $form->textField($model,'phone',array('class'=>'input-text')); ?>

	<?php echo $form->label($model,'aid'); ?>: 
    <?php echo CHtml::dropDownList(chtmlName($model, 'aid'), $model->aid,CHtml::listData($this->getActList(), 'aid', 'title'),array('empty'=>'全部') )?>

	<?php echo $form->label($model,'status'); ?>: 
    <?php echo CHtml::dropDownList(chtmlName($model, 'status'), $model->status, array('1'=>'有效','0'=>'无效','已兑奖'),array('empty'=>'全部'))?>

	<?php echo $form->label($model,'ctm'); ?>: 
	<?php echo calendar(chtmlName($model, 'ctm').'[1]','', 'YYYY-MM-DD','130px');?>--
	<?php echo calendar(chtmlName($model, 'ctm').'[2]','', 'YYYY-MM-DD','130px');?>
	
	<button class="btn primary-bg medium" style="margin-left: 50px;">
		<span class="button-content"><i class="glyph-icon icon-search"></i>查询</span>
	</button>

<?php $this->endWidget(); ?>

</div>
<!-- search-form -->
