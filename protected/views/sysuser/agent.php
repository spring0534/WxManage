<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#sys-user-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div id="page-title">
	<h3>
		管理
		<small> >>用户管理 </small>
	</h3>
	<div id="breadcrumb-right">
		<div class="float-right">
			<a href="<?php U('createAgent');?>" class="btn large primary-bg ">
			<span class="button-content">
				<i class="glyph-icon icon-plus float-left "></i>
				添加下级代理
			</span>
		</a>
		</div>
	</div>
</div>
<div id="page-content">



	<div class="search-form">
<div class="explain-col">
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'htmlOptions' => array(
		'style' => 'margin: 0'
	),
)); ?>



	<?php echo $form->label($model,'username'); ?>: &nbsp;
	<?php echo $form->textField($model,'username',array('class'=>'input-text')); ?>


	<?php echo $form->label($model,'nickname'); ?>: &nbsp;
	<?php echo $form->textField($model,'nickname',array('class'=>'input-text')); ?>



	<button class="btn primary-bg medium" style="margin-left: 50px;">
	<span class="button-content"><i class="glyph-icon icon-search"></i>查询</span>
	</button>

<?php $this->endWidget(); ?>

</div>
</div>
	<!-- search-form -->

<?php

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'sys-user-grid',
	'template'=>'<div class="content-box"> <table class="table table-hover text-center"><tbody>{items} </tbody></table></div><div class="summary" style="float: left;">{summary}</div><div class="pager">{pager}</div>',
	'dataProvider'=>$model->search_agent(),
	/*'filter'=>$model,*/
	'columns'=>array(

		/*'id', */
		/*'pid',*/

		'username',
		'nickname',
		array('name'=>'类型','value'=>'$data->group->groupname?$data->group->groupname:系统'),
		array('name'=>'级别','value'=>'$data->m_level?num2c($data->m_level).级代理:顶级代理商'),
		array('name'=>'上级代理商','value'=>'$data->puser->username?$data->puser->username."(".$data->puser->nickname.")":无'),
		array(
			'name'=>'最后登录时间 ',
			'value'=>'toDate($data->last_login_time)',

		),

		'login_count',
		'create_time',
		'last_login_ip',
		array(
			'name'=>'状态 ',
			'value'=>'showstatu($data->status)',
			'type'=>'html'
		),

					array(
			'class' => 'FButtonColumn',
			'template' => '$data->getTemplate2()',

			'updateButtonOptions' => array(
				'title' => '修改',
				'style' => 'padding:10px'
			),
			'header' => '管理操作',
			'htmlOptions' => array(
				"width" => '300px'
			),
			'buttons' => array(
				'delete' => array(
					'imageUrl' => null,
					'label' => '<i class="glyph-icon icon-remove"></i>删除',
					'options' => array(
						'class' => 'ext-cbtn delete tooltip-button',
						'title' => '',
						'data-original-title' => "删除"
					)
				),
				'update' => array(
					'imageUrl' => null,
					'title' => '',
					'label' => '<i class="glyph-icon icon-edit"></i>编辑',
					'options' => array(
						'class' => 'ext-cbtn'
					),
					'url'=>'Yii::app()->createUrl("/sysuser/updateAgent", array("id"=>$data->id))'
				),
				'switch'=>array(
					'title'=>'',
					'label'=>'<i class="glyph-icon icon-cogs"></i>管理',
					'options'=>array(
						'class'=>'ext-cbtn'
					),
					'url'=>'Yii::app()->createUrl("/sysUserGh/switch", array("ghid"=>$data->ghid))'
				),
				'login'=>array(
					'title'=>'',
					'label'=>'<i class="glyph-icon icon-sun"></i>登录&管理',
					'options'=>array(
						'class'=>'ext-cbtn'
					),
					'url'=>'Yii::app()->createUrl("/sysuser/switchLogin", array("id"=>$data->id))'
				),
				'open'=>array(
					'title'=>'',
					'label'=>'<i class="glyph-icon icon-cogs"></i>微应用开通',
					'options'=>array(
						'class'=>'ext-cbtn'
					),
					'url'=>'Yii::app()->createUrl("/plugin/openPlugn", array("ghid_control"=>$data->ghid))'
				)

			)

		)
	)
));
?>
</div>
