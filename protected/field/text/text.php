
<table cellpadding="2" cellspacing="1" width="98%" class="table table-hover">
	<tr>
		<td width="100">文本框长度</td>
		<td>
			<input type="text" name="setting[size]" value="<?php echo $setting[size]; ?>"
				size="10" class="col-md-6 float-left">
		</td>
	</tr>
	<tr>
		<td>默认值</td>
		<td>
			<input name="setting[defaultvalue]" type="text" class="col-md-6 float-left"
				value="<?php echo $setting[defaultvalue]; ?>" size="40">
		</td>
	</tr>
	<tr>
		<td>是否为密码框</td>
		<td>
		<?php echo CHtml::switchButton('setting[setting[ispassword]]', $setting['setting[ispassword]']==1?1:'0', array('1'=>'是',0=>'否'));?>
			
		</td>
	</tr>
</table>