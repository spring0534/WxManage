<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#wx-material-grid').yiiGridView('update', {
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
							添加素材
						</span>
					</a>
                    <div class="dropdown-menu float-right qmenu" style="padding-top: 0;">
					<div class="small-box">
                            <div class="primary-bg text-transform-upr font-size-12 font-bold  pad10A">请选择素材类型</div>
                            <div class="pad10A dashboard-buttons clearfix">
                                <a href="<?php U('add/type/text')?>" class="btn vertical-button remove-border bg-blue" title="">
                                    <span class="glyph-icon icon-separator-vertical pad0A medium">
                                        <i class="glyph-icon icon-dashboard opacity-80 font-size-20"></i>
                                    </span>
                                    <span class="button-content">文本</span>
                                </a>
                                <a href="<?php U('add/type/news_1')?>" class="btn vertical-button remove-border bg-red" title="">
                                    <span class="glyph-icon icon-separator-vertical pad0A medium">
                                        <i class="glyph-icon icon-tags opacity-80 font-size-20"></i>
                                    </span>
                                    <span class="button-content">单图文</span>
                                </a>
                                <a href="<?php U('add/type/news_n')?>" class="btn vertical-button remove-border bg-purple" title="">
                                    <span class="glyph-icon icon-separator-vertical pad0A medium">
                                        <i class="glyph-icon icon-reorder opacity-80 font-size-20"></i>
                                    </span>
                                    <span class="button-content">多图文</span>
                                </a>
                                <!--
                                <a href="<?php U('add/type/text')?>" class="btn vertical-button remove-border bg-azure" title="">
                                    <span class="glyph-icon icon-separator-vertical pad0A medium">
                                        <i class="glyph-icon icon-bar-chart opacity-80 font-size-20"></i>
                                    </span>
                                    <span class="button-content">图片</span>
                                </a>
                                 -->

                            </div>
                        </div>
                    </div>
                </div>
	</div>
</div>
<div id="page-content">

	<div style="padding-bottom: 10px">

	<div class="infobox notice-bg">
		<h4 class="infobox-title">温馨提示</h4>
		<p>如果是认证服务号，支持在文本素材中获取用户的基本信息。基本信息包括微信昵称：[nickname] 省份：[province] 城市：[city] 微信Id:[openid]</p>
		<p>在文本中的以上特定字符会被替换为真实的信息</p>


	</div>

	</div>
	<div class="search-form">
<?php

$this->renderPartial('_oneselfsearch', array(
	'model'=>$model
));
?>
</div>
	<!-- search-form -->

<?php

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'wx-material-grid',
	'template'=>'<div class="content-box"> <table class="table table-hover text-left"><tbody>{items} </tbody></table></div><div class="summary" style="float: left;">{summary}</div><div class="pager">{pager}</div>',
	'dataProvider'=>$model->oneselfSearch(),
	'enableHistory'=>true,
	'columns'=>array(
		'id',
		'title',
		array( // display 'create_time' using an expression
			'name'=>'msg_type',
			'value'=>'Yii::app()->params["msg_type"][$data->msg_type]'
		),
		'ctm',
		array( // display 'author.username' using an expression
			'name'=>'status',
			'value'=>'$data->status?"正常":"禁用"'
		),

		// 'operator_uid',
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
