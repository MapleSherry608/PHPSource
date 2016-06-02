<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class OrderGoodsViewModel extends ViewModel {
    public $viewFields=array(
        'zxin_shop_order_goods'=>array(
            'total'=>'total',
            'price'=>'price',
            'orderid'=>'orderid',
            'goodsid'=>'goodsid',
            'optionname'=>'optiontitle',
            
            '_as'=>'og',
            '_type'=>'left',
        ),
        'zxin_shop_goods'=>array(
            'id'=>'id',
            'pcate'=>'pcate',
            'uniacid'=>'uniacid',
            'ccate'=>'ccate',
            'type'=>'type',
            'status'=>'status',
            'displayorder'=>'displayorder',
            'title'=>'title',
            'thumb'=>'thumb',
            'unit'=>'unit',
            'description'=>'description',
            'content'=>'content',
            'goodssn'=>'goodssn',
            'productsn'=>'productsn',
            'productprice'=>'productprice',
            'marketprice'=>'marketprice',
            'costprice'=>'costprice',
            'originalprice'=>'originalprice',
            'total'=>'goodstotal',
            'totalcnf'=>'totalcnf',
            
            'sales','salesreal',
            'spec','createtime',
            'weight','credit',
            'maxbuy','usermaxbuy',
            'hasoption', 'dispatch',
            'thumb_url', 'isnew',
            'ishot', 'isdiscount',
            'isrecommand', 'issendfree',
            'istime','iscomment',
            'viewcount','deleted',
            'viewcount','score',
            'taobaoid', 'taobaourl', 'updatetime',
            'share_title', 'cash', 'isnodiscount',
            'showlevels', 'buylevels',
            'showgroups',  'noticeopenid',
            'tcate',  'noticetype',
            'needfollow','followtip','followurl',

            '_as'=>'g',
            '_on'=>'g.id=og.goodsid',
        ),
    );
}