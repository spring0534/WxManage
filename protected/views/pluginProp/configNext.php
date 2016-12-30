
<div id="page-title">
	<h3>
		活动配置
		<small> >>活动配置 </small>
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
		<a href="<?php echo Yii::app()->createUrl('activity/admin');?>" class="btn medium primary-bg btn_back">
			<span class="button-content">
				<i class="glyph-icon icon-mail-reply float-left"></i>
				返回
			</span>
		</a>
	</div>
	<div class="form-wizard">
		<ul class="anchor">
			<li>
				<a href="#step-1" class=" disabled" isdone="1" rel="1">
					<label class="wizard-step">1</label>
					<span class="wizard-description">
						1、添加活动
						<small>填写创建活动的相关配置</small>
					</span>
				</a>
			</li>
			<li>
				<a href="#step-2" class="selected" isdone="1" rel="2">
					<label class="wizard-step">2</label>
					<span class="wizard-description">
						2、配置活动
						<small>为创建的活动配置自定义属性</small>
					</span>
				</a>
			</li>
		</ul>
		<div class="stepContainer" style="height: 297px;">
			<div id="step-1" class="content">
			<div class="form">
		<form class="col-md-20 center-margin" id="myform" action="<?php U('configSave'); ?>" method="post">


		<div class="tabs ui-tabs ui-widget ui-widget-content ui-corner-all">
            <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">

                <li onclick="dclose()" class="ui-state-default ui-corner-top ui-tabs-active ui-state-active" role="tab" tabindex="0" aria-controls="icon-only-tabs-1" aria-labelledby="ui-id-7" aria-selected="true">
                    <a href="#icon-only-tabs-1" title="Tab 1" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-7">
                        <i class="glyph-icon icon-archive float-left opacity-80"></i>
                        默认
                    </a>
                </li>

                <?php
                if(!empty($pgroup)){
                	$index=1;
                	foreach ($pgroup as $kkk=>$vvv){
                		$index++;
                		?>
                		 <li onclick="showPreview('<?php echo $vvv->img;?>')" class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="icon-only-tabs-2" aria-labelledby="ui-id-8" aria-selected="false">
		                    <a href="#icon-only-tabs-<?php echo $index;?>" title="<?php echo $vvv->name;?>" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-8">
		                         <i class="glyph-icon icon-archive float-left opacity-80"></i>
		                        <?php echo $vvv->name;?>
		                    </a>
		                </li>

                		<?php
                	}
                }
                ?>

            </ul>
            <div id="icon-only-tabs-1" aria-labelledby="ui-id-7" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-expanded="true" aria-hidden="false" style="display: block;">
               <div class="form-row">
				<div class="form-label col-md-3">
					<label for="">
						<strong>资源包:</strong>

					</label>
				</div>
				<div class="form-input col-md-6">
					<?php if($isres){?>
						<input data-trigger="change" type="text" class="font-blue" size="" value="正在使用" disabled="disabled" class="input-text">
					<?php }else{?>

						<input data-trigger="change" type="text" class="font-red"  size="" value="未使用" disabled="disabled" class="input-text">
					<?php }?>
					<p>
					如果要使用资源包，请从右边的按钮进行相关操作
					</p>
				</div>
			</div>
		<?php echo CHtml::hiddenField('aid',$aid);?>
		<?php echo CHtml::hiddenField('ptype',$act->type);?>
		<div class="form-row">
				<div class="form-label col-md-3">
					<label for="">
						<strong>主题模板:</strong>
					</label>
				</div>
				<div class="form-input col-md-6">
						<?php echo CHtml::dropDownList('themeid', $act->themeid, CHtml::listData(PluginTheme::model()->findAllByAttributes(array('ptype'=>$act->type)), 'id', 'name'),array('empty'=>'请选择模板'))?>
					<p>
					如果没有,默认使用default主题
					</p>
				</div>
			</div>
			<?php
			if($list[0]){
			foreach ($list[0][1] as $kk=>$model){?>
			<div class="form-row">
				<div class="form-label col-md-3">
					<label for="">
						<strong><?php echo $model[name];?>:</strong>
						<?php if($model->required){?><font color="red">*</font><?php };?>
					</label>
				</div>
				<div class="form-input col-md-6">
					<?php echo $model[input];?>
					<p>
					<?php echo $model[memo];?>
					</p>
				</div>
			</div>
			<?php }

			}?>
            </div>
            <?php
                if(!empty($pgroup)){
                	$index2=1;
                	foreach ($pgroup as $kkk=>$vvv){
                		$index2++;
                		?>
                		<div id="icon-only-tabs-<?php echo $index2;?>" aria-labelledby="ui-id-8" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-expanded="false" aria-hidden="true" style="display: none;">

							<?php
							if($list[$vvv->id][1]){
							foreach ($list[$vvv->id][1] as $kk=>$model){?>
							<div class="form-row">
								<div class="form-label col-md-3">
									<label for="">
										<strong><?php echo $model[name];?>:</strong>
										<?php if($model->required){?><font color="red">*</font><?php };?>
									</label>
								</div>
								<div class="form-input col-md-6">
									<?php echo $model[input];?>
									<p>
									<?php echo $model[memo];?>
									</p>
								</div>
							</div>
							<?php }

							}else{
								echo '无相关配置';
							}
							?>
			            </div>
                		<?php
                	}
                }
                ?>

        </div>

			
						<div class="button-pane text-center">
							<button onclick="javascript:return $('#myform').parsley( 'validate' );" type="submit" class="btn large primary-bg text-transform-upr font-size-11" id="demo-form-valid">
								<span class="button-content"><i class="glyph-icon icon-check float-left"></i>提交</span>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>