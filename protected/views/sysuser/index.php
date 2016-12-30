
<div id="page-title">
	<h3>
		用户管理
		<small> >> 用户管理操作 </small>
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
	<div style="padding-bottom: 10px">
		<a href="<?php echo $this->createUrl('create');?>" class="btn medium primary-bg">
			<span class="button-content">
				<i class="glyph-icon icon-plus float-left"></i>
				添加用户
			</span>
		</a>
	</div>
	
<?php

$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider, 
	'itemView'=>'_view', 
	'template'=>'<div class="summary">{summary}</div><div class="content-box"> <table class="table table-hover text-center">
		<thead>
			<tr>
				
				<th>id</th>
				<th class="text-center">用户名</th>
				<th class="text-center">昵称</th>
				<th class="text-center">用户组</th>
				<th class="text-center">邮箱</th>
				<th class="text-center">管理操作</th>
			</tr>
		</thead>
		<tbody>{items} </tbody>
	</table></div><div class="pager">{pager}</div>'
));
?> 
                
            
</div>
