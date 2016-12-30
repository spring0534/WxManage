<div id="page-title">
	<h3>
		菜单管理
		<small> >>添加菜单 </small>
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
	<style type="text/css">
html {
	_overflow-y: scroll
}
</style>
	<div class="common-form">
		<form class="col-md-10 center-margin" method="post" action='<?php echo $this->createUrl('insert');?>' id='login-validation'>
			<div class="form-row">
				<div class="form-label col-md-2">
					<label for=""> 上级菜单: </label>
				</div>
				<div class="form-input col-md-10">
                   <?php
																			if (empty($menuid)){
																				?>
        <select name="info[pid]" class='chosen-select col-md-6 float-left' style='position: relative;'>
						<option value="0">作为一级菜单</option>
           <?php echo $select_categorys; ?>
      	</select>
        	<?php
																			}else{
																				?>
        	<input type="hidden" name="info[pid]" value="<?php echo $menuid; ?>" />
					<strong><?php echo $menuname; ?></strong>
        	<?php
																			}
																			?>
                </div>
			</div>
			<div class="form-row">
				<div class="form-label col-md-2">
					<label for=""> 菜单名称: </label>
				</div>
				<div class="form-input col-md-10">
					<input placeholder="菜单名称" class="col-md-6 float-left" type="text" name="info[title]" id='menuname' data-trigger="change" data-required="true">
				</div>
			</div>
			<div class="form-row">
				<div class="form-label col-md-2">
					<label for=""> 模块名: </label>
				</div>
				<div class="form-input col-md-10">
					<input placeholder="模块名" class="col-md-6 float-left" type="text" name="info[modelname]" id='modelname' data-trigger="change" value="<?php echo $mlist['modelname']; ?>">
				</div>
			</div>
			<div class="form-row">
				<div class="form-label col-md-2">
					<label for=""> 图标: </label>
				</div>
				<div class="form-input col-md-10">
					<input placeholder="图标" class="col-md-6 float-left" type="text" name="info[icon]" id='icon' data-trigger="change">
				</div>
			</div>

			<div class="form-row">
				<div class="form-label col-md-2">
					<label for=""> 是否启用: </label>
				</div>
				<div class="form-input col-md-10">
					<?php echo CHtml::switchButton('info[status]', 1,array('1'=>'是',0=>'否'))?>
					
                
 				</div>
			</div>
			<div class="form-row">
				<div class="form-label col-md-2">
					<label for=""> 是否显示: </label>
				</div>
				<div class="form-input col-md-10">
					<?php echo CHtml::switchButton('info[isshow]', 1,array('1'=>'是',0=>'否'))?>
					
                
 				</div>
			</div>
			
			<div class="form-row">
				<input type="hidden" name="superhidden" id="superhidden">
				<div class="form-input col-md-10 col-md-offset-2">
					<button class="btn primary-bg medium" onclick="javascript:return $('#login-validation').parsley( 'validate' );">
						<span class="button-content">提交</span>
					</button>
				</div>
			</div>
		</form>
		<!--table_form_off-->
	</div>