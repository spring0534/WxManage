
<div id="page-title">
	<h3>
		添加属性
		<small> 为微应用xxx添加属性 </small>
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
		<a href="<?php U('admin')?>" class="btn medium primary-bg btn_back">
			<span class="button-content">
				<i class="glyph-icon icon-mail-reply float-left"></i>
				返回
			</span>
		</a>
	</div>
	<div class="form">
		<form class="col-md-10 center-margin" id="myform" action="<?php U('00'); ?>" method="post">
			<?php foreach ($models as $kk=>$model){?>
			<div class="form-row">
				<div class="form-label col-md-2">
					<label for="">
						<strong><?php echo $model->proptitle;?></strong>
						<?php if($model->required){?><font color="red">*</font><?php };?>
					</label>
				</div>
				<div class="form-input col-md-6">
					<input type="text" name="<?php echo $model->propname?>" size="30" value="<?php echo $model->defaultvalue;?>" data-trigger="change" data-required="true">
					<p><?php echo $model->memo;?></p>
				</div>
			</div>
			<?php }?>

			
			<div class="button-pane text-center">

				<button type="reset" class="btn medium primary-bg text-transform-upr font-size-11" id="demo-form-valid" >
						<span class="button-content"><i class="glyph-icon icon-undo float-left"></i>重置</span>
					</button>
				<button onclick="javascript:return $('#myform').parsley( 'validate' );" type="submit" class="btn large primary-bg text-transform-upr font-size-11" id="demo-form-valid" >
					<span class="button-content"><i class="glyph-icon icon-plus float-left"></i>添加</span>
				</button>
			</div>
		</form>
	</div>
</div>