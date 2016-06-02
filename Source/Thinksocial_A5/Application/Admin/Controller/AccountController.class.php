<?php

namespace Admin\Controller;

import('Vendor.qrcode.phpqrcode');
class AccountController  extends AdminController  {
    
    protected $token=null;//token
    protected $cookie=null;//cookie
    protected $fakeid=null;//fakeid
    protected $appid=null;
    protected $secret=null;
    public function	__init__(){
        $account=M("member_public")->select();
        $account=$account[0];
        $this->appid=$account['appid'];
        $this->secret=$account['secret'];
    }


    /**
     * 公众号信息
     *@author  碎月无晴 <906857431@qq.com>
     */
    public function account(){
        $id=intval(I("id"));
        if(empty($id)){
            $account=M("member_public")->find();
            if(empty($account)){
                $this->redirect("Account/addAccount");
            }else{
                $siteroot = rtrim(SITEROOT,"/");
                $this->assign('siteroot',$siteroot);
                $this->assign('account',$account);
                $this->assign('do','accountInfo');
                $this->display("Account/account");
            }
        }else{
            $account=I("account");
            $where['id']=$id;
            $result=M("member_public")->where($where)->save($account);
            if($result){
                $this->redirect("Account/account");
            }else{
                $this->error("修改公众号失败！");
            }
        }
    }
    /**
	 * 添加公众号
	 */
    public function addAccount(){
        $step=intval(I("step"));
        if($step==0){
            $step=1;
            $this->assign('step',$step);
            $this->display("Account/addAccount");
        }elseif($step==2){
            $name=I("name");
            $description=I("description");
            $this->assign('name',$name);
            $this->assign('description',$description);
            $this->assign('step',$step);
            $this->display("Account/addAccount");
        }elseif($step==3){
            $wxusername=trim(I("wxusername"));
            $wxpassword=trim(I("wxpassword"));
            $verify=trim(I("verify"));
            $loginStatus=$this->account_wexin_login($wxusername,$wxpassword,$verify);
            if($loginStatus==1){
                
                $basicinfo=$this->account_weixin_basic($wxusername);
                //下载头像和二维码 开始
                if (!empty($basicinfo['headimg'])) {
                    $filename="./Public/Admin/Images/headimg.jpg";
                    $result=file_put_contents($filename, $basicinfo['headimg']);
                }
                if (!empty($basicinfo['qrcode'])) {
                    $filename="./Public/Admin/Images/qrcode.jpg";
                    $result=file_put_contents($filename, $basicinfo['qrcode']);
                }
                //下载二维码 结束
                $siteroot = rtrim(SITEROOT);
                $account['headface_url']=$siteroot."Public/Admin/Images/headimg.jpg";
                $account['qrcode']=$siteroot."Public/Admin/Images/qrcode.jpg";
                $account['public_name'] = trim($basicinfo['name']);   //公众号名称
                $account['fakeid']=$this->fakeid;//公众号fakeid
                $account['token']=$this->token;//公众号token
                $account['account'] = trim($basicinfo['account']);    //登录帐号
                $account['public_id'] = trim($basicinfo['original']); //原始id
                $account['appid'] = trim($basicinfo['key']);//appid
                $account['secret'] = trim($basicinfo['secret']);//秘钥
                $account['username']=$wxusername;//登录帐号
                $account['password']=md5($wxpassword);//登录密码
                $account['description']=trim($basicinfo['signature']);//公众号描述
                $account['level']=trim($basicinfo['level']);//公众号类型
                $result=M("member_public")->add($account);
                if($result){
                   $this->assign('step',$step);
                   $this->assign('account',$account);
                   $this->display("Account/addAccount");
                }else{
                    $this->error("接入失败！");
                }
            }else{
                $this->error($loginStatus[1]);
            }
        }elseif($step==4){
           $account=I("account");
           $appid=trim($account['appid']);
           $where['appid']=$appid;
           $id=M("member_public")->where($where)->find();
           $id=$id['id'];
           $where['id']=$id;
           $result=M("member_public")->where($where)->save($account);
           if($result){
               $this->assign('name',$account['public_name']);
               $this->assign('step',$step);
               $this->display("Account/addAccount");
           }else{
               $this->error("修改失败！");
           }
        }
    }
    
