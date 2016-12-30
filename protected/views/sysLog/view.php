<div id="page-title">
	<h3>
		错误日志
		<small> >>详细查看</small>
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
		<a href="<?php U('admin');?>" class="btn medium primary-bg">
			<span class="button-content">
				<i class="glyph-icon icon-mail-reply-all float-left"></i>
				返回
			</span>
		</a>
	</div>

<?php

$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model, 
	'attributes'=>array(
		'id', 
		'level', 
		'category', 
		'ghid', 
		array(
			'label'=>'message', 
			'value'=>msubstr($model->message, 0, 20, true)
		), 
		'ip', 
		array(
			'label'=>'logtime', 
			'value'=>date("Y-m-d H:i:s", $model->logtime)
		)
		, 
		'request_url', 
		'ip', 
		'message'
	)
));
?>
</div>