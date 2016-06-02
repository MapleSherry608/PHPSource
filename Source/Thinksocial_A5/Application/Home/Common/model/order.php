<?php
class Zxin_DShop_Order
{
    /**
     * 判断是否手机微信
     * @return boolean
     */
    function is_weixin()
    {
        if (empty($_SERVER['HTTP_USER_AGENT']) || strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false && strpos($_SERVER['HTTP_USER_AGENT'], 'Windows Phone') === false) {
            return false;
        }
        return true;
    }
    /**
     * 计算运费价格
     * @param unknown $weight
     * @param unknown $d
     * @return number
     */
    function getDispatchPrice($weight, $d)
    {
    	if (empty($d)) {
    		return 0;
    	}
    	$price = 0;
    	if ($weight <= $d['firstweight']) {
    		$price = intval($d['firstprice']);
    	} else {
    		$price = intval($d['firstprice']);
    		$secondweight = $weight - intval($d['firstweight']);
    		$dsecondweight = intval($d['secondweight']) <= 0 ? 1 : intval($d['secondweight']);
    		$secondprice = 0;
    		if ($secondweight % $dsecondweight == 0) {
    			$secondprice = $secondweight / $dsecondweight * intval($d['secondprice']);
    		} else {
    			$secondprice = ((int) ($secondweight / $dsecondweight) + 1) * intval($d['secondprice']);
    		}
    		$price += $secondprice;
    	}
    	return $price;
    }
    /**
     * 生成订单号
     * @param unknown $table
     * @param unknown $field
     * @param unknown $prefix
     * @return string
     */
    function createNO($table, $field, $prefix)
    {
        $numeric = true;
        $length  = 6;
        $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
        if ($numeric) {
            $hash = '';
        } else {
            $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
            $length--;
        }
        $max = strlen($seed) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $seed{mt_rand(0, $max)};
        }
    
