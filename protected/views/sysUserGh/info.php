
<div id="page-title">
	<h3>
		公众号设置
		<small> &gt;&gt;更新账号 </small>
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
			<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
				<li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="icon-only-tabs-1" aria-labelledby="ui-id-7" aria-selected="false">
					<a href="#icon-only-tabs-1" title="Tab 1" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-7">
						<i class="glyph-icon icon-archive float-left opacity-80"></i>
						一键绑定
					</a>
				</li>
				<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active" role="tab" tabindex="0" aria-controls="icon-only-tabs-2" aria-labelledby="ui-id-8" aria-selected="true">
					<a href="#icon-only-tabs-2" title="Tab 2" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-8">
						<i class="glyph-icon icon-beaker float-left opacity-80"></i>
						手动绑定
					</a>
				</li>
			</ul>
			<div id="icon-only-tabs-1" aria-labelledby="ui-id-7" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-expanded="false" aria-hidden="true" style="display: none;">
				<form class="col-md-5 center-margin form-vertical " style="top: 20%; bottom: 25%; position: fixed; right: 20%; left: 20%;" id="login-validation" action="<?php U('accountOps')?>" method="post">
					<div class="content-box-wrapper pad10A pad0B">
						一键获取模式
						<small>设置用户名密码后，程序会自动采集您的公众号相关信息。</small>
						:
						<div class="divider mrg5T mobile-hidden"></div>
						<br>
						<div class="form-row">
							<div class="form-label col-md-2">
								<label for="login_email">
									微信公众帐号:
									<span class="required">*</span>
								</label>
							</div>
							<div class="form-input col-md-10">
								<div class="form-input-icon">
									<i class="glyph-icon icon-envelope-alt ui-state-default"></i>
									<input placeholder="帐号" onblur="verifyGen()" data-trigger="change" data-required="true" id="username" name="uname" type="text" maxlength="50" value="">
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="form-label col-md-2">
								<label for="login_pass">微信公众帐号密码:</label>
							</div>
							<div class="form-input col-md-10">
								<div class="form-input-icon">
									<i class="glyph-icon icon-unlock-alt ui-state-default"></i>
									<input required="required" placeholder="密码" data-trigger="keyup" data-rangelength="[3,25]" id="pwd" name="pwd" type="password" maxlength="128" value="">
								</div>
							</div>
						</div>
						<div class="form-row" style="display: none;" id="vimg">
							<div class="form-label col-md-2">
								<label for="login_pass">登录验证码:</label>
							</div>
							<div class="form-input col-md-10">
								<div class="form-input-icon">
									<i class="glyph-icon icon-unlock-alt ui-state-default"></i>
									<input type="text" name="imgcode" class="txt grid-1 alpha pin" value="" autocomplete="off">
									<span class="help-inline">
										<img src="https://mp.weixin.qq.com/cgi-bin/verifycode?username=qq&amp;r=1407836525027" id="imgverify">
										<a href="javascript:;" onClick="verifyGen()">换一张</a>
									</span>
								</div>
							</div>
						</div>
						<div class="button-pane text-center">
							<button onclick="javascript:return $('#login-validation').parsley( 'validate' );$(this).html('ssss')" type="submit" class="btn large primary-bg text-transform-upr font-size-11" id="demo-form-valid" >
								<span class="button-content">一键绑定</span>
							</button>
						</div>
					</div>
				</form>
				<script type="text/javascript">
				<!--
					var codeurl = {'1':'https://mp.weixin.qq.com/cgi-bin/verifycode', '2':'https://plus.yixin.im/captcha'};
					function verifyGen() {
						if ($('#username').val()) {
							$('#vimg').show();
							var type = $('#type').val() ? $('#type').val() : 1;
							$('#imgverify').attr('src', codeurl[type] + '?username='+$('#username').val()+'&r='+Math.round(new Date().getTime()));

						} else {
							//message('请先输入微信公众平台用户名');
						}
					}
					//verifyGen();

				//-->
				</script>
			</div>
			<div id="icon-only-tabs-2" aria-labelledby="ui-id-8" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-expanded="true" aria-hidden="false" style="display: block;">
				<div class="form">
					<?php
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'sys-user-gh-form',
	'htmlOptions'=>array(
		'class'=>'col-md-10 center-margin'
	)

));
?>

						<div class="infobox warning-bg" id="msgtip" style="display: none">
							<p>
								<i class="glyph-icon icon-exclamation mrg10R"></i>
							</p>
						</div>
							<div class="form-row">

	<div class="form-row">
		<div class="form-label col-md-2">
			<?php echo $form->labelEx($model,'ghid'); ?>
		</div>
		<div class="form-input col-md-6">
			<?php echo $form->textField($model,'ghid',array('size'=>50,'maxlength'=>50, )); ?>

		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<?php echo $form->labelEx($model,'name'); ?>
		</div>
		<div class="form-input col-md-6">
			<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50)); ?>

		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<?php echo $form->labelEx($model,'qrcode_small'); ?>
		</div>
		<div class="form-input col-md-6">

		<?php  echo imageUpdoad(get_class($model).'[qrcode_small]',$model->qrcode_small,'qrcode_small',array('id'=>'qrcode_small'),'image')?>

		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2"><label>公众号类型</label></div>
		<div class="form-input col-md-6">

