<?php
namespace Admin\Model;
use Think\Model;
/**
 */
class MemberPublicModel  extends Model{
    /**
     * 授权登录
     * @param   $appid 公众号appid
     * @param   $secret 公众号secret
     */
    public function getOpenid($appid,$secret){
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
        $json_obj=$this->request_get($get_token_url);
        $access_token = $json_obj['access_token'];
        $openid = $json_obj['openid'];
    }
    /**
     * 根据openid获取用户信息
     * @param  $openid 微信用户的openid
     */
    public function getUserInfo($appid,$secrect,$openid){
        $accessToken=$this->getToken($appid,$secrect);;
        //获取用户信息
        $get_user_info_url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$accessToken.'&openid='.$openid.'&lang=zh_CN';
        $user_obj = $this->request_get($get_user_info_url);
        $user_obj = json_decode($user_obj, true);
        return $user_obj;
    }
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
        $accesstoken=$this->getToken($appid,$secrect);
        $url ="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$accesstoken;
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
    		$postUrl = $url;
    		$curlPost = $param;
    		$ch = curl_init(); //初始化curl
    		curl_setopt($ch, CURLOPT_URL, $postUrl); //抓取指定网页
    		curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
    		curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
    		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
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
    
   /**
    * 格式化路径
    * @param  数组 $code
    */
    private function dotrans($code)
    {
        return preg_replace("#\\\u([0-9a-f]+)#ie", "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))",$code);
    }
    
    
    /**
     * 获取自定义菜单数据，转为json数组
     * @return 返回json类型数据
     */
    public function getJsonMenu(){
        header("Content-Type: text/html;charset=utf-8");
        $menu=M("custom_menu")->select();
        $menu=D('Tree')->toTree($menu);
        $set = array();
        $set['button'] = array();
        foreach ($menu as $key=>$value){
            $entry = array();
            $entry['name'] = $value['title'];
            if(empty($value['_child'])){
                $entry['type']=$value['type'];
                $entry['url']=$value['url'];
            }else{
                foreach ($value['_child'] as $k=>$v){
                    $e = array();
                    $e['type'] = $v['type'];
                    $e['name'] = $v['title'];
                    //跳转路径
                    if($v['type']== 'view'){
                        $e['url'] =  $v['url'];
                        //触发关键字
                    }else{
                        $e['key'] = $v['keyword'];
                    }
                    $entry['sub_button'][] = $e;
                }
            }
            $set['button'][] = $entry;
        }
        $dat =json_encode($set);
        $dat = $this->dotrans($dat);
        return $dat;
    }
    
