<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#plugin-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div id="page-title">
	<h3>
		互动微应用
		<small> &gt;&gt;为[<?php echo $gh->name?>]开通微应用</small>
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

	<div class="search-form">
<?php

$this->renderPartial('_search2', array(
	'model'=>$model
));
?>
</div>
	<!-- search-form -->

<?php

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'plugin-grid', 
	'template'=>'<div class="content-box"> <table class="table table-hover text-center"><tbody>{items} </tbody></table></div><div class="summary" style="float: left;">{summary}</div><div class="pager">{pager}</div>', 
	'dataProvider'=>$model->search2(), 
	'enableHistory'=>true,
	'columns'=>array(
		array(
			'name'=>'<input type="checkbox" id="checkall">选择', 
			'value'=>'	CHtml::checkBox("selectopen[]",$data->puse?(strtotime($data->puse->endtime)<time()?0:1):0,array("id"=>"plugin-grid_c0_".$data->id,"value"=>$data->ptype,"data-id"=>$data->id))', 
			'type'=>'raw', 
			'footer'=>'<button type="button" onclick="GetCheckbox();" class="btn medium primary-bg">开通所选</button>', 
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
		array(
			'name'=>'开通到期时间', 
			'value'=>'calendar("etime".$data->id, $data->puse->endtime,"YYYY-MM-DD hh:mm:ss","175px")', 
			'type'=>'raw', 
			'htmlOptions'=>array(
				'width'=>'150', 
				'style'=>'text-align:center'
			)
		), 
		
		array(
			'name'=>'剩余使用次数', 
			'value'=>'CHtml::telField("maxnum".$data->id,$data->puse->maxnum,array("style"=>"width:50px","id"=>"maxnum".$data->id))', 
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
		'name', 
		'simple_memo', 
		array(
			'name'=>'cate', 
			'value'=>'Yii::app()->params["plugin_cate"][$data->cate]'
		), 
		array(
			'name'=>'开通状态', 
			'value'=>'$data->puse?(strtotime($data->puse->endtime)<time()?"<font color=orange>×已过期</font>":"<font color=green>√已开通</font>"):"<font color=red>◇未开通</font>"', 
			'type'=>'html'
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
        	Wind.use("artDialog","iframeTools",function(){
        		art.dialog.confirm('确定要为<?php echo $gh->name?>开通选中的微应用吗？', function () {
        			$.post("<?php U('openselect')?>",{'data[]':data,'ghid':'<?php echo $gh->ghid;?>'}, function (data) {
        				if (data=='ok') {
                          	$("input:checkbox[name='selectdel[]']").each(function (){
                          		 if($(this).is(":checked")){
                                  $(this).closest('tr').remove();
                          		 }
                          	});
                          	 top.art.dialog({
                           	    content: '开通成功!',
                           	    ok: function () {
                           	    	 location.reload();
                           	    },
                           	    lock:true,
                           	    icon: 'succeed',
                           	    cancel: function(){ location.reload();}
                           	});
                        }else{
    							alert("开通失败");
                        }
        			});
        		}, function () {
        		    art.dialog.tips('取消操作');
        		});
            });
        }else{
                alert("请选择要开通的微应用!");
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
jQuery(document).on('click','#checkall',function() {
	var checked=this.checked;
	jQuery("input[name='selectopen\[\]']:enabled").each(function() {this.checked=checked;});
});
jQuery(document).on('click', "input[name='selectdel\[\]']", function() {
	jQuery('#checkall').prop('checked', jQuery("input[name='selectdel\[\]']").length==jQuery("input[name='selectdel\[\]']:checked").length);
});
</script>