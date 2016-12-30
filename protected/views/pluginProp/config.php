
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
		<a href="<?php echo Yii::app()->createUrl('activity/admin');?>" class="btn medium primary-bg">
			<span class="button-content">
				<i class="glyph-icon icon-mail-reply float-left"></i>
				返回
			</span>
		</a>
		<a href="<?php echo Yii::app()->createUrl('activity/updateCache',array('aid'=>$aid));?>"  class="btn medium primary-bg">
			<span class="button-content">
				<i class="glyph-icon icon-refresh float-left"></i>
				更新配置缓存
			</span>
		</a>
		<!--
		<a href="<?php echo Yii::app()->createUrl('activity/admin');?>" class="btn medium primary-bg  float-right" >
			<span class="button-content">
				<i class="glyph-icon icon-upload float-left"></i>
				素材资源批量上传
			</span>
		</a>
		-->

		<div class="dropdown btn medium primary-bg float-right " style="margin-right: 30px;">
				<div  class="btn medium primary-bg exp " style="margin-left: 10px;cursor: hand;" data-toggle="dropdown" >
					<span class="button-content " >
						<i class="glyph-icon icon-folder-close"></i> <span >资源包管理</span>
						<i class="glyph-icon icon-chevron-down"></i>
					</span>
				</div>

                    <ul class="dropdown-menu float-right qmenu" style="padding: 0">
                    <div class="bg-gray text-transform-upr font-size-10 font-bold font-gray-dark pad10A" style="background: #2E7FCA;color: #FFF!important;">操作菜单</div>
                         <li>
                            <a href='<?php echo Yii::app()->createUrl('activity/dirlist',array('aid'=>$aid));?>'  >
                                <i class="glyph-icon icon-gear mrg5R"></i>资源包管理
                             </a>
                         </li>
                         <li>
                            <a href='javascript:;' id="upzip" data-aid="<?php echo $aid;?>" >
                                <i class="glyph-icon icon-upload mrg5R"></i>资源包上传
                             </a>
                         </li>
                         <li>
                            <a href='<?php echo Yii::app()->createUrl('activity/zipDel',array('aid'=>$aid));?>' >
                                <i class="glyph-icon icon-remove-circle mrg5R"></i>删除资源包
                            </a>
                         </li>
						 <li>
                            <a href='<?php echo Yii::app()->createUrl('activity/exportZip',array('aid'=>$aid));?>' >
                                <i class="glyph-icon icon-download mrg5R"></i>原始资源包下载
                            </a>
                         </li>

                    </ul>
          </div>

	</div>
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
				<button  type="reset" class="btn large  text-transform-upr font-size-11" id="demo-form-valid" >
					<span class="button-content">重置</span>
				</button>

				<button onclick="javascript:return $('#myform').parsley( 'validate' );" type="submit" class="btn large primary-bg text-transform-upr font-size-11" id="demo-form-valid" >
					<span class="button-content"><i class="glyph-icon icon-check float-left"></i>提交</span>
				</button>
			</div>
		</form>
	</div>
</div>
<script>
$(document).ready(function(){
    ajax_up('#upzip', zipcallback, 'uploadzip', '0',$('#upzip').attr('data-aid'));
    function zipcallback(res, obj){
        alert('资源包上传成功！','succeed');
    }

});
var dialog_pr;
function showPreview(url){
    Wind.use("artDialog",function(){
        dialog_pr=top.art.dialog({
            title: '预览',
            fixed: true,
			padding:0,
			top: '50%',
			width:380,
			height:640,
			left:'90%',
            id:"image_priview_config",
			content: '<div style="padding:30px"><img src="/public/static/images/loader-dark.gif" style="border:0px;width:35px;height:35px;vertical-align: middle;"> 加载中...</div>'
        });
		dialog_pr.content('<div class="priview_img"><img src="'+url+'" style="width:380px;height:640px;"/></div>');

    });
}
function dclose(){
	if(dialog_pr){
		dialog_pr.close();
	}
}
</script>