<?php
require '../../ThinkPHP/Common/functions.php';
$map=array(
    'type'=>5000,
    'uniacid'=>12,
    'openid'=>'adfmidasfidshofiosd',
    'tid'=>14,
    'fee'=>12.5,
    'status'=>12,
    'module'=>'Refuel'
);
/*  $log = M('core_paylog')->where($map)->find();
 if($arr['trade_status'] == 'TRADE_FINISHED') {
 $log['status']=-1;
 }else if($arr['trade_status'] == 'TRADE_SUCCESS'){
 $log['status']=1;
 }else{
 $log['status']=5;
} */
M('core_paylog')->add($map);
exit();

