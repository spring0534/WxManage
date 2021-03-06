<?php
/* @var $this WxRouterMenuController */
/* @var $model WxRouterMenu */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'wx-router-menu-form',
	'htmlOptions' =>array('class' => 'col-md-10 center-margin'),
	'enableAjaxValidation'=>false,
)); ?>

<div class="infobox warning-bg" id='msgtip' style='display: none'>
    <p><i class="glyph-icon icon-exclamation mrg10R"></i><?php echo $form->errorSummary($model); ?>
</p>
</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'name'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'name',array('size'=>20,'maxlength'=>20,'class'=>'col-md-6 float-left','data-trigger'=>"change", 'data-required'=>"true")); ?>
		</div>
	</div>
	<?php if($model->isNewRecord){?>
	<?php echo CHtml::hiddenField(get_class($model)."[parent_id]", $pid);?>
	<?php }else{?>
	<?php echo $form->hiddenField($model, 'parent_id');?>
	<?php }?>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for="">菜单类型</label>
		</div>
		<div class="form-input col-md-10">
		<?php echo CHtml::dropDownList(get_class($model)."[menu_type]", $model->menu_type, array('click'=>'点击事件','view'=>'访问网页'),array('class'=>'col-md-6 float-left t-select red','data-trigger'=>"change", 'data-required'=>"true"));?>
		</div>
	</div>


	<div class="menu_type_div" <?php if($model->menu_type!='click'&&!empty($model->menu_type))echo 'style="display:none"';?> >
		<div class="form-row">
			<div class="form-label col-md-2">
				<label for="">响应方式 </label>
			</div>
			<div class="form-input col-md-10">

				<?php echo CHtml::dropDownList(get_class($model)."[reply_type]", $model->reply_type, array(1=>'素材库内容',2=>'第三方接口'),array('class'=>'col-md-6 float-left r-select'))?>
			</div>
		</div>


		<div class="form-row"  <?php if($model->reply_type!=1&&!empty($model->reply_type))echo 'style="display:none"';?> >
			<div class="form-label col-md-2" >
				<label for="">请选择素材 </label>
			</div>
			<div class="form-input col-md-10">

				<?php echo CHtml::dropDownList(get_class($model)."[reply_id][1]", $model->reply_id, CHtml::listData(WxMaterial::model()->findAllByAttributes(array('ghid'=>gh()->ghid)),'id', 'title'),array('class'=>'col-md-6 float-left '))?>
			</div>
		</div>
		<div class="form-row"  <?php if($model->reply_type!=2&&!empty($model->reply_type))echo 'style="display:none"';?>>
			<div class="form-label col-md-2">
				<label for="">请选择第三方接口 </label>
			</div>
			<div class="form-input col-md-10">
				<?php echo CHtml::dropDownList(get_class($model)."[reply_id][2]", $model->reply_id, CHtml::listData(WxForward::getMylist(), 'id', 'name'),array('class'=>'col-md-6 float-left r-select'))?>
			</div>
		</div>
	</div>
	<div class="menu_type_div" <?php if($model->menu_type!='view')echo 'style="display:none"';?>>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'url'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textField($model,'url',array('size'=>60,'maxlength'=>200,'class'=>'col-md-6 float-left')); ?>
		</div>
	</div>
	</div>

	<div class="button-pane">
		<div class="form-input col-md-10 col-md-offset-2">
			<button class="btn primary-bg medium"
				onclick="javascript:return $('#wx-router-menu-form').parsley( 'validate' );">
				<span class="button-content"><i class="glyph-icon icon-check float-left"></i><?php echo $model->isNewRecord ? '提交' : '保存'; ?>
</span>
			</button>
			<button type="reset" class="btn primary-bg medium" id="demo-form-valid" >
						<span class="button-content"><i class="glyph-icon icon-undo float-left"></i>重置</span>
					</button>
		</div>

	</div>
<?php $this->endWidget(); ?>

</div>
<!-- form -->
<script>
if($('.errorSummary').length>0){
	$('#msgtip').slideDown();

}
$('body').click(function(){
	$('#msgtip').slideUp();
});
$('.t-select').change(function(){
	$('.menu_type_div').slideUp().eq($(this).get(0).selectedIndex).slideDown();;
});
$('.r-select').change(function(){
	$(this).closest('div.form-row').nextAll('div.form-row').slideUp().eq($(this).get(0).selectedIndex).slideDown();
});

</script>