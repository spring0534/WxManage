
<tr>
	<td><?php echo CHtml::encode($data->id); ?></td>
	<td><?php echo CHtml::encode($data->username); ?></td>
	<td><?php echo CHtml::encode($data->nickname); ?></td>
	<td><?php echo CHtml::encode($this->getGroupName($data->groupid)); ?></td>
	<td><?php echo CHtml::encode($data->email); ?></td>
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
				<?php echo CHtml::link('<i class="glyph-icon icon-edit mrg5R"></i>修改', array('update', 'id'=>$data->id)); ?>
				</li>
				
				<li class="divider"></li>
				<li onclick="return confirm('确定要删除?删除后将不可恢复!')">
					<?php echo CHtml::link('<i class="glyph-icon icon-remove mrg5R"></i>删除', array('delete', 'id'=>id),array('class'=>"font-red",'encode'=>false )); ?>
				</li>
			</ul>
		</div>
	</td>
</tr>


