
<table cellpadding="2" cellspacing="1" width="98%" class="table table-hover">
	<tr>
		<td width="100">选项列表</td>
		<td>
			<textarea name="setting[options]" rows="2" cols="20" id="options"
				style="height: 100px; width: 400px;"><?php echo $setting[options]; ?></textarea>
		</td>
	</tr>
	
	<tr>
		<td>默认值</td>
		<td>
			<input name="setting[defaultvalue]" type="text"
				class="col-md-6 float-left"
				value="<?php echo $setting[defaultvalue]; ?>" size="40">
		</td>
	</tr>
</table>
<SCRIPT LANGUAGE="JavaScript">
function fieldtype_setting(obj) {
	if(obj!='varchar') {
		$('#minnumber').css('display','');
	} else {
		$('#minnumber').css('display','none');
	}
}
</SCRIPT>