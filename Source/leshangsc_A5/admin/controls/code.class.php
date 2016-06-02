<?php
	class Code{
		function index(){
			if(!$this->is_cached("main/code_list",$_SERVER['REQUEST_URI'])){
				$code=D("Code");
				$config=D("Config");
				$config_data=$config->config_list();
				$current_dir=isset($_GET['id'])?trim($_GET['id']):"tpl";
				$page=new Page($code->totals($config_data[0]['template'],$current_dir), PAGENUM);
				$datas=$code->limit($page->limit)->html_list($config_data[0]['template'],$current_dir);
				$dir=$code->html_dir();
				$this->assign("dir",$dir);
				$this->assign("res_dir_list",$code->res_dir());
				$this->assign("current_dir",$current_dir);
				$this->assign("datas",$datas);
				$this->assign("fpage", $page->fpage(0));
			}
			$this->display("main/code_list",$_SERVER['REQUEST_URI']);
		}
	
		function mod_index(){
			if(!$this->is_cached("main/code_mod",$_SERVER['REQUEST_URI'])){
				$config=D("Config");
				$config_data=$config->config_list();
				
				
				$file=trim($_GET['file']);
				$dir=trim($_GET['dir']);
				$current_dir=trim($_GET['current_dir']);
				if($current_dir=="tpl"){
					$filename=dirname(dirname(__FILE__))."/../../"."home/views/".$config_data[0]['template']."/".$dir."/".$file;
				} else {
					$filename=dirname(dirname(__FILE__))."/../../"."home/views/".$config_data[0]['template']."/resource/".$dir."/".$file;
				}
				
				
				
				if (substr($filename,-4)!=".tpl" && substr($filename,-4)!=".css" && substr($filename,-3)!=".js") {
					$this->error("打开文件出错,只能打开tpl,css,js文件!",2);
				}
				
				if(!$handle = @fopen($filename, 'rb')){
					$this->error("打开文件出错!",2);
				}
				$tpl['content'] = fread($handle, filesize($filename));
				$tpl['content'] = htmlentities($tpl['content'], ENT_QUOTES, "UTF-8");
				$tpl['name']=$file;
				$tpl['dir']=$dir;
				$tpl['current_dir']=$current_dir;
				fclose($handle);
				
				$this->assign('tpl',$tpl);
			}
			$this->display("main/code_mod",$_SERVER['REQUEST_URI']);
		}
		
		function mod(){
			$file=trim($_POST['name']);
			$dir=trim($_POST['dir']);
			$tpl_content =$_POST['tpl_content'];
			$current_dir=trim($_POST['current_dir']);
			$config=D("Config");
			$config_data=$config->config_list();
			if($current_dir=="tpl"){
				$filename=dirname(dirname(__FILE__))."/../../"."home/views/".$config_data[0]['template']."/".$dir."/".$file;
			} else {
				$filename=dirname(dirname(__FILE__))."/../../"."home/views/".$config_data[0]['template']."/resource/".$dir."/".$file;
			}
			if(!$handle = @fopen($filename, 'wb')){
				$this->error("打开目标模版文件失败，请检查模版目录的权限",1);
			}
			if(fwrite($handle, $tpl_content) === false){
				$this->error('写入目标 $file 失败,请检查读写权限',1);
			}
			fclose($handle);
			$this->success("编辑成功!", 1);
		}
	}