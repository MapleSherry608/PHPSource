<?php
include_once 'common.php';
if ($operation == 'display' && $_W['isajax']) {
    $shop_member_log->where(array('openid' => $openid, 'status' => 0, 'type' => 0, 'uniacid' => $_W['uniacid']))->delete();
    $logno = m_m('common')->createNO('member_log', 'logno', 'RC');
    $log = array(
        'uniacid' => $_W['uniacid'],
        'logno' => $logno,
        'title' => $shopSet['shop']['name'] . "会员充值",
        'openid' => $openid,
        'type' => 0,
        'createtime' => time(),
        'status' => 0
    );
    $logid = $shop_member_log->add($log);
    $credit = m_m('member')->getCredit($openid, 'deposit');
    $wechat = array('success' => false);
    if (m_m('order')->is_weixin()) {
        $setting = array(
            'pay'=>array(
                'wechat' => C('wechat'),
                'alipay' => C('alipay'),
                'credit' => C('credit'),
            )
        );
        if (isset($setting['pay']) && $setting['pay']['wechat']['switch'] == 1) {
            if (is_array($setting['pay']['wechat']) && $setting['pay']['wechat']['switch']) {
                $wechat['success'] = true;
            }
        }
    }
    $alipay = array('success' => false);
    if (isset($setting['pay']['alipay']) && $setting['pay']['alipay'] == 1) {
        if (is_array($setting['pay']['alipay']) && $setting['pay']['alipay']['switch']) {
            $alipay['success'] = true;
        }
    }
    show_json(1, array('set' => $shopSet, 'logid' => $logid, 'isweixin' => m_m('order')->is_weixin(), 'wechat' => $wechat, 'alipay' => $alipay, 'credit' => $credit));
} else {
    if ($operation == 'recharge' && $_W['ispost']) {
        $logid = intval($_GPC['logid']);
        if (empty($logid)) {
            show_json(0, '充值出错, 请重试!');
        }
        $money = floatval($_GPC['money']);
        if (empty($money)) {
            show_json(0, '请填写充值金额!');
        }
        $type = $_GPC['type'];
        if (!in_array($type, array('weixin', 'alipay'))) {
            show_json(0, '未找到支付方式');
        }
        $log = $shop_member_log->where(array('uniacid' => $uniacid, 'id' => $logid))->find();
        if (empty($log)) {
            show_json(0, '充值出错, 请重试!');
        }
        $shop_member_log->where(array('id' => $log['id']))->save(array('money' => $money));
        if ($type == 'weixin') {
            if (!m_m('order')->is_weixin()) {
                show_json(0, '非微信环境!');
            }
            if (empty($shopSet['pay']['weixin'])) {
                show_json(0, '未开启微信支付!');
            }
            $wechat = array('success' => false);
            $params = array();
            $params['tid'] = $log['logno'];
            $params['user'] = $openid;
            $params['fee'] = $money;
            $params['title'] = $log['title'];
            load()->model('payment');
            $setting = array(
                'pay'=>array(
                    'wechat' => C('wechat'),
                    'alipay' => C('alipay'),
                    'credit' => C('credit'),
                )
            );
            if (is_array($setting['pay'])) {
                $options = $setting['pay']['wechat'];
                $options['appid'] = $_W['account']['appid'];
                $options['secret'] = $_W['account']['secret'];
                $wechat = m_m('common')->wechat_build($params, $options, 1);
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
                $alipay = array('success' => false);
                $params = array();
                $params['tid'] = $log['logno'];
                $params['user'] = $openid;
                $params['fee'] = $money;
                $params['title'] = $log['title'];
                $setting = array(
                    'pay'=>array(
                        'wechat' => C('wechat'),
                        'alipay' => C('alipay'),
                        'credit' => C('credit'),
                    )
                );
                if (is_array($setting['pay'])) {
                    $options = $setting['pay']['alipay'];
                    $alipay = m_m('common')->alipay_build($params, $options, 1, $openid);
                    if (!empty($alipay['url'])) {
                        $alipay['success'] = true;
                    }
                }
                show_json(1, array('alipay' => $alipay));
            }
        }
    } else {
        if ($operation == 'complete' && $_W['ispost']) {
            $logid = intval($_GPC['logid']);
            $log = $shop_member_log->where(array('uniacid' => $uniacid, 'id' => $logid))->find();
            if (!empty($log) && empty($log['status'])) {
                $shop_member_log->where(array('id' => $logid))->save(array('status' => 1, 'rechargetype' => $_GPC['type']));
                /*  m_m('member')->setCredit($openid, 'credit2', $log['money']);
                 m_m('member')->setRechargeCredit($openid, $log['money']);
                m_m('notice')->sendMemberLogMessage($logid); */
            }
            show_json(1);
        } else {
            if ($operation == 'return') {
                $logno = trim($_GPC['out_trade_no']);
                if (empty($logno)) {
                    die('充值出现错误，请重试!');
                }
                $log = $shop_member_log->where(array('uniacid' => $uniacid, 'logno' => $logno))->find();
                if (!empty($log) && empty($log['status'])) {
                    $shop_member_log->where(array('id' => $log['id']))->save(array('status' => 1, 'rechargetype' => 'alipay'));
                    /*  m_m('member')->setCredit($openid, 'credit2', $log['money']);
                     m_m('member')->setRechargeCredit($openid, $log['money']);
                    m_m('notice')->sendMemberLogMessage($log['id']); */
                }
                die('<div style="width:94%;padding:0 3%px;font-size:24px;">充值成功, 请关闭到浏览器, 返回到微信点击返回!</div>');
            }
        }
    }
}