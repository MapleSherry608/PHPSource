<?php
namespace Home\Controller;
class MemberController extends HomeController{
    public function _empty(){
        $action=array(
            'center','info','notice','recharge'
        );
        
        if(in_array(ACTION_NAME, $action)){
            /**
             * 充值
             */
            if(ACTION_NAME == 'recharge'){
                include_once 'Application/Home/Controller/Shop/member/recharge.inc.php';
            }
            /**
             * 个人中心
             */
            if(ACTION_NAME=='center'){
                include_once 'Application/Home/Controller/Shop/member/center.inc.php';
            }
            /**
             * 我的资料
             */
            if(ACTION_NAME=='info'){
                include_once 'Application/Home/Controller/Shop/member/info.inc.php';
            }
            
            /**
             * 消息提醒设置
             */
            if(ACTION_NAME=='notice'){
                include_once 'Application/Home/Controller/Shop/member/notice.inc.php';
            }
            $this->assign($array);
            $this->display('Shop/default/member/'.strtolower(ACTION_NAME));
        }else {
            $this->redirect('Member/center');
        }
    }
}