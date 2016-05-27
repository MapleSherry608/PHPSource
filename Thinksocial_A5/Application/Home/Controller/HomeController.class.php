<?php
namespace Home\Controller;
use Think\Controller;
class HomeController extends Controller {
    protected $appId;
    protected $appSecret;
    protected $account_name;
    protected $openid;
	/**
     * 前台控制器初始化
     */
    protected function _initialize(){
        $account=M("member_public")->field('appid,secret,public_name')->find();
        $this->appId=$account['appid'];
        $this->appSecret=$account['secret'];
        $this->account_name=$account['public_name'];
        $this->openid=$this->getOpenid();
        $result=weixin_memb_login();
        if($result==-1){
            //这里重定向到用户登入注册页面 或依照权限可供进入
        }
		//自动加载数据库配置文件
		$config =   S('DB_CONFIG_DATA');
        if(!$config){
            $config =   api('Config/lists');
            S('DB_CONFIG_DATA',$config);
        }
        C($config);//添加配置
		// 获取当前用户ID
        define('MEMBID',is_login());
        if(!MEMBID){// 还没登录 跳转到登录页面
            //$this->redirect('Public/login');
        }
        $this->assign("openid",$this->openid);
		if(method_exists($this,'__init__'))$this->__init__();
    }
	/**
	 * 检查用户登入
	 * （调用该方法以后就会造成必须登入以后才能查看的现象）
	 */
	protected function checkauth($inapi=true){
		$mid=is_login();
		if($mid) { 
			return true;
		}
		$openid=session('OPENID');

		if(!empty($openid)) {
			$result=weixin_memb_login();
			if($result>0) {
				$forward = base64_encode($_SERVER['QUERY_STRING']);
				return true;
			}
			if ($inapi) {//此处后期更改为跳转到登入页面去，在开发完框架以后改！
				$this->error("该系统必须接入微信才可以使用,请接入微信！",null,12);
			}
		}else{
			if ($inapi) {
				$this->error("该系统必须接入微信才可以使用,请接入微信！",null,12);
			}
		}
		return true;
	}
    
    public function getOpenid(){ 
        $openid=I('openid');
        $openid=empty($openid)?I('openId'):$openid;
    	if(empty($openid)){
    	     $openid=session('OPENID'); 
    	}
        if(empty($openid)){
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            if (strpos($user_agent, 'MicroMessenger') === false) {
                return false;
            } else {
                $account=M("member_public")->field('appid,secret')->find();
                $appid=$account['appid'];
                $secret=$account['secret'];
                $code=$_REQUEST["code"];
                if(empty($code)){
                    $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
                    $callback = urlencode($url);
                    $state = 1;
                    $forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$callback}&response_type=code&scope=snsapi_base&state={$state}#wechat_redirect";
                    header('location: ' . $forward);
                    exit();
                }
                $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL,$get_token_url);
                curl_setopt($ch,CURLOPT_HEADER,0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                $json_obj = curl_exec($ch);
                curl_close($ch);
                $json_obj = json_decode($json_obj,true);
                $access_token = $json_obj['access_token'];
                $refresh_token = $json_obj['refresh_token'];
                $openid = $json_obj['openid'];
                //opendi存到fans表--判断是否存在，不存在添加，存在就不添加
                if(!empty($openid)){
                    $fan=M('member_fans')->where(array('openid'=>$openid))->find();
                    if(empty($fan)){
                        $access_token_ = $this->getAccessToken();
                        //获取用户信息
                        $get_user_info_url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token_.'&openid='.$openid.'&lang=zh_CN';
                        $user_obj = $this->request_get($get_user_info_url);
                        $user_obj = json_decode($user_obj, true);
                        //解析json
                        $nickname=$user_obj['nickname'];
                        $avatar=$user_obj['headimgurl'];
                        $membmodel=api('Member/Member/getModel');
                        $uid=$membmodel->register($nickname,md5($openid),md5($openid).'@baguatan.com','',$avatar,$openid);
                        if($uid>=0){
                            //添加到fans
                            $fans['membid']=$uid;
                            $fans['openid']=strval($openid);
                            $fans['nickname']=$nickname;
                            $fans['groupid']=0;
                            $fans['follow']=1;
                            $fans['followtime']=time();
                            $result=M("member_fans")->add($fans);
                        }
                    }
    				session('OPENID',$openid);//oDcX5svnpYbIHx3qo57NfElBaRnM
                }
            }
        }else{
            $fan=M('member_fans')->where(array('openid'=>$openid))->find();
        	   if(empty($fan)){
        	       $access_token_ = $this->getAccessToken();
        	       //获取用户信息
        	       $get_user_info_url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token_.'&openid='.$openid.'&lang=zh_CN';
        	       $user_obj = $this->request_get($get_user_info_url);
        	       $user_obj = json_decode($user_obj, true);
        	       //解析json
        	       $nickname=$user_obj['nickname'];
        	       $avatar=$user_obj['headimgurl'];
        	       $membmodel=api('Member/Member/getModel');
        	       $uid=$membmodel->register($nickname,md5($openid),md5($openid).'@baguatan.com','',$avatar,$openid);
        	       if($uid>=0){
        	           //添加到fans
        	           $fans['membid']=$uid;
        	           $fans['openid']=strval($openid);
        	           $fans['nickname']=$nickname;
        	           $fans['groupid']=0;
        	           $fans['follow']=1;
        	           $fans['followtime']=time();
        	           $result=M("member_fans")->add($fans);
        	       }
        	   }
    	   session('OPENID',$openid);
        }
        return $openid;
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
    
