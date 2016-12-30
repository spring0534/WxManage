<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#user-reg-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div id="page-title">
	<h3>
		管理
		<small>
			&lt;管理
		
		</small>
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
	<!-- search-form -->

<?php

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-reg-grid', 
	'template'=>'<div class="content-box">
		<table class="table table-hover text-center">
			<tbody>{items}
			</tbody>
		</table>
	</div>
	<div class="summary" style="float: left;">{summary}</div>
	<div class="pager">{pager}</div>', 
	'dataProvider'=>$model->search(),
	/*'ajaxUpdate'=>false,*/
	'enableHistory'=>true,
	/*'filter'=>$model,*/
	'columns'=>array(
		array(
			'name'=>'<input type="checkbox" id="checkall">选择',
			'value'=>'	CHtml::checkBox("selectopen[]",0,array("id"=>"plugin-grid_c0_".$data->id,"value"=>$data->id,"data-id"=>$data->id))',
			'type'=>'raw',
			'footer'=>'<button type="button" onclick="GetCheckbox();" class="btn medium primary-bg">删除所选</button>',
			'htmlOptions'=>array(
				'width'=>'100',
				'style'=>'text-align:center',
				'class'=>'form-checkbox-radio'
			),
			'headerHtmlOptions'=>array(
				'width'=>'24px',
				'style'=>'text-align:center',
				'class'=>'form-checkbox-radio'
			)
		),
		
		/*'id',*/ 
		'act.title', 
		/*'wxid', 
		'src_openid',*/ 
		/*'ghid',*/
		array(
			'name'=>'微信头像',
			'value'=>'CHtml::image($data->wxuser->headimgurl,"",
				array(
				"height"=>150,
				"class"=>"medium radius-all-2",
				)
			)',  // 这里显示图片
			'type'=>'raw',  // 这里是原型输出
			'htmlOptions'=>array(
				'width'=>'100',
				'style'=>'text-align:center'
			)
		),
		array(
			'name'=>'微信昵称',
			'value'=>'$data->wxuser->nickname',  
			
		),
		'username',
		'phone',
		/*'company',*/
		'prize',
		/*'relate_aid',*/
		'sncode',
		/*'qrcode',
		'qrcode_small',*/
		'score',
		/*'total_time',*/
		'ext_info',
		'status',
		array(
			'name'=>'状态',
			'value'=>'CHtml::dropDownList(chtmlName($data, "status"), $data->status, array("1"=>"有效","0"=>"无效","3"=>"已兑奖"),array("data-id"=>$data->id,"theme"=>"none","style"=>"height: 30px;border: 1px solid #D3D3D3;","onChange"=>"updateC(this)"))',
			'type'=>'raw',
			'htmlOptions'=>array(
				'width'=>'100',
				'style'=>'text-align:center',
				'class'=>'form-checkbox-radio'
			),
			'headerHtmlOptions'=>array(
				'width'=>'24px',
				'style'=>'text-align:center',
				'class'=>'form-checkbox-radio'
			)
		),
		/*'ip',*/
		/*'ua',*/
		'ctm',
		/*'utm',
		'tags',
		'notes',
		'flag',
		'form_id',*/

			array(
			'class'=>'CButtonColumn', 
			'template'=>'{delete}', 
			'viewButtonOptions'=>array(
				'title'=>'查看', 
				'style'=>'padding:10px'
			), 
			'updateButtonOptions'=>array(
				'title'=>'修改', 
				'style'=>'padding:10px'
			), 
			'header'=>'管理操作', 
			'htmlOptions'=>array(
				"width"=>'200px'
			), 
			'buttons'=>array(
				'delete'=>array(
					'imageUrl'=>null, 
					'label'=>'<i class="glyph-icon icon-remove"></i>删除', 
					'options'=>array(
						'class'=>'ext-cbtn delete tooltip-button', 
						'title'=>'', 
						'data-original-title'=>"编辑"
					)
				), 
				'update'=>array(
					'imageUrl'=>null, 
					'title'=>'', 
					'label'=>'<i class="glyph-icon icon-edit"></i>编辑', 
					'options'=>array(
						'class'=>'ext-cbtn'
					)
				), 
				'config'=>array(
					'title'=>'', 
					'label'=>'<i class="glyph-icon icon-cogs"></i>配置', 
					'options'=>array(
						'class'=>'ext-cbtn'
					), 
					'url'=>'Yii::app()->createUrl("/pluginProp/config", array("aid"=>"xxx"))'
				)
			)
		)
		
	)
));
?>
</div>
<script type="text/javascript">
function GetCheckbox(){
        var data=new Array();
        $("input:checkbox[name='selectopen[]']").each(function (){
                if($(this).is(":checked")){
                    data.push([$(this).val(),$('#etime'+$(this).attr('data-id')).val(),$('#maxnum'+$(this).attr('data-id')).val()]);
                }

        });
        if(data.length > 0){
        	if(confirm('确定要删除所选吗？')){
                $.post("<?php U('delAll')?>",{'data[]':data}, function (data) {
                       if (data=='ok') {
                        	
                            top.alert('删除成功！');
                            location.reload();
                      }else{
							alert("删除失败");
                      }
                });
        	}
        }else{
                alert("请选择要删除的选项!");
        }

}

function updateC(obj){
	if(confirm('确定要更改为此状态吗？')){
        $.post("<?php U('updateC')?>",{'id':$(obj).attr('data-id'),'value':$(obj).val()}, function (data) {
               if (data=='ok') {
                    alert('操作成功！');
              }else{
					alert("操作失败");
              }
        });
	}
}

jQuery(document).on('click','#checkall',function() {
	var checked=this.checked;
	jQuery("input[name='selectopen\[\]']:enabled").each(function() {this.checked=checked;});
});
jQuery(document).on('click', "input[name='selectdel\[\]']", function() {
	jQuery('#checkall').prop('checked', jQuery("input[name='selectdel\[\]']").length==jQuery("input[name='selectdel\[\]']:checked").length);
});
</script>