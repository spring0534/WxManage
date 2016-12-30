
<div id="page-title">
	<h3>
		修改素材
		<small>
      素材类型-><?php echo Yii::app()->params['msg_type'][$model->msg_type];?>
    </small>
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

<?php $this->renderPartial('_form', array('model'=>$model,'actlist'=>$actlist,'content'=>$content)); ?>
</div>
