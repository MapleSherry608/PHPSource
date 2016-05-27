<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 撒哈拉的寂寞 <1032453491@qq.com>
// +----------------------------------------------------------------------
namespace Home\Controller;
/**
 * 官方活动
 * @author Administrator
 */
class ActivityController extends HomeController{
    private $psize=5;
    
    /**
     * 扫码签到
     */
    public function scan(){
        $params=I();
        if(!$params){
            $this->success("签到错误！请于管理员联系");
        }
        $map=array(
            'active_id'=>$params['activeid'],
            'user_id'=>$params['userid']
        );
        $applyed=M("Registration")->field('id,status')->where($map)->find();
        $realname=M('Member')->where('id='.$params['userid'])->getField('realname');
        if(empty($applyed)){
            $this->success("签到失败！该报名信息不存在！");
        }else{
            if($applyed['status']==4){
                $this->success("您已签到！");
            }else{
                M("Registration")->where('id='.$applyed['id'])->save(array('status'=>4));
                $this->success("签到成功！".$applyed['id']." ".$realname);
            }
        }
    }
    
    /**
     * 扫码管理员登录
     */
    public function sweepBarcode(){
        $params=I();
        $code=session('scancode');
        if($code==null){
            if($_POST){
                $codeid=strval($params['codeid']);
                $scancodeiden=M("Activity")->where(array('id'=>$params['activeid']))->getField('scancodeiden');
                if($codeid==$scancodeiden){
                    session('scancode',$codeid);
                    $this->redirect('Home/Activity/scan',array('activeid'=>$params['activeid'],'userid'=>$params['userid']),1);
                }else{
                    $this->success('扫码标识有误！');
                }
            }
            //加载页面
            $this->assign('params',$params);
            $this->display('Activity/signPage');
        }else{
            $this->redirect('Home/Activity/scan',array('activeid'=>$params['activeid'],'userid'=>$params['userid']),1);
        }
    }
    
    /**
     * 活动支付
     */
    public function payment(){
        $uid=MEMBID;
        $regId=I('id',0,'strval');
        if(!$regId){
           $this->error("报名信息不存在");
           exit();
        }
        $applyInfo=M('Registration')->where(array('id'=>$regId))->find();
        $activeId=intval($applyInfo['active_id']);
        //活动当前报名人数
        $totalInfo=M('Registration')->field(array('SUM(total_acount)'=>'totalNum'))->where(array('active_id'=>$activeId,'status'=>array('egt'=>3)))->select();
        $activeInfo=M('Activity')->field('title,max_acount')->where(array('id'=>$activeId))->find();
        $maxCount=intval($activeInfo['max_acount']);
        if($totalInfo['totalNum']>=$maxCount){
            $this->error("对不起你下手太慢，下次记得加快速度哦！");
            exit();
        }
        if($totalInfo['totalNum']+$applyInfo['total_acount']>$maxCount){
            $this->error("剩余名额不足，请减少报名人数！");
            exit();
        }
       $orderModel=M("ActiveOrder");
       $orderInfo = $orderModel->where(array('uid'=>$uid,'apply_id'=>$regId))->find();
       if(empty($orderInfo)){
            $data=array(
                'from_user'=> $this->openid,
                'uid'=>$uid,
                'ordersn'=>date('YmdHis').$applyInfo['id'],
                'status'=>0,
                'apply_id'=>$regId,
                'title'=>$activeInfo['title'],
                'price'=>$applyInfo['total_fee'],
            );
            $orderId=$orderModel->add($data);
            $orderInfo=$orderModel->where('id='.$orderId)->find();
        }else{
            if($orderInfo['status']==1){
                $this->success("该活动已付款！");
                exit();
            }
        }
        $params['ordersn'] =$orderInfo['ordersn'];
        $params['user'] =$orderInfo['from_user'];
        $params['fee'] = $orderInfo['price'];
        $params['title'] = $orderInfo['title'];
        $this->optionPay($params);
    }
    
