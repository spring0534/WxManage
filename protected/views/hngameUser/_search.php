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
	姓名:<?php echo $form->textField($model,'realname',array('class'=>'input-text','style'=>'width:200px;')); ?>&nbsp;
	手机:<?php echo $form->textField($model,'phone',array('class'=>'input-text','style'=>'width:200px;')); ?>&nbsp;
	家乡:<?php echo $form->textField($model,'address',array('class'=>'input-text','style'=>'width:200px;')); ?>&nbsp;
	资源:<?php echo $form->textField($model,'resource',array('class'=>'input-text','style'=>'width:200px;')); ?>&nbsp;
	需求:<?php echo $form->textField($model,'demand',array('class'=>'input-text','style'=>'width:200px;')); ?>&nbsp;
	<button class="btn primary-bg medium" style="margin-left: 50px;">
		<span class=""><i class="glyph-icon icon-search"></i>查询</span>
	</button>

<?php $this->endWidget(); ?>

</div>
<!-- search-form -->
