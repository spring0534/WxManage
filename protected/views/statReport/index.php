
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
<th>day</th>
<th>aid</th>
<th>ghid</th>
<th>pv</th>
<th>uv</th>
<th>cv</th>
<th>ip</th>
<th>s1</th>
<th>s2</th>
<th>s3</th>
<th>s4</th>
<th>sub</th>
<th>unsub</th>
<th>msg</th>
			</tr>
		</thead>
		<tbody>{items} </tbody>
	</table></div><div class="pager">{pager}</div>'
));
?> 
  
                
            
</div>

