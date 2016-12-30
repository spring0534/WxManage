<include file="Public:header" />
<body>
<div class="subnav">
    <div class="content-menu ib-a blue line-x">
        <a href="{:U('index')}" ><em>菜单管理</em></a><a href='javascript:;' class="on"><em>菜单移动</em></a>    </div>
</div>
<style type="text/css">
	html{_overflow-y:scroll}
</style>
<form name="myform" id="myform" action="{:U('move')}" method="post">
<table width="100%" class="table_form contentWrap">

      <tr>
        <th  width="200">菜单名称：</th>
        <td>{$mlist.title}
        <input type="hidden" name="menuid" value="{$mlist.id}"/></td>
      </tr>
     
	<tr>
        <th>移动到：</th>
        <td>
          <select name="topid" size="20" style="width:200px">
          <volist name="toparr" id="menuarr">
            <option value="{$menuarr.id}" <eq name="menuarr.id" value="$mlist.pid">selected </eq> >{$menuarr.title}</option>
          </volist>
        </select></td>
      </tr>
</table>
<!--table_form_off-->
	<div class="btn"><input type="submit" id="dosubmit" class="button" name="dosubmit" value="提交" onclick="return confirm('确定移动？')"/></div>
</div>

</form>

</body>
</html>