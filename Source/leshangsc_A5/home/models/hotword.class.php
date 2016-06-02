<?php
	class Hotword{
		function lists(){
			return $this->order("times desc")->select();
		}
		function add($post){
			return $this->insert($post);
		}
		function mod($keyword){
			return $this->where(array("keyword"=>$keyword))->update("times=times+1");
		}
		function is_exist($keyword){
			$data=$this->where(array("keyword"=>$keyword))->find();
			if($data){
				return true;
			} else {
				return false;
			}
		}
		
		function addkeyword($keyword,$type){
			$keyword=trim($keyword);
			if($this->is_exist($keyword)){
				$this->mod($keyword);
			} else {
				$post['keyword']=$keyword;
				$post['times']=0;
				$post['type']=$type;
				$this->add($post);
			}
		}
	}