<!-- 页面标题 -->
<div>
<?php
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'sys-user-gh-form',
	'htmlOptions'=>array(
		'class'=>'col-md-10 center-margin'
	),
	'enableAjaxValidation'=>false
));
?>

        <div class="infobox warning-bg" id='msgtip' style='display: none'>
		<p>
			<i class="glyph-icon icon-exclamation mrg10R"></i><?php echo $form->errorSummary($model); ?>
		    </p>
	</div>

	 <div class="tabs">
            <ul>
                <li>
                    <a href="#icon-only-tabs-1" title="Tab 1">
                        <i class="glyph-icon icon-archive float-left opacity-80"></i>
                        基础信息
                    </a>
                </li>
                <li>
                    <a href="#icon-only-tabs-2" title="Tab 2">
                        <i class="glyph-icon icon-beaker float-left opacity-80"></i>
                       微信支付
                    </a>
                </li>
                <li>
                    <a href="#icon-only-tabs-3" title="Tab 3">
                        <i class="glyph-icon icon-cogs float-left opacity-80"></i>
                       授权方式
                    </a>
                </li>
            </ul>
            <div id="icon-only-tabs-1">
               <div class="form-row">
		<div class="form-label col-md-2">
			<?php echo $form->labelEx($model,'api_url'); ?>
		</div>
		<div class="form-input col-md-6">
			<?php echo $form->textField($model, '',array('value'=>Yii::app()->params['wxapiBaseUrl'].$model->api_url,'readOnly'=>'', 'size'=>60,'maxlength'=>200)); ?>

		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<strong></strong><?php echo $form->labelEx($model,'api_token'); ?>
		</div>
		<div class="form-input col-md-6">
			<?php echo $form->textField($model,'api_token',array('size'=>60,'maxlength'=>100,'readOnly'=>'')); ?>

		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<?php echo $form->labelEx($model,'ghid'); ?>
		</div>
		<div class="form-input col-md-6">
			<?php echo $form->textField($model,'ghid',array('size'=>50,'maxlength'=>50,'readOnly'=>'', )); ?>

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

<?php echo CHtml::dropDownList(chtmlName($model, 'type'), $model->type, Yii::app()->params['ghtype'])?>
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
            </div>
            <div id="icon-only-tabs-2">
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
            </div>
            <div id="icon-only-tabs-3">
              <div class="form-row">
		<div class="form-label col-md-2">
			<label>oauth授权方式 </label>
		</div>
		<div class="form-input col-md-6">


<?php echo CHtml::dropDownList(chtmlName($model, 'oauth'), $model->oauth, Yii::app()->params['oauthtype'])?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label>微信分享方式 </label>
		</div>
		<div class="form-input col-md-6">
	<?php echo CHtml::dropDownList(chtmlName($model, 'jsapi'), $model->jsapi, Yii::app()->params['oauthtype'])?>
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
            </div>
        </div>






	<div class="button-pane">
		<div class="form-input col-md-6 col-md-offset-2">
			<button class="btn primary-bg medium" onclick="javascript:return $('#sys-user-gh-form').parsley( 'validate' );">
				<span class="button-content">
					<i class="glyph-icon icon-check float-left"></i><?php echo $model->isNewRecord ? '提交' : '保存'; ?>
</span>
			</button>
		</div>
	</div>
<?php $this->endWidget(); ?>

</div>
<!-- form -->
