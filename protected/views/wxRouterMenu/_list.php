
<tr>

<td><?php echo CHtml::encode($data->id); ?></td><td><?php echo CHtml::encode($data->ghid); ?></td><td><?php echo CHtml::encode($data->name); ?></td><td><?php echo CHtml::encode($data->parent_id); ?></td><td><?php echo CHtml::encode($data->seq); ?></td><td><?php echo CHtml::encode($data->menu_type); ?></td><td><?php echo CHtml::encode($data->url); ?></td><td><?php echo CHtml::encode($data->event_key); ?></td><td><?php echo CHtml::encode($data->reply_type); ?></td><td><?php echo CHtml::encode($data->reply_id); ?></td><td><?php echo CHtml::encode($data->status); ?></td><td><?php echo CHtml::encode($data->note); ?></td><td><?php echo CHtml::encode($data->ctm); ?></td><td><?php echo CHtml::encode($data->utm); ?></td>
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

