<?php
namespace Home\Controller;
class OrderController extends HomeController{
    public function _empty(){
        $action=array(
            'confirm','detail','express','list','op','pay','receive'
        );
        if(in_array(ACTION_NAME, $action)){
            /**
             * 处理订单
             */
            if(ACTION_NAME=='op'){
                include_once 'Application/Home/Controller/Shop/order/op.inc.php';
            }
            /**
             * 订单详情
             */
            if(ACTION_NAME=='detail'){
                include_once 'Application/Home/Controller/Shop/order/detail.inc.php';
            }
            /**
             * 订单支付
             */
            if(ACTION_NAME=='pay'){
                include_once 'Application/Home/Controller/Shop/order/pay.inc.php';
            }
            /**
             * 确认订单
             */
            if(ACTION_NAME=='confirm'){
                include_once 'Application/Home/Controller/Shop/order/confirm.inc.php';
            }
            
            /**
             * 全部订单
             */
            if(ACTION_NAME=='list'){
                include_once 'Application/Home/Controller/Shop/order/list.inc.php';
            }
            
            /**
             * 物流信息
             */
            if(ACTION_NAME == 'express'){
                include_once 'Application/Home/Controller/Shop/order/express.inc.php';
            }
            $this->assign($array);
            $this->display('Shop/default/order/'.strtolower(ACTION_NAME));
        }else{
            if(IS_AJAX){
                show_json(1);
            }
            $this->redirect('Order/list');
        }
    }
    
    /**
     * jssdk函数
     * @param unknown $appId
     * @param unknown $appSecret
     */
    protected function getSignPackage() {
        $jsapiTicket = $this->getJsApiTicket();
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $timestamp = time();
        $nonceStr = $this->createNonceStr();
    
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
    
        $signature = sha1($string);
    
        $signPackage = array(
            "appId"     => $this->appId,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }
    
    protected function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    
    protected function getJsApiTicket() {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode(file_get_contents("jsapi_ticket.json"));
        if ($data->expire_time < time()) {
            $accessToken = $this->getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
            $res = json_decode($this->httpGet($url));
            $ticket = $res->ticket;
            if ($ticket) {
                $data->expire_time = time() + 7000;
                $data->jsapi_ticket = $ticket;
                $fp = fopen("jsapi_ticket.json", "w");
                fwrite($fp, json_encode($data));
                fclose($fp);
            }
        } else {
            $ticket = $data->jsapi_ticket;
        }
    
        return $ticket;
    }
    
    protected function getAccessToken() {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
        $res = json_decode($this->httpGet($url));
        $access_token = $res->access_token;
        return $access_token;
    }
}