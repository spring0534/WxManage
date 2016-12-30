
<div id="page-title">
	<small> 全局菜单管理，权限设置会以菜单为显示基础 </small>
</div>
<div id="page-content">

	<div style="padding-bottom: 10px">
		<a href="/"
			class="btn medium primary-bg btn_back">
			<span class="button-content">
				<i class="glyph-icon icon-plus float-left"></i>
				返回
			</span>
		</a>
	</div>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider' => $dataProvider,
	'itemView' => '_list',
	'template' =>'<div class="summary">{summary}</div><div class="content-box"> <table class="table table-hover text-center">
		<thead>
			<tr>
				<th>aid</th>
<th>type</th>
<th>akey</th>
<th>wxurl_qrcode</th>
<th>title</th>
<th>description</th>
<th>uid</th>
<th>status</th>
<th>need_attent</th>
<th>starttime</th>
<th>endtime</th>
<th>ctm</th>
<th>ltm</th>
<th>siteid</th>
<th>token</th>
<th>email</th>
<th>ghid</th>
<th>themeid</th>
<th>scrurl</th>
<th>reseturl</th>
<th>tenant_id</th>
<th>paid</th>
<th>did</th>
<th>dtype</th>
			</tr>
		</thead>
		<tbody>{items} </tbody>
	</table></div><div class="pager">{pager}</div>'
));
?>



</div>

