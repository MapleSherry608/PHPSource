<?php
//---------------------------------
//开发: 小曾
//扣扣: 839024615
//官网: www.php127.com
//---------------------------------
//取英文支付名称
function get_pay_type($name){
    $type=C('pay_type');
    return  $type[$name];
}
//分转元
function get_price($price){
    return $price/100;
}
//写日志
function get_log($str){
    $open=fopen("logs/".date("Y-m-d").".txt","a" );
    $time = Date('H:i:s');
    fwrite($open,$time.' '.$str."\r\n");
    fclose($open);
}
