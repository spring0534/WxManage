<body class="body_none" style="height:350px;">
<style>
/*上传*/
.eidt_uphoto {
    border: 1px solid #CCCCCC;
}
.eidt_uphoto ul {
    height: 280px;
    overflow-y: scroll;
    padding-bottom: 10px;
    position: relative;
}
.eidt_uphoto li {
    display: inline;
    float: left;
    height: 100px;
    margin: 10px 0 0 10px;
    width: 87px;
}
.eidt_uphoto .invalid {
    background: none repeat scroll 0 0 #FBFBFB;
    border: 1px solid #CCCCCC;
    height: 98px;
    position: relative;
    width: 78px;
}
.eidt_uphoto .invalid .error {
    padding: 30px 1px;
    text-align: center;
}
.eidt_uphoto .no {

    border: 1px solid #CCCCCC;
    height: 98px;
    overflow: hidden;
    text-indent: -2000em;
}
.eidt_uphoto .nouplode {
    background: #FBFBFB;
    border: 1px solid #CCCCCC;
    height: 98px;
    overflow: hidden;
	text-align: center;
	padding: 0px 5px 0px 5px;
}
.eidt_uphoto .schedule {
    background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #CCCCCC;
    height: 98px;
    line-height: 98px;
    position: relative;
    text-align: center;
}
.eidt_uphoto .schedule span {
    background: none repeat scroll 0 0 #F0F5F9;
    height: 98px;
    left: 0;
    position: absolute;
    top: 0;
}
.eidt_uphoto .schedule em {
    left: 0;
    position: absolute;
    text-align: center;
    top: 0;
    width: 78px;
    z-index: 1;
}
.eidt_uphoto .get{
	background:#ffffff;
	border:1px solid #cccccc;
	position:relative;
	overflow:hidden;
}
.eidt_uphoto .selected{
	border:2px solid #1D76B7;
}
.eidt_uphoto .get img{
	cursor:pointer;
}
.eidt_uphoto .selected a{
	position:absolute;
	width:15px;
	height:15px;
	background:url("<?php echo $this->assets(); ?>/images/icon/check.gif") no-repeat;
	right:1px;
	top:1px;
	overflow:hidden;
	text-indent:-2000em;

}
.eidt_uphoto .del:hover{
	background-position:-20px 0;
}
.eidt_uphoto .get img{
	vertical-align:top;
	width:87px;
	height:75px;
	border-bottom:1px solid #ccc;
}
.eidt_uphoto .get input{
	border: 0!important;
	outline: 0 none;
	width: 100%;
}
.eidt_uphoto .get .edit{
	position:absolute;
	height:22px;
	line-height:22px;
	text-align:center;
	width:78px;
	bottom:0;
	left:0;
	background:#e5e5e5;
	color:#333;
	filter:alpha(opacity=70);
	-moz-opacity:0.7;
	opacity:0.7;
	display:none;
}
.eidt_uphoto li:hover .edit,
.eidt_uphoto li:hover .del{
	/*text-decoration:none;
	display:block;*/
}
/*上传选择按钮*/

#btupload{
    vertical-align:middle;border:none;cursor: hand;!important;cursor: pointer;
}

.addnew{
	background-position: left bottom;
}
#page-content-wrapper {
  margin-left: 230px;
  position: relative;
  z-index: 4;
  margin-top: 10px;
  margin-right: 0px;
}
</style>
<div class="">
  <form method="post" action="<?php U("public_album_load")?>">

    <div class="search_type cc mb10">
       <!--<div class="mb10"> <span class="mr20"> 名称：
        <input type="text" class="input " name="filename" style="width:200px;" value="<?php echo $_GET[filename];?>" placeholder="请输入文件名...">
        日期：
        <input type=" float-left"  name="uploadtime" class="input   J_date" style="width:200px;" value="<?php echo $_GET[uploadtime];?>" placeholder="上传日期">
       			<button  type="submit" class="btn medium primary-bg " id="demo-form-valid" onclick="alert('暂时不支持该操作');return false;">
						<span class="button-content">搜索</span>
					</button>
        </span> </div>-->
    </div>

  </form>
  <div class="eidt_uphoto">
    <ul  style="height: 250px;" id="fsUploadProgress">

      <?php foreach ($data as $kk=>$vo ){?>
        <li class="uploaded">
          <div class="get" id="aid-<?php echo $vo[id];?>"> <a class="del" href="javascript:;"></a> <img onClick="album_cancel(this,'<?php echo $vo[id];?>','<?php echo $vo[url];?>')" width="87" height="98" src="<?php echo thumb($vo[filepath]);?>" data-id="<?php echo $vo[id];?>" data-path="<?php echo $vo[url];?>" alt="<?php echo $vo[filename];?>" title="<?php echo $vo[filename];?>">
            <input type="text" class="J_file_desc" name="flashatt[<?php echo $vo[id];?>]" value="<?php echo $vo[filename];?>" style="width:68px">
          </div>
        </li>
        <?php }?>

    </ul>
  </div>
  <div class="p10"><div class="float-right mrg10T"> <?php $this->widget('CLinkPager',$pages); ?></div> </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	set_status_empty();
});
function set_status_empty(){
	parent.window.$('#att-status').html('');
	parent.window.$('#att-name').html('');
}
function album_cancel(obj,id,source){
	var src = $(obj).attr("data-path");
	var filename = $(obj).attr("title");
	//选择状态中的数据对象
	var selected = $("#fsUploadProgress .selected");
	if($("#aid-"+id).hasClass('selected')){
		$("#aid-"+id).removeClass("selected");
		selected = $("#fsUploadProgress .selected");
		var imgstr = parent.window.$("#att-status").html();
		var length = selected.children("img").length;
		var strs = filenames = '';
		for(var i=0;i<length;i++){
			strs += '|'+selected.children("img").eq(i).attr('path');
			filenames += '|'+selected.children("img").eq(i).attr('title');
		}
		parent.window.$('#att-status').html(strs);
		parent.window.$('#att-name').html(filenames);
	} else {
		var num = parent.window.$('#att-status').html().split('|').length;
		var file_upload_limit = '<?php echo $file_upload_limit; ?>';
		if(num > file_upload_limit) {alert('不能选择超过'+file_upload_limit+'个附件'); return false;}
		$("#aid-"+id).addClass("selected");
		parent.window.$('#att-status').append('|'+src);
		parent.window.$('#att-name').append('|'+filename);
	}
}
</script>
</body>
