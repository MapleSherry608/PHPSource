<?php

namespace Admin\Controller;

/**
 * 有赏众帮
 *  
 */
class SendMessageController  extends AdminController  {
    /**
     * 获取所有粉丝
     */
    public function fansAll($APPID,$APPSECRET){
        $TOKEN_URL="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$APPID."&secret=".$APPSECRET;
        $json=file_get_contents($TOKEN_URL);
        $result=json_decode($json);
        $accessToken=$result->access_token;
        //前10000条数据
        $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token=' .$accessToken;
        $user_obj=D("member_public")->request_get($url);
        $user_obj =json_decode($user_obj, true);
        $total=$user_obj['total'];
        $count=$user_obj['count'];
        $openidArray=$user_obj['data']['openid'];
        print_R("去重前;".count($openidArray));
        $nextOpenid=$user_obj['next_openid'];
        //判断数据库是否存在，存在则删除本数组中数据
        for($i=0;$i<$total;$i++){
            $ifExit=M("member_fans")->where(array('openid'=>$openidArray[$i]))->find();
            if(!empty($ifExit)){
                print_R("openid:".$openidArray[$i]."<br/>");
               unset($openidArray[$i]);
            }
        }
        sort($openidArray);
        print_R("去重前;".count($openidArray));
        $count=count($openidArray);
        $total=count($openidArray);
        //数据插到数据库
        if($total<10000){
            //先插入前2000条
            if($count>2000){
                //大于2000条，先插前2000
                for($i=0;$i<2000;$i++){
                    $fans['membid']=0;
                    $fans['openid']=$openidArray[$i];
                    $fans['nickname']='';
                    $fans['groupid']=0;
                    $fans['follow']=1;
                    $fans['followtime']=time();
                    $result=M("member_fans")->add($fans);
                }
                if($count>4000){
                    //大于4000条，再插入2000  4000条已插入
                    for($i=2000;$i<4000;$i++){
                        $fans['membid']=0;
                        $fans['openid']=$openidArray[$i];
                        $fans['nickname']='';
                        $fans['groupid']=0;
                        $fans['follow']=1;
                        $fans['followtime']=time();
                        $result=M("member_fans")->add($fans);
                    }
                    //大于6000条
                    if($count>6000){
                        //大于6000 插入前6000条 6000条已经插入
                        for($i=4000;$i<6000;$i++){
                            $fans['membid']=0;
                            $fans['openid']=$openidArray[$i];
                            $fans['nickname']='';
                            $fans['groupid']=0;
                            $fans['follow']=1;
                            $fans['followtime']=time();
                            $result=M("member_fans")->add($fans);
                         }
                        //大于8000条  先插入6000-8000
                        if($count>8000){
                            for($i=6000;$i<8000;$i++){
                                $fans['membid']=0;
                                $fans['openid']=$openidArray[$i];
                                $fans['nickname']='';
                                $fans['groupid']=0;
                                $fans['follow']=1;
                                $fans['followtime']=time();
                                $result=M("member_fans")->add($fans);
                            }
                            //再插入8000之后的
                            for($i=8000;$i<10000;$i++){
                                $fans['membid']=0;
                                $fans['openid']=$openidArray[$i];
                                $fans['nickname']='';
                                $fans['groupid']=0;
                                $fans['follow']=1;
                                $fans['followtime']=time();
                                $result=M("member_fans")->add($fans);
                            }
                        }else{
                            //插入剩余的
                            for($i=6000;$i<$count;$i++){
                                $fans['membid']=0;
                                $fans['openid']=$openidArray[$i];
                                $fans['nickname']='';
                                $fans['groupid']=0;
                                $fans['follow']=1;
                                $fans['followtime']=time();
                                $result=M("member_fans")->add($fans);
                            }
                        }
                        
                    }else{
                        //小于6000条 插入剩余的
                        for($i=4000;$i<$count;$i++){
                            $fans['membid']=0;
                            $fans['openid']=$openidArray[$i];
                            $fans['nickname']='';
                            $fans['groupid']=0;
                            $fans['follow']=1;
                            $fans['followtime']=time();
                            $result=M("member_fans")->add($fans);
                        }
                    }
                }else{
                    //小于4000条 插入剩余的
                    for($i=2000;$i<$count;$i++){
                        $fans['membid']=0;
                        $fans['openid']=$openidArray[$i];
                        $fans['nickname']='';
                        $fans['groupid']=0;
                        $fans['follow']=1;
                        $fans['followtime']=time();
                        $result=M("member_fans")->add($fans);
                    }
                }
                
            //2000条之内，直接插入    
            }else{
                for($i=0;$i<$count;$i++){
                    
                    $fans['membid']=0;
                    $fans['openid']=$openidArray[$i];
                    $fans['nickname']=$i;
                    $fans['groupid']=0;
                    $fans['follow']=1;
                    $fans['followtime']=time();
                    $result=M("member_fans")->add($fans);
                }
            }
        }
        //查询是否有空的数据，如果有，则删除数据
        //$delResult=M("member_fans")->where(array('openid'=>''))->delete();
    }

    //测试模版消息效果
    public function testSendMessage(){
        header("Content-type: text/html; charset=utf-8"); 
        //openid
        $touser='oTI5Mt5uaW5FdVKzMhKgv2SgF-io';
        //模版id
        $template_id='5NdEcrK3FqhEzQpPsEQhYK9P1bbp0S0DbdrTkhoTSTQ';
        //跳转路径
        $redirectUrl='http://www.baidu.com';
        //模版内容
        $data=array(
            'first'=>array('value'=>urlencode("您好,您已购买成功！"),'color'=>"#743A3A"),
            'product'=>array('value'=>urlencode("郑鹏栋一头"),'color'=>'#743A3A'),
            'price'=>array('value'=>urlencode("0.01"),'color'=>'#743A3A'),
            'time'=>array('value'=>urlencode("2015年12月24日"),'color'=>'#743A3A'),
            'remark'=>array('value'=>urlencode("平安夜礼物，只要0.01"),'color'=>'#743A3A'),
        );
        //公众号appid
        $appid='wx94ba0074fe5832e4';
        //公众号secrect
        $secrect='2189f68c325f2eb1b1109bc5a9f4fdc8';
        
      /*   //测试发送模版消息--成功
        $sendResult=D("MemberPublic")->doSendMessage($touser, $template_id, $redirectU.
        
        rl, $data, $topcolor = '#7B68EE',$appid,$secrect);
         if($sendResult){
            print_R("发送成功！");
        }else{
            print_R("发送失败！");
        }   */
        
      /*   
       //测试获取用户信息--成功
        $userInfo=D("MemberPublic")->getUserInfo($appid,$secrect,$touser);
        print_R($userInfo); */
        
    }
    //测试
    public function test(){
        $openid='oTI5Mt5uaW5FdVKzMhKgv2SgF-io';
        header("Content-type: text/html; charset=utf-8");
        //公众号appid
        $appid='wx94ba0074fe5832e4';
        //公众号secrect
        $secrect='2189f68c325f2eb1b1109bc5a9f4fdc8';
        
        $touser='oTI5Mt5uaW5FdVKzMhKgv2SgF-io';
        
       /*  //测试自定义菜单 --测试成功
        * 
        */
        $this->fansAll($appid,$secrect);
    }
}
?>