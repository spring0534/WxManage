
<link rel="stylesheet" type="text/css" href="<?php echo $this->assets(); ?>/css/stat.css">
<div id="page-title">
	<h3>
		统计
		<small> >>当前在线情况</small>
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

	 $(document).ready(function() {                                                  
	        Highcharts.setOptions({                                                     
	            global: {                                                               
	                useUTC: false                                                       
	            }                                                                       
	        });                                                                         
	                                                                                    
	        var chart;                                                                  
	        $('#highcharts-44').highcharts({                                                
	            chart: {                                                                
	                type: 'spline',                                                     
	                animation: Highcharts.svg, // don't animate in old IE               
	                marginRight: 2,                                                    
	                events: {                                                           
	                    load: function() {                                                        
	                    	 var series = this.series;                           
	                        setInterval(function() {                                    
	                        	                                  
	                        	  var x = (new Date()).getTime();
	                            $.post('',{dynamic:1},function(data){       
	                            	 	
	                            	series[0].addPoint([x, parseInt(data.pv)], true, true);
	                            	series[1].addPoint([x, parseInt(data.uv)], true, true);
	                            	series[2].addPoint([x, parseInt(data.ip)], true, true);
		                            },'json');                   
	                        }, 3000);                                               
	                    }                                                               
	                }                                                                   
	            },                                                                      
	            title: {                                                                
	                text: '当前在线情况'                                            
	            },                                                                      
	            xAxis: {                                                                
	                type: 'datetime',                                                   
	                tickPixelInterval: 150                                             
	            },                                                                      
	            yAxis: {                                                                
	                title: {                                                            
	                    text: '统计值'                                                   
	                },                                                                  
	                plotLines: [{                                                       
	                    value: 0,                                                       
	                    width: 1,                                                       
	                    color: '#808080'                                                
	                }]                                                                  
	            },                                                                      
	            tooltip: {                                                              
	                formatter: function() {                                             
	                        return '<b>'+ this.series.name +'</b><br>'+                
	                        Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) +'<br>'+
	                        Highcharts.numberFormat(this.y, 2);                         
	                }                                                                   
	            },                                                                      
	            legend: {                                                               
	                enabled: true                                                      
	            },                                                                      
	            exporting: {                                                            
	                enabled: false                                                      
	            },                                                                      
	            series: [{                                                              
	                name: '浏览次数（pv）',                                                
	                data: (function() {  


	                	 var data = [],                                                  
	                        time = (new Date()).getTime(),                              
	                        i;                                                          
	                                                                                    
	                   
	                	 <?php if(empty($data1)){
	                        	?>
	                        	data.push({x:0,y:0 }); 
	                        	<?php 
	                        }?>                                       
	                    <?php 
	                             foreach ($data1 as $k=>$v){
	                    
	                             	?>
	                             	 data.push({                                                 
	     	                            x: time - <?php echo 7-$k;?>*1000,                                     
	     	                            y: parseInt(<?php echo $v['pv'];?>)                                       
	     	                        }); 
	                             	<?php 
	                             }
	                             ?>                                                             
	                    return data; 
	                	                                                 
	                })()                                                              
	            },
	            {                                                              
	                name: '独立访客（uv）',                                                
	                data: (function() {  
	                	 var data = [],                                                  
	                        time = (new Date()).getTime(),                              
	                        i;  
	                	 <?php if(empty($data1)){
	                        	?>
	                        	data.push({x:0,y:0 }); 
	                        	<?php 
	                        }?>                                            
	                    <?php 
	                             foreach ($data1 as $k=>$v){
	                    
	                             	?>
	                             	 data.push({                                                 
	                             		  x: time - <?php echo 7-$k;?>*1000,                                   
	     	                            y: <?php echo $v['uv'];?>                                       
	     	                        }); 
	                             	<?php 
	                             }
	                             ?>                                                             
	                    return data; 
	                	                                                 
	                })()                                                              
	            },
	            {                                                              
	                name: '独立Ip数',                                                
	                data: (function() {  
	                	 var data = [],                                                  
	                        time = (new Date()).getTime(),                              
	                        i; 
	                        <?php if(empty($data1)){
	                        	?>
	                        	data.push({x:0,y:0 }); 
	                        	<?php 
	                        }?> 
	                	                                         
	                    <?php 
	                             foreach ($data1 as $k=>$v){
	                    
	                             	?>
	                             	 data.push({                                                 
	                             		  x: time - <?php echo 7-$k;?>*1000,                                    
	     	                            y: <?php echo $v['ip'];?>                                       
	     	                        }); 
	                             	<?php 
	                             }
	                             ?>                                                             
	                    return data; 
	                	                                                 
	                })()                                                              
	            }
	            ]                                                                      
	        });                                                                         
	    });            

});
</script>
<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/highcharts/highcharts.js"></script>
<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/highcharts/modules/exporting.js"></script>
<script type="text/javascript" src="<?php echo $this->assets(); ?>/js/highcharts/modules/no-data-to-display.js"></script>