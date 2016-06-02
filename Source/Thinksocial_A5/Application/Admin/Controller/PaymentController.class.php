<?php
namespace Home\Controller;
class PaymentController extends HomeController{
    public function _empty(){
        header("Content-type: text/html; charset=utf-8");
        $uniacid                    = 0;
        $openid                     = !empty($_SESSION['openid'])?$_SESSION['openid']:'formUser';
        $shop_member                = M('member');
        $core_paylog                = M('core_paylog');
        $shop_order                 = M('zxin_shop_order');
        
        $action=array(
            'alipay','wechat','unionpay'
        );
        if(in_array(ACTION_NAME, $action)){
            if(ACTION_NAME=='alipay'){
                $arr = I();
                //待处理参数
                /* Array
                (
                    [module] => home
                    [body] => 0:0
                    [is_success] => T
                    [notify_id] => RqPnCoPT3K9%2Fvwbh3InUFmHNX9zcinQbLpt%2BG1%2BVibpZCfnpNZIEql87P4YojvIfV%2Bri
                    [notify_time] => 2015-12-16 17:32:52
                    [notify_type] => trade_status_sync
                    [out_trade_no] => SH201512161732569674
                    [payment_type] => 1
                    [seller_id] => 2088411612214192
                    [service] => alipay.wap.create.direct.pay.by.user
                    [subject] => 搜雪商城订单: SH201512161732569674
                    [total_fee] => 0.01
                    [trade_no] => 2015121600001000620065431082
                    [trade_status] => TRADE_SUCCESS
                    [sign] => 8c3929a0ed390dcbe700452c8f87ad9b
                    [sign_type] => MD5
                ) */
                if(!empty($arr)){
                    $out_trade_no = $arr['out_trade_no'];
                    $body = $arr['body'];
                    $strs = explode(':', $body);
                    $uniacid = $strs[0];
                    $type = $strs[1];
                    $setting = array(
                        'payment'=>array(
                            'wechat' => C('wechat'),
                            'alipay' => C('alipay'),
                            'credit' => C('credit'),
                        ),
                        'shop'=>array(
                            'name'=>'搜雪商城',
                        ),
                    );
                    if (is_array($setting['payment'])) {
                        $alipay = $setting['payment']['alipay'];
                        if (!empty($alipay)) {
                            $prepares = array();
                            foreach ($arr as $key => $value) {
                                if ($key != 'sign' && $key != 'sign_type') {
                                    $prepares[] = "{$key}={$value}";
                                }
                            }
                            sort($prepares);
                            $string = implode($prepares, '&');
                            $string .= $alipay['secret'];
                            $sign = md5($string);
                            if ($sign == $arr['sign']) {
                                if ($type == '0') {
                                    $tid = $out_trade_no;
                                    $log = $core_paylog->where(array('tid'=>$tid,'module'=>'zxin_shop'))->find();
                                    if (!empty($log) && $log['status'] == '0') {
                                        $record = array();
                                        $record['status'] = '1';
                                        $result=$core_paylog->where(array('plid' => $log['plid']))->save($record);
                                        $order =   $shop_order->field('id,status')->where(array('uniacid' => $uniacid, 'ordersn' => $tid))->find();
                                        $orderid = $order['id'];
                                        if ($order['status'] == 0) {
                                            $shop_order->where(array('id' => $orderid))->save(array('status' => 1, 'paytime' => time()));
                                        }
                                        if ($result) {
                                            $ret = array();
                                            $ret['weid'] = $uniacid;
                                            $ret['uniacid'] = $log['uniacid'];
                                            $ret['result'] = 'success';
                                            $ret['type'] = $log['type'];
                                            $ret['from'] = 'return';
                                            $ret['tid'] = $log['tid'];
                                            $ret['user'] = $log['openid'];
                                            $ret['fee'] = $log['fee'];
                                            $ret['is_usecard'] = $log['is_usecard'];
                                            $ret['card_type'] = $log['card_type'];
                                            $ret['card_fee'] = $log['card_fee'];
                                            $ret['card_id'] = $log['card_id'];
                                            payResult($ret);
                                            die('success');
                                        }
                                    }
                                } else {
                                    if ($type == '1') {
                                        $logno = trim($out_trade_no);
                                        if (empty($logno)) {
                                            die;
                                        }
                                        /* $log = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_member_log') . ' WHERE `uniacid`=:uniacid and `logno`=:logno limit 1', array(':uniacid' => $_W['uniacid'], ':logno' => $logno));
                                        if (!empty($log) && empty($log['status'])) {
                                            pdo_update('ewei_shop_member_log', array('status' => 1, 'rechargetype' => 'alipay'), array('id' => $log['id']));
                                            m('member')->setCredit($log['openid'], 'credit2', $log['money'], array(0, '人人商城会员充值:credit2:' . $log['money']));
                                            m('member')->setRechargeCredit($log['openid'], $log['money']);
                                            m('notice')->sendMemberLogMessage($log['id']);
                                        } */
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if(ACTION_NAME=='wechat'){
                
            }
        }
    }
}