    /**
     * 获取验证码
     * @param  登录帐号 $username
     * @return  验证码图片
     */
    public function account_wxcode(){
        $username=I("username");
        $response =D("MemberPublic")->request_get("https://mp.weixin.qq.com/cgi-bin/verifycode?username=$username");
        header("Content-Type:image/jpg; charset=UTF-8");
        echo $response;
        exit();
    }
    /**
     * 登录微信公众号
     * @param 登录帐号 $username
     * @param 密码 $password
     * @return 是否登录成功 1成功  else 错误信息
     */
    public function account_wexin_login($username,$password,$imgcode){
        $password=md5($password);
        $loginurl ='https://mp.weixin.qq.com/cgi-bin/login?lang=zh_CN';
        $post = array(
            'username' => $username,
            'pwd' => $password,
            'imgcode' => $imgcode,
            'f' => 'json',
        );
        $code_cookie =I('code_cookie');
        $response =D("MemberPublic")->ihttp_request($loginurl, $post, array('CURLOPT_REFERER' => 'https://mp.weixin.qq.com/', 'CURLOPT_COOKIE' => $code_cookie));
        $data = json_decode($response['content'], true);
        if ($data['base_resp']['ret'] == 0) {
            preg_match('/token=([0-9]+)/', $data['redirect_url'], $match);
            $this->token=$match[1];
            $this->cookie=implode('; ', $response['headers']['Set-Cookie']);
        }else {
    		switch ($data['ErrCode']) {
    			case "-1":
    				$msg = "系统错误，请稍候再试。";
    				break;
    			case "-2":
    				$msg = "微信公众帐号或密码错误。";
    				break;
    			case "-3":
    				$msg = "微信公众帐号密码错误，请重新输入。";
    				break;
    			case "-4":
    				$msg = "不存在该微信公众帐户。";
    				break;
    			case "-5":
    				$msg = "您的微信公众号目前处于访问受限状态。";
    				break;
    			case "-6":
    				$msg = "登录受限制，需要输入验证码，稍后再试！";
    				break;
    			case "-7":
    				$msg = "此微信公众号已绑定私人微信号，不可用于公众平台登录。";
    				break;
    			case "-8":
    				$msg = "微信公众帐号登录邮箱已存在。";
    				break;
    			case "-200":
    				$msg = "因您的微信公众号频繁提交虚假资料，该帐号被拒绝登录。";
    				break;
    			case "-94":
    				$msg = "请使用微信公众帐号邮箱登陆。";
    				break;
    			case "10":
    				$msg = "该公众会议号已经过期，无法再登录使用。";
    				break;
    			default:
    				$data['ErrCode'] = -2; 
    				$msg = "未知的返回。";
    		}
		    return array($data['ErrCode'], $msg);
        }
        return  true;
    }
    /**
     * 获取公众号基本信息
     * @param  登录帐号 $username
     * @return 公众号基本信息
     */
    public  function account_weixin_basic($wxusername) {
        $response =$this->account_weixin_http('https://mp.weixin.qq.com/cgi-bin/settingpage?t=setting/index&action=index&lang=zh_CN');
        $info = array();
        preg_match('/fakeid=([0-9]+)/', $response['content'], $match);
        $this->fakeid=$match[1];
        //获取头像
        $image =$this->account_weixin_http('https://mp.weixin.qq.com/misc/getheadimg?fakeid=' . $this->fakeid);
        if (!empty($image['content'])) {
            $info['headimg'] = $image['content'];
        }
        //获取二维码
        $image =$this-> account_weixin_http('https://mp.weixin.qq.com/misc/getqrcode?fakeid=' . $this->fakeid. '&style=1&action=download');
        if (!empty($image['content'])) {
            $info['qrcode'] = $image['content'];
        }
        
        /* header("content-type: image/jpg");
        print_R($image['content']); */
        
        preg_match('/(gh_[a-z0-9A-Z]+)/', $response['meta'], $match);
        $info['original'] = $match[1];
        preg_match('/名称([\s\S]+?)<\/li>/', $response['content'], $match);
        $info['name'] = trim(strip_tags($match[1]));
        preg_match('/微信号([\s\S]+?)<\/li>/', $response['content'], $match);
        $info['account'] = trim(strip_tags($match[1]));
        preg_match('/介绍([\s\S]+?)meta_content\">([\s\S]+?)<\/li>/', $response['content'], $match);
        $info['signature'] = trim(strip_tags($match[2]));
        preg_match('/认证情况([\s\S]+?)meta_content\">([\s\S]+?)<\/li>/', $response['content'], $match);
        $info['level_tmp'] = trim(strip_tags($match[2]));
        preg_match('/类型([\s\S]+?)meta_content\">([\s\S]+?)<\/li>/', $response['content'], $match);
        $info['type_temp'] = trim(strip_tags($match[2]));
        
    
        $info['level'] = 1;
        $is_key_secret = 1;
        if (strexists($info['type_temp'], '订阅号')) {
            if (strexists($info['level_tmp'], '微信认证')) {
                $info['level'] = 3;
            }
        } elseif (strexists($info['type_temp'], '服务号')) {
            $info['level'] = 2;
            if (strexists($info['level_tmp'], '微信认证')) {
                $info['level'] = 4;
            }
        }
        if ($is_key_secret == 1) {
            $authcontent =$this->account_weixin_http('https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&lang=zh_CN');
            preg_match_all("/value\:\"(.*?)\"/", $authcontent['content'], $match);
            $info['key'] = $match[1][2];
            $info['secret'] = $match[1][3];
            unset($match);
        }
        preg_match_all("/(?:country|province|city): '(.*?)'/", $response['content'], $match);
        $info['country'] = trim($match[1][0]);
        $info['province'] = trim($match[1][1]);
        $info['city'] = trim($match[1][2]);
        return $info;
    }
    
