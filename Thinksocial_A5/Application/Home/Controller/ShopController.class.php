<?php
namespace Home\Controller;
class ShopController extends HomeController{
    public function _empty(){
        
        $action=array(
            'address','cart','category','detail','history','index','list','notice','util','favorite'
        );
        
        if(in_array(ACTION_NAME, $action)){
            /**
             * 首页
             */
            if(ACTION_NAME=='index'){
                include_once 'Application/Home/Controller/Shop/shop/index.inc.php';
            }
            
            /**
             * 商品详情 
             */
            if(ACTION_NAME=='detail'){
                include_once 'Application/Home/Controller/Shop/shop/detail.inc.php';
            }
            
            /**
             * 收藏
             */
            if(ACTION_NAME=='favorite'){
                include_once 'Application/Home/Controller/Shop/shop/favorite.inc.php';
            }
            
            /**
             * 我的足迹
             */
            if(ACTION_NAME=='history'){
                include_once 'Application/Home/Controller/Shop/shop/history.inc.php';
            }
            
            /**
             * 购物车
             */
            if(ACTION_NAME=='cart'){
                include_once 'Application/Home/Controller/Shop/shop/cart.inc.php';
            }
            
            /**
             * 分类
             */
            if(ACTION_NAME=='category'){
                include_once 'Application/Home/Controller/Shop/shop/common.inc.php';
            }
            
            /**
             * 全部商品
             */
            if(ACTION_NAME=='list'){
                include_once 'Application/Home/Controller/Shop/shop/list.inc.php';
            }
            
            /**
             * 收获地址
             */
            if(ACTION_NAME=='address'){
                include_once 'Application/Home/Controller/Shop/shop/address.inc.php';
            }
            
            /**
             * 消息通知
             */
            if(ACTION_NAME=='notice'){
                include_once 'Application/Home/Controller/Shop/shop/notice.inc.php';
            }
            
            /**
             * util 调用
             */
            if(ACTION_NAME=='util'){
                include_once 'Application/Home/Controller/Shop/shop/util.inc.php';
            }
            
            $this->assign($array);
            $this->display('Shop/default/shop/'.strtolower(ACTION_NAME));
        }else {
            $this->redirect('index');
        }
    }
}