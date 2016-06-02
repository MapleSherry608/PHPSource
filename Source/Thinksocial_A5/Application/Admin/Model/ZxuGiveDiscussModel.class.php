<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model\ViewModel;
/**
 * 用户模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class ZxuGiveDiscussModel extends ViewModel {
     public $viewFields = array(
     //评论
     'zxu_give_discuss'=>array(
     			'id',
     			'assid',
     			'content',
     			'memberid',
                'createtime',
                '_type'=>'LEFT'
		),
     //有赏众帮   
     'zxu_give_assist'=>array(
         		'id'=>'aid',
         		'title',
                '_on'=>'zxu_give_assist.id=zxu_give_discuss.assid',
                '_type'=>'LEFT'
      ),
     //会员
     'member'=>array(
         'id'=>'mid',
         'nickname',
         '_on'=>'zxu_give_discuss.memberid=member.id'
     )    
     );
}
