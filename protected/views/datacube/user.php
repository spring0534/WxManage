
<link rel="stylesheet" type="text/css" href="<?php echo $this->assets(); ?>/css/stat.css">
<div id="page-title">
	<h3>
		统计
		<small> >>用户分析数据</small>
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
			 <li onclick="location.href='<?php U('user/type/1')?>'" <?php if($_GET['type']=='1'||empty($_GET['type'])) echo 'class=" ui-tabs-active ui-state-active"';?>>
				<a href="#icon-only-tabs-1">
					<i class="glyph-icon icon-trello float-left opacity-80"></i>
					用户增减数据
				</a>
			</li>
			
			<li onclick="location.href='<?php U('user/type/2')?>'" <?php if($_GET['type']=='2') echo 'class=" ui-tabs-active ui-state-active"';?>>
				<a href="#icon-only-tabs-2">
					<i class="glyph-icon icon-bar-chart float-left opacity-80"></i>
					累计用户数据
				</a>
			</li>
			
		</ul>
		<div id="icon-only-tabs-<?php if($_GET['type']=='1'||empty($_GET['type'])){ echo 1;}else{echo 2;}?>" aria-labelledby="ui-id-<?php if($_GET['type']=='area'){ echo 2;}else{echo 1;}?>" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-expanded="false" aria-hidden="true" style="display: block;">
		
		<div class="search-form">
<div class="explain-col">
<?php $form=$this->beginWidget('CActiveForm', array(
	'method'=>'get',
	'htmlOptions' => array(
		'style' => 'margin: 0'
	),
)); ?>
	


	<?php echo $form->label($model,'day'); ?>: 
	<?php echo calendar(chtmlName($model, 'day').'[1]',$r1?$r1:date('Y-m-d', strtotime("-7 day")), 'YYYY-MM-DD','180px');?>--
	<?php echo calendar(chtmlName($model, 'day').'[2]',$r2?$r2:date('Y-m-d'), 'YYYY-MM-DD','180px');?>
	
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
        title: {
            text: '消息统计',
           
        },
        subtitle: {
            text: '来源: 消息接口',
            
        },
        xAxis: {
            categories: [ 
<?php 
        foreach ($data1['list'] as $k=>$v){
        	echo "'".$v['ref_date']."',";
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
        <?php if($_GET['type']==1||empty($_GET['type'])){
    		?>

    		
        series: [{
            name: '新增的用户数量',
            data: [
                   <?php 
                   foreach ($data1['list'] as $k=>$v){
                   
                   	echo $v['new_user'].',';
                   }
                   ?>
                  ]
        },{
            name: '取消关注用户量',
            data: [
                   <?php 
                   foreach ($data1['list'] as $k=>$v){
                   
                   	echo $v['cancel_user'].',';
                   }
                   ?>
                  ]
        }]
    		<?php 
    	}else{
    		?>
    		series: [{
                name: '累计用户数据量',
                data: [
                       <?php 
                       foreach ($data1['list'] as $k=>$v){
                       
                       	echo $v['cumulate_user'].',';
                       }
                       ?>
                      ]
            }]
    		<?php 
    	}?>
    });

   
});
</script>
<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/highcharts/highcharts.js"></script>
<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/highcharts/modules/exporting.js"></script>
<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/highcharts/modules/no-data-to-display.js"></script>