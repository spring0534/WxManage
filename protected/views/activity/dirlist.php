
<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#activity-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div id="page-title">
	<h3>
		资源目录
		<small> >>Home\<?php echo $cur_position;?> </small>
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
	<div style="padding-bottom: 10px;padding-left: 20px;">
		<a href="<?php echo $this->createUrl('/pluginProp/config',array('aid'=>$aid))?>" class="btn medium primary-bg ">
					<span class="button-content">
						<i class="glyph-icon icon-reply float-left "></i>
						返回活动配置
					</span>
				</a>
		<a href="<?php echo U('dirlist',array('aid'=>$aid))?>" class="btn medium primary-bg ">
			<span class="button-content">
				<i class="glyph-icon icon-home float-left "></i>
				目录首页
			</span>
		</a>
		<?php
          if(!empty($return_folder)){
          	?>
              <a href="<?php U('&dirlist')?><?php if(!empty($return_folder)){?>?dir=<?php echo $return_folder;?><?php }?>" class="btn medium primary-bg ">
					<span class="button-content">
						<i class="glyph-icon icon-reply float-left "></i>
						返回上一层
					</span>
				</a>
          	<?php
          }else if($home!=$cur_position){
          	?>
          	<a href="<?php echo U('dirlist',array('aid'=>$aid))?>" class="btn medium primary-bg ">
					<span class="button-content">
						<i class="glyph-icon icon-reply float-left "></i>
						返回上一层
					</span>
				</a>

          	<?php
          }
          ?>


				<div class="form-input col-md-6" style="float: right">

						<div class="form-input-icon">
                                    <i class="glyph-icon icon-search transparent"></i>
                                    <input  id="search_val" size="" value="" placeholder="请输入文件名查找"  type="text"  class="radius-all-100" name="" id="">
                                </div>
				</div>

	</div>

	<!-- search-form -->

      <table width="100%" cellspacing="0">

        <tbody>


<tr><td>
         <div class="row-fluid">
		<ul class="thumbnails">

		<?php foreach ($filelist as $key=>$vo){$i++;?>
			<li class="li_con" data-name="<?php echo $vo['filename'];?>">
									<div class="thumbnail" >
										<div class="module-pic"  <?php if($vo['isdir']==1){?> onclick="location.href='<?php U('&dirlist')?>?dir=<?php echo $vo['pname'];?>'" <?php }?>>

											 <?php if($vo['isdir']==1){
									            	?>
									            	<img src="<?php echo $this->assets(); ?>/images/icon/folder-icon-128x128.png" />
									            	<?php
									            }else{
									            	$ext=pathinfo($vo['pname'],PATHINFO_EXTENSION);
									            	if($ext=='jpg'||$ext=='jpeg'||$ext=='gif'||$ext=='png'||$ext=='ico'){
									            		?>

            											<img src="<?php echo $app_path.$cur_position.'/'.$vo['filename'].'?tc='.time(); ?>" id="csJmp<?php echo $i;?>_preview" onload="DrawImage(this,150,150,true)" onclick="image_priview($('#csJmp<?php echo $i;?>_preview').attr('src'))" width="100">

            											<?php
									            	}else if($ext=='html'||$ext=='htm'){
									            		?><img style="height: 150px" src="<?php echo $this->assets(); ?>/images/icon/fileicon/html.png" /><?php

									            	}else if($ext=='js'){
									            		?><img style="height: 150px" src="<?php echo $this->assets(); ?>/images/icon/fileicon/text.png" /><?php

									            	}else if($ext=='css'){
									            		?><img style="height: 150px" src="<?php echo $this->assets(); ?>/images/icon/fileicon/text.png" /><?php

									            	}else if($ext=='mp3'||$ext=='wav'){
									            		?><img style="height: 150px" src="<?php echo $this->assets(); ?>/images/icon/fileicon/mp3.png" /><?php

									            	}else if($ext==''){

									            	}else{
									            		?><img style="height: 150px" src="<?php echo $this->assets(); ?>/images/icon/fileicon/text.png" /><?php

									            	}

									            	unset($ext);
									            }?>
										</div>
										<div class="module-button">
											<a href="javascript:;" class="pull-left" title='<?php echo $vo['filename'];?>'>
												<?php echo $vo['filename'];?>
											</a>
								 			<?php if(empty($vo['isdir'])){
								 				$ext=pathinfo($vo['pname'],PATHINFO_EXTENSION);
								 				if($ext=='jpg'||$ext=='jpeg'||$ext=='gif'||$ext=='png'||$ext=='ico'){
								 					?><a class="pull-right" href="javascript:;" onclick="flashupload('csJmp<?php echo $i;?>', '上传图片','csJmp<?php echo $i;?>',thumb_images,'1,<?php echo $ext;?>,1,,,0,1,0,0,0,resourceUpload,<?php echo $vo['pname']; ?>,<?php echo $aid; ?>')"><span class='font-blue'>更改</span></a>
								 					<?php
								 				}else if($ext=='html'||$ext=='htm'){
								 					?><a class="pull-right" href="javascript:;" onclick="flashupload('csJmp<?php echo $i;?>', '上传图片','csJmp<?php echo $i;?>',thumb_images,'1,html|htm,1,,,0,1,0,0,0,resourceUpload,<?php echo $vo['pname']; ?>,<?php echo $aid; ?>')"><span class='font-blue'>更改</span></a>
								 					 <?php
								 				}else if($ext=='js'){
								 					?>
 					 							 <a class="pull-right" href="javascript:;" onclick="flashupload('csJmp<?php echo $i;?>', '上传图片','csJmp<?php echo $i;?>',thumb_images,'1,js,1,,,0,1,0,0,0,resourceUpload,<?php echo $vo['pname']; ?>,<?php echo $aid; ?>')"><span class='font-blue'>更改</span></a>
 					 							 <?php
								 				}else if($ext=='css'){
								 					?>
 					 								<a class="pull-right" href="javascript:;" onclick="flashupload('csJmp<?php echo $i;?>', '上传图片','csJmp<?php echo $i;?>',thumb_images,'1,css,1,,,0,1,0,0,0,resourceUpload,<?php echo $vo['pname']; ?>,<?php echo $aid; ?>')"><span class='font-blue'>更改</span></a>
 					 							<?php
								 				}else if($ext=='mp3'||$ext=='wav'){
								 					?>
 					 								<a class="pull-right" href="javascript:;" onclick="flashupload('csJmp<?php echo $i;?>', '上传图片','csJmp<?php echo $i;?>',thumb_images,'1,mp3,1,,,0,1,0,0,0,resourceUpload,<?php echo $vo['pname']; ?>,<?php echo $aid; ?>')"><span class='font-blue'>更改</span></a>
 					 							<?php
								 				}else if($ext==''){

								 				}else{

								 				}

 												unset($ext);

								            }?>

										</div>
									</div>
								</li>
		<?php }?>
		</ul>
	</div>
