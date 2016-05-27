<?php
include_once 'common.php';

$member = $shop_member->where(array('openid'=>$openid))->find();
$notice = iunserializer($member['noticeset']);
if(IS_AJAX){
    $operation = I('op');
    if(empty($operation)){
        $operation = 'display';
    }
    if ($operation == 'display') {
        show_json(1, array('notice' => $notice));
    } else {
        if ($operation == 'set' && IS_AJAX) {
            $on = I('on');
            $c = I('notice');
            if (empty($on)) {
                unset($notice[$c]);
            } else {
                $notice[$c] = $on;
            }
            $shop_member->where(array('openid' => $openid, 'uniacid' => $uniacid))->save(array('noticeset' => iserializer($notice)));
            show_json(1);
        }
    }
}