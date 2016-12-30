<div id="page-title" style="margin:0;">
	<h3>
		自动回复管理
		<small> &gt;&gt;管理 </small>
	</h3>
	<div id="breadcrumb-right">
		                <div class="dropdown dash-menu">

					<a href="javascript:;" data-url="<?php U('create')?>" class="btn large primary-bg p-add">
			<span class="button-content">
				<i class="glyph-icon icon-plus float-left"></i>
				添加主菜单
			</span>
		</a>
		<a href="javascript:;" class="btn large primary-bg btn-order">
			<span class="button-content">
				<i class="glyph-icon icon-save float-left"></i>
				保存排序
			</span>
		</a>
		<a href="javascript:;" class="btn large primary-bg btn-save">
			<span class="button-content">
				<i class="glyph-icon icon-cloud-upload float-left btn-create"></i>
				生成微信菜单
			</span>
		</a>
<!--
		<a href="javascript:;" class="btn medium primary-bg">
			<span class="button-content">
				<i class="glyph-icon icon-remove float-left btn-create"></i>
				撤消微信菜单
			</span>
		</a>
		<a href="javascript:;" class="btn medium primary-bg">
			<span class="button-content">
				<i class="glyph-icon icon-cloud-download float-left btn-create"></i>
				远程拉取
			</span>
		</a>
		 -->
                </div>
	</div>
</div>
<div id="page-content">


<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/jquery.dragsort-0.4.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->assets(); ?>/mobile/base.css">
<br>
<div id="page-content">
	<style type="text/css">
.icon-op {
	color: #525899;
	line-height: 20px;
	padding: 0 4px;
}
</style>
	<div class="infobox notice-bg">
		<h4 class="infobox-title">温馨提示</h4>
		<p>注意：1级菜单最多只能开启3个，2级子菜单最多开启5个!</p>
		<p>生成自定义菜单前,如果有拖动排序，请先保存再生成 !</p>
		<p>当您为自定义菜单填写链接地址时请填写以"http://"开头，这样可以保证用户手机浏览的兼容性更好</p>
		<p>撤销自定义菜单：撤销后，您的微信公众帐号上的自定义菜单将不存在；如果您想继续在微信公众帐号上使用自定义菜单，请点击“生成自定义菜单”按钮，将重新启用！</p>
	</div>
	<form action="" class='menu-form col-md-6'>
		<ul class="dragsort-ver  ui-sortable">

  <?php
		if (empty($dataProvider)){
			echo '没有添加任何菜单！';
		}else{
			foreach ($dataProvider as $k=>$v){
				?>
  	 <li class="  pad5A pad10L pad10R mrg5T mrg5B m-pid">
				<ol class=" pad5A pad10L pad10R mrg5T mrg5B bg-blue-alt">
					<i class="glyph-icon icon-tasks" style="padding-right: 8px;"></i>
					<label class='title'><?php echo $v['name'];?></label>
					<input name="listorders[<?php echo $v['id'];?>]" type="hidden" size="3" value="<?php echo $v['seq'];?>" class="input-text-c input-text">
					<a href="<?php U('delete/id/'.$v['id'])?>" class="btn   float-right ui-icon tooltip-button m-del" data-original-title="删除" onclick="return confirm('确定要删除此一级菜单吗?将会连同下面的了菜单一起删除!');" data-id="<?php echo $v['id']; ?>" data-pid='2'>
						<i class="glyph-icon  icon-remove float-left ui-button icon-op"></i>
					</a>
					<a href="<?php U('update/id/'.$v['id'])?>" class="btn   float-right ui-icon tooltip-button m-edit" data-original-title="编辑" data-id="<?php echo $v['id']; ?>">
						<i class="glyph-icon icon-cog icon-edit float-left ui-button icon-op"></i>
					</a>
					<a href="javascript:;" data-url="<?php U('create/pid/'.$v['id'])?>" class="btn   float-right ui-icon tooltip-button m-add" data-id="<?php echo $v['id']; ?>" data-original-title="添加子菜单">
						<i class="glyph-icon icon-cog icon-plus float-left ui-button icon-op"></i>
					</a>
				</ol>
        <?php if(!empty($v['sublist'])){?>
        	  <ul class="dragsort-ver  ui-sortable m-nid" style="padding-left: 50px;">
        	  <?php foreach ($v['sublist'] as $kk=>$vv){?>
	        	 		<li class="  pad5A pad10L pad10R mrg5T mrg5B bg-gray m-cid">
						<i class="glyph-icon icon-reorder" style="padding-right: 8px;"></i>
						<label class='title'><?php echo $vv['name'];?></label>
						<input name="listorders[<?php echo $vv['id'];?>]" type="hidden" size="3" value="<?php echo $vv['seq'];?>" class="input-text-c input-text">
						<a href="<?php U('delete/id/'.$vv['id'])?>" class="btn   float-right ui-icon tooltip-button m-del" onclick="return confirm('确定要删除此菜单吗?');" data-original-title="删除" data-id="<?php echo $vv['id']; ?>">
							<i class="glyph-icon  icon-remove float-left ui-button icon-op"></i>
						</a>
						<a href="<?php U('update/id/'.$vv['id'])?>" class="btn   float-right ui-icon tooltip-button m-edit" data-original-title="编辑" data-id="<?php echo $vv['id']; ?>">
							<i class="glyph-icon icon-cog icon-edit float-left ui-button icon-op"></i>
						</a>
					</li>

        	  <?php }?>
        	  </ul>
        <?php }?>
	</li>
  <?php }}?>
