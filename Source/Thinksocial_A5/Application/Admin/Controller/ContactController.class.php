<?php
namespace Admin\Controller;
/**
 * 社交后台
 * @author Administrator zhaoxuan
 *
 */
class ContactController extends AdminController{
    
    /**
     * 删除活动类型
     */
    public function cate_del(){
        $id = I('id',0,'intval');
        if ($id){
            $result = M('ActiveCategory')->delete($id);
            if ($result){
                $this->success("类型删除成功！",U('Contact/cateList'));
            }else{
                $this->error("类型删除失败！",U('Contact/cateList'));
            }
        }else{
            $this->error("请选择需要删除的类型！",U('Contact/cateList'));
        }
    }
    /**
     * 添加活动分类
     */
    public function addCategory(){
        $parentId = I('parentId',0,'intval');
        $id = I('id',0,'intval');
        if ($id){
            $cate = M('ActiveCategory')->find($id);
            if (is_array($cate)){
                $parentId = $cate['parentid'];
                $this->assign('cate',$cate);
            }
        }
        if ($_POST){
            $cate = I('cate');
            if ($cate['id']){
                $result = M('ActiveCategory')->where(array('id'=>$cate['id']))->setField($cate);
                $msg = '修改类型';
            }else{
                $result = M('ActiveCategory')->add($cate);
                $msg = '添加类型';
            }
            if ($result){
                $this->success($msg.'成功！',U('Contact/cateList'));
            }else{
                $this->success($msg.'成功！',U('Contact/addCategory'));
            }
        }
        $first_menu = M('ActiveCategory')->field('id,name')->where(array('parentid'=>0,'enabled'=>1))->select();
        $this->assign('parentId',$parentId);
        $this->assign('first_menu',$first_menu);
        $this->display();
    }
    
    /**
     * 活动列表
     */
    public function cateList(){
        $where = array(
            'parentid' =>  I('childId',0,'intval')
        );
        $name = I('name','','strval');
        if ($name){
            $where['name'] = array('like','%'.$name.'%');
        }
        $cateList = $this->lists('ActiveCategory',$where,'');
        $this->assign("cateList",$cateList);
        $this->assign('parentId',$where['parentid']);
        $this->display();
    }
    
    /**
     * 活动管理
     */
    public function activeList(){
        $where=array();
        $title=I('title');
        if($title){
            $where['title']=array('like','%'.$title.'%');
        }
        $activelist=$this->lists('activity',$where,'add_time desc','id,title,initiator,province,city,district,detailaddress,add_Time,active_begin_time');
        $this->assign('title',$title);
        $this->assign('list',$activelist);
        $this->display();
    }
    
