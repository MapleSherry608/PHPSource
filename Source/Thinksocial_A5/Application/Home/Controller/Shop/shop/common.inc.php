<?php
    header("Content-type: text/html; charset=utf-8");
    global $_W,$_GPC;
    $_GPC = I();
    if(!empty($_GPC['openId'])){
        $openid = $_GPC['openId'];
    }else{
        $openid = m_m('user')->getOpenid();
    }
    
    $shopSet = m_m('common')->getSysset('shop');
    $shopSet['logo'] = tomedia($shopSet['logo']);
    $shopSet['img'] = tomedia($shopSet['img']);
    $shopSet['signimg'] = tomedia($shopSet['signimg']);
    $shopSet['catadvimg'] = tomedia($shopSet['catadvimg']);
    $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
    
    $uniacid                    = 0;
    /**
     * 数据库表名
     * @var unknown
     */
    $shop_member                = M('member');
    $shop_adv                   = M('zxin_shop_adv');
    $shop_notice                = M('zxin_shop_notice');
    $shop_goods                 = M('zxin_shop_goods');
    $shop_store                 = M('zxin_shop_store');
    $shop_order                 = M('zxin_shop_order');
    $shop_category              = M('zxin_shop_category');
    $shop_order_goods           = M('zxin_shop_order_goods');
    $shop_goods_spec            = M('zxin_shop_goods_spec');
    $shop_goods_spec_item       = M('zxin_shop_goods_spec_item');
    $shop_goods_option          = M('zxin_shop_goods_option');
    $shop_goods_param           = M('zxin_shop_goods_param');
    $shop_member_favorite       = M('zxin_shop_member_favorite');
    $shop_member_history        = M('zxin_shop_member_history');
    $shop_member_cart           = M('zxin_shop_member_cart');
    $shop_member_address        = M('zxin_shop_member_address');
    $shop_order_comment         = M('zxin_shop_order_comment');
    
    //用户信息
    $member    = $shop_member->where(array('openid'=>$openid))->find();
    //会员id
    $uid = $member['id'];
    
    //模版赋值
    $array['_W'] = $_W;
    $array['_GPC'] = $_GPC;
    $array['member'] = $member;
    $array['shopSet'] = $shopSet;