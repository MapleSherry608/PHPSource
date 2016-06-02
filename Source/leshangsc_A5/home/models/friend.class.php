<?php
	class Friend{
		function lists($id){
			return $this->where(array("friend_id"=>$id,"verify"=>0))->select();
		}
		function friends($id){
			$v1=$this->where(array("friend_id"=>$id,"verify"=>1))->select();
			$v2=$this->where(array("user_id"=>$id,"verify"=>1))->select();
			if($v1){
				return $v1;
			} 
			if($v2){
				return $v2;
			}
		}
		function message_num($uid){
			return $this->where(array("friend_id"=>$uid,"verify"=>0))->total();
		}
		function friend_num($id){
			$v1=$this->where(array("friend_id"=>$id,"verify"=>1))->total();
			$v2=$this->where(array("user_id"=>$id,"verify"=>1))->total();
			if($v1){
				return $v1;
			} 
			if($v2){
				return $v2;
			}
		}
		function add(){
			return $this->insert();
		}
		function verify($id){
			return $this->where(array("id"=>$id))->update("verify=1");
		}
		function check($f_id,$u_id,$verify=0){
			if(!$verify){//查找是否已发送过好友请求
				return $this->where(array("friend_id"=>$f_id,"user_id"=>$u_id))->total();
			} else {//查找是否已是好友
				$v1=$this->where(array("friend_id"=>$f_id,"user_id"=>$u_id,"verify"=>1))->total();
				$v2=$this->where(array("friend_id"=>$u_id,"user_id"=>$f_id,"verify"=>1))->total();
				if($v1 || $v2){
					return true;
				} else {
					return false;
				}
			}
		}
	}