<div class="explain-col">
<?php
$form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'htmlOptions'=>array(
		'style'=>'margin: 0',
		'class'=>'search_form'
	)
));
?>

<ul>

<li style="display: table;float: left;">
	&nbsp;<?php echo $form->label($model,'username'); ?>:
	<?php echo $form->textField($model,'username',array('class'=>'input-text')); ?>
</li>
<li style="display: table;float: left;">
	&nbsp;<?php echo $form->label($model,'phone'); ?>:
	<?php echo $form->textField($model,'phone',array('class'=>'input-text')); ?>
</li>

<li style="display: table;float: left;">
	&nbsp;<?php echo $form->label($model,'status'); ?>:
    <?php echo CHtml::dropDownList(chtmlName($model, 'status'), $model->status, array('1'=>'有效','0'=>'无效','3'=>'已兑奖'),array('empty'=>'全部'))?>
</li>
<li style="display: table;float: left;">
	&nbsp;<?php echo $form->label($model,'ctm'); ?>:
	<?php echo calendar(chtmlName($model, 'ctm').'[1]','', '','180px');?>-
	<?php echo calendar(chtmlName($model, 'ctm').'[2]','', '','180px');?>
	</li>
<li style="display: table;float: left;">
	&nbsp;登记信息:
    <?php echo CHtml::dropDownList('info', '', array('1'=>'有登记信息','2'=>'无登记信息'),array('empty'=>'全部'))?>
</li>
</ul>
	<button class="btn primary-bg medium" style="margin-left: 10px;margin-bottom:1px">
		<span class="button-content"><i class="glyph-icon icon-search"></i>&nbsp;搜索</span>
	</button>
	<button class="btn primary-bg medium" style="margin-left: 10px;" type="button" onclick="export_list()">
		<span class="button-content"><i class="glyph-icon icon-download-alt"></i>&nbsp;导出</span>
	</button>




<?php $this->endWidget(); ?>

</div>
<!-- search-form -->
<script>
function export_list(){
	if(confirm('如果导出的数据超过10000条，导出的时间会较长，请耐心等待！')){
		window.open($('.keys').attr('title').indexOf("?")!=-1?($('.keys').attr('title')+"&export=yes&"+$('.search-form form').serialize()):($('.keys').attr('title')+"?export=yes&"+$('.search-form form').serialize()));
	}

}
</script>
