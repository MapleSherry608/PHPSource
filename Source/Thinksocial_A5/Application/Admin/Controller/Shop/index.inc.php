<?php
include_once 'common.inc.php';
$path=IA_ROOT.'Uploads/Data/perm';
$cachefile = $path . "/ShopIndex";
if(!is_dir($path)){
    @mkdir($path);
}
$url=SITEROOT."index.php?s=/Home/Shop/index";
if(IS_POST){
    $data=array(
        'url'=>$url,
        'thumb'=>I('thumb'),
        'title'=>I('title'),
        'keywords'=>I('keywords'),
        'description'=>I('description'),
    );
    file_put_contents($cachefile, iserializer($data));
    $this->success('商城入口更新成功~~~',U('shop/index',array('op'=>'display')));
}
$list = iunserializer(@file_get_contents($cachefile));
$this->assign('operation',$operation);
$this->assign('arr',$list);
$this->assign('url',$url);