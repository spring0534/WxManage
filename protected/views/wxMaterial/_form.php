
<div class="tabs ui-tabs ui-widget ui-widget-content ui-corner-all">
	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
		<li>
			<a href="#icon-only-tabs-2" onclick='location.href="<?php U("index");?>"' title="Tab 1" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-2">
				<i class="glyph-icon icon-mail-reply float-left opacity-80"></i>
				返回
			</a>
		</li>
		<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active" role="tab" tabindex="0" aria-controls="icon-only-tabs-1" aria-labelledby="ui-id-1" aria-selected="true">
			<a href="#icon-only-tabs-1" title="Tab 1" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-1">
				<i class="glyph-icon icon-file-text-alt float-left opacity-80"></i>
				<?php echo Yii::app()->params['msg_type'][$model->msg_type];?>
			</a>
		</li>
	</ul>
	<div id="icon-only-tabs-1" aria-labelledby="ui-id-1" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-expanded="true" aria-hidden="false" style="display: block;">




<?php

switch ($model->msg_type){
	case 'text':
		?>

 <!-- 文 本-->
		<div class="form">

<?php
		$form=$this->beginWidget('CActiveForm', array(
			'id'=>'wx-material-form',
			'htmlOptions'=>array(
				'class'=>'col-md-10 center-margin'
			),
			'enableAjaxValidation'=>false
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
				<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>100,'class'=>'col-md-6 float-left','placeholder'=>'title','id'=>'','data-trigger'=>"change", 'data-required'=>"true")); ?>
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
						<button class="btn primary-bg medium" onclick="javascript:return $('#wx-material-form').parsley( 'validate' );">
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
		<!-- 文本 end -->



        <?php
		break;
	case 'news_1':
		?>


  <!-- 单图文 -->
		<div class="form">

<?php
		$form=$this->beginWidget('CActiveForm', array(
			'id'=>'wx-material-form',
			'htmlOptions'=>array(
				'class'=>'col-md-10 center-margin'
			),
			'enableAjaxValidation'=>false
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
						<?php echo CHtml::textField('clist[1][name]',$content[0]['title'],array('size'=>60,'maxlength'=>100,'class'=>'col-md-6 float-left','id'=>'','data-trigger'=>"change", 'data-required'=>"true",)); ?>
					</div>
					</div>
					<div class="form-row">
						<div class="form-label col-md-2">
							<label for="">描述</label>
						</div>
						<div class="form-input col-md-10">
						<?php echo CHtml::textArea('clist[1][note]',$content[0]['note'],array('size'=>60,'maxlength'=>100,'class'=>'col-md-6 float-left','id'=>'','style'=>'height:120px;','data-trigger'=>"change", 'data-required'=>"true",)); ?>
					</div>
					</div>
					<div class="form-row">
						<div class="form-label col-md-2">
							<label for="">显示图片</label>
						</div>
						<div class="form-input col-md-10">
						<?php  echo imageUpdoad('clist[1][pic]',$content[0]['pic'],'qrcode_small',array('id'=>'qrcode_small'),'image')?>

					</div>
					</div>
					<div class="form-row">
						<div class="form-label col-md-2">
							<label for="">点击后执行的操作</label>
						</div>
						<div class="form-input col-md-10">

					<?php 	echo CHtml::dropDownList('clist[1][event_]',$content[0]['onclick'],array(1=>'跳转到链接',2=>'跳转到活动',3=>'详细内容'),array('class'=>'col-md-6 float-left event_cc','empty'=>'请选择操作','data-for'=>'1','data-trigger'=>"change", 'data-required'=>"true"));?>

				   </div>
					</div>
					<div class="form-row">
						<div id="event_url" class="event_r_n_1" <?php if($content[0]['onclick']!=1&&!empty($content[0]['onclick'])){echo 'style="display: none"'; }?>>
							<div class="form-label col-md-2">
								<label for=""> URL链接地址 </label>
							</div>
							<div class="form-input col-md-10">
							<?php echo CHtml::textField('clist[1][url]',$content[0]['url'],array('size'=>60,'maxlength'=>100,'class'=>'col-md-6 float-left','placeholder'=>'url链接 ','id'=>'')); ?>
						</div>
						</div>
						<div id="event_act" class="event_r_n_1" <?php if($content[0]['onclick']!=2){echo 'style="display: none"'; }?>>
							<div class="form-label col-md-2">
								<label for="">活动 </label>
							</div>
							<div class="form-input col-md-10">
						<?php  echo CHtml::dropDownList('clist[1][actv]', $content[0]['aid'], CHtml::listData($actlist, 'aid', 'title'),array('class'=>'col-md-6 float-left'))?>

						</div>
						</div>
						<div id="event_content" class="event_r_n_1" <?php if($content[0]['onclick']!=3){echo 'style="display: none"'; }?>>
							<div class="form-label col-md-2">
								<label for="">详细内容 </label>
							</div>
							<div class="form-input col-md-10">
								<input type="hidden" name="clist[1][did]" value="<?php echo $content[0]['did'];?>">
						<?php echo ueditor('clist[1][detail]',$model->getdetail($content[0]['did']),'detail_id1')?>

						</div>
						</div>
					</div>
				</div>
				<!-- ----------------------------------------------------------- -->

				<div class="button-pane">
					<div class="form-input col-md-10 col-md-offset-2">
						<button class="btn primary-bg medium" onclick="javascript:return $('#wx-material-form').parsley( 'validate' );">
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
		<!-- 单图文  end-->







         <?php
		break;
	case 'news_n':
		?>



 <div class="form">


<?php
		$form=$this->beginWidget('CActiveForm', array(
			'id'=>'wx-material-form',
			'htmlOptions'=>array(
				'class'=>'col-md-10 center-margin'
			),
			'enableAjaxValidation'=>false
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
				<div >

<div class="example-box">
    <div class="example-code clearfix">

        <div class="row">
            <div class="col-md-20 column-sort" id='main_div'>
<?php foreach ($content as $kkk=>$vvv){$num++;?>

                <div class="content-box mrg15B" >
                   <h3 class="content-box-header primary-bg" style="cursor: move;">
							<span class="float-left">
								<span>
									<i class="glyph-icon icon-bar-chart float-left"></i> &nbsp;多图文第<?php echo $num;?>条</span>
							</span>
							<a href="javascript:" class="float-right icon-separator btn toggle-button toggle-button-op" title="展开&收缩" style="display: inline;">
								<i class="glyph-icon icon-toggle icon-chevron-down"></i>
							</a>
							<a href="javascript:" class="float-right icon-separator btn toggle-button-rm" data-style="dark" data-theme="bg-white" data-opacity="60" style="display: inline;">
								<i class="glyph-icon icon-remove"></i>
							</a>

						</h3>
                    <div class="content-box-wrapper">

                       						<div class="news_content" data-index=1>
								<div class="form-row">
									<div class="form-label col-md-2">
										<label for="">标题</label>
									</div>
									<div class="form-input col-md-10">
		<?php echo CHtml::textField('clist['.$num.'][name]',$vvv['title'],array('size'=>60,'maxlength'=>100,'class'=>'col-md-6 float-left from-field','id'=>'','data-trigger'=>"change", 'data-required'=>"true",)); ?>
	</div>
								</div>

								<div class="form-row">
									<div class="form-label col-md-2">
										<label for="">显示图片</label>
									</div>
									<div class="form-input col-md-10">
		<?php  echo imageUpdoad('clist['.$num.'][pic]',$vvv['pic'],'qrcode_small'.$num,array('id'=>'qrcode_small'.$num),'image')?>
	</div>
								</div>
								<div class="form-row">
									<div class="form-label col-md-2">
										<label for="">点击后执行的操作</label>
									</div>
									<div class="form-input col-md-10">
	<?php 	echo CHtml::dropDownList('clist['.$num.'][event_]',$vvv['onclick'],array(1=>'跳转到链接',2=>'跳转到活动',3=>'详细内容'),array('class'=>'col-md-6 float-left event_cc','empty'=>'请选择操作','data-for'=>$num,'data-trigger'=>"change", 'data-required'=>"true"));?>
   </div>
								</div>
								<div class="form-row">
									<div id="event_url" class="event_r_n_<?php echo $num;?>" <?php if($vvv['onclick']!=1&&!empty($vvv['onclick'])){echo 'style="display: none"'; }?>>
										<div class="form-label col-md-2">
											<label for=""> URL链接地址 </label>
										</div>
										<div class="form-input col-md-10">
			<?php echo CHtml::textField('clist['.$num.'][url]',$vvv['url'],array('size'=>60,'maxlength'=>100,'class'=>'col-md-6 float-left','placeholder'=>'url链接 ','id'=>'')); ?>
		</div>
									</div>
									<div id="event_act" class="event_r_n_<?php echo $num;?>" <?php if($vvv['onclick']!=2){echo 'style="display: none"'; }?>>
										<div class="form-label col-md-2">
											<label for="">活动 </label>
										</div>
										<div class="form-input col-md-10">
		<?php  echo CHtml::dropDownList('clist['.$num.'][actv]', $vvv['aid'], CHtml::listData($actlist, 'aid', 'title'),array('class'=>'col-md-6 float-left','id'=>''))?>
		</div>
									</div>
									<div id="event_content" class="event_r_n_<?php echo $num;?>" <?php if($vvv['onclick']!=3){echo 'style="display: none"'; }?>>
										<div class="form-label col-md-2">
											<label for="">详细内容 </label>
										</div>
										<div class="form-input col-md-10">
											<input type="hidden" name="clist[<?php echo $num;?>][did]" value="<?php echo $vvv['did'];?>">
		<?php echo ueditor('clist['.$num.'][detail]',$model->getDetail($vvv['did']),'detail_id'.$num,"'auto'")?>

		</div>
									</div>
								</div>
							</div>

                    </div>
                </div>
<?php }?>


            </div>


        </div>

    </div>
</div>


				</div>

				<div class="button-pane">
					<div class="form-input col-md-10 col-md-offset-2">
						<a href="javascript:addlist();" class="btn medium primary-bg">
							<span class="button-content">
								<i class="glyph-icon icon-plus float-left"></i>
								增加一条图文素材
							</span>
						</a>
						<button class="btn primary-bg medium" onclick="javascript:return $('#wx-material-form').parsley( 'validate' );">
							<span class="button-content"><i class="glyph-icon icon-check float-left"></i><?php echo $model->isNewRecord ? '提交' : '保存'; ?>
</span>
						</button>
						<a href="javascript:;" class="btn medium primary-bg">
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
		<script>
var start=<?php echo count($content);?>;
function addlist(callback){
	start++;
	if($('.news_content').length>=10){
		alert('不能再添加 了！多图文最多支持10条素材集合！');return;
	}
	$.get('<?php U('add?op=tpl&&num=');?>'+start,function(data){
		if(start>2){
			 $('.content-box').addClass('content-box-closed');
		}
		$('#main_div').append(data);
		event_bind();
		if(typeof(callback)=='function')callback();
	});
}
function event_bind(){
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
}
event_bind();
</script>
       <?php
		break;
	case 'image':
		?>


        <?php
		break;
}
?>

	   </div>
</div>
<script>
$(function(){
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