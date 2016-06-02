<?php 
namespace Admin\Controller;
class EmulatorController extends AdminController{
    public function _empty(){
        $action=array('index');
        if(in_array(ACTION_NAME,$action)){
            if(ACTION_NAME=='index'){
                if(IS_AJAX){
                    $data=array('缺少api');
                    $this->ajaxReturn($data,'json');
                }
                $timestamp=NOW_TIME;
                $nonce=random(5);
                $token = 'omJNpZEhZeHj1ZxFECKkP48B5VFbk1HP';
                $signkey = array($token, TIMESTAMP, $nonce);
                sort($signkey, SORT_STRING);
                $signString = implode($signkey);
                $signString = sha1($signString);
                $arr=array(
                    'timestamp'=>$timestamp,
                    'nonce'=>$nonce,
                    'token'=>$token,
                    'signString'=>$signString,
                );
                $this->assign('arr',$arr);
            }
            $this->display('emulator/index');
        }else {
            $this->redirect(U('Emulator/index'));
        }
    }
}