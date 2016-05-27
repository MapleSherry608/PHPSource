<?php
include_once 'common.inc.php';
$member = $shop_member->where(array('openid'=>$openid))->find();
$orderid = intval($_GPC['orderid']);
if ($operation == 'display' && IS_AJAX) {
    if (empty($orderid)) {
        show_json(0, '参数错误!');
    }
    $order = $shop_order->where(array('id' => $orderid, 'uniacid' => $uniacid, 'openid' => $openid))->find();
    if (empty($order)) {
        show_json(0, '订单未找到!');
    }
    $log = $core_paylog->where(array('uniacid' => $uniacid, 'module' => 'zxin_shop', 'tid' => $order['ordersn']))->find();
    if (!empty($log) && $log['stauts'] != 0) {
        show_json(-1, '订单已支付, 无需重复支付!');
    }
    if (!empty($log) && $log['status'] == 0) {
        $core_paylog->where(array('plid' => $log['plid']))->delete();
        $log = null;
    }
    $plid = $log['plid'];
    if (empty($log)) {
        $log = array(
            'uniacid' => $uniacid,
            'openid' => $member['id'],
            'module' => "zxin_shop",
            'tid' => $order['ordersn'],
            'fee' => $order['price'],
            'status' => 0
        );
        $plid=$core_paylog->add($log);
    }

    $set = array(
        'pay'=>array(
            'wechat' => C('wechat'),
            'alipay' => C('alipay'),
            'credit' => C('credit'),
        )
    );
    
    $pay = $set['pay'];
    $is_weixin = m_m('order')->is_weixin();
    
    $credit = array('success' => false);
    if ($is_weixin) {
        $currentcredit = 0;
        if ($order['deductcredit2'] <= 0 && $pay['credit']['switch'] == 1) {
            $credit = array('success' => true, 'current' => $member['deposit']);
        }
    }

    $wechat = array('success' => false);
    if ($is_weixin) {
        if ($pay['wechat']['switch']==1) {
            $wechat['success'] = true;
        }
    }
    
    $alipay = array('success' => false);
    if ($pay['alipay']['switch']==1) {
        $alipay['success'] = true;
    }
    /* 现金 */
    $cash = array('success' => $order['cash'] == 1 && isset($set['pay']) && $set['pay']['cash'] == 1);
    $returnurl = urlencode(U('order/pay', array('orderid' => $orderid)));
    show_json(1, array('order' => $order, 'set' => $set, 'credit' => $credit, 'wechat' => $wechat, 'alipay' => $alipay, 'cash' => $cash, 'isweixin' => m_m('order')->is_weixin(), 'currentcredit' => $currentcredit, 'returnurl' => $returnurl));
} else {
    if ($operation == 'pay' && IS_AJAX ) {
        $set = array(
            'pay'=>array(
                'wechat' => C('wechat'),
                'alipay' => C('alipay'),
                'credit' => C('credit'),
            ),
            'shop'=>array(
                'name'=>'搜雪商城',
            ),
        );
        $order = $shop_order->where(array('id' => $orderid, 'uniacid' => $uniacid, 'openid' => $openid))->find();
        if (empty($order)) {
            show_json(0, '订单未找到!');
        }
        $type = I('type');
        if (!in_array($type, array('weixin', 'alipay', 'unionpay','credit'))) {
            show_json(0, '未找到支付方式');
        }
        $log = $core_paylog->where(array('uniacid' => $uniacid, 'module' => 'zxin_shop', 'tid' => $order['ordersn']))->find();
        if (empty($log)) {
            show_json(0, '支付出错,请重试!');
        }
        $plid = $log['plid'];
        $param_title = $set['shop']['name'] . "订单: " . $order['ordersn'];
         
        if ($type == 'weixin') {
            $shop_order->where(array('id' => $order['id']))->save(array('paytype' => 21));
            if (!m_m('order')->is_weixin()) {
                show_json(0, '非微信环境!');
            }
            if (empty($set['pay']['wechat'])) {
                show_json(0, '未开启微信支付!');
            }
            $wechat = array('success' => false);
            $params = array();
            $params['tid'] = $log['tid'];
            $params['user'] = $openid;
            $params['fee'] = $order['price'];
            $params['title'] = $param_title;
            if (is_array($set['pay'])) {
                $options = $set['pay']['wechat'];
                $wechat = m_m('common')->wechat_build($params, $options, 0);
                $wechat['success'] = false;
                if (!is_error($wechat)) {
                    $wechat['success'] = true;
                } else {
                    show_json(0, $wechat['message']);
                }
            }
            if (!$wechat['success']) {
                show_json(0, '微信支付参数错误!');
            }
            show_json(1, array('wechat' => $wechat));
        } else {
            if ($type == 'alipay') {
                $shop_order->where(array('id' => $order['id']))->save(array('paytype' => 22));
                $alipay = array('success' => false);
                $params = array();
                $params['tid'] = $log['tid'];
                $params['user'] = $openid;
                $params['fee'] = $order['price'];
                $params['title'] = $param_title;
                if (is_array($set['pay'])) {
                    $options = $set['pay']['alipay'];
                    $alipay = m_m('common')->alipay_build($params, $options, 0, $openid);
                    if (!empty($alipay['url'])) {
                        $alipay['success'] = true;
                    }
                }
                show_json(1, array('alipay' => $alipay));
            }
        }
    } else {
        if ($operation == 'complete' && IS_AJAX) {
            
            $order = $shop_order->where(array('id' => $orderid, 'uniacid' => $uniacid, 'openid' => $openid))->find();
            if (empty($order)) {
                show_json(0, '订单未找到!');
            }
            $type = $_GPC['type'];
            if (!in_array($type, array('weixin', 'alipay', 'credit', 'cash'))) {
                show_json(0, '未找到支付方式');
            }
            $log = $core_paylog->where(array('uniacid' => $uniacid, 'module' => 'zxin_shop', 'tid' => $order['ordersn']))->find();
            if (empty($log)) {
                show_json(0, '支付出错,请重试!');
            }
            $plid = $log['plid'];
            
            if ($type == 'weixin') {
                $record = array();
                $record['status'] = '1';
                $record['type'] = 'wechat';
                $core_paylog->where(array('plid' => $log['plid']))->save($record);
                $ret = array();
                $ret['result'] = 'success';
                $ret['type'] = 'wechat';
                $ret['from'] = 'return';
                $ret['tid'] = $log['tid'];
                $ret['user'] = $log['openid'];
                $ret['fee'] = $log['fee'];
                $ret['weid'] = $log['weid'];
                $ret['uniacid'] = $log['uniacid'];
                m_m('order')->payResult($ret);
            }
        } else {
            if ($operation == 'return') {
                $arr = I();
                $tid = $arr['out_trade_no'];
                $log = $core_paylog->where(array('uniacid' => $uniacid, 'module' => 'zxin_shop', 'tid' => $tid))->find();
                if (empty($log)) {
                    die('支付出现错误，请重试!');
                }
                if ($log['status'] != 1) {
                    $record = array();
                    $record['status'] = '1';
                    $record['type'] = 'alipay';
                    $core_paylog->where(array('plid' => $log['plid']))->save($record);
                    $ret = array();
                    $ret['result'] = 'success';
                    $ret['type'] = 'alipay';
                    $ret['from'] = 'return';
                    $ret['tid'] = $log['tid'];
                    $ret['user'] = $log['openid'];
                    $ret['fee'] = $log['fee'];
                    $ret['weid'] = $log['weid'];
                    $ret['uniacid'] = $log['uniacid'];
                    m_m('order')->payResult($ret);
                }
                $http = "http://".$_SERVER['SERVER_NAME']."/kuangjia/index.php?s=home/order/list/status/0";
                header("location:".$http);
                /* die('<div style="width:94%;padding:0 3%px;font-size:24px;">支付成功, 请关闭到浏览器, 返回到微信点击返回!</div>'); */
            }
        }
    }
}
$signPackage=$this->getSignPackage();
$array['signPackage'] = $signPackage;