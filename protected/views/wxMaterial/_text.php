
<div class="tabs ui-tabs ui-widget ui-widget-content ui-corner-all">
	<ul	class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<li onclick="location.href='<?php U('add/type/text')?>'">
			<a href="#icon-only-tabs-1">
				<i class="glyph-icon icon-file-text-alt float-left opacity-80"></i>
				文本
			</a>
		</li>
		<li onclick="location.href='<?php U('add/type/news_1')?>'">
			<a href="#icon-only-tabs-2">
				<i class="glyph-icon icon-trello float-left opacity-80"></i>
				单图文
			</a>
		</li>
		<li onclick="location.href='<?php U('add/type/news_n')?>'">
			<a href="#icon-only-tabs-3" >
				<i class="glyph-icon icon-bar-chart float-left opacity-80"></i>
				多图文
			</a>
		</li>
		<li onclick="location.href='<?php U('add/type/image')?>'">
			<a href="#icon-only-tabs-4" >
				<i class="glyph-icon icon-picture float-left opacity-80"></i>
				图片
			</a>
		</li>
	</ul>
	<div id="icon-only-tabs-1"	style="display: block;">
		<div class="form">

<?php
$form = $this->beginWidget('CActiveForm', array(
	'id' => 'wx-material-form',
	'htmlOptions' => array(
		'class' => 'col-md-10 center-margin'
	),
	'enableAjaxValidation' => false
));
?>
<input type="hidden" name="m_type" value="text">
<div class="infobox warning-bg" id='msgtip' style='display: none'>
				<p>
					<i class="glyph-icon icon-exclamation mrg10R"></i><?php echo $form->errorSummary($model); ?>
</p>
			</div>
			<div class="msgtype_content">
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for=""><?php echo $form->labelEx($model,'title'); ?> </label>
					</div>
					<div class="form-input col-md-10">
				<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>100,'class'=>'col-md-6 float-left','id'=>'','data-trigger'=>"change", 'data-required'=>"true")); ?>
			</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for=""><?php echo $form->labelEx($model,'content'); ?> </label>
					</div>
					<div class="form-input col-md-10">
				<?php 	echo $form->textArea($model,'content',array('class'=>'col-md-6 float-left','style'=>"height: 20%",'id'=>'','data-trigger'=>"change", 'data-required'=>"true"));?>
			</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for=""><?php echo $form->labelEx($model,'status'); ?> </label>
					</div>
					<div class="form-input col-md-10">
			<?php echo CHtml::switchButton(chtmlName($model, 'status'), $model->status,array(1=>'正常',0=>'禁用'));?>
		</div>
				</div>

				<div class="button-pane">
					<div class="form-input col-md-10 col-md-offset-2">
						<button class="btn primary-bg medium"
							onclick="javascript:return $('#wx-material-form').parsley( 'validate' );">
							<span class="button-content"><i class="glyph-icon icon-check float-left"></i><?php echo $model->isNewRecord ? '提交' : '保存'; ?>
</span>
						</button>
						<button type="reset" class="btn primary-bg medium" id="demo-form-valid" >
						<span class="button-content"><i class="glyph-icon icon-undo float-left"></i>重置</span>
					</button>
					</div>
				</div>
			</div>
<?php $this->endWidget(); ?>

</div>
		<!-- form -->
	</div>
</div>
<script>
if($('.errorSummary').length>0){
	$('#msgtip').slideDown();

}
$('body').click(function(){
	$('#msgtip').slideUp();
});
</script>