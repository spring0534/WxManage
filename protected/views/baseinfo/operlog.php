<!-- 页面标题 -->
<div id="page-title">
	<h3>
		基本管理
		<small> &gt;&gt;查看日志 </small>
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
	<div class="form-row col-md-10 center-margin text-center" style="padding-bottom: 18px;">
		<div class="form-label col-md-2  font-size-14">
			<label for="">请选择搜索条件:</label>
		</div>
		<div class="form-input col-md-10">
			<div class="row">
				<div class="col-md-4">
					<select id="" name="">
						<option>请选择搜索条件</option>
						<option value="adminid" <?php echo ($cond == 'adminid')? " selected='selected'" : "";?>>管理员昵称</option>
						<option value="optime" <?php echo ($cond == 'optime')? " selected='selected'" : "";?>>操作时间</option>
					</select>
				</div>
				<div class="col-md-4">
					<input placeholder="输入时间格式为xxxx-xx-xx，如2014-11-20" type="text" value="<?php echo $cont; ?>" name="content" />
				</div>
				<div class="col-md-2 float-left">
					<a href="#" id="search" class="btn medium primary-bg ">
						<span class="button-content">
							<i class="glyph-icon icon-search float-left"></i>
							立即搜索
						</span>
					</a>
					<script type="text/javascript">
		                    $(function(){
		                 	   $("#search").click(function(){
		                 		   var cond = $("select option:selected").val();
		                 		   var cont = $("input[name=content]").val();
		                 		   if (cond == "请选择搜索条件"){
		                 	            alert("请选择搜索条件");
		                 		   } else if (cont==""){
		                 			   alert("请输入条件值");
		                 		   } else {
		                 			   location.href="<?php echo $this->createUrl('/baseinfo/operlog');?>"+"?condition="+cond+"&content="+cont;
		                 		   }
		                 	   });
		                 	});
	                    </script>
				</div>
			</div>
		</div>
	</div>
	<style type="text/css">
html {
	_overflow-y: scroll;
}

table tr td {
	font-size: 14px;
	border-bottom: #E9ECF1 solid 1px;
	padding: 8px;
}
</style>
	<div class="content-box">
		<table class="table table-hover text-center">
			<tbody>
				<tr>
					<th class="text-center" style="width: 10%">序号</th>
					<th class="text-center" style="width: 25%">管理员昵称</th>
					<th class="text-center" style="width: 25%">操作模块</th>
					<th class="text-center" style="width: 20%">实际操作</th>
					<th class="text-center" style="width: 20%">操作时间</th>
				</tr>
			</tbody>
			<tbody>
	 <?php echo $loglist?>	
	            </tbody>
		</table>
	</div>
</div>
<center>
	总共有
	<b><?php echo $count;?></b> 条记录
&nbsp;&nbsp;&nbsp;&nbsp;
<?php

$this->widget('CLinkPager', array(
	'header'=>'', 
	'firstPageLabel'=>'首页', 
	'lastPageLabel'=>'末页', 
	'prevPageLabel'=>'上一页', 
	'nextPageLabel'=>'下一页', 
	'pages'=>$pages, 
	'maxButtonCount'=>20
));
?>
</center>