    /**
     * 活动报名
     */
    public function applyOption(){
        $activeId=I('activeid',0,'intval');
        $uid=MEMBID;
        $activeInfo=M('Activity')->where(array('id'=>$activeId))->find();
        //二维码路径
        $location=SITEROOT."index.php?module=Home&controller=Activity&action=sweepBarcode&userid=".$uid."&activeid=".$activeId;
        $comper_time=strtotime(date('Y-m-d H:i:s'));
        //未到报名时间时报名
        if($activeInfo['start_time']>$comper_time){
            echo '<p>报名还没开始哦！亲！</p>';
            exit();
        }
        //报名时间超过截止日期
        if($activeInfo['end_time']<$comper_time){
            echo '<p>报名已结束哦！亲！</p>';
            exit();
        }
        $activeApply=M('Registration')->field(array('SUM(total_acount)'=>'totalNum','SUM(signup_acount)'=>'signupNum','SUM(children_acount)'=>'childNum'))->where(array('active_id'=>$activeId,'status'=>array('neq'=>2)))->select();
        
        //判断报名人数已满
        if($activeApply['totalNum']>=$activeInfo['max_acount']){
            echo '<p>本活动报名人数已满...</p>';
            exit();
        }
        
        $applyData=array(
            'user_id'=>$uid,
            'active_id'=>$activeId,
            'add_time'=>$comper_time,
            'location'=>$this->getBuildQrCode($location)
        );
        //活动需要审核设置状态为待审核
        if($activeInfo['if_auditing']==1){
            $applyData['status']=0;//待审核
        }else{
            //不需要审核的活动判断是否需要收费
            if($activeInfo['if_fee']==1){
                $applyData['status']=1;//不审核要收费
            }else{
                $applyData['status']=3;//成功-》既不审核也不收费
            }
        }
        
        $childNum=I('childNum',0,'intval');
        $personNum=I('personNum',0,'intval');
        if($activeInfo['if_persion']==1){
            //人数
            $applyData['signup_acount']=$personNum;//成人数
            $applyData['children_acount']=$childNum;//儿童数
            $applyData['total_acount']=$applyData['signup_acount']+$applyData['children_acount'];
            //费用
            $applyData['signup_fee']=$activeInfo['active_fee']*$personNum;//成人费用
            $applyData['children_fee']=$activeInfo['child_fee']*$childNum;//儿童费用
            $applyData['total_fee']=$applyData['signup_fee']+$applyData['children_fee'];
        
            //判断报名人数是否超过限定人数(一个微信号报多个名)
            if($applyData['total_acount']-1>$activeInfo['wechatmaxnum']){
                echo '<p>人数超过已限定...</p>';
                exit();
            }
        }else{
            //人数
            $applyData['signup_acount']=1;
            $applyData['children_acount']=0;
            $applyData['total_acount']=1;
            //费用
            $applyData['signup_fee']=$activeInfo['active_fee'];
            $applyData['children_fee']=0;
            $applyData['total_fee']=$activeInfo['active_fee'];
        }
        if($applyInfo['if_show_pic']==1){
            $applyData['signup_pics']=$_GPC['imgUrl'];
        }

        //报名人数过多
        if($activeApply['totalNum']+$applyData['total_acount']>$activeInfo['max_acount']){
            echo '<p>对不起，您报名人数超过规定的总人数...</p>';
            exit();
        }
       
        $applyed=M('Registration')->field('id')->where(array('active_id'=>$activeId,'user_id'=>$uid,'status'=>array('neq'=>2)))->find();
        //已报名
        if($applyed){
            echo '<p>你已经报名本活动，请给朋友一点机会哦...</p>';
            exit();
        }else{
            //添加报名信息
            $resultId=M('Registration')->add($applyData);
            $applyBase=M('ApplyInfo')->field('info_name')->where(array('active_id'=>$activeId))->select();
            $basePreperty=I('inputArray');
            $realname=I('realname','','strval');
            $mobile=I('mobile','','strval');
            $realnameData=array(
                'user_info'=>'姓名',
                'user_value'=>$realname,
                'signup_id'=>$resultId,
                'add_time'=>$comper_time
            );
            M('Property')->add($realnameData);//添加姓名
            $mobileData=array(
                'user_info'=>'手机',
                'user_value'=>$mobile,
                'signup_id'=>$resultId,
                'add_time'=>$comper_time
            );
            M('Property')->add($mobileData);//添加手机
            if($realname&&$mobile){
                $memberData=array(
                    'realname'=>$realname,
                    'mobile'=>$mobile
                );
                M('Member')->where('id='.$uid)->save($memberData);
            }
            //追加报名填写的信息
            foreach ($applyBase as $key=>$value){
                $baseData=array(
                    'user_info'=>$value['info_name'],
                    'user_value'=>$basePreperty[$key],
                    'signup_id'=>$resultId,
                    'add_time'=>$comper_time
                );
                M('Property')->add($baseData);
            }
            //成功显示二维码
            if($applyData['status']==3){
                echo tomedia($applyData['location']);
            }else if($applyData['status']==0){
                echo '<p>报名正在审核中...</p>';//否则显示状态
            }else{
                $url=U('Activity/payment',array('id'=>$resultId));
                echo $url;
            }
        }
        exit();
    }
    
