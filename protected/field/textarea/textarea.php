
<table cellpadding="2" cellspacing="1" width="98%" class="table table-hover">
	<tr>
		<td width="100">文本域宽度</td>
		<td>
			<input type="text" name="setting[width]" value="<?php echo $setting[width]; ?>"
				size="10" class="col-md-6 float-left">
			px
		</td>
	</tr>
	<tr>
		<td>文本域高度</td>
		<td>
			<input type="text" name="setting[height]" value="<?php echo $setting[height]; ?>"
				size="10" class="col-md-6 float-left">
			px
		</td>
	</tr>
	<tr>
		<td>默认值</td>
		<td>
			<textarea name="setting[defaultvalue]" rows="2" cols="20"
				id="defaultvalue" style="height: 60px; width: 250px;"><?php echo $setting[defaultvalue]; ?></textarea>
		</td>
	</tr>
</table>