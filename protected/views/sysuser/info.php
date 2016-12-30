
<div id="page-title">
	<h3>
		公众号设置
		<small> &gt;&gt;绑定公众号 </small>
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
	<div class="example-code">
		<div class="tabs ui-tabs ui-widget ui-widget-content ui-corner-all">

			<div id="icon-only-tabs-2" aria-labelledby="ui-id-8" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-expanded="true" aria-hidden="false" style="display: block;">
				<div class="form">
					<form class="col-md-10 center-margin" id="sys-user-gh-form" action="<?php U('BindingGh/uid/'.$_GET['uid'])?>" method="post">
						<div class="infobox warning-bg" id="msgtip" style="display: none">
							<p>
								<i class="glyph-icon icon-exclamation mrg10R"></i>
							</p>
						</div>
						<div class="form-row">
							<div class="form-label col-md-2">
								<label for="SysUserGh_ghid" class="required">
									公众号ID（原始ID）
									<span class="required">*</span>
								</label>
							</div>
							<div class="form-input col-md-6">
								<input size="50" maxlength="50" name="SysUserGh[ghid]" id="SysUserGh_ghid" type="text" value="" data-trigger="change" data-required="true">
							</div>
						</div>
						<div class="form-row">
							<div class="form-label col-md-2">
								<label for="SysUserGh_name" class="required">
									公众号名称
									<span class="required">*</span>
								</label>
							</div>
							<div class="form-input col-md-6">
								<input size="50" maxlength="50" name="SysUserGh[name]" id="SysUserGh_name" type="text" value="" data-trigger="change" data-required="true">
							</div>
						</div>
						<div class="form-row">
							<div class="form-label col-md-2">
								<label for="SysUserGh_company" class="required">
									公司名
									<span class="required">*</span>
								</label>
							</div>
							<div class="form-input col-md-6">
								<input size="60" maxlength="100" name="SysUserGh[company]" id="SysUserGh_company" type="text" value="" data-trigger="change" data-required="true">
							</div>
						</div>
						<div class="form-row">
							<div class="form-label col-md-2">
								<label for="SysUserGh_wxh">微信号</label>
							</div>
							<div class="form-input col-md-6">
								<input size="50" maxlength="50" name="SysUserGh[wxh]" id="SysUserGh_wxh" type="text" value="">
							</div>
						</div>
						<div class="form-row">
							<div class="form-label col-md-2">
								<label for="">公众号类型</label>
							</div>
							<div class="form-input col-md-6">
								<?php echo CHtml::dropDownList("SysUserGh[type]", '', Yii::app()->params['ghtype'])?>
							</div>
						</div>
						<div class="form-row">
							<div class="form-label col-md-2">
								<label for="SysUserGh_desc">公众号介绍</label>
							</div>
							<div class="form-input col-md-6">
								<input size="60" maxlength="200" name="SysUserGh[desc]" id="SysUserGh_desc" type="text" value="">
							</div>
						</div>
						<div class="form-row">
							<div class="form-label col-md-2">
								<label for="SysUserGh_appid">Appid</label>
							</div>
							<div class="form-input col-md-6">
								<input size="50" maxlength="50" name="SysUserGh[appid]" id="SysUserGh_appid" type="text" value="">
							</div>
						</div>
						<div class="form-row">
							<div class="form-label col-md-2">
								<label for="SysUserGh_appsecret">Appsecret</label>
							</div>
							<div class="form-input col-md-6">
								<input size="50" maxlength="50" name="SysUserGh[appsecret]" id="SysUserGh_appsecret" type="text" value="">
							</div>
						</div>
						<div class="form-row">
							<div class="form-label col-md-2">
								<label for="SysUserGh_notes">备注</label>
							</div>
							<div class="form-input col-md-6">
								<textarea rows="6" cols="20" name="SysUserGh[notes]" id="SysUserGh_notes"></textarea>
							</div>
						</div>
						<div class="form-row">
							<div class="form-label col-md-2">
								<label for="">oauth授权方式 </label>
							</div>
							<div class="form-input col-md-6">
							<?php echo CHtml::dropDownList("SysUserGh[oauth]", '', Yii::app()->params['oauthtype'])?>

							</div>
						</div>
						
						<div class="form-row">
							<div class="button-pane text-center">
								
								
								<button type="reset" class="btn primary-bg medium" id="demo-form-valid" >
						<span class="button-content"><i class="glyph-icon icon-undo float-left"></i>重置</span>
					</button>
								<button class="btn primary-bg medium" onclick="javascript:return $('#sys-user-gh-form').parsley( 'validate' );">
									<span class="button-content">
										<i class="glyph-icon icon-check float-left"></i>
										确定保存
									</span>
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>