    /**
     * 绑定自定义菜单
     * @param 路径 $url
     * @param 数据 $data
     * @return 绑定结果
     */
    public function https_request($url,$data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
    
    
    function ihttp_request($url, $post = '', $extra = array(), $timeout = 60) {
        $urlset = parse_url($url);
        if (empty($urlset['path'])) {
            $urlset['path'] = '/';
        }
        if (!empty($urlset['query'])) {
            $urlset['query'] = "?{$urlset['query']}";
        }
        if (empty($urlset['port'])) {
            $urlset['port'] = $urlset['scheme'] == 'https' ? '443' : '80';
        }
        if (strexists($url, 'https://') && !extension_loaded('openssl')) {
            if (!extension_loaded("openssl")) {
                print_R('请开启您PHP环境的openssl');
            }
        }
        if (function_exists('curl_init') && function_exists('curl_exec')) {
            $ch = curl_init();
            if (ver_compare(phpversion(), '5.6') >= 0) {
                curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
            }
            if (!empty($extra['ip'])) {
                $extra['Host'] = $urlset['host'];
                $urlset['host'] = $extra['ip'];
                unset($extra['ip']);
            }
            curl_setopt($ch, CURLOPT_URL, $urlset['scheme'] . '://' . $urlset['host'] . ($urlset['port'] == '80' ? '' : ':' . $urlset['port']) . $urlset['path'] . $urlset['query']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
            if ($post) {
                if (is_array($post)) {
                    $filepost = false;
                    foreach ($post as $name => $value) {
                        if (substr($value, 0, 1) == '@' || (class_exists('CURLFile') && $value instanceof CURLFile)) {
                            $filepost = true;
                            break;
                        }
                    }
                    if (!$filepost) {
                        $post = http_build_query($post);
                    }
                }
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            }
            /*          if (!empty($GLOBALS['_W']['config']['setting']['proxy'])) {
             $urls = parse_url($GLOBALS['_W']['config']['setting']['proxy']['host']);
             curl_setopt($ch, CURLOPT_PROXY, "{$urls['host']}:{$urls['port']}");
             $proxytype = 'CURLPROXY_' . strtoupper($urls['scheme']);
             if (!empty($urls['scheme']) && defined($proxytype)) {
             curl_setopt($ch, CURLOPT_PROXYTYPE, constant($proxytype));
             } else {
             curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
             curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
             }
             if (!empty($GLOBALS['_W']['config']['setting']['proxy']['auth'])) {
             curl_setopt($ch, CURLOPT_PROXYUSERPWD, $GLOBALS['_W']['config']['setting']['proxy']['auth']);
             }
             } */
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSLVERSION, 1);
            if (defined('CURL_SSLVERSION_TLSv1')) {
                curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
            }
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1');
            if (!empty($extra) && is_array($extra)) {
                $headers = array();
                foreach ($extra as $opt => $value) {
                    if (strexists($opt, 'CURLOPT_')) {
                        curl_setopt($ch, constant($opt), $value);
                    } elseif (is_numeric($opt)) {
                        curl_setopt($ch, $opt, $value);
                    } else {
                        $headers[] = "{$opt}: {$value}";
                    }
                }
                if (!empty($headers)) {
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                }
            }
            $data = curl_exec($ch);
            $status = curl_getinfo($ch);
            $errno = curl_errno($ch);
            $error = curl_error($ch);
            curl_close($ch);
            if ($errno || empty($data)) {
                return error(1, $error);
            } else {
                return $this->ihttp_response_parse($data);
            }
        }
        $method = empty($post) ? 'GET' : 'POST';
        $fdata = "{$method} {$urlset['path']}{$urlset['query']} HTTP/1.1\r\n";
        $fdata .= "Host: {$urlset['host']}\r\n";
        if (function_exists('gzdecode')) {
            $fdata .= "Accept-Encoding: gzip, deflate\r\n";
        }
        $fdata .= "Connection: close\r\n";
        if (!empty($extra) && is_array($extra)) {
            foreach ($extra as $opt => $value) {
                if (!strexists($opt, 'CURLOPT_')) {
                    $fdata .= "{$opt}: {$value}\r\n";
                }
            }
        }
        $body = '';
        if ($post) {
            if (is_array($post)) {
                $body = http_build_query($post);
            } else {
                $body = urlencode($post);
            }
            $fdata .= 'Content-Length: ' . strlen($body) . "\r\n\r\n{$body}";
        } else {
            $fdata .= "\r\n";
        }
        if ($urlset['scheme'] == 'https') {
            $fp = fsockopen('ssl://' . $urlset['host'], $urlset['port'], $errno, $error);
        } else {
            $fp = fsockopen($urlset['host'], $urlset['port'], $errno, $error);
        }
        stream_set_blocking($fp, true);
        stream_set_timeout($fp, $timeout);
        if (!$fp) {
            return error(1, $error);
        } else {
            fwrite($fp, $fdata);
            $content = '';
            while (!feof($fp))
                $content .= fgets($fp, 512);
            fclose($fp);
            return $this->ihttp_response_parse($content, true);
        }
    }
    
    function ihttp_response_parse($data, $chunked = false) {
        $rlt = array();
        $headermeta = explode('HTTP/', $data);
        if (count($headermeta) > 2) {
            $data = 'HTTP/' . array_pop($headermeta);
        }
        $pos = strpos($data, "\r\n\r\n");
        $split1[0] = substr($data, 0, $pos);
        $split1[1] = substr($data, $pos + 4, strlen($data));
    
        $split2 = explode("\r\n", $split1[0], 2);
        preg_match('/^(\S+) (\S+) (\S+)$/', $split2[0], $matches);
        $rlt['code'] = $matches[2];
        $rlt['status'] = $matches[3];
        $rlt['responseline'] = $split2[0];
        $header = explode("\r\n", $split2[1]);
        $isgzip = false;
        $ischunk = false;
        foreach ($header as $v) {
            $pos = strpos($v, ':');
            $key = substr($v, 0, $pos);
            $value = trim(substr($v, $pos + 1));
            if (is_array($rlt['headers'][$key])) {
                $rlt['headers'][$key][] = $value;
            } elseif (!empty($rlt['headers'][$key])) {
                $temp = $rlt['headers'][$key];
                unset($rlt['headers'][$key]);
                $rlt['headers'][$key][] = $temp;
                $rlt['headers'][$key][] = $value;
            } else {
                $rlt['headers'][$key] = $value;
            }
            if(!$isgzip && strtolower($key) == 'content-encoding' && strtolower($value) == 'gzip') {
                $isgzip = true;
            }
            if(!$ischunk && strtolower($key) == 'transfer-encoding' && strtolower($value) == 'chunked') {
                $ischunk = true;
            }
        }
        if($chunked && $ischunk) {
            $rlt['content'] = ihttp_response_parse_unchunk($split1[1]);
        } else {
            $rlt['content'] = $split1[1];
        }
        if($isgzip && function_exists('gzdecode')) {
            $rlt['content'] = gzdecode($rlt['content']);
        }
    
        $rlt['meta'] = $data;
        if($rlt['code'] == '100') {
            return ihttp_response_parse($rlt['content']);
        }
        return $rlt;
    }
    
    
    function ihttp_response_parse_unchunk($str = null) {
        if(!is_string($str) or strlen($str) < 1) {
            return false;
        }
        $eol = "\r\n";
        $add = strlen($eol);
        $tmp = $str;
        $str = '';
        do {
            $tmp = ltrim($tmp);
            $pos = strpos($tmp, $eol);
            if($pos === false) {
                return false;
            }
            $len = hexdec(substr($tmp, 0, $pos));
            if(!is_numeric($len) or $len < 0) {
                return false;
            }
            $str .= substr($tmp, ($pos + $add), $len);
            $tmp  = substr($tmp, ($len + $pos + $add));
            $check = trim($tmp);
        } while(!empty($check));
        unset($tmp);
        return $str;
    }
    
    
}
?>
