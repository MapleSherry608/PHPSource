<?php
//---------------------------------
//开发: 小曾
//扣扣: 839024615
//本程序只作为测试学习使用
//正式版请登陆pay.php127.com
//---------------------------------

include_once 'Class/weixin/lib/WxPay.JsApiPay.php';
include_once 'Class/weixin/lib/WxPay.Notify.php';

class WeixinAction extends Action {
    public function  _initialize() {
        $where['class'] = 'weixin';
        $F=M('pay')->where($where)->find();
        $cof=unserialize($F['val']);
        
        header("Content-Type:text/html;charset=utf-8");
        
        define('WX_APPID',$cof['AppId']['val']);
        define('WX_MCHID',$cof['mch_id']['val']);
        define('WX_KEY',$cof['Key']['val']);
        define('WX_APPSECRET',$cof['AppSecret']['val']);
        define('WX_SSLCERT_PATH','http://'.$_SERVER['HTTP_HOST'].'/Class/weixin/cert/apiclient_cert.pem');
        define('WX_SSLCERT_PATH','http://'.$_SERVER['HTTP_HOST'].'./Class/weixin/cert/apiclient_key.pem');
        
        //echo WxPayConfig::SSLCERT_PATH; exit;
    }

    //提交请求
    public function start(){
        if(IS_GET){
            
            $id=I('get.id');
            $where['id']=$id;
            $F=M('order')->where($where)->find();
            if(!$F){ $this->error('订单号不存在'); }
            
            //①、获取用户openid
            $tools = new JsApiPay();
             if(C('wx_user')){ 
                 $isuser = 'snsapi_userinfo';
             }else{
                 $isuser = 'snsapi_base';
             }
            $arr = $tools->GetOpenid($isuser);
            $openId = $arr['openid'];
            if(C('wx_user')){
                //取用户资料
                $url2='https://api.weixin.qq.com/sns/userinfo?access_token='.$arr['access_token'].'&openid='.$arr['openid'].'&lang=zh_CN';
                $info = $this->json_curl($url2);
                $where2['openid']=$openId;
                $db=M('wx_user');
                if(!$user=$db->where($where2)->find()){
                    $info['time'] = time();
                    $db->add($info);
                }
            }
            //②、统一下单
            $notify_url = 'http://'.$_SERVER['HTTP_HOST'].U("notify");
            
            if($F['pay_shop']){
                $pay_shop=$F['pay_shop'];
            }else{
                $pay_shop=C('name');
            }
            
            $input = new WxPayUnifiedOrder();
            $input->SetBody($pay_shop); //商品描述
            $input->SetAttach($pay_shop); //附加数据
            $input->SetOut_trade_no($F['out_trade_no']);//商户订单号
            $input->SetTotal_fee($F['pay_price']); //金额
            $input->SetTime_start(date("YmdHis")); //交易起始时间
            $input->SetTime_expire(date("YmdHis", time() + 600)); //交易起始时间
            $input->SetGoods_tag("test"); //商品标记
            $input->SetNotify_url($notify_url); //通知地址
            $input->SetTrade_type("JSAPI");
            $input->SetOpenid($openId);
            //echo 1;exit;
            $order = WxPayApi::unifiedOrder($input);
            
            $jsApiParameters = $tools->GetJsApiParameters($order);
            //获取共享收货地址js函数参数
            $editAddress = $tools->GetEditAddressParameters();
            
            //print_r($jsApiParameters);exit;
            echo "<script type='text/javascript'>
            //调用微信JS api 支付
            function jsApiCall() {
                WeixinJSBridge.invoke('getBrandWCPayRequest', {$jsApiParameters} , function(res) {
                    WeixinJSBridge.log(res.err_msg);
                     if(res.err_msg == 'get_brand_wcpay_request:ok') { 
                        alert('支付成功');WeixinJSBridge.call('closeWindow');
                     }else if(res.err_msg == 'get_brand_wcpay_request:cancel'){
                        alert('您取消了支付');WeixinJSBridge.call('closeWindow');
                     }else{
                        alert('支付失败 错误代码 '+res.err_msg);WeixinJSBridge.call('closeWindow');
                     }
                });
            }
            function callpay() {
                if (typeof WeixinJSBridge == 'undefined') {
                    if (document.addEventListener) {
                        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                    } else if (document.attachEvent) {
                        document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                    }
                } else {
                    jsApiCall();
                }
            }
            callpay();
            </script>";
            
        }else{
            $this->error('非法操作');
        }
    }
    public function notify(){
        //初始化日志 
        get_log("微信支付");
        $notify = new PayNotifyCallBack;
        $data=$notify->Handle(false);
    }
    //curl请求
    public function json_curl($url){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_URL, $url);
			$json =  curl_exec($ch);
			curl_close($ch);
			return json_decode($json,1);
	}
    
}

class PayNotifyCallBack extends WxPayNotify{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		$notfiyOutput = array();
		//get_log(json_encode($data));
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
            get_log("输入参数不正确");
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
            get_log("订单查询失败");
			return false;
		}
        
        //处理订单状态
        $db=M('order');
        $where['out_trade_no']=$data['out_trade_no'];
        $where['pay_price']=$data['total_fee'];
        $where['ok']=0;
        if(!$db->where($where)->find()){
            $msg = "订单不存在";
            get_log("订单不存在");
			return false;
        }
        $data['trade_no']=$data['transaction_id'];
        $data['f_time']=time();
        $data['ok'] = 1;
        if(!$db->where($where)->save($data)){
            if(C('wx_user')){
                $score=C('wx_score')*($data['total_fee']/100);
                M('wx_user')->where("openid='".$data['openid']."'")->setInc('score',$score);
            }
            $msg = "订单状态更新失败";
            get_log("订单状态更新失败");
			return false;
        }
        get_log("ok");
		return true;
	}
}

