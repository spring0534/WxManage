
<div id="page-title">
	<h3>
		互动微应用中心>>
		<small> 各类微应用展示 </small>
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
		<a href="javascript:history.back(-1);" class="btn medium primary-bg btn_back">
			<span class="button-content">
				<i class="glyph-icon icon-reply float-left "></i>
				返回
			</span>
		</a>
		<div class="center-content col-md-10 center-margin">
			<div class="center-page plugin">
				<div class="float-left">
					<div class="col-md-6">
						<img src="<?php echo $model->icon_url;?>" style="height: 100px;">
					</div>
				</div>
				<div class="right">
					<ul>
						<li class="title"><?php echo $model->name;?></li>
						<li>
							分类：
							<span>游戏</span>
						</li>
						<li>
							兼容性：
							<span>无</span>
						</li>
						<li>
							简介：
							<span><?php echo $model->simple_memo;?></span>
						</li>
						<li class="prize">
							价格：
							<span><?php echo $model->price_year;?>元/年</span>
						</li>
					</ul>
				</div>
			</div>
			
			<div class="row">
				<div class="">
					<div class="center-page-post">
						<div class="box desc">
							<h2>介绍</h2>
							<p></p>
							<p><?php echo $model->detail_memo;?></p>
							<p></p>
						</div>
						
						<div class="box banner-list">
							<h2>截图</h2>
							<div id="owl-demo1" class="owl-carousel owl-theme owl-loaded">
								<div class="owl-stage-outer" style="padding-left: 0px; padding-right: 0px;">
									<div class="owl-stage">
								<?php foreach ((array)unserialize($model->screenshots) as $k=>$v){?>
									<div class=" float-left" style="width: 195.25px; margin-right: 10px;">
											<div class="item">
												<img style="width: 195.25px;" src="<?php echo $v['url'];?>" alt="<?php echo $v['alt'];?>" class="img-responsive ">
											</div>
										</div>
									<?php }?>
								</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>