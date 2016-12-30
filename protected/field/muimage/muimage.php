
<table cellpadding="2" cellspacing="1" width="98%" class="table table-hover">
	
	<tr>
		<td width="190">默认值</td>
		<td>
			<?php /*echo muimageUpload('screenshots',$model->screenshots, 'screenshots_artid');*/?>
			<?php  echo muimageUpload('defaultvalue', $setting[defaultvalue], 'defaultvalue') ?>
		</td>
	</tr>
	<tr>
		<td width="171">允许上传的图片类型</td>
		<td width="989">
			<input type="text" name="setting[upload_allowext]"
				value="<?php echo $setting[upload_allowext]; ?>" size="40" class="col-md-6 float-left">
		</td>
	</tr>
	<tr>
		<td>图片上传张数</td>
		<td width="989">
			<input name="setting[upload_limit]" type="text"
				value="<?php echo $setting[upload_limit]; ?>" size="8" maxlength="8"
				class="col-md-6 float-left" />
			(0为不限)
		</td>
	</tr>
	<tr>
		<td>是否从已上传中选择</td>
		<td>
		<?php echo CHtml::switchButton('setting[isselectimage]', $setting[isselectimage]==1?1:'0', array('1'=>'是',0=>'否'));?>
			
		</td>
	</tr>
	<tr>
		<td>总文件大小</td>
		<td width="989">
			<input name="setting[size_limit]" type="text"
				value="<?php echo $setting[size_limit]; ?>" size="8" maxlength="8"
				class="col-md-6 float-left" />
			MB
		</td>
	</tr>
</table>
