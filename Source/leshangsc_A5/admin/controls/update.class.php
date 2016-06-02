<?php
	class update{
		
		private $url="http://www.leesuntech.com/update/";
		function index(){
			$this->assign("version",VERSION);
			$this->display("main/update");
		}
		
		function check(){
			ini_set('max_execution_time','300');
			$data=file_get_contents($this->url."update.txt");
			$datas=explode(":",$data);
			if(trim($datas[1])!=VERSION){
				
				
				$update_order=file($this->url."update_order.txt");	
				$update_dir=$this->get_update_dir($update_order);
				//获取升级文件
				$update_files=file($this->url.$update_dir."/files.txt");
				
				foreach($update_files as $v){
					$filename=explode("|",$v);
					
					$dir=dirname($filename[0]);
					$source=trim($this->url.$update_dir."/".$filename[0]);
					$destiny=trim(dirname(dirname(__FILE__))."/../../".$dir."/".$filename[1]);
					if(!@copy($source,$destiny)){
						$this->error("复制文件出错，升级失败!",2);
					}
				}
				
				$current='define("VERSION","'.VERSION.'");';
				
				$update=D("Update");
				
			
				//读取SQL文件
				$sqlArr=file($this->url.$update_dir."/update_sql.txt");
				foreach($sqlArr as $var){
					if($var!=''){
						$sqlTxt=str_replace("\r", "\n", str_replace('ls_',TABPREFIX, $var));
						$result=$update->update_database(trim($sqlTxt));
					
					}
					if(!$result){
						$this->error("更新SQL文件错误!",200);
					}
				}
				//读取配置文件
				$configArr=file(dirname(dirname(__FILE__))."/../../config.inc.php");
				$configTxt="";
				foreach($configArr as $val){
					if(trim($val)==$current){
						$val='define("VERSION","'.trim($datas[1]).'");';
					}
					$configTxt.=trim($val)."\n";
				}
				
				if(file_put_contents(dirname(dirname(__FILE__))."/../../config.inc.php",$configTxt)){
					$this->success("已更新为".trim($datas[1])."版!",2);
				} else {
					$this->error("更新配置文件错误!",2);
				}
			} else{
				$this->success("当前为最新版本",2);
			}
		}
		
		//获取当前版本的下一版本号（版本号也是文件夹名）
		function get_update_dir($update_order){
			foreach($update_order as $k=>$v){
				if(trim($v)==VERSION){
					return  trim($update_order[$k+1]);
				}
			}
		}
		
	}