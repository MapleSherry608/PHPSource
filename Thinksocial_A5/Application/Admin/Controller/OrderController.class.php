<?php
namespace Admin\Controller;
use Think\Page;
class OrderController extends AdminController{
    public function _empty(){
        $action=array('list');
        $Shop_OrderView          = D('OrderView');
        $Shop_OrderGoodsView     = D('OrderGoodsView');
        $Shop_Order              = M('zxin_shop_order');
        $Member                  = M('member');
        $core_paylog             = M('core_paylog');
        $Shop_dispatch           = M('zxin_shop_dispatch');
        $Shop_member_address     = M('zxin_shop_member_address');
        $Shop_order_refund       = M('zxin_shop_order_refund');
        $uniacid=0;
        if(in_array(ACTION_NAME,$action)){
            $operation = !empty($_GET['op']) ? $_GET['op'] : 'display';
            $pindex = max(1, intval(I('get.p')));
            $psize = 20;
            if($operation=='display'){
                $status = I('status');
                $sendtype = !isset($_GET['sendtype']) ? 0 : $_GET['sendtype'];
                $condition['uniacid']=$uniacid;
                $condition['deleted']=0;
                $starttime=0;
                $endtime=0;
                if (empty($starttime) || empty($endtime)) {
                    $starttime = strtotime('-1 month');
                    $endtime = time();
                }
                if (!empty($_POST['time'])) {
                    $starttime = strtotime($_POST['time']['start']);
                    $endtime = strtotime($_POST['time']['end']);
                    if ($_POST['searchtime'] == '1') {
                        $condition['o.createtime']=array('between',array($starttime,$endtime));
                    }
                }
                if ($_POST['paytype'] != '') {
                    if ($_POST['paytype'] == '2') {
                        $condition['o.paytype']=array('like',array(21,22,23),'OR');
                    } else {
                        $condition['o.paytype']=intval($_POST['paytype']);
                    }
                }
                if (!empty($_POST['keyword'])) {
                    $_POST['keyword'] = trim($_POST['keyword']);
                    $condition['o.ordersn']=array('like',"%{$_POST['keyword']}%");
                }
                if (!empty($_POST['expresssn'])) {
                    $_POST['o.expresssn'] = trim($_POST['expresssn']);
                    $condition['o.expresssn']=array('like',"%{$_POST['expresssn']}%");
                }
                if (!empty($_POST['member'])) {
                    $_POST['member'] = trim($_POST['member']);
                    $where['m.realname']=array('like',"%{$_POST['member']}%");
                    $where['m.mobile']=array('like',"%{$_POST['member']}%");
                    $where['m.nickname']=array('like',"%{$_POST['member']}%");
                    $where['a.realname']=array('like',"%{$_POST['member']}%");
                    $where['a.mobile']=array('like',"%{$_POST['member']}%");
                    
                    $where['_logic'] = 'OR';
                    $condition['_complex'] = $where;
                }
                if ($status != '') {
                    if ($status == '-1') {
                        $condition['o.status']=array('eq',-1);
                        $condition['r.status']=array('neq',1);
                    } else {
                        if ($status == '4') {
                            $condition['o.refundid']=array('neq',0);
                            $condition['r.status']=array('eq',0);
                        } else {
                            if ($status == '5') {
                                $condition['r.status']=array('eq',1);
                            } else {
                                $condition['o.status']=array('eq',intval($status));
                            }
                        }
                    }
                }
                $total = $Shop_OrderView->where($condition)->count();
                $totalmoney = $Shop_OrderView->where($condition)->getField('sum(o.price)');
                $page=new Page($total,$psize);
                $page->rollPage=10;
                $pageHtml=$page->show();
                $list= $Shop_OrderView->page($pindex,$psize)->where($condition)->select();
                $paytype = array(
                    '0' => array(
                        'css' => 'default',
                        'name' => '未支付'
                    ),
                    '1' => array(
                        'css' => 'danger',
                        'name' => '余额支付'
                    ),
                    '2' => array(
                        'css' => 'danger',
                        'name' => '在线支付'
                    ),
                    '3' => array(
                        'css' => 'primary',
                        'name' => '货到付款'
                    ),
                    '11' => array(
                        'css' => 'default',
                        'name' => '后台付款'
                    ),
                    '21' => array(
                        'css' => 'success',
                        'name' => '微信支付'
                    ),
                    '22' => array(
                        'css' => 'warning',
                        'name' => '支付宝支付'
                    ),
                    '23' => array(
                        'css' => 'warning',
                        'name' => '银联支付'
                    ),
                );
                $orderstatus = array(
                    '-1' => array(
                        'css' => 'default',
                        'name' => '已关闭'
                    ),
                    '0' => array(
                        'css' => 'danger',
                        'name' => '待付款'
                    ),
                    '1' => array(
                        'css' => 'info',
                        'name' => '待发货'
                    ),
                    '2' => array(
                        'css' => 'warning',
                        'name' => '待收货'
                    ),
                    '3' => array(
                        'css' => 'success',
                        'name' => '已完成'
                    )
                );
                foreach ($list as &$value) {
                    $s = $value['status'];
                    $value['statusvalue'] = $s;
                    $value['statuscss'] = $orderstatus[$value['status']]['css'];
                    $value['status'] = $orderstatus[$value['status']]['name'];
                    if ($s == -1) {
                        if ($value['refundstatus'] == 1) {
                            $value['status'] = '已退款';
                        }
                    }
                    $p = $value['paytype'];
                    $value['css'] = $paytype[$p]['css'];
                    $value['paytype'] = $paytype[$p]['name'];
                    $value['dispatchname'] = empty($value['addressid']) ? '自提' : $value['dispatchname'];
                    if (empty($value['dispatchname'])) {
                        $value['dispatchname'] = '快递';
                    }
                    if ($value['isverify'] == 1) {
                        $value['dispatchname'] = "线下核销";
                    }
                    if ($value['dispatchtype'] == 1 || !empty($value['isverify'])) {
                        $carrier = iunserializer($value['carrier']);
                        if (is_array($carrier)) {
                            $value['realname'] = $carrier['carrier_realname'];
                            $value['mobile'] = $carrier['carrier_mobile'];
                        }
                    } else {
                        $value['address'] = $value['province'] . " " . $value['city'] . " " . $value['area'] . " " . $value['address'];
                    }
                    $order_goods = $Shop_OrderGoodsView->where(array('uniacid'=>$uniacid,'orderid'=>$value['id']))->select();
                    $goods = '';
                    foreach ($order_goods as &$og) {
                        $goods .= "" . $og['title'] . "\r\n";
                        if (!empty($og['optiontitle'])) {
                            $goods .= " 规格: " . $og['optiontitle'];
                        }
                        if (!empty($og['option_goodssn'])) {
                            $og['goodssn'] = $og['option_goodssn'];
                        }
                        if (!empty($og['option_productsn'])) {
                            $og['productsn'] = $og['option_productsn'];
                        }
                        if (!empty($og['goodssn'])) {
                            $goods .= ' 商品编号: ' . $og['goodssn'];
                        }
                        if (!empty($og['productsn'])) {
                            $goods .= ' 商品条码: ' . $og['productsn'];
                        }
                        $goods .= ' 单价: ' . $og['price'] / $og['total'] . ' 折扣后: ' . $og['realprice'] / $og['total'] . ' 数量: ' . $og['total'] . ' 总价: ' . $og['price'] . " 折扣后: " . $og['realprice'] . "\r\n ";
                    }
                    unset($og);
                    $value['goods'] = set_medias($order_goods, 'thumb');
                    $value['goods_str'] = $goods;
                }
                unset($value);
                if ($_POST['export'] == 1) {
                    $columns = array(
                        array(
                            'title' => '订单编号',
                            'field' => 'ordersn',
                            'width' => 24
                        ),
                        array(
                            'title' => '收货姓名(或自提人)',
                            'field' => 'realname',
                            'width' => 12
                        ),
                        array(
                            'title' => '联系电话',
                            'field' => 'mobile',
                            'width' => 12
                        ),
                        array(
                            'title' => '收货地址',
                            'field' => 'address',
                            'width' => 12
                        ),
                        array(
                            'title' => '商品名称',
                            'field' => 'goods_title',
                            'width' => 24
                        ),
                        array(
                            'title' => '商品编码',
                            'field' => 'goods_goodssn',
                            'width' => 12
                        ),
                        array(
                            'title' => '商品规格',
                            'field' => 'goods_optiontitle',
                            'width' => 12
                        ),
                        array(
                            'title' => '商品数量',
                            'field' => 'goods_total',
                            'width' => 12
                        ),
                        array(
                            'title' => '商品单价(折扣前)',
                            'field' => 'goods_price1',
                            'width' => 12
                        ),
                        array(
                            'title' => '商品单价(折扣后)',
                            'field' => 'goods_price2',
                            'width' => 12
                        ),
                        array(
                            'title' => '商品价格(折扣后)',
                            'field' => 'goods_rprice1',
                            'width' => 12
                        ),
                        array(
                            'title' => '商品价格(折扣后)',
                            'field' => 'goods_rprice2',
                            'width' => 12
                        ),
                        array(
                            'title' => '支付方式',
                            'field' => 'paytype',
                            'width' => 12
                        ),
                        array(
                            'title' => '配送方式',
                            'field' => 'dispatchname',
                            'width' => 12
                        ),
                        array(
                            'title' => '运费',
                            'field' => 'dispatchprice',
                            'width' => 12
                        ),
                        array(
                            'title' => '总价',
                            'field' => 'price',
                            'width' => 12
                        ),
                        array(
                            'title' => '状态',
                            'field' => 'status',
                            'width' => 12
                        ),
                        array(
                            'title' => '下单时间',
                            'field' => 'createtime',
                            'width' => 24
                        ),
                        array(
                            'title' => '付款时间',
                            'field' => 'paytime',
                            'width' => 24
                        ),
                        array(
                            'title' => '发货时间',
                            'field' => 'sendtime',
                            'width' => 24
                        ),
                        array(
                            'title' => '完成时间',
                            'field' => 'finishtime',
                            'width' => 24
                        ),
                        array(
                            'title' => '快递公司',
                            'field' => 'expresscom',
                            'width' => 24
                        ),
                        array(
                            'title' => '快递单号',
                            'field' => 'expresssn',
                            'width' => 24
                        ),
                        array(
                            'title' => '订单备注',
                            'field' => 'remark',
                            'width' => 36
                        )
                    );
                    foreach ($list as &$row) {
                        $row['ordersn'] = $row['ordersn'] . " ";
                        $row['expresssn'] = $row['expresssn'] . " ";
                        $row['createtime'] = date('Y-m-d H:i:s', $row['createtime']);
                        $row['paytime'] = !empty($row['paytime']) ? date('Y-m-d H:i:s', $row['paytime']) : '';
                        $row['sendtime'] = !empty($row['sendtime']) ? date('Y-m-d H:i:s', $row['sendtime']) : '';
                        $row['finishtime'] = !empty($row['finishtime']) ? date('Y-m-d H:i:s', $row['finishtime']) : '';
                    }
                    unset($row);
                    $exportlist = array();
                    foreach ($list as &$r) {
                        $ogoods = $r['goods'];
                        unset($r['goods']);
                        foreach ($ogoods as $k => $g) {
                            if ($k > 0) {
                                $r['ordersn'] = '';
                                $r['realname'] = '';
                                $r['mobile'] = '';
                                $r['address'] = '';
                                $r['paytype'] = '';
                                $r['dispatchname'] = '';
                                $r['dispatchprice'] = '';
                                $r['price'] = '';
                                $r['status'] = '';
                                $r['createtime'] = '';
                                $r['sendtime'] = '';
                                $r['finishtime'] = '';
                                $r['expresscom'] = '';
                                $r['expresssn'] = '';
                                $r['remark'] = '';
                            }
                            $r['goods_title'] = $g['title'];
                            $r['goods_goodssn'] = $g['goodssn'];
                            $r['goods_optiontitle'] = $g['optiontitle'];
                            $r['goods_total'] = $g['total'];
                            $r['goods_price1'] = $g['price'] / $g['total'];
                            $r['goods_price2'] = $g['realprice'] / $g['total'];
                            $r['goods_rprice1'] = $g['price'];
                            $r['goods_rprice2'] = $g['realprice'];
                            $exportlist[] = $r;
                        }
                    }
                    unset($r);
                    getApi('model','excel')->export($exportlist, array("title" => "订单数据-" . date('Y-m-d-H-i', time()), "columns" => $columns));
                }
            }elseif ($operation=='detail'){
                $id = intval(I('id'));
                $item= $Shop_Order->where(array('id' => $id, 'uniacid' => $uniacid))->find();
                $item['statusvalue'] = $item['status'];
                if (empty($item)) {
                    $this->error('抱歉，订单不存在!');
                }
                $member = $Member->where(array('openid'=>$item['openid']))->find();
                $dispatch = $Shop_dispatch->where(array('id' => $item['dispatchid'], 'uniacid' => $uniacid))->find();
                if (empty($item['addressid'])) {
                    $user = unserialize($item['carrier']);
                } else {
                    $user = $Shop_member_address->where(array('id' => $item['addressid'], 'uniacid' => $uniacid))->find();
                    $user['address'] = $user['province'] . ' ' . $user['city'] . ' ' . $user['area'] . ' ' . $user['address'];
                }
                $refund = $Shop_order_refund->where(array('orderid' => $item['id'], 'uniacid' => $uniacid))->order('id desc')->find();
                $goods  = $Shop_OrderGoodsView->where(array('orderid' => $id, 'uniacid' => $uniacid))->select();
                foreach ($goods as &$r) {
                    if (!empty($r['option_goodssn'])) {
                        $r['goodssn'] = $og['option_goodssn'];
                    }
                    if (!empty($og['option_productsn'])) {
                        $r['productsn'] = $og['option_productsn'];
                    }
                }
                unset($r);
                $item['goods'] = $goods;
                $this->assign('item',$item);
                $this->assign('member',$member);
                $this->assign('dispatch',$dispatch);
                $this->assign('user',$user);
                $this->assign('refund',$refund);
                $this->assign('goods',$goods);
            }elseif ($operation=='delete'){
                $orderid = intval(I('id'));
                $Shop_Order->where(array('id' => $orderid, 'uniacid' => $uniacid))->save(array('deleted' => 1));
                $this->success('订单删除成功',U('Order/list', array('op' => 'display')));
            }elseif ($operation=='deal'){
                $id = intval(I('id'));
                $item = $Shop_Order->where(array('id' => $id, 'uniacid' => $uniacid))->find();
                if (empty($item)) {
                    $this->error('抱歉，订单不存在!',U(''));
                }
                $to = trim(I('to'));
                if ($to == 'confirmpay') {
                    $this->order_list_confirmpay($item);
                } else {
                    if ($to == 'cancelpay') {
                        $this->order_list_cancelpay($item);
                    } else {
                        if ($to == 'confirmsend') {//确认发货
                            $this->order_list_confirmsend($item);
                        } else {
                            if ($to == 'cancelsend') {//取消发货
                                $this->order_list_cancelsend($item);
                            } else {
                                if ($to == 'confirmsend1') {
                                    $this->order_list_confirmsend1($item);
                                } else {
                                    if ($to == 'cancelsend1') {
                                        $this->order_list_cancelsend1($item);
                                    } else {
                                        if ($to == 'finish') {//完成订单
                                            $this->order_list_finish($item);
                                        } else {
                                            if ($to == 'close') {//关闭订单
                                                $this->order_list_close($item);
                                            } else {
                                                if ($to == 'refund') {//同意退款
                                                    $this->order_list_refund($item);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                die;
            }
            
            $arr=array(
                'operation'=>$operation,
                'list'=>$list,
                'paytype'=>$paytype,
                'orderstatus'=>$orderstatus,
                'total'=>$total,
                'starttime'=>$starttime,
                'endtime'=>$endtime,
                'totalmoney'=>$totalmoney,
                'pageHtml'=>$pageHtml,
            );
            if($operation=='display'){
                $operation='list';
            }
            $this->assign('arr',$arr);
            $this->display($operation);
        }else{
            $this->redirect('list');
        }
    }
    /* ---------------------------处理订单start-------------------------- */
    //微信提醒
    function changeWechatSend($ordersn, $status, $msg = '')
    {
        global $_W;
        $paylog = M('core_paylog')->field('plid, openid, tag')->where("tid = '{$ordersn}' AND status = 1 AND type = 'wechat'")->find();
        if (!empty($paylog['openid'])) {
            /* $paylog['tag'] = iunserializer($paylog['tag']);
            $acid = $paylog['tag']['acid'];
            load()->model('account');
            $account = account_fetch($acid);
            $payment = uni_setting($account['uniacid'], 'payment');
            if ($payment['payment']['wechat']['version'] == '2') {
                return true;
            }
            $send = array('appid' => $account['key'], 'openid' => $paylog['openid'], 'transid' => $paylog['tag']['transaction_id'], 'out_trade_no' => $paylog['plid'], 'deliver_timestamp' => TIMESTAMP, 'deliver_status' => $status, 'deliver_msg' => $msg);
            $sign = $send;
            $sign['appkey'] = $payment['payment']['wechat']['signkey'];
            ksort($sign);
            $string = '';
            foreach ($sign as $key => $v) {
                $key = strtolower($key);
                $string .= "{$key}={$v}&";
            }
            $send['app_signature'] = sha1(rtrim($string, '&'));
            $send['sign_method'] = 'sha1';
            $account = WeAccount::create($acid);
            $response = $account->changeOrderStatus($send);
            if (is_error($response)) {
                message($response['message']);
            } */
        }
    }
    //处理回调
    function order_list_backurl()
    {
        global $_GPC;
        return $_GPC['op'] == 'detail' ? U('order/list') : U('');
    }
    //确认发货
    function order_list_confirmsend($item)
    {
        global $_W, $_GPC;
        if ($item['status'] != 1) {
            $this->error('订单未付款，无法发货！');
        }
        if (!empty($_GPC['isexpress']) && empty($_GPC['expresssn'])) {
            $this->error('请输入快递单号！');
        }
        if (!empty($item['transid'])) {
            $this->changeWechatSend($item['ordersn'], 1);
        }
        $Map = array('id' => $item['id'], 'uniacid' => $_W['uniacid']);
        $data = array('status' => 2, 'remark' => trim($_GPC['remark']), 'express' => trim($_GPC['express']), 'expresscom' => trim($_GPC['expresscom']), 'expresssn' => trim($_GPC['expresssn']), 'sendtime' => time());
        M('zxin_shop_order')->where($Map)->save($data);
        if (!empty($item['refundid'])) {
            $refund = M('zxin_shop_order_refund')->where(array('id' => $item['refundid']))->find();
            if (!empty($refund)) {
                M('zxin_shop_order_refund')->where(array('id' => $item['refundid']))->save(array('status' => -1));
                M('zxin_shop_order')->where(array('id' => $item['id']))->save(array('refundid' => 0));
            }
        }
        m_m('notice')->sendOrderMessage($item['id']);
        $this->success('发货操作成功！', $this->order_list_backurl(), 'success');
    }
    //取消发货
    function order_list_cancelsend($item)
    {
        global $_W, $_GPC;
        if ($item['status'] != 2) {
            $this->error('订单未发货，不需取消发货！');
        }
        if (!empty($item['transid'])) {
            $this->changeWechatSend($item['ordersn'], 0, $_GPC['cancelreson']);
        }
        $data = array('status' => 1, 'sendtime' => 0, 'remark' => $_GPC['remark']) ;
        $Map  = array('id' => $item['id'], 'uniacid' => $_W['uniacid']);
        M('zxin_shop_order')->where($Map)->save($data);
        $this->success('取消发货操作成功！', $this->order_list_backurl(), 'success');
    }
    //取消发货
    function order_list_cancelsend1($item)
    {
        global $_W, $_GPC;
        if ($item['status'] != 3) {
            $this->error('订单未取货，不需取消！');
        }
        M('zxin_shop_order')->where(array('status' => 1, 'finishtime' => 0, 'remark' => trim($_GPC['remark'])))->save(array('id' => $item['id'], 'uniacid' => $_W['uniacid']));
        $this->success('取消发货操作成功！', $this->order_list_backurl(), 'success');
    }
    //完成订单
    function order_list_finish($item)
    {
        global $_W, $_GPC;
        M('zxin_shop_order')->where(array('id' => $item['id'], 'uniacid' => $_W['uniacid']))->save(array('status' => 3, 'finishtime' => time(), 'remark' => $_GPC['remark']));
        m_m('member')->upgradeLevel($item['openid']);
        m_m('notice')->sendOrderMessage($item['id']);
       /* if (p('commission')) {
            p('commission')->checkOrderFinish($item['id']);
        } */
        $this->success('订单操作成功！', $this->order_list_backurl(), 'success');
    }
    //关闭订单
    function order_list_close($item)
    {
        global $_W, $_GPC;
        $shopset = m_m('common')->getSysset('shop');
        if (!empty($item['transid'])) {
            $this->changeWechatSend($item['ordersn'], 0, $_GPC['reson']);
        }
        M('zxin_shop_order')->where(array('id' => $item['id'], 'uniacid' => $_W['uniacid']))->save(array('status' => -1, 'canceltime' => time(), 'remark' => $_GPC['remark']));
        if ($item['deductprice'] > 0) {
            m_m('member')->setCredit($item['openid'], 'credit1', $item['deductcredit'], array('0', $shopset['name'] . "购物返还抵扣积分 积分: {$item['deductcredit']} 抵扣金额: {$item['deductprice']} 订单号: {$item['ordersn']}"));
        }
        $this->success('订单关闭操作成功！', $this->order_list_backurl(), 'success');
    }
    //同意退款
    function order_list_refund($item)
    {
        global $_W, $_GPC;
        $shopset = m_m('common')->getSysset('shop');
        if (empty($item['refundid'])) {
            $this->success('订单未申请退款，不需处理！');
        }
        $refund = M('zxin_shop_order_refund')->where(array('id' => $item['refundid'],'status'=>0))->find();
        if (empty($refund)) {
            M('zxin_shop_order')->where(array('id' => $item['id'], 'uniacid' => $_W['uniacid']))->save(array('refundid' => 0));
            $this->error('未找到退款申请，不需处理！');
        }
        if (empty($refund['refundno'])) {
            $refund['refundno'] = m_m('common')->createNO('order_refund', 'refundno', 'SR');
            M('zxin_shop_order_refund')->where(array('id' => $refund['id']))->save(array('refundno' => $refund['refundno']));
        }
        $refundstatus = intval($_GPC['refundstatus']);
        $refundcontent = $_GPC['refundcontent'];
        if ($refundstatus == 0) {
            $this->error('暂不处理',U(''));
        } else {
            if ($refundstatus == 1) {
                $realprice = $refund['price'];
                $join = " o left join sx_zxin_shop_goods g on o.goodsid=g.id ";
                $goods = M('zxin_shop_order_goods')->field(' g.credit, o.total,o.realprice ')->join($join)->where(array('o.orderid' => $item['id'], 'o.uniacid' => $_W['uniacid']))->select();
                $credits = 0;
                foreach ($goods as $g) {
                    $credits += $g['credit'] * $g['total'];
                }
                $refundtype = 0;
                if ($item['paytype'] == 1) {
                    m_m('member')->setCredit($item['openid'], 'credit2', $realprice, array(0, $shopset['name'] . "退款: {$realprice}元 订单号: " . $item['ordersn']));
                    $result = true;
                } else {
                    if ($item['paytype'] == 21) {
                        $realprice = round($realprice - $item['deductcredit2'], 2);
                        $result = m('finance')->refund($item['openid'], $item['ordersn'], $refund['refundno'], $realprice * 100);
                        $refundtype = 2;
                    } else {
                        if ($realprice < 1) {
                            $this->error('退款金额必须大于1元，才能使用微信企业付款退款!');
                        }
                        $realprice = round($realprice - $item['deductcredit2'], 2);
                        $result = m('finance')->pay($item['openid'], 1, $realprice * 100, $refund['refundno'], $shopset['name'] . "退款: {$realprice}元 订单号: " . $item['ordersn']);
                        $refundtype = 1;
                    }
                }
                if (is_error($result)) {
                    $this->error($result['message']);
                }
                m_m('member')->setCredit($item['openid'], 'credit1', -$credits, array(0, $shopset['name'] . "退款扣除积分: {$credits} 订单号: " . $item['ordersn']));
                if ($item['deductcredit'] > 0) {
                    m_m('member')->setCredit($item['openid'], 'credit1', $item['deductcredit'], array('0', $shopset['name'] . "购物返还抵扣积分 积分: {$item['deductcredit']} 抵扣金额: {$item['deductprice']} 订单号: {$item['ordersn']}"));
                }
                if (!empty($refundtype)) {
                    if ($item['deductcredit2'] > 0) {
                        m_m('member')->setCredit($item['openid'], 'credit2', $item['deductcredit2'], array('0', $shopset['name'] . "购物返还抵扣余额 积分: {$item['deductcredit2']} 订单号: {$item['ordersn']}"));
                    }
                }
                M('zxin_shop_order_refund')->where(array('id' => $item['refundid']))->save(array('reply' => '', 'status' => 1, 'refundtype' => $refundtype));
                m_m('notice')->sendOrderMessage($item['id'], true);
                M('zxin_shop_order')->where(array('id' => $item['id'], 'uniacid' => $_W['uniacid']))->save(array('refundid' => 0, 'status' => -1, 'refundtime' => time()));
            } else {
                if ($refundstatus == -1) {
                    M('zxin_shop_order_refund')->where(array('id' => $item['refundid']))->save(array('reply' => $refundcontent, 'status' => -1));
                    m_m('notice')->sendOrderMessage($item['id'], true);
                    M('zxin_shop_order')->where(array('id' => $item['id'], 'uniacid' => $_W['uniacid']))->save(array('refundid' => 0));
                }
            }
        }
        $this->success('退款申请处理成功！', $this->order_list_backurl(), 'success');
    }
    /* ---------------------------处理订单end-------------------------- */
}