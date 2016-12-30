
<link rel="stylesheet" type="text/css" href="<?php echo $this->assets(); ?>/css/stat.css">
<?php
Yii::app()->clientScript->registerScript('search_detail', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#stat-data-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div id="page-title">
	<h3>
		统计
		<small> >>访问明细 </small>
	</h3>
</div>
<div id="page-content">
	<div class="search-form">
		<div class="explain-col">
<?php

$form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'htmlOptions'=>array(
		'style'=>'margin: 0'
	)
));
?>


	<label for="StatData_aid">活动</label>:
    <?php echo CHtml::dropDownList(chtmlName($model, 'aid'), $model->aid,CHtml::listData($this->getActList(), 'aid', 'title'),array('empty'=>'全部','style'=>"width:200px;") )?>

	<?php echo $form->label($model,'rtime'); ?>:
	<?php echo calendar(chtmlName($model, 'rtime').'[1]',date('Y-m-d', strtotime("-2 day")), '','180px');?>--
	<?php echo calendar(chtmlName($model, 'rtime').'[2]',date('Y-m-d'), '','180px');?>

	<button class="btn primary-bg medium" style="margin-left: 30px;">
				<span class="button-content">
					<i class="glyph-icon icon-search"></i>
					&nbsp;搜索
				</span>
			</button>
			<div class="dropdown open ">
				<div  class="btn medium primary-bg exp" style="margin-left: 30px;">
					<span class="button-content">
						<i class="glyph-icon icon-download-alt"></i>
						<i class="glyph-icon icon-caret-down float-right"></i>
					</span>
				</div>
				<div class="dropdown-menu pad0A float-right exp_show qmenu" style="display: none">
					<div class="medium-box " style="width: 300px;">
						<div class="bg-gray text-transform-upr font-size-12 font-bold font-gray-dark pad10A" style="background: #2E7FCA; color: #FFF !important;">
							数据导出配置
							<a class="btn small exp_close float-right radius-all-4 ui-state-default tooltip-button" title="" href="#ove" data-original-title="关闭">
								<i class="glyph-icon icon-remove"></i>
							</a>
						</div>

						<div class="pad10A">
							<p class="font-gray-dark pad0B">考虑到性能问题,限制每次导出的条数,配置下面的参数导出你想要的数据.</p><!--
							<div class="divider mrg10T mrg10B"></div>
							<div class="form-row">
								<div class="form-label col-md-4">
									<label for=""> 起始条数: </label>
								</div>
								<div class="form-input col-md-8">
									<input type="text" id="prestart" name="prestart" value=1>
								</div>
							</div>
							<div class="form-row">
								<div class="form-label col-md-4">
									<label for=""> 结束条数:</label>
								</div>
								<div class="form-input col-md-8">
									<input type="text" id="endstart" name="endstart" value="1000">
								</div>
							</div>
							 -->

							<div class="btn primary-bg medium check_exp" style="margin-left: 85px; margin-bottom: 10px;cursor: pointer;">
								<span class="button-content">
									<i class="glyph-icon icon-download-alt"></i>
									确认导出
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
<?php $this->endWidget(); ?>

</div>
		<!-- search-form -->
	</div>
	<div class="row"></div>
	<!-- search-form -->

<?php

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'stat-data-grid',
	'template'=>'<div class="content-box">
		<table class="table table-hover text-center">
			<tbody>{items}
			</tbody>
		</table>
	</div>
	<div class="summary" style="float: left;">{summary}</div>
	<div class="pager">{pager}</div>',
	'dataProvider'=>$model->search_detail(),
	/*'filter'=>$model,*/
	'columns'=>array(
		array(
			'name'=>'头像',
			'value'=>'CHtml::image($data->wxuser->headimgurl?$data->wxuser->headimgurl:__PUBLIC__."/images/gravatar3.jpg","",
					array(
					"height"=>150,
				    "style"=>"cursor: -webkit-zoom-in;",
					"class"=>"medium radius-all-2",
				    "onclick"=>"image_priview($(this).attr(\"src\"))"
					)
				)',  // 这里显示图片
			'type'=>'raw',  // 这里是原型输出
			'htmlOptions'=>array(
				'width'=>'50',
				'style'=>'text-align:center'
			)
		),
		array('name'=>'微信昵称',
			'value'=>'$data->wxuser->nickname',
			'htmlOptions'=>array(
				'width'=>'100',
				'style'=>'text-align:center'
			)),
		'title',
		    /*'id' => 'ID',*/
			/*'aid' ,*/
			/*'wxid' ,*/
			//'fromWxid',
			/*'ghid',*/
			/*'cid',*/
			/*'tid',*/

			'url' ,
			/*'ip' ,*/
			/*'pv', */
		'screen',

		// 'referrer',
		// 'brv' ,
		'brvsub',
			/*'lg' ,*/
			'netType',
		'os',
		'osv',
		array(
			'name'=>'mobile',
			'value'=>'CHtml::link($data->mobile,"http://www.baidu.com/baidu?word=$data->mobile&ie=utf-8",
				array(
				"target"=>"_blank",
				"style"=>"text-decoration: underline;"
				)
			)',  // 这里显示图片
			'type'=>'raw',  // 这里是原型输出
			'htmlOptions'=>array(
				/*'width'=>'200',*/
				'style'=>'text-align:center'
			)
		),
			/*'mobileName',*/
			/*'srcType',
			'logType',
			'shareType' ,*/
			/*'shareUrl',*/
			/*'country',*/
			/*'region',*/
			'city',
			/*'area',*/
			'isp',
		'rtime',
			/*'ua' => 'Ua',
			'realUrl'=>'真实URL',*/
		array(
			'class'=>'CButtonColumn',
			'template'=>'{detail}',
			'header'=>'操作',
			'htmlOptions'=>array(
				"width"=>'40px'
			),
			'buttons'=>array(
				'detail'=>array(
					'url'=>'Yii::app()->createUrl("/statData/view", array("id"=>"$data->id"))',
					'label'=>'<i class="glyph-icon icon-search"></i>详情',
					'options'=>array(
						'class'=>'ext-cbtn delete tooltip-button',
						'title'=>'',
						'data-original-title'=>'详情'
					)
				)
			)
		)
	)
));
?>
</div>
<script>
$('.exp').click(function(){
	if ($('.exp_show').is(':hidden')) $('.exp_show').show(); else $('.exp_show').hide();
});
$(document).on("click",".exp_close",function(){
	if ($('.exp_show').is(':hidden')) $('.exp_show').show(); else $('.exp_show').hide();
});
$('.check_exp').click(function(){
	var ps=$('#prestart').val();
	var es=$('#endstart').val();
	//if(!ps||!es){alert('请填写正确的数值');}
	//if(ps>=es){alert('请填写正确的数值');}
	var p='pl='+ps+'&pr='+es+'&';
	window.open($('.keys').attr('title').indexOf("?")!=-1?($('.keys').attr('title')+"&export=yes&"+p+$('.search-form form').serialize()):($('.keys').attr('title')+"?export=yes&"+p+$('.search-form form').serialize()));
	if ($('.exp_show').is(':hidden')) $('.exp_show').show(); else $('.exp_show').hide();
});
</script>