<?php echo CHtml::groupButton(chtmlName($model, 'type'), $model->type, Yii::app()->params['ghtype'])?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<?php echo $form->labelEx($model,'wxh'); ?>
		</div>
		<div class="form-input col-md-6">
			<?php echo $form->textField($model,'wxh',array('size'=>50,'maxlength'=>50)); ?>

		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<?php echo $form->labelEx($model,'company'); ?>
		</div>
		<div class="form-input col-md-6">
			<?php echo $form->textField($model,'company',array('size'=>60,'maxlength'=>100)); ?>

		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<?php echo $form->labelEx($model,'desc'); ?>
		</div>
		<div class="form-input col-md-6">
			<?php echo $form->textArea($model,'desc',array('size'=>120,'maxlength'=>200)); ?>

		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<?php echo $form->labelEx($model,'notes'); ?>
		</div>
		<div class="form-input col-md-6">
			<?php echo $form->textArea($model,'notes',array('size'=>50,'maxlength'=>50)); ?>

		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<?php echo $form->labelEx($model,'appid'); ?>
		</div>
		<div class="form-input col-md-6">
			<?php echo $form->textField($model,'appid',array('size'=>50,'maxlength'=>50)); ?>

		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<?php echo $form->labelEx($model,'appsecret'); ?>
		</div>
		<div class="form-input col-md-6">
			<?php echo $form->textField($model,'appsecret',array('size'=>50,'maxlength'=>50)); ?>

		</div>
	</div>

<div class="form-row">
		<div class="form-label col-md-2">
			<?php echo $form->labelEx($model,'encodingAESKey'); ?>
		</div>
		<div class="form-input col-md-6">
			<?php echo $form->textField($model,'encodingAESKey',array('size'=>200,'maxlength'=>200)); ?>
			<p style="color: #9D9D9D;margin-top: 5px;">如果接口消息加密模式选择的是安全模式，请填写微信公众平台提供的EncodingAESKey</p>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<?php echo $form->labelEx($model,'paySignKey'); ?>
		</div>
		<div class="form-input col-md-6">
			<?php echo $form->textField($model,'paySignKey',array('size'=>200,'maxlength'=>200)); ?>
<p style="color: #9D9D9D;margin-top: 5px;">开通微信支付配置，V2版本必填</p>
		</div>
	</div><div class="form-row">
		<div class="form-label col-md-2">
			<?php echo $form->labelEx($model,'partnerId'); ?>
		</div>
		<div class="form-input col-md-6">
			<?php echo $form->textField($model,'partnerId',array('size'=>20,'maxlength'=>20)); ?>
<p style="color: #9D9D9D;margin-top: 5px;">开通微信支付配置</p>
		</div>
	</div><div class="form-row">
		<div class="form-label col-md-2">
			<?php echo $form->labelEx($model,'partnerKey'); ?>
		</div>
		<div class="form-input col-md-6">
			<?php echo $form->textField($model,'partnerKey',array('size'=>50,'maxlength'=>50)); ?>
<p style="color: #9D9D9D;margin-top: 5px;">开通微信支付配置</p>
		</div>
	</div><div class="form-row">
		<div class="form-label col-md-2">
			<?php echo $form->labelEx($model,'mchId'); ?>
		</div>
		<div class="form-input col-md-6">
			<?php echo $form->textField($model,'mchId',array('size'=>32,'maxlength'=>32)); ?>
<p style="color: #9D9D9D;margin-top: 5px;">开通微信支付配置，V3版本必填</p>
		</div>
	</div>

	<div class="form-row">
		<div class="form-label col-md-2">
			<label>oauth授权方式 </label>
		</div>
		<div class="form-input col-md-6">


<?php echo CHtml::groupButton(chtmlName($model, 'oauth'), $model->oauth, Yii::app()->params['oauthtype'])?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label>微信分享方式 </label>
		</div>
		<div class="form-input col-md-6">
	<?php echo CHtml::groupButton(chtmlName($model, 'jsapi'), $model->jsapi, Yii::app()->params['oauthtype'])?>
		</div>
	</div>

			<?php if(user()->groupid==2){?>
	<div class="form-row">
		<div class="form-label col-md-2">
			<?php echo $form->labelEx($model,'dev_oauth_ghid'); ?>
		</div>
		<div class="form-input col-md-6">
			<?php echo $form->textField($model,'dev_oauth_ghid',array('size'=>50,'maxlength'=>50)); ?>
			<p style="color: red;margin-top: 5px;">开发者特权，优先使用此处的配置</p>
		</div>
	</div>
		<div class="form-row">
		<div class="form-label col-md-2">
			<?php echo $form->labelEx($model,'dev_jsapi_ghid'); ?>
		</div>
		<div class="form-input col-md-6">
			<?php echo $form->textField($model,'dev_jsapi_ghid',array('size'=>50,'maxlength'=>50)); ?>
			<p style="color: red;margin-top: 5px;">开发者特权，优先使用此处的配置</p>
		</div>
	</div>
	<?php }?>

						<div class="form-row">
							<div class="button-pane text-center">
								<button class="btn primary-bg medium" onclick="javascript:return $('#sys-user-gh-form').parsley( 'validate' );">
									<span class="button-content">
										<i class="glyph-icon icon-check float-left"></i>
										绑定
									</span>
								</button>
								<button type="reset" class="btn primary-bg medium" id="demo-form-valid" >
						<span class="button-content"><i class="glyph-icon icon-undo float-left"></i>重置</span>
					</button>
							</div>
						</div>

					<?php $this->endWidget(); ?>
				</div>
			</div>
		</div>
	</div>
</div>