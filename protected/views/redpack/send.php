<div id="page-title">
	<h3>
		现金红包
		<small> >>订单审核 </small>
	</h3>
</div>
<div class="form">
<?php
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'plugin-theme-form',
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
    <?php echo $form->hiddenField($model, 'id');?>
    <div class="form-row">
		<div class="form-label col-md-2">
			<label for="">订单编号</label>
		</div>
		<div class="form-input col-md-10" style="font-size: 20px;">
			<?php echo $model->tb_order_no; ?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for="">金额(元)</label>
		</div>
		<div class="form-input col-md-3">
			<?php echo $form->textField($model,'amount',array('size'=>10,'maxlength'=>10,'class'=>'col-md-6','onkeyup'=>"this.value=this.value.replace(/[^0-9.]/g,'')")); ?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for="">状态</label>
		</div>
		<div class="form-input col-md-8">
			<div class="form-input col-md-3"><?php echo $form->dropDownList($model,'status',array('0'=>'审核失败','2'=>'审核通过',))?></div>
			<div class="form-input col-md-9" style="color: red;">审核通过同时会给用户派发红包</div>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for="">备注</label>
		</div>
		<div class="form-input col-md-8">
			<div class="form-input col-md-6"><?php echo $form->textArea($model,'remark',array('rows'=>5,'width'=>'300px'))?></div>
			<div class="form-input col-md-6" style="color: red;">备注信息将发送给用户</div>
			<div class="form-input col-md-10">
    		<input type='radio' name='remarkRadio' onchange="check_radio()" style="width:20px;" 
    		value='亲爱的，福利已发放，请查收；产品使用中如有任何问题随时联系，我们定当竭尽所能，肝脑涂地~。老主顾服务平台正在建设中，持续关注有更多福利哦~我们不会辜负任何一位买家，感谢您的支持，祝您生活愉快~~消息如有打扰，跪求您海涵~'>
    		亲爱的，福利已发放，请查收；产品使用中如有任何问题随时联系，我们定当竭尽所能，肝脑涂地~。老主顾服务平台正在建设中，持续关注有更多福利哦~我们不会辜负任何一位买家，感谢您的支持，祝您生活愉快~~消息如有打扰，跪求您海涵~
            </div>
            <div class="form-input col-md-10">
            <input type='radio' name='remarkRadio' onchange="check_radio()" style="width:20px;" 
            value='亲爱的，您提交的订单号码有误，我们无法查询到您的订单，请核实后再提交哦。或直接联系我们说明，我们不会辜负任何一位买家，谢谢您的支持!'>
                                     亲爱的，您提交的订单号码有误，我们无法查询到您的订单，请核实后再提交哦。或直接联系我们说明，我们不会辜负任何一位买家，谢谢您的支持!
            </div>
            <div class="form-input col-md-10">
            <input type='radio' name='remarkRadio' onchange="check_radio()" style="width:20px;" 
            value='亲提交的订单尚未评价哦，如您满意请提交评价晒图后24小时内给您发奖项红包呢，如有任何问题随时联系，我们定当竭力为您服务，我们不会辜负任何一位买家，谢谢您的支持!'>
                                     亲提交的订单尚未评价哦，如您满意请提交评价晒图后24小时内给您发奖项红包呢，如有任何问题随时联系，我们定当竭力为您服务，我们不会辜负任何一位买家，谢谢您的支持!
		    </div>
		</div>
	</div>
	<div class="button-pane">
		<div class="form-input col-md-10 col-md-offset-2">
			<button class="btn primary-bg medium">
				<span class="button-content"><i class="glyph-icon icon-check float-left"></i>保存</span>
			</button>
		</div>
	</div>
<?php $this->endWidget(); ?>
</div>
<script type="text/javascript">
function check_radio(){
    var chkObjs = document.getElementsByName("remarkRadio");
    for(var i=0;i<chkObjs.length;i++){
        if(chkObjs[i].checked){
//             if(chk == i){
//                 break;
//             }
            document.getElementById("RedpackTask_remark").value=chkObjs[i].value;
        }
    }
}
</script>