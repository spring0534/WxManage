<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<title>微信安全支付</title>
<style type="text/css">
@charset "utf-8";
*{margin: 0; padding: 0;}
html, body {
    color: #666666;
    height: 100%;
    line-height: 23px;
}
body,input{
	font:16px/1.5 tahoma,arial,"微软雅黑";
    vertical-align: middle;
}
input{
	width:300px;
}
a{
	display:inline-block;
	width:100px;
	height:30px;
	border-radius:15px;
	line-height:30px;
	background:#cccccc;
	cursor:pointer;
	text-decoration: none;
}
table{
	width:900px;
}
tr{
	heigth:40px;
	text-align:center;
}
.item{
	width:25%;
}
.txt{
	width:35%;
}
.tip{
	width:40%
}
.red{
	color:red;
}
.right{
	text-align:right;
}
.left{
	text-align:left;
}
</style>
</head>
<body>
<br />
<div align="center">
<form  action="?" name="myform" method="post">
   <table cellspacing="20" border="0">
		<tr><td class="item right">商户订单号|微信支付交易号:</td>
		    <td class="txt"><input type="text" name="out_trade_no" value="<?php echo $trade_no; ?>" id="ordernum"></td>
		    <td class="tip red left">*订单查询、退款、退款查询时填写</td>
		</tr>
        <tr><td class="right">退款金额(分):</td>
		    <td><input type="text" name="refund_fee" value=<?php echo $refund_fee; ?> ></td>
		    <td class="red left">*退款时填写，单位为分</td>
		</tr>
		<tr><td class="right">日期(格式：20140101):</td>
		    <td><input type="text" name="bill_date" value="<?php echo $bill_date; ?>"></td>
		    <td class="red left">*对账单查询时填写</td>
		</tr>
    </table>
</form>
<br />
<a onclick = "submitData('<?php echo Yii::app()->request->getHostInfo();?>/wxpaytest/default/orderQuery')" >订单查询</a>
<a onclick = "submitData('<?php echo Yii::app()->request->getHostInfo();?>/wxpaytest/default/refund')" >快速退款</a>
<a onclick = "submitData('<?php echo Yii::app()->request->getHostInfo();?>/wxpaytest/default/refundQuery')" >退款查询</a>
<a onclick = "submitData('<?php echo Yii::app()->request->getHostInfo();?>/wxpaytest/default/billQuery')" >对账单查询</a>
<br />
<br />
 <?php if(!empty($info)):?>
 <table cellspacing="1" border="0" style="background:#cccccc;">
    <tr style="background:#ffffff;"><td colspan="2"><h3><?php echo $title;?></h3></td></tr>
    <?php foreach ($info as $k=>$v){?>
       <tr style="background:#ffffff;"><td style="width:40%"><?php echo $v['name'];?></td>
           <td style="width:60%"><?php echo $v['msg'];?></td>
       </tr>
    <?php }?>
 </table> 
 <?php endif;?> 
</div>
<script type="text/javascript">
   var $order = document.getElementById('ordernum');
   function submitData(path){
	   if(!/\d{28,}/.test($order.value)){
		   $order.name = "out_trade_no";
	   } else {
		   $order.name = "transaction_id";
	   }
	   document.myform.action=path;
	   document.myform.submit();
   }
</script>
</body>
</html>