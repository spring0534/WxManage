<?php
/* @var $this PluginPropController */
/* @var $model PluginProp */
/* @var $form CActiveForm */
?>
<style>
.form p {
margin: 0;
margin-top: 10px;
color: #757575;
}
</style>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'plugin-prop-form',
	'htmlOptions' =>array('class' => 'col-md-6 center-margin'),
	'enableAjaxValidation'=>false,
)); ?>

<div class="infobox warning-bg" id='msgtip' style='display: none'>
    <p><i class="glyph-icon icon-exclamation mrg10R"></i><?php echo $form->errorSummary($model); ?>
</p>
</div>

	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'ptype'); ?> </label>
		</div>
		<div class="form-input col-md-6">
			<?php echo $form->textField($model,'ptype',array('size'=>20,'maxlength'=>20,'readonly'=>'readonly')); ?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'proptype'); ?> </label>
		</div>
		<div class="form-input col-md-6">

			 <?php echo CHtml::dropDownList('PluginProp[proptype]', $model->proptype, CHtml::listData($diyfield, 'value', 'name'),array('empty'=>'请选择类型','onchange'=>"javascript:field_setting(this.value);",'data-trigger'=>"change", 'data-required'=>"true"));?>
		</div>
	</div>


<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'proptitle'); ?> </label>
		</div>
		<div class="form-input col-md-6">
			<?php echo $form->textField($model,'proptitle',array('size'=>50,'maxlength'=>50)); ?>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'propname'); ?> </label>
		</div>
		<div class="form-input col-md-6">
			<?php echo $form->textField($model,'propname',array('size'=>20,'maxlength'=>20)); ?>
		</div>
	</div>
<div class="form-row">
				<div class="form-label col-md-2">
					<label for="">
						<font color="red">*</font>
						<strong>属性分组 </strong>
					</label>
				</div>
				<div class="form-input col-md-6">
					  <?php echo CHtml::dropDownList('PluginProp[pgroup]', intval($model->pgroup), CHtml::listData(PluginPropGroup::model()->findAllByAttributes(array('ptype'=>$model->ptype)), 'id', 'name'),array('empty'=>'默认','data-trigger'=>"change"));?>
				</div>
			</div>






<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'memo'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo $form->textArea($model,'memo',array('size'=>60,'maxlength'=>500)); ?>
		</div>
	</div>



	<div class="form-row">
		<div class="form-label col-md-2">
			<label for="">附加参数 </label>
		</div>
		<div class="form-input col-md-10">
		<div id="setting">
			<?php echo $setting; ?>
			</div>
		</div>
	</div>
<div class="form-row">
				<div class="form-label col-md-2">
					<label for="">
						<strong>长度取值范围</strong>
					</label>
				</div>
				<div class="form-input col-md-10">
					<label>
						最小值：
						<input type="text" name="PluginProp[minlength]" id="field_minlength"
							value="<?php unserialize($model->setting)->minlength?>" size="5" class="col-md-2 ">
					</label>--
					<label>
					最大值：
					<input type="text" name="PluginProp[maxlength]" id="field_maxlength"
						value="<?php unserialize($model->setting)->maxlength?>" size="5" class="col-md-2 ">
						</label>
					<p>系统将会检测数据长度范围是否符合要求，如果不想限制长度请留空</p>
				</div>
			</div>

	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'required'); ?> </label>
		</div>
		<div class="form-input col-md-6">

			<?php echo CHtml::switchButton('PluginProp[required]', $model->required, array(1=>'是',0=>'否'))?>
		</div>
	</div>





	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'pattern'); ?> </label>
		</div>
		<div class="form-input col-md-6">
			  <?php echo CHtml::dropDownList("PluginProp[pattern]", $model->pattern,CHtml::listData($regxarr, 'value', 'name') ,array('empty'=>'常用正则'));?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'isshow'); ?> </label>
		</div>
		<div class="form-input col-md-10">
			<?php echo CHtml::switchButton('PluginProp[isshow]', $model->isshow, array(1=>'是',0=>'否'))?>
		</div>
	</div>


	<div class="form-row">
		<div class="form-label col-md-2">
			<label for=""><?php echo $form->labelEx($model,'editable'); ?> </label>
		</div>
		<div class="form-input col-md-10">

			<?php echo CHtml::switchButton('PluginProp[editable]', $model->editable, array(1=>'是',0=>'否'))?>
		</div>
	</div>




	<div class="button-pane">
		<div class="form-input col-md-10 col-md-offset-2">
			<button class="btn primary-bg medium"
				onclick="javascript:return $('#plugin-prop-form').parsley( 'validate' );">
				<span class="button-content"><i class="glyph-icon icon-check float-left"></i><?php echo $model->isNewRecord ? '提交' : '保存'; ?>
</span>
			</button>

			<button type="reset" class="btn medium primary-bg text-transform-upr font-size-11" id="demo-form-valid" >
						<span class="button-content"><i class="glyph-icon icon-undo float-left"></i>重置</span>
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
<script type="text/javascript">
<!--
	function field_setting(fieldtype) {
		if(fieldtype=="") return false;
		$.get("<?php U('showsubarr')?>?type="+fieldtype, function(data){
			if(data.field_basic_table=='1') {
				$('#field_basic_table0').attr("disabled",false);
				$('#field_basic_table1').attr("disabled",false);
			} else {
				$('#field_basic_table0').attr("checked",true);
				$('#field_basic_table0').attr("disabled",true);
				$('#field_basic_table1').attr("disabled",true);
			}
			if(data.field_allow_search=='1') {
				$('#field_allow_search0').attr("disabled",false);
				$('#field_allow_search1').attr("disabled",false);
			} else {
				$('#field_allow_search0').attr("checked",true);
				$('#field_allow_search0').attr("disabled",true);
				$('#field_allow_search1').attr("disabled",true);
			}
			if(data.field_allow_fulltext=='1') {
				$('#field_allow_fulltext0').attr("disabled",false);
				$('#field_allow_fulltext1').attr("disabled",false);
			} else {
				$('#field_allow_fulltext0').attr("checked",true);
				$('#field_allow_fulltext0').attr("disabled",true);
				$('#field_allow_fulltext1').attr("disabled",true);
			}
			if(data.field_allow_isunique=='1') {
				$('#field_allow_isunique0').attr("disabled",false);
				$('#field_allow_isunique1').attr("disabled",false);
			} else {
				$('#field_allow_isunique0').attr("checked",true);
				$('#field_allow_isunique0').attr("disabled",true);
				$('#field_allow_isunique1').attr("disabled",true);
			}
			$('#field_minlength').val(data.field_minlength);
			$('#field_maxlength').val(data.field_maxlength);
			$('#setting').html(data.setting);

		},"json");
	}

//-->
</script>