<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#wx-router-keyword-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div id="page-title">
	<h3>
		自动回复管理
		<small> &gt;&gt;管理 </small>
	</h3>
	<div id="breadcrumb-right">
		                <div class="dropdown dash-menu">

                    <a href="javascript:;" class="btn btn large primary-bg " data-toggle="dropdown" data-placement="left">
						<span class="button-content">
							<i class="glyph-icon icon-plus float-left "></i>
							添加自动回复
						</span>
					</a>
                    <div class="dropdown-menu float-right qmenu" style="padding-top: 0;">
                        <div class="small-box" style="  width: 355px;">
                            <div class="primary-bg text-transform-upr font-size-12 font-bold pad10A">请选择素材类型</div>
                            <div class="pad10A dashboard-buttons clearfix">
                                <a style="width: 80px;" href="<?php U('create/msg_type/text')?>" class="btn vertical-button remove-border bg-blue" title="">
                                    <span class="glyph-icon icon-separator-vertical pad0A medium">
                                        <i class="glyph-icon icon-dashboard opacity-80 font-size-20"></i>
                                    </span>
                                    <span class="button-content">文本回复</span>
                                </a>
                                <a  style="width: 80px;" href="<?php U('create/msg_type/subscribe')?>" class="btn vertical-button remove-border bg-red" title="">
                                    <span class="glyph-icon icon-separator-vertical pad0A medium">
                                        <i class="glyph-icon icon-tags opacity-80 font-size-20"></i>
                                    </span>
                                    <span class="button-content">关注回复</span>
                                </a>
                                <a  style="width: 80px;" href="<?php U('create/msg_type/image')?>" class="btn vertical-button remove-border bg-purple" title="">
                                    <span class="glyph-icon icon-separator-vertical pad0A medium">
                                        <i class="glyph-icon icon-reorder opacity-80 font-size-20"></i>
                                    </span>
                                    <span class="button-content">图片默认回复</span>
                                </a>
                                 <a style="width: 80px;"  href="<?php U('create/msg_type/voice')?>" class="btn vertical-button remove-border bg-purple" title="">
                                    <span class="glyph-icon icon-separator-vertical pad0A medium">
                                        <i class="glyph-icon icon-reorder opacity-80 font-size-20"></i>
                                    </span>
                                    <span class="button-content">语音默认回复</span>
                                </a>
                                 <a  style="width: 80px;" href="<?php U('create/msg_type/video')?>" class="btn vertical-button remove-border bg-purple" title="">
                                    <span class="glyph-icon icon-separator-vertical pad0A medium">
                                        <i class="glyph-icon icon-reorder opacity-80 font-size-20"></i>
                                    </span>
                                    <span class="button-content">视频默认回复</span>
                                </a>
                                 <a  style="width: 80px;" href="<?php U('create/msg_type/other')?>" class="btn vertical-button remove-border bg-purple" title="">
                                    <span class="glyph-icon icon-separator-vertical pad0A medium">
                                        <i class="glyph-icon icon-reorder opacity-80 font-size-20"></i>
                                    </span>
                                    <span class="button-content">无匹配回复</span>
                                </a>


                            </div>
                        </div>
                    </div>
                </div>
	</div>
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
	'id'=>'wx-router-keyword-grid',
	'template'=>'<div class="content-box"> <table class="table table-hover text-center"><tbody>{items} </tbody></table></div><div class="summary" style="float: left;">{summary}</div><div class="pager">{pager}</div>',
	'dataProvider'=>$model->search(),
	/*'filter'=>$model,*/
	'columns'=>array(
		array(
			'name'=>'keyword',
			'value'=>'showKeyword($data->msg_type,$data->keyword)',
			'type'=>'html'
		),
		array(
			'name'=>'match_mode',
			'value'=>'$data->match_mode==1?"完全匹配":"包含匹配"'
		),
		array(
			'name'=>'reply_type',
			'value'=>'$data->reply_type==1?"素材库内容":"第三方接口"'
		),
		array(
			'name'=>'reply_id',
			'value'=>'WxRouterKeyword::model()->getReplyByid($data->reply_id,$data->reply_type)',
			'type'=>'html'
		),
		array(
			'name'=>'status',
			'value'=>'showstatu($data->status)',
			'type'=>'html'
		),
		/*
		'note',
		'ctm',
		'utm',
		'tenant_id',
		'uid',
		'msg_type',
		*/
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',
			'header'=>'管理操作',
			'htmlOptions'=>array(
				"width"=>'150px'
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
					'label'=>'<i class="glyph-icon icon-edit"></i>编辑',
					'options'=>array(
						'class'=>'ext-cbtn'
					)
				),
				'config'=>array(
					'label'=>'<i class="glyph-icon icon-cogs"></i>配置',
					'options'=>array(
						'class'=>'ext-cbtn'
					),
					/*'imageUrl'=>Yii::app()->baseUrl.'/static/images/icon/computer_key.png',*/
					'url'=>'Yii::app()->createUrl("/pluginProp/config", array("aid"=>$data->aid))'
				)
			)
		)
	)
));
?>
</div>