    /**
     * 发起活动
     */
    public function addActive(){
        if(IS_POST){
            $hinge = I('hinge','','strval');
            $rule_result = M("Rule")->where(array('name'=>trim($hinge)))->find();
            if (is_array($rule_result)){
                $this->error("关键字已存在！请重新输入...");
            }
            $dis=I('dis');
            $baidumap=I('baidumap');
            $activeData=array(
                'hinge' =>$hinge,
                'title'=>I('title'),
                'first_cate_id'=>I('first_cate_id'),
                'second_cate_id'=>I('second_cate_id'),
                'province' => $dis['province'],
                'city' =>$dis['city'],
                'district' => $dis['district'],
                'lng' => $baidumap['lng'],
                'lat' => $baidumap['lat'],
                'detailaddress'=>I('address'),
                'initiator'=>I('initiator'),
                'content'=>htmlspecialchars_decode(I('content')),
                'uniacid'=>0,
                'conver_pic'=>I('conver_pic'),
                'movement_pic'=>I('movement_pic'),
                'active_begin_time'=>strtotime(I('starttime')),
                'active_end_time'=>strtotime(I('endtime')),
                'start_time'=>strtotime(I('applyStarttime')),
                'end_time'=>strtotime(I('applyEndtime')),
                'add_time'=>strtotime(date('Y-m-d H:i:s')),
                'if_fee'=>I('if_fee'),
                'scancodeiden'=>I('scancodeiden'),
                'wechatmaxnum'=>I('wechatmaxnum'),
                'if_auditing'=>I('if_auditing'),
                'max_acount'=>I('max_acount'),
                'if_persion'=>I('if_persion'),
                'active_fee'=>I('active_fee'),
                'if_show_pic'=>I('if_show_pic'),
                'child_fee'=>I('child_fee'),
                'if_pic'=>I('if_pic'),
            );
            
            
            $insertId=M('activity')->add($activeData);
            if($insertId){
                //添加关键字
                if ($hinge){
                    //添加到规则表
                    $rule['uniacid']=0;
                    $rule['name']= $hinge;
                    $rule['module']='news';
                    $ruleid = M("rule")->add($rule);
                    if($ruleid){
                        $http = 'http://'.$_SERVER['SERVER_NAME'];
                        $imgReply['rid']= $ruleid;
                        $imgReply['title']= $hinge;
                        $imgReply['thumb'] = $http.I('conver_pic');
                        $imgReply['url'] = $http.'/index.php?s=/Activity/detail/activeid/'.$insertId;
                        $imgReply['content']= $activeData['content'];
                        M("news_reply")->add($imgReply);
                    }
                }
                
                $applyNameInfo=I('applyNameInfo');//属性名称集合
                $applySelectInfo=I('applySelectInfo');//属性类型集合
                $prepertyCount=I('prepertyCount');//对应属性值数量
                $textArray=I('setPrepertyText');//显示值集合
                $valueArray=I('setPrepertyValue');//实际值集合
                $index=0;
                foreach($applyNameInfo as $key=>$value){
                    $applyInfoData=array(
                        'info_name'=>$value,
                        'info_type'=>$applySelectInfo[$key],
                        'isRequired'=>I($value),
                        'ordernumber'=>1,
                        'addtime'=>strtotime(date('Y-m-d H:i:s')),
                        'active_id'=>$insertId,
                    );
                    $applyId=M('ApplyInfo')->add($applyInfoData);
                    $countValue=$prepertyCount[$key]; //对应属性数量
                    for($i=0;$i<$countValue;$i++){
                        $valueData=array(
                            'value'=>$valueArray[$index][$value],
                            'text'=>$textArray[$index][$value],
                            'addTime'=>strtotime(date('Y-m-d H:i:s')),
                            'apply_info_id'=>$applyId
                        );
                        M('InfoProperty')->add($valueData);
                        $index++;
                    }
                }
                $this->success("添加成功！",U('Contact/activeList'),1); 
            }else{
                $this->error("添加失败！");
            }
        } 
        $active=array(
            'scancodeiden'=>rand(1000,9999)
        );
        $category=M('ActiveCategory')->field('id,name,parentid')->where(array('enabled'=>1))->select();
        if (!empty($category)) {
            $children = array();
            foreach ($category as $cid => $cate) {
                if (!empty($cate['parentid'])) {
                    $children[$cate['parentid']][$cate['id']] = array($cate['id'], $cate['name']);
                    unset($category[$key]);
                }
            }
        }
        $this->assign('category',$category);
        $this->assign('children',$children);
        $this->assign("active",$active);
        $this->display();
    }

