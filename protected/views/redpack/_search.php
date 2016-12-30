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
	订单编号:<?php echo $form->textField($model,'tb_order_no',array('class'=>'input-text','style'=>'width:200px;')); ?>&nbsp;
	状态: <?php echo CHtml::dropDownList(chtmlName($model, 'status'), $model->status, array(''=>'全部','0'=>'审核失败','1'=>'等待审核','2'=>'派发成功','3'=>'派发失败'))?>
	<button class="btn primary-bg medium" style="margin-left: 50px;">
		<span class=""><i class="glyph-icon icon-search"></i>查询</span>
	</button>

<?php $this->endWidget(); ?>

</div>
<!-- search-form -->
