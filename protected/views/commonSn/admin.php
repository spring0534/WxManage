<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#common-sn-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div id="page-title">
	<h3>
		SN码管理
		<small> >>管理 </small>
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
		<a href="<?php echo $this->createUrl('/activity/admin');?>" class="btn medium primary-bg ">
			<span class="button-content">
				<i class="glyph-icon icon-mail-reply-all float-left "></i>
				返回活动
			</span>
		</a>
		<a href="<?php echo U('create',array('aid'=>$aid));?>" class="btn medium primary-bg ">
			<span class="button-content">
				<i class="glyph-icon icon-plus float-left "></i>
				单个添加
			</span>
		</a>
		<a href="<?php echo WEB_URL.'/uploads/public/files/sn_template.xls';?>" class="btn medium primary-bg ">
			<span class="button-content">
				<i class="glyph-icon icon-download float-left "></i>
				下载导入模板
			</span>
		</a>
		<form action="<?php U('importExcel');?>" enctype="multipart/form-data" class=" float-right font-red" method="post">
			批量导入SN码:
			<input style="display: inline-block; width: 200px" type="File" name="fileName">
			<input type="hidden" name="aid" id="aid" value=<?php echo $aid;?>>
			<button class="btn bg-orange medium" type="submit" onclick="loadshow();">
				<span class="button-content">
					<i class="glyph-icon icon-upload float-left "></i>
					导入
				</span>
			</button>
		</form>
	</div>
	<div class="search-form">
		<input type="hidden" name="aid" id="aid" value=<?php echo $aid;?>>
<?php

$this->renderPartial('_search', array(
	'model'=>$model
));
?>
</div>
	<!-- search-form -->

<?php

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'common-sn-grid', 
	'template'=>'<div class="content-box">
		<table class="table table-hover text-center">
			<tbody>{items}
			</tbody>
		</table>
	</div>
	<div class="summary" style="float: left;">{summary}</div>
	<div class="pager">{pager}</div>', 
	'dataProvider'=>$model->search(),
	/*'filter'=>$model,*/
	'columns'=>array(
		array(
			'selectableRows'=>2, 
			'footer'=>'<button type="button" onclick="GetCheckbox();" class="btn medium primary-bg">批量删除</button>', 
			'class'=>'CCheckBoxColumn', 
			'htmlOptions'=>array(
				'class'=>'form-checkbox-radio', 
				'style'=>'float:none'
			), 
			'headerHtmlOptions'=>array(
				'width'=>'13px', 
				'style'=>'text-align:center', 
				'class'=>'form-checkbox-radio'
			), 
			'header'=>'选择', 
			'checkBoxHtmlOptions'=>array(
				'name'=>'selectdel[]'
			)
		), 
		
		'sncode', 
		'snpwd', 
		'status', 
		'grade', 
		array(
			'name'=>'备注', 
			'value'=>'$data->note'
		), 
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
				"width"=>'10%'
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
				)
			)
		)
	)
));
?>
<img src="<?php echo $this->assets(); ?>/images/loader-dark.gif" alt="" style="display: none" />
</div>
<script type="text/javascript">
function GetCheckbox(){

        var data=new Array();
        $("input:checkbox[name='selectdel[]']").each(function (){
                if($(this).is(":checked")){
                    data.push($(this).val());
                }

        });
        if(data.length > 0){
        	if(confirm('确定要删除选中项吗？')){
                $.post("<?php U(delall)?>",{'selectdel[]':data,'aid':$('#aid').val()}, function (data) {
                       if (data=='ok') {
                        	$("input:checkbox[name='selectdel[]']").each(function (){
                        		 if($(this).is(":checked")){
                                $(this).closest('tr').remove();
                        		 }
                        	});
                            alert('删除成功！','succeed');
                      }else{
							alert("删除失败");
                      }
                });
        	}
        }else{
                alert("请选择要删除的选项!");
        }

}
function loadshow() {
    d = '<div id="loader-overlay" class="ui-front hide loader ui-widget-overlay bg-gray opacity-80"><label style="left: 50%;top: 50%;   margin:37px 0 0 -17px; position: absolute;">正在导入....</label><img src="<?php echo $this->assets(); ?>/images/loader-dark.gif" alt="" /></div>';
    $("#loader-overlay").remove(),
    $("body").append(d),
    $("#loader-overlay").fadeIn("fast"),
    window.setTimeout(function() {
        $("#loader-overlay").fadeOut("fast")
    },
    55000);
   
}
</script>