    /**
     * 编辑活动
     */
    public function editActive(){
        if(IS_POST){
            $hinge = I('hinge','','strval');
            $rule_result = M("Rule")->where(array('name'=>trim($hinge)))->find();
            if (is_array($rule_result)){
                $this->error("关键字已存在！请重新输入...");
            }
            
            $dis=I('dis');
            $baidumap=I('baidumap');
            $activeData=array(
                'id'=>I('id'),
                'hinge' =>$hinge,
                'title'=>I('title'),
                'first_cate_id'=>I('first_cate_id'),
                'second_cate_id'=>I('second_cate_id'),
                'province' => $dis['province'],
                'city' =>$dis['city'],
                'district' => $dis['district'],
                'lng' => $baidumap['lng'],
                'lat' => $baidumap['lat'],
                'detailaddress'=>I('address'),
                'initiator'=>I('initiator'),
                'content'=>htmlspecialchars_decode(I('content')),
                'uniacid'=>0,
                'conver_pic'=>I('conver_pic'),
                'movement_pic'=>I('movement_pic'),
                'active_begin_time'=>strtotime(I('starttime')),
                'active_end_time'=>strtotime(I('endtime')),
                'start_time'=>strtotime(I('applyStarttime')),
                'end_time'=>strtotime(I('applyEndtime')),
                'add_time'=>strtotime(date('Y-m-d H:i:s')),
                'if_fee'=>I('if_fee'),
                'scancodeiden'=>I('scancodeiden'),
                'wechatmaxnum'=>I('wechatmaxnum'),
                'if_auditing'=>I('if_auditing'),
                'max_acount'=>I('max_acount'),
                'if_persion'=>I('if_persion'),
                'active_fee'=>I('active_fee'),
                'if_show_pic'=>I('if_show_pic'),
                'child_fee'=>I('child_fee'),
                'if_pic'=>I('if_pic'),
            );
            $result=M('activity')->save($activeData);
            if($result){
                //添加关键字
                if ($hinge){
                    //添加到规则表
                    $rule['uniacid']=0;
                    $rule['name']= $hinge;
                    $rule['module']='news';
                    $ruleid = M("rule")->add($rule);
                    if($ruleid){
                        $http = 'http://'.$_SERVER['SERVER_NAME'];
                        $imgReply['rid']= $ruleid;
                        $imgReply['title']= $hinge;
                        $imgReply['thumb'] = $http.I('conver_pic');
                        $imgReply['url'] = $http.'/index.php?s=/Activity/detail/activeid/'.$activeData['id'];
                        $imgReply['content']= $activeData['content'];
                        M("news_reply")->add($imgReply);
                    }
                }
               $this->success('编辑成功',U('Contact/activeList'));
            }else{
                $this->error("编辑失败！");
            }
        }
        $id=intval(I('id'));
        if(empty($id)){
            $this->error('请选择需要更新的活动');
        }
        $active=M('Activity')->where(array('id'=>$id))->find();
        $reside['province']=$active['province'];
        $reside['city']=$active['city'];
        $reside['district']=$active['district'];
        $item['lng']=$active['lng'];
        $item['lat']=$active['lat'];
        $category=M('ActiveCategory')->field('id,name,parentid')->where(array('enabled'=>1))->select();
        if (!empty($category)) {
            $children = array();
            foreach ($category as $cid => $cate) {
                if (!empty($cate['parentid'])) {
                    $children[$cate['parentid']][$cate['id']] = array($cate['id'], $cate['name']);
                    unset($category[$key]);
                }
            }
        }
        $this->assign('category',$category);
        $this->assign('children',$children);
        $this->assign('active',$active);
        $this->assign('reside',$reside);
        $this->assign('item',$item);
        $this->display();
    }

    /**
     * 报名信息自增项
     */
    public function regItem(){
        $tag = rand(5,100);
        $this->assign("tag",$tag);
        include $this->display('info_item');
    }
    
    /**
     * 删除活动
     */
    public function delactive(){
        $id=intval(I('id'));
        if(empty($id)){
            $this->error("未找到活动信息");
        }else{
            $result=M('activity')->delete($id);
            if($result){
                $this->success("删除成功！");
            }else{
                $this->error("删除失败！");
            }
        }
        
    }
    
    /**
     * 统计报名信息
     */
    public function statistics(){
        $id = intval(I('id'));
        if(empty($id)){
            $this->error('未找到活动信息');
        }
        $activeInfo=M('activity')->field("title, max_acount, active_fee")->where(array('id'=>$id))->find();
        $map['active_id']=$id;
        //成功win
        $map['status']=3;
        $winTotalInfo=$this->getStatusInfo($map);
        //代付款Payment
        $map['status']=1;
        $paymentInfo=$this->getStatusInfo($map);
        //待审核audit
        $map['status']=0;
        $auditInfo=$this->getStatusInfo($map);
        //已签到Registration
        $map['status']=4;
        $registrationInfo=$this->getStatusInfo($map);
        //驳回reject
        $map['status']=2;
        $rejectInfo=$this->getStatusInfo($map);
        //已报名
        unset($map['status']);
        $applyInfo=$this->getStatusInfo($map);
        $this->assign('activeInfo',$activeInfo);
        $this->assign('winTotalInfo',$winTotalInfo);
        $this->assign('paymentInfo',$paymentInfo);
        $this->assign('auditInfo',$auditInfo);
        $this->assign('registrationInfo',$registrationInfo);
        $this->assign('rejectInfo',$rejectInfo);
        $this->assign('applyInfo',$applyInfo);
        $this->display();
    }
    
