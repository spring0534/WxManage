
<div id="page-title">
	<small> 全局菜单管理，权限设置会以菜单为显示基础 </small>
</div>
<div id="page-content">
	<style type="text/css">
html {
	_overflow-y: scroll
}

table tr td {
	font-size: 12px;
	border-bottom: #E9ECF1 solid 1px;
	padding: 8px;
}
</style>
	<div style="padding-bottom: 10px">
		<a href="/"
			class="btn medium primary-bg">
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
				<th>id</th>
<th>aid</th>
<th>wxid</th>
<th>src_openid</th>
<th>ghid</th>
<th>username</th>
<th>phone</th>
<th>company</th>
<th>prize</th>
<th>relate_aid</th>
<th>sncode</th>
<th>qrcode</th>
<th>qrcode_small</th>
<th>score</th>
<th>total_time</th>
<th>ext_info</th>
<th>status</th>
<th>ip</th>
<th>ua</th>
<th>ctm</th>
<th>utm</th>
<th>tags</th>
<th>notes</th>
<th>flag</th>
<th>form_id</th>
			</tr>
		</thead>
		<tbody>{items} </tbody>
	</table></div><div class="pager">{pager}</div>'
));
?> 
  
                
            
</div>

