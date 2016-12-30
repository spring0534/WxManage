
<div id="page-title">
	<h3>
		添加属性
		<small> >>为微应用添加属性 </small>
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
		<a href="<?php echo $this->createUrl('PluginProp/admin/ptype/'.$_GET['ptype'])?>" class="btn medium primary-bg btn_back">
			<span class="button-content">
				<i class="glyph-icon icon-mail-reply float-left"></i>
				返回
			</span>
		</a>
	</div>
	<div class="form">
		<form class="col-md-6 center-margin" id="myform" action="<?php U('create'); ?>" method="post">
			<?php echo CHtml::hiddenField('PluginProp[ptype]',$_GET['ptype']);?>
			<div class="form-row">
				<div class="form-label col-md-2">
					<label for="">
						<font color="red">*</font>
						<strong>属性类型 </strong>
					</label>
				</div>
				<div class="form-input col-md-6">
					  <?php echo CHtml::dropDownList('PluginProp[proptype]', '', CHtml::listData($diyfield, 'value', 'name'),array('empty'=>'请选择类型','onchange'=>"javascript:field_setting(this.value);",'data-trigger'=>"change", 'data-required'=>"true"));?>
		</div>
			</div>
			<div class="form-row">
				<div class="form-label col-md-2">
					<label for="">
						<font color="red">*</font>
						<strong>属性别名</strong>
					</label>
				</div>
				<div class="form-input col-md-6">
					<input type="text" name="PluginProp[proptitle]" id="name" size="30" data-trigger="change" data-required="true" placeholder='为便于阅读，一般用中文'>
					<p>例如：文章标题</p>
				</div>
			</div>
			<div class="form-row">
				<div class="form-label col-md-2">
					<label for="">
						<font color="red">*</font>
						<strong>属性名</strong>
					</label>
				</div>
				<div class="form-input col-md-6">
					<input type="text" name="PluginProp[propname]" id="field" size="20"  placeholder='只能输入字母、下划线，不能以数字开头'>
					<p>只能由英文字母组成,例如 title</p>
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
					  <?php echo CHtml::dropDownList('PluginProp[pgroup]', '', CHtml::listData(PluginPropGroup::model()->findAllByAttributes(array('ptype'=>$_GET['ptype'])), 'id', 'name'),array('empty'=>'默认','data-trigger'=>"change"));?>
				</div>
			</div>
			<div class="form-row">
				<div class="form-label col-md-2">
					<label for="">
						<strong>属性说明</strong>
					</label>
				</div>
				<div class="form-input col-md-10">
					<textarea rows="" cols="" name="PluginProp[memo]"></textarea>
					<br />
					<p>显示在字段别名右方作为表单输入提示</p>
				</div>
			</div>
			<div class="form-row">
				<div class="form-label col-md-2">
					<label for="">
						<strong>相关参数</strong>
						<br />
						设置控件相关属性
					</label>
				</div>
				<div class="form-input col-md-10">
					<div id="setting"></div>
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
						<input type="text" name="PluginProp[minlength]" id="field_minlength" value="1" size="5" class="col-md-2 ">
					</label>
					--
					<label>
						最大值：
						<input type="text" name="PluginProp[maxlength]" id="field_maxlength" value="" size="5" class="col-md-2 ">
					</label>
					<p>系统将会检测数据长度范围是否符合要求，如果不想限制长度请留空</p>
				</div>
			</div>
			<div class="form-row">
				<div class="form-label col-md-2">
					<label for="">
						<strong>数据校验正则</strong>
					</label>
				</div>
				<div class="form-input col-md-6">
			  <?php echo CHtml::dropDownList("PluginProp[pattern]", '',CHtml::listData($regxarr, 'value', 'name') ,array('empty'=>'常用正则'));?>
		<p>系统将通过此正则校验提交的数据合法性，如果不想校验数据请留空</p>
				</div>
			</div>
			<div class="form-row">
				<div class="form-label col-md-2">
					<label for="">
						<strong>是否为必填信息</strong>
					</label>
				</div>
				<div class="form-input col-md-10">
			<?php echo CHtml::switchButton('PluginProp[required]', 0, array(1=>'是',0=>'否'))?>

		</div>
			</div>
			<div class="form-row">
				<div class="form-label col-md-2">
					<label for="">
						<strong>商户是否可见</strong>
					</label>
				</div>
				<div class="form-input col-md-10">
		<?php echo CHtml::switchButton('PluginProp[isshow]', 1, array(1=>'是',0=>'否'))?>

		</div>
			</div>
			
			<div class="button-pane text-center">
				<button onclick="javascript:;" type="reset" class="btn large  text-transform-upr font-size-11" id="demo-form-valid" >
					<span class="button-content">重置</span>
				</button>
				<button onclick="javascript:return $('#myform').parsley( 'validate' );" type="submit" class="btn large primary-bg text-transform-upr font-size-11" id="demo-form-valid" >
					<span class="button-content">添加</span>
				</button>
			</div>
		</form>
	</div>
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