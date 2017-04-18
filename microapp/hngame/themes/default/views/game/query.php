<!DOCTYPE html>
<html lang="zh-CN"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>湖南游戏老乡会</title>
<style type="text/css">
a,abbr,acronym,address,applet,article,aside,audio,big,blockquote,body,canvas,caption,center,cite,code,dd,del,details,
dfn,div,dl,dt,em,embed,fieldset,figcaption,figure,footer,form,h1,h2,h3,h4,h5,h6,header,hgroup,html,i,iframe,img,ins,kbd,
label,legend,li,mark,menu,nav,object,ol,output,p,pre,q,ruby,s,samp,section,small,span,strike,strong,sub,summary,sup,table,
tbody,td,tfoot,th,thead,time,tr,tt,u,ul,var,video{margin:0;padding:0;border:0;font-size:100%;font:inherit;vertical-align:baseline}
article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section{display:block}
body{line-height:1}
ol,ul{list-style:none}
blockquote,q{quotes:none}
blockquote:after,blockquote:before,q:after,q:before{content:'';content:none}
table{border-collapse:collapse;border-spacing:0}
body{background:rgba(153, 153, 153, 0.16);font-family:"Microsoft YaHei","Microsoft JhengHei",STHeiti,MingLiu;text-align:center;color:#000;height:100%}
.query-container{margin:6% auto 0 auto}
h1{font-size:30px;font-weight:700;text-shadow:0 1px 4px rgba(0,0,0,.2)}
form{position:relative;width:305px;margin:3px auto 0 auto;text-align:center;}
input{width:200px;height:42px;line-height:42px;margin:3px;font-size:18px;color:#000;}
input:-moz-placeholder{color:#666}
input:-ms-input-placeholder{color:#666}
input::-webkit-input-placeholder{color:#666}
button{cursor:pointer;width:50px;height:44px;margin-top:25px;padding:0;background: rgba(6, 127, 228, 0.71);-moz-border-radius:6px;-webkit-border-radius:6px;border-radius:6px;border:0;-moz-box-shadow:0 15px 30px 0 rgba(255,255,255,.25) inset,0 2px 7px 0 rgba(0,0,0,.2);font-family:"Microsoft YaHei",Helvetica,Arial,sans-serif;font-size:16px;font-weight:700;color:#fff;text-shadow:0 1px 2px rgba(0,0,0,.1);-o-transition:all .2s;-moz-transition:all .2s;-webkit-transition:all .2s;-ms-transition:all .2s}
button:hover{-moz-box-shadow:0 15px 30px 0 rgba(255,255,255,.15) inset,0 2px 7px 0 rgba(0,0,0,.2);-webkit-box-shadow:0 15px 30px 0 rgba(255,255,255,.15) inset,0 2px 7px 0 rgba(0,0,0,.2);box-shadow:0 15px 30px 0 rgba(255,255,255,.15) inset,0 2px 7px 0 rgba(0,0,0,.2)}
button:active{-moz-box-shadow:0 15px 30px 0 rgba(255,255,255,.15) inset,0 2px 7px 0 rgba(0,0,0,.2);-webkit-box-shadow:0 15px 30px 0 rgba(255,255,255,.15) inset,0 2px 7px 0 rgba(0,0,0,.2);box-shadow:0 5px 8px 0 rgba(0,0,0,.1) inset,0 1px 4px 0 rgba(0,0,0,.1);border:0 solid #016FCB}
*{margin:0;padding:0}
img{border:none}
.box-line{height: 0; overflow: hidden; border-top: 1px #c2c2c2 solid; margin-top: 20px;}
.ul-list{text-align:left;}
.ul-list h2{text-align:center; margin-top:10px;}
.ul-list ul{border-top:1px #c2c2c2 solid; margin-top:10px; clear:both;}
.ul-list ul li{float: left; width: 95%; height: auto; padding:8px 0 0 10px;}
.ul-list ul li label{font-weight: bold;}
</style>
</head>
<body>
<div class="query-container">
	<h1>查询通讯录</h1>
	<form action="<?php echo AU('game/query');?>" method="post" id="queryForm" novalidate="novalidate">
		<div>
			<input type="text" id="keyword" name="keyword" placeholder="请输入搜索关键词"> <button id="queryBtn" type="button" class="subbtn">查 询</button>
		</div>
	</form>
</div>
<div class="ul-list">
</div>
<script src="<?php echo $this->assets(); ?>/js/jquery.min.js"></script>
<script src="<?php echo $this->SURL(); ?>/js/alert.js"></script>
<script type="text/javascript">
//jquery.validate表单验证
$(document).ready(function(){
	var isRunning = false;
	$("#queryBtn").click(function(){
        if(isRunning) return;
        if(!$("#keyword").val()){
            alert("请输入搜索关键词");
            return;
        }
        isRunning = true;
        $.ajax({  
            type : "POST",  
            url : "<?php echo AU('game/query');?>",
            data : {  
                "keyword" : $("#keyword").val()
            },//数据，这里使用的是Json格式进行传输  
            dataType:'json',
            success : function(result) {//返回数据根据结果进行相应的处理  
                if(result.result_code == 0){
                    var len = result.result_data.length;
                    if(len > 0){
                        $(".ul-list").html("");
                        $(".ul-list").append("<h2>查询结果</h2>");
                        var ul = "";
                    	for(var i=0; i < len; i++){
                        	var item = result.result_data[i];
                        	ul = "<ul>";
                        	ul += "<li><label>姓名</label>："+item.realname+"</li>";
                        	ul += "<li><label>手机</label>："+item.phone+"</li>";
                        	ul += "<li><label>公司</label>："+item.company+"</li>";
                        	ul += "<li><label>职位</label>："+item.position+"</li>";
                        	ul += "<li><label>家乡</label>："+item.address+"</li>";
                        	ul += "<li><label>QQ</label>："+item.qq+"</li>";
                        	ul += "<li><label>微信号</label>："+item.wxname+"</li>";
                        	ul += "<li><label>邮箱</label>："+item.email+"</li>";
                        	ul += "<li><label>资源</label>："+item.resource+"</li>";
                        	ul += "<li><label>需求</label>："+item.demand+"</li>";
                        	ul += "</ul>";
                        	$(".ul-list").append(ul);
                    	}
                    }else{
                        $(".ul-list").html("");
                        $(".ul-list").append("<h2>无数据</h2>");
                    }
                }
                isRunning = false;
            },
            error:function(xhr){
            	isRunning = false;
            },
        });  
	});
});
</script>
</body>
</html>