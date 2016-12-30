
<link rel="stylesheet" type="text/css" href="<?php echo $this->assets(); ?>/css/stat.css">
<div id="page-title">
	<h3>
		统计概况
		<small> >>概况</small>
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

	<div class="row " style="margin-top: -20px;">
		<div class="mobile-content">
			<div class="content-overview" id="content_overview">
				<div class="content-time">昨日（<?php echo date('Y-m-d',strtotime("-1 day"))?>）</div>


				<div class="row mrg20L" style=" height: 100px;">

            <div class="col-md-2" style="width: 165px;">
                <div class="info-box bg-purple font-white">
                    <b>新增关注人数</b>
                    <span class="stats mrg10R">

                       <?php echo intval($data['sub']);?>
                    </span>
                </div>

            </div>
            <div class="col-md-2"  style="width: 165px;">

                <div class="info-box bg-gray">
                    <b>取消关注人数</b>
                    <span class="stats mrg10R">

                        <?php echo intval($data['unsub']);?>

                    </span>
                </div>

            </div>
            <div class="col-md-2"  style="width: 165px;">

                <div class="info-box bg-orange">
                    <b>接收消息数</b>
                    <span class="stats mrg10R">

                       <?php echo intval($data['msg_num']);?>

                    </span>
                </div>

            </div>
            <div class="col-md-2"  style="width: 165px;">

                <div class="info-box success-bg">
                    <b>发送消息数</b>
                    <span class="stats mrg10R">

                       0

                    </span>
                </div>

            </div>


        </div>

			<br>
			<div class="row mrg20L" style=" height: 100px;">
				<div class="col-md-2"  style="width: 165px;">

	                <div class="info-box success-bg">
	                    <b>活动浏览次数（PV）</b>
	                    <span class="stats mrg10R"  >

	                        <?php echo intval($dataY['pv']);?>

	                    </span>
	                </div>

	            </div>
	            <div class="col-md-2"  style="width: 165px;">

	                <div class="info-box bg-azure font-black">
	                    <b>活动独立访客（UV）</b>
	                    <span class="stats mrg10R">

	                        <?php echo intval($dataY['uv']);?>

	                    </span>
	                </div>

	            </div>
	            <div class="col-md-2"  style="width: 165px;">

	                <div class="info-box bg-orange">
	                    <b>活动访问IP数</b>
	                    <span class="stats mrg10R">

	                        <?php echo intval($dataY['ip']);?>

	                    </span>
	                </div>

	            </div>
	            <div class="col-md-2"  style="width: 165px;">

	                <div class="info-box bg-green">
	                    <b>活动新独立访客</b>
	                    <span class="stats mrg10R">

	                        <?php echo intval($dataY['cv']);?>

	                    </span>
	                </div>

	            </div>
	            <div class="col-md-2"  style="width: 165px;">

	                <div class="info-box bg-gray-alt">
	                    <b>活动分享好友次数</b>
	                    <span class="stats mrg10R">

	                        <?php echo intval($dataY['s1']);?>

	                    </span>
	                </div>

	            </div>
	            <div class="col-md-2"  style="width: 165px;">

	                <div class="info-box bg-blue">
	                    <b>活动分享朋友圈次数</b>
	                    <span class="stats mrg10R">

	                        <?php echo intval($dataY['s2']);?>

	                    </span>
	                </div>

	            </div>

			</div>

			<div style="border-bottom: 1px solid #D8D8D8;width:100%"></div>
			<div class="content-charts" id="content_charts">

				<div class="charts-main" id="charts_main" data-highcharts-chart="12">
					<div class="highcharts-container" id="highcharts-44"></div>
				</div>
			</div>
			<br>
			<br>
			<br>
			<div class="content-system">
				<div class="system-head">
					<em></em>
				</div>
				<div class="system-lengend" id="system_lengend" style="background:#F5F7F9">
					<em class="l-name" style="font-style: normal">移动设备统计7天内统计</em>
				</div>
			</div>
			<div class="content-charts" id="content_charts">
				<div class="charts-main" id="charts_main" data-highcharts-chart="12" style="width: 45%; float: left;">
					<div class="highcharts-container" id="highcharts-4"></div>
				</div>

				<div class="charts-main" id="charts_main" data-highcharts-chart="12" style="width: 45%; float: left;">
					<div class="highcharts-container" id="highcharts-6"></div>
				</div>
			</div>


		</div>
	</div>
</div>
</div>
<script>
$(function () {
    $('#highcharts-44').highcharts({
        title: {
            text: '7天内活动统计图表',

        },
        subtitle: {
            text: '来源: 活动统计',

        },
        xAxis: {
            categories: [ <?php
                    foreach ($dataM as $k=>$v){
               	echo "'".$v['day']."',";
               }
               ?>]
        },
        yAxis: {
            title: {
                text: '数量'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: ''
        },
        /*legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },*/
        series: [{
            name: '浏览次数（PV）',
            data: [
                   <?php
                   foreach ($dataM as $k=>$v){
                   	echo $v['pv'].',';
                   }
                   ?>
                  ]
        }, {
            name: '独立访客（UV）',
            data: [<?php
                    foreach ($dataM as $k=>$v){
               	echo $v['uv'].',';
               }
               ?>]
        }, {
            name: '独立IP数',
            data: [<?php
                    foreach ($dataM as $k=>$v){
               	echo $v['ip'].',';
               }
               ?>]
        },{
            name: '分享好友次数',
            data: [<?php
                    foreach ($dataM as $k=>$v){
               	echo $v['s1'].',';
               }
               ?>]
        }, {
            name: '分享朋友圈次数',
            data: [<?php
                    foreach ($dataM as $k=>$v){
               	echo $v['s2'].',';
               }
               ?>]
        }]
    });

    $('#highcharts-4').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: '7天内操作系统占比'
        },
        subtitle: {
            text: '来源: 活动统计'
        },
        tooltip: {
    	    pointFormat: '操作系统占比'
        },

        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                },
                showInLegend: true
            }
        },
        series: [{
            type: 'pie',
            name: 'Browser share',
            data: [
                <?php
                  foreach ($os as $k=>$v){
                   	echo "['".$v['os']."',   ".$v['num']."],";
                   }
                ?>
            ]
        }]
    });

    $('#highcharts-6').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: '移动品牌TOP15'
        },
        subtitle: {
            text: '来源: 活动统计'
        },
        xAxis: {
            categories: [
                         <?php
                                 foreach ($mobile as $k=>$v){
                                 	if(empty($v['mobile'])){$v['mobile']='其它';}
                                  	echo "'".$v['mobile']."',";
                                  }
                               ?>
            ]
        },
        yAxis: {
            min: 0,
            title: {
                text: '访问次数'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span>',
            pointFormat: '' + '',
            footerFormat: '<table><tbody><tr><td style="color:{series.color};padding:0">{series.name}: </td><td style="padding:0"><b>{point.y:.1f} mm</b></td></tr></tbody></table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: '访问次数',
            data: [ <?php
                    foreach ($mobile as $k=>$v){
              	echo $v['num'].",";
              }
           ?>]

        }]
    });
});
</script>
<style>
.info-box b{
-moz-opacity: .8;
filter: alpha(opacity:80);
opacity: .8;
}
</style>
<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/highcharts/highcharts.js"></script>
<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/highcharts/modules/exporting.js"></script>
<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/highcharts/modules/no-data-to-display.js"></script>