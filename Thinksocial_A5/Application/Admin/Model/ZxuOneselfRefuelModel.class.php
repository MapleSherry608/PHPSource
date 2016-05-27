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

class ZxuOneselfRefuelModel extends ViewModel {
     public $viewFields = array(
     //土豪发布
     'zxu_oneself_refuel'=>array(
     			 'id',
     			 'membid',               
     			 'imgurl',
     			 'thumb_url',
                 'details',
                 'score',
                 'createtime',
                 'type',
                 'contribute',
                 'contrtime',
                 'contrstatus',
                 'payissue',
                 'paytime',
                 'payscore',
                 'paystatus'
		),
     //会员
     'member'=>array(
                 'id'=>'mid',
                 'nickname',
                 '_on'=>'zxu_oneself_refuel.membid=member.id'
     )    
     );
}
