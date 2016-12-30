<div id="page-title">
	<h3>
		权限管理
		<small> >>全局菜单管理，权限设置会以菜单为显示基础 </small>
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
	<script type="text/javascript">

  function checknode(obj)
  {
	  //top.alert('ss');
      var chk = $(".cbox");
      //var test = obj.checked;
      //top.alert(test);
      var count = chk.length;
     // top.alert(count);
      var num = chk.index(obj);
      //top.alert(num);
      var level_top = level_bottom =  chk.eq(num).attr('level')
      for (var i=num; i>=0; i--)
      {
              var le = chk.eq(i).attr('level');
              if(eval(le) < eval(level_top))
              {
                  chk.eq(i).get(0).checked=true;//attr("checked",true);
                  var level_top = level_top-1;
              }
      }
      for (var j=num+1; j<count; j++)
      {
              var le = chk.eq(j).attr('level');
              if(chk.eq(num).attr("checked")==true) {
                  if(eval(le) > eval(level_bottom)) chk.eq(j).get(0).checked=true;//.attr("checked",true);
                  else if(eval(le) == eval(level_bottom)) break;
              }
              else {
                  if(eval(le) > eval(level_bottom)) chk.eq(j).get(0).checked=false;//.attr("checked",false);
                  else if(eval(le) == eval(level_bottom)) break;
              }
      }
  }
  function selectAll(checked) {
	  var cks = document.getElementsByTagName("input");
	  var ckslen = cks.length;
	  for(var i=0;i<ckslen-1;i++) {
	   if(cks[i].type == 'checkbox') {
	    cks[i].checked = checked;
	   }
	  }
	 }
</script>
	<div style="padding-bottom: 10px">
		<a href="<?php echo $this->createUrl('admin');?>" class="btn medium primary-bg btn_back">
			<span class="button-content">
				<i class="glyph-icon icon-mail-reply float-left"></i>
				返回
			</span>
		</a>
	</div>
	<form name="myform" action="<?php echo $this->createUrl('saveflag');?>" method="post">
		<table class="table table-hover text-left">
			<tbody>
				<tr style="text-align: left">
					<th class="text-left" style="width: 10px">
						<div class="form-checkbox-radio col-md-10" style='padding-top:0'>
							<input type="radio" name="cc" id="cc" onclick="selectAll(true)">
							<label for="cc">全选</label>
							<input type="radio" name="cc" id="cc2" onclick="selectAll(false)">
							<label for="cc2">取消</label>
						</div>
					</th>
				</tr>

<?php echo $categorys; ?>

<?php echo $flagmenu ;?>
			</tbody>
		</table>
		<input type="hidden" name="gid" value="<?php echo $gid;?>" />
		<div class="form-row">
			<input type="hidden" name="superhidden" id="superhidden">
			<button class="btn primary-bg medium">
				<span class="button-content">提交</span>
			</button>
		</div>
	</form>
</div>
