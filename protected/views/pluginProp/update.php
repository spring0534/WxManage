
<div id="page-title">
	<h3>
		微应用属性
		<small> >>编辑属性 </small>
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
		<a href="<?php echo $this->createUrl('PluginProp/admin/ptype/'.$plugin->ptype)?>" class="btn medium primary-bg btn_back">
			<span class="button-content">
				<i class="glyph-icon icon-mail-reply float-left"></i>
				返回
			</span>
		</a>
	</div>

<?php

$this->renderPartial('_form', array(
	'model'=>$model,
	'diyfield'=>$diyfield,
	'regxarr'=>$regxarr,
	'setting'=>$setting,
	'plugin'=>$plugin
));
?>
</div>
