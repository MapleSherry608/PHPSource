<?php
    header("Content-type: text/html; charset=utf-8");
    global $_W, $_GPC;
    $arr = $_GPC = I();
    $uniacid                    = 0;
    
    $shopSet = m_m('common')->getSysset('shop');
    $shopSet['logo'] = tomedia($shopSet['logo']);
    $shopSet['img'] = tomedia($shopSet['img']);
    $shopSet['signimg'] = tomedia($shopSet['signimg']);
    $shopSet['catadvimg'] = tomedia($shopSet['catadvimg']);
    
    if(!empty($_GPC['openId'])){
        $openid = $_GPC['openId'];
    }else{
        $openid = m_m('user')->getOpenid();
    }
    session('openid',$openid);
    
    $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
    
    $shop_member                = M('member');
    $shop_order                 = M('zxin_shop_order');
    $shop_member_log            = M('zxin_shop_member_log');
    
    
    //用户信息
    $member    = $shop_member->where(array('openid'=>$openid))->find();
    //会员id
    $uid = $member['id'];
    
    //模版赋值
    $array['_W'] = $_W;
    $array['_GPC'] = $_GPC;
    $array['member'] = $member;
    $array['openid'] = $openid;
    $array['shopSet'] = $shopSet;