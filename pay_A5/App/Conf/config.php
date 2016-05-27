<?php
//---------------------------------
//开发: 小曾
//扣扣: 839024615
//官网: www.php127.com
//---------------------------------
$db = require('./Conf/db.php');
$tp = require('./Conf/config.php');
$form = require('./Conf/form.php');
$shop = require('./Conf/shop.php');
$config = array(
    //系统配置
    'APP_GROUP_LIST'	 => 'Home,User,Admin,Install', //项目分组
    'DEFAULT_GROUP' 	 => 'Home', //默认分组
    'TMPL_FILE_DEPR' 	 => '_',  //模板路由
    'URL_PATHINFO_DEPR'  => '/',  //URL路由
    'URL_MODEL'     	 => '1',  //URL模式
    'URL_CASE_INSENSITIVE'      =>true,
    //'SHOW_PAGE_TRACE'   => true, //显示页面Trace信息
    'pay_type' => array(
        'weixin' => '微信支付',
        'weixin-qrcode' => '微信二维码',
        'weixin-scanning' => '微信扫码',
        'alipay' => '支付宝支付',
        'alipay-qrcode' => '支付宝二维码',
        'alipay-scanning' => '支付宝扫码',
    ),
);
return array_merge($config,$shop,$form,$tp,$db);
?>