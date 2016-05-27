<?php
namespace Admin\Controller;

class ShopController extends AdminController{
    public function _empty(){
        $action=array(
           'index','goods','category','dispatch','adv','notice','comment'
        );
        
        if(in_array(ACTION_NAME,$action)){
            /**
             * 商品管理
             */
            if(ACTION_NAME=='goods'){
                include_once 'Application/Admin/Controller/Shop/goods.inc.php';
            }
            
            /**
             * 分类管理
             */
            if(ACTION_NAME=='category'){
                include_once 'Application/Admin/Controller/Shop/category.inc.php';
            }
            
            /**
             * 配送方式管理
             */
            if(ACTION_NAME=='dispatch'){
                include_once 'Application/Admin/Controller/Shop/dispatch.inc.php';
            }
            
            /**
             * 幻灯片管理
             */
            if(ACTION_NAME=='adv'){
                include_once 'Application/Admin/Controller/Shop/adv.inc.php';
            }
            
            /**
             * 公告管理
             */
            if(ACTION_NAME=='notice'){
                include_once 'Application/Admin/Controller/Shop/notice.inc.php';
            }
            
            /**
             * 评论管理
             */
            if(ACTION_NAME=='comment'){
                include_once 'Application/Admin/Controller/Shop/comment.inc.php';
            }

            /**
             * 商城入口
             */
            if(ACTION_NAME=='index'){
                include_once 'Application/Admin/Controller/Shop/index.inc.php';
            }
            
            $this->assign($array);
            $this->display(ACTION_NAME);
        }else{
            /**
             * tpl模版获取
             */
            if(ACTION_NAME=='tpl'){
                include_once 'Application/Admin/Controller/Shop/tpl.inc.php';
            }
            /**
             * 商品管理异步处理
             */
            if (ACTION_NAME == 'setgoodsproperty') {
                include_once 'Application/Admin/Controller/Shop/setgoodsproperty.inc.php';
            }
            
        }
    }
}