    // 发送get请求
    protected function request_get($url = '')
    {
        if (empty($url)) {
            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    protected function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_URL, $url);
    
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }
    /**
     * 支付页面
     * @param unknown $params
     * @param unknown $mine
     */
    public function optionPay($params=array()){
        if(!IS_MOBILE) {//判断是否手机端访问
            $this->error('支付功能只能在手机上使用');
        } 
        
        if (!MEMBID) {//判断用户是否登录
            header('location: ' . 'http://mp.weixin.qq.com/s?__biz=MzAxNDc2NjkwMg==&mid=402034274&idx=1&sn=0be282d856c135dfb2deb9bd25831231#rd');
        }
        $alipay=C("ALIPAY");//支付宝支付
        $wechat=C("WECHAT");//微信支付
        $credit=C("CREDIT");//余额支付
        if((empty($alipay)||empty($alipay['switch']))&&(empty($wechat)||empty($wechat['switch']))&&(empty($credit)||empty($credit['switch']))) {
            $this->error('没有有效的支付方式, 请联系网站管理员.');
        }
        
        if($params['fee'] <= 0) {
            $this->error('支付金额不能小于零');
        }
        
        $pars = array('tid'=>$params['ordersn']);
        $log=M('core_paylog')->where($pars)->find();
        if(empty($log)){
            $pars=array(
                'openid'=>$params['user'],
                'tid'=>$params['ordersn'],
                'fee'=>$params['fee'],
                'status'=>0,
                'module'=>CONTROLLER_NAME,
                'createtime'=>strtotime(date('Y-m-d H:i:s'))
            );
            $params['plid'] = M("CorePaylog")->add($pars);
        }else{
            $params['plid']=$log['plid'];
            if($log['status'] == 1) {
                $this->error('这个订单已经支付成功, 不需要重复支付');
            }
        }
        $params['account_name']=$this->account_name;
        $pay_param = base64_encode(json_encode($params));
        $this->assign("alipay",$alipay);
        $this->assign("wechat",$wechat);
        $this->assign("credit",$credit);
        $this->assign("params",$params);
        $this->assign("pay_param",$pay_param);
        $this->display("Public/paycenter");
    }
    
