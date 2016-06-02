<?php
	class News{
		function load($id){
			return $this->where(array("id"=>$id))->find();
		}
		function list_u($cate_id){
			if(!isset($cate_id) || !$cate_id){
				$datas=$this->order("id desc")->where(array("user_id"=>$_SESSION['user']['id']))->select();
			} else {
				$datas=$this->order("id desc")->where(array("cate"=>$cate_id,"user_id"=>$_SESSION['user']['id']))->select();
			}
			return $datas;
		}
		function news_list($cate_id=0,$is_main=0,$order="sort asc"){
			if($is_main){
				return $this->where(array("cate"=>$cate_id,"verify"=>1))->order($order)->select();
			} else {
				if($cate_id){
					return $this->where(array("cate"=>$cate_id,"verify"=>1))->order($order)->select();
				} else {
					return $this->where(array("verify"=>1))->order($order)->select();
				}
			}
		}
		function news_num($uid){
			return $this->where(array("user_id"=>$uid,"verify"=>0))->total();
		}
		function add(){
			return $this->insert();
		}
		function mod($id){
			return $this->where(array("id"=>$id))->update();
		}
		function article($id){
			return $this->where(array("id"=>$id))->select();
		}
		function add_recommand($id){
			return $this->where(array("id"=>$id))->update("recommand=recommand+1");
		}
		function del_recommand($id){
			return $this->where(array("id"=>$id))->update("recommand=recommand-1");
		}
		function search_list($keywords){
			return $this->where(array("title"=>"%'{$keywords}'%"))->select();
		}
		function search_total($keywords){
			return $this->where(array("title"=>"%'{$keywords}'%"))->total();
		}
		function totals_u($cate_id){
			if(!isset($cate_id) || !$cate_id){
				$datas=$this->order("id desc")->where(array("user_id"=>$_SESSION['user']['id']))->total();
			} else {
				$datas=$this->order("id desc")->where(array("cate"=>$cate_id,"user_id"=>$_SESSION['user']['id']))->total();
			}
			return $datas;
		}
		function totals($cate_id,$is_main){
			if($is_main){
				return $this->where(array("cate"=>$cate_id,"verify"=>1))->total();
			} else {
				if($cate_id){
					return $this->where(array("cate"=>$cate_id,"verify"=>1))->total();
				} else {
					return $this->where(array("verify"=>1))->total();
				}
			}
		}
	}