<?php
//在开始加载
Yii::app()->clientScript->coreScriptPosition = CClientScript::POS_BEGIN;
//这些不加载
Yii::app()->clientScript->scriptMap=array('jquery.js'=>false,'jquery.min.js'=>false);
?>
<html><head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo CHtml::encode(Yii::app()->params['title']); ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <!-- Favicons -->
		<style type="text/css">
			html{_overflow-y:scroll}
		</style>
        <!--[if lt IE 9]>
          <script src="<?php echo $this->assets(); ?>/js/minified/core/html5shiv.min.js"></script>
          <script src="<?php echo $this->assets(); ?>/js/minified/core/respond.min.js"></script>
        <![endif]-->
		<script language="JavaScript">
		<!--
			if(top!=self)
			if(self!=top) top.location=self.location;
		//-->
		</script>
        <!-- Fides Admin CSS Core -->
         <link rel="stylesheet" type="text/css" href="<?php echo $this->assets(); ?>/css/minified/admin-all.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $this->assets(); ?>/css/minified/aui-production.min.css">
        <!-- Theme UI -->
        <link id="layout-theme" rel="stylesheet" type="text/css" href="<?php echo $this->assets(); ?>/themes/minified/fides/color-schemes/dark-blue.min.css">
        <!-- Fides Admin Responsive -->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->assets(); ?>/themes/minified/fides/common.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $this->assets(); ?>/themes/minified/fides/responsive.min.css">
        <!-- Fides Admin JS -->
		<script type="text/javascript">var WEB_URL='<?php echo WEB_URL;?>',statics='<?php echo __PUBLIC__;?>';</script>
        <script type="text/javascript" src="<?php echo $this->assets(); ?>/js/minified/aui-production.js"></script>
        <script type="text/javascript" src="<?php echo $this->assets(); ?>/js/minified/screenfull.js"></script>
 		<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/wind.js?k=1"></script>
        <script type="text/javascript" src="<?php echo $this->assets(); ?>/js/artDialog.js?skin=default_2"></script>
        <script type="text/javascript" src="<?php echo $this->assets(); ?>/js/artiframeTools.js?skin=default_2"></script>
        <script>
            jQuery(window).load(
                function(){
                    var wait_loading = window.setTimeout( function(){     $('#loading').slideUp('fast');},1000 );
                });

        </script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"><style type="text/css">.jqstooltip { position: absolute;left: 0px;top: 0px;visibility: hidden;background: rgb(0, 0, 0) transparent;background-color: rgba(0,0,0,0.6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";color: white;font: 10px arial, san serif;text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;z-index: 10000;}.jqsfield { color: white;font: 10px arial, san serif;text-align: left;}</style><style type="text/css">.lb_translate_span_6142570132:hover{background:#f1f0a0 !important;}.lb_translate_span_6142570132{display:inline !important;width:auto !important;margin:0 !important;padding:0 !important;font-family:inherit !important;font-size:inherit !important;line-height:inherit !important;float:none !important;text-transform:initial !important;color:inherit !important;font-weight:inherit !important;cursor:inherit !important;}</style></head>
    <body  scroll='no' style='overflow:hidden' >
        <div id="loading" class="ui-front loader ui-widget-overlay bg-white opacity-100" style="">
            <img src="<?php echo $this->assets(); ?>/images/loader-dark.gif" alt="">
        </div>
        <div id="page-wrapper" class="main_page">
            <div id="page-header" class="clearfix">
               <div id="header-logo">
				<a href="javascript:;" class="tooltip-button" data-placement="bottom" title="" id="close-sidebar" data-original-title="收起左侧菜单">
					<i class="glyph-icon icon-angle-left"></i>
				</a>
				<a href="javascript:;" class="tooltip-button hidden" data-placement="bottom" title="" id="rm-close-sidebar" data-original-title="开启左侧菜单">
					<i class="glyph-icon icon-caret-right"></i>
				</a>
				<a href="javascript:;" class="tooltip-button hidden" title="" id="responsive-open-menu" data-original-title="导航">
					<i class="glyph-icon icon-align-justify"></i>
				</a>
				<div >
					<lable style="width: 153px;  overflow: hidden;  text-overflow: ellipsis; height: 20px;float: left;line-height: -68px;margin-top: 15px;" >
					<nobr><i class="glyph-icon icon-reorder"></i> 微营OS后台管理</nobr>
					</label>

				</div>
				<br>
				<i class="opacity-80"></i>
			</div>
<?php
	foreach ($this->getTopMenu() as $kkk=>$vv){
		$top_index1++;
?>
	<div class="user-profile dropdown mtop_menu <?php if($top_index1==1){echo 'user-profile-on';}?>"   data-id="<?php echo $vv['id'];?>" >
        <a href="javascript:;" title="" class="user-ico clearfix" data-toggle="dropdown">
            <span class="userinfo"><?php echo $vv['title'];?></span>
        </a>
    </div>
 <?php }?>

<div class="top-icon-bar">
<div class="dropdown new_dropdown" ><a  href="javascript:;"  class="tooltip-button fullscreen-btn" data-placement="bottom" title="" id="close-sidebar" data-original-title="全屏"  > <i class="glyph-icon icon-fullscreen"></i>  </a>    </div>
<!-- <div class="dropdown new_dropdown" ><a  href='#'  class="tooltip-button" data-placement="bottom" title="" id="close-sidebar" data-original-title="使用帮助">  <i class="glyph-icon icon-question"></i>   </a>   </div> -->
<div class="dropdown new_dropdown" ><a  href='javascript:;_MP(0,"<?php echo $this->createUrl('baseinfo/index');?>")'  class="tooltip-button" data-placement="bottom" title="" id="close-sidebar" data-original-title="修改资料">  <i class="glyph-icon icon-user-md"></i>   </a>   </div>
<?php
                        if(user()->ghid){
                        	?>
                        <div class="dropdown new_dropdown" ><a  href='javascript:;_MP(0,"<?php echo $this->createUrl('/sysUserGh/switch/ghid/'.user()->ghid);?>")'  class="tooltip-button" data-placement="bottom" title="" id="close-sidebar" data-original-title="切换回自己的公众号">  <i class="glyph-icon icon-exchange"></i>   </a>   </div>

                        	<?php
                        }
                        ?>
<div class="dropdown new_dropdown" > <a  href="<?php echo $this->createUrl('login/logout');?>"  class="tooltip-button" data-placement="bottom" title="" id="close-sidebar" data-original-title="退出">       <i class="glyph-icon icon-signout"></i> </a>  </div>


                </div>
<?php if(!empty(gh()->ghid)){?>
 				<div class="user-profile dropdown" style="  float: right;  margin-right: 4px;">
                    <a href="javascript:;" title="" class="user-ico clearfix" data-toggle="dropdown" style="background-image:none">
                        <img width="36" src="<?php echo $this->assets(); ?>/images/wx.png" alt="<?php echo gh()->name;?>">
                        <span class="userinfo"><?php echo gh()->name;?></span>
                        <i class="glyph-icon icon-chevron-down float-right " style="margin-left: 10px;background-color: rgba(255,255,255,.1);    color: #fff;padding: 8px;margin-top: 8px;line-height: 14px;border-radius: 3px;"></i>
                    </a>

                </div>
                <?php   }?>
            </div><!-- #page-header -->

            <div id="page-sidebar" class="scrollable-content" style="overflow: hidden; outline: none; height: 925px;" tabindex="5003">
            <div style="display: inline-block;display:none" >
					<img width="60" src="<?php if(!empty(user()->headimg)){ echo user()->headimg;}else{ echo $this->assets()."/images/gravatar.jpg";} ?>" style="padding-right: 4px; border-radius: 180px; float: left; margin-top: 8px;margin-left: 15px;">
					<label style=" text-indent: 10px;    width: 50%;    margin-top: 20px;    margin-bottom: 8px;"><?php echo user()->nickname;?>    </label>
<nobr>

                                    <a href='javascript:_MP(0,"<?php echo $this->createUrl('baseinfo/index');?>")' class="btn small hover-blue-alt tooltip-button" data-placement="top" title="" data-original-title="信息修改">
                                        <i class="glyph-icon icon-edit"></i>
                                    </a>
                                    <a href='javascript:_MP(0,"<?php echo $this->createUrl('baseinfo/modifypwd');?>")' class="btn small hover-yellow tooltip-button" data-placement="top" title="" data-original-title="密码修改">
                                        <i class="glyph-icon icon-key"></i>
                                    </a>
                                    <a href="<?php echo $this->createUrl('login/logout');?>" class="btn small hover-red tooltip-button" data-placement="top" title="" data-original-title="退出">

                                        <i class="glyph-icon icon-off"></i>
                                    </a>
</nobr>

				</div>


<ul id="sidebar-menu" class="sf-js-enabled sf-arrows">

<?php
	foreach ($this->getTopMenu() as $kkk=>$vv){
	$top_index++;
?>
<div class="ltop_menu" id="top_<?php echo $vv['id'];?>" style="<?php if($top_index!=1){echo 'display:none';}?>">
					<li class="header"><span><?php echo $vv['title'];?></span></li>
					<?php
					$leftmenu=$this->leftmenu();
					foreach ($leftmenu[$kkk] as $k=>$list){
								    	$i++;
					?>
				 	<?php if(empty($list['sublist'])){?>

				 	<li id="_MP<?php echo $list['id']+200;?>" <?php if(Yii::app()->controller->id=='default'&&$i==1) echo 'class="current-page"' ;?> >
				        <a href="javascript:;/*当前地址：<?php echo $this->createUrl('/'.$list['modelname']);?> */"   onclick=_MP(<?php echo $list['id']+200;?>,"<?php echo $this->createUrl("/".$list['modelname']);?>")>
				            <i class="glyph-icon <?php echo $list['icon'];?>"></i>
				            <span><?php echo $list['title'];?></span>
				        </a>
    				</li>
				 	<?php }else{?>
				 	    <li>
					        <a href="javascript:;" title="Elements" class="sf-with-ul">
					            <i class="glyph-icon <?php echo $list['icon'];?>"></i>
					            <span><?php echo $list['title'];?></span>
					        </a>
					        <div class="sidebar-submenu" style="display: none;">

					            <ul>
					            <?php
						   			 foreach ($list['sublist'] as $kk=>$mmss){
						    			$ii++;
						    		?>
						    		<li id="_MP<?php echo $mmss['id'];?>">
						               <a href="javascript:; /*当前地址：  <?php echo $this->createUrl('/'.$mmss[modelname]);?> */" onclick=_MP(<?php echo $mmss['id'];?>,"<?php echo $this->createUrl('/'.$mmss[modelname]);?>") hidefocus="true" style="outline:none;">
						                  <span><?php echo $mmss['title'];?></span>
						               </a>
						            </li>

						    		<?php
						   			 }
						   			 ?>

					            </ul>

					        </div><!-- .sidebar-submenu -->
   						 </li>

				 <?php }?>
 <?php }?>
 </div>
	<?php
	}
	?>
                </div>

            </div><!-- #page-sidebar -->
            <div id="page-content-wrapper">
            <div class="content" style="position:relative; overflow:hidden">
                <iframe name="right" id="rightMain" src="<?php echo $this->createUrl('/index')?>" frameborder="false" scrolling="auto" style="border: none; height: 463px; " width="100%" height="auto" allowtransparency="true"></iframe>
            </div>
            </div><!-- #page-main -->
        </div><!-- #page-wrapper -->
        <script>
				$('.mtop_menu').click(function(){
					var _this=this;
					$('.mtop_menu').removeClass('user-profile-on');
					$(_this).addClass('user-profile-on');
					$('.ltop_menu').hide();
					$('#top_'+$(_this).attr('data-id')).show();
					var fristlink=$('#top_'+$(_this).attr('data-id')).children('li');
					if(fristlink.children('a').eq(0).next('.sidebar-submenu').length>0){
						fristlink.children('a').eq(0).next('.sidebar-submenu').children('ul').children('li').eq(0).children('a').eq(0).click();
					}else{
						fristlink.children('a').eq(0).click();
					}

				});
				</script>
<script  type="text/javascript" src="<?php echo $this->assets(); ?>/js/minified/admin.js?v=1.1"></script>
</body></html>
