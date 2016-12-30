
<tr>

<td><?php echo CHtml::encode($data->id); ?></td><td><?php echo CHtml::encode($data->ptype); ?></td><td><?php echo CHtml::encode($data->name); ?></td><td><?php echo CHtml::encode($data->icon_url); ?></td><td><?php echo CHtml::encode($data->simple_memo); ?></td><td><?php echo CHtml::encode($data->detail_memo); ?></td><td><?php echo CHtml::encode($data->usage); ?></td><td><?php echo CHtml::encode($data->promote); ?></td><td><?php echo CHtml::encode($data->hot); ?></td><td><?php echo CHtml::encode($data->status); ?></td><td><?php echo CHtml::encode($data->need_reset_url); ?></td><td><?php echo CHtml::encode($data->need_scr_url); ?></td><td><?php echo CHtml::encode($data->ctm); ?></td><td><?php echo CHtml::encode($data->utm); ?></td><td><?php echo CHtml::encode($data->processor_class); ?></td><td><?php echo CHtml::encode($data->dtype); ?></td><td><?php echo CHtml::encode($data->screenshots); ?></td><td><?php echo CHtml::encode($data->menus); ?></td><td><?php echo CHtml::encode($data->price_month); ?></td><td><?php echo CHtml::encode($data->price_year); ?></td><td><?php echo CHtml::encode($data->tenant_id); ?></td><td><?php echo CHtml::encode($data->uid); ?></td><td><?php echo CHtml::encode($data->cate); ?></td>
	<td>
		<div class="dropdown">
			<a href="javascript:;" title="" class="btn medium bg-blue"
				data-toggle="dropdown">
				<span class="button-content">
					<i class="glyph-icon font-size-11 icon-cog"></i>
					<i class="glyph-icon font-size-11 icon-chevron-down"></i>
				</span>
			</a>
			<ul class="dropdown-menu float-right">
				<li>
				<a href="/gii/crud/update?id="><i class="glyph-icon icon-edit mrg5R"></i>修改</a>				</li>
				<li>
				<a href="/gii/crud/editflag?gid="><i class="glyph-icon icon-calendar mrg5R"></i>权限管理</a>				</li>
				<li class="divider"></li>
				<li>
					<a class="font-red" href="/gii/crud/delete?id="><i class="glyph-icon icon-remove mrg5R"></i>删除</a>				</li>
			</ul>
		</div>
	</td>
</tr>

