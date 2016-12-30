<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#sys-user-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div id="page-title">
	<h3>
		管理
		<small> >>批量开通微应用 </small>
	</h3>
	<div id="breadcrumb-right">
		<div class="float-right">
			<a href="<?php echo WEB_URL.Yii::app()->request->getUrl();?>" class="btn medium bg-white tooltip-button black-modal-60 mrg5R" data-placement="bottom" data-original-title="刷新">
				<span class="button-content">
					<i class="glyph-icon icon-refresh"></i>
				</span>
			</a>
		</div>
	</div>
</div>
<div id="page-content">
	<form style="margin: 0" id="yw0" action="<?php echo $this->createUrl('/plugin/batchOpen'); ?>" method="POST">
		<div class="search-form">
			<div class="explain-col">
				<label for="Plugin_ptype">请选择开通微应用</label>
				: &nbsp;
				<?php  echo CHtml::dropDownList('Plugin[ptype]','', CHtml::listData($this->getActList(true), 'ptype', 'plugin.name'),array('data-trigger'=>"change", 'data-required'=>"true",'empty'=>'请选择活动类型','style'=>'width:200px'));?>
				<label for="Plugin_name">开通次数</label>
				: &nbsp;
				<input class="input-text" name="Plugin[num]" id="Plugin_num" type="text" maxlength="20" value=1>
				<label for="Plugin_status">开通到期时间</label>
				: &nbsp;
				<?php  echo calendar("Plugin[etime]", time()+30*24*60*60,"YYYY-MM-DD hh:mm:ss");?>
				<button class="btn primary-bg medium" style="margin-left: 50px;" type="submit">
					<span class="button-content">开通</span>
				</button>
			</div>
		</div>
		<br>
		<div style="padding-top: 10px">
			<a href="javascript:;" class="btn medium primary-bg " id="checkall">
				<span class="button-content">
					<i class="glyph-icon icon-hand-up"></i>
					全选/反选
				</span>
			</a>
		</div>
		<div class="form-checkbox-radio col-md-20">
	<?php echo CHtml::multipleListButton('selectopen', '', CHtml::listData($data, 'id', 'company'))?>

	</div>
		<div style="margin-top: 20px; float: right;">
	<?php $this->widget('CLinkPager',$pages); ?>
	</div>
		<div></div>
	</form>
</div>
<style>
.multiple-select a {
	margin-top: 10px;
}
</style>
<script>
jQuery(document).on('click','#checkall',function() {
	//var checked=this.checked;
	//jQuery("input[name='selectopen\[\]']:enabled").each(function() {this.checked=checked;});
	$('.multiple-select a').each(function(index,obj){
		if($(obj).hasClass("primary-bg")){
			$(obj).siblings('select').find('option[value="'+$(obj).attr('data')+'"]').removeAttr('selected');
	    }else{
	    	$(obj).siblings('select').find('option[value="'+$(obj).attr('data')+'"]').attr('selected','selected');
	    }
		$(obj).toggleClass('primary-bg');
		$(obj).find('i').eq(0).toggleClass('icon-plus').toggleClass('icon-ok');
	});
	
});

</script>