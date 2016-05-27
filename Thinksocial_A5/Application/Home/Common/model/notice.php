<?php
class Zxin_Dshop_Notice
{
	public function sendOrderMessage($orderid = '0', $delRefund = false)
	{
		global $_W;
		if (empty($orderid)) {
			return;
		}
		$order = M('zxin_shop_order')->where(array('id' => $orderid))->find();
		if (empty($order)) {
			return;
		}
		$detailurl = SITEROOT."index.php?s=order/detail/id/".$orderid;
		
		$openid = $order['openid'];
		$column = " g.id,g.title,og.total,og.price,og.optionname as optiontitle,g.noticeopenid,g.noticetype ";
		$join = " og left join sx_zxin_shop_goods g on g.id=og.goodsid  ";
		$condition = array(
		    'og.uniacid' => $_W['uniacid'],
		    'og.orderid' => $orderid,
		);
		$order_goods = M('zxin_shop_order_goods')->join($join)->where($condition)->select();
		$goods = '';
		foreach ($order_goods as $og) {
			$goods .= "" . $og['title'] . '( ';
			if (!empty($og['optiontitle'])) {
				$goods .= " 规格: " . $og['optiontitle'];
			}
			$goods .= ' 单价: ' . $og['price'] / $og['total'] . ' 数量: ' . $og['total'] . ' 总价: ' . $og['price'] . "); ";
		}
		$member = m_m('member')->getMember($openid);
		$usernotice = unserialize($member['noticeset']);
		if (!is_array($usernotice)) {
			$usernotice = array();
		}
		$set = m_m('common')->getSysset();
		$shop = $set['shop'];
		$tm = $set['notice'];
		if ($delRefund) {
			if (!empty($order['refundid'])) {
				$refund = M('zxin_shop_order_refund')->where(array('id' => $order['refundid']))->find();
				if (empty($refund)) {
					return;
				}
				if (empty($refund['status'])) {
					$msg = array('first' => array('value' => "您的退款申请已经提交！", "color" => "#4a5077"), 'orderProductPrice' => array('title' => '退款金额', 'value' => 'yen' . $refund['price'] . '元', "color" => "#4a5077"), 'orderProductName' => array('title' => '商品详情', 'value' => $goods, "color" => "#4a5077"), 'orderName' => array('title' => '订单编号', 'value' => $order['ordersn'], "color" => "#4a5077"), 'remark' => array('value' => "\r\n等待商家确认退款信息！", "color" => "#4a5077"));
					if (!empty($tm['refund']) && empty($usernotice['refund'])) {
						m_m('message')->sendTplNotice($openid, $tm['refund'], $msg, $detailurl);
					} else {
						if (empty($usernotice['refund'])) {
							m_m('message')->sendCustomNotice($openid, $msg, $detailurl);
						}
					}
				} else {
					if ($refund['status'] == 1) {
						$refundtype = empty($refund['refundtype']) ? '余额账户' : '对应支付渠道（如银行卡，微信钱包等, 具体到账时间请您查看微信支付通知)';
						$msg = array('first' => array('value' => "您的订单已经完成退款！", "color" => "#4a5077"), 'orderProductPrice' => array('title' => '退款金额', 'value' => 'yen' . $refund['price'] . '元', "color" => "#4a5077"), 'orderProductName' => array('title' => '商品详情', 'value' => $goods, "color" => "#4a5077"), 'orderName' => array('title' => '订单编号', 'value' => $order['ordersn'], "color" => "#4a5077"), 'remark' => array('value' => "\r\n 退款金额 yen" . $refund['price'] . ", 已经退回您的{$refundtype}，请留意查收！\r\n 【" . $shop['name'] . "】期待您再次购物！", "color" => "#4a5077"));
						if (!empty($tm['refund1']) && empty($usernotice['refund1'])) {
							m_m('message')->sendTplNotice($openid, $tm['refund'], $msg, $detailurl);
						} else {
							if (empty($usernotice['refund1'])) {
								m_m('message')->sendCustomNotice($openid, $msg, $detailurl);
							}
						}
					} elseif ($refund['status'] == -1) {
						$remark = "\n驳回原因: " . $refund['reply'];
						if (!empty($shop['phone'])) {
							$remark .= "\n客服电话:  " . $shop['phone'];
						}
						$msg = array('first' => array('value' => "您的退款申请被商家驳回，可与商家协商沟通！", "color" => "#4a5077"), 'orderProductPrice' => array('title' => '退款金额', 'value' => 'yen' . $refund['price'] . '元', "color" => "#4a5077"), 'orderProductName' => array('title' => '商品详情', 'value' => $goods, "color" => "#4a5077"), 'orderName' => array('title' => '订单编号', 'value' => $order['ordersn'], "color" => "#4a5077"), 'remark' => array('value' => $remark, "color" => "#4a5077"));
						if (!empty($tm['refund2']) && empty($usernotice['refund2'])) {
							m_m('message')->sendTplNotice($openid, $tm['refund2'], $msg, $detailurl);
						} else {
							if (empty($usernotice['refund2'])) {
								m_m('message')->sendCustomNotice($openid, $msg, $detailurl);
							}
						}
					}
				}
				return;
			}
		}
		$buyerinfo = '';
		if (!empty($order['addressid'])) {
			$address = M('zxin_shop_member_address')->field('id,realname,mobile,address,province,city,area')->where(array('id' => $order['addressid'], 'uniacid' => $_W['uniacid']))->find();
			if (!empty($address)) {
				$buyerinfo = "收件人: " . $address["realname"] . "\n联系电话: " . $address["mobile"] . "\n收货地址: " . $address["province"] . $address["city"] . $address["area"] . " " . $address["address"];
			}
		} else {
			$carrier = iunserializer($order["carrier"]);
			if (is_array($carrier)) {
				$buyerinfo = "联系人: " . $carrier["carrier_realname"] . "\n联系电话: " . $carrier["carrier_mobile"];
			}
		}
		if ($order['status'] == -1) {
			if (empty($order['dispatchtype'])) {
				$address = M('zxin_shop_member_address')->where(array('id' => $order['addressid'], 'uniacid' => $_W['uniacid']))->find();
				$orderAddress = array('title' => '收货信息', 'value' => '收货地址: ' . $address['province'] . ' ' . $address['city'] . ' ' . $address['area'] . ' ' . $address['address'] . ' 收件人: ' . $address['realname'] . ' 联系电话: ' . $address['mobile'], "color" => "#4a5077");
			} else {
				$carrier = iunserializer($order['carrier']);
				$orderAddress = array('title' => '收货信息', 'value' => '自提地点: ' . $carrier['address'] . ' 联系人: ' . $carrier['realname'] . ' 联系电话: ' . $carrier['mobile'], "color" => "#4a5077");
			}
			$msg = array('first' => array('value' => "您的订单已取消!", "color" => "#4a5077"), 'orderProductPrice' => array('title' => '订单金额', 'value' => 'yen' . $order['price'] . '元(含运费' . $order['dispatchprice'] . '元)', "color" => "#4a5077"), 'orderProductName' => array('title' => '商品详情', 'value' => $goods, "color" => "#4a5077"), 'orderAddress' => $orderAddress, 'orderName' => array('title' => '订单编号', 'value' => $order['ordersn'], "color" => "#4a5077"), 'remark' => array('value' => "\r\n【" . $shop['name'] . "】欢迎您的再次购物！", "color" => "#4a5077"));
			if (!empty($tm['cancel']) && empty($usernotice['cancel'])) {
				m_m('message')->sendTplNotice($openid, $tm['cancel'], $msg, $detailurl);
			} else {
				if (empty($usernotice['cancel'])) {
					m_m('message')->sendCustomNotice($openid, $msg, $detailurl);
				}
			}
		} else {
			if ($order['status'] == 0) {
				$newtype = explode(',', $tm['newtype']);
				if (empty($tm['newtype']) || is_array($newtype) && in_array(0, $newtype)) {
					$remark = "\n订单下单成功,请到后台查看!";
					if (!empty($buyerinfo)) {
						$remark .= "\r\n下单者信息:\n" . $buyerinfo;
					}
					$msg = array('first' => array('value' => "订单下单通知!", "color" => "#4a5077"), 'keyword1' => array('title' => '时间', 'value' => date('Y-m-d H:i:s', $order['createtime']), "color" => "#4a5077"), 'keyword2' => array('title' => '商品名称', 'value' => $goods, "color" => "#4a5077"), 'keyword3' => array('title' => '订单号', 'value' => $order['ordersn'], "color" => "#4a5077"), 'remark' => array('value' => $remark, "color" => "#4a5077"));
					$account = m_m('common')->getAccount();
					if (!empty($tm['openid'])) {
						$openids = explode(',', $tm['openid']);
						foreach ($openids as $tmopenid) {
							if (empty($tmopenid)) {
								continue;
							}
							if (!empty($tm['new'])) {
								m_m('message')->sendTplNotice($tmopenid, $tm['new'], $msg, '', $account);
							} else {
								m_m('message')->sendCustomNotice($tmopenid, $msg, '', $account);
							}
						}
					}
				}
				foreach ($order_goods as $og) {
					if (!empty($og['noticeopenid'])) {
						$noticetype = explode(',', $og['noticetype']);
						if (empty($og['noticetype']) || is_array($noticetype) && in_array(0, $noticetype)) {
							$goodstr = $og['title'] . '( ';
							if (!empty($og['optiontitle'])) {
								$goodstr .= " 规格: " . $og['optiontitle'];
							}
							$goodstr .= ' 单价: ' . $og['price'] / $og['total'] . ' 数量: ' . $og['total'] . ' 总价: ' . $og['price'] . "); ";
							$msg = array('first' => array('value' => "商品下单通知!", "color" => "#4a5077"), 'keyword1' => array('title' => '时间', 'value' => date('Y-m-d H:i:s', $order['createtime']), "color" => "#4a5077"), 'keyword2' => array('title' => '商品名称', 'value' => $goodstr, "color" => "#4a5077"), 'keyword3' => array('title' => '订单号', 'value' => $order['ordersn'], "color" => "#4a5077"), 'remark' => array('value' => "\r\n商品已经下单，请及时备货，谢谢!", "color" => "#4a5077"));
							if (!empty($tm['new'])) {
								m_m('message')->sendTplNotice($og['noticeopenid'], $tm['new'], $msg, '', $account);
							} else {
								m_m('message')->sendCustomNotice($og['noticeopenid'], $msg, '', $account);
							}
						}
					}
				}
				$msg = array('first' => array('value' => "您的订单已提交成功！", "color" => "#4a5077"), 'keyword1' => array('title' => '店铺', 'value' => $shop['name'], "color" => "#4a5077"), 'keyword2' => array('title' => '下单时间', 'value' => date('Y-m-d H:i:s', $order['createtime']), "color" => "#4a5077"), 'keyword3' => array('title' => '商品', 'value' => $goods, "color" => "#4a5077"), 'keyword4' => array('title' => '金额', 'value' => 'yen' . $order['price'] . '元(含运费' . $order['dispatchprice'] . '元)', "color" => "#4a5077"), 'remark' => array('value' => "\r\n您的订单我们已经收到，支付后我们将尽快配送~~", "color" => "#4a5077"));
				if (!empty($tm['submit']) && empty($usernotice['submit'])) {
					m_m('message')->sendTplNotice($openid, $tm['submit'], $msg, $detailurl);
				} else {
					if (empty($usernotice['submit'])) {
						m_m('message')->sendCustomNotice($openid, $msg, $detailurl);
					}
				}
			} else {
				if ($order['status'] == 1) {
					$newtype = explode(',', $tm['newtype']);
					if ($tm['newtype'] == 1 || is_array($newtype) && in_array(1, $newtype)) {
						$remark = "\n订单已经下单支付，请及时备货，谢谢!";
						if (!empty($buyerinfo)) {
							$remark .= "\r\n购买者信息:\n" . $buyerinfo;
						}
						$msg = array('first' => array('value' => "订单下单支付通知!", "color" => "#4a5077"), 'keyword1' => array('title' => '时间', 'value' => date('Y-m-d H:i:s', $order['createtime']), "color" => "#4a5077"), 'keyword2' => array('title' => '商品名称', 'value' => $goods, "color" => "#4a5077"), 'keyword3' => array('title' => '订单号', 'value' => $order['ordersn'], "color" => "#4a5077"), 'remark' => array('value' => $remark, "color" => "#4a5077"));
						$account = m_m('common')->getAccount();
						if (!empty($tm['openid'])) {
							$openids = explode(',', $tm['openid']);
							foreach ($openids as $tmopenid) {
								if (empty($tmopenid)) {
									continue;
								}
								if (!empty($tm['new'])) {
									m_m('message')->sendTplNotice($tmopenid, $tm['new'], $msg, '', $account);
								} else {
									m_m('message')->sendCustomNotice($tmopenid, $msg, '', $account);
								}
							}
						}
					}
					$remark = "\r\n商品已经下单支付，请及时备货，谢谢!";
					if (!empty($buyerinfo)) {
						$remark .= "\r\n购买者信息:\n" . $buyerinfo;
					}
					foreach ($order_goods as $og) {
						$noticetype = explode(',', $og['noticetype']);
						if ($og['noticetype'] == '1' || is_array($noticetype) && in_array(1, $noticetype)) {
							$goodstr = $og['title'] . '( ';
							if (!empty($og['optiontitle'])) {
								$goodstr .= " 规格: " . $og['optiontitle'];
							}
							$goodstr .= ' 单价: ' . $og['price'] / $og['total'] . ' 数量: ' . $og['total'] . ' 总价: ' . $og['price'] . "); ";
							$msg = array('first' => array('value' => "商品下单支付通知!", "color" => "#4a5077"), 'keyword1' => array('title' => '时间', 'value' => date('Y-m-d H:i:s', $order['createtime']), "color" => "#4a5077"), 'keyword2' => array('title' => '商品名称', 'value' => $goodstr, "color" => "#4a5077"), 'keyword3' => array('title' => '订单号', 'value' => $order['ordersn'], "color" => "#4a5077"), 'remark' => array('value' => $remark, "color" => "#4a5077"));
							if (!empty($tm['new'])) {
								m_m('message')->sendTplNotice($og['noticeopenid'], $tm['new'], $msg, '', $account);
							} else {
								m_m('message')->sendCustomNotice($og['noticeopenid'], $msg, '', $account);
							}
						}
					}
					$remark = "\r\n【" . $shop['name'] . "】欢迎您的再次购物！";
					if ($order['isverify']) {
						$remark = "\r\n点击订单详情查看可消费门店, 【" . $shop['name'] . "】欢迎您的再次购物！";
					}
					$msg = array('first' => array('value' => "您已支付成功订单！", "color" => "#4a5077"), 'keyword1' => array('title' => '订单', 'value' => $order['ordersn'], "color" => "#4a5077"), 'keyword2' => array('title' => '支付状态', 'value' => '支付成功', "color" => "#4a5077"), 'keyword3' => array('title' => '支付日期', 'value' => date('Y-m-d H:i:s', $order['paytime']), "color" => "#4a5077"), 'keyword4' => array('title' => '商户', 'value' => $shop['name'], "color" => "#4a5077"), 'keyword5' => array('title' => '金额', 'value' => 'yen' . $order['price'] . '元(含运费' . $order['dispatchprice'] . '元)', "color" => "#4a5077"), 'remark' => array('value' => $remark, "color" => "#4a5077"));
					$pay_detailurl = $detailurl;
					if (strexists($pay_detailurl, '/addons/ewei_shop/')) {
						$pay_detailurl = str_replace("/addons/ewei_shop/", '/', $pay_detailurl);
					}
					if (strexists($pay_detailurl, '/core/mobile/order/')) {
						$pay_detailurl = str_replace("/core/mobile/order/", '/', $pay_detailurl);
					}
					if (!empty($tm['pay']) && empty($usernotice['pay'])) {
						m_m('message')->sendTplNotice($openid, $tm['pay'], $msg, $pay_detailurl);
					} else {
						if (empty($usernotice['pay'])) {
							m_m('message')->sendCustomNotice($openid, $msg, $pay_detailurl);
						}
					}
					if ($order['dispatchtype'] == 1 && empty($order['isverify'])) {
						$carrier = iunserializer($order['carrier']);
						if (!is_array($carrier)) {
							return;
						}
						$msg = array('first' => array('value' => "自提订单提交成功!", "color" => "#4a5077"), 'keyword1' => array('title' => '自提码', 'value' => $order['ordersn'], "color" => "#4a5077"), 'keyword2' => array('title' => '商品详情', 'value' => $goods, "color" => "#4a5077"), 'keyword3' => array('title' => '提货地址', 'value' => $carrier['address'], "color" => "#4a5077"), 'keyword4' => array('title' => '提货时间', 'value' => $carrier['content'], "color" => "#4a5077"), 'remark' => array('value' => "\r\n请您到选择的自提点进行取货, 自提联系人: " . $carrier['realname'] . ' 联系电话: ' . $carrier['mobile'], "color" => "#4a5077"));
						if (!empty($tm['carrier']) && empty($usernotice['carrier'])) {
							m_m('message')->sendTplNotice($openid, $tm['carrier'], $msg, $detailurl);
						} else {
							if (empty($usernotice['carrier'])) {
								m_m('message')->sendCustomNotice($openid, $msg, $detailurl);
							}
						}
					}
				} else {
					if ($order['status'] == 2) {
						if (empty($order['dispatchtype'])) {
							$address = M('zxin_shop_member_address')->where(array("uniacid" => $_W['uniacid'], "id" => $order['addressid']))->find();
							if (empty($address)) {
								return;
							}
							$msg = array('first' => array('value' => "您的宝贝已经发货！", "color" => "#4a5077"), 'keyword1' => array('title' => '订单内容', 'value' => "【" . $order['ordersn'] . "】" . $goods, "color" => "#4a5077"), 'keyword2' => array('title' => '物流服务', 'value' => $order['expresscom'], "color" => "#4a5077"), 'keyword3' => array('title' => '快递单号', 'value' => $order['expresssn'], "color" => "#4a5077"), 'keyword4' => array('title' => '收货信息', 'value' => "地址: " . $address['province'] . ' ' . $address['city'] . ' ' . $address['area'] . ' ' . $address['address'] . "收件人: " . $address['realname'] . ' (' . $address['mobile'] . ') ', "color" => "#4a5077"), 'remark' => array('value' => "\r\n我们正加速送到您的手上，请您耐心等候。", "color" => "#4a5077"));
							if (!empty($tm['send']) && empty($usernotice['send'])) {
								m_m('message')->sendTplNotice($openid, $tm['send'], $msg, $detailurl);
							} else {
								if (empty($usernotice['send'])) {
									m_m('message')->sendCustomNotice($openid, $msg, $detailurl);
								}
							}
						}
					} else {
						if ($order['status'] == 3) {
							$sendtime = empty($order['dispatchtype']) ? $order['sendtime'] : $order['finishtime'];
							$msg = array('first' => array('value' => "亲, 您购买的宝贝已经确认收货!", "color" => "#4a5077"), 'keyword1' => array('title' => '订单号', 'value' => $order['ordersn'], "color" => "#4a5077"), 'keyword2' => array('title' => '商品名称', 'value' => $goods, "color" => "#4a5077"), 'keyword3' => array('title' => '下单时间', 'value' => date('Y-m-d H:i:s', $order['createtime']), "color" => "#4a5077"), 'keyword4' => array('title' => '发货时间', 'value' => date('Y-m-d H:i:s', $sendtime), "color" => "#4a5077"), 'keyword5' => array('title' => '确认收货时间', 'value' => date('Y-m-d H:i:s', $order['finishtime']), "color" => "#4a5077"), 'remark' => array('title' => '', 'value' => "\r\n【" . $shop['name'] . '】感谢您的支持与厚爱，欢迎您的再次购物！', "color" => "#4a5077"));
							if (!empty($tm['finish']) && empty($usernotice['finish'])) {
								m_m('message')->sendTplNotice($openid, $tm['finish'], $msg, $detailurl);
							} else {
								if (empty($usernotice['finish'])) {
									m_m('message')->sendCustomNotice($openid, $msg, $detailurl);
								}
							}
							$first = "买家购买的商品已经确认收货!";
							if ($order['isverify'] == 1) {
								$first = "买家购买的商品已经确认核销!";
							}
							$remark = "";
							if (!empty($buyerinfo)) {
								$remark = "\r\n购买者信息:\n" . $buyerinfo;
							}
							$newtype = explode(',', $tm['newtype']);
							if ($tm['newtype'] == 2 || is_array($newtype) && in_array(2, $newtype)) {
								$msg = array('first' => array('value' => $first, "color" => "#4a5077"), 'keyword1' => array('title' => '订单号', 'value' => $order['ordersn'], "color" => "#4a5077"), 'keyword2' => array('title' => '商品名称', 'value' => $goods, "color" => "#4a5077"), 'keyword3' => array('title' => '下单时间', 'value' => date('Y-m-d H:i:s', $order['createtime']), "color" => "#4a5077"), 'keyword4' => array('title' => '发货时间', 'value' => date('Y-m-d H:i:s', $sendtime), "color" => "#4a5077"), 'keyword5' => array('title' => '确认收货时间', 'value' => date('Y-m-d H:i:s', $order['finishtime']), "color" => "#4a5077"), 'remark' => array('title' => '', 'value' => $remark, "color" => "#4a5077"));
								$account = m('common')->getAccount();
								if (!empty($tm['openid'])) {
									$openids = explode(',', $tm['openid']);
									foreach ($openids as $tmopenid) {
										if (empty($tmopenid)) {
											continue;
										}
										if (!empty($tm['finish'])) {
											m_m('message')->sendTplNotice($tmopenid, $tm['finish'], $msg, '', $account);
										} else {
											m_m('message')->sendCustomNotice($tmopenid, $msg, '', $account);
										}
									}
								}
							}
							foreach ($order_goods as $og) {
								$noticetype = explode(',', $og['noticetype']);
								if ($og['noticetype'] == '2' || is_array($noticetype) && in_array(2, $noticetype)) {
									$goodstr = $og['title'] . '( ';
									if (!empty($og['optiontitle'])) {
										$goodstr .= " 规格: " . $og['optiontitle'];
									}
									$goodstr .= ' 单价: ' . $og['price'] / $og['total'] . ' 数量: ' . $og['total'] . ' 总价: ' . $og['price'] . "); ";
									$msg = array('first' => array('value' => $first, "color" => "#4a5077"), 'keyword1' => array('title' => '订单号', 'value' => $order['ordersn'], "color" => "#4a5077"), 'keyword2' => array('title' => '商品名称', 'value' => $goodstr, "color" => "#4a5077"), 'keyword3' => array('title' => '下单时间', 'value' => date('Y-m-d H:i:s', $order['createtime']), "color" => "#4a5077"), 'keyword4' => array('title' => '发货时间', 'value' => date('Y-m-d H:i:s', $sendtime), "color" => "#4a5077"), 'keyword5' => array('title' => '确认收货时间', 'value' => date('Y-m-d H:i:s', $order['finishtime']), "color" => "#4a5077"), 'remark' => array('title' => '', 'value' => $remark, "color" => "#4a5077"));
									if (!empty($tm['finish'])) {
										m_m('message')->sendTplNotice($og['noticeopenid'], $tm['finish'], $msg, '', $account);
									} else {
										m_m('message')->sendCustomNotice($og['noticeopenid'], $msg, '', $account);
									}
								}
							}
						}
					}
				}
			}
		}
	}
	/* 后续  会员升级   会员充值 */
}