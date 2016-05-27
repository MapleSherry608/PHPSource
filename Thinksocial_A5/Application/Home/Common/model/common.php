<?php
//require_once 'communication.func.php';
class Zxin_DShop_Common
{
    /**
     * 生成随机数
     * @param unknown $table
     * @param unknown $field
     * @param unknown $prefix
     * @return string
     */
    public function createNO($table, $field, $prefix)
    {
        $billno = date('YmdHis') . random(6, true);
        while (1) {
            $count = M('zxin_shop_' . $table)->where(array($field => $billno))->count();
            if ($count <= 0) {
                break;
            }
            $billno = date('YmdHis') . random(6, true);
        }
        return $prefix . $billno;
    }
    /**
     * 获取公众号信息
     * @return boolean
     */
    public function getAccount()
    {
        $account= M("member_public")->find();
        return $account;
    }
    /**
     * 获取商城配置数据
    * @param number $uniacid
    * @return Ambigous <multitype:, string, unknown, mixed, \Think\mixed, boolean, NULL, unknown, object>
    */
    public function getSetData($uniacid = 0)
    {
        $shop_sysset = M('zxin_shop_sysset');
        if (empty($uniacid)) {
            $uniacid = 0;
        }
        $path = IA_ROOT . "/Uploads/data/sysset";
        $cachefile = $path . "/sysset_" . $uniacid;
        if (is_file($cachefile)) {
            $set = iunserializer(file_get_contents($cachefile));
        } else {
            $set = $shop_sysset->where( array('uniacid' => $uniacid))->find();
            if (empty($set)) {
                $set = array();
            }
            if (!is_dir($path)) {
                @mkdirs($path);
            }
            file_put_contents($cachefile, iserializer($set));
        }
        return $set;
    }
    /**
     * 获取商城配置信息
     * @param string $key
     * @param number $uniacid
     * @return Ambigous <multitype:, mixed>|mixed
     */
    public function getSysset($key = '', $uniacid = 0)
    {
        $set = m_m('common')->getSetData($uniacid);
        $allset = unserialize($set['sets']);
        $retsets = array();
        if (!empty($key)) {
            if (is_array($key)) {
                foreach ($key as $k) {
                    $retsets[$k] = isset($allset[$k]) ? $allset[$k] : array();
                }
            } else {
                $retsets = isset($allset[$key]) ? $allset[$key] : array();
            }
            return $retsets;
        } else {
            return $allset;
        }
    }
    /**
     * 同步微信地址
     * @return boolean|multitype:string unknown Ambigous <string> Ambigous <string, unknown>
     */
    public function shareAddress()
    {
        global $_W, $_GPC;
        $appid = $_W['account']['appid'];
        $secret = $_W['account']['secret'];
        $url = SITEROOT . "index.php?" . $_SERVER['QUERY_STRING'];
        if (empty($_GPC['code'])) {
            $oauth2_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $appid . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
            header("location: {$oauth2_url}");
            die;
        }
        $code = $_GPC['code'];
        $token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $secret . "&code=" . $code . "&grant_type=authorization_code";
        $resp = ihttp_get($token_url);
        $token = @json_decode($resp['content'], true);
        if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
            return false;
        }
        $package = array("appid" => $appid, "url" => $url, 'timestamp' => time() . "", 'noncestr' => random(8, true) . "", 'accesstoken' => $token['access_token']);
        ksort($package, SORT_STRING);
        $addrSigns = array();
        foreach ($package as $k => $v) {
            $addrSigns[] = "{$k}={$v}";
        }
        $string = implode('&', $addrSigns);
        $addrSign = strtolower(sha1(trim($string)));
        $data = array("appId" => $appid, "scope" => "jsapi_address", "signType" => "sha1", "addrSign" => $addrSign, "timeStamp" => $package['timestamp'], "nonceStr" => $package['noncestr']);
        return $data;
    }
    /**
     * 构造支付参数链接
     * @param unknown $params
     * @param unknown $alipay
     * @param number $type
     * @param string $openid
     * @return multitype:string
     */
    public function alipay_build($params, $alipay = array(), $type = 0, $openid = '')
    {
        $uniacid = 0;
        $tid = $params['tid'];
        $set = array();
        $set['service'] = 'alipay.wap.create.direct.pay.by.user';
        $set['partner'] = $alipay['partner'];
        $set['_input_charset'] = 'utf-8';
        $set['sign_type'] = 'MD5';
        if ($type == 0) {
            //购物
            $set['notify_url'] = SITEROOT."index.php/Home/Payment/alipay";
            $set['return_url'] = SITEROOT."index.php/Home/Order/pay/op/return/openId/".$openid;
            
        } elseif($type == 1) {
            //积分充值
            $set['notify_url'] = SITEROOT."index.php/Home/Payment/alipay";
            $set['return_url'] = SITEROOT."index.php/Home/member/recharge/op/return/openId/".$openid;
        }
        $set['out_trade_no'] = $tid;
        $set['subject'] = $params['title'];
        $set['total_fee'] = $params['fee'];
        $set['seller_id'] = $alipay['account'];
        $set['payment_type'] = 1;
        $set['body'] = $uniacid . ':' . $type;
        $prepares = array();
        foreach ($set as $key => $value) {
            if ($key != 'sign' && $key != 'sign_type') {
                $prepares[] = "{$key}={$value}";
            }
        }
        sort($prepares);
        $string = implode($prepares, '&');
        $string .= $alipay['secret'];
        $set['sign'] = md5($string);
        return array('url' => ALIPAY_GATEWAY . '?' . http_build_query($set, '', '&'));
    }
    /**
     * 构造微信支付参数
     * @param unknown $params
     * @param unknown $wechat
     * @param number $type
     * @return multitype:string number |Ambigous <multitype:unknown string , multitype:unknown string Ambigous <> Ambigous <boolean, string> >|multitype:unknown string |multitype:string unknown number
     */
    public function wechat_build($params, $wechat, $type = 0)
    {
        $uniacid = 0;
        $openid = session('openid');
    
        if (empty($wechat['version']) && !empty($wechat['apikey'])) {
            $wechat['version'] = 1;
        }
        $wOpt = array();
        if ($wechat['version'] == 1) {
            $wOpt['appId'] = $wechat['appid'];
            $wOpt['timeStamp'] = NOW_TIME . "";
            $wOpt['nonceStr'] = random(8);
            $package = array();
            $package['bank_type'] = 'WX';
            $package['body'] = urlencode($params['title']);
            $package['attach'] = $uniacid . ':' . $type;
            $package['partner'] = $wechat['partner'];
            $package['out_trade_no'] = $params['tid'];
            $package['total_fee'] = $params['fee'] * 100;
            $package['fee_type'] = '1';
            $package['notify_url'] = SITEROOT."index.php/Home/Payment/wechat";
            $package['spbill_create_ip'] = CLIENT_IP;
            $package['time_start'] = date('YmdHis', NOW_TIME);
            $package['time_expire'] = date('YmdHis', NOW_TIME + 600);
            $package['input_charset'] = 'UTF-8';
            ksort($package);
            $string1 = '';
            foreach ($package as $key => $v) {
                if (empty($v)) {
                    continue;
                }
                $string1 .= "{$key}={$v}&";
            }
            $string1 .= "key={$wechat['key']}";
            $sign = strtoupper(md5($string1));
            $string2 = '';
            foreach ($package as $key => $v) {
                $v = urlencode($v);
                $string2 .= "{$key}={$v}&";
            }
            $string2 .= "sign={$sign}";
            $wOpt['package'] = $string2;
            $string = '';
            $keys = array('appId', 'timeStamp', 'nonceStr', 'package', 'appKey');
            sort($keys);
            foreach ($keys as $key) {
                $v = $wOpt[$key];
                if ($key == 'appKey') {
                    $v = $wechat['apikey'];
                }
                $key = strtolower($key);
                $string .= "{$key}={$v}&";
            }
            $string = rtrim($string, '&');
            $wOpt['signType'] = 'SHA1';
            $wOpt['paySign'] = sha1($string);
            return $wOpt;
        } else {
            $package = array();
            $package['appid'] = $wechat['appid'];
            $package['mch_id'] = $wechat['mchid'];
            $package['nonce_str'] = random(8);
            $package['body'] = $params['title'];
            $package['attach'] = $uniacid . ':' . $type;
            $package['out_trade_no'] = $params['tid'];
            $package['total_fee'] = $params['fee'] * 100;
            $package['spbill_create_ip'] = CLIENT_IP;
            $package['time_start'] = date('YmdHis', NOW_TIME);
            $package['time_expire'] = date('YmdHis', NOW_TIME + 600);
            $package['notify_url'] = SITEROOT."index.php/Home/Payment/wechat";
            $package['trade_type'] = 'JSAPI';
            $package['openid'] = $openid;
            ksort($package, SORT_STRING);
            $string1 = '';
            foreach ($package as $key => $v) {
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
                return error(-1, strval($xml->err_code) . ': ' . strval($xml->err_code_des));
            }
            $prepayid = $xml->prepay_id;
            $wOpt['appId'] = $wechat['appid'];
            $wOpt['timeStamp'] = NOW_TIME;
            $wOpt['nonceStr'] = random(8);
            $wOpt['package'] = 'prepay_id=' . $prepayid;
            $wOpt['signType'] = 'MD5';
            ksort($wOpt, SORT_STRING);
            foreach ($wOpt as $key => $v) {
                $string .= "{$key}={$v}&";
            }
            $string .= "key={$wechat['apikey']}";
            $wOpt['paySign'] = strtoupper(md5($string));
            return $wOpt;
        }
    }
}