<!-- 页面标题 -->
<div id="page-title">
	<h3>
		基本管理
		<small> &gt;&gt;编辑基本信息 </small>
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
	<div class="common-form">
		<form class="col-md-10 center-margin" method="post" action='<?php echo $this->createUrl('UpdateInfo');?>' id='login-validation'>
			<div class="form-row">
				<div class="form-label col-md-2">
					<label for=""> 头像地址: </label>
				</div>
				<div class="form-input col-md-6">
					<?php echo imageUpdoad('info[headimg]', $info->headimg,'headimg',array('id'=>'headimg'));?>
				</div>
			</div>
			<div class="form-row">
				<div class="form-label col-md-2">
					<label for=""> 账号昵称: </label>
				</div>
				<div class="form-input col-md-6">
					<input placeholder="管理员昵称"  type="text" name="info[nickname]" id='nickname' data-trigger="change" value="<?php echo $info->nickname; ?>">
				</div>
			</div>
			<div class="form-row">
				<div class="form-label col-md-2">
					<label for=""> 联系电话: </label>
				</div>
				<div class="form-input col-md-6">
					<input placeholder="电话号码"  type="text" name="info[phone]" id='mobile' data-trigger="change" value="<?php echo $info->phone; ?>">
				</div>
			</div>
			<!--
			<div class="form-row">
				<div class="form-label col-md-2">
					<label for=""> qq号码: </label>
				</div>
				<div class="form-input col-md-6">
					<input placeholder="qq号"  type="text" name="info[qq]" id='qq' data-trigger="change" value="<?php echo $info->qq; ?>">
				</div>
			</div>
		 -->
			<div class="form-row">
				<div class="form-label col-md-2">
					<label for=""> 电子邮箱: </label>
				</div>
				<div class="form-input col-md-6">
					<input placeholder="邮箱" data-type="email"  type="text" name="info[email]" id='email' data-trigger="change" value="<?php echo $info->email; ?>">
				</div>
			</div>

			<div class="button-pane ">
				<input type="hidden" name="superhidden" id="superhidden">
				<div class="form-input col-md-8 col-md-offset-2">
					<button class="btn primary-bg medium" onclick="javascript:return $('#login-validation').parsley( 'validate' );">
						<span class="button-content"><i class="glyph-icon icon-check float-left"></i>保存</span>
					</button>
				</div>
			</div>
		</form>
		<!--table_form_off-->
	</div>
</div>