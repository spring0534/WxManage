<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#redpack-pack-grid').yiiGridView('update', {
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
</style> 
<div id="page-title">
	<h3>
		现金红包
		<small> >>派发列表 </small>
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

function displayStatus($status){
	if ($status==0){
		return "<font color=#DD4F43>审核失败</font>";
	}elseif ($status==1){
		return "<font color=#65BBF2>等待审核</font>";
	}elseif ($status==2){
		return "<font color=#19A15F>派发成功</font>";
	}elseif ($status==3){
		return "<font color=#FFCE42>派发失败</font>";
	}
}

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'redpack-pack-grid',
	'template'=>'<div class="content-box"> <table class="table table-hover text-center"><tbody>{items} </tbody></table></div><div class="summary" style="float: left;">{summary}</div><div class="pager">{pager}</div>',
	'dataProvider'=>$model->search(),
	/*'filter'=>$model,*/
	'columns'=>array(
		array('name'=>'headimgurl','type' => 'image','htmlOptions' => array('class' => 'headimg')),
		'nickname',
		'tb_order_no',
		array('header'=>'金额（元）','name'=>'amount','value' => '$data->amount/100'),
		array(
			'header'=>'状态',
			'name'=>'status',
			'value'=>'displayStatus($data->status)',
			'type'=>'html'
		),
		'ctm',
		array(
			'class'=>'CButtonColumn', 
			'template'=>'{update}', 
			'header'=>'管理操作', 
			'htmlOptions'=>array(
				"width"=>'10%'
			), 
			'buttons'=>array(
				'update'=>array(
						'imageUrl'=>null,
						'label'=>'<i class="glyph-icon icon-edit"></i>审核',
//						'url'=>'"javascript:showlink(\'\');"',
						'url'=>'Yii::app()->createUrl("/redpack/send", array("id"=>$data->id))',
						'options'=>array(
							'class'=>'link ext-cbtn tooltip-button',
							'title'=>'',
							'data-original-title'=>"审核"
						)
				)
			)
		)
	)
));
?>
<script type="text/javascript">
function showlink(qc,url){
	 Wind.use("artDialog",function(){
		 top.art.dialog({
	        id:'link',
	        fixed: true,
	        lock: true,
	        title:'链接信息',
	        padding:8,
	        background:"#000000",
	        opacity:0.27,
	        close:function(){
	       		$("#rightMain").contents().find('embed').remove();
	        },
	        content: ''
	    });
	 });
}
</script>
</div>