    /**
     * 添加评论
     */
    public function addComment(){
        $uid=MEMBID;
        $activeId=I('activeid',0,'intval');
        $content=I('content','','strip_tags');
        //评论信息
        $commentData=array(
            'membid'=>$uid,
            'activeid'=>$activeId,
            'content'=>$content,
            'commenttype'=>2,
            'createtime'=>strtotime(date('Y-m-d H:i:s'))
        );
    
        $result=M("PunchComment")->add($commentData);
        if($result>0){
            $memberInfo=$this->getMembersInfo($uid);
            $html="<div class='actReviewBox'>".
                "<div class='myReviewBox'>".
                "<div class='myHeadBox'><a><img src='".$memberInfo['avatar']."'></a></div>".
                "<div class='myReviewContent'>".
                "<h1><span>".$memberInfo['nickname']."</span>".date('Y-m-d H:i:s',$commentData['createtime'])."</h1>".
                "<p>".$commentData['content']."</p>".
                "</div>".
                "</div>".
                "</div>";
            echo $html;
        }
    }
    
    /**
     * ajax加载评论
     */
    public function ajaxComment(){
        $nextNumber=I('nextNumber',0,'intval');
        $activeId=I('activeid',0,'intval');
        $commentList=$this->disposeComment($activeId,$nextNumber);
        $nextNumber+=$this->psize;
        $this->assign('nextNumber',$nextNumber);
        $this->assign('commentList',$commentList);
        $this->display('Activity/comment_item');
    }
    
    /**
     * 显示详情
     */
    public function detail(){
        $uid=MEMBID;
        $activeid=I('activeid',0,'intval');
        if(!$activeid){
            $this->error('活动不存在！');
            exit();
        }
        $activeInfo=M('Activity')->field('id,title,movement_pic,detailaddress,end_time,initiator,active_fee,active_begin_time,active_end_time,content,if_persion,if_show_pic')
                    ->where(array('id'=>$activeid))->find();
        if(!empty($activeInfo)){
            $activeid=intval($activeInfo['id']);
            $commentList=$this->disposeComment($activeid);
            $this->assign('nextNumber',$this->psize);
            $activeInfo['commentCount']=M("PunchComment")->where(array('status'=>1,'activeid'=>$activeid,'commenttype'=>2))->count();
            if($uid){
                $memberInfo=M('Member')->field('id,realname,nickname,mobile')->where(array('id'=>$uid))->find();
                $memberInfo['realname']=empty($memberInfo['realname'])?$memberInfo['nickname']:$memberInfo['realname'];
                $applyed=M('Registration')->field('id,status,location')->where(array('active_id'=>$activeid,'user_id'=>$uid))->find();
            }
            $applyInfo = M('ApplyInfo')->field('id,info_type,isRequired,info_name')->where(array('active_id'=>$activeid))->select();
            foreach ($applyInfo as $key=>$value){
                $infoId=intval($value['id']);//报名项ID
                $applyInfo[$key]['child']=M('InfoProperty')->field('text')->where(array('apply_info_id'=>$infoId))->select();
            }
        }
        $this->assign('userInfo',$memberInfo);
        $this->assign('applyed',$applyed);
        $this->assign('applyInfo',$applyInfo);
        $this->assign('activeInfo',$activeInfo);
        $this->assign('commentList',$commentList);
        $this->display('Activity/activeDetail');
    }
    /**
     * 异步加载活动数据
     */
    public function ajaxIndex(){
        $nextNumber=I('nextNumber',0,'intval');
        $timeDate=I('timeDate','default_time','strval');
        $cateId=I('cateId',0,'intval');
        $map=array();
        if($timeDate&&$timeDate!='default_time'){
            $map['_string'] = " FROM_UNIXTIME(active_begin_time, '%Y-%m-%d' ) = '".$timeDate."'";
        }
        if($cateId){
            $map['second_cate_id']=$cateId;
        }
        $activityList=$this->disposeIndex($map,$nextNumber);
        $nextNumber+=$this->psize;
        $this->assign('nextNumber',$nextNumber);
        $this->assign('activityList',$activityList);
        $this->display('Activity/index_item');
    }
    
