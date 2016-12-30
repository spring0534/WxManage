<?php
/**
 *
 * ----------------------------------------------
 * 版权所有 2014-2015 联众互动
 * ----------------------------------------------
 * @date: 2014-11-28
 * @author: mankio <546234549@qq.com>
 *
 */
header("content-type:text/html; charset=utf-8");
class DefaultController extends Controller{
	private $componyName;     //收款方，填公司名称
	private $ghid;            //公众号id
	private $openid;
    /**
	 * init()函数
	 * 根据公众号获取商户信息，保存到全局变量
	 */
	function init(){
		if(!empty($_GET['ghid'])){
			$account = Yii::app ()->db->createCommand ()
						->select ( 'name,appid,appsecret,paySignKey,partnerId,partnerKey,mchId' )
						->from ( 'sys_user_gh' )
						->where ( 'ghid=:ghid', array (':ghid' => $_GET['ghid']) )
						->queryRow ();
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
			$this->ghid = $_GET['ghid'];
		} else { 
			//茂业百货南山店
			 $GLOBALS['accountinfo']= array(
			        'appid' => 'wxd9312eae2db48037',
					'appkey' => '',
					'appsecret' =>'3ce4b0c711450d56d0561293b8914a0c',
					'partnerid' =>'1395962102',
					'partnerkey' =>'w57e5g698wgjuh76t3o9iu7jms9872j8',
					'signtype' => 'sha1',
					'mchid' => '1395962102',
					'curlTimeout' => 30,
			
			);
			$this->ghid = 'gh_31c20303d9c5';   
			$this->componyName = '此页面由zard团队开发';
			$this->openid = 'o9n4cwy3Wj0Fzhw0OyA0sexKtnME';
		}
	}
	/**
	 * 确认订单页面
	 */
	public function actionIndex(){
		$this->renderPartial('index',array(
				'jspackage' => $this->getJspackageV3($this->openid),
				'shoppingdesc' => "【测试】九牧王棉服",
				'totalfee'=> sprintf("%.2f", floatval(1)/100),
				'compony' => $this->componyName,
		));
	}
	
	/**
	 * V3版本通知接口
	 */
	public function actionNoticeV3(){
		include_once Yii::app()->getExtensionPath().'/WxPayPubHelper.php';
		$notify = new Notify_pub(); //使用通用通知接口
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
		$notify->saveData($xml);
		//验证签名，并回应微信。如果微信收到商户的应答不是成功或超时，微信认为通知失败。
		if($notify->checkSign() == FALSE){
			$notify->setReturnParameter("return_code","FAIL");
			$notify->setReturnParameter("return_msg","签名失败");
		}else{
			$notify->setReturnParameter("return_code","SUCCESS");
		}
		$returnXml = $notify->returnXml();
		echo $returnXml;
		
		//微信支付响应的回调数据存入数据库的sys_wxpay_log表
		$this->wxpay_log(array('【V3测试】通知接口','notify通知信息',$xml));
		if($notify->checkSign() == TRUE){
			if ($notify->data["return_code"] == "FAIL") {
				$this->wxpay_log(array('【V3测试】通知接口','通信出错',$xml));
			}else if($notify->data["result_code"] == "FAIL"){
				$this->wxpay_log(array('【V3测试】通知接口','业务出错',$xml));
			}else{
				$this->wxpay_log(array('【V3测试】通知接口','支付成功',$xml));
			}
		}
	}
	/**
	 * V2、V3告警接口
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
		
		//=========步骤2：使用统一支付接口，获取prepay_id============
		//使用统一支付接口
		$unifiedOrder = new UnifiedOrder_pub();
		$unifiedOrder->setParameter("openid","$openid");//商品描述
		$unifiedOrder->setParameter("body","【测试】九牧王棉服");//商品描述
		//自定义订单号，此处仅作举例
		$timeStamp = time();
		$out_trade_no = $GLOBALS['accountinfo']['appid']."$timeStamp";
		$unifiedOrder->setParameter("out_trade_no","$out_trade_no");//商户订单号
		$unifiedOrder->setParameter("total_fee","1");//总金额
		$unifiedOrder->setParameter("notify_url",'http://wxpay.gamzer.cc/wxpaytest/default/noticev3/');//通知地址
		$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
		//非必填参数，商户可根据实际情况选填
		//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号
		//$unifiedOrder->setParameter("device_info","XXXX");//设备号
		//$unifiedOrder->setParameter("attach","XXXX");//附加数据
		//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
		//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间
		//$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记
		//$unifiedOrder->setParameter("openid","XXXX");//用户标识
		//$unifiedOrder->setParameter("product_id","XXXX");//商品ID
		$prepay_id = $unifiedOrder->getPrepayId();
		//3.使用jsapi调起支付
		$jsApi = new JsApi_pub();
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