<?php

namespace Admin\Controller;
use Common\Api\WeAccount;
use Common\Api\WeiXinAccount;
/**
 * 后台管理微信
 *  
 */
class ReplyController  extends AdminController  {
	public $replyClass=null;
	protected $membmodel=null;
	protected $fansmodel=null;
	public function	__init__(){
		$this->membmodel=api('Member/Member/getModel');
		$this->fansmodel=M('MemberFans');
	}
	private function uni_modules( $enabledOnly= "basic") {
		$modules = M('modules')->where(array('name'=>$enabledOnly))->find();
		return $modules;
	}
	private function system_modules(){
		$modules = M('Modules')->where(array('issystem'=>1))->getField('id,name');
		return $modules;
	}
	private function module_types() {
		 $types = array(
			'business' => array(
				'name' => 'business',
				'title' => '主要业务',
				'desc' => ''
			),
			'customer' => array(
				'name' => 'customer',
				'title' => '客户关系',
				'desc' => ''
			),
			'activity' => array(
				'name' => 'activity',
				'title' => '营销及活动',
				'desc' => ''
			),
			'services' => array(
				'name' => 'services',
				'title' => '常用服务及工具',
				'desc' => ''
			),
			'biz' => array(
				'name' => 'biz',
				'title' => '行业解决方案',
				'desc' => ''
			),
			'other' => array(
				'name' => 'other',
				'title' => '其他',
				'desc' => ''
			)
		);
		return $types;
	}
	public function allReply($rm=null){
		empty($rm) && $this->error('非法访问');
		$this->assign("rm",$rm);
		$do=$_REQUEST['do'];
		$dos = array('display', 'post', 'delete');
		$do = in_array($do, $dos) ? $do : 'display';
		$module = $this->uni_modules($rm);
		$sysmods=$this->system_modules();
		if(!in_array($rm, $sysmods)) {
			if($module['issolution']) {
				$solution = $module;
				define('FRAME', 'solution');
			} else {
				define('FRAME', 'ext');
				$types = $this->module_types();
				//define('ACTIVE_FRAME_URL', U('')url('home/welcome/ext', array('m' => $rm)));
			}
			//$frames = buildframes(array(FRAME), $rm);
			$frames = $frames[FRAME];
		}
		if($do == 'display') {
			$pindex = max(1, intval($_REQUEST['page']));
			$psize = 20;
			$cids = $parentcates = $list =  array();
			$types = array('', '等价', '包含', '正则表达式匹配', '直接接管');
			$condition=array();
			$condition['module'] = $rm;
			$params = array();
			$status = isset($_REQUEST['status']) ? intval($_REQUEST['status']) : -1;
			if ($status != -1){
				$condition['status'] =$status;
			}
			if(isset($_REQUEST['keyword'])) {
				$condition['name'] = array('LIKE',"'%".$_REQUEST['keyword']."%'");
			}
			$replies = $this->lists("Rule",$condition);
			if (!empty($replies)) {
				foreach($replies as &$item) {
					$condition['rid'] = $item['id'];
					$item['keywords'] = $this->lists("rule_keyword",$condition,"displayorder DESC, `type` ASC, id DESC");
					//$entries = module_entries($rm, array('rule'),$item['id']);
					if(!empty($entries)) {
						$item['options'] = $entries['rule'];
					}
				}
			}
			$this->assign("rm",$rm);
			$this->assign('module',$module);
			$this->assign('replies',$replies);
			$this->display();
		}
		
		if($do == 'post') {
			if ($_W['isajax'] && $_W['ispost']) {
				
				$sql = 'SELECT `rid` FROM ' . tablename('rule_keyword') . " WHERE `uniacid` = :uniacid  AND `content` = :content";
				$result = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'], ':content' => $_REQUEST['keyword']));
				if (!empty($result)) {
					$keywords = array();
					foreach ($result as $reply) {
						$keywords[] = $reply['rid'];
					}
					$rids = implode($keywords, ',');
					$sql = 'SELECT `id`, `name` FROM ' . tablename('rule') . " WHERE `id` IN ($rids)";
					$rules = pdo_fetchall($sql);
					exit(@json_encode($rules));
				}
				exit('success');
			}
			$rid = intval($_REQUEST['rid']);
			$this->assign('rid',$rid);
			if(!empty($rid)) {
				$reply = reply_single($rid);
				if(empty($reply) || $reply['uniacid'] != $_W['uniacid']) {
					message('抱歉，您操作的规则不在存或是已经被删除！', url('platform/reply', array('m' => $rm)), 'error');
				}
				foreach($reply['keywords'] as &$kw) {
					$kw = array_elements(array('type', 'content'), $kw);
				}
			}
			if(IS_POST) {
				if(empty($_REQUEST['name'])) {
					message('必须填写回复规则名称.');
				}
				$keywords = @json_decode(htmlspecialchars_decode($_REQUEST['keywords']), true);
				if(empty($keywords)) {
					message('必须填写有效的触发关键字.');
				}
				$rule = array(
					'uniacid' => $_W['uniacid'],
					'name' => $_REQUEST['name'],
					'module' => $rm,
					'status' => intval($_REQUEST['status']),
					'displayorder' => intval($_REQUEST['displayorder_rule']),
				);
		
				if(!empty($_REQUEST['istop'])) {
					$rule['displayorder'] = 255;
				} else {
					$rule['displayorder'] = range_limit($rule['displayorder'], 0, 254);
				}
				$module = WeUtility::createModule($rm);
				
				if(empty($module)) {
					message('抱歉，模块不存在请重新其它模块！');
				}
				$msg = $module->fieldsFormValidate();
				
				if(is_string($msg) && trim($msg) != '') {
					message($msg);
				}
				if (!empty($rid)) {
					$result = pdo_update('rule', $rule, array('id' => $rid));
				} else {
					$result = pdo_insert('rule', $rule);
					$rid = pdo_insertid();
				}
				if (!empty($rid)) {
								$sql = 'DELETE FROM '. tablename('rule_keyword') . ' WHERE `rid`=:rid AND `uniacid`=:uniacid';
					$pars = array();
					$pars[':rid'] = $rid;
					$pars[':uniacid'] = $_W['uniacid'];
					pdo_query($sql, $pars);
			
					$rowtpl = array(
						'rid' => $rid,
						'uniacid' => $_W['uniacid'],
						'module' => $rule['module'],
						'status' => $rule['status'],
						'displayorder' => $rule['displayorder'],
					);
					foreach($keywords as $kw) {
						$krow = $rowtpl;
						$krow['type'] = range_limit($kw['type'], 1, 4);
						$krow['content'] = $kw['content'];
						pdo_insert('rule_keyword', $krow);
					}
					$rowtpl['incontent'] = $_REQUEST['incontent'];
					$module->fieldsFormSubmit($rid);
					message('回复规则保存成功！', url('platform/reply/post', array('m' => $m, 'rid' => $rid)));
				} else {
					message('回复规则保存失败, 请联系网站管理员！');
				}
			}
			$this->assign('replyClass',$this);
			$this->assign('module',$module);
			$this->assign('replies',$replies);
			$this->assign("reply",$reply);
			$this->display('reply-post');
			exit();
		}
		
		if($do == 'delete') {
			$rid = intval($_REQUEST['rid']);
			if(empty($rid)) {
				message('非法访问.');
			}
			$reply = reply_single($rid);
			if(empty($reply) || $reply['uniacid'] != $_W['uniacid']) {
				message('抱歉，您操作的规则不在存或是已经被删除！', url('platform/reply', array('m' => $m)), 'error');
			}
				if (pdo_delete('rule', array('id' => $rid))) {
				pdo_delete('rule_keyword', array('rid' => $rid));
						pdo_delete('stat_rule', array('rid' => $rid));
				pdo_delete('stat_keyword', array('rid' => $rid));
						$module = WeUtility::createModule($reply['module']);
				if (method_exists($module, 'ruleDeleted')) {
					$module->ruleDeleted($rid);
				}
			}
			message('规则操作成功！', referer());	
		}
		
	}
	public function GetModule($rm='basic',$rid){
		$rmodule=$rm.'Module';
		$this->$rmodule($rid);
	}
	/**
	 * 文字回复
	 */
	public function basicModule($rid=0){
		if(!empty($rid) && $rid > 0) {
			$isexists =M('rule')->field("id")->where(array('id'=>$rid))->find("id");
		}
		if(!empty($isexists)) {
			$replies =M('basic_reply')->where(array('rid'=>$rid))->order('id desc')->select();
		}
		$this->assign('isexists',$isexists);
		$this->assign('replies',$replies);
		$this->display("basicModule");
	}
	/**
	 * 图文回复
	 */
	public function newsModule(){
		$this->display();
	}
	/**
	 * 音乐回复
	 */
	public function musicModule(){
		$this->display();
	}
	/**
	 * 图片回复
	 */
	public function imagesModule(){
		$this->display();
	}
	/**
	 * 语音回复
	 */
	public function voiceModule(){
		$this->display();
	}
	/**
	 * 视频回复
	 */
	public function videoModule(){
		$this->display();
	}
	/**
	 * 更新选中的用户
	 */
	public function updfans(){
		$id = array_unique((array)I('id',0));
        $id = is_array($id) ? implode(',',$id) : $id;
		if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
		$map=array();
        $map['id'] =   array('in',$id);
		$firstrow=$page->firstRow;
		$fanslist=$this->fansmodel->field("id,membid,openid")->where($map)->select();
		if(empty($id)){
			$this->assign('p',$p);
			$this->display();
		}
		$account=M("member_public")->find();
        $appid=$account['appid'];
        $secrect=$account['secret'];
    	foreach ($fanslist as $key => $value) {
			$memdata=$this->getUserInfo($value['openid']);
			$data=array(
				'nickname'=>$memdata['nickname'],
				'gender'=>$memdata['sex'],
				'residecity'=>$memdata['city'],
				'resideprovince'=>$memdata['province'],
				'nationality'=>$memdata['country'],
				'avatar'=>$memdata['headimgurl'],
			);
			if(empty($value['membid'])){
				$value['membid']=$this->membmodel->register($nickname,md5($value['openid']),md5($value['openid']).'@baguatan.com','',$memdata['headimgurl'],strval($value['openid']),$data);
			}else{
				$this->membmodel->updateMemberFields($value['membid'],true,$data,1);
			}
			$fans=array(
				'membid'=>$value['membid'],
				'nickname'=>$memdata['nickname'],
				'follow'=>$memdata['subscribe'],
				'followtime'=>$memdata['subscribe_time'],
				'unfollowtime'=>$memdata['subscribe']?null:time(),
				'updatetime'=>time(),  
			);
			M('MemberFans')->where(array('id'=>$value['id']))->save($fans);
    	}
		$this->success('更新成功',U('Reply/fans'));
	
    }
    
