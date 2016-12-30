<div id="page-title">
	<h3>
		操作日志
		<small> >详细查看</small>
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
		'uid', 
		'ghid', 
		'action', 
		'controller', 
		'module', 
		'optime'
	)
));
?>
</div>