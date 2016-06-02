<?php
include_once 'common.inc.php';
$orderid = intval(I('id'));
if (IS_AJAX) {
    $order = $shop_order->where(array('id' => $orderid, 'uniacid' => $uniacid, 'openid' => $openid))->find();
    if (empty($order)) {
        show_json(0);
    }
    $join = " og left join sx_zxin_shop_goods g on g.id= og.goodsid ";
    $column = " og.goodsid,og.price,g.title,g.thumb,og.total,g.credit,og.optionid,og.optionname as optiontitle,g.isverify,g.storeids ";
    $goods = $shop_order_goods->field($column)->join($join)->where(array('og.uniacid' => $uniacid, 'og.orderid' => $orderid))->select();
    foreach ($goods as &$item){
        $item['thumb']=tomedia($item['thumb']);
    }
    $order['goodstotal'] = count($goods);
    $order['finishtimevalue'] = $order['finishtime'];
    $order['finishtime'] = date('Y-m-d H:i:s', $order['finishtime']);
    $address = false;
    $carrier = false;
    $stores = array();
    if ($order['dispatchtype'] == 0) {
        $address = $shop_member_address->field('realname,mobile,address')->where(array('id' => $order['addressid']))->find();
    }
    if ($order['dispatchtype'] == 1 || $order['isverify'] == 1) {
        $carrier = unserialize($order['carrier']);
    }
    $set = array();
    $canrefund = false;
    if ($order['status'] == 1) {
        $canrefund = true;
    } else {
        if ($order['status'] == 3) {
            $tradeset =  m_m('common')->getSysset('trade');
            $refunddays = intval($tradeset['refunddays']);
            $refunddays == 0 && ($refunddays = 7);
            $days = intval((time() - $order['finishtimevalue']) / 3600 / 24);
            if ($days <= $refunddays) {
                $canrefund = true;
            }
        }
    }
    $order['canrefund'] = $canrefund;
    show_json(1, array('order' => $order, 'goods' => $goods, 'address' => $address, 'carrier' => $carrier, 'stores' => $stores, 'isverify' => '', 'set' => $set));
}