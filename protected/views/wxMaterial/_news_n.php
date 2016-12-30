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
		<li onclick="location.href='<?php U('add/type/news_n')?>'" class=" ui-tabs-active ui-state-active">
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

		<div id="icon-only-tabs-3" aria-labelledby="ui-id-3" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-expanded="false" aria-hidden="true" style="display: block;">
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
<input type="hidden" name="m_type" value="news_n">
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

				<div class="example-box">
    <div class="example-code clearfix">

        <div class="row">
            <div class="col-md-20 column-sort" id='main_div'>

				</div>
				</div>
				</div>

				<div class="button-pane">
					<div class="form-input col-md-10 col-md-offset-2">
						<a href="javascript:addlist();"
							class="btn medium primary-bg">
							<span class="button-content">
								<i class="glyph-icon icon-plus float-left"></i>
								增加一条图文素材
							</span>
						</a>
						<button class="btn primary-bg medium"
							onclick="javascript:return $('#wx-material-form').parsley( 'validate' );">
							<span class="button-content"><i class="glyph-icon icon-check float-left"></i><?php echo $model->isNewRecord ? '提交' : '保存'; ?>
</span>
						</button>
						<a href="javascript:;"
							class="btn medium primary-bg">
							<span class="button-content">
								<i class="glyph-icon icon-undo float-left"></i>
								取消
							</span>
						</a>
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
	if($('.errorSummary').length>0){
		$('#msgtip').slideDown();
	}
	$('body').click(function(){
		$('#msgtip').slideUp();
	});

})
var start=0;
function addlist(callback){
	start++;
	if($('.news_content').length>=10){
		alert('不能再添加 了！多图文最多支持10条素材集合！');return;
	}
	$.get('<?php U('add?op=tpl&num=');?>'+start,function(data){
		if(start>2){
			 $('.content-box').addClass('content-box-closed');
		}
		$('#main_div').append(data);
		$('.event_cc').unbind('change').bind("change",function(e){
			$('.event_r_n_'+$(this).attr('data-for')).slideUp().eq($(this)[0].selectedIndex-1).slideDown();

		});
		$('.toggle-button-op').unbind('click').bind("click",function(e){
			$(this).find('i').toggleClass('icon-chevron-down');
			$(this).find('i').toggleClass('icon-chevron-up');
			$(this).closest('.content-box').toggleClass('content-box-closed');
		});
		$('.toggle-button-rm').unbind('click').bind('click',function(){
			if(confirm('确定要删除此条图文素材吗?')){
				 $(this).closest('.content-box').slideUp(500,function(){
					    $(this).remove();
					 });
			}
		});
		if(typeof(callback)=='function')callback();
	});
}
addlist(addlist);
//addlist();
</script>
