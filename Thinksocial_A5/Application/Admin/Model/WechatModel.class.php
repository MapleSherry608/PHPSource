<?php
namespace Admin\Model;
use Think\Model;
/**
 */
class WechatModel  {
    /**
     * 发送自定义的模板消息
     * @param $touser 微信用户的openid
     * @param $template_id 模版消息id
     * @param $url  跳转路径
     * @param $data 模版内容
     * @param string $topcolor 模版字体颜色
     * @return bool 返回发送状态
     */
    function doSendMessage($touser, $template_id, $redirectUrl, $data, $topcolor = '#7B68EE',$appid,$secrect){
        $template = array(
        				'touser' => $touser,
        				'template_id' => $template_id,
        				'url' => $redirectUrl,
        				'topcolor' => $topcolor,
        				'data' => $data
        );
        $json_template =json_encode($template);
        $accesstoken=$this->getAccessToken($appid,$secrect);
        $url ="https://api.weixin. qq.com/cgi-bin/message/template/send?access_token=".$accesstoken;
        $dataRes =$this->request_post($url, urldecode($json_template));
         if ($dataRes['errcode'] == 0) {
            return true;
             
        } else {
            return false;
        }
        $text =json_encode($template);
        return $text;
    }
    /**
     * 获取Accesstoken 用于模版消息
     * @param $appid 公众号appid
     * @param $secrect 公众号secrect
     */
    function getAccessToken($appid,$secrect){
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secrect;
        $json=file_get_contents($url);
        $result=json_decode($json);
        $access_token=$result->access_token;
        return $access_token;
    }
    /**
     * 发送get请求,返回数据
     * @param string $url
     * @return bool|mixed
     */
    function request_get($url = ''){
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
    /**
     * 发送post请求
     * @param string $url
     * @param string $param
     * @return bool|mixed
     */
    function request_post($url = '', $param = '')
    {
        if (empty($url) || empty($param)) {
            return false;
        }
        $ch = curl_init(); //初始化curl
        curl_setopt($ch, CURLOPT_URL, $url); //抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $data = curl_exec($ch); //运行curl
        curl_close($ch);
        return $data;
    }
    /**
     * 获取accessToken 
     * @param   $appid 公众号appid
     * @param   $secrect 公众号secret
     */
    function getToken($appid,$secrect){
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secrect;
        $token = $this->request_get($url);
        $token = json_decode(stripslashes($token));
        $arr = json_decode(json_encode($token), true);
        $access_token = $arr['access_token'];
        return $access_token;
    }
}
?>
