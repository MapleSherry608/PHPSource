<?php
include_once 'common.inc.php';
if (IS_AJAX) {
    if ($operation == 'cancel') {
        $orderid = intval(I('orderid'));
        $order = $shop_order->field('id,ordersn,openid,status,deductcredit,deductprice')->where(array('id' => $orderid, 'uniacid' => $uniacid, 'openid' => $openid))->find();
        if (empty($order)) {
            show_json(0, '订单未找到!');
        }
        if ($order['status'] != 0) {
            show_json(0, '订单已支付，不能取消!');
        }
        $shop_order->where(array('id' => $order['id']))->save(array('status' => -1, 'canceltime' => time()));
        /* m('notice')->sendOrderMessage($orderid); */ //TODO
        /* if ($order['deductprice'] > 0) {
         $shop = m_m('common')->getSysset('shop');
         m('member')->setCredit($order['openid'], 'credit1', $order['deductcredit'], array('0', $shop['name'] . "购物返还抵扣积分 积分: {$order['deductcredit']} 抵扣金额: {$order['deductprice']} 订单号: {$order['ordersn']}"));
        } */
        show_json(1);
    } else {
        if ($operation == 'complete') {
            $orderid = intval(I('orderid'));
            $order = $shop_order->field('id,status,openid')->where(array('id' => $orderid, 'uniacid' => $uniacid, 'openid' => $openid))->find();
            if (empty($order)) {
                show_json(0, '订单未找到!');
            }
            if ($order['status'] != 2) {
                show_json(0, '订单未发货，不能确认收货!');
            }
            $shop_order->where(array('id' => $order['id']))->save(array('status' => 3, 'finishtime' => time()));
            /* m('member')->upgradeLevel($order['openid']);
             m('notice')->sendOrderMessage($orderid); */
            show_json(1);
        } else {
            if ($operation == 'refund') {
                $tradeset =  m_m('common')->getSysset('trade');
                $orderid = intval(I('orderid'));
                $order=$shop_order->field('id,status,price,refundid,goodsprice,dispatchprice,deductprice,deductcredit2,finishtime')->where(array('id' => $orderid, 'uniacid' => $uniacid, 'openid' => $openid))->find();
                if (empty($order)) {
                    show_json(0, '订单未找到!');
                }
                if ($order['status'] != 1 && $order['status'] != 3) {
                    show_json(0, '订单未付款或未收货，不能申请退款!');
                } else {
                    if ($order['status'] == 3) {
                        $refunddays = intval($tradeset['refunddays']);
                        $refunddays == 0 && ($refunddays = 7);
                        $days = intval((time() - $order['finishtime']) / 3600 / 24);
                        if ($days > $refunddays) {
                            show_json(0, '订单完成已超过 ' . $refunddays . ' 天, 无法发起退款申请!');
                        }
                    }
                }
                $order['refundprice'] = $order['price'] + $order['deductcredit2'];
                if ($order['status'] >= 3) {
                    $order['refundprice'] -= $order['dispatchprice'];
                }
                $refundid = $order['refundid'];
                if (IS_POST) {
                    $cancel = I('cancel');
                    if (!empty($cancel)) {
                        $shop_order_refund->where(array('id' => $refundid))->save(array('status' => -1));
                        $shop_order->where(array('id'=>$orderid))->save(array('refundid'=>0));
                        show_json(1);
                    } else {
                        $refunddata = I('refunddata');
                        $refund = array(
                            'uniacid' => $uniacid,
                            'orderid' => $orderid,
                            'refundno' =>  m_m('order')->createNO('order_refund', 'refundno', 'SR'),
                            'price' => $order['refundprice'],
                            'reason' => $refunddata['reason'],
                            'content' => $refunddata['content']
                        );
                        if (empty($refundid)) {
                            $refund['createtime'] = time();
                            $refundid = $shop_order_refund->add($refund);
                            $shop_order->where(array('id'=>$orderid))->save(array('refundid'=>$refundid));
                        } else {
                            $shop_order_refund->where(array('id' => $refundid))->save($refund);
                        }
                        /* m('notice')->sendOrderMessage($orderid, true); */
                        show_json(1);
                    }
                }
                $refund = false;
                if (!empty($refundid)) {
                    $refund = $shop_order_refund->where( array('id' => $refundid, 'uniacid' => $uniacid, 'orderid' => $orderid))->find();
                    $refund['createtime'] = date('Y-m-d H:i', $refund['createtime']);
                }
                show_json(1, array('order' => $order, 'refund' => 1));
            } else {
                if ($operation == 'comment') {
                    $orderid = intval(I('orderid'));
                    $order = $shop_order->field('id,status,iscomment')->where( array('id' => $orderid, 'uniacid' => $uniacid, 'openid' => $openid))->find();
                    if (empty($order)) {
                        show_json(0, '订单未找到!');
                    }
                    if ($order['status'] != 3 && $order['status'] != 4) {
                        show_json(0, '订单未收货，不能评价!');
                    }
                    if ($order['iscomment'] >= 2) {
                        show_json(0, '您已经评价了!');
                    }
                    if (IS_AJAX) {
                        $member = $shop_member->where(array('openid'=>$openid))->find();
                        $comments = I('comments');
                        if (!is_array($comments)) {
                            show_json(0, '数据出错，请重试!');
                        }
                        foreach ($comments as $c) {
                            $old_c = $shop_order_comment->where(array('uniacid' => $uniacid, 'goodsid' => $c['goodsid'], 'orderid' => $orderid))->count();
                            if (empty($old_c)) {
                                $comment = array(
                                    'uniacid' => $uniacid,
                                    'orderid' => $orderid,
                                    'goodsid' => $c['goodsid'],
                                    'level' => $c['level'],
                                    'content' => $c['content'],
                                    'images' => is_array($c['images']) ? iserializer($c['images']) : iserializer(array()),
                                    'openid' => $openid,
                                    'nickname' => $member['nickname'],
                                    'headimgurl' => $member['avatar'],
                                    'createtime' => time()
                                );
                                $shop_order_comment->add($comment);
                            } else {
                                $comment = array(
                                    'append_content' => $c['content'],
                                    'append_images' => is_array($c['images']) ? iserializer($c['images']) : iserializer(array())
                                );
                                $shop_order_comment->where(array('uniacid' => $uniacid, 'goodsid' => $c['goodsid'], 'orderid' => $orderid))->save($comment);
                            }
                        }
                        if ($order['iscomment'] <= 0) {
                            $d['iscomment'] = 1;
                        } else {
                            $d['iscomment'] = 2;
                        }
                        $shop_order->where(array('id' => $orderid))->save($d);
                        show_json(1);
                    }
                    $column = " og.id,og.goodsid,og.price,g.title,g.thumb,og.total,g.credit,og.optionid,o.title as optiontitle ";
                    $join = " og left join sx_zxin_shop_goods g on g.id = og.goodsid left join sx_zxin_shop_goods_option o on o.id = og.optionid ";
                    $condition = array(
                        'og.orderid'=>$orderid,
                        'og.uniacid'=>$uniacid,
                    );
                    $goods = $shop_order_goods->field($column)->join($join)->where($condition)->select();
                    foreach ($goods as &$item){
                        $item['thumb']=tomedia($item['thumb']);
                    }
                    show_json(1, array('order' => $order, 'goods' => $goods));
                } else {
                    if ($operation == 'delete') {
                        $orderid = intval(I('orderid'));
                        $order = $shop_order->field('id,status')->where(array('id' => $orderid, 'uniacid' => $uniacid, 'openid' => $openid))->find();
                        if (empty($order)) {
                            show_json(0, '订单未找到!');
                        }
                        if ($order['status'] != 3 && $order['status'] != -1) {
                            show_json(0, '订单无交易，不能删除!');
                        }
                        $shop_order->where(array('id' => $order['id']))->save(array('deleted'=>1));
                        show_json(1);
                    }
                }
            }
        }
    }
}
if ($operation == 'refund') {
    $tradeset =  m_m('common')->getSysset('trade');
    $this->assign('tradeset',$tradeset);
    $this->display('Shop/default/order/refund');die();
} else {
    if ($operation == 'comment') {
        $this->display('Shop/default/order/comment');die();
    }
}