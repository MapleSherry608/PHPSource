<?php
include_once 'common.inc.php';
//匹配微信地址
/* $trade = m_m('common')->getSysset('trade');
 if (!empty($trade['shareaddress'])) {
 if (IS_AJAX) {
 $shareAddress = m_m('common')->shareAddress();
 if (empty($shareAddress)) {
 die;
 }
 }
 } */
if (IS_AJAX) {
    $fromcart = 0;
    if ($operation == 'display') {
        $id = intval($arr['id']);
        $optionid = intval($arr['optionid']);
        $total = intval($arr['total']);


        /* $ids = I('cartids'); */
        empty($total) && ($total = 1);
        if (empty($id)) {
            $condition = '';
            $cartids = I('cartids');
            if (!empty($cartids)) {
                $condition = array(
                    'c.id' => array('in',$cartids),
                    'c.openid' => array('eq',$openid),
                    'c.deleted' => array('eq',0),
                    'c.uniacid' => array('eq',$uniacid),
                );
            }
            $column = " c.goodsid,c.total,g.maxbuy,g.issendfree,g.isnodiscount,g.weight, g.title,g.thumb,o.marketprice omarketprice, g.marketprice gmarketprice,o.title as optiontitle,c.optionid,g.storeids,g.isverify,g.deduct ";
            $join = " as c left join sx_zxin_shop_goods as g on c.goodsid = g.id left join sx_zxin_shop_goods_option as o on c.optionid = o.id ";
            $goods = $shop_member_cart->field($column)->join($join)->where($condition)->order('c.id desc')->select();
            foreach ($goods as &$g) {
                $g['thumb'] = tomedia($g['thumb']);
                $omarketprice=floatval($g['omarketprice']);
                if(!empty($omarketprice)){
                    $g['marketprice']=$g['omarketprice'];
                }else{
                    $g['marketprice']=$g['gmarketprice'];
                }
            }
            if (empty($goods)) {
                show_json(-1, array('url' => U('shop/cart')));
            }
            $fromcart = 1;
        }else {
            $data = $shop_goods->field('id as goodsid,title,weight,issendfree,isnodiscount, thumb,marketprice,storeids,isverify,deduct')->where(array('uniacid' => $uniacid, 'id' => $id))->find();
            $data['total'] = $total;
            if (!empty($optionid)) {
                $option = $shop_goods_option->field('id,title,marketprice,goodssn,productsn')->where(array('uniacid' => $uniacid, 'goodsid' => $id, 'id' => $optionid))->find();
                if (!empty($option)) {
                    $data['optionid'] = $optionid;
                    $data['optiontitle'] = $option['title'];
                    $data['marketprice'] = $option['marketprice'];
                }
            }
            $goods[] = $data;
        }
        $member = $shop_member->where(array('openid'=>$openid))->find();
        if(empty($member['realname'])){
            $member['realname'] = '';
        }
        if(empty($member['mobile'])){
            $member['mobile'] = '';
        }
        $stores = array();
        $address = false;
        $carrier = false;
        $carrier_list = array();
        $dispatch = false;
        $dispatch_list = false;
        $saleset = false;
        $isverify = false;
        $address_count = $shop_member_address->where(array('deleted'=>0,'isdefault'=>1,'openid'=>$openid,'uniacid'=>$uniacid))->count();
        if(intval($address_count) > 0){
            if (!$isverify) {
                $address = $shop_member_address->field('id,realname,mobile,address,province,city,area')->where(array('deleted'=>0,'isdefault'=>1,'openid'=>$openid,'uniacid'=>$uniacid))->find();
                $carrier = false;
                $carrier_list = array();
                $weight = 0;
                foreach ($goods as &$g) {
                    if (empty($g['issendfree'])) {
                        $weight += $g['weight'] * $g['total'];
                    }
                }
                $dispatch = false;
                $dispatch_list = $shop_dispatch->field('id,dispatchname,dispatchtype,firstprice,firstweight,secondprice,secondweight,areas,carriers')->where(array('enabled'=>1,'uniacid'=>$uniacid))->order('displayorder desc')->select();
                foreach ($dispatch_list as &$d) {
                    $d['price'] = 0;
                    if ($d['dispatchtype'] == 1) {
                        $clist = unserialize($d['carriers']);
                        if (is_array($clist)) {
                            $carrier_list = array_merge($carrier_list, $clist);
                        }
                        continue;
                    }
                    $areas = unserialize($d['areas']);
                    if ($weight > 0) {
                        if (!empty($address)) {
                            $setprice = false;
                            if (is_array($areas) && count($areas) > 0) {
                                foreach ($areas as $area) {
                                    $citys = explode(";", $area['citys']);
                                    if (in_array($address['city'], $citys)) {
                                        $d['price'] = m_m('order')->getDispatchPrice($weight, $area);
                                        $setprice = true;
                                        break;
                                    }
                                }
                            }
                            if (!$setprice) {
                                $d['price'] =  m_m('order')->getDispatchPrice($weight, $d);
                            }
                        } else {
                            if (empty($member['city'])) {
                                $d['price'] =  m_m('order')->getDispatchPrice($weight, $d);
                            } else {
                                if (!empty($member['city'])) {
                                    $setprice = false;
                                    if (is_array($areas) && count($areas) > 0) {
                                        foreach ($areas as $area) {
                                            $citys = explode(";", $areas['citys']);
                                            if (in_array($member['city'], $citys)) {
                                                $d['price'] =  m_m('order')->getDispatchPrice($weight, $area);
                                                $setprice = true;
                                                break;
                                            }
                                        }
                                    }
                                    if (!$setprice) {
                                        $d['price'] =  m_m('order')->getDispatchPrice($weight, $d);
                                    }
                                }
                            }
                        }
                    }
                    if (!$dispatch) {
                        $dispatch = $d;
                    }
                }
                unset($d);
                if (!empty($carrier_list)) {
                    $carrier = $carrier_list[0];
                }
            }
        }
        $level =$member['levelid'];
        $total = 0;
        $goodsprice = 0;
        $realprice = 0;
        $deductprice = 0;
        $discountprice = 0;
        $totalprice = 0 ;
        foreach ($goods as &$g) {
            $g['thumb'] = tomedia($g['thumb']);
            if ($g['isverify'] == 2) {
                $isverify = true;
            }
            $gprice = $g['marketprice'] * $g['total'];
            $realprice += $gprice;
            $goodsprice += $gprice;
            $totalprice += $gprice;
            $total += $g['total'];
            $deductprice += $g['deduct'];
        }

        if (!empty($dispatch)) {
            $realprice += $dispatch['price'];
        }

        //预留抵扣参数 TODO
        $deductcredit = 0;
        $deductmoney = 0;
        $deductcredit2 = 0;

        show_json(1, array(
        'member' => $member,
        'deductcredit' => $deductcredit,//可抵扣积分
        'deductmoney' => $deductmoney,//可抵扣金额
        'deductcredit2' => $deductcredit2,//可抵扣余额
        'saleset' => $saleset,
        'goods' => $goods,
        'weight' => $weight,
        'set'=>array('name'=>'搜雪商城'),
        'fromcart' => $fromcart,
        'haslevel' => ! empty($level['id']),
        'total' => $total,
        'dispatchprice' => ! empty($dispatch) ? number_format($dispatch['price'], 2) : 0,
        'totalprice' => number_format($totalprice, 2),
        'goodsprice' => number_format($goodsprice, 2),
        'discountprice' => number_format($discountprice, 2),
        'discount' => $level['discount'],
        'realprice' => number_format($realprice, 2),
        'address' => $address,
        'carrier' => $carrier,
        'carrier_list' => $carrier_list,
        'dispatch' => $dispatch,
        'dispatch_list' => $dispatch_list,
        'isverify' => $isverify,
        'stores' => $stores
        ));
    } else {
        if ($operation == 'getdispatchprice') {
            $totalprice = floatval(I('totalprice'));
            $addressid = intval(I('addressid'));
            $dispatchid = intval(I('dispatchid'));
            $weight = I('weight');
            if (empty($weight)) {
                show_json(1, array('price' => 0));
            }
            $address = $shop_member_address->field('id,realname,mobile,address,province,city,area')->where(array('uniacid' => $uniacid, 'openid' => $openid, 'id' => $addressid))->find();
            $dispatch = $shop_dispatch->field('id,dispatchname,dispatchtype,firstprice,firstweight,secondprice,secondweight,areas,carriers')->where(array('uniacid' => $uniacid, 'id' => $dispatchid))->find();
            $areas = unserialize($dispatch['areas']);
            $setprice = false;
            if (is_array($areas) && count($areas) > 0) {
                foreach ($areas as $area) {
                    $citys = explode(";", $area['citys']);
                    if (in_array($address['city'], $citys)) {
                        $price =  m_m('order')->getDispatchPrice($weight, $area);
                        $setprice = true;
                        break;
                    }
                }
            }
            if (!$setprice) {
                $price =  m_m('order')->getDispatchPrice($weight, $dispatch);
            }
            show_json(1, array('price' => $price));
        } else {
            if ($operation == 'create' && IS_AJAX) {
                $member = $shop_member->where(array('openid'=>$openid))->find();
                $dispatchtype = intval(I('dispatchtype'));
                $addressid = intval(I('addressid'));
                if (!empty($addressid) && $dispatchtype == 0) {
                    $address = $shop_member_address->field('id,realname,mobile,address,province,city,area')->where(array('uniacid' => $uniacid, 'openid' => $openid, 'id' => $addressid))->find();
                    if (empty($address)) {
                        show_json(0, '未找到地址');
                    }
                }
                $dispatchid = intval(I('dispatchid'));
                $dispatch = $shop_dispatch->field('id,dispatchname,dispatchtype,firstprice,firstweight,secondprice,secondweight,areas,carriers')->where(array('uniacid' => $uniacid, 'id' => $dispatchid))->select();
                $goods = I('goods');
                if (empty($goods)) {
                    show_json(0, '未找到任何商品');
                }
                $allgoods = array();
                $totalprice = 0;
                $goodsprice = 0;
                $weight = 0;
                $discountprice = 0;
                $goodsarr = explode('|', $goods);
                $cash = 1;
                $level = 0;
                $deductprice = 0;
                $saleset = false;
                $isverify = false;
                foreach ($goodsarr as $g) {
                    if (empty($g)) {
                        continue;
                    }
                    $goodsinfo = explode(',', $g);
                    $goodsid = !empty($goodsinfo[0]) ? intval($goodsinfo[0]) : '';
                    $optionid = !empty($goodsinfo[1]) ? intval($goodsinfo[1]) : 0;
                    $goodstotal = !empty($goodsinfo[2]) ? intval($goodsinfo[2]) : '1';
                    if (empty($goodsid)) {
                        show_json(0, '参数错误，请刷新重试');
                    }

                    $column = " id as goodsid,title, weight,total,issendfree,isnodiscount, thumb,marketprice,cash,isverify,goodssn,productsn,istime,timestart,timeend,usermaxbuy,maxbuy,unit,buylevels,buygroups,deleted,status,deduct ";
                    $data = $shop_goods->field($column)->where(array('uniacid' => $uniacid, 'id' => $goodsid))->find();
                    if (empty($data['status']) || !empty($data['deleted'])) {
                        show_json(-1, $data['title'] . '<br/> 已下架!');
                    }
                    $data['total'] = $goodstotal;
                    if ($data['cash'] != 2) {
                        $cash = 0;
                    }
                    $unit = empty($data['unit']) ? '件' : $data['unit'];
                    if ($data['maxbuy'] > 0) {
                        if ($goodstotal > $data['maxbuy']) {
                            show_json(-1, $data['title'] . '<br/> 一次限购 ' . $data['maxbuy'] . $unit . "!");
                        }
                    }
                    if ($data['usermaxbuy'] > 0) {
                        $join = " og left join sx_zxin_shop_order o on og.orderid=o.id ";
                        $condition = array(
                            'og.goodsid'=>$data['goodsid'],
                            'o.status'=>array('EGT',1),
                            'o.openid'=>$openid,
                            'o.uniacid'=>$uniacid,
                        );
                        $order_goodscount = $shop_order_goods->join($join)->where($condition)->getField('sum(og.total)');
                        if(empty($order_goodscount)){
                            $order_goodscount=0;
                        }
                        if ($order_goodscount >= $data['usermaxbuy']) {
                            show_json(-1, $data['title'] . '<br/> 最多限购 ' . $data['usermaxbuy'] . $unit . "!");
                        }
                    }
                    if ($data['istime'] == 1) {
                        if (time() < $data['timestart']) {
                            show_json(-1, $data['title'] . '<br/> 限购时间未到!');
                        }
                        if (time() > $data['timeend']) {
                            show_json(-1, $data['title'] . '<br/> 限购时间已过!');
                        }
                    }
                    $levelid = $member['levelid'];
                    $groupid = $member['groupid'];
                    if ($data['buylevels'] != '') {
                        $buylevels = explode(',', $data['buylevels']);
                        if (!in_array($levelid, $buylevels)) {
                            show_json(-1, '您的会员等级无法购买<br/>' . $data['title'] . '!');
                        }
                    }
                    if ($data['buygroups'] != '') {
                        $buygroups = explode(',', $goods['buygroups']);
                        if (!in_array($groupid, $buygroups)) {
                            show_json(-1, '您所在会员组无法购买<br/>' . $data['title'] . '!');
                        }
                    }
                    if (!empty($optionid)) {
                        $option = $shop_goods_option->field('id,title,marketprice,goodssn,productsn,stock')->where(array('uniacid' => $uniacid, 'goodsid' => $goodsid, 'id' => $optionid))->find();
                        if (!empty($option)) {
                            if ($option['stock'] != -1) {
                                if (empty($option['stock'])) {
                                    show_json(-1, $data['title'] . "<br/>" . $option['title'] . " 库存不足!");
                                }
                            }
                            $data['optionid'] = $optionid;
                            $data['optiontitle'] = $option['title'];
                            $data['marketprice'] = $option['marketprice'];
                            if (!empty($option['goodssn'])) {
                                $data['goodssn'] = $option['goodssn'];
                            }
                            if (!empty($option['productsn'])) {
                                $data['productsn'] = $option['productsn'];
                            }
                        }
                    } else {
                        if ($data['total'] != -1) {
                            if (empty($data['total'])) {
                                show_json(-1, $data['title'] . "<br/>库存不足!");
                            }
                        }
                    }
                    $gprice = $data['marketprice'] * $goodstotal;
                    $goodsprice += $gprice;
                    if (empty($data['isnodiscount']) && !empty($level['id'])) {
                        $dprice = round($gprice * $level['discount'] / 10, 2);
                        $discountprice += $gprice - $dprice;
                        $totalprice += $dprice;
                    } else {
                        $totalprice += $gprice;
                    }
                    if (empty($data['issendfree'])) {
                        $weight += $data['weight'] * $goodstotal;
                    }
                    if ($data['isverify'] == 2) {
                        $isverify = true;
                    }
                    $deductprice += $data['deduct'];
                    $allgoods[] = $data;
                }

                if (empty($allgoods)) {
                    show_json(0, '未找到任何商品');
                }
                $deductenough = 0;
                if ($saleset && $totalprice >= floatval($saleset['enoughmoney']) && floatval($saleset['enoughdeduct']) > 0) {
                    $deductenough = floatval($saleset['enoughdeduct']);
                    if ($deductenough > $totalprice) {
                        $deductenough = $totalprice;
                    }
                }
                $dispatchprice = 0;
                if ($weight > 0) {
                    $zeroprice = false;
                    if ($saleset) {
                        if (!empty($saleset['enoughfree']) && floatval($saleset['enoughorder']) <= 0) {
                            $zeroprice = true;
                        } else {
                            if (!empty($saleset['enoughfree']) && $totalprice >= floatval($saleset['enoughorder'])) {
                                if (!empty($saleset['enoughareas'])) {
                                    $areas = explode(";", $saleset['enoughareas']);
                                    if (!in_array($address['city'], $areas)) {
                                        $zeroprice = true;
                                    }
                                } else {
                                    $zeroprice = true;
                                }
                            }
                        }
                    }
                    if (!$zeroprice) {
                        if (empty($dispatchtype)) {
                            $areas = unserialize($dispatch['areas']);
                            $setprice = false;
                            if (is_array($areas) && count($areas) > 0) {
                                foreach ($areas as $area) {
                                    $citys = explode(";", $area['citys']);
                                    if (in_array($address['city'], $citys)) {
                                        $dispatchprice =  m_m('order')->getDispatchPrice($weight, $area);
                                        $setprice = true;
                                        break;
                                    }
                                }
                            }
                            if (!$setprice) {
                                $dispatchprice =  m_m('order')->getDispatchPrice($weight, $dispatch);
                            }
                        }
                    }
                }
                $dispatchprice = I('dispatchprice');
                $totalprice -= $deductenough;
                $totalprice += $dispatchprice;
                $deductcredit = 0;
                $deductmoney = 0;
                $deductcredit2 = 0;
                $ordersn =  m_m('order')->createNO('order', 'ordersn', 'SH');
                $verifycode = "";
                $order = array(
                    'uniacid' => $uniacid,
                    'openid' => $openid,
                    'ordersn' => $ordersn,
                    'price' => $totalprice,
                    'cash' => $cash,
                    'discountprice' => $discountprice,
                    'deductprice' => $deductmoney,
                    'deductcredit' => $deductcredit,
                    'deductcredit2' => $deductcredit2,
                    'deductenough' => $deductenough,
                    'status' => 0,
                    'paytype' => 0,
                    'transid' => '',
                    'remark' => I('remark'),
                    'addressid' => empty($dispatchtype) ? $addressid : 0,
                    'goodsprice' => $goodsprice,
                    'dispatchprice' => $dispatchprice,
                    'dispatchtype' => $dispatchtype,
                    'dispatchid' => $dispatchid,
                    'carrier' => is_array(I('carrier')) ? iserializer(I('carrier')) : iserializer(array()),
                    'createtime' => time(),
                    'isverify' => $isverify ? 1 : 0,
                    'verifycode' => $verifycode
                );
                $orderid = $shop_order->add($order);
                if (I('fromcart') == 1) {
                    $cartids = I('cartids');
                    if (!empty($cartids)) {
                        $condition = array(
                            'id'=>array('in',$cartids),
                            'openid'=>$openid,
                            'uniacid'=>$uniacid,
                        );
                        $shop_member_cart->where($condition)->save(array('deleted'=>1));
                    } else {
                        $condition = array(
                            'openid'=>$openid,
                            'uniacid'=>$uniacid,
                        );
                        $shop_member_cart->where($condition)->save(array('deleted'=>1));
                    }
                }
                foreach ($allgoods as $goods) {
                    $order_goods = array(
                        'uniacid' => $uniacid,
                        'orderid' => $orderid,
                        'goodsid' => $goods['goodsid'],
                        'price' => $goods['marketprice'] * $goods['total'],
                        'total' => $goods['total'],
                        'optionid' => $goods['optionid'],
                        'createtime' => time(),
                        'optionname' => $goods['optiontitle'],
                        'goodssn' => $goods['goodssn'],
                        'productsn' => $goods['productsn']
                    );
                    if (empty($goods['isnodiscount']) && !empty($level['id'])) {
                        $order_goods['realprice'] = $order_goods['price'] * $level['discount'] / 10;
                    } else {
                        $order_goods['realprice'] = $order_goods['price'];
                    }
                    $shop_order_goods->add($order_goods);
                }
                /* if ($deductcredit > 0) {
                 $shop = m_m('common')->getSysset('shop');
                 m('member')->setCredit($openid, 'credit1', -$deductcredit, array('0', $shop['name'] . "购物积分抵扣 消费积分: {$deductcredit} 抵扣金额: {$deductmoney} 订单号: {$ordersn}"));
                 }
                 m('order')->setStocksAndCredits($orderid, 0);
                 m('notice')->sendOrderMessage($orderid);
                 if (p('commission')) {
                 p('commission')->calculate($orderid);
                 p('commission')->checkOrderConfirm($orderid);
                 } */
                show_json(1, array('orderid' => $orderid));
            }
        }
    }
}