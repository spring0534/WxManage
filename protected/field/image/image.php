
<table cellpadding="2" cellspacing="1" width="98%" class="table table-hover">
	<tr class="image_text_le" style="display: none">
		<td >文本框长度</td>
		<td>
			<input type="number" name="setting[width]" size="10" class="col-md-6 float-left">
			px
		</td>
	</tr>
	<tr>
		<td width="190">默认值</td>
		<td>
			<?php  echo imageUpdoad('setting[defaultvalue]',$setting[defaultvalue],'defaultvalue',array('id'=>'defaultvalue'),'image')?>
		</td>
	</tr>
	<tr>
		<td>表单显示模式</td>
		<td class='stype'>

			<?php echo CHtml::groupButton('setting[show_type]', isset($setting[show_type])?$setting[show_type]:1, array(1=>'图片模式',0=>'文本框模式'))?>
			
		</td>
	</tr>
	<tr>
		<td>允许上传的图片类型</td>
		<td>
			<input type="text" name="setting[upload_allowext]"
				value="<?php echo $setting[upload_allowext]; ?>" size="40" class="col-md-6 float-left">
		</td>
	</tr>
	<tr>
		<td>文件大小限制</td>
		<td width="989">
			<input name="setting[size_limit]" type="number"
				value="<?php echo $setting[size_limit]; ?>" size="8" maxlength="8"
				class="col-md-6 float-left" />
			MB
		</td>
	</tr>
	<tr>
		<td>是否添加水印</td>
		<td>
		<?php echo CHtml::switchButton('setting[watermark]', '0', array('1'=>'是',0=>'否'));?>
			
		</td>
	</tr>
	<tr>
		<td>是否从已上传中选择</td>
		<td>
			<?php echo CHtml::switchButton('setting[isselectimage]', $setting[isselectimage]==1?1:'0', array('1'=>'是',0=>'否'));?>
		</td>
	</tr>
	<tr>
		<td>图像大小</td>
		<td>
			宽
			<input type="number" name="setting[images_width]" value="" size="3"
				class="col-md-2 ">
			px 高
			<input type="number" name="setting[images_height]" value="" size="3"
				class="col-md-2 ">
			px
		</td>
	</tr>
</table>
<script>
$(function(){
$('select').unbind('change').bind("change",function(){
	if($(this).val()==1){
		$('.image_text_le').hide();
		}else{
			$('.image_text_le').show();
		};
  });
})
</script>