    /**
     * 模拟公众号登录
     * @param  登录帐号 $username
     * @param 路径 $url
     * @param string $post
     */
    public function account_weixin_http($url, $post = '') {
        $auth['token']=$this->token;
        $auth['cookie']=$this->cookie;
        return D("MemberPublic")->ihttp_request($url.'&token='.$auth['token'], $post, array('CURLOPT_COOKIE' => $auth['cookie'], 'CURLOPT_REFERER' =>'https://mp.weixin.qq.com/advanced/advanced?action=edit&t=advanced/edit&token='.$auth['token']));
    }
    public function test(){
        $longurl="http://sx.baguatan.cn";
        $appid=$this->appid;
        $secret=$this->secret;
        $accessToken=D("MemberPublic")->getToken($appid,$secret);
        $url = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token=".$accessToken;
        $send['action'] = 'long2short';
        $send['long_url'] = $longurl;
        $response = D("MemberPublic")->https_request($url, json_encode($send));
        echo $response;
        exit();
    }
    /**
     * 创建二维码
     */
    public function createQrcode(){
        $longurl=trim(I("longurl"));
        if(empty($longurl)){
            //显示页面
            $url="http://".$_SERVER['SERVER_NAME'];
            $this->assign('url',$url);
            $this->assign('do','qrcodeInfo');
            $this->display("Account/qrcode");
        }else{
            $appid=$this->appid;
            $secret=$this->secret;
            $accessToken=D("MemberPublic")->getToken($appid,$secret);
            $url = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token=".$accessToken;
            $send['action'] = 'long2short';
            $send['long_url'] = $longurl;
            $response = D("MemberPublic")->https_request($url, json_encode($send));
            echo $response;
            exit();
        }
    }
    /**
     * 显示二维码
     */
    public function showQrcode(){
        $url=trim(I("url"));
        if(empty($url)){
            //"http://".$_SERVER['SERVER_NAME']
            $url="http://".$_SERVER['SERVER_NAME'];
        }else{
            $url = str_replace('%lwx%','\/',$url);
            $url=str_replace("\\", '', $url);
        }
       
        $QRcode = new \QRcode();
        $errorCorrectionLevel = "L";
        $matrixPointSize = "5";
        $QRcode::png($url, false, $errorCorrectionLevel, $matrixPointSize);
        exit();
    }
    ////////////////////////////////////////////////二维码管理////////////////////////////////////////////////////////////////
    /*
     *  
     * 二维码列表
     */
    public function  qrcodeList(){
        $keyword=I('keyword');
        if($keyword){
            $where['name']=array('like','%'.$title.'%');
        }
        $qrcodeList=$this->lists("qrcode",$where,array('id'=>'desc'));
        print_R("qrcodeList:".$qrcodeList);
        $this->assign('list',$qrcodeList);
        $this->assign('where',$where);
        $this->display("Account/qr-list");
    }  
    
    public function qrcodeDetail(){
        $id=intval(I("id"));
        if(empty($id)){
           //添加二维码 
        }else{
            //编辑二维码
        }
        $this->display("Account/qr-post");
    }
    
