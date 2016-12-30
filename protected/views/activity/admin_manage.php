
<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#activity-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div id="page-title">
	<h3>
		活动列表
		<small> >>管理活动列表 </small>
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

$this->renderPartial('_search_manage', array(
	'model'=>$model
));
?>
</div>
	<!-- search-form -->

<?php

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'activity-grid', 
	'template'=>'<div class="content-box"> <table class="table table-hover text-center"><tbody>{items} </tbody></table></div><div class="summary" style="float: left;">{summary}</div><div class="pager">{pager}</div>', 
	'dataProvider'=>$model->search_manage(),
	/*'filter'=>$model,*/
	'columns'=>array(
		
		'aid', 
		'title', 
		array(
			'name'=>'type', 
			'value'=>'$data->plugin->name'
		), 
		
		'ptheme.name', 
		array(
			'name'=>'状态 ',
			'value'=>'showstatu($data->status)',
			'type'=>'html'
		),
		'starttime', 
		'endtime',
		/*
		'need_attent',
		'ctm',
		'ltm',
		'siteid',
		'token',
		'email',
		'ghid',
		'scrurl',
		'reseturl',
		'tenant_id',
		'paid',
		'did',
		'dtype',
		*/
		array(
			'class'=>'WCMButtonColumn', 
			'header'=>'管理操作', 
			'htmlOptions'=>array(
				"width"=>'20%'
			)
		)
	)
	
));
?>
</div>
<script>
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
         content: '<div class="bootbox-body" style="width: 489px;">'+
         			'<p align="center"><img src="'+qc+'"></p>'+
		            	'<textarea style="resize: none;cursor:pointer !important;width: 400px;height:40px;" class="form-control" id="tex_copy"   >'+url+'</textarea>'+
		            	'<a href="javascript:" id="btn_copy" style="border-radius: 0;z-index:9999;position: absolute;height:40px;line-height: 40px;"  name="btn_copy"  data="'+url+'" class="btn large primary-bg" title=""><embed class="button-content" id="ZeroClipboardMovie_1" src="<?php echo $this->assets(); ?>/js/zeroClipboard/ZeroClipboard.swf" loop="false" menu="false" style="position: absolute;" quality="best" bgcolor="#ffffff" width="90" height="40" name="ZeroClipboardMovie_1" align="middle" allowscriptaccess="always" allowfullscreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="id=1&amp;width=90&amp;height=34" wmode="transparent"><span class="button-content" id="btn_copy1">复制链接</span> </a>'+
			        '</div> <script  src="<?php echo $this->assets(); ?>/js/zeroClipboard/ZeroClipboard.js?k=6620"><\/script><script>var clip = new ZeroClipboard.Client(); 	clip.setHandCursor(true);   	clip.addEventListener("mouseOver", function (client){     	clip.setText($(".form-control").val() );  	});clip.addEventListener("complete", function (client, text) {top.art.dialog({id: "Tips",title: false,cancel: false,fixed: true,lock: true,content:"复制成功^_^!", icon: "succeed",time:1, background:"#000000",  }) ; 	}); <\/script>'
        
        
     });
	 
	 });
}
function view_(url){
	 Wind.use("artDialog","iframeTools",function(){
		 top.art.dialog({
			 title: false,
			 fixed: true,
			 width:420,
			 height:680,
		     lock: true,
		     padding:0,
		     background:"#000000", opacity:0.27,
		     content:'<iframe src="'+url+'1" name="OpenartDialog142804424544122" frameborder="0" allowtransparency="true" style="width: 420px; height: 680px; border: 0px none;"></iframe>'
			});
	 });

	 
}
function copy(url){
	 Wind.use("artDialog","iframeTools",function(){
		 art.dialog.open(url, {
			    title: '确定克隆活动？',
			    fixed: true,
		        lock: true,
		        padding:8,
		        background:"#000000", opacity:0.27,
			    ok: function () {
			    	var iframe = this.iframe.contentWindow;
			    	if (!iframe.document.body) {
			        	alert('iframe还没加载完毕呢')
			        	return false;
			        };
			    	var form = iframe.document.getElementById('activity-form');
			        $.post("<?php U('copy');?>",  $(form).serialize(),
			           	 function(data){
			           		if(data){
				           		if(data.code==-1){
									alert(data.msg);
									
					           	}else{
					           		top.alert('添加成功！');
						           	if(data.code==1){
						           		var win = art.dialog.open.origin;
								    	win.location.reload();
							        }
						        }
				           	}
			           	 },'json'); 
			       
			    },
			    cancel: true
			});
	 });
}
//设置剪贴板数据
function setClipboardText(event, value){
	if(event.clipboardData){
		return event.clipboardData.setData("text/plain", value);
	}else if(window.clipboardData){
		return window.clipboardData.setData("text", value);
	}
}

</script>
