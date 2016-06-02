<?php
	class User{
		function load_user($id){
			return $this->where(array("id"=>$id))->select();
		}
		function add(){
			return $this->insert($_POST,1,1);
		}
		function mod($id){
			return $this->where(array("id"=>$id))->update($_POST,1,1);
		}
		function load_pub_info($id){
			return $this->field("id")->where(array("id"=>$id))
						->r_select(
							array("news","*","user_id",array("news_info","create_time desc")),
							array("comment","*","user_id",array("comment_info"))
			);
		}
		function load_pub_pro($id){
			return $this->field("id")->where(array("id"=>$id))
						->r_select(
							array("product","*","user_id",array("pro_info","add_time desc"))
							
			);
		}
		
	}