    /**
	 * 
	 * 撒哈拉的寂寞1032453491@
	 */
    public function renewal(){
    	$REQUEST    =   (array)I('request.');
		$p=I('p',1);
    	$total = $this->fansmodel->count();
		$page = new \Think\Page($total, 50, $REQUEST);
		$firstrow=$page->firstRow;
		$fanslist=M('MemberFans')->field("id,membid,openid,nickname")->limit($page->firstRow.','.$page->listRows)->select();
		if(!empty($fanslist)){
			$this->assign('p',$p);
			$this->display();
			$account=M("member_public")->find();
	        $appid=$account['appid'];
	        $secrect=$account['secret'];
	    	foreach ($fanslist as $key => $value) {
				$memdata=$this->getUserInfo($value['openid']);
				$data=array(
					'nickname'=>$memdata['nickname'],
					'gender'=>$memdata['sex'],
					'residecity'=>$memdata['city'],
					'resideprovince'=>$memdata['province'],
					'nationality'=>$memdata['country'],
					'avatar'=>$memdata['headimgurl'],
				);
				if(empty($value['membid'])){
					$value['membid']=$this->membmodel->register($memdata['nickname'],md5($value['openid']),md5($value['openid']).'@baguatan.com','',$memdata['headimgurl'],strval($value['openid']),$data);
				}else{
					$this->membmodel->updateMemberFields($value['membid'],true,$data,1);
				}
				$fans=array(
					'membid'=>$value['membid'],
					'nickname'=>$memdata['nickname'],
					'follow'=>$memdata['subscribe'],
					'followtime'=>$memdata['subscribe_time'],
					'unfollowtime'=>$memdata['subscribe']?null:time(),
					'updatetime'=>time(),
				);
				M('MemberFans')->where(array('id'=>$value['id']))->save($fans);
	    	}
		}else{
			$this->success('更新成功',U('Reply/fans'));
		}
    }
	/**
	 * 获取用户信息
	 * @author  碎月无晴 <906857431@qq.com>
	 */
	private function getUserInfo($openid){
	    $account=M("member_public")->find();
	    $appid=$account['appid'];
	    $secrect=$account['secret'];
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secrect;
	    $token = $this->GetWeiXinData($url);
	    $token = json_decode(stripslashes($token));
	    $arr = json_decode(json_encode($token), true);
	    $accessToken = $arr['access_token'];
        //获取用户信息
        $get_user_info_url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$accessToken.'&openid='.$openid.'&lang=zh_CN ';
        $user_obj = $this->GetWeiXinData($get_user_info_url);
        $user_obj = json_decode($user_obj, true);
        return $user_obj;
    }
  	/**
     * 发送get请求,返回数据
     * @param string $url
     * @return bool|mixed
	 * @author 沙哈拉的寂寞 <1032453491@qq.com>
     */
    private function GetWeiXinData($url = ''){
        if (empty($url)) {
            return false;
        }
        $data = file_get_contents($url);
        return $data;
    }
  	/**
   	 * 添加会员和粉丝
	 * @author  碎月无晴 <906857431@qq.com>
   	 */  
  	public function addFans($openid){
  	    $account=M("member_public")->find();
  	    $appid=$account['appid'];
  	    $secrect=$account['secret'];
  	    $memberInfo=$this->getUserInfo($openid);
  	    $data=array(
  	        'nickname'=>$memberInfo['nickname'],
  	        'openid'=>strval($openid),
  	        'gender'=>$memberInfo['sex'],
  	        'email'=>md5($openid).'@baguatan.com',
  	        'residecity'=>$memberInfo['city'],
  	        'resideprovince'=>$memberInfo['province'],
  	        'nationality'=>$memberInfo['country'],
  	        'avatar'=>$memberInfo['headimgurl'],
  	    );
  	   
  	    if(empty($value['membid'])){
  	        //$value['membid']=$this->membmodel->register($nickname,md5($value['openid']),md5($value['openid']).'@baguatan.com','',$memdata['headimgurl'],strval($value['openid']),$data);
  	        $memberid=M("member")->add($data);
  	    }else{
  	        $memberid=$this->membmodel->updateMemberFields($value['membid'],true,$data,1);
  	    }
  	    
          $fans['membid']=$memberid;
          $fans['openid']=$openid;
          $fans['nickname']=$memberInfo['nickname'];
          $fans['groupid']=0;
          $fans['follow']=1;
          $fans['followtime']=time();
          $result=$this->fansmodel->add($fans);
  	}
   	/**
     * 获取所有粉丝
	 * @author 碎月无晴 <906857431@qq.com>
     */
    public function download(){
    	$post = $_REQUEST;
		$acc = WeAccount::create(150);
		if(!empty($post['next'])) {
			$nextOpenid = $post['next'];
		}
		$fans = $acc->fansAll($nextOpenid);
		if(!is_error($fans) && is_array($fans['fans'])) {
			$count = count($fans['fans']);
			$buffSize = ceil($count / 500);
			for($i = 0; $i < $buffSize; $i++) {
				$buffer = array_slice($fans['fans'], $i * 500, 500);
				$openids = implode("','", $buffer);
				$openids = "'".$openids."'";
				$sql = 'SELECT `openid` FROM ' . tablename('member_fans') . " WHERE  `openid` IN (".$openids.")";
				$ds = M()->query($sql);
				$exists = array();
				foreach($ds as $row) {
					$exists[] = $row['openid'];
				}
				$sql = '';
				foreach($buffer as $openid) {
					if(!empty($exists) && in_array($openid, $exists)) {
						continue;
					}
					$sql .= "(  0, '".$openid."',  1, 0, ''),";
				}
				if(!empty($sql)) {
					$sql = rtrim($sql, ',');
					$sql = 'INSERT INTO ' . tablename('member_fans') . ' ( `membid`, `openid`, `follow`, `followtime`, `tag`) VALUES ' . $sql;
		
					M()->execute($sql);
				}
			}

			$ret = array();
			$ret['total'] = $fans['total'];
			$ret['count'] = count($fans['fans']) + 2;
			if(!empty($fans['next'])) {
				$ret['next'] = $fans['next'];
			}
			exit(json_encode($ret));
		} else {
			exit(json_encode($fans));
		}
    }
    /**
	 *发送客服消息
	 * @author 碎月无晴 <906857431@qq.com>
	 */
    public function sendCustomMes($touser,$content){
        $account=M("member_public")->find();
        $APPID=$account['appid'];
        $APPSECRET=$account['secret'];
        $TOKEN_URL="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$APPID."&secret=".$APPSECRET;
        $json=file_get_contents($TOKEN_URL);
        $result=json_decode($json);
        $ACC_TOKEN=$result->access_token;
        $data = '{
	    "touser":"'.$touser.'",
	    "msgtype":"text",
	    "text":
	    {
	         "content":"'.$content.'"
	    }
	}';
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$ACC_TOKEN;
        $result =$this->https_post($url,$data);
        $final = json_decode($result);
        return $final;
    }

    /**
	 * 发送get请求
	 * @author 碎月无晴 <906857431@qq.com>
	 */
    private function request_get($url = ''){
        if (empty($url)) {
            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    /**
	 * post请求
	 * @author 碎月无晴 <906857431@qq.com>
	 */
    function https_post($url,$data){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            return 'Errno'.curl_error($curl);
        }
        curl_close($curl);
        return $result;
    }
    /**
	 * 粉丝信息列表
	 * @author 碎月无晴 <906857431@qq.com>
	 */
    public function fans(){
        $title=I('title');
        $status=I('status');
        if($title){
            $where['nickname']=array('like','%'.$title.'%');
        }
        
       if($status||$status=0){
			$where['status']=$status;	
		}
        
        $model=D("FansView");
        $lists=$this->lists($model,$where,array('id'=>'desc'));
        for($i=0,$count=count($lists);$i<$count;$i++){
                $lists[$i]['followtime']=date('Y-m-d H:i:s',$lists[$i]['followtime']);
                $lists[$i]['unfollowtime']=date('Y-m-d H:i:s',$lists[$i]['unfollowtime']);
        }
		$accou=WeAccount::create(150);
		$account=$accou->getAccount();
		$this->assign('account',$account);
        $this->assign('title',$title);
        $this->assign('page',$lists);
        $this->assign('list',$lists);
        $this->assign('do','listInfo');
        $this->assign('where',$where);
        $this->display("Reply/fans");
        
    }
    
    /**
	 * 点击发送信息页面
	 * @author 碎月无晴 <906857431@qq.com>
	 */
    public function sendMesPage(){
        $id=I("id");
        $fans=M("member_fans")->where("id=".$id)->find();
        $nickname=$fans['nickname'];
        $this->assign('fanid',$id);
        $this->assign('nickname',$nickname);
        $this->assign('do','sendMes');
        $this->display("Reply/fansMes");
    }

    /**
	 * 给粉丝发送消息
	 * @author 碎月无晴 <906857431@qq.com>
	 */
    public function sendMessage(){
        $id=I("id");
        $content=I("content");
        $fans=M("member_fans")->where("id=".$id)->find();
        $openid=$fans['openid'];
        $sql="SELECT MAX(id) id  FROM sx_chats_record WHERE openid='".$openid."'";
        $maxid=M()->query($sql);
        $maxid=$maxid[0]['id'];
        $nickname=$fans['nickname'];
        if($content!=null){
            //发送消息
            $msgtype="text";
            $fina=$this->sendCustomMes($openid,$content);
           // 
            if(is_object($fina)) {
                $array = (array)$fina;
            } 
            if($array['errcode']==0){
                //记录到客服消息日志表
                $chat['flag']=2;
                $chat['openid']=$openid;
                $chat['msgtype']="text";
                $chat['content']=$content;
                $chat['createtime']=time();
                $result=M("chats_record")->add($chat);
            }else{
                //发送消息失败
              $error="发送信息失败";
            }
        }
        $exit=json_encode(array('error'=>$error));
        echo $exit;
        exit();
    }

    /**
	 * 聊天记录刷新
	 * @author  碎月无晴 <906857431@qq.com>
	 */
    public function chatLog(){
        $fanid=intval(I("fanid"));
        $openid=M("member_fans")->where("id=".$fanid)->find();
        $membid=$openid['membid'];
        $membid=M("member")->where("id=".$membid)->find();
        //头像
        $avatar=$membid['avatar'];
        //openid
        $openid=$openid['openid'];
        //页面显示最大id
        $maxid=I("id");
        //第一次打开对话框，显示最近5条消息 
        if($maxid<=0){
            //查询到当前最大id -- 
            $sql="SELECT MAX(id) id  FROM sx_chats_record WHERE openid='".$openid."'";
            $maxid=M()->query($sql);
            $maxid=$maxid[0]["id"];
            $minid=$maxid-5;
            $sql="SELECT * FROM sx_chats_record  WHERE openid='".$openid."'  and id>".$minid." order by id asc  LIMIT 5";
        }else{ 
            
            //判断页面的聊天是不是最新的，如果是，不刷新
            $sql="SELECT MAX(id) id  FROM sx_chats_record WHERE openid='".$openid."'";
           
            $newmaxid=M()->query($sql);
            $newmaxid=$newmaxid[0]["id"];
            if($newmaxid==$maxid){
                echo json_encode(array("str"=>"","maxid"=>$maxid));
                exit();
            }else{
                // echo json_encode(array("str"=>"","maxid"=>$maxid));
                $sql="SELECT * FROM sx_chats_record  WHERE openid='".$openid."' AND id>".$maxid." LIMIT 5";
                //重新 给maxid赋值
                $maxSql="SELECT max(id) maxid  FROM sx_chats_record  WHERE openid='".$openid."' AND id>".$maxid." LIMIT 5";
                $maxid=M()->query($maxSql);
                $maxid=$maxid[0]['maxid'];
            }
        }
        $record=M()->query($sql);
        $count=count($record);
        for($i=0;$i<$count;$i++){
            if($record[$i]['flag']==1){
                
                $str.= '<div class="pull-left col-lg-12 col-md-12 col-sm-12 col-xs-12">' .
                    '<div class="pull-left">' .
                    '<img src="' . $avatar . '" width="35"><br>' .
                    '</div>' .
                    '<div class="alert alert-info pull-left infol">' .
                    $record[$i]['content'] . '<br>' . date('m-d H:i:s', $record[$i]['createtime']) .
                    '</div>' .
                    '<div style="clear:both"></div>' .
                    '</div>'.
                    '<div style="clear:both"></div>';
                
               // $str.="<div><li>粉丝：".$record[$i]['content']."</li>";
            }else{
                $str.= '<div class="pull-left col-lg-12 col-md-12 col-sm-12 col-xs-12"  >' .
                    '<div class="pull-left" style="margin-right:10px;">' .
                    '<img src="http://kj.baguatan.cn/Public/Admin/Images/baguatan.jpg" width="35"><br>' .
                    '</div>' .
                    '<div class="alert alert-info pull-left infol">' .
                    $record[$i]['content'] . '<br>' . date('m-d H:i:s', $record[$i]['createtime']) .
                    '</div>' .
                    '<div style="clear:both"></div>' .
                    '</div>'.
                    '<div style="clear:both"></div>';
            }
        }
        echo json_encode(array("str"=>$str,"maxid"=>$maxid));
        exit();
        //
        //粉丝信息显示左边    客服消息显示右边
      /*   $count=count($record);
        for($i=0;$i<$count;$i++){
            //粉丝信息，显示在左边
            $str .= tpl_chats_log($avatar,$record[$i]['content'], $record[$i]['createtime'],$record[$i]['flag']);
        } */
       
    }

    /**
	 * 文字回复列表
	 * @author 碎月无晴 <906857431@qq.com>
	 */
    public function textReply(){
        $title=I('title');
        $status=I('status');
        if($title){
            $where=" and rule.name like '%".$title."%'";
        }
        if($status!=-1&&$status!=null){
            $where.=" and rule.status=".$status;
        }
        $sql=" SELECT reply.id , rule.`name`,reply.`content`  FROM sx_basic_reply reply,sx_rule rule
                WHERE reply.`rid`=rule.`id` ".$where;
        $lists=M()->query($sql);
        $this->assign('list',$lists);
        $this->assign('do','listInfo');
        $where=array();
        $where['status']=$status;
        $where['tiptitle']=$title.'';  
        $this->assign('where',$where);
        $this->display("Reply/Index");
    }
    /**
	 * 添加文字回复
	 * @author 碎月无晴 <906857431@qq.com>
	 */
    public function addTextReply(){
        $keyword=I('keyword');
        $content=I('content');
        $id=I("id");
        if($keyword!=null&&$content!=null){
            //添加到rule表     basic_reply
            $rule['uniacid']=0;
            $rule['name']=$keyword;
            $rule['module']='basic';
            $rule['status']=I("status");
            $rule['displayorder']=0;
            $result=M("rule")->add($rule);
            if($result){
                $ruleid=M()->query("select max(id) id from sx_rule");
                $ruleid=$ruleid[0]['id'];
                $reply['rid']=$ruleid;
                $reply['content']=$content;
                M("basic_reply")->add($reply);
            }
              $this->redirect('Reply/textReply');
        }else{
            //显示详情页面
            $replyInfo="";
            $this->assign('do','listDetail');
            $this->display("Reply/Index");
        }
       
    }
    
    /**
	 * 删除文字回复
	 * @author 碎月无晴 <906857431@qq.com>
	 */
    public function delTextReply(){
        $id=I("id");
        $replyInfo=M("basic_reply")->where("id=".$id)->find();
        $rid=$replyInfo["rid"];
        if($id!=null){
            $ReplyInfo=M("basic_reply");
            $ReplyInfo->where('id='.$id)->delete();
            M("rule")->where("id=".$rid)->delete();
            $this->redirect('Reply/textReply');
        }
    }

    /**
	 * 图文回复列表
	 * @author 碎月无晴 <906857431@qq.com>
	 */
    public function imgReply(){                 
        $sql="SELECT rule.name,news.* FROM sx_rule rule,sx_news_reply news
              WHERE rule.id=news.rid";
        $imgReply=M()->query($sql);
        $this->assign('list',$imgReply);
        $this->assign('do','imgReplyInfo');
        $this->display("Reply/imgReply");
    }

    /**
	 * 添加图文回复
	 * @author 碎月无晴 <906857431@qq.com>
	 */
    public function addImgReply(){
        $imgReply=I("imgReply");
        $id=intval(I("id"));
        if($imgReply!=null){
            $imgReply['content']= htmlspecialchars_decode($imgReply['content']);
            if($id!=null){
                //查询原来关键字 判断是否相等 如果不相等 则修改
                 $imgReplyInfo=M("news_reply")->where(array('id'=>$id))->find();
                 $ruleid=$imgReplyInfo['rid'];
                 $ruleName=M("rule")->where(array('id'=>$ruleid))->find();
                 $ruleName=$ruleName['title'];
                 $postRuleName=trim(I("rule"));
                 if($ruleName!=$postRuleName){
                     $data['name']=$postRuleName;
                     $result=M("rule")->where(array('id'=>$ruleid))->save($data);
                 }
                //修改图文
                $result=M("news_reply")->where("id=".$id)->save($imgReply);
            }else{
                //添加到规则表
                $rule['uniacid']=0;
                $rule['name']=trim(I("rule"));
                $rule['module']='news';
                $result=M("rule")->add($rule);
                $ruleid=M()->query("select max(id) id from sx_rule");
                $ruleid=$ruleid[0]['id'];
                if($result){
                    $imgReply['rid']=$ruleid;
                    M("news_reply")->add($imgReply);
                }
            }
            $this->redirect('Reply/imgReply');
           
        }else{
            //跳转页面
            //查询图文回复详情
            if($id!=null){
                $sql="SELECT rule.name,news.* FROM sx_rule rule,sx_news_reply news
                      WHERE rule.id=news.rid and news.id=".$id;
                $imgReplyInfo=M()->query($sql);
                $imgReplyInfo=$imgReplyInfo[0];
                $imgReplyInfo['thumb']=$imgReplyInfo['thumb'];
            }
            $this->assign('reply',$imgReplyInfo);
            $this->display("Reply/imgDetail");
        }
    }

    /**
	 * 删除图文回复
	 * @author 碎月无晴 <906857431@qq.com>
	 */
    public function delImgReply(){
        $id=I("id");
        $replyInfo=M("news_reply")->where("id=".$id)->find();
        $rid=$replyInfo["rid"];
        M("news_reply")->where("id=".$id)->delete();
        M("rule")->where("id=".$rid)->delete();
        $this->redirect("Reply/imgReply");
    }

    /**
	 * 显示自定义菜单
	 * @author 碎月无晴 <906857431@qq.com>
	 */
    public function menuList(){
    	
		$current['designer'] = ' class="current"';
		
		$accounts = uni_accounts();
		$do=$_REQUEST['do'];
		$dos = array('display', 'save', 'remove', 'refresh','search_key');
		$do = in_array($do, $dos) ? $do : 'display';
		if(IS_AJAX) {
			if($do == 'search_key') {
				$condition = array();
				$key_word = trim($_REQUEST['key_word']);
				if(!empty($key_word)) {
					$condition['content'] = array('like'," '%".$key_word."%' ");
				}
				$data = M('RuleKeyword')->field('content')->where($condition)->order('id DESC,displayorder DESC')->limit('0,15')->select();
				$exit_da = array();
				if(!empty($data)) {
					foreach($data as $da) {
						$exit_da[] = $da['content'];
					}
				}
				exit(json_encode($exit_da));
			}
			$post = I();
			if(!empty($post['method'])) {
				$do = $post['method'];
			}
		}
		if($do == 'remove') {
			$flag = true;
			foreach($accounts as $acc) {
				$account = WeAccount::create(150);
				$update = $account->menuQuery();
				$update[] = array('createtime' => time());
				if($flag) {
					M('MemberPublic')->where(array('id'=>150))->save(array('menuset' => iserializer($update)));
					$flag = false;
				}
				$ret = $account->menuDelete();
				if(is_error($ret)) {
					exit(json_encode($ret));
				}
			}
			exit('success');
		}
		
		if($do == 'save') {
			if ($post['type'] == 'history') {
				$post['menus'] = M('MemberPublic')->where(array('id'=>150))->getField('menuset');
				$post['menus'] = iunserializer(base64_decode($post['menus']));
				if(empty($post['menus']) || !is_array($post['menus'])){
					exit(json_encode(error('-1','菜单数据历史记录错误.')));
				}
				unset($post['menus'][count($post['menus']) - 1]);
			}
		
			if (!empty($post['menus'])) {
				foreach ($post['menus'] as &$m) {
					$m['title'] = preg_replace_callback('/\:\:([0-9a-zA-Z_-]+)\:\:/', create_function('$matches', 'return utf8_bytes(hexdec($matches[1]));'), $m['title']);
					if (!empty($m['subMenus'])) {
						foreach ($m['subMenus'] as &$subm) {
							$subm['title'] = preg_replace_callback('/\:\:([0-9a-zA-Z_-]+)\:\:/', create_function('$matches', 'return utf8_bytes(hexdec($matches[1]));'), $subm['title']);
						}
					}
				}
			}
			$menuset = $menus = $post['menus'];
			
			if ($post['type'] != 'history') {
				$menuset[] = array('createtime' => time());
				M('MemberPublic')->where(array('id'=>150))->save(array('menuset' => base64_encode(iserializer($menuset))));
			}
			
			$account = WeAccount::create(150);
			$ret = $account->menuCreate($menus);
			if(is_error($ret)) {
				exit(json_encode($ret));
			}
			exit('success');
		}
		
		if($do == 'display') {
			if(!empty($accounts)) {
				if(empty($menus) || !is_array($menus)) {
					$account = WeAccount::create(150);
					$menus = $account->menuQuery();
				}
			}
			if (is_error($menus)) {
				$this->error($menus['message']);
			}
			$hmenus = array();
			$hmenu = M('MemberPublic')->where(array('id'=>150))->getField('menuset');
			if (!empty($hmenu)) {
				$hmenus = iunserializer(base64_decode($hmenu));
				$createtime = !empty($hmenus) && is_array($hmenus) ? array_pop($hmenus) : '';
			}
			if(!is_array($menus)) {
				$menus = array();
			}
		}
		$this->assign('accounts',$accounts);
   		$this->assign('menus',$menus);
        $this->assign('hmenus',$hmenus);
		$this->assign('createtime',$createtime);
        $this->display();
    }

    /**
     * 清空菜单
     * @author 碎月无晴 <906857431@qq.com>
     */
     public function clearMenu(){
         $account=M("member_public")->find();
         $APPID=$account['appid'];
         $APPSECRET=$account['secret'];
         $TOKEN_URL="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$APPID."&secret=".$APPSECRET;
         $json=file_get_contents($TOKEN_URL);
         $result=json_decode($json);
         $ACC_TOKEN=$result->access_token;
         $delUrl="https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$ACC_TOKEN;
         $result=D("member_public")->request_get($delUrl);
         $result =json_decode($result, true);
         $result=$result['errcode'];
         if($result==0){
             $this->success('清空成功',U('Reply/menuList'));
         }else{
             $this->error('清空失败！');
         }
         
     }
    /**
	 * 删除自定义菜单
	 * @author 碎月无晴 <906857431@qq.com>
	 */
    public function delMenu(){
        $id=intval(I("id"));
        $pid=intval(I("pid"));
        //一级菜单，删除此菜单，并删除所有子菜单
        if($pid==0){
           $menu=M("custom_menu");
           $result=$menu->where('id='.$id)->delete();
            if($result){
                //删除子菜单
               $result=$menu->where('pid='.$id)->delete();
            }
           
        }else{
        //二级菜单，删除此菜单
            $menu=M("custom_menu");
            $result=$menu->where('id='.$id)->delete();
        }
        $this->redirect('Reply/menuList');
        
    }



    /**
	 * 设置欢迎语
	 * @author 碎月无晴 <906857431@qq.com>
	 */
    public function welcome(){
        $ruleid=I("rule");
        if($ruleid!=null){
           // 修改数据
           $data['welcome']=$ruleid;
           $id=M("member_public")->select();
           $id=$id[0]['id'];
           $where['id']=$id;
           M('member_public')->where('id='.$id)->save($data);
           $this->redirect('Reply/welcome');
        }else{
            //查询所有规则
            $ruleList=M("rule")->select();
            $ruleid=M("member_public")->find();
            $ruleid=$ruleid['welcome'];
            //跳转页面
            $this->assign('ruleid',$ruleid);
            $this->assign('rule',$ruleList);
            $this->assign('do','welcome');
            $this->display("Reply/welcome");
        }
    }
    
}
?>