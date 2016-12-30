<!-- 页面标题 -->
<div id="page-title">
	<h3>
		基本管理
		<small> &gt;&gt;修改密码 </small>
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
	<style type="text/css">
html {
	_overflow-y: scroll
}
</style>
	<div class="common-form">
		<form class="col-md-10 center-margin" method="post" action='<?php echo $this->createUrl('SaveNewpwd');?>' id='pwd-validation'>
			<div class="form-row">
				<div class="form-label col-md-2">
					<label for=""> 请输入旧密码: </label>
				</div>
				<div class="form-input col-md-6">
					<input placeholder="旧密码" type="password" name="info[oldpwd]" id='oldpwd' data-trigger="change" data-required="true">
				</div>
			</div>
			<div class="form-row">
				<div class="form-label col-md-2">
					<label for class="label-description"> 请输入新密码: </label>
				</div>
				<div class="form-input col-md-6">
					<input placeholder="新密码,密码长度为6~25个字符" data-rangelength="[6,25]" type="password" name="info[newpwd]" id='newpwd' data-trigger="change" data-required="true">
				</div>
			</div>
			<div class="form-row">
				<div class="form-label col-md-2">
					<label for=""> 请确认新密码: </label>
				</div>
				<div class="form-input col-md-6">
					<input placeholder="请再次输入新密码" data-equalTo="#newpwd" type="password" name="info[pwdagain]" id='pwdagain' data-trigger="keyup" data-required="true">
				</div>
			</div>

			<div class="button-pane">
				<input type="hidden" name="superhidden" id="superhidden">
				<div class="form-input col-md-8 col-md-offset-2">
					<button class="btn primary-bg medium" onclick="javascript:return $('#pwd-validation').parsley( 'validate' );">
						<span class="button-content"><i class="glyph-icon icon-check float-left"></i>确认修改</span>
					</button>
				</div>
			</div>
		</form>
		<!--table_form_off-->
	</div>