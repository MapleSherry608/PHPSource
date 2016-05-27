<?php
//---------------------------------
//开发: 小曾
//扣扣: 839024615
//本程序只作为测试学习使用
//正式版请登陆pay.php127.com
//---------------------------------

include_once 'Class/weixin/lib/WxPay.JsApiPay.php';
include_once 'Class/weixin/lib/WxPay.Notify.php';

include_once 'Class/weixin/lib/WxPay.Api.php';
include_once 'Class/weixin/lib/WxPay.MicroPay.php';

require_once "Class/weixin/JsSDK/jssdk.php";

class AdminAction extends Action {
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
        
        $this->assign('appid',WX_APPID);
        $this->assign('time',time());
        $this->assign('string',MD5(time()));
        
        //这里是JsSDK
        $jssdk = new JSSDK(WX_APPID,WX_APPSECRET);
        $signPackage = $jssdk->getSignPackage();
        $this->assign('signPackage',$signPackage);
        //$jssdk->aa();
    }

    //微信端后台
    public function index(){
        if(MXPAY_DEBUG){
            session('wx_admin',111);
        }
        if(!session('?wx_admin')){
            $tools = new JsApiPay();
            $openId_type = 'snsapi_userinfo';
            $user = $tools->GetOpenid($openId_type);
            $openId = $user['openid'];
            $wx_admin_openid=S('wx_admin_openid');
            $arr=explode(";",$wx_admin_openid);
            if(in_array($openId,$arr)){
                session('wx_admin',$openId);
                $this->display();
            }else{
                $this->error('对不起,你没有权限!');
            }
        }else{
            $this->display();
        }
    }
    public function scanning(){
        if(IS_POST){
            $auth_code = I('post.auth_code');
            $pay_shop = '微信端扫码收款';
            $pay_fee = I('post.pay_fee')*100;
            if($pay_fee==0){
                $this->error('请输入金额');
            }
            
            $out_trade_no="M".Date('YmdHis').rand(1000,9999);
            
            $input = new WxPayMicroPay();
            $input->SetAuth_code($auth_code);
            $input->SetBody($pay_shop);
            $input->SetTotal_fee($pay_fee);
            $input->SetOut_trade_no($out_trade_no);

            
            $microPay = new MicroPay();
            $data=$microPay->pay($input);
            if($data['result_code']=="SUCCESS"){
                $data['pay_shop'] = $pay_shop;                
                $data['pay_type'] = 'weixin-scanning';
                $data['pay_price'] = $data['total_fee'];
                $data['s_time']=time();
                $data['f_time']=time();
                $data['ok']=1;
                $data['out_trade_no']=$out_trade_no;
                $data['trade_no']=$data['transaction_id'];
                if($id=M('order')->add($data)){
                    $this->success("收款成功");
                }else{
                    $this->error('收款成功,订单写入失败');
                }
            }
        }else{
            $this->display();
        }
    }
}

