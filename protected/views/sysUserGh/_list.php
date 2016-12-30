
<tr>

<td><?php echo CHtml::encode($data->id); ?></td><td><?php echo CHtml::encode($data->ghid); ?></td><td><?php echo CHtml::encode($data->name); ?></td><td><?php echo CHtml::encode($data->icon_url); ?></td><td><?php echo CHtml::encode($data->qrcode); ?></td><td><?php echo CHtml::encode($data->qrcode_small); ?></td><td><?php echo CHtml::encode($data->type); ?></td><td><?php echo CHtml::encode($data->wxh); ?></td><td><?php echo CHtml::encode($data->company); ?></td><td><?php echo CHtml::encode($data->desc); ?></td><td><?php echo CHtml::encode($data->tenancy); ?></td><td><?php echo CHtml::encode($data->login_name); ?></td><td><?php echo CHtml::encode($data->login_pwd); ?></td><td><?php echo CHtml::encode($data->api_url); ?></td><td><?php echo CHtml::encode($data->api_token); ?></td><td><?php echo CHtml::encode($data->zf_api_url); ?></td><td><?php echo CHtml::encode($data->zf_api_token); ?></td><td><?php echo CHtml::encode($data->appid); ?></td><td><?php echo CHtml::encode($data->appsecret); ?></td><td><?php echo CHtml::encode($data->notes); ?></td><td><?php echo CHtml::encode($data->status); ?></td><td><?php echo CHtml::encode($data->open_portal); ?></td><td><?php echo CHtml::encode($data->open_msite); ?></td><td><?php echo CHtml::encode($data->ctm); ?></td><td><?php echo CHtml::encode($data->utm); ?></td><td><?php echo CHtml::encode($data->operator_uid); ?></td><td><?php echo CHtml::encode($data->interact); ?></td><td><?php echo CHtml::encode($data->tenant_id); ?></td><td><?php echo CHtml::encode($data->ec_cid); ?></td><td><?php echo CHtml::encode($data->oauth); ?></td><td><?php echo CHtml::encode($data->access_token); ?></td><td><?php echo CHtml::encode($data->at_expires); ?></td>
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

