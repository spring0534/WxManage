<link href="<?php echo $this->assets(); ?>/css/admin_style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->assets(); ?>/js/artDialog/skins/default.css" rel="stylesheet" />

<style>
body{
background: #FFFFFF
}
input, select, textarea {
width: auto;
}
/*设置tab*/
.pop_nav {
	padding:10px 15px 0;
	margin-bottom:10px;
}
.pop_nav ul{
	border-bottom:1px solid #e3e3e3;
	padding:0 5px;
	height:25px;
	clear:both;
}
.pop_nav ul li{
	float:left;
	margin-right:10px;
}
.pop_nav ul li a{
	float:left;
	display:block;
	padding:0 10px;
	height:25px;
	line-height:23px;
}
.pop_nav ul li.current a{
	border:1px solid #e3e3e3;
	border-bottom:0 none;
	color:#333;
	font-weight:700;
	background:#fff;
	position:relative;
	border-radius:2px;
	margin-bottom:-1px;
}
.pop_cont{
	padding:0 15px;
}
/*上传*/
.edit_menu_cont {
    padding: 10px 15px;
}
.edit_uping {
    height: 30px;
    margin-bottom: 10px;
}
.edit_uping .num {
    color: #999999;
    float: right;
    margin-top: 5px;
}
.edit_uping .num em {
    color: #FF5500;
    font-style: normal;
}
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
    background: url("<?php echo $this->assets(); ?>/images/icon/upload_pic.jpg") no-repeat scroll center center #FBFBFB;
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
.addnew:hover {
background-position: initial;
}
#btupload{
    vertical-align:middle;border:none;cursor: hand;!important;cursor: pointer;
}
.addnew{

	background: url("<?php echo $this->assets(); ?>/js/swfupload/images/swfBnt.png?v=s") no-repeat; float:left; margin-right:10px;width:75px; height:28px; line-height:28px;font-weight:700; color:#fff;
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
<script>
//用于图库加载
function set_iframe(id,src){
	if($("#"+id).attr("src") == ""){
		$("#"+id).attr("src",src);
	}
}

//网络地址
function applinefile(obj) {
	var strs = $(obj).val() ? '|'+ $(obj).val() :'';
	$('#att-status').html(strs);
}

//是否添加水印设置
function change_params(){
	if($('#watermark_enable').attr('checked')) {

		swfu_<?php echo $module; ?>.addPostParam('watermark_enable', '1');
	} else {
		swfu_<?php echo$module;?>.removePostParam('watermark_enable');
	}
}

</script>
<script type="text/javascript">
<?php echo $initupload;?>
</script>
<div class="wrap" style="padding:5px;">
  <div class="pop_nav">
    <ul class="J_tabs_nav">
      <li class="current"><a href="javascript:;;">上传附件</a></li>
     <!-- $isadmn -->
      <?php if($thumb_div){?>
      <li class=""><a href="javascript:;;" onClick="set_iframe('album_list','<?php U("public_album_load",array("args"=>$_GET['args']));?>')">图库</a></li>
      <?php }?>
      <?php if($online_div){?>
       <li class=""><a href="javascript:;;">网络文件</a></li>
       <?php }?>
       <?php if($res_div){?>
 	  <!-- <li class=""><a href="javascript:;;" onClick="set_iframe('album_public-list','<?php U("public_wxm_load",array("args"=>$_GET['args']));?>')">提供素材</a></li> -->
    <?php }?>
    </ul>
  </div>
  <div class="J_tabs_contents">
    <div class="pop_cont">
      <div class="">
        <div class="edit_uping">
             <!--选择按钮-->
            <div class="addnew"><span  id="buttonPlaceHolder"></span></div>
            <span class="num">
              <!--
            	<input type="checkbox" id="watermark_enable" value="1" <?php if( $watermark_enable){?>checked<?php }?>  onclick="change_params()"><em>是否添加水印</em>-->
				最多上传<em> <?php echo $file_upload_limit;?></em> 个附件,单文件最大 <em><?php echo $file_size_limit;?> KB</em>,
				<em style="cursor: help;" title="可上传格式：{$file_types}">支持<?php echo $file_types;?>格式</em>
			</span>
        </div>
        <div class="eidt_uphoto">
          <ul id="fsUploadProgress" class="cc">
            <!--<li class="J_empty"><div class="no">暂无</div></li>-->
          </ul>
        </div>
      </div>
    </div>
  <?php if($thumb_div){?>
    <div class="pop_cont">
      <iframe name="album-list" src="" frameborder="false" scrolling="no" style="overflow-x:hidden;border:none" width="100%" height="350" allowtransparency="true" id="album_list"></iframe>
    </div>
     <?php }?>
      <?php if($online_div){?>
     <div class="pop_cont"> 请输入网络地址
      <div class="bk3"></div>
      <input type="text" name="info[filename]" class="input" value=""  style="width:600px;" placeholder="http://"  onblur="applinefile(this)">
    </div>
     <?php }?>
      <?php if($res_div){?>
     <div class="pop_cont">
      <iframe name="album_public-list" src="" frameborder="false" scrolling="no" style="overflow-x:hidden;border:none" width="100%" height="350" allowtransparency="true" id="album_public-list"></iframe>
    </div>
     <?php }?>
  </div>
</div>
<div id="att-status" style="display:none"></div>
<div id="att-status-del" style="display:none"></div>
<div id="att-name" style="display:none"></div>

<script>
$(function(){
    $("#att-status").html("");
    $("#att-status-del").html("");
    $("#att-name").html("");
	//tab
	var tabs_nav = $('.J_tabs_nav');
	if (tabs_nav.length) {
	    Wind.use('tabs', function () {
	        tabs_nav.tabs('.J_tabs_contents > div');
	    });
	}
	});
</script>
</html>