    /**
     * 活动首页
     */
    public function index(){
        //活动分类
        $activeCategory = M('ActiveCategory')->field('id,name,parentid')->where(array('enabled'=>1))->select();
        $cateList=list_to_tree($activeCategory,'id','parentid');
        //活动列表
        
        $activityList=$this->disposeIndex();
        $this->assign('cateList',$cateList);
        $this->assign('activityList',$activityList);
        $this->assign('nextNumber',$this->psize);
        $this->display('Activity/index');
    }
    
    /*************************************************************************************************************/
    /*================================================私有方法====================================================*/
    /*************************************************************************************************************/
    /**
     * 支付完成时参数
     */
    public function payResult($params=array()){
        
        //回调函数
        if($params['from']=='notify'){
            if($params['result']=='success'){
                $map=array('ordersn'=>$params['tid']);
                $result=M('ActiveOrder')->where($map)->save(array('status'=>1));
                if($result){
                    $applyId=M('ActiveOrder')->where($map)->getField('apply_id');
                    M('Registration')->where(array('id'=>$applyId))->save(array('status'=>3));
                }
            }
            exit();
        }
        
        //返回函数
        if($params['from']=='return'){
            if($params['type']=='alipay'){
                $this->success("支付成功！请在微信端刷新页面");
                exit();
            }
            if($params['type']=='wechat'){
                $map=array('ordersn'=>$params['tid']);
                $applyId=M('ActiveOrder')->where($map)->getField('apply_id');
                $activeId=M('Registration')->where(array('id'=>$applyId))->getField('active_id');
                $this->success("支付成功！",U('Activity/detail',array('activeid'=>$activeId)));
                exit();
            }
        }
    }
    
    /**
     * 生成二维码
     * @param unknown $keytext 二维码内容
     * @return string 返回二维码链接
     */
    public function getBuildQrCode($keytext){
        Vendor("phpqrcode.phpqrcode");
        $rootUrl='./Uploads/qrImg/';
        mkdirs($rootUrl);
        $salt=random(12,false);
        $key= md5($keytext.$salt);
        $len = strlen($keytext);
        if(!empty($keytext)){
            if (!is_dir($rootUrl)){
                mkdir($rootUrl);
            }
            if ($len <= 360){
                $t=md5($keytext);
                \QRcode::png($keytext, $rootUrl."/".$key.'.png');
                $qrText = urlencode($keytext);
            }
            return $rootUrl.$key.".png"; 
        }else{
            //$this->error("二维码参数不能为空");
            return null;
        }
    }
    
    /**
     * 根据用户ID获取用户信息
     * @param number $user_id
     */
    private function getMembersInfo($user_id=0){
        $memberInfo=M('Member')->field('id,nickname,avatar')->where(array('id'=>$user_id))->find();
        return $memberInfo;
    }
    
    /**
     * 根据打卡ID获取评论
     * @param number $activeId
     * @param number $indexPage
     * @return unknown
     */
    private function disposeComment($activeId=0,$indexPage=0){
        $commentList=M("PunchComment")->field('id,membid,content,createtime')->where(array('status'=>1,'commenttype'=>2,'activeid'=>$activeId))->order(array('createtime'=>'desc'))->limit($indexPage.','.$this->psize)->select();
        foreach ($commentList as $key=>$value){
            $membid=intval($value['membid']);
            $membInfo=$this->getMembersInfo($membid);
            $commentList[$key]['nickname']=$membInfo['nickname'];
            $commentList[$key]['avatar']=$membInfo['avatar'];
            unset($membid);
            unset($membInfo);
        }
        return $commentList;
    }
    
    /**
     * 处理活动数据
     */
    private function disposeIndex($where=array(),$indexPage=0){
        //活动列表
        $activityList=M('Activity')->field('id,title,conver_pic,if_fee,active_fee,start_time')->where($where)->order(array('add_time'=>'desc'))->limit($indexPage.','.$this->psize)->select();
        return $activityList;
    }
}
?>