        $billno = date('YmdHis') . $hash;
        while (1) {
            $count = M('zxin_shop_'.$table)->where(array($field => $billno))->count();
            if ($count <= 0) {
                break;
            }
            $billno = date('YmdHis') . $hash;
        }
        return $prefix . $billno;
    }
    /**
     * 支付回调
     */
    function payResult($params)
    {
        $uniacid                    = 0;
        $shop_order                 =M('zxin_shop_order');
        $shop_member_address        =M('zxin_shop_member_address');
        $fee = intval($params['fee']);
    	$data = array('status' => $params['result'] == 'success' ? 1 : 0);
    	$ordersn = $params['tid'];
    	$order =   $shop_order->field('id,ordersn, price,openid,dispatchtype,addressid,carrier,status,isverify,deductcredit2')->where(array('uniacid' => $uniacid, 'ordersn' => $ordersn))->find();
    	$orderid = $order['id'];
    	if ($params['from'] == 'return') {
    		$address = false;
    		if (empty($order['dispatchtype'])) {
    			$address = $shop_member_address->field('realname,mobile,address')->where(array('id' => $order['addressid']))->find();
    		}
    		$carrier = false;
    		if ($order['dispatchtype'] == 1) {
    			$carrier = unserialize($order['carrier']);
    		}
    		if ($params['type'] == 'cash') {
    			show_json(2, array('order' => $order, 'address' => $address, 'carrier' => $carrier));
    		} else {
    			if ($order['status'] == 0) {
    			    $shop_order->where(array('id' => $orderid))->save(array('status' => 1, 'paytime' => time()));
    				/* if ($order['deductcredit2'] > 0) {
    					$shopset = m('common')->getSysset('shop');
    					m('member')->setCredit($order['openid'], 'credit2', -$order['deductcredit2'], array(0, $shopset['name'] . "余额抵扣: {$order['deductcredit2']} 订单号: " . $order['ordersn']));
    				} */
//     				setStocksAndCredits($orderid, 1);
    				/* sendOrderMessage($orderid);//消息提醒 */
    			}
    			show_json(1, array('order' => $order, 'address' => $address, 'carrier' => $carrier));
    		}
    	}
    }
    function setStocksAndCredits($orderid = '', $type = 0)
    {
        $uniacid                    = 0;
        $shop_order                 =M('zxin_shop_order');
        $shop_order_goods           =M('zxin_shop_order_goods');
        $shop_goods                 =M('zxin_shop_goods');
        $shop_goods_option          =M('zxin_shop_goods_option');
    	$order =$shop_order->field('id,price,openid,dispatchtype,addressid,carrier,status')->where(array('id' => $orderid))->find();
    	$cloumn = " og.goodsid,og.total,g.totalcnf,g.credit,og.optionid,g.total as goodsottal,og.optionid,g.sales,g.salesreal ";
    	$join = " og left join sx_zxin_shop_goods g on g.id=og.goodsid ";
    	$goods = $shop_order_goods->field($cloumn)->join($join)->where(array('og.uniacid' => $uniacid, 'og.orderid' => $orderid))->select();
    	$credits = 0;
    	foreach ($goods as $g) {
    		$stocktype = 0;
    		if ($type == 0) {
    			if ($g['totalcnf'] == 0) {
    				$stocktype = -1;
    			}
    		} else {
    			if ($type == 1) {
    				if ($g['totalcnf'] == 1) {
    					$stocktype = -1;
    				}
    			} else {
    				if ($type == 2) {
    					if ($order['status'] >= 1) {
    						if ($g['totalcnf'] == 1) {
    							$stocktype = 1;
    						}
    					} else {
    						if ($g['totalcnf'] == 0) {
    							$stocktype = 1;
    						}
    					}
    				}
    			}
    		}
    		if (!empty($stocktype)) {
    			if (!empty($g['optionid'])) {
    				$option = getOption($g['goodsid'], $g['optionid']);
    				if (!empty($option) && $option['stock'] != -1) {
    					$stock = -1;
    					if ($stocktype == 1) {
    						$stock = $option['stock'] + $g['total'];
    					} else {
    						if ($stocktype == -1) {
    							$stock = $option['stock'] - $g['total'];
    							$stock <= 0 && ($stock = 0);
    						}
    					}
    					if ($stock != -1) {
    						$shop_goods_option->where(array('uniacid' => $uniacid, 'goodsid' => $g['goodsid'], 'id' => $g['optionid']))->save(array('stock' => $stock));
    					}
    				}
    			}
    			if (!empty($g['goodstotal']) && $g['goodstotal'] != -1) {
    				$totalstock = -1;
    				if ($stocktype == 1) {
    					$totalstock = $g['goodstotal'] + $g['total'];
    				} else {
    					if ($stocktype == -1) {
    						$totalstock = $g['goodstotal'] - $g['total'];
    						$totalstock <= 0 && ($totalstock = 0);
    					}
    				}
    				if ($totalstock != -1) {
    				    $shop_goods->where(array('uniacid' => $uniacid, 'id' => $g['goodsid']))->save(array('total' => $totalstock));
    				}
    			}
    		}
    		$credits += $g['credit'] * $g['total'];
    		if ($type == 0) {
    		    $shop_goods->where(array('uniacid' => $uniacid, 'id' => $g['goodsid']))->save(array('sales' => $g['sales'] + $g['total']));
    		} elseif ($type == 1) {
    			if ($order['status'] >= 1) {
    				$shop_goods->where(array('uniacid' => $uniacid, 'id' => $g['goodsid']))->save(array('salesreal' => $g['salesreal'] + $g['total']));
    			}
    		}
    	}
    	/* $shopset = array(
    	    'name'=>'搜雪商城',
    	);
    	if ($type == 1) {
    		m('member')->setCredit($order['openid'], 'credit1', $credits, array(0, $shopset['name'] . '购物积分 订单号: ' . $order['ordersn']));
    	} elseif ($type == 2) {
    		if ($order['status'] >= 1) {
    			m('member')->setCredit($order['openid'], 'credit1', -$credits, array(0, $shopset['name'] . '购物取消订单扣除积分 订单号: ' . $order['ordersn']));
    		}
    	} */
    }
}
