<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#hngame-user-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<style type="text/css">
.headimg img{
  width:60px;
  height:60px;
}
.grid-view .button-column {
    width: 100px;
}
</style> 
<div id="page-title">
	<h3>
		湖南游戏老乡会
		<small> >> 通讯录 </small>
	</h3>
</div>
<div id="page-content">
	<div class="search-form">
<?php
 $this->renderPartial('_search', array(
 	'model'=>$model
 ));
?>
</div>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'hngame-user-grid',
	'template'=>'<div class="content-box"> <table class="table table-hover text-center"><tbody>{items} </tbody></table></div><div class="summary" style="float: left;">{summary}</div><div class="pager">{pager}</div>',
	'dataProvider'=>$model->search(),
	/*'filter'=>$model,*/
	'columns'=>array(
		array('name'=>'headimgurl','type' => 'image','htmlOptions' => array('class' => 'headimg')),
		'nickname',
		'realname',
		'phone',
		'company',
		'position',
		'address',
		'qq',
		'wxname',
		'resource',
		'demand',
		array(
			'class' => 'CButtonColumn',
			'template' => '{update}{delete}',
			'buttons' => array(
				'update' => array(
					'imageUrl' => null,
					'label' => '<i class="glyph-icon icon-edit"></i>编辑',
					'options' => array(
						'class' => 'ext-cbtn'
					)
				),
				'delete' => array(
					'imageUrl' => null,
					'label' => '<i class="glyph-icon icon-remove"></i>删除',
					'options' => array(
						'class' => 'ext-cbtn delete tooltip-button',
						'data-original-title' => "删除"
					)
				)
			)
		)
	)
));
?>
</div>

