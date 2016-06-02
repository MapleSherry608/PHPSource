<?php
	class News{
		function news_list($cate_id){
			if(!isset($cate_id) || !$cate_id){
				$datas=$this->order("id desc")->where(array("user_id"=>0))->select();
			} else {
				$datas=$this->order("id desc")->where(array("cate"=>$cate_id,"user_id"=>0))->select();
			}
			return $datas;
		}
		function news_list_u($cate_id){
			if(!isset($cate_id) || !$cate_id){
				$datas=$this->order("id desc")->where(array("user_id <>"=>0))->select();
			} else {
				$datas=$this->order("id desc")->where(array("cate"=>$cate_id,"user_id <>"=>0))->select();
			}
			return $datas;
		}
		function add($post){
			return $this->insert($post);
		}
		function mod($id){
			return $this->where(array("id"=>$id))->update();
		}
		function verify($id){
			return $this->where(array("id"=>$id))->update("verify=1");
		}
		function totals($cate_id){
			if(!isset($cate_id) || !$cate_id){
				$datas=$this->where(array("user_id"=>0))->order("id desc")->total();
			} else {
				$datas=$this->order("id desc")->where(array("cate"=>$cate_id))->total();
			}
			return $datas;
		}
		function totals_u($cate_id){
			if(!isset($cate_id) || !$cate_id){
				$datas=$this->order("id desc")->where(array("user_id <>"=>0))->total();
			} else {
				$datas=$this->order("id desc")->where(array("cate"=>$cate_id,"user_id <>"=>0))->total();
			}
			return $datas;
		}
		
		function filter_img($img){
			return  $this->where(array("thumb"=>$img))->find();
		}
	}