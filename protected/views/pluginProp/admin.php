<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#plugin-prop-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/jquery.dragsort-0.5.2.min.js"></script>
<div id="page-title">
	<h3>
		管理
		<small> &gt;&gt;微应用属性管理 </small>
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
		<a href="<?php echo Yii::app()->createUrl('plugin/admin');?>" class="btn medium primary-bg ">
			<span class="button-content">
				<i class="glyph-icon icon-mail-reply float-left "></i>
				返回
			</span>
		</a>
		<a href="<?php U('create',array('ptype'=>$_GET['ptype']));?>" class="btn medium primary-bg ">
			<span class="button-content">
				<i class="glyph-icon icon-plus float-left "></i>
				添加属性
			</span>
		</a>
		<a href="<?php echo $this->createUrl('/pluginPropGroup/admin',array('ptype'=>$_GET['ptype']));?>" class="btn medium primary-bg ">
			<span class="button-content">
				<i class="glyph-icon icon-plus float-left "></i>
				添加属性分组
			</span>
		</a>
	</div>
	<div class="search-form">
<?php

$this->renderPartial('_search', array(
	'model'=>$model
));
?>
</div>
	<!-- search-form -->

<?php

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'plugin-prop-grid',
	'template'=>'<div class="content-box"> <table class="table table-hover text-center"><tbody>{items} </tbody></table></div><div class="summary" style="float: left;">{summary}</div><div class="pager">{pager}</div>',
	'dataProvider'=>$model->search(),
	/*'ajaxUpdate'=>false,*///条件<i class="glyph-icon icon-search"></i>查询时间不能排序，固用AJAX操作
	/*'filter'=>$model,*/
	'columns'=>array(
		'seq',
		/*'id',*/
		'proptitle',
		'propname',
		array(
			'name'=>'proptype',
			'value'=>'CHtml::listData(Yii::app()->getController()->diyfield, \'value\', \'name\')[$data->proptype].\'(\'.$data->proptype.\')\''
		),
		array(
			'name'=>'required',
			'value'=>'$data->required?"是":"否"'
		),
		array(
			'name'=>'editable',
			'value'=>'$data->editable?"是":"否"'
		),
		array(
			'name'=>'pgroup',
			'value'=>'$data->pgroup?($data->pgroups->name):"默认"'
		),
		/*
		'select_option',
		'required',
		'maxlength',
		'seq',
		'memo',
		'editable',
		'ctm',
		'utm',
		*/
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',
			'updateButtonOptions'=>array(
				'title'=>'修改',
				'style'=>'padding:10px'
			),
			'header'=>'管理操作',
			'htmlOptions'=>array(
				"width"=>'100'
			),
			/*'width'=>'200px',*/
			'buttons'=>array(
				'update'=>array(
					'imageUrl'=>null,
					'label'=>'<i class="glyph-icon icon-edit"></i>编辑',
					'options'=>array(
						'class'=>'ext-cbtn  tooltip-button',
						'title'=>'',
						'data-original-title'=>"编辑"
					)
				),
				'delete'=>array(
					'imageUrl'=>null,
					'label'=>'<i class="glyph-icon icon-remove"></i>删除',
					'options'=>array(
						'class'=>'ext-cbtn delete tooltip-button',
						'title'=>'',
						'data-original-title'=>"编辑"
					)
				),

			)

		)
	)
));
?>
</div>
<script>
$(".items tbody").dragsort({
    dragSelector : "tr",
	dragSelectorExclude:'input,textarea,span,i,img',
    dragEnd : function(){
    	 var arr=new Array();
         $("table.items tbody tr").each(function(){
            var _this=this;
				var id=$(_this).find('td').eq(1).html();
				var order=$(_this).index()+1;
				$(_this).find('td').eq(0).html(order);
				if(id&&order){
					arr.push([id,order]);
				}
			});
         $.post("<?php U('listorder',array('ptype'=>$_GET['ptype']));?>",{'data':arr},function(msg){});
    },
    scrollSpeed:0
});
</script>