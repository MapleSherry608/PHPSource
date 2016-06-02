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

class FansViewModel extends ViewModel {
     public $viewFields = array(
     'member_fans'=>array(
     			'id'=>'fanid',
     			'membid',
     			'openid',
     			'nickname',
     			'follow',
     			'followtime',
                'unfollowtime',
                '_type'=>'LEFT'
		),
     'member'=>array(
     		'id',
     		'avatar',
            '_on'=>'member_fans.membid=member.id'
      )
     );
}
