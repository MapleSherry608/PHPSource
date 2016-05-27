<?php
	class Survey{
		function survey_result(){
			if(!$this->is_cached("public/survey_result")){
				$survey=D("Survey");
				$id=intval($_GET['id']);
				$res=$survey->load($id);
				$result=unserialize(htmlspecialchars_decode($res['result']));
				foreach($result as $k=>$v){
					$total+=$v;
				}
				foreach($result as $k=>$v){
					$percent[$k]=floor($v/$total*100);
				}
				$this->assign("percent",$percent);
			}
			$this->display("public/survey_result");
		}
		function mod_result(){
			if(!$_POST['item']){
				$this->error("请选择投票", 1);	
			}
			$IPaddress=get_ip();
			if($_COOKIE["IPadr"]!=$IPaddress){
				setcookie("IPadr",$IPaddress,time()+60*60);
				$id=intval($_POST['id']);
				$survey=D("Survey");
				$res=$survey->load($id);
				if(!$res['result']){
					for($i=1;$i<=5;$i++){
						$result["item".$i]=0;
					}
					$result["item".$_POST['item']]=1;
				} else {
					$result=unserialize(htmlspecialchars_decode($res['result']));
					$result["item".$_POST['item']]+=1;
				}
				$result=serialize($result);
				if(false !== $survey->mod($id,$result)){
					$this->success("投票成功!", 1);
				} else {
					$this->error($user->getMsg(), 1);
				}
			} else {
				$this->error("您已投过票", 1);
			}
		}
	}