</ul>
	</form>
	<!--
	<div class="menu-form col-md-6">
		<div id="mapContent" class="clearfix" style="width: 1134px;">
			<div id="code_open" onclick="toggleFooter();" style="display: none;"></div>
			<div class="map_container">
				<div class="map-container-phone">
					<div class="map-container-phone-inner" style="position: relative;"></div>
				</div>
			</div>
		</div>
	</div>
	 -->
</div>
<script type="text/javascript">
		$("ul").dragsort({
			dragEnd: saveOrder,
			scrollSpeed: 5,
			dragSelectorExclude:'input,textarea,span,i,span,i'
			});

		function saveOrder() {
			$(".dragsort-ver li").each(function(){
				$(this).find('input').eq(0).val($(this).index());
				$(this).index();
			});

		}
		$('.p-add').click(function(){
			if($('.m-pid').length>=3){
				var html;
				 $('.radius-all-4').each(function(){

					  html+='<div class="dropup mrg15R " style="position: absolute;bottom: 0;"><a href="javascript:;" class="btn medium primary-bg" title="" data-toggle="dropdown">                      <span class="button-content text-center float-none font-size-11 text-transform-upr"><i class="glyph-icon icon-caret-top float-left"></i>      菜单1                      </span>                  </a>                  <ul class="dropdown-menu">                      <li>                         <a href="javascript:;" title="">                              Nav link 1                          </a>                      </li>      </ul>              </div>';
					  $('.map-container-phone-inner').html( html);
				});

				 alert('一级菜单只允许三个,您不能再添加一级菜单');
				 return;
			 }
			 location.href=$(this).attr('data-url');
		});
		$('.m-add').click(function(){
			 if($(this).closest('ol').next('ul').find('li').length>=5){
				  alert('此菜单下的子菜单最多只能开启5个!');
				  return;
			 };
			 location.href=$(this).attr('data-url');
		});
		$('.btn-order').click(function(){
			$.post("<?php U('listorder')?>",$('.menu-form').serialize(),function(msg){
					alert('保存成功','succeed');
			},"json");

		});
		$('.btn-save').click(function(){
			$.get("<?php U('upload')?>",function(msg){
					if(msg.code=='err'){
						alert(msg.msg);
					}else{
						alert('生成成功','succeed');
					}

			},"json");

		});

		/*setInterval(function(){
			var html='';
			alert($('li').length);
			 $('.radius-all-4').each(function(){
				  alert('8888888888888');
				  html+='<div class="dropup mrg15R " style="position: absolute;bottom: 0;"><a href="javascript:;" class="btn medium primary-bg" title="" data-toggle="dropdown">                      <span class="button-content text-center float-none font-size-11 text-transform-upr"><i class="glyph-icon icon-caret-top float-left"></i>      菜单1                      </span>                  </a>                  <ul class="dropdown-menu">                      <li>                         <a href="javascript:;" title="">                              Nav link 1                          </a>                      </li>      </ul>              </div>';

			});
			  $('.map-container-phone-inner').html( html);
		},3000);
		*/

		//prepend
		</script>
</div>
