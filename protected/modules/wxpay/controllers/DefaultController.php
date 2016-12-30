<?php
/**
 *
 * ----------------------------------------------
 * 版权所有 2014-2015 个人所有
 * ----------------------------------------------
 * @date: 2014-11-28
 * @author: mankio <546234549@qq.com>
 *
 */
header("content-type:text/html; charset=utf-8");
class DefaultController extends Controller{
	private $componyName;     //收款方，填公司名称
	private $goodsName;       //商品名称
	private $totalFee;        //支付金额，单位为分
	private $productFee;      //商品品费用，单位为分
	private $transportFee;    //物流费用，单位为分  当不为0时，确保 商品费用（productFee）+物流费用（transportFee）= 支付金额（totalFee）
	private $openid;          //支付用户的openid,V3版本JS API支付用到
	private $returnUri;       //返回到活动页面的URL
	private $orderid;         //订单号
    /**
	 * init()函数
	 * 根据公众号获取商户信息，保存到全局变量
	 */
	function init(){ 
		if (!empty($_GET['out_trade_no'])){
			$orderinfo = Yii::app ()->db2->createCommand ()
						->select ( 'id,ghid,openid,goodsname,price' )
						->from ( 'goods_order' )
						->where ( 'id=:id', array (':id' => $_GET['out_trade_no']) )
						->queryRow (); 
			$account = Yii::app ()->db->createCommand ()
						->select ( 'name,appid,appsecret,paySignKey,partnerId,partnerKey,mchId' )
						->from ( 'sys_user_gh' )
						->where ( 'ghid=:ghid', array (':ghid' => $orderinfo['ghid']) )
						->queryRow (); 
			//商户号信息
			$GLOBALS['accountinfo']= array(
					'appid' => $account['appid'],           //微信公众号原始id appid
					'appkey' => $account['paySignKey'],     //paySignKey,审核通过后在微信发送的邮件中查看
					'appsecret' => $account['appsecret'],  
					'partnerid' => $account['partnerId'],   //注册时分配的财付通商户号partnerId
					'partnerkey' => $account['partnerKey'], //财付通商户密钥partnerKey
					'mchid' => $account['mchId'],           //代理商户号  V3版
					'signtype' => 'sha1',                   //签名加密方法
					//'sslcertPath' => '/xxx/cacert/apiclient_cert.pem',    //SSL证书(pem格式)路径  V3版
					//'sslkeyPath' => '/xxx/cacert/apiclient_key.pem',      //SSL证书秘钥(pem格式)路径  V3版
					'curlTimeout' => 30,  //curl使用HTTP POST方法，此处可修改其超时时间，默认为30秒
					
			); 
			$this->componyName = $account['name'];
			$this->goodsName = $orderinfo['goodsname'];
			$this->totalFee = $orderinfo['price'] * 100;  //转换为分
			$this->productFee = 0;
			$this->transportFee = 0;
			$this->openid = $orderinfo['openid'];
			$this->orderid = 'ZO'.str_pad($orderinfo['id'], 13, 0,STR_PAD_LEFT);
			$this->returnUri = empty($_GET['return_uri']) ? null : $_GET['return_uri'];
		}
	}
	/**
	 * 确认订单页面
	 */
	public function actionIndex()
	{
		$this->renderPartial('index',array(
				'jspackage' =>  $this->getJspackageV3($this->openid),
				'shoppingdesc' => $this->goodsName,
				'totalfee'=> sprintf("%.2f", floatval($this->totalFee)/100),
				'compony' => $this->componyName,
				'returnUri' => $this->returnUri,   //回调URL
		));
	}
	
	/**
	 * V3版本通知接口
	 */
	public function actionNoticeV3(){
		include_once Yii::app()->getExtensionPath().'/WxPayPubHelper.php';
		$notify = new Notify_pub(); //使用通用通知接口
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
		//$this->wxpay_log(array('V3通知接口','notify通知信息',$xml));
		$notify->saveData($xml);
         //获取支付秘钥
       	$GLOBALS['accountinfo']['partnerkey'] = Yii::app ()->db->createCommand ()
			  ->select ( 'partnerKey' )
			   ->from ( 'sys_user_gh' )
			   ->where ( 'appid=:appid', array (':appid' => $notify->data["appid"]) )
			   ->queryScalar();
		//验证签名，并回应微信。如果微信收到商户的应答不是成功或超时，微信认为通知失败。
		if($this->isTrue($notify->data) == FALSE){
			$notify->setReturnParameter("return_code","FAIL");
			$notify->setReturnParameter("return_msg","签名失败");
		}else{
			$notify->setReturnParameter("return_code","SUCCESS");
		} 
		
		$returnXml = $notify->returnXml();
		echo $returnXml;
		
		//回调信息存入数据库
		$this->wxpay_log(array('V3通知接口','notify通知信息',$xml));
		if($notify->checkSign() == TRUE){
			if ($notify->data["return_code"] == "FAIL") {
				$data = array('isdeal'=>1,'paystatus'=>0,'payinfo'=>'通信出错');
				$this-> updateOrderInfo($data,$notify->data["out_trade_no"]); //更新订单状态
				$this->wxpay_log(array('V3通知接口','通信出错',$xml));
			}else if($notify->data["result_code"] == "FAIL"){
				$data = array('isdeal'=>1,'paystatus'=>0,'payinfo'=>'业务出错');
				$this-> updateOrderInfo($data,$notify->data["out_trade_no"]); //更新订单状态
				$this->wxpay_log(array('V3通知接口','业务出错',$xml));
			}else{
				$data = array(
						'payid' => $notify->data["transaction_id"],
						'isdeal' => 1,
						'paystatus' => 1,
						'getstatus' => 2,
						'payinfo' => '支付成功',
						'payend' => $notify->data["time_end"],
				);
				$this-> updateOrderInfo($data,$notify->data["out_trade_no"]); //更新订单状态
				$this->wxpay_log(array('V3通知接口','支付成功',$xml));
			}
			//例如：推送支付完成信息
		} 
	} 
	/**
	 * V3告警接口
	 */
	public function actionAlarm(){
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
		$this->wxpay_log(array('【测试】告警接口','告警信息',$xml));
		echo "success";
	}

