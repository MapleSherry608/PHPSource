<?php
namespace Home\Controller;
use Think\Upload;
use Think\Image;

class PunchcardController extends HomeController{
    private $psize=5;
    
    /**
     * 添加评论
     */
    public function addComment(){
        $uid=MEMBID;
        $punchId=I('punchid',0,'intval');
        $content=I('content','','strip_tags');
        //评论信息
        $commentData=array(
            'membid'=>$uid,
            'punchid'=>$punchId,
            'content'=>$content,
            'commenttype'=>2,
            'createtime'=>strtotime(date('Y-m-d H:i:s'))
        );
        $result=M("PunchComment")->add($commentData);
        if($result>0){
            $memberInfo=$this->getMembersInfo($uid);
            $html="<div class='actReviewBox'>".
                "<div class='signReviewBox'>".
                "<div class='signHeadBox'><a><img src='".$memberInfo['avatar']."'></a></div>".
                "<div class='signReviewContent'>".
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
        $punchId=I('punchid',0,'intval');
        $commentList=$this->disposeComment($punchId,$nextNumber);
        $nextNumber+=$this->psize;
        $this->assign('nextNumber',$nextNumber);
        $this->assign('commentList',$commentList);
        $this->display('Punch/comment_item');
    }
    
    /**
     * 打卡评论
     */
    public function commentOption(){
        $punchId=I('punchid',0,'intval');
        if(!$punchId){
            $this->error('该条打卡信息不存在！');
            exit();
        }
        $punchInfo=M('PunchClock')->where(array('id'=>$punchId,'status'=>1))->find();//当前打卡记录
        if(empty($punchInfo)){
            $this->error('该条打卡信息不存在！或失效');
            exit();
        }
        $punchMembInfo=$this->getMembersInfo(intval($punchInfo['membid']));
        $praiseCount=$this->optionCount($punchId,1);//点赞数量
        $commentCount=$this->optionCount($punchId,2);//评论数量
        $punchInfo['images']=unserialize($punchInfo['imglist']);//图片集合
        //评论集合
        $commentList=$this->disposeComment($punchId);
        //点赞人集合
        $praiseList=$this->getPraiseInfo($punchId);
        $this->assign('punchInfo',$punchInfo);
        $this->assign('punchMembInfo',$punchMembInfo);
        $this->assign('praiseCount',$praiseCount);
        $this->assign('commentCount',$commentCount);
        $this->assign('commentList',$commentList);
        $this->assign('praiseList',$praiseList);
        $this->assign('nextNumber',$this->psize);
        $this->assign('punchId',$punchId);
        $this->display('Punch/signComment');
    }
    
    
    /**
     * ajax湖区哦用户打卡列表记录
     */
    public function ajaxSign(){
        $nextNumber=I('nextNumber',0,'intval');
        $membid=I('membid',0,'intval');
        $punchList=$this->disposePunchList($membid,$nextNumber);
        $nextNumber+=$this->psize;
        $this->assign('nextNumber',$nextNumber);
        $this->assign('punchList',$punchList);
        $this->display('Punch/sign_item');
    }
    
    /**
     * 用户打卡列表记录
     */
    public function signList(){
        C('TOKEN_ON',false);
        $membid=I('membid',0,'intval');
        $punchList=$this->disposePunchList($membid);
        $this->assign('nextNumber',$this->psize);
        $this->assign('membid',$membid);
        $this->assign('punchList',$punchList);
        $this->display('Punch/signRecord');
    }
    
    /**
     * 好友信息
     */
    public function friendIndex(){
        $membid=I('membid',0,'intval');
        $memberModel=M('Member');
        $memberInfo=$memberModel->field('sx_member.id,nickname,gender,avatar,bio,levelid,title')->join('left join sx_member_level ON sx_member.levelid = sx_member_level.id')->where(array('sx_member.id'=>$membid))->find();
        $this->assign('memberInfo',$memberInfo);
        $this->display('Punch/friendInfo');
    }

    /**
     * ajax获取点赞人信息集合
     */
    public function ajaxPraise(){
        $nextNumber=I('nextNumber',0,'intval');
        $punchId=I('punchid',0,'intval');//获取打卡记录信息$punchId=0,$type=1,$uniacid=0
        $praiseList = $this->disposePraiseList($punchId,$nextNumber);
        $nextNumber+=$this->psize;
        $this->assign('nextNumber',$nextNumber);
        $this->assign('praiseList',$praiseList);
        $this->display('Punch/zan_item');
    }
    
    /**
     * 获取点赞人信息集合
     */
    public function praiseList(){
        $punchId=I('punchid',0,'intval');//获取打卡记录信息$punchId=0,$type=1,$uniacid=0
        $praiseList = $this->disposePraiseList($punchId);
        $this->assign('nextNumber',$this->psize);
        $this->assign('punchId',$punchId);
        $this->assign('praiseList',$praiseList);
        $this->display('Punch/zanList');
    }
   
    /**
     * 点赞功能
     */
    public function praiseOption(){
        $uid=MEMBID;
        $punchId=I('punchId');//获取打卡记录信息
        $praiseCount=I('praiseCount');
        //返回消息
        $resultInfo=array(
            'code'=>202,
            'mes'=>'',
            'praiseCount'=>$praiseCount,
            'praisePeople'=>''
        );
        if(!$uid){
            $resultInfo['mes']='当前状态无法点赞！';
        }else{
            $currentUserInfo=$this->getMembersInfo($uid);
            $html="<div class='myPraiseContent'>".
                "<div class='myPraisePic'><img src='".$currentUserInfo['avatar']."'></div>".
                "<p>".$currentUserInfo['nickname']."</p>".
                "</div>";
             $resultInfo['praisePeople']=$html;
            //唯一性判断数据（根据公众ID、用户ID、对应的打卡记录）
            $soleData=array(
                'membid'=>$uid,
                'status'=>1,
                'punchid'=>$punchId,
                'commenttype'=>1
            );
            $punchComment=M('PunchComment');
            $soleInfo=$punchComment->field('id')->where($soleData)->find();
            //唯一性判断->用户未点赞时进行点赞、否则不能点赞
            if(empty($soleInfo)){
                //创建点赞信息（添加之用）
                $data=array(
                    'membid'=>$uid,
                    'punchid'=>$punchId,
                    'content'=>'',
                    'status'=>1,
                    'commenttype'=>1,
                    'createtime'=>strtotime(date('Y-m-d H:i:s'))
                );
                //添加点赞信息
                $resultId = $punchComment->add($data);
                if($resultId>0){
                    $praiseCount++;
                    $resultInfo['code']=200;
                    $resultInfo['praiseCount']=$praiseCount;
                }else{
                    $resultInfo['mes']='点赞失败！';
                }
            }else{
                $resultInfo['mes']='你已为该活动点赞！';
            }
        }
        echo json_encode($resultInfo);
    }

    /**
     * 异步分页
     */
    public function ajaxIndex(){
        $nextNumber=I('nextNumber',0,'intval');
        //打卡记录
        $punchClock=M('PunchClock')
                    ->field('id,title,status,content,imglist,membid,punch_date')
                    ->where(array('status'=>1))
                    ->order(array('punch_date'=>'desc'))
                    ->limit($nextNumber.','.$this->psize)
                    ->select();
        $punchClock=$this->disposePunch($punchClock);
        $nextNumber=$nextNumber+$this->psize;
        $this->assign('nextNumber',$nextNumber);
        $this->assign('punchInfoList',$punchClock);
        $this->display('Punch/index_item');
    }
    
    /**
     * 打卡首页
     */
    public function index(){
        //打卡记录
        $punchClock=M('PunchClock')
                    ->field('id,title,status,content,imglist,membid,punch_date')
                    ->where(array('status'=>1))
                    ->order(array('punch_date'=>'desc'))
                    ->limit('0,'.$this->psize)
                    ->select();
        $punchClock=$this->disposePunch($punchClock);
        $this->assign('nextNumber',$this->psize);
        $this->assign('punchInfoList',$punchClock);
        $this->display('Punch/index');
    }
    
    /**
     * 发新帖
     */
    public function posted(){
        if($_POST){
            $punchClock=M("PunchClock");
            //表单令牌验证防止重复提交表单
            if ($punchClock->autoCheckToken($_POST)){
                $imageList=$_POST['imageslist'];
                $data=array(
                    'uniacid'=>20,
                    'title'=>$_POST['title'],
                    'status'=>1,
                    'content'=>$_POST['content'],
                    'imglist'=>serialize($imageList),
                    'membid'=>MEMBID,
                    'punch_date'=>strtotime(date('Y-m-d H:i:s')),
                );
                $result=$punchClock->add($data);
                if($result){
                    foreach ($imageList as $key=>$value){
                        $image = new Image(\Think\Image::IMAGE_GD);
                        $image->open($value);// 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg
                        $thumb=str_replace("Picture","Thumb",$value);
                        $index=strrpos($thumb,'/')+1;//寻找位置
                        $thumbUrl=substr($thumb,0,$index);
                        mkdirs($thumbUrl); //创建文件夹
                        $image->thumb(200, 200,\Think\Image::IMAGE_THUMB_FIXED)->save($thumb);
                        unset($image);
                    }
                    $this->success('保存成功',U('Punchcard/index'));
                }else{
                    $this->error('保存失败');
                }
            }else{
                $this->success('保存成功',U('Punchcard/index'));
            }
            exit();
        }
        $this->assign('uid',MEMBID);
        $this->display('Punch/punch');
    }
    
    /**
     * 图片上传
     */
    public function uploadify(){
        if($_FILES){
            $setting =  array(
                'mimes'    => '', //允许上传的文件MiMe类型
                'maxSize'  => 0, //上传的文件大小限制 (0-不做限制)
                'exts'     => 'gif,jpg,jpeg,bmp,png,swf', //允许上传的文件后缀
                'autoSub'  => true, //自动子目录保存文件
                'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
                'rootPath' => './Uploads/Picture/', //保存根路径
                'savePath' => '', //保存路径
                'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
                'saveExt'  => 'jpg', //文件保存后缀，空则使用原后缀
                'replace'  => false, //存在同名是否覆盖
                'hash'     => true, //是否生成hash编码
                'callback' => true, //检测文件是否存在回调函数，如果存在返回文件信息数组
            );
            /* 调用文件上传组件上传文件 */
            //实例化上传类，传入上面的配置数组
            $this->uploader = new Upload($setting, 'Local');
            $info = $this->uploader->upload($_FILES);
            if($info){
                $imgUrl=$setting['rootPath']. $info ['file'] ['savepath'] . $info ['file'] ['savename'];
                $info['imgUrl']=$imgUrl;
            }else{
                $info['code']=200;
            }
            echo json_encode($info);
            exit();
        }
    }

    /*************************************************************************************************************/
    /*================================================私有方法====================================================*/
    /*************************************************************************************************************/
    /**
     * 根据打卡ID获取评论
     * @param number $punchId
     * @param number $indexPage
     * @return unknown
     */
    private function disposeComment($punchId=0,$indexPage=0){
        $commentList=M("PunchComment")->field('id,membid,content,createtime')->where(array('status'=>1,'commenttype'=>2,'punchid'=>$punchId))->order(array('createtime'=>'desc'))->limit($indexPage.','.$this->psize)->select();
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
     * 根据membid获取打卡信息
     * @param number $membid
     * @return unknown
     */
    private function disposePunchList($membid=0,$indexPage=0){
        $punchList=M('PunchClock')->field('id,title,content,imglist,punch_date,membid')->where(array('membid'=>$membid,'status'=>1))->order(array('punch_date'=>'desc'))->limit($indexPage.','.$this->psize)->select();
        foreach($punchList as $key=>$value){
            $punchList[$key]['daily']=date('d',$value['punch_date']);
            $punchList[$key]['montch']=date('m',$value['punch_date']);
            $punchList[$key]['images']=unserialize($value['imglist']);
        }
        return $punchList;
    }
    
    /**
     * 根据打卡ID获取点赞人集合信息
     * @param number $punchId
     */
    private function disposePraiseList($punchId=0,$indexPage=0){
        $map=array(
            'punchid'=>$punchId,
            'commenttype'=>1,
            'status'=>1,
        );
        $punchComment=M('PunchComment');
        $praiseList = $punchComment->field('id,membid,createtime')->where($map)->order(array('createtime'=>'desc'))->limit($indexPage.','.$this->psize)->select();
        foreach ($praiseList as $key=>$value){
            $praiseList[$key]['members']=$this->getMembersInfo($value['membid']);
        }
        return $praiseList;
    }
    
    /**
     * 处理打卡集合
     * @param unknown $punchList 打卡集合
     */
    private function disposePunch($punchList=array()){
        foreach ($punchList as $key=>$value){
            $punchId=intval($value['id']);
            $punchList[$key]['praiseCount']=$this->optionCount($punchId,1);//点赞数量
            $punchList[$key]['commentCount']=$this->optionCount($punchId,2);//评论数量
            $punchList[$key]['images']=unserialize($value['imglist']);//打卡图片集合
            $punchList[$key]['userInfo']=$this->getMembersInfo($value['membid']);//根据ID得到用户信息
            $punchList[$key]['praiseInfo']=$this->getPraiseInfo($punchId);
            unset($punchList[$key]['imglist']);
        }
        return $punchList;
    }
    
    /**
     * 获取点赞人信息
     * @param number $punchId
     */
    private function getPraiseInfo($punchId=0){
        $praiseInfo=array();
        $map=array(
            'punchid'=>$punchId,
            'commenttype'=>1,
            'status'=>1
        );
        //获取该活动的点赞人数
        $praiseModel=M('PunchComment')->field('id,membid')->where($map)->limit('0,5')->order(array('createtime'=>'desc'))->select();
        foreach ($praiseModel as $key=>$value){
            $userId=intval($value['membid']);
            $praiseInfo[$key]=$this->getMembersInfo($userId);
        }
        return $praiseInfo;
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
     * 计算数量
     * @param number $punchId 打卡ID
     * @param number $type 2、评论数量   1、点赞数量
     */
    private function optionCount($punchId=0,$type=1){
        $map=array(
            'punchid'=>$punchId,
            'commenttype'=>$type,
            'status'=>1
        );
        $punchComent = M('PunchComment')->where($map)->count();
        return $punchComent;
    }
}
