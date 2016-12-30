<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<title>微信安全支付</title>
</head>
<body>
</br></br></br></br>
<div align="center">
<form  action="./refund.php" method="post">
<p>申请退款：</p>
<p>退款单号: <input type="text" name="out_trade_no" value=<?php echo $out_trade_no; ?> ></p>
			<p>退款金额(分): <input type="text" name="refund_fee" value=<?php echo $refund_fee; ?> ></p>
		    <button type="submit" >提交</button>
		</form>
		
		</br>
		<a href="../index.php">返回首页</a>

	</div>
</body>
</html>