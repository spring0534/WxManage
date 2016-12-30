
<link rel="stylesheet" type="text/css" href="<?php echo $this->assets(); ?>/css/stat.css">
<?php
/*Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#stat-report-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");*/
?>
<div id="page-title">
	<h3>
		统计
		<small> >>流量统计 </small>
	</h3>
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

	<?php echo $form->label($model,'day'); ?>: 
	<?php echo calendar(chtmlName($model, 'day').'[1]',$r1?$r1:date('Y-m-d', strtotime("-7 day")), 'YYYY-MM-DD','180px');?>--
	<?php echo calendar(chtmlName($model, 'day').'[2]',$r2?$r2:date('Y-m-d'), 'YYYY-MM-DD','180px');?>
	
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
					<div class="highcharts-container" id="highcharts-44"></div>
				</div>
			</div>
			
		</div>
	</div>
	
<br><br><br><br><br>
	<!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'stat-report-grid',
	'template' =>'<div class="content-box">
		{items}
	</div>
	<div class="summary" style="float: left;">{summary}</div>
	<div class="pager">{pager}</div>',
	'dataProvider'=>$model->search_flow(),
	/*'filter'=>$model,*/
	'columns'=>array(
		/*'id',*/
		array(
			'name'=>'day',
			'value'=>'$data->day',
			'footer'=>'统计结果',
			'footerHtmlOptions'=>array(
				'class'=>'tjtd',
				'style'=>'color:orangered'
			),
		),
		/*'aid',
		'ghid',*/
		'pv',
		'uv',
		'cv',
		'ip',
		's1',
		's2',
		's3',
		's4',
		'sub',
		'unsub',
		/*'msg',*/
	),
	
)); ?>
</div>


<script>
$(function () {
    $('#highcharts-44').highcharts({
        title: {
            text: '活动统计图表',
            
        },
        subtitle: {
            text: '来源: 活动统计',
           
        },
        xAxis: {
            categories: [ <?php 
            foreach ($model->search_flow()->data as $k=>$v){
            	echo "'".$v->day."',";
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
        plotOptions: {
            series: {
                showCheckbox: true
            },
            line:{
                events :{
                    checkboxClick: function(event) {
                        if(event.checked==true) {
                            this.show();
                        }
                        else {
                            this.hide();
                        }
                    },
                    legendItemClick:function(event) {//return false 即可禁用LegendIteml，防止通过点击item显示隐藏系列
                        return false;
                    }
                }
            }
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
                   foreach ($model->search_flow()->data as $k=>$v){
                   	echo $v['pv'].',';
                   }
                   ?>
                  ],
             selected: true//默认checkbox勾选
        }, {
            name: '独立访客（UV）',
            data: [<?php 
                    foreach ($model->search_flow()->data as $k=>$v){
               	echo $v['uv'].',';
               }
               ?>],
               selected: true//默认checkbox勾选
        }, {
            name: '独立IP数',
            data: [<?php 
                    foreach ($model->search_flow()->data as $k=>$v){
               	echo $v['ip'].',';
               }
               ?>],
               selected: true//默认checkbox勾选
        },{
            name: '分享好友次数',
            data: [<?php 
                    foreach ($model->search_flow()->data as $k=>$v){
               	echo $v['s1'].',';
               }
               ?>],
               selected: true//默认checkbox勾选
        }, {
            name: '分享朋友圈次数',
            data: [<?php 
                    foreach ($model->search_flow()->data as $k=>$v){
               	echo $v['s2'].',';
               }
               ?>],
               selected: true//默认checkbox勾选
        }]
    });

   
});
function tj(){
	var tjarr=[];
	$('.items tbody tr').each(function(index,element){
	  $(element).find('td').each(function(index2,element2){
		  if(index2>0){
			  tjarr[index2]=(parseInt(tjarr[index2])?parseInt(tjarr[index2]):0)+parseInt($(element2).text());
		  }
	  });
	});
	$(".tjtd").closest("tr").find('td').each(function(index,element){
	    if(index>0){
			$(element).html(tjarr[index]).css({'color':'orangered','font-weight':'600'});
	    }
	 });
}
tj();
$(document).on("click",".sort-link",function(){
	setTimeout(function(){
		tj();
	},2000);
});

</script>
<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/highcharts/highcharts.js"></script>
<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/highcharts/modules/exporting.js"></script>
<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/highcharts/modules/no-data-to-display.js"></script>
