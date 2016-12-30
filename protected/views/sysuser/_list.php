
<tr>

<td><?php echo CHtml::encode($data->id); ?></td><td><?php echo CHtml::encode($data->pid); ?></td><td><?php echo CHtml::encode($data->username); ?></td><td><?php echo CHtml::encode($data->nickname); ?></td><td><?php echo CHtml::encode($data->password); ?></td><td><?php echo CHtml::encode($data->phone); ?></td><td><?php echo CHtml::encode($data->qq); ?></td><td><?php echo CHtml::encode($data->email); ?></td><td><?php echo CHtml::encode($data->last_login_time); ?></td><td><?php echo CHtml::encode($data->last_login_ip); ?></td><td><?php echo CHtml::encode($data->login_count); ?></td><td><?php echo CHtml::encode($data->create_time); ?></td><td><?php echo CHtml::encode($data->status); ?></td><td><?php echo CHtml::encode($data->groupid); ?></td>
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

