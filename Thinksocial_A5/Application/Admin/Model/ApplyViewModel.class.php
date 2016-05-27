<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class ApplyViewModel extends ViewModel{
    public $viewFields = array(
        'Registration'=>array('id','total_acount','status','add_time','signup_pics'),    
        'Member'=>array('realname','mobile','_on'=>'Registration.user_id=Member.id'),
        'Activity'=>array('title','if_fee','_on'=>'Registration.active_id=Activity.id'),
    );
}
?>