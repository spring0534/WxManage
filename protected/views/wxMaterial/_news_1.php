<div class="tabs ui-tabs ui-widget ui-widget-content ui-corner-all">
	<ul
		class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<li onclick="location.href='<?php U('add/type/text')?>'">
			<a href="#icon-only-tabs-1">
				<i class="glyph-icon icon-file-text-alt float-left opacity-80"></i>
				文本
			</a>
		</li>
		<li onclick="location.href='<?php U('add/type/news_1')?>'"
			class=" ui-tabs-active ui-state-active">
			<a href="#icon-only-tabs-2">
				<i class="glyph-icon icon-trello float-left opacity-80"></i>
				单图文
			</a>
		</li>
		<li onclick="location.href='<?php U('add/type/news_n')?>'">
			<a href="#icon-only-tabs-3">
				<i class="glyph-icon icon-bar-chart float-left opacity-80"></i>
				多图文
			</a>
		</li>
		<li onclick="location.href='<?php U('add/type/image')?>'">
			<a href="#icon-only-tabs-4">
				<i class="glyph-icon icon-picture float-left opacity-80"></i>
				图片
			</a>
		</li>
	</ul>
	<div id="icon-only-tabs-2" aria-labelledby="ui-id-2"
		class="ui-tabs-panel ui-widget-content ui-corner-bottom"
		role="tabpanel" aria-expanded="false" aria-hidden="true"
		style="display: block;">
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
<input type="hidden" name="m_type" value="news_1">
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
						<label for=""><?php echo $form->labelEx($model,'status'); ?> </label>
					</div>
					<div class="form-input col-md-10">
			<?php echo CHtml::switchButton(chtmlName($model, 'status'), $model->status,array(1=>'正常',0=>'禁用'));?>
		</div>
				</div>


				<!-- ----------------------------------------------------------- -->
				<div class="news_content" data-index=1>

				<div class="form-row">
					<div class="form-label col-md-2">
						<label for="">标题</label>
					</div>
					<div class="form-input col-md-10">
						<?php echo CHtml::textField('clist[1][name]','',array('size'=>60,'maxlength'=>100,'class'=>'col-md-6 float-left','id'=>'','data-trigger'=>"change", 'data-required'=>"true",)); ?>
					</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for="">描述</label>
					</div>
					<div class="form-input col-md-10">
						<?php echo CHtml::textArea('clist[1][note]','',array('size'=>60,'maxlength'=>100,'class'=>'col-md-6 float-left','id'=>'','style'=>'height:120px;','data-trigger'=>"change", 'data-required'=>"true",)); ?>
					</div>
				</div>
				<div class="form-row">
					<div class="form-label col-md-2">
						<label for="">显示图片</label>
					</div>
					<div class="form-input col-md-10">
						<?php  echo imageUpdoad('clist[1][pic]','','qrcode_small',array('id'=>'qrcode_small'),'image')?>

					</div>
				</div>

				<div class="form-row">
					<div class="form-label col-md-2">
						<label for="">点击后执行的操作</label>
					</div>
					<div class="form-input col-md-10">

					<?php 	echo CHtml::dropDownList('clist[1][event_]',1,array(1=>'跳转到链接',2=>'跳转到活动',3=>'详细内容'),array('class'=>'col-md-6 float-left event_cc','empty'=>'请选择操作','data-for'=>'1','data-trigger'=>"change", 'data-required'=>"true"));?>

				   </div>
				</div>
				<div class="form-row">
					<div id="event_url" class="event_r_n_1">
						<div class="form-label col-md-2">
							<label for=""> URL链接地址 </label>
						</div>
						<div class="form-input col-md-10">
							<?php echo CHtml::textField('clist[1][url]','',array('size'=>60,'maxlength'=>100,'class'=>'col-md-6 float-left','placeholder'=>'url链接 ','id'=>'')); ?>
						</div>
					</div>
					<div id="event_act" class="event_r_n_1" style="display: none">
						<div class="form-label col-md-2">
							<label for="">活动 </label>
						</div>
						<div class="form-input col-md-10">
						<?php  echo CHtml::dropDownList('clist[1][actv]', '1', CHtml::listData($actlist, 'aid', 'title'),array('class'=>'col-md-6 float-left'))?>

						</div>
					</div>
					<div id="event_content" class="event_r_n_1" style="display: none">
						<div class="form-label col-md-2">
							<label for="">详细内容 </label>
						</div>
						<div class="form-input col-md-10">
						<?php echo ueditor('clist[1][detail]','','detail_id1')?>

						</div>
					</div>
				</div>
				</div>
				<!-- ----------------------------------------------------------- -->

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
$(function(){
	$('.msg_type_c').bind("change",function(e){
		$('.msgtype_content').hide();
		$('.msgtype_content').eq($(this)[0].selectedIndex-1).show();
	});
	$('.event_cc').bind("change",function(e){
		$('.event_r_n_'+$(this).attr('data-for')).hide();
		$('.event_r_n_'+$(this).attr('data-for')).eq($(this)[0].selectedIndex-1).show();
	});

	if($('.errorSummary').length>0){
		$('#msgtip').slideDown();
	}
	$('body').click(function(){
		$('#msgtip').slideUp();
	});

})
</script>