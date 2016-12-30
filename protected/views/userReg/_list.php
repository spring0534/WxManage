
<tr>

<td><?php echo CHtml::encode($data->id); ?></td><td><?php echo CHtml::encode($data->aid); ?></td><td><?php echo CHtml::encode($data->wxid); ?></td><td><?php echo CHtml::encode($data->src_openid); ?></td><td><?php echo CHtml::encode($data->ghid); ?></td><td><?php echo CHtml::encode($data->username); ?></td><td><?php echo CHtml::encode($data->phone); ?></td><td><?php echo CHtml::encode($data->company); ?></td><td><?php echo CHtml::encode($data->prize); ?></td><td><?php echo CHtml::encode($data->relate_aid); ?></td><td><?php echo CHtml::encode($data->sncode); ?></td><td><?php echo CHtml::encode($data->qrcode); ?></td><td><?php echo CHtml::encode($data->qrcode_small); ?></td><td><?php echo CHtml::encode($data->score); ?></td><td><?php echo CHtml::encode($data->total_time); ?></td><td><?php echo CHtml::encode($data->ext_info); ?></td><td><?php echo CHtml::encode($data->status); ?></td><td><?php echo CHtml::encode($data->ip); ?></td><td><?php echo CHtml::encode($data->ua); ?></td><td><?php echo CHtml::encode($data->ctm); ?></td><td><?php echo CHtml::encode($data->utm); ?></td><td><?php echo CHtml::encode($data->tags); ?></td><td><?php echo CHtml::encode($data->notes); ?></td><td><?php echo CHtml::encode($data->flag); ?></td><td><?php echo CHtml::encode($data->form_id); ?></td>
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
