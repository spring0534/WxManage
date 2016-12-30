<div id="page-title">
	<h3>
		菜单管理
		<small> >>系统菜单 </small>
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
		<a href="<?php echo $this->createUrl('/sysmenu/menuadd');?>" class="btn medium primary-bg">
			<span class="button-content">
				<i class="glyph-icon icon-plus float-left"></i>
				添加菜单
			</span>
		</a>
	</div>
	<form name="myform" action="<?php echo $this->createUrl('listorder');?>" method="post">
		<div class="content-box">
			<table class="table table-hover text-center">
				<tbody>
					<tr>
						<th class="text-center" style="width: 10px">排序</th>
						<th class="text-center">id</th>
						<th class="">菜单名称</th>
						<th class="text-center">模块名称</th>
						<th class="text-center">使用状态</th>
						<th class="text-center">显示状态</th>
						<th class="text-center">管理操作</th>
					</tr>
				</tbody>
				<tbody>
 <?php echo $menulist?>	
            </tbody>
			</table>
		</div>
		<div class="form-row">
			<input type="hidden" name="superhidden" id="superhidden">
			<button class="btn primary-bg medium">
				<span class="button-content">排序</span>
			</button>
		</div>
	</form>
</div>
