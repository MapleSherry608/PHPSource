<?php
include_once 'common.php';
if (IS_AJAX) {
    $member['credit1'] = number_format($member['score'], 0);
    $member['credit2'] = number_format($member['deposit'], 2);
    $level = array('levelname' => empty($shopSet['shop']['levelname']) ? '普通会员' : $shopSet['shop']['levelname']);
    if (!empty($member['level'])) {
        $level = 1;/* 默认为1级会员 TODO */
    }
    $orderparams = array('uniacid' => $uniacid, 'openid' => $openid);
    $status0=array('status'=>0);
    $status1=array('status'=>1,'refundid'=>0);
    $status2=array('status'=>2,'refundid'=>0);
    $status4=array('refundid'=>array('neq',0));
    $order = array(
        'status0' => $order = $shop_order->where(array_merge($orderparams,$status0))->count(),
        'status1' => $order = $shop_order->where(array_merge($orderparams,$status1))->count(),
        'status2' => $order = $shop_order->where(array_merge($orderparams,$status2))->count(),
        'status4' => $order = $shop_order->where(array_merge($orderparams,$status4))->count()
    );
    if (mb_strlen($member['nickname'], 'utf-8') > 6) {
        $member['nickname'] = mb_substr($member['nickname'], 0, 6, 'utf-8');
    }
    $open_creditshop = false;//是否开启积分商城
    /* $creditshop = p('creditshop');
     if ($creditshop) {
     $creditshop_set = $creditshop->getSet();
     if (!empty($creditshop_set['centeropen'])) {
     $open_creditshop = true;
     }
     } */
    show_json(1, array('member' => $member, 'order' => $order, 'level' => $level, 'open_creditshop' => $open_creditshop));
}