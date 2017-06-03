<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name = "format-detection" content = "telephone=no">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=device-dpi">
    <title><?php  echo $this->activity['title'];?></title>
    <?php include_once ROOT_PATH.'/public/common/php_header.php';?>
    <style type="text/css">
    html{
	    background:url("<?php echo $this->SURL(); ?>/images/bg.jpg") no-repeat;
	    background-size:100% 100%;
	    width:100%; 
	    height:100%;
	    overflow：hidden;
    }
    .page .main{
        position: fixed;
	    bottom: 41%;
	    width: 100%;
	    left: 0px;
    }
    .input, .btn{
        width: 280px;
        margin: 5px auto;
    }
    .input input{
	    border-color: #000;
	    border-width: 1px;
	    border-spacing: inherit;
	    font-weight: bold;
	    font-size: 20px;
	    text-align: center;
	    background-color: rgba(0, 0, 0, 0);
        width:260px;
        height:32px;
        -webkit-appearance: none;
    }
    .btn input{
	    -webkit-appearance: none;
	    width: 280px;
	    height: 42px;
	    background-color: red;
	    border: azure;
	    font-size: 24px;
	    font-weight: bold;
	    color: #fff;
	    letter-spacing: 3px;
    }
    </style>
    <script src="<?php echo $this->SURL(); ?>/js/jquery-1.10.2.min.js"></script>
    <script src="<?php echo $this->SURL(); ?>/js/alert.js"></script>
    <script type="text/javascript">
        var sysParam = {
            wxId: "<?php echo $userinfo['openid'];?>",
            shareOpenId:"<?php echo empty($this->fromwx) ? '':$this->fromwx;?>", 
            baseUrl: '<?php echo $this->SURL(true); ?>/',
            isAttention: 1
        };
    </script>
</head>
<body>
<div class="page">
	<div class="main">
	   <div class="input"><input type="number" id="tb_sncode" name="tb_sncode" size="20" maxlength="20" placeholder="此处输入兑奖码"/></div>
       <div class="btn"><input class="btn" type="button" id="submit_btn" value="此处领取红包"/></div>
    </div>
</div>
<script type="text/javascript">
    var isRunning = false;
    $('#submit_btn').on('click',function(){
        if(isRunning) return;
        var tb_sncode = $('#tb_sncode').val();
        if(!(/^\d{10}$/.test(tb_sncode))){
            alert("请输入正确的兑奖码!");
            return;
        }
        $('#submit_btn').attr("disabled", true);
        isRunning = true;
        $.ajax({  
            type : "POST",  
            url : "<?php echo AU('game/save');?>",
            data : {  
                "tb_sncode" : tb_sncode  
            },//数据，这里使用的是Json格式进行传输  
            dataType:'json',
            success : function(result) {//返回数据根据结果进行相应的处理  
                alert(result.result_msg);
                if(result.result_code == 0){
                	$('#tb_sncode').val('');
                }
                $('#submit_btn').attr("disabled", false);
                isRunning = false;
            },
            error:function(xhr){
            	isRunning = false;
            },
        });  
    })
    
	function init_wxjs(){
		WX_STAT.init({
	    	  	hideToolbar:true,
	    		hideOptionMenu:false,
	    		networkType:"",
	    		title:"<?php echo preg_replace('/xxxx/', $this->userinfo['nickname'] , $settings['shareTitle']); ?>",
	    		desc: "<?php echo $settings['shareDesc']; ?>",
	    		img:"<?php echo $settings['shareIcon']; ?>",
	    		link:"<?php echo  Yii::app()->request->getHostInfo().AU('game/index') .'?_fromwx='. $this->userinfo['openid'];?>"+"&gid="+sysParam.doshareGoodId
	          },
	          { // 分享取消
	    		cancel : function(resp) {
	        		//alert(resp);
	    		},
	    		// 分享失败
	    		fail : function(resp) {
	    			//alert(resp);
	    		},
	    		// 分享成功
	    		ok : function(resp) {
	    			//alert(resp);
	    		}
	    	  },
	    	  {
    		    aid:'<?php echo $this->activity['aid'];?>',
          		wxid:'<?php echo $this->userinfo['openid'];?>',
          		fromType:<?php echo $this->fromType;?>,
          		fromWxid:'<?php echo $this->fromwx;?>',
	          }
		);
	}
	init_wxjs();
</script>
</body>
</html>
