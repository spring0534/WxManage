<!-- ----------------------------------------------------------- -->
 <div class="content-box box-toggle-wt button-toggle" style="margin-bottom:20px">
            <h3 class="content-box-header primary-bg" style="cursor: move;">
                <span class="float-left"><span><i class="glyph-icon icon-bar-chart float-left"></i> &nbsp;多图文第<?php echo $num;?>条</span> </span>
                <a href="javascript:" class="float-right icon-separator btn toggle-button toggle-button-op" title="展开&收缩" style="display: inline; ">
                    <i class="glyph-icon icon-toggle icon-chevron-down"></i>
                </a>
                <a href="javascript:" class="float-right icon-separator btn toggle-button-rm" data-style="dark" data-theme="bg-white" data-opacity="60" style="display: inline; ">
                    <i class="glyph-icon icon-remove"></i>
                </a>
               
            </h3>
            <div class="content-box-wrapper" style="">
  
<div class="news_content" data-index=1>
<div class="form-row">
	<div class="form-label col-md-2">
		<label for="">标题</label>
	</div>
	<div class="form-input col-md-10">
		<?php echo CHtml::textField('clist['.$num.'][name]','',array('size'=>60,'maxlength'=>100,'class'=>'col-md-6 float-left from-field','id'=>'','data-trigger'=>"change", 'data-required'=>"true",)); ?>
	</div>
</div>

<div class="form-row">
	<div class="form-label col-md-2">
		<label for="">显示图片</label>
	</div>
	<div class="form-input col-md-10">
		<?php  echo imageUpdoad('clist['.$num.'][pic]','','qrcode_small'.$num,array('id'=>'qrcode_small'.$num),'image')?>
	</div>
</div>
<div class="form-row">
	<div class="form-label col-md-2">
		<label for="">点击后执行的操作</label>
	</div>
	<div class="form-input col-md-10">
	<?php 	echo CHtml::dropDownList('clist['.$num.'][event_]',1,array(1=>'跳转到链接',2=>'跳转到活动',3=>'详细内容'),array('class'=>'col-md-6 float-left event_cc','empty'=>'请选择操作','data-for'=>$num,'data-trigger'=>"change", 'data-required'=>"true"));?>
   </div>
</div>
<div class="form-row">
	<div id="event_url" class="event_r_n_<?php echo $num;?>">
		<div class="form-label col-md-2">
			<label for=""> URL链接地址 </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo CHtml::textField('clist['.$num.'][url]','',array('size'=>60,'maxlength'=>100,'class'=>'col-md-6 float-left','placeholder'=>'url链接 ','id'=>'')); ?>
		</div>
	</div>
	<div id="event_act" class="event_r_n_<?php echo $num;?>" style="display: none">
		<div class="form-label col-md-2">
			<label for="">活动 </label>
		</div>
		<div class="form-input col-md-10">
		<?php  echo CHtml::dropDownList('clist['.$num.'][actv]', '1', CHtml::listData($actlist, 'aid', 'title'),array('class'=>'col-md-6 float-left','id'=>''))?>
		</div>
	</div>
	<div id="event_content" class="event_r_n_<?php echo $num;?>" style="display: none">
		<div class="form-label col-md-2">
			<label for="">详细内容 </label>
		</div>
		<div class="form-input col-md-10">
		<?php echo ueditor('clist['.$num.'][detail]','','detail_id'.$num,"'auto'")?>
	
		</div>
	</div>
</div>
</div>

</div>
 </div>
<!-- ----------------------------------------------------------- -->