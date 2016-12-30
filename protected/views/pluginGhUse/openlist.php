
<div id="page-title">
	<h3>
		互动微应用中心>>
		<small> 已开通微应用 </small>
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
	<div class="form">
		<div class="form-row">
			<div class="col-md-10" style="margin: 0 auto; float: none;">
				<div class="tabs ui-tabs ui-widget ui-widget-content ui-corner-all">
					<div id="icon-only-tabs-2" style="display: block;">
						<div class="row-fluid">
							<ul class="thumbnails">
		<?php if(empty($list)) echo '未开通任何活动微应用！';?>
		<?php foreach ($list as $key=>$val){?>
			<li style="width: 260px; width: 220px;">
									<div class="thumbnail" onclick="location.href='echo $this->createUrl('/plugin/view/id/'.$val->plugin['id'])?>'">
										<div class="module-pic" onclick="location.href='<?php echo $this->createUrl('/plugin/view/id/'.$val->plugin['id'])?>'">
											<img src="<?php echo $val->plugin['icon_url'];?>" onerror="this.src=&#39;<?php echo $this->assets(); ?>/images/nopic.png&#39;">
											<div class="module-detail" style="display: none;">
												<h5 class="module-title" style="display: block;">
								<?php echo $val->plugin->name;?>
								<small>（标识：<?php echo $val->plugin['ptype'];?>）</small>
												</h5>
												<p class="module-brief"><?php echo $val->plugin['simple_memo'];?></p>
											</div>
										</div>
										<div class="module-button">
											<a href="javascript:;" class="pull-left">
												<span class='font-red'>价格：<?php echo $val->plugin['price_year'];?>/年</span>
											</a>
											<a href="javascript:;" class="pull-left">
												<span class='font-blue'>已开通</span>
											</a>
											<a href="<?php echo $this->createUrl('/plugin/view/id/'.$val->plugin['id'])?>" class="pull-right">
												<span class='font-blue'>查看</span>
											</a>
										</div>
									</div>
								</li>
		<?php }?>
		</ul>
						</div>
					</div>
				</div>
			</div>
			<center>
<?php $this->widget('CLinkPager',$pages); ?>
</center>
		</div>
	</div>
	<script type="text/javascript">
$(function(){
	$('.thumbnail').hover(function(){
			$(this).find('div.module-detail').slideDown();
		},function(){
			$(this).find('div.module-detail').slideUp();
		}
	);
});

	</script>
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
	max-width: 100%;
	margin-right: auto;
	margin-left: auto;
}

.thumbnail .caption {
	padding: 9px;
	color: #555555;
}

.module-pic {
	width: 100%;
	min-height: 135px;
	max-height: 200px;
	overflow: hidden;
	position: relative;
	background-color: #E4E4E4;
	cursor: pointer;
}

.module-pic img {
	display: block;
	min-height: 135px;
	margin: 0 auto;
	height: 200px;
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
	filter: Alpha(opacity =       70);
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
}

.module-button a {
	padding-left: 10px;
}
</style>
</div>