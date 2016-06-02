<?php
	class Group{
		function add(){
			return $this->insert();
		}
		function main_list(){
			return $this->where(array("pid"=>0))->order("sort asc")->select();
		}
		function sub_list(){
			return $this->where(array("pid <>"=>0))->order("sort asc")->select();
		}
		function mod($id){
			return $this->where(array("id"=>$id))->update();
		}
		function set_default($id){
			$cancel=$this->where(array("is_default"=>1))->update("is_default=0");
			return $this->where(array("id"=>$id))->update("is_default=1");
		}
		function load_default(){
			$data=$this->where(array("is_default"=>1))->select();
			return $data[0];
		}
		function has_sub($id){
			return $this->where(array("pid"=>$id))->total();
		}
		function totals(){
			return $this->where(array("pid"=>0))->total();
		}
		function score_range($score){
			$datas=$this->select();
			foreach($datas as $k=>$v){
				$sub=$this->where(array("pid"=>$v['id']))->find();
				if($sub){
					unset($datas[$k]);
				}
			}
			
			$temp=array();
			foreach($datas as $k=>$v){
				
				$val=$v['score']-$score;
				if($val>0){
					$temp[$k]['id']=$v['id'];
					$temp[$k]['val']=$val;
				}
			}
			
			$data=$this->arraySort($temp, 'val', 'asc');
			$data=array_shift($data);
			
			return $data;
		}
		
		 function arraySort($arr, $keys, $type = 'asc') {
			$keysvalue = $new_array = array();
			foreach ($arr as $k => $v){
				$keysvalue[$k] = $v[$keys];
			}
			$type == 'asc' ? asort($keysvalue) : arsort($keysvalue);
			reset($keysvalue);
			foreach ($keysvalue as $k => $v) {
			   $new_array[$k] = $arr[$k];
			}
			return $new_array;
		}

	}