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

class PropertyViewModel extends ViewModel {
     public $viewFields = array(
     'Property'=>array(
     			'id',
     			'propersn',
     			'orderid',
     			'membid',
     			'userid',
     			'productid',
     			'status',
     			'expect',
     			'figure',
     			'earnings',
     			'poundage',
     			'createtime',
     			'recordtime',
     			'ransom',
     			'ransomtime',
     			'_as'=>'prop',
     			'_type'=>'LEFT'
		),
     'Member'=>array(
     		'referrerid'=>'referrerid',
     		'realname'=>'realname',
     		'nickname'=>'membnickname',
     		'phone'=>'phone',
     		'email'=>'email',
     		'qq'=>'qq',
     		'idcard'=>'idcard',
     		'_as'=>'memb',
     		'_on'=>'prop.membid=memb.id',
     		'_type'=>'LEFT'
		),
     'User'=>array(
     		'nickname'=>'usenickname',
     		'sex'=>'sex',
     		'qq'=>'qq',
     		'_as'=>'user',
     		'_on'=>'prop.userid=user.uid',
     		'_type'=>'LEFT'
		),
     'product'=>array(
     		'productname',
     		'_as'=>'prod',
     		'_on'=>'prop.productid=prod.id',
		)
	
   );
}
