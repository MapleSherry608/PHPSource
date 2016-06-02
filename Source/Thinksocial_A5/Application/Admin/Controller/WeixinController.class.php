<?php

namespace Admin\Controller;
use Think\Controller;
use Common\Api\WeAccount;
use Common\Api\WeiXinAccount;
/**
 * 微信交互控制器
 *  
 */
class WeixinController  extends Controller  {
    
	public function _initialize(){
		$this->publicinfo=M("MemberPublic")->find();
	}
	//服务器url配置
	public function checkToken(){
	    //b26a67335d8fc1bd56496dd0ae43e7ef 
	    if (!isset($_GET['echostr'])) {
	        //创建默认菜单
	        $acc = WeAccount::create(150);
			$accessToken=$acc->fetch_token();
	        $create_menu_url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$accessToken;
	        $createMenu=$this->getJsonMenu();
	        //$result = $this->https_request($create_menu_url,strval($createMenu));
	        $result = $this->https_request($create_menu_url,$createMenu);
	        //响应事件
	        $this->responseMsg();
	        echo $result;
	    }else{
	        //验证token
	       $this->valid();
	    }
	}
	//获取自定义菜单
	public function getJsonMenu(){
	    header("Content-Type: text/html;charset=utf-8");
		$hmenu = M('MemberPublic')->where(array('id'=>150))->getField('menuset');
		$hmenus = iunserializer(base64_decode($hmenu));
		$hmenus = $this->menuBuildMenuSet($hmenus);
	    return $hmenus;
	}
	
