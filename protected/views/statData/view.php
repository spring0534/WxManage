<div id="page-title">
	<h3>
		内容
		<small> >>详细内容 </small>
	</h3>
</div>
<div id="page-content">
	<div style="padding-bottom: 10px">
		<a href="<?php echo $this->createUrl('/statReport/detail');?>" class="btn medium primary-bg ">
			<span class="button-content">
				<i class="glyph-icon icon-reply float-left "></i>
				返回
			</span>
		</a>
	</div>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'微信头像',
				'value'=>CHtml::image($model->wxuser->headimgurl?$model->wxuser->headimgurl:__PUBLIC__.'/images/gravatar3.jpg',"",
					array(
					"height"=>250,
				    "style"=>"cursor: -webkit-zoom-in;width: 150px;height: 150px;",
					"class"=>"medium radius-all-2",
				    "onclick"=>"image_priview($(this).attr(\"src\"))"
					)
				),  // 这里显示图片
				'type'=>'raw',  // 这里是原型输出
				'htmlOptions'=>array(
					'width'=>'280',
					'style'=>'text-align:center'
				)
			),
			array('name'=>'微信昵称','value'=>$model->wxuser->nickname),
			array('name'=>'登记姓名','value'=>$model->wxuserReg->username),
			array('name'=>'登记手机号','value'=>$model->wxuserReg->phone),
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
			/*'pv' ,*/
			'screen',
			//'referrer',
			//'brv' ,
			'brvsub',
			/*'lg' ,*/
			'netType',
			'os' ,
			'osv' ,
			array(
			'name'=>'mobile',
			'value'=>CHtml::link($model->mobile,"http://www.baidu.com/baidu?word=".$model->mobile."&ie=utf-8",
				array(
				"target"=>"_blank",
				"style"=>"text-decoration: underline;"
				)
			),
			'type'=>'raw',
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
			'region',
			'city',
			'area',
			'isp',
			'rtime',
			/*'ua' => 'Ua',
			'realUrl'=>'真实URL',*/
	),
)); ?>

<style>
table.detail-view tr.odd {
background: #f5f7f9;
border-left: 1px #FFF solid;
}
table.detail-view tr {
background: #FFFFFF!important;
border-left: 1px #FFF solid;
height: 28px;
line-height: 28px;
}
table.detail-view tr:hover {
background: #EDF4FA!important;
border-left: 1px #FFF solid;
height: 28px;
line-height: 28px;
}
table.detail-view th, table.detail-view td {
font-size: 0.9em;
border: 1px rgb(221, 221, 221) solid;
padding: 0.3em 0.6em;
vertical-align: top;
}
</style>




