
<div id="page-title">
	<h3>
		系统设置
		<small> >>配置系统</small>
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
	<div class="tabs ui-tabs ui-widget ui-widget-content ui-corner-all">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li>
				<a href="#icon-only-tabs-1">
					<i class="glyph-icon icon-trello float-left opacity-80"></i>
					网站设置
				</a>
			</li>
			<li>
				<a href="#icon-only-tabs-2">
					<i class="glyph-icon icon-bar-chart float-left opacity-80"></i>
					附件设置
				</a>
			</li>
		</ul>
		<div id="icon-only-tabs-1" aria-labelledby="ui-id-1" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-expanded="false" aria-hidden="true" style="display: block;">
			<div class="form">
				<form class="col-md-20 center-margin" id="myform" action="<?php U('update'); ?>" method="post">
					<input type="hidden" name="uname" value="sysmeta">
					<div class="form-row">
						<div class="form-label col-md-3">
							<label for="">
								<strong>网站开关:</strong>
							</label>
						</div>
						<div class="form-input col-md-6">
				<?php echo CHtml::switchButton("info[webclose]", $list['webclose'],array(1=>'开启',0=>'关闭'))?>
						
					
				</div>
					</div>
					<div class="form-row">
						<div class="form-label col-md-3">
							<label for="">
								<strong>网站名称:</strong>
							</label>
						</div>
						<div class="form-input col-md-6">
						
					<?php echo CHtml::telField("info[site_name]", $list['site_name']);?>
				</div>
					</div>
					<div class="form-row">
						<div class="form-label col-md-3">
							<label for="">
								<strong> 联系电话 :</strong>
							</label>
						</div>
						<div class="form-input col-md-6">
						<?php echo CHtml::telField("info[site_phone]", $list['site_phone']);?>
				</div>
					</div>
					<div class="form-row">
						<div class="form-label col-md-3">
							<label for="">
								<strong> 网站favicon图标:</strong>
							</label>
						</div>
						<div class="form-input col-md-6">
						<?php echo imageUpdoad('info[seo_favicon]',  $list['seo_favicon'],'seo_favicon',array('id'=>'seo_favicon'));?>
				</div>
					</div>
					<div class="form-row">
						<div class="form-label col-md-3">
							<label for="">
								<strong> 网站LOGO:</strong>
							</label>
						</div>
						<div class="form-input col-md-6">
						
						<?php echo imageUpdoad('info[logo]',  $list['logo'],'logo',array('id'=>'logo'));?>
				</div>
					</div>
					<div class="form-row">
						<div class="form-label col-md-3">
							<label for="">
								<strong>站点邮箱:</strong>
							</label>
						</div>
						<div class="form-input col-md-6">
						<?php echo CHtml::telField("info[site_email]", $list['site_email']);?>
				</div>
					</div>
					<div class="form-row">
						<div class="form-label col-md-3">
							<label for="">
								<strong> 网站标题:</strong>
							</label>
						</div>
						<div class="form-input col-md-6">
						<?php echo CHtml::telField("info[seo_title]", $list['seo_title']);?>
				</div>
					</div>
					<div class="form-row">
						<div class="form-label col-md-3">
							<label for="">
								<strong>网站底部统计代码:</strong>
							</label>
						</div>
						<div class="form-input col-md-6">
						<?php echo CHtml::textArea("info[site_statistics]", $list['site_statistics']);?>
				</div>
					</div>
					
					<div class="button-pane text-center">
						<button  type="reset" class="btn large  text-transform-upr font-size-11" id="demo-form-valid" >
							<span class="button-content">重置</span>
						</button>
						<button onclick="javascript:return $('#myform').parsley( 'validate' );" type="submit" class="btn large primary-bg text-transform-upr font-size-11" id="demo-form-valid" >
							<span class="button-content">提交</span>
						</button>
					</div>
				</form>
			</div>
		</div>
		<div id="icon-only-tabs-2" aria-labelledby="ui-id-2" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-expanded="false" aria-hidden="true" style="display: block;">
		无
		</div>
	</div>
	<script>
$(document).ready(function(){
	ajax_up('#lup',cfun,'watermark_img','0','0','0','0');
	
});
function cfun(res,obj){
	$("#watermark_img_div").html('<img src="'+weburl+res.msg+'"/>  <input type="hidden" name="info[watermark_img]" id="watermark_img" value="'+res.msg+'"/>');
}
	
</script>
</div>
</div>
</div>
</div>
