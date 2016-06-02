<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class OrderViewModel extends ViewModel {
    public $viewFields = array(
        'zxin_shop_order'=>array(
            'id' => 'id',
            'uniacid' => 'uniacid',
            'openid' => 'openid',
            'ordersn' => 'ordersn',
            'price' => 'price',
            'goodsprice' => 'goodsprice',
            'discountprice' => 'discountprice',
            'sendtype' => 'sendtype',
            'paytype' => 'paytype',
            'transid' => 'transid',
            'goodstype' => 'goodstype',
            'remark' => 'remark',
            'addressid' => 'addressid',
            'expresscom' => 'expresscom',
            'expresssn' => 'expresssn',
            'express' => 'express',
            'dispatchid' => 'dispatchid',
            'dispatchprice' => 'dispatchprice',
            'status' => 'status',
            'paydetail' => 'paydetail',
            'createtime' => 'createtime',
            'sendtime' => 'sendtime',
            'paytime' => 'paytime',
            'refundid' => 'refundid',
            'deleted' => 'deleted',
            '_as'=>'o',
            '_type'=>'LEFT'
        ),
        'zxin_shop_order_refund'=>array(
            '_as'=>'r',
            '_on'=>'r.orderid=o.id',
            '_type'=>'LEFT'
        ),
        'member'=>array(
            'status'=>'refundstatus',
            'mobile'=>'m_mobile',
            'nickname'=>'nickname',
            '_as'=>'m',
            '_on'=>'m.openid=o.openid',
            '_type'=>'LEFT'
        ),
        'zxin_shop_member_address'=>array(
            'realname'=>'realname',
            'mobile'=>'a_mobile',
            'province'=>'province',
            'city'=>'city',
            'area'=>'area',
            'address'=>'address',
            '_as'=>'a',
            '_on'=>'o.addressid = a.id',
            '_type'=>'LEFT'
        ),
        'zxin_shop_dispatch'=>array(
            'dispatchname'=>'dispatchname',
            '_as'=>'d',
            '_on'=>'d.id = o.dispatchid',
            '_type'=>'LEFT'
        ),
    );
}