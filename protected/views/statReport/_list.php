
<tr>

<td><?php echo CHtml::encode($data->id); ?></td><td><?php echo CHtml::encode($data->day); ?></td><td><?php echo CHtml::encode($data->aid); ?></td><td><?php echo CHtml::encode($data->ghid); ?></td><td><?php echo CHtml::encode($data->pv); ?></td><td><?php echo CHtml::encode($data->uv); ?></td><td><?php echo CHtml::encode($data->cv); ?></td><td><?php echo CHtml::encode($data->ip); ?></td><td><?php echo CHtml::encode($data->s1); ?></td><td><?php echo CHtml::encode($data->s2); ?></td><td><?php echo CHtml::encode($data->s3); ?></td><td><?php echo CHtml::encode($data->s4); ?></td><td><?php echo CHtml::encode($data->sub); ?></td><td><?php echo CHtml::encode($data->unsub); ?></td><td><?php echo CHtml::encode($data->msg); ?></td>
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