    /**
     * 支付宝支付
     */
    public function pay_option(){
        $pars=I('pay_param');
        $option=I('option');
        $params = @json_decode(base64_decode($pars), true);
        if(!empty($option)&&!empty($params['ordersn'])){
            M('CorePaylog')->where(array('tid'=>$params['ordersn']))->save(array('type'=>$option));
        }
        if($option=='alipay'){
            $alipay=C("ALIPAY");//支付宝支付
            $ps = array();
            $ps['ordersn'] = $params['ordersn'];
            $ps['type'] = $option;
            $ps['fee'] = $params['fee'];
            $ps['title'] = $params['title'];
            $ret = $this->alipay_build($ps, $alipay);
            if($ret['url']) {
                $tmpl=C('TMPL_PARSE_STRING');
                echo '<script type="text/javascript" src="'.$tmpl['__HOME_JS__'].'/alipay.js"></script><script type="text/javascript">_AP.pay("'.$ret['url'].'","'.SITEROOT.'")</script>';
                exit();
            }
        }
        if($option=='wechat'){
            $wechat =C("WECHAT");
            $wechat['appid'] = $this->appId;
            $wechat['secret'] = $this->appSecret;
            $ps = array();
            $ps['ordersn'] = $params['ordersn'];
            $ps['type'] = $option;
            $ps['fee'] = $params['fee'];
            $ps['title'] = $params['title'];
            $wOpt = $this->wechat_build($ps, $wechat);
            if (is_error($wOpt)) {
                if ($wOpt['message'] == 'invalid out_trade_no') {
                    $id = date('YmdH');
                    pdo_update('core_paylog', array('plid' => $id), array('plid' => $log['plid']));
                    pdo_query("ALTER TABLE ".tablename('core_paylog')." auto_increment = ".($id+1).";");
                    $this->error("抱歉，发起支付失败，系统已经修复此问题，请重新尝试支付。");
                }
                $this->error("抱歉，发起支付失败，具体原因为：“{$wOpt['errno']}:{$wOpt['message']}”。请及时联系站点管理员。");
                exit;
            }
            echo "
                <script type='text/javascript'>
                document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
                    WeixinJSBridge.invoke('getBrandWCPayRequest', {
                        'appId' : '".$wOpt['appId']."',
                        'timeStamp': '".$wOpt['timeStamp']."',
                        'nonceStr' : '".$wOpt['nonceStr']."',
                        'package' : '".$wOpt['package']."',
                        'signType' : '".$wOpt['signType']."',
                        'paySign' : '".$wOpt['paySign']."'
                    }, function(res) {
                        if(res.err_msg == 'get_brand_wcpay_request:ok') {
                           location.href='".SITEROOT."index.php?module=Home&controller=Payment&action=wechat_return&ps=".base64_encode(json_encode($ps))."'
                        } else {
                           history.go(-1);
                            //alert('启动微信支付失败, 请检查你的支付参数. 详细错误为: ' + res.err_msg);
                        }
                    });
                }, false);
                </script>";
            exit();
        }
    }
    /**
     * 支付宝支付
     * @param unknown $params 支付参数
     * @param unknown $alipay 支付宝配置参数
     * @return multitype:NULL
     */
    private function alipay_build($params, $alipay = array()) {
    	$set = array();
    	$set['notify_url'] = SITEROOT."index.php/Home/Payment/alipay_notify";
    	$set['service'] = 'alipay.wap.create.direct.pay.by.user';
    	$set['partner'] = $alipay['partner'];
    	$set['_input_charset'] = 'utf-8';
    	$set['sign_type'] = 'MD5';
    	$set['return_url'] =SITEROOT."index.php?module=home&controller=Payment&action=alipay_return";
    	$set['out_trade_no'] = $params['ordersn'];
    	$set['subject'] = $params['title'];
    	$set['total_fee'] = $params['fee'];
    	$set['seller_id'] = $alipay['account'];
    	$set['payment_type'] = 1;
    	$set['body'] = $params['type'];
    	$prepares = array();
    	foreach($set as $key => $value) {
    		if($key != 'sign' && $key != 'sign_type') {
    			$prepares[] = "{$key}={$value}";
    		}
    	}
    	sort($prepares);	
    	$string = implode($prepares, '&');	
    	$string .= $alipay['secret'];	
    	$set['sign'] = md5($string);
    	$url_str=ALIPAY_GATEWAY . '?' . http_build_query($set, '', '&');
    	$response = ihttp_request($url_str);
    	return array('url' => $response['headers']['Location']);
    }
    /**
     * 微信支付
     * @param unknown $params 支付参数
     * @param unknown $wechat 配置参数
     * @return multitype:string number |Ambigous <multitype:unknown string , multitype:unknown string Ambigous <> Ambigous <boolean, string> >|multitype:unknown string |multitype:string unknown number
     */
    protected function wechat_build($params, $wechat) {	
        $wOpt = array();	
        $package['appid'] = $wechat['appid'];		
        $package['mch_id'] = $wechat['mchid'];		
        $package['nonce_str'] = random(8);		
        $package['body'] = $params['type'];		
        $package['out_trade_no'] = $params['ordersn'];
        $package['total_fee'] = $params['fee'] * 100;		
        $package['spbill_create_ip'] = CLIENT_IP;		
        $package['time_start'] = time();
        $package['time_expire'] = date('YmdHis', time() + 600);		
        $package['notify_url'] = SITEROOT."index.php/Home/Payment/wechat_notify";
        $package['trade_type'] = 'JSAPI';		
        $package['openid'] = $this->openid;
        ksort($package, SORT_STRING);		
        $string1 = '';		
        foreach($package as $key => $v) {			
            if (empty($v)) {
                continue;
            }
            $string1 .= "{$key}={$v}&";		
        }		
        $string1 .= "key={$wechat['apikey']}";		
        $package['sign'] = strtoupper(md5($string1));	
        $dat = array2xml($package);	
        $response = ihttp_request('https://api.mch.weixin.qq.com/pay/unifiedorder', $dat);		
        if (is_error($response)) {	
            return $response;
        }
        $xml = @simplexml_load_string($response['content'], 'SimpleXMLElement', LIBXML_NOCDATA);
        if (strval($xml->return_code) == 'FAIL') {	
            return error(-1, strval($xml->return_msg));	
    	}		
    	if (strval($xml->result_code) == 'FAIL') {			
    	    return error(-1, strval($xml->err_code).': '.strval($xml->err_code_des));	
    	}		
    	$prepayid = $xml->prepay_id;		
    	$wOpt['appId'] = $wechat['appid'];		
    	$wOpt['timeStamp'] = time();		
    	$wOpt['nonceStr'] = random(8);		
    	$wOpt['package'] = 'prepay_id='.$prepayid;		
    	$wOpt['signType'] = 'MD5';		
    	ksort($wOpt, SORT_STRING);		
    	foreach($wOpt as $key => $v) {			
    	    $string .= "{$key}={$v}&";		
    	}		
    	$string .= "key={$wechat['apikey']}";		
    	$wOpt['paySign'] = strtoupper(md5($string));
    	return $wOpt;	
    }
    
    //插入一段字符串
    private function str_insert($str, $i, $substr)
    {
        for($j=0; $j<$i; $j++){
            $startstr .= $str[$j];
        }
        for ($j=$i; $j<strlen($str); $j++){
            $laststr .= $str[$j];
        }
        $str = ($startstr . $substr . $laststr);
        return $str;
    }
}