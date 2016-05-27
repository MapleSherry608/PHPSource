<?php
    header("Content-type: text/html; charset=utf-8");
    global $_W, $_GPC;
    $arr = $_GPC = I();
    
    if(!empty($_GPC['openId'])){
        $openid = $_GPC['openId'];
    }else{
        $openid = m_m('user')->getOpenid();
    }
    
    session('openid',$openid);
    
    $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
    
    $uniacid                    = 0;
    $shop_member                = M('member');
    $core_paylog                = M('core_paylog');
    $shop_order                 = M('zxin_shop_order');
    $shop_order_goods           = M('zxin_shop_order_goods');
    $shop_goods                 = M('zxin_shop_goods');
    $shop_dispatch              = M('zxin_shop_dispatch');
    $shop_member_cart           = M('zxin_shop_member_cart');
    $shop_goods_option          = M('zxin_shop_goods_option');
    $shop_member_address        = M('zxin_shop_member_address');
    $shop_order_refund          = M('zxin_shop_order_refund');
    $shop_order_comment         = M('zxin_shop_order_comment');
    
    //用户信息
    $member    = $shop_member->where(array('openid'=>$openid))->find();
    //会员id
    $uid = $member['id'];
    
    //模版赋值
    $array['_W'] = $_W;
    $array['member'] = $member;
    $array['openid'] = $openid;
