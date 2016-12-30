<div class="explain-col">
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'htmlOptions' => array(
		'style' => 'margin: 0'
	),
)); ?>
	<?php echo $form->label($model,'proptitle'); ?>: 
	<?php echo $form->textField($model,'proptitle',array('class'=>'input-text')); ?>
	<?php echo $form->label($model,'required'); ?>: 
    <?php echo CHtml::groupButton(chtmlName($model, 'required'), $model->required?$model->required:'3', array('0'=>'否','1'=>'是',3=>'所有'))?>
	<?php echo $form->label($model,'editable'); ?>:
	<?php echo CHtml::groupButton(chtmlName($model, 'editable'), $model->editable?$model->editable:'3', array('0'=>'否','1'=>'是',3=>'所有'))?>
	<button class="btn primary-bg medium" style="margin-left: 50px;">
		<span class="button-content"><i class="glyph-icon icon-search float-left"></i><i class="glyph-icon icon-search"></i>查询</span>
	</button>
<?php $this->endWidget(); ?>

</div>
<!-- search-form -->
