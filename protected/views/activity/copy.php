<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/artDialog.js?skin=default"></script>
<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/artiframeTools.js?skin=default"></script>
<style>
#page-content-wrapper{
margin-right:0;
}
</style>
<div class="form" style="  padding: 0;">
	<form class=" center-margin" id="activity-form" action="" method="post" style="padding: 0!important;margin:0!important;">
		<div class="infobox warning-bg" id="msgtip" style="display: none">
			<p>
				<i class="glyph-icon icon-exclamation mrg10R"></i>
			</p>
		</div>
		<input type="hidden" name="aid"  value="<?php echo $model->aid;?>">
		<div class="form-row">
			<div class="form-label col-md-3">
				<label for="">
					<label for="Activity_title" class="required">
						新的活动标题
						<span class="required">*</span>
					</label>
				</label>
			</div>
			<div class="form-input col-md-8">
				<input size="60" maxlength="255" data-trigger="change" data-required="true" name="Activity[title]" id="Activity_title" type="text" value="<?php echo $model->title.'--克隆';?>">
			</div>
		</div>
		<div class="form-row">
			<div class="form-label col-md-3">
				<label for="">
					<label for="Activity_type" class="required">
						所属商户
						<span class="required">*</span>
					</label>
				</label>
			</div>
			<div class="form-input col-md-6" >
				<div class="chosen-container chosen-container-single chosen-container-active chosen-with-drop" style="width: 390px;" title="" id="Activity_type_chosen">
					<a class="chosen-single" tabindex="-1">
						<span id="ghid"><?php echo user()->nickname.'('. $model->ghid.')';?></span>
						<div>
							<i class="glyph-icon icon-caret-down"></i>
						</div>
					</a>
					<input type="hidden" name="Activity[ghid]" id="Activity_ghid_value" value="<?php echo $model->ghid;?>">

						<?php
						if (in_array(user()->groupid, array(0, 1, 2, 3))){
						?>
							<div class="chosen-drop">
							<div class="chosen-search">
								<input type="text" data-trigger="change" data-required="true" id="Activity_ghid" type="text" value="" placeholder="输入公众号名称或者原始id进行<i class="glyph-icon icon-search"></i>查询)" autocomplete="off">
								<i class="glyph-icon icon-search"></i>
							</div>
							<ul class="chosen-results">
								<?php echo $ghidlist;?>
							</ul>
						<?php
						}
						?>

					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<script>

$(function(){

    $(document).on("click",".chosen-results li",function(){
        	var _this=this;
    		$('#ghid').text($(_this).text());
    		$('#Activity_ghid_value').val($(_this).attr('data-ghid'));
    		$(".chosen-results li").removeClass('selected');
    		$(_this).addClass('selected');
    	});
    $('#Activity_ghid').on('keyup paste', function() {
    	$.post("<?php U('copy');?>", { keyword: $(this).val() },
    	 function(data){
    		$('.chosen-results').html(data.html);
    	 },'json');

    });
});
</script>
<style>
.chosen-results li:hover {
	background-color: #0F78DD;
	color: #ffffff;
}

.selected {
	background-color: #0F78DD;
	color: #ffffff;
}
</style>