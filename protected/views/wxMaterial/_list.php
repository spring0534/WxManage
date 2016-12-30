
<tr>
	<td><?php echo CHtml::encode($data->id); ?></td>
	<td><?php echo CHtml::encode($data->title); ?></td>
	<td><?php echo CHtml::encode(Yii::app()->params['msg_type'][$data->msg_type]) ?></td>
	<td><?php echo CHtml::encode($data->ghid); ?></td>
	<td><?php echo CHtml::encode($data->ctm); ?></td>
	<td><?php echo CHtml::encode($data->utm); ?></td>
	<td><?php echo CHtml::encode($data->status); ?></td>
	<td><?php echo CHtml::encode($data->operator_uid); ?></td>
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
					<a href="<?php U('update/id/'.$data->id)?>">
						<i class="glyph-icon icon-edit mrg5R"></i>
						修改
					</a>
				</li>
				
				<li class="divider"></li>
				<li onclick="return confirm('确定要删除吗?删除后将不可恢复 !');">
					<a class="font-red" href="<?php U('delete/id/'.$data->id)?>">
						<i class="glyph-icon icon-remove mrg5R"></i>
						删除
					</a>
				</li>
			</ul>
		</div>
	</td>
</tr>
