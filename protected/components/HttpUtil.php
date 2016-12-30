<?php
/**
 * Http工具类
 *
 */
class HttpUtil {
    public static function postPage($url, $data=null) {
        $ch = curl_init();  // 启动一个CURL会话
        curl_setopt($ch, CURLOPT_POST, 1);  // 发送一个常规的Post请求
        curl_setopt($ch, CURLOPT_URL, $url);    // 要访问的地址
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
//        curl_setopt($ch, CURLOPT_PROXY, '203.195.200.113:8091');
//        curl_setopt($ch, CURLOPT_PROXY, "203.195.211.138"); //代理服务器地址
//        curl_setopt($ch, CURLOPT_PROXYPORT, 8091); //代理服务器端口
//        //curl_setopt($ch, CURLOPT_PROXYUSERPWD, ":"); //http代理认证帐号，username:password的格式
//        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
//        curl_setopt($ch, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
//        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)'); // 模拟用户使用的浏览器
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); // 设置超时限制防止死循环
//        curl_setopt($ch, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        if(!empty($data))
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//            'Content-Type: application/json; charset=utf-8',
//            'Content-Length: ' . strlen($data))
//        );
        ob_start();
        curl_exec($ch); // 执行操作
        if (curl_errno($ch)) {
            echo 'Errno' . curl_error($ch); //捕抓异常
        }
        $return_content = ob_get_contents();
        ob_end_clean();

        $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//        return array($return_code, $return_content);
        if ($return_code == 200) {
            Yii::log('Post数据返回内容：url='.$url.',return_content='.$return_content);
            return $return_content;
        } else {
            Yii::log('Post数据返回错误：url='.$url.',return_code='.$return_code);
            return '';
        }
    }

    public static function getPage($url) {
        Yii::log("HttpUtil.getPage==>" . $url);
        $ch = curl_init();  // 启动一个CURL会话
        curl_setopt($ch, CURLOPT_URL, $url);    // 要访问的地址
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        ob_start();
        curl_exec($ch); // 执行操作
        if (curl_errno($ch)) {
            echo 'Errno' . curl_error($ch); //捕抓异常
        }
        $return_content = ob_get_contents();
        ob_end_clean();
        $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//        return array($return_code, $return_content);
        if ($return_code == 200) {
            return $return_content;
        } else {
            return '';
        }
    }
}
