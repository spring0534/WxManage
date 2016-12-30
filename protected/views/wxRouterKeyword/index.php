
<div id="page-title">
	<small> 全局菜单管理，权限设置会以菜单为显示基础 </small>
</div>
<div id="page-content">

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
<th>ghid</th>
<th>keyword</th>
<th>match_mode</th>
<th>reply_type</th>
<th>reply_id</th>
<th>status</th>
<th>note</th>
<th>ctm</th>
<th>utm</th>
<th>tenant_id</th>
<th>uid</th>
<th>msg_type</th>
			</tr>
		</thead>
		<tbody>{items} </tbody>
	</table></div><div class="pager">{pager}</div>'
));
?> 
  
                
            
</div>

