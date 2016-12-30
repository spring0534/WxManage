
<link rel="stylesheet" type="text/css" href="<?php echo $this->assets(); ?>/css/stat.css">
<div id="page-title">
	<h3>
		统计
		<small> >>移动设备</small>
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
		<div class="search-form">
<div class="explain-col">
<?php $form=$this->beginWidget('CActiveForm', array(
	'method'=>'get',
	'htmlOptions' => array(
		'style' => 'margin: 0'
	),
)); ?>
	

	<?php echo $form->label($model,'aid'); ?>: 
    <?php echo CHtml::dropDownList(chtmlName($model, 'aid'), $model->aid,CHtml::listData($this->getActList(), 'aid', 'title'),array('empty'=>'全部','style'=>"width:200px;") )?>

	<?php echo $form->label($model,'rtime'); ?>: 
	<?php echo calendar(chtmlName($model, 'rtime').'[1]',$r1?$r1:date('Y-m-d', strtotime("-7 day")), '','180px');?>--
	<?php echo calendar(chtmlName($model, 'rtime').'[2]',$r2?$r2:date('Y-m-d'), '','180px');?>
	
	<button class="btn primary-bg medium" style="margin-left: 50px;">
		<span class="button-content"><i class="glyph-icon icon-search"></i>查询</span>
	</button>

<?php $this->endWidget(); ?>

</div>
<!-- search-form -->

</div>
	<div class="row">
		<div class="mobile-content">
			

			<div class="content-charts" id="content_charts">
						<br>
						<div class="charts-main" id="charts_main" data-highcharts-chart="12">
							<div class="highcharts-container" id="highcharts-4"></div>
						</div>
			</div>
			
			<br><br><br><br>
			<div class="content-charts" id="content_charts">
						<br>
						<div class="charts-main" id="charts_main" data-highcharts-chart="12">
							<div class="highcharts-container" id="highcharts-6"></div>
						</div>
			</div>
				
		</div>
	</div>
</div>
<script>
$(function () {
    $('#highcharts-4').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: '操作系统占比'
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
<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/highcharts/highcharts.js"></script>
<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/highcharts/modules/exporting.js"></script>
<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/highcharts/modules/no-data-to-display.js"></script>