	/**
	 * V3版本
	 * @return JS API支付所需的数据包
	 */
	private function getJspackageV3($openid){
		include_once Yii::app()->getExtensionPath().'/WxPayPubHelper.php';
		$jsApi = new JsApi_pub(); 
			
		$unifiedOrder = new UnifiedOrder_pub();
		$unifiedOrder->setParameter("openid","$openid");
		$unifiedOrder->setParameter("body","$this->goodsName");
		$unifiedOrder->setParameter("out_trade_no","$this->orderid");
		$unifiedOrder->setParameter("total_fee","$this->totalFee");
		$unifiedOrder->setParameter("notify_url",'http://wxpay.gamzer.cc/wxpay/default/noticeV3/');
		$unifiedOrder->setParameter("trade_type","JSAPI");
		/* if (!empty($this->returnUri)){
			$unifiedOrder->setParameter("attach","$this->returnUri");//附加数据
		} */
		//非必填参数，商户可根据实际情况选填
		//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号
		//$unifiedOrder->setParameter("device_info","XXXX");//设备号
		//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
		//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间
		//$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记
		//$unifiedOrder->setParameter("product_id","XXXX");//商品ID
		$prepay_id = $unifiedOrder->getPrepayId();
		$jsApi->setPrepayId($prepay_id);
		return $jsApi->getParameters();
	}
	/**
	 * 
	 * @param array $paramArr  参数数组
	 * @return string 返回JSON格式的字符串
	 * '{"appId":"wxf8b4f85f3a794e77","package":"bank_type=WX&body=%E5%BE%AE%E4%BF%A1%E6%94%AF%E4%BB%98test&fee_type=1&input_charset=UTF-8&notify_url=http%3A%2F%2Fwww.baidu.com&out_trade_no=6PZy39bnjb4lhmFu&partner=1900000109&spbill_create_ip=127.0.0.1&total_fee=1&sign=514ACB5AD2A7089DA88C1B0E1B6A2D92","timeStamp":1417076063,"nonceStr":"AIlg1whsjmEwWhUX","paySign":"48bb29dd4fabb3af3ca338f3b001ed2ba2aa8d86","signType":"sha1"}'
	 */
	public function getParameters($paramArr){
		Yii::import("application.controllers.WxPayHelperController");
	    $wxPayHelper = new WxPayHelperController();
		foreach ($paramArr as $k=>$v){
			$wxPayHelper->setParameter($k, $v);
		}
		return $wxPayHelper->create_biz_package();
	}

	/**
	 * 更新订单状态
	 * @param array $order  返回的订单信息
	 * @param string $orderid  订单号
	 */
	private function updateOrderInfo($order, $orderid){
		$id = (int)preg_replace('/ZO0+/', '', $orderid);
		$ginfo = Yii::app ()->db2->createCommand ()
    		->select ( 'goodsid,isdeal' )
    		->from ( 'goods_order' )
    		->where ( 'id=:id', array (':id' => $id) )
    		->queryRow();
		if($ginfo['isdeal']) return;   //订单已处理就返回
		if ($order['paystatus'] == 1){
			$ginfo = oo::goodsconf()->getGoodsByGid($ginfo['goodsid']);
			oo::goodsconf()->updateGoods($ginfo);
		}
		Yii::app()->db2->createCommand()
		 ->update('goods_order',$order,'id=:id', array (':id' => $id ));
	}
	 
	/**
	 *判断返回的交易通知里的订单信息是否被篡改
	 */
	private function isTrue($order){
		$filter = array();  //通知接口的参数过滤 sign和值为空的字段
		foreach ($order as $k=>$v){
			if ($k != 'sign' && $v !== ""){
				$filter[$k] = $v;
			}
		}
		Yii::import("ext.CommonUtil");
		$commonUtil = new CommonUtil();
		$str =  $commonUtil->formatQueryParaMap($filter,false);
		Yii::import("ext.MD5SignUtil");
		$md5SignUtil = new MD5SignUtil();
		$sign = $md5SignUtil->sign($str,$commonUtil->trimString($GLOBALS['accountinfo']['partnerkey']));
		return ($sign === $order['sign']) ? true : false;
	}
	/**
	 * 微信支付通知结果信息日志 
	 * @param  array $arr 数组的形式记录
	 */
	public function wxpay_log($arr){
		$data = array('interfaceName'=>$arr[0],'result'=>$arr[1],'data'=>$arr[2],'ctm'=>date('Y-m-d H:i:s',time()));
		Yii::app ()->db2->createCommand()->insert('wxpay_log',$data);
		unset($data);
	}
}