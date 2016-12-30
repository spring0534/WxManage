<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<title><?php echo $dao['title'];?></title>
<link rel="stylesheet" type="text/css" href="<?php echo $this->assets(); ?>/css/page_mp_article.css">
<style>
</style>
</head>
<body id="activity-detail" class="zh_CN ">
	<div id="js_article" class="rich_media">
		<div id="js_top_ad_area" class="top_banner"></div>
		<div class="rich_media_inner">
			<div id="page-content">
				<div id="img-content" class="rich_media_area_primary">
					<h2 class="rich_media_title" id="activity-name"><?php echo $dao['title'];?></h2>
					<div class="rich_media_meta_list">
						<em id="post-date" class="rich_media_meta rich_media_meta_text"><?php echo $gh->ctm;?></em>
						<a class="rich_media_meta rich_media_meta_link rich_media_meta_nickname" href="javascript:void(0);" id="post-user"><?php echo $gh->company;?></a>
					</div>
					<div class="rich_media_content" id="js_content">
						<?php echo $page->content;?>
					</div>
				</div>
				
			</div>
			
		</div>
	</div>
</body>
</html>