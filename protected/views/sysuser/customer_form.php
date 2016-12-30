
<div id="page-title">
	<h3>
		用户管理
		<small> >> 客服管理 </small>
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
	<div style="padding-bottom: 10px">
		<a href="<?php echo $this->createUrl('customer');?>" class="btn medium primary-bg btn_back">
			<span class="button-content">
				<i class="glyph-icon icon-mail-reply float-left"></i>
				返回
			</span>
		</a>
	</div>

<div class="form">

<?php

$form = $this->beginWidget('CActiveForm', array(
	'id' => 'sys-user-form',
	'htmlOptions' => array(
		'class' => "col-md-10 center-margin"
	),
	'enableAjaxValidation' => false
));
?>
<input type="hidden" name="<?php echo chtmlName($model, 'mid');?>" value="<?php echo user()->id;?>">
<input type="hidden" name="<?php echo chtmlName($model, 'pid');?>" value="<?php echo user()->id;?>">
<div class="infobox warning-bg" id='msgtip' style='display: none'>
    <p><i class="glyph-icon icon-exclamation mrg10R"></i><?php echo $form->errorSummary($model); ?></p>
</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""> 用户名: </label>
		</div>
		<div class="form-input col-md-10">
			<?php if($model->isNewRecord )
			echo $form->textField($model,'username',array('size'=>50,'maxlength'=>50,'placeholder'=>'用户名','class'=>"col-md-6 float-left",'data-trigger'=>"change",'data-required'=>"true"));
			else
			echo $form->textField($model,'username',array('size'=>50,'maxlength'=>50,'placeholder'=>'用户名','class'=>"col-md-6 float-left",'data-trigger'=>"change",'data-required'=>"true",'disabled'=>'true'));
			?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""> 昵称: </label>
		</div>
		<div class="form-input col-md-10">

			<?php echo $form->textField($model,'nickname',array('size'=>25,'maxlength'=>25,'placeholder'=>'昵称','class'=>"col-md-6 float-left",'data-trigger'=>"change",'data-required'=>"true"));?>


		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""> 密码: </label>
		</div>
		<div class="form-input col-md-10">
		<?php if($model->isNewRecord )
		echo $form->passwordField($model,'password',array('value'=>'','id'=>'newpwd','size'=>50,'maxlength'=>50,'placeholder'=>'密码','class'=>"col-md-6 float-left",'data-rangelength'=>"[6,25]",'type'=>"password",'data-trigger'=>'change', 'data-required'=>'true' ));
		else
		echo $form->passwordField($model,'password',array('value'=>'','id'=>'newpwd','size'=>50,'maxlength'=>50,'placeholder'=>'留空则不更改密码','class'=>"col-md-6 float-left",'data-rangelength'=>"[6,25]",'type'=>"password",'data-trigger'=>'change' ));;
		?>

		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""> 确认密码: </label>
		</div>
		<div class="form-input col-md-10">
		<?php if($model->isNewRecord ){?>
			  <input placeholder="确认密码" class="col-md-6 float-left" data-equalTo="#newpwd" type="password" name="info[pwdagain]" id='pwdagain' data-trigger="keyup" data-required="true" >
		<?php }else{ ?>
		  <input placeholder="确认密码" class="col-md-6 float-left" data-equalTo="#newpwd" type="password" name="info[pwdagain]" id='pwdagain' data-trigger="keyup"  >
		<?php };?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""> 用户类型: </label>
		</div>
		<div class="form-input col-md-10">

			<?php echo CHtml::dropDownList(chtmlName($model, 'groupid'), 5, CHtml::listData($model->getGrouplist(), 'groupid', 'groupname'),array(/*'empty'=>'请选择',*/'class'=>"col-md-6 float-left",'disabled'=>'true'))?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""> 状态: </label>
		</div>
		<div class="form-input col-md-10">
		<div class="form-checkbox-radio col-md-10">

			<?php
			if($model->isNewRecord){
				 echo CHtml::switchButton(chtmlName($model, 'status'), 1,array(1=>'启用',0=>'禁用'));
			}else{

				 echo CHtml::switchButton(chtmlName($model, 'status'), $model->status,array(1=>'启用',0=>'禁用'));
			}
			?>

		</div>
		</div>
	</div>
	
	<div class="form-row">
		<div class="form-input col-md-10 col-md-offset-2">
			<button class="btn primary-bg medium"
				onclick="javascript:return $('#sys-user-form').parsley( 'validate' );">
				<span class="button-content"><i class="glyph-icon icon-check float-left"></i><?php echo $model->isNewRecord ? '提交' : '保存'; ?></span>
			</button>

		</div>

	</div>


<?php $this->endWidget(); ?>

</div>
<!-- form -->
<script>
if($('.errorSummary').length>0){
	$('#msgtip').slideDown();

}
$('body').click(function(){
	$('#msgtip').slideUp();
});
</script>
</div>
