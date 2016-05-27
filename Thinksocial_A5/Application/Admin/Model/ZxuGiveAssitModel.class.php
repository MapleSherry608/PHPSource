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

class ZxuGiveAssitModel extends ViewModel {
     public $viewFields = array(
     //有赏众帮
     'zxu_give_assist'=>array(
     			'id',
     			'title',
     			'description',
     			'content',
                'status',
     			'thumb',
     			'createtime',
                'updatetime',
                'validTime',
                'uid',
                'display',
                'likecount',
                'likescore'
		),
     //管理员    
     'user'=>array(
         		'id'=>'userid',
         		'username',
                '_on'=>'zxu_give_assist.uid=user.id'
      )
    /*  //评论    
     'zxu_give_discuss'=>array(
             'id'=>'disid',
             'assid'=>'dis_assid',
             'content'=>'dis_content',
             'memberid',
             'createtime'=>'dis_createtime',
             '_on'=>'zxu_give_assist.id=zxu_give_discuss.assid'
     ),
     //题目    
     'zxu_give_option'=>array(
         'id'=>'optionid',
         'title'=>'opt_title',
         'assid'=>'op_assid',
         'answer',
         'voteCount',
         'creattime'=>'op_creattime',
         '_on'=>'zxu_give_assist.id=zxu_give_option.assid'
     )      */   
     );
}