	//格式化路径
	private function dotrans($code) {
	    return preg_replace("#\\\u([0-9a-f]+)#ie", "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))",$code);
	}
	//响应消息
	public function responseMsg(){
	    $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];//file_get_contents('php://input');
	    if (!empty($postStr)){
	        libxml_disable_entity_loader(true);
	        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
	        $ERENT_TYPE = trim($postObj->MsgType);
	        if(!empty($postObj->FromUserName)){
	            define('OPENID_O',strval($postObj->FromUserName));
	        }
	        //记录日志
	        $this->logger($ERENT_TYPE,$postObj);
	        switch ($ERENT_TYPE)
	        {
	            //事件
	            case "event":
	                $result = $this->receiveEvent($postObj);
	                break;
	            //文本    
	            case "text":
	                $result = $this->receiveText($postObj);
	                break;
	            //图片--发送图片返回
	            case "image":
	                $result = $this->receiveImage($postObj);
	                break;
	            //位置，回复经纬度    
	            case "location":
	                $result = $this->receiveLocation($postObj);
	                break;
	            //录音    --发送接收到的录音返回
	            case "voice":
	                $result = $this->receiveVoice($postObj);
	                break;
	            //视频--
	            case "video":
	                $result = $this->receiveVideo($postObj);
	                break;
	            case "link":
	                $result = $this->receiveLink($postObj);
	                break;
	            default:
	                $result = "unknown msg type: ".$ERENT_TYPE;
	                break;
	        }
	         
	        echo $result;
	        
	    }else {
	        echo "";
	        exit;
	    }  
	}
	
	//关注回复
	private function subcriReply($object,$type,$ruleid){
	/*     $result=$this->transmitText($object, "欢迎关注~");
	    return $result; */
	    if($type=="basic"){
	         //发送文本回复
	         $content=M("basic_reply")->where("rid=".$ruleid)->find();
	         $content=$content["content"];
	         $result=$this->transmitText($object, $content);
	         return $result;
	     }else{
	         //发送图文回复
	         $ImgReply=M("news_reply")->where("rid=$ruleid")->select();
	         //图文回复
	         $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							 <ArticleCount>%s</ArticleCount>
                            <Articles>";
	         for($i=0;$i<count($ImgReply);$i++){
	             //title
	             $title=$ImgReply[$i]['title'];
	             //desc
	             $desc=$ImgReply[$i]['description'];
	             //url
	             $link=$ImgReply[$i]['url'];
	             //picurl
	             $picurl=$ImgReply[$i]['thumb'];
	             if(stristr($ImgReply[$i]['thumb'],$_SERVER['SERVER_NAME'])){
	              $picurl=$ImgReply[$i]['thumb'];
	              }else{
	                        $siteroot = rtrim(SITEROOT,"/");
	                         $picurl=$siteroot.$ImgReply[$i]['thumb'];
	              }    
	             $textTpl.= " <item>
    	                   <Title><![CDATA[".$title."]]></Title>
    	                   <Description><![CDATA[".$desc."]]></Description>
    	                   <PicUrl><![CDATA[".$picurl."]]></PicUrl>
                           <Url><![CDATA[".$link."]]></Url>
    	                   </item>";
	         }
	         $textTpl.= "</Articles>
                                       <FuncFlag>0</FuncFlag>
                                       </xml>
                                         ";
	         //图文回复
	         $msgType = "news";
	         $count=count($ImgReply);
	         $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $msgType,$count);
	        return $result;
	     }  
	     
	}
	
	//接收事件消息
	private function receiveEvent($object){
	    $content = "";
	    switch ($object->Event)
	    {
	        case "subscribe":
				$this->logger("subcribe",$object);
	           	$openid=$object->FromUserName;
	            $rule = $this->subscribeEvent($openid);
	            if(is_array($rule)){
	            	if($rule['module'] == "news"){
	            		$newsList = M('NewsReply')->field(array('id','title','description','thumb','url'))->where(array('rid'=>$rule['id']))->order('displayorder desc')->select();
						if($newsList){
							$content = array();
							foreach($newsList as $key=>$val){
								$content[$key]['Title'] = $val['title'];
								$content[$key]['Description'] = $val['description'];
								$content[$key]['PicUrl'] = tomedia($val['thumb']);//;
								if(!empty($val['url'])){
									$content[$key]['Url'] = $val['url'].'&openid='.$object->FromUserName;
								}else{
									$content[$key]['Url'] = strval(MURL("index/Relation/recontent",array("id"=>intval($val['id'])),true,true)).'&openid='.$object->FromUserName;
								}
							}
							break;
						}
					}else{
						$basicContent = M('BasicReply')->where(array('rid'=>$rule['id']))->getField('content');
						if($basicContent){
							$content = $basicContent;
							break;
						}
					}
				}
	            break;
	        case "unsubscribe":
	            //修改状态
	            $openid = $object->FromUserName;
	            $this->unsubsribeEvent($openid);
	            break;
	        case "SCAN":
	            $content = "扫描场景 ".$object->EventKey;
	            break;
	        case "CLICK":
	            $ruleModel = M('Rule');
				$newsModel = M('NewsReply');
				$basicModel = M('BasicReply');
	            $newsRule = $ruleModel->where(array('model'=>'news','status'=>1))->order('displayorder desc')->select();
	            $basicRule = $ruleModel->where(array('model'=>'basic','status'=>1))->order('displayorder desc')->select();
	            $newsList = array();
				$basicContent = '';
				if($newsRule){
		            foreach($newsRule as $v){
		            	if($object->EventKey == $v['name']){
		            		$newsList = $newsModel->field(array('id','title','description','thumb','url'))->where(array('rid'=>$v['id']))->order('displayorder desc')->select();
							if($newsList){
								$content = array();
								foreach($newsList as $key=>$val){
									$content[$key]['Title'] = $val['title'];
									$content[$key]['Description'] = $val['description'];
									$content[$key]['PicUrl'] = tomedia($val['thumb']);
									if(!empty($val['url'])){
										$content[$key]['Url'] = $val['url'].'&openid='.$object->FromUserName;
									}else{
										$content[$key]['Url'] = strval(U("Home/Relation/recontent",array("id"=>$val['id']),true,true)).'&openid='.$object->FromUserName;
									}
								}
								break;
							}
		            	}
		            }
				}
				if((!$newsList)&&$basicRule){
					foreach($basicRule as $t){
						if($object->EventKey == $t['name']){
							$basicContent = $basicModel->where(array('rid'=>$t['id']))->order('displayorder desc')->getField('content');
							if($basicContent){
								$content = $basicContent;
								break;
							}
						}
					}
				}
				if((!$newsList)&&(!$basicContent)){
					$content = "单击菜单：".$object->EventKey;
				}
	            break;
	        case "LOCATION":
	            $content = "上传位置：纬度 ".$object->Latitude.";经度 ".$object->Longitude;
	            return $result;
	            break;
	        case "VIEW":
	            $content = "跳转链接 ".$object->EventKey;
	            break;
	        default:
	            $content = "receive a new event: ".$object->Event;
	            break;
	    }
	    if(is_array($content)){
	    	if(isset($content[0]['PicUrl'])){
	    		$result = $this->transmitNews($object, $content);
	    	}else if(isset($content['MusicUrl'])){
	    		$result = $this->transmitMusic($object, $content);
	    	}
	    }else{
	    	$result = $this->transmitText($object, $content);
	    }
		return $result;
	}
	//接收文本消息
	private function receiveText($object){
	    $word=$object->Content;
	    //判断是否开启常用服务接口，如果开启，则进行匹配回复 
	    //----城市天气  百度百科 即时翻译  今日老黄历 看新闻 快递查询
	  if(strstr($word,"天气")){
	        $pos=strpos($word,"天气");
	        $city= substr($word,0,$pos);
	        $url = "http://apix.sinaapp.com/weather/?appkey=".$object->ToUserName."&city=".urlencode($city);
	        $output = $this->request_get($url);
	        $weather = json_decode($output, true);
	        $result=D("Rule")->receiveWeacher($object,$weather);
	        return $result;
	    }
	    //百度百科 "百科+查询内容"   如: "百科姚明"
	    if(strstr($word,"百科")){
	        $result=D("Rule")->receiveBaike($word,$object);
	        return $result;
	    }
	    //即使翻译 @查询内容(中文或英文)
	    if(strstr($word,"@")){
	        $result=D("Rule")->receiveTranslate($word,$object);
	        return $result;
	    }
	    //今日老黄历    "日历", "万年历", "黄历"或"几号"
	    if(strstr($word,"日历")){
	        $result=D("Rule")->receiveCalendar($object);
	        return $result;
	    }
	    //看新闻
	    if(strstr($word,"新闻")){
	        $result=D("Rule")->receiveNews($word,$object);
	        return $result;
	    }
	    //快递查询
	    if(strstr($word,"快递")){
	        $result=D("Rule")->receiveExpress($word,$object);
	        return $result;
	    }
	    
	    $keywordList=M("rule")->where("status=1")->select();
	     $i=1;
	        foreach($keywordList as $keyword){
	            if($word==$keyword['name']){
	                //查询回复类型
	                $ruleId=$keyword['id'];
	                $replyType=$keyword['module'];
	                if($replyType=="basic"){
	                    //文字回复
	                    $basicReply=M("basic_reply")->where("rid=$ruleId")->find();
	                    $Content=$basicReply['content'];
	                    $result = $this->transmitText($object, $Content);
	                    return $result;
	                }elseif($replyType=="news"){
	                    //图文回复
	                    $ImgReply=M("news_reply")->where("rid=$ruleId")->select();
	                    //图文回复
	                    $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							 <ArticleCount>%s</ArticleCount>
                            <Articles>";
	                    for($i=0;$i<count($ImgReply);$i++){
	                        //title
	                        $title=$ImgReply[$i]['title'];
	                        //desc
	                        $desc=$ImgReply[$i]['description'];
	                        //url http://sx.baguatan.cn/index.php?s=Refuel/giveSelfrefuel
	                        if(!empty($ImgReply[$i]['url'])){
	                        	$link=$ImgReply[$i]['url'].'/openid/'.$object->FromUserName;
							}else{
								$link=strval(U("Home/Relation/recontent",array("id"=>$ImgReply[$i]['id']),true,true)).'&openid='.$object->FromUserName;
							}
	                        //picurl
	                        $picurl=$ImgReply[$i]['thumb'];
	                        if(stristr($ImgReply[$i]['thumb'],$_SERVER['SERVER_NAME'])){
	                         $picurl=$ImgReply[$i]['thumb'];
	                         }else{
	                         //$picurl="http://".$_SERVER['SERVER_NAME'].$ImgReply[$i]['thumb'];
	                         $siteroot = rtrim(SITEROOT,"/");
	                         $picurl=$siteroot.$ImgReply[$i]['thumb'];
	                        }  
	                        $textTpl.= " <item>
    	                   <Title><![CDATA[".$title."]]></Title>
    	                   <Description><![CDATA[".$desc."]]></Description>
    	                   <PicUrl><![CDATA[".$picurl."]]></PicUrl>
                           <Url><![CDATA[".$link."]]></Url>
    	                   </item>";
	                    }
	                    $textTpl.= "</Articles>
                                       <FuncFlag>0</FuncFlag>
                                       </xml>
                                         ";
	                    //图文回复
	                    $msgType = "news";
	                    $count=count($ImgReply);
	                    $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $msgType,$count);
	                    echo $resultStr;
	                    break;
	                }
	            //最后一个都没有匹配到，则默认回复
	            }elseif($i==count($keywordList)&&$word!=$keyword['name']){
	               /*  $result = $this->transmitText($object, $Content);
	                return $result; */
	                //发送客服消息
	                $result = $this->transmitService($object);
	                //记录客服消息
	                $chat['flag']=1;
                    $chat['openid']= trim($object->FromUserName);;
                    $chat['msgtype']="text";
                    $chat['content']=trim($object->Content);
                    $chat['createtime']=time();
                    M("chats_record")->add($chat);
	                return $result;
	            }
	            $i++;
	        }
	}
	
	//回复多客服消息
	public function transmitService($object){
	    $xmlTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[transfer_customer_service]]></MsgType>
                   </xml>";
	    $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
	    return $result;
	}
	
	//接收图片消息
	private function receiveImage($object){
	    $content = array("MediaId"=>$object->MediaId);
	    $result = $this->transmitImage($object, $content);
	    return $result;
	}
	
	//接收位置消息
	private function receiveLocation($object){
	    $content = "你发送的是位置，纬度为：".$object->Location_X."；经度为：".$object->Location_Y."；缩放级别为：".$object->Scale."；位置为：".$object->Label;
	    $result = $this->transmitText($object, $content);
	    return $result;
	}
	
	//接收语音消息
	private function receiveVoice($object){
	    if (isset($object->Recognition) && !empty($object->Recognition)){
	        $content = "你刚才说的是：".$object->Recognition;
	        $result = $this->transmitText($object, $content);
	    }else{
	        $content = array("MediaId"=>$object->MediaId);
	        $result = $this->transmitVoice($object, $content);
	    }
	
	    return $result;
	}
	
	//接收视频消息
	private function receiveVideo($object){
	    $content = array("MediaId"=>$object->MediaId, "ThumbMediaId"=>$object->ThumbMediaId, "Title"=>"", "Description"=>"");
	    $result = $this->transmitVideo($object, $content);
	    return $result;
	}
	
	//接收链接消息
	private function receiveLink($object){
	    $content = "你发送的是链接，标题为：".$object->Title."；内容为：".$object->Description."；链接地址为：".$object->Url;
	    $result = $this->transmitText($object, $content);
	    return $result;
	}
	
	//回复文本消息
	private function transmitText($object, $content){
	    $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    </xml>";
	    $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
	    return $result;
	}
	
	//回复图片消息
	private function transmitImage($object, $imageArray){
	    $itemTpl = "<Image>
                        <MediaId><![CDATA[%s]]></MediaId>
                    </Image>";
	
	    $item_str = sprintf($itemTpl, $imageArray['MediaId']);
	
	    $textTpl = "<xml>
                	    <ToUserName><![CDATA[%s]]></ToUserName>
                	    <FromUserName><![CDATA[%s]]></FromUserName>
                	    <CreateTime>%s</CreateTime>
                	    <MsgType><![CDATA[image]]></MsgType>
                	    $item_str
                    </xml>";
	
	    
	    $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
	    return $result;
	}
	//回复图文消息
	private function transmitNews($object, $arr_item){
	        if(!is_array($arr_item))
	            return;
	
	        $itemTpl = "<item>
					        <Title><![CDATA[%s]]></Title>
					        <Description><![CDATA[%s]]></Description>
					        <PicUrl><![CDATA[%s]]></PicUrl>
					        <Url><![CDATA[%s]]></Url>
					    </item>";
	        $item_str = "";
	        foreach ($arr_item as $item)
	            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
	
	        $newsTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[news]]></MsgType>
							<Content><![CDATA[]]></Content>
							<ArticleCount>%s</ArticleCount>
							<Articles>
							$item_str</Articles>
						</xml>";
	
	        $result = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($arr_item));
	        return $result;
	}
	
	//回复语音消息
	private function transmitVoice($object, $voiceArray){
	    $itemTpl = "<Voice>
                        <MediaId><![CDATA[%s]]></MediaId>
                    </Voice>";
	
	    $item_str = sprintf($itemTpl, $voiceArray['MediaId']);
	
	    $textTpl = "<xml>
                	    <ToUserName><![CDATA[%s]]></ToUserName>
                	    <FromUserName><![CDATA[%s]]></FromUserName>
                	    <CreateTime>%s</CreateTime>
                	    <MsgType><![CDATA[voice]]></MsgType>
                	    $item_str
	                </xml>";
	
	    $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
	    return $result;
	}
	
	//回复视频消息
	private function transmitVideo($object, $videoArray){
	    $itemTpl = "<Video>
                        <MediaId><![CDATA[%s]]></MediaId>
                        <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
                        <Title><![CDATA[%s]]></Title>
                        <Description><![CDATA[%s]]></Description>
                    </Video>";
	
	    $item_str = sprintf($itemTpl, $videoArray['MediaId'], $videoArray['ThumbMediaId'], $videoArray['Title'], $videoArray['Description']);
	    $textTpl = "<xml>
                	    <ToUserName><![CDATA[%s]]></ToUserName>
                	    <FromUserName><![CDATA[%s]]></FromUserName>
                	    <CreateTime>%s</CreateTime>
                	    <MsgType><![CDATA[video]]></MsgType>
                	    $item_str
	               </xml>";
	
	    $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
	    return $result;
	}
	//回复音乐消息
	private function transmitMusic($object, $musicArray){
	    $itemTpl = "<Music>
                        <Title><![CDATA[%s]]></Title>
                        <Description><![CDATA[%s]]></Description>
                        <MusicUrl><![CDATA[%s]]></MusicUrl>
                        <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                    </Music>";
	
	    $item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);
	
	    $textTpl = "<xml>
                	    <ToUserName><![CDATA[%s]]></ToUserName>
                	    <FromUserName><![CDATA[%s]]></FromUserName>
                	    <CreateTime>%s</CreateTime>
                	    <MsgType><![CDATA[music]]></MsgType>
                	    $item_str
                   </xml>";
	
	    $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
	    return $result;
	}
	//自定义菜单
	private function https_request($url,$data = null){
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	    if (!empty($data)){
	        curl_setopt($curl, CURLOPT_POST, 1);
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	    }
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    $output = curl_exec($curl);
	    curl_close($curl);
	    return $output;
	}
	//订阅号测试是否能获取openid
	public function subsriTest($openid){
	    $fans['membid']=88;
	    $fans['openid']=strval($openid);
	    $fans['nickname']='DY';
	    $fans['groupid']=0;
	    $fans['follow']=1;
	    $fans['followtime']=time();
	    $result=M("member_fans")->add($fans);
	    $content="欢迎关注~";
	    return $content;
	}
	//关注事件
	private function subscribeEvent($openid){
		$acc = WeAccount::create(150);
	    $user_obj = $acc->fansQueryInfo($openid,true);
	    $user_obj = json_decode($user_obj, true);
	    //解析json
	    $nickname=$user_obj['nickname'];
	    $avatar=$user_obj['headimgurl'];
	   // if($uid>=0){
	    //查询会员表，判断是否存在，如果不存在添加数据
	    $ifMemExit=M("member")->where(array('openid'=>strval($openid)))->find();
	    if(empty($ifMemExit)){
	        $member['openid']=strval($openid);
	        $member['nickname']=$nickname;
	        $member['avatar']=$avatar;
	        $member['gender']=$user_obj['sex'];
	        $member['residecity']=$user_obj['city'];
	        $member['resideprovince']=$user_obj['province'];
	        $member['nationality']=$user_obj['country'];
	        $member['email']=md5(strval($openid)).'@baguatan.com';
			$member['createtime']=time();
	        $result=M("member")->add($member);
			$fansinfo=M("member")->where(array('openid'=>strval($openid)))->find();
			if($fansinfo){
				 //添加到fans
	  	        $fans['membid']=$result;
	  	        $fans['openid']=strval($openid);
	  	        $fans['nickname']=$nickname;
	  	        $fans['groupid']=0;
	 	        $fans['follow']=1;
	 	        $fans['followtime']=time();
	  	        $result=M("member_fans")->add($fans);
			}
	    }else{
	        //修改fans 更新关注状态 关注时间
	        $fans['followtime']=time();
	        $fans['follow']=1;
	        $where['openid']=strval($openid);
	        M("member_fans")->where($where)->save($fans);
	    }  
		$account=$acc->getAccount();
        $rule=M("rule")->where(array("id"=>$account['welcome']))->find();
	    return $rule;
	}
	
	//取消关注事件
	private function unsubsribeEvent($openid){
	    $fans['unfollowtime']=time();
	    $fans['follow']=2;
	    $where['openid']=strval($openid);
	    M("member_fans")->where($where)->save($fans);
	}
	
	// 发送get请求
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

	//验证token
	public function valid(){
	    if (! empty ( $_GET ['echostr'] ) && ! empty ( $_GET ["signature"] ) && ! empty ( $_GET ["nonce"] )) {
	        $signature = $_GET ["signature"];
	        $timestamp = $_GET ["timestamp"];
	        $nonce = $_GET ["nonce"];
			
	        $tmpArr = array ( $this->publicinfo['token'],  $timestamp, $nonce);
	        sort ( $tmpArr, SORT_STRING );
	        $tmpStr = sha1 ( implode ( $tmpArr ) );
	        if ($tmpStr == $signature) {
	            echo $_GET ["echostr"];
	        }
	        exit ();
	    }
	}
	
    
    //记录日志
	private function logger($type,$postObj){
	    $data['openid']=trim($postObj->FromUserName);
	    $data['createtime']=time();
	    if($type=="text"){
	        $data['content']=trim($postObj->Content);
	        $data['type']="文本";
	    }elseif($type=="image"){
	        $data['content']=trim($postObj->MediaId);
	        $data['type']="图片";
	    }elseif($type=="voice"){
	        $data['content']=trim($postObj->MediaId);
	        $data['type']="录音";
	    }elseif($type=="video"){
	        $data['content']=trim($postObj->MediaId);
	        $data['type']="视频";
	    }elseif($type=="subcribe"){
	        $data['content']=trim($postObj->Content);
	        $data['type']="关注事件";
	    }else{
	        $data['content']=trim($postObj->MediaId);
	        $data['type']="其他";
	    }
	    M("message_log")->add($data);
	}
	//处理自定义菜单目录
	private function menuBuildMenuSet($menu) {
		$set = array();
		$set['button'] = array();
		foreach($menu as $m) {
			$entry = array();
			$entry['name'] = urlencode($m['title']);
			if(!empty($m['subMenus'])) {
				$entry['sub_button'] = array();
				foreach($m['subMenus'] as $s) {
					$e = array();
					if ($s['type'] == 'url') {
						$e['type'] = 'view';
					} elseif (in_array($s['type'], $this->types)) {
						$e['type'] = $s['type'];
					} else {
						$e['type'] = 'click';
					}
					$e['name'] = urlencode($s['title']);
					if($e['type'] == 'view') {
						$e['url'] = urlencode($s['url']);
					} else {
						$e['key'] = urlencode($s['forward']);
					}
					$entry['sub_button'][] = $e;
				}
			} else {
				if ($m['type'] == 'url') {
					$entry['type'] = 'view';
				} elseif (in_array($m['type'], $this->types)) {
					$entry['type'] = $m['type'];
				} else {
					$entry['type'] = 'click';
				}
				if($entry['type'] == 'view') {
					$entry['url'] = urlencode($m['url']);
				} else {
					$entry['key'] = urlencode($m['forward']);
				}
			}
			$set['button'][] = $entry;
		}
		$dat = json_encode($set);
		$dat = urldecode($dat);
		return $dat;
	}
}
?>