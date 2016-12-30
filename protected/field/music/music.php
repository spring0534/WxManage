
<table cellpadding="2" cellspacing="1" width="98%" class="table table-hover">
	
	<tr>
		<td width="190">默认值</td>
		<td>
			<?php  echo musicUpdoad("setting[defaultvalue]", $setting[defaultvalue], 'defaultvalue', array(
			'id'=>'defaultvalue'
		));
		?>
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