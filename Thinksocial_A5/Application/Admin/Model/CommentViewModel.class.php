<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class CommentViewModel extends ViewModel {
    public $viewFields=array(
      'zxin_shop_order_comment'=>array(
          'id',
          'uniacid',
          'orderid',
          'goodsid',
          'openid',
          'nickname',
          'headimgurl',
          'level',
          'content',
          'images',
          'createtime',
          'deleted',
          'append_content',
          'append_images',
          'reply_content',
          'reply_images',
          'append_reply_content',
          'append_reply_images',
          '_as'=>'c',
          '_type'=>'left',
      ),  
      'zxin_shop_goods'=>array(
          'title'=>'title',
          'thumb'=>'thumb',
          '_as'=>'g',
          '_on'=>'c.goodsid = g.id',
          '_type'=>'left',
      ),  
      'zxin_shop_order'=>array(
          'ordersn'=>'ordersn',
          '_as'=>'o',
          '_on'=>'c.orderid = o.id',
      ),  
    );
}