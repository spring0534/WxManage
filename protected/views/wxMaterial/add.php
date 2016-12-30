
<div id="page-title">
	<h3>
		添加素材
		<small> >> 请选择添加不同的素材类型 </small>
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

<?php $this->renderPartial('_'.$tpl, array('model'=>$model,'actlist'=>$actlist)); ?>
</div>