    /*
     *  
     * 添加二维码
     */
    public function  qrcodePost(){
        $qrcode=I("qrcode");
        
        
    }
    
    public function qrcodeTest(){
        //expire_seconds 该二维码有效时间，以秒为单位。 最大不超过2592000（即30天），此字段如果不填，则默认有效期为30秒。
        //action_name  二维码类型，QR_SCENE为临时,QR_LIMIT_SCENE为永久,QR_LIMIT_STR_SCENE为永久的字符串参数值
        //scene_id 场景值ID，临时二维码时为32位非0整型，永久二维码时最大值为100000（目前参数只支持1--100000）
        
        $qrcodeInfols='{
                          "expire_seconds": 1800,
                          "action_name": "QR_SCENE",
                          "action_info": {
                          "scene": {
                                   "scene_id": 100000
                                  }
                           }
                     }';
        
       $qrcodeInfoyj='{
                            "action_name": "QR_LIMIT_SCENE",
                            "action_info": {
                                "scene": {
                                    "scene_id": 1000
                                }
                            }
                      }'; 
       
       $url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=TOKEN";
       //返回值说明 {"ticket":"gQH47joAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL2taZ2Z3TVRtNzJXV1Brb3ZhYmJJAAIEZ23sUwMEmm3sUw==",
                 //"expire_seconds":60,"url":"http:\/\/weixin.qq.com\/q\/kZgfwMTm72WWPkovabbI"}
                 //ticket  获取的二维码ticket，凭借此ticket可以在有效时间内换取二维码。
                 //expire_seconds 该二维码有效时间，以秒为单位。 最大不超过2592000（即30天）。
                 //url  二维码图片解析后的地址，开发者可根据该地址自行生成需要的二维码图片 
                 //
                  
        $getQrcodeUrl="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=TICKET";
        
        
    }
    /**
     * 常用服务接入
     */
    public function serviceIn(){
         $serviceList=M("service")->select();
         foreach($serviceList as &$item){
             if($item['status']==0){
                 $item['status']=' checked="checked"';
             }else{
                 $item['status']='';
             }
         }
         $this->assign('List',$serviceList); 
         $this->display("Account/service");
    }
    
    
    public function tests(){
        
        $arr1[0]="4";
        $arr1[1]="5";
        $arr1[2]="6";
        $arr=M("service")->field('id')->select();
        for($i=0,$len=count($arr);$i<$len;$i++){
            $arr2[$i]=$arr[$i]['id'];
        }
        for($i=0;$i<count($arr2);$i++){
            for($j=0;$j<count($arr1);$j++){
                if($arr2[$i]==$arr1[$j]){
                    $arr2[$i]="";
                }
            }
        }
        $count=count($arr2);
        for($i=0;$i<$count;$i++){
            if(empty($arr2[$i])){
                unset($arr2[$i]);
            }
        }
        shuffle($arr2);
          for($i=0,$len=count($arr2);$i<$len;$i++){
            $data['status']=1;
            $where['id']=$arr2[$i];
            $resultOff=M("service")->where($where)->save($data);
        } 
        print_R($arr2);
    }
    /**
     * 修改常用服务状态
     */
    public function changeService(){
        $arr1 = explode(',', I("id"));
        if(is_array($arr1)) {
            for($i=0,$len=count($arr1);$i<$len;$i++){
                //设置为on status=0
                $data['status']=0;
                $where['id']=$arr1[$i];
                $resultOn=M("service")->where($where)->save($data);
            }
         
            $arr=M("service")->field('id')->select();
            for($i=0,$len=count($arr);$i<$len;$i++){
                $arr2[$i]=$arr[$i]['id'];
            }
            for($i=0;$i<count($arr2);$i++){
                for($j=0;$j<count($arr1);$j++){
                    if($arr2[$i]==$arr1[$j]){
                        $arr2[$i]="";
                    }
                }
            }
             $count=count($arr2);
             for($i=0;$i<$count;$i++){
                 if(empty($arr2[$i])){
                     unset($arr2[$i]);
                 }
              }
              shuffle($arr2);
              for($i=0,$len=count($arr2);$i<$len;$i++){
                $data['status']=1;
                $where['id']=$arr2[$i];
                $resultOff=M("service")->where($where)->save($data);
            }  
            
        }
        exit();
    }
}
?>