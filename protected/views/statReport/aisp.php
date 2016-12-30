
<link rel="stylesheet" type="text/css" href="<?php echo $this->assets(); ?>/css/stat.css">
<div id="page-title">
	<h3>
		统计
		<small> >>地区、运营商</small>
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
	<div class="tabs ui-tabs ui-widget ui-widget-content ui-corner-all">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			 <li onclick="location.href='<?php U('aisp/type/area')?>'" <?php if($_GET['type']=='area') echo 'class=" ui-tabs-active ui-state-active"';?>>
				<a href="#icon-only-tabs-2">
					<i class="glyph-icon icon-trello float-left opacity-80"></i>
					地区分布
				</a>
			</li>
			
			<li onclick="location.href='<?php U('aisp/type/isp')?>'" <?php if($_GET['type']=='isp') echo 'class=" ui-tabs-active ui-state-active"';?>>
				<a href="#icon-only-tabs-3">
					<i class="glyph-icon icon-bar-chart float-left opacity-80"></i>
					网络运营商
				</a>
			</li>
		</ul>
		<div id="icon-only-tabs-<?php if($_GET['type']=='area'){ echo 2;}else{echo 1;}?>" aria-labelledby="ui-id-<?php if($_GET['type']=='area'){ echo 2;}else{echo 1;}?>" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-expanded="false" aria-hidden="true" style="display: block;">
		
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
	<?php echo calendar(chtmlName($model, 'rtime').'[1]',$r1?$r1:date('Y-m-d', strtotime("-7 day")), 'YYYY-MM-DD','180px');?>--
	<?php echo calendar(chtmlName($model, 'rtime').'[2]',$r2?$r2:date('Y-m-d'), 'YYYY-MM-DD','180px');?>
	
	<button class="btn primary-bg medium" style="margin-left: 50px;">
		<span class="button-content"><i class="glyph-icon icon-search"></i>查询</span>
	</button>

<?php $this->endWidget(); ?>

</div>
<!-- search-form -->
	<br>
			<br>
			<br>	
</div>
	<div class="row">
		<div class="mobile-content">

			<div class="content-charts" id="content_charts">
						<br>
						<div class="charts-main" id="charts_main" data-highcharts-chart="12">
							<div class="highcharts-container" id="highcharts-4"></div>
						</div>
			</div>
			<br>
			<br>
			<br>
			
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
        text: '地区/运营商分布占比TOP10'
    },
    subtitle: {
        text: '来源: 活动统计'
    },
    tooltip: {
	    pointFormat: '占{point.percentage:.1f} %'
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
              foreach ($data1 as $k=>$v){
              	$ff=$v[$field]?$v[$field]:'未知';
               	echo "['".$ff."', ".$v['num']."],";
               }
            ?>
        ]
    }]
});
});
</script>
<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/highcharts/highcharts.js"></script>
<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/highcharts/modules/exporting.js"></script>
<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/highcharts/modules/no-data-to-display.js"></script>