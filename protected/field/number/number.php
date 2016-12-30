
<table cellpadding="2" cellspacing="1" width="98%" class="table table-hover">
	<tr>
		<td width="100">取值范围</td>
		<td>
			<input type="text" name="setting[minnumber]"
				value="<?php echo $setting[minnumber]; ?>" size="5" class="input-text">
			-
			<input type="text" name="setting[maxnumber]"
				value="<?php echo $setting[maxnumber]; ?>" size="5" class="input-text">
		</td>
	</tr>
	<tr>
		<td>小数位数:</td>
		<td>
			<?php echo CHtml::dropDownList("setting[decimaldigits]", $setting[decimaldigits], array(0=>0,1=>1,2=>2,3=>3,4=>4,5=>5))?>
		</td>
	</tr>
	<tr>
		<td>默认值</td>
		<td>
			<input type="text" name="setting[defaultvalue]"
				value="<?php echo $setting[defaultvalue]; ?>" size="40" class="col-md-6 float-left">
		</td>
	</tr>
</table>