    /**
     * 活动报名
     */
    public function applyList(){
        $where=array();
        $title=I('title');
        if($title){
            $where['title']=array('like','%'.$title.'%');
        }
        
        $Model = D("ApplyView");
        $applylist=$this->lists($Model,$where,array('add_time'=>'desc'));
        $this->assign('title',$title);
        $this->assign('list',$applylist);
        $this->display();
    }

    /**
     * 显示详情
     */
    public function applyInfo(){
        $id = intval(I('id'));
        if(empty($id)){
            $this->error("请选择对应的记录");
        }
        $map=array(
            'id'=>$id
        );
        $apply=D("ApplyView")->where($map)->find();
        $applyInfo=$this->lists("property",array('signup_id'=>$id));
        $this->assign("apply",$apply);
        $this->assign("applyInfo",$applyInfo);
        $this->display("Contact/adjust");
        exit();        
    }

    /**
     * 审核报名信息
     */
    public function adjust(){
    	$id = intval(I('id'));
    	$resultId = intval(I('resultId'));
    	$model=M('Registration');
    	$info=$model->field("status,if_fee")->join('sx_activity ON sx_activity.id = sx_registration.active_id')->find();
    	$updateData=array();
    	//驳回
    	if($resultId==0){
    		$updateData['status']=2;
    	}else{
    		//通过(待审核)
    		if($info['status']==0){
    			if($info['if_fee']==1){//需付费的活动设置状态为代付款
    				$updateData['status']=1;
    			}else{//不需付费的活动设置状态为成功
    				$updateData['status']=3;
    			}
    		//成功(代付款——用于处理用户操作失误时手动更改状态)
    		}else if($info['status']==1){
    			$updateData['status']=3;
    		}
    	}
    	$result=$model->where('id='.$id)->setField($updateData);
    	$rsg=array();
    	if($result){
    		$rsg['code']='200';
    		$rsg['msg']='审核成功！';
    	}else{
    	    $rsg['code']='201';
    	    $rsg['msg']='审核失败！';
    	}
    	echo json_encode($rsg);
    	exit();
    }
    
    /**
     * 删除报名信息
     */
    public function delapply(){
        $id=I('id');
        if(empty($id)){
            $this->error("请选择需要删除的记录");
        }
        //删除报名订单
        $order_result=M('active_order')->where(array('apply_id'=>$id))->delete();
        //删除报名信息
        $info_result=M('property')->where(array('signup_id'=>$id))->delete();
        //删除报名记录
        $result=array();
        if($order_result>=0&&$info_result>=0){
            $result_data=M('registration')->delete($id);
            if($result_data){
                $result['code']=200;
                $result['msg']="删除成功！";
            }else{
               $result['code']=201;
               $result['msg']="删除失败！";
            }
        }else{
            $result['code']=201;
            $result['msg']="子表删除失败！";
        }
        $result['web']=$order_result;
        $result['meb']=$info_result;
        echo json_encode($result);
        exit();
    }

    /**
     * 导出报名数据
     */
    public function exportApply(){
        $title=I('title');
        if(empty($title)){
            $this->error("请根据活动名称搜索之后方可导出");
        }else{
            $map['title']= array('like','%'.$title.'%');
        }
        $active_id = M('Activity')->where($map)->getField('id');
        //表头数组
        $headList=M('ApplyInfo')->field('info_name')->where(array('active_id'=>$active_id))->select();
        $headArr[0]='姓名';
        $headArr[1]='手机号';
        foreach ($headList as $key=>$value){
            $headArr[$key+2]=$value['info_name'];
        }
        //报名记录
        $baseList=D('ApplyView')->field('id,realname,mobile')->where($map)->select();
        //记录所对应的报名信息
        foreach ($baseList as $key=>$value){
          /*  $newArray[0] =$value['realname'];
           $newArray[1] =$value['mobile']; */
           $propertyList=  M('property')->field('user_value')->where('signup_id='.intval($value[id]))->select();
           foreach ($propertyList as $k=>$v){
               $newArray[$k]=$v['user_value'];
           }
           $baseList[$key]=$newArray;
        }
        $this->exportData('报名信息', $headArr, $baseList);
    }
    
    /**
     * 获取不同状态的报名信息
     */
    private function getStatusInfo($where=array()){
        $result=M('registration')->field("SUM(total_acount) as totalNum,SUM(signup_acount) as signupNum,SUM(children_acount) as childNum")->where($where)->find();
        return $result;
    }
}
?>