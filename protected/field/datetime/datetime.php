
<table cellpadding="2" cellspacing="1" width="98%" class="table table-hover">
	<tr>
		<td width="97" height="65">时间格式：</td>
		<td >
		<?php echo  CHtml::groupButton('setting[timeformat]', $setting[timeformat]?$setting[timeformat]:'YYYY-MM-DD hh:mm:ss', array('YYYY-MM-DD'=>'短时间格式(年-月-日)','YYYY-MM-DD hh:mm:ss'=>'长时间格式(年-月-日 时：分：秒)'))?>
			
		</td>
	</tr>
</table>