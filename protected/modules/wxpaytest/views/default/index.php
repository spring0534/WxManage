<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" name="viewport">
<script src="http://www.playwx.com/hd/static/common/js/jquery-1.10.2.min.js"></script>
<title>微信支付测试</title>
<meta name="description" content="">
<meta name="keywords" content="">
<style type="text/css">
*{
	padding:0;
	margin:0;
}

html,body{
	width: 100%;
	height: 100%;
	overflow: hidden;
}

body{
	background-color: #fff;
	font-family: 'Arial';
}
.goodsInfo,.details{
	width: 100%;
	margin-top:25px;
	border-bottom:1px solid #cccccc;
}
.goodsInfo{
	text-align: center;
}
.details{
	margin-top:0px;
	margin-bottom: 20px;
}
.goodsInfo p:first-child{
	font-size: 18px;
}
.goodsInfo p:last-child{
	font-family:"Arial Black";
	font-size: 50px;
}
.details p{
	margin-top:10px;
	font-size: 16px;
}
.details p:last-child{
	margin-bottom:10px;
}
.details .item{
	margin-left:20px;
	color:#666666;
}
.details .content{
	float:right;
	color:#3f4f5f;
	font-size:14px;
	font-weight:500;
	font-family:"calibri";
	margin-right:15px;
}
.butt{
	background: #45C01A;
	color:#FFFFFF;
	font-family:"黑体";
	border-radius:4px;
	font-size:16px;
	text-align:center;
	width: 86.6%;
	height: 40px;
	line-height: 40px;
	margin:0 auto;
}
</style>
</head>	
<body>
 <div class="goodsInfo">
     <p><?php echo $shoppingdesc;?></p>
     <p><span>￥</span><?php echo $totalfee;?></p>
 </div>
 <div class="details">
     <p><span class="item">收款方</span><span class="content"><?php echo $compony;?></span></p> 
     <p><span class="item">商&nbsp;&nbsp;品</span><span class="content"><?php echo $shoppingdesc;?></span></p>  
 </div>
<div class="butt" onclick="callpay()">微信支付</div>
<script type="text/javascript">
//调用微信JS api 支付
var flag = true;
function jsApiCall(){
	if (flag){
		flag = false;
		$(".butt").css("background","#5EF12B");
		setTimeout(function(){
			$(".butt").css("background","#45C01A");
			WeixinJSBridge.invoke(
				 'getBrandWCPayRequest',
			     <?php echo $jspackage; ?>,
			     function(res){
				    flag = true;
					WeixinJSBridge.log(res.err_msg);
					//alert(res.err_code+res.err_desc+res.err_msg+window.location.href);
			});},500
		);
	}
}
function callpay(){
	if (typeof WeixinJSBridge == "undefined"){
	    if( document.addEventListener ){
	        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
	    }else if (document.attachEvent){
	        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
	        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
	    }
	}else{
	    jsApiCall();
	}
}
</script>
</body>
</html>