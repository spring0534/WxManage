
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
<th>公众号</th>
<th>name</th>
<th>icon_url</th>
<th>qrcode</th>
<th>qrcode_small</th>
<th>type</th>
<th>wxh</th>
<th>company</th>
<th>desc</th>
<th>tenancy</th>
<th>login_name</th>
<th>login_pwd</th>
<th>api_url</th>
<th>api_token</th>
<th>zf_api_url</th>
<th>zf_api_token</th>
<th>appid</th>
<th>appsecret</th>
<th>notes</th>
<th>status</th>
<th>open_portal</th>
<th>open_msite</th>
<th>ctm</th>
<th>utm</th>
<th>operator_uid</th>
<th>interact</th>
<th>tenant_id</th>
<th>ec_cid</th>
<th>oauth</th>
<th>access_token</th>
<th>at_expires</th>
			</tr>
		</thead>
		<tbody>{items} </tbody>
	</table></div><div class="pager">{pager}</div>'
));
?> 
  
                
            
</div>

