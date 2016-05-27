<?php
global $_W, $_GPC;
$sitepath = substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'));
$_W['siteroot'] = htmlspecialchars('http://' . $_SERVER['HTTP_HOST'] . $sitepath);
if(substr($_W['siteroot'], -1) != '/') {
    $_W['siteroot'] .= '/';
}
$urls = parse_url($_W['siteroot']);
$urls['path'] = str_replace(array('/web', '/app', '/payment/wechat', '/payment/alipay', '/api'), '', $urls['path']);
$_W['siteroot'] = $urls['scheme'].'://'.$urls['host'].((!empty($urls['port']) && $urls['port']!='80') ? ':'.$urls['port'] : '').$urls['path'];
$_W['siteurl'] = $urls['scheme'].'://'.$urls['host'].((!empty($urls['port']) && $urls['port']!='80') ? ':'.$urls['port'] : '') . $_W['script_name'] . (empty($_SERVER['QUERY_STRING'])?'':'?') . $_SERVER['QUERY_STRING'];
unset($sitepath);

$_W['isajax'] = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
$_W['ispost'] = $_SERVER['REQUEST_METHOD'] == 'POST';

if(!$_W['isajax']) {
    $input = file_get_contents("php://input");
    if(!empty($input)) {
        $__input = @json_decode($input, true);
        if(!empty($__input)) {
            $_GPC['__input'] = $__input;
            $_W['isajax'] = true;
        }
    }
    unset($input, $__input);
}
$account=M("member_public")->field('appid,secret')->find();
$_W['account'] = $account;
define('UNIACID',0);
define('ZXIN_SHOP_DEBUG', false);