</td></tr>
         <?php if (empty($filelist)){
         	?>
         	<tr><td align="left" colspan="2">没有找到文件哦！</td></tr>
         	<?php
         }?>

        </tbody>
      </table>


</div>
<style>
.thumbnails {
	margin-left: -20px;
	list-style: none;
	*zoom: 1;
}

.thumbnails:before,.thumbnails:after {
	display: table;
	line-height: 0;
	content: "";
}

.thumbnails:after {
	clear: both;
}

.row-fluid .thumbnails {
	margin-left: 0;
}

.thumbnails>li {
	float: left;
	margin-bottom: 20px;
	margin-left: 20px;
}

.thumbnail {
	display: block;
	padding: 4px;
	line-height: 20px;
	border: 1px solid #ddd;
	cursor: pointer;
}

.thumbnail:hover {
	display: block;
	padding: 4px;
	line-height: 20px;
	border: 1px solid #ddd;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	-webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.055);
	-moz-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.055);
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.055);
	-webkit-transition: all 0.2s ease-in-out;
	-moz-transition: all 0.2s ease-in-out;
	-o-transition: all 0.2s ease-in-out;
	transition: all 0.2s ease-in-out;
	-webkit-box-shadow: 0 5px 15px rgba(0, 0, 0, .6) !important;
	box-shadow: 0 5px 15px rgba(0, 0, 0, .6) !important;
}

a.thumbnail:hover,a.thumbnail:focus {
	border-color: #0088cc;
	-webkit-box-shadow: 0 1px 4px rgba(0, 105, 214, 0.25);
	-moz-box-shadow: 0 1px 4px rgba(0, 105, 214, 0.25);
	box-shadow: 0 1px 4px rgba(0, 105, 214, 0.25);
}

.thumbnail>img {
	display: block;
	max-width: 150px;
	max-height: 150px;
	margin-right: auto;
	margin-left: auto;
}

.thumbnail .caption {
	padding: 9px;
	color: #555555;
}

.module-pic {
	width: 100%;
	height: 180px;
	overflow: hidden;
	position: relative;
	background-color: rgba(245, 247, 249, 0.6);
	cursor: pointer;
}

.module-pic img {
	display: block;
	//min-height: 135px;
	margin: 0 auto;
	//height: 200px;
	margin-top: 15px;
}

.module-button {
	padding: 9px 0px;
	padding-right: 5px;
	height: 30px;
	line-height: 30px;
	background-color: #F0F0F0;
}

.module-button .popover {
	width: auto;
	left: auto;
	top: auto;
	bottom: 0;
	right: 0;
	margin: 0;
	margin-bottom: 55px;
	line-height: 20px;
}

.module-button .popover-content {
	padding: 5px 10px;
	overflow: hidden;
}

.module-button .popover .arrow {
	left: 85%;
}

.module-button .popover select {
	width: 100%;
}

.module-detail {
	position: absolute;
	bottom: 0;
	filter: Alpha(opacity =         70);
	background: #000;
	background: rgba(0, 0, 0, 0.7);
	width: 100%;
	font-family: arial, 宋体b8b\4f53, sans-serif;
	height: 100%;
}

.module-detail p {
	padding: 0 9px;
	margin: 0;
}

.module-detail h5 {
	color: #FFF;
	font-weight: normal;
	padding: 0 9px;
}

.module-detail h5 small,.module-detail p {
	color: #CCC;
}

.pull-right {
	float: right;
}

.pull-left {
	float: left;
	width: 130px;
height: 40px;
white-space: nowrap;
overflow: hidden;
text-overflow: ellipsis;
}

.module-button a {
	padding-left: 10px;
}
</style>
<script>
$('#search_val').on('keyup paste', function() {
	if($('#search_val').val()!=''){
		$('.li_con').each(function(){
			var name=$(this).attr('data-name');
			if(name){
				if(name.indexOf($('#search_val').val()) != -1){
					$(this).show();
				}else{
					$(this).hide();
				}
			}

		});
	}else{
		$('.li_con').show();
	}

});

         </script>