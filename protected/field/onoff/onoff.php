
<table cellpadding="2" cellspacing="1" width="98%" class="table table-hover">
	<tr>
		<td width="100">显示样式</td>
		<td>
			<input type="text" name="setting[style]" value="<?php echo $setting[style]?$setting[style]:'1=Yes|0=No'; ?>"
				size="10" class="col-md-6 float-left">
		</td>
	</tr>
	<tr>
		<td>默认值</td>
		<td>
			<?php echo CHtml::switchButton("setting[defaultvalue]", $setting[defaultvalue]?$setting[defaultvalue]:"")?>;
		</td>
	</tr>
	
</table>