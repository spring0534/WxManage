<div class="form-wizard">
	<ul class="anchor">
		<li>
			<a href="javascript:;" class="selected" isdone="1" rel="1">
				<label class="wizard-step">1</label>
				<span class="wizard-description">
				<?php if($model->isNewRecord){?>
					1、微应用添加
					<?php }else{?>
						1、编辑微应用
					<?php }?>

					<small>填写创建微应用的相关配置</small>
				</span>
			</a>
		</li>
		<li>
			<a href="javascript:;" class="disabled" isdone="1" rel="2">
				<label class="wizard-step">2</label>
				<span class="wizard-description">
					2、配置微应用
					<small>为创建的微应用配置自定义属性</small>
				</span>
			</a>
		</li>
	</ul>
	<div class="stepContainer" style="height: 297px;">
		<div id="step-1" class="content">
			<div class="form">

<?php
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'plugin-form',
	'htmlOptions'=>array(
		'class'=>'col-md-6 center-margin'
	),
	'enableAjaxValidation'=>false
));
?>

<div class="infobox warning-bg" id='msgtip' style='display: none'>
					<p>
						<i class="glyph-icon icon-exclamation mrg10R"></i><?php echo $form->errorSummary($model); ?>
</p>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for=""><?php echo $form->labelEx($model,'ptype'); ?> </label>
					</div>
					<div class="form-input col-md-10">
					<?php if($model->isNewRecord){?>
					<?php echo $form->textField($model,'ptype',array('size'=>20,'maxlength'=>20,'class'=>'col-md-6 float-left','data-trigger'=>"change", 'data-type'=>'letter','placeholder'=>'只能输入字母','data-required'=>"true")); ?>
					<?php }else{?>
						<?php echo $form->textField($model,'ptype',array('size'=>20,'maxlength'=>20,'class'=>'col-md-6 float-left','data-trigger'=>"change", 'data-type'=>'letter','placeholder'=>'只能输入字母','data-required'=>"true",'disabled'=>'disabled')); ?>
					<?php }?>

		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for=""><?php echo $form->labelEx($model,'name'); ?> </label>
					</div>
					<div class="form-input col-md-10">
			<?php echo $form->textField($model,'name',array('size'=>20,'maxlength'=>20,'class'=>'col-md-6 float-left','data-trigger'=>"change", 'data-required'=>"true")); ?>
		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for=""><?php echo $form->labelEx($model,'icon_url'); ?> </label>
					</div>
					<div class="form-input col-md-10">

			<?php  echo imageUpdoad(get_class($model).'[icon_url]',$model->icon_url,'icon_url',array('id'=>'icon_url'),'image')?>

		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for=""><?php echo $form->labelEx($model,'processor_class'); ?> </label>
					</div>
					<div class="form-input col-md-10">
			<?php echo $form->textField($model,'processor_class',array('size'=>60,'maxlength'=>100,'class'=>'col-md-6 float-left')); ?>
		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for=""><?php echo $form->labelEx($model,'status'); ?> </label>
					</div>
					<div class="form-input col-md-10">
			<?php echo CHtml::groupButton(chtmlName($model, 'status'), $model->isNewRecord?2:$model->status, array(0=>'未发布',1=>'已发布',2=>'内部测试',3=>'已废弃'))?>
		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for=""><?php echo $form->labelEx($model,'simple_memo'); ?> </label>
					</div>
					<div class="form-input col-md-10">
			<?php echo $form->textField($model,'simple_memo',array('size'=>60,'maxlength'=>500,'class'=>'col-md-10 float-left','data-trigger'=>"change", 'data-required'=>"true")); ?>
		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for=""><?php echo $form->labelEx($model,'detail_memo'); ?> </label>
					</div>
					<div class="form-input col-md-10">

			<?php echo ueditor(chtmlName($model, 'detail_memo'),$model->detail_memo,'detail_memo',"'100%'");?>
		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for=""><?php echo $form->labelEx($model,'screenshots'); ?> </label>
					</div>
					<div class="form-input col-md-10">
			<?php echo muimageUpload('screenshots',$model->screenshots);?>
		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for=""><?php echo $form->labelEx($model,'promote'); ?> </label>
					</div>
					<div class="form-input col-md-10">
			<?php echo $form->textField($model,'promote',array($htmlOptions)); ?>
		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for=""><?php echo $form->labelEx($model,'hot'); ?> </label>
					</div>
					<div class="form-input col-md-10">
			<?php echo $form->textField($model,'hot',array($htmlOptions)); ?>
		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for=""><?php echo $form->labelEx($model,'price_month'); ?> </label>
					</div>
					<div class="form-input col-md-10">
			<?php echo $form->textField($model,'price_month',array($htmlOptions)); ?>
		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for=""><?php echo $form->labelEx($model,'price_year'); ?> </label>
					</div>
					<div class="form-input col-md-10">
			<?php echo $form->textField($model,'price_year',array($htmlOptions)); ?>
		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for=""><?php echo $form->labelEx($model,'cate'); ?> </label>
					</div>
					<div class="form-input col-md-10">

			<?php echo CHtml::groupButton(chtmlName($model, 'cate'), $model->isNewRecord?5:$model->cate, Yii::app()->params['plugin_cate'])?>
		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for="">预设属性 </label>
					</div>
					<div class="form-input col-md-10">
					<?php if($model->isNewRecord){?>
					<?php echo CHtml::multipleListButton('setting[preconfig]', array('shareTitle','shareDesc','shareIcon','customWxCss','statJs'), $this->defaultField);?>
					<?php }else{?>
					<?php echo CHtml::multipleListButton('setting[preconfig]',$model->setting['preconfig'], $this->defaultField);?>
					<?php }?>


    </div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for="">启用SN管理 </label>
					</div>
					<div class="form-input col-md-10">

			<?php echo CHtml::switchButton('setting[sn]', $model->setting['sn'])?>
		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for="">启用奖项管理 </label>
					</div>
					<div class="form-input col-md-10">
			<?php echo CHtml::switchButton('setting[prize]',  $model->setting['prize'])?>
		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for="">启用高级管理 </label>
					</div>
					<div class="form-input col-md-10">
			<?php echo CHtml::switchButton('setting[advancedManage]',  $model->setting['advancedManage'])?>
		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for=""><?php echo $form->labelEx($model,'versions'); ?> </label>
					</div>
					<div class="form-input col-md-10">
			<?php echo CHtml::groupButton(chtmlName($model, 'versions'), $model->isNewRecord?'2':$model->versions, array(2=>'2.0',1=>'1.0'))?>
		</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for="">开发者</label>
					</div>
					<div class="form-input col-md-10">
						<?php echo CHtml::groupButton(chtmlName($model, 'uid'), $model->isNewRecord?user()->id:$model->uid, CHtml::listData(SysUser::model()->findAllByAttributes(array('groupid'=>2)), 'id','nickname'))?>
						
					</div>
				</div>
				<div class="button-pane text-center">
					
					<button type="reset" class="btn large primary-bg text-transform-upr font-size-11" id="demo-form-valid" >
						<span class="button-content"><i class="glyph-icon icon-undo float-left"></i>重置</span>
					</button>
					<button onclick="javascript:return $('#plugin-form').parsley( 'validate' );" type="submit" class="btn large primary-bg text-transform-upr font-size-11" id="demo-form-valid" >
						<span class="button-content"><?php echo $model->isNewRecord ? '创建微应用' : '保存'; ?></span>
					</button>
				</div>


<?php $this->endWidget(); ?>

</div>
			<!-- form -->
		</div>
	</div>
</div>
<style>
.multiple-select a {
	margin-bottom: 10px;
}
</style>
<script>
if($('.errorSummary').length>0){
	$('#msgtip').slideDown();

}
$('body').click(function(){
	$('#msgtip').slideUp();
});


</script>