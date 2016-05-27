<?php
    header("Content-type: text/html; charset=utf-8");
    
    global $_W,$_GPC;
    $_GPC = I();
    
    $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
    
    $uniacid=0;//区分公众号
    $catlevel=2;//分类等级 默认2级
    $shop_goods             =M('zxin_shop_goods');//商品
    $shop_order             =M('zxin_shop_order');//商品
    $shop_category          =M('zxin_shop_category');//分类
    $shop_dispatch          =M('zxin_shop_dispatch');//配送方式
    $shop_adv               =M('zxin_shop_adv');//幻灯片
    $shop_notice            =M('zxin_shop_notice');//公告
    $shop_goods_spec        =M('zxin_shop_goods_spec');//规格
    $shop_goods_spec_item   =M('zxin_shop_goods_spec_item');//规格项
    $shop_goods_param       =M('zxin_shop_goods_param');//自定义属性
    $shop_goods_option      =M('zxin_shop_goods_option');//规格属性
    $shop_store             =M('zxin_shop_store');//线下门店
    $shop_comment_view      =D('CommentView');//评论视图
    $shop_order_comment     =M('zxin_shop_order_comment');//评论
    $member                 =M('member');//会员
    
    $condiction['uniacid']=$uniacid;
    
    $array['_W'] = $_W;
    $array['uniacid'] = $uniacid;
    $array['catlevel'] = $catlevel;