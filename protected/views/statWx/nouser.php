
<link rel="stylesheet" type="text/css" href="<?php echo $this->assets(); ?>/css/stat.css">
<div id="page-title">
	<h3>
		统计
		<small> >>新增用户统计</small>
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
	'action'=>Yii::app()->createUrl($this->route),
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
				
				<div class="charts-main" id="charts_main" data-highcharts-chart="12">
					<div class="highcharts-container" id="highcharts-44"></div>
				</div>
			</div>
			<br>
			<br>
			<br>
			
		</div>
	</div>
</div>
<script>
$(function () {
    $('#highcharts-44').highcharts({
        title: {
            text: '新增独立访客统计',
           
        },
        subtitle: {
            text: '来源: 活动统计',
            
        },
        xAxis: {
            categories: [ 
<?php 
        foreach ($data1 as $k=>$v){
        	echo "'".date('Y-m-d', strtotime($v['days']))."',";
        }
        ?>
                         ]
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
            name: '新增独立访客（CV）',
            data: [0,
                   <?php 
                   foreach ($data1 as $k=>$v){
                   	echo $v['cv'].',';
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