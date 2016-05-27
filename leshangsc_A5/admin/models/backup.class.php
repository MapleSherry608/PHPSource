<?php
	class Backup{
		
		function backup_list(){
			$datas=array();
			$filelist=Simfile::getSubFile(PROJECT_PATH."backup/");
			foreach($filelist as $key=>$var){
				$datas[$key]['file']=basename($var);
				$datas[$key]['size']=tosize(filesize($var));
				$datas[$key]['time']=date("Y-m-d H:i:s",filemtime($var));
			}
			return $datas;
		}
		
		function backup_database(){
			$add_time = date("Y-m-d H:i:s");
			$sql="";
			$sql.="--Version:".VERSION."\r\n".
				  "--Mysql Ver:".$this->dbVersion()."\r\n".
				  "--Create time:".$add_time."\r\n";
			$tabname=$this->getTablesName();
			foreach($tabname as $var){
				$sql.= "DROP TABLE IF EXISTS `".$var."`;\r\n";
				$row_struct=$this->query("show create table {$var}","select"); 
				$sql.= $row_struct[0]['Create Table'].";\r\n";
				$row_data=$this->getTablesData($var);
				if(!count($row_data)){
					return false;
				} else {
					foreach((array)$row_data[1] as $var_data){
						$sql_data= "INSERT INTO `{$var}` VALUES (";
						foreach($var_data as $row){
							$sql_data.="'".addslashes($row)."',";
						}
						$sql_data=substr($sql_data,0,-1);  //删除最后一个逗号
						$sql_data.= ");\r\n";
						$sql.=$sql_data;
					}
				}
			}
			$target_sql=PROJECT_PATH."backup/".time().".sql"; 
			return Simfile::write($target_sql,$sql);
			
		}
		
		public function restore($file){
			$filename=PROJECT_PATH."backup/".$file;
			$handle=@fopen($filename,"rb");
			$head=@fread($handle,70);
			fclose($handle);
			$arr=$this->getSqlInfo($head);
			
			if($arr['cms_ver']!=VERSION){
				$data['info']="版本不统一，不能还原!";
				$data['res']=false;
				return $data;
			}
			
			$sql=$this->removeComments(file_get_contents($filename));
			$sql = trim($sql);
			$sql = str_replace("\r", '', $sql);
       		$segmentSql = explode(";\n", $sql);
			foreach($segmentSql as $var){
				if($var!=''){
					$result=$this->query(trim($var));
					
				}
				if(!$result){
					$data['info']=$var;
					$data['res']=false;
					return $data;
				}
			}
			
			
			$data['info']="还原数据成功!";
			$data['res']=true;
			return $data;
		}
		
		private function getSqlInfo($head){
			$file_info = array('cms_ver'=>'', 'mysql_ver'=> '', 'add_time'=>'');
			$head=str_replace("--","",$head);
			$arr = explode("\n", $head);
			
			foreach($arr as $var){
				$temp = explode(":", $var);
				switch($temp[0]){
					case 'Version':
						$file_info['cms_ver']=trim($temp[1]);
					break;
					case 'Mysql Ver':
						$file_info['mysql_ver']=trim($temp[1]);
					break;
					case 'Create time':
						$file_info['add_time']=trim($temp[1]);
					break;
				}
			}
			return $file_info;
		}
		
		private function removeComments($sql){
			/* 删除SQL行注释，行注释不匹配换行符 */
			$sql = preg_replace('/^\s*(?:--|#).*/m', '', $sql);
	
			/* 删除SQL块注释，匹配换行符，且为非贪婪匹配 */
			//$sql = preg_replace('/^\s*\/\*(?:.|\n)*\*\//m', '', $sql);
			$sql = preg_replace('/^\s*\/\*.*?\*\//ms', '', $sql);
	
			return $sql;
   		 }
		
		
		private function getTablesName(){ 
			$temp=array();
         	$result=$this->query("show table status","select"); 
			if(!$result){
				return false;
			} else {
				foreach($result as $var){
					$temp[]=$var["Name"]; 
				}
				return $temp; 
			}
        } 
		
		private function getTablesData($tabName){
			$allData=array();
			$sql="select * from {$tabName}";
			$result=$this->query($sql,"select");
			if(!$result){
				return false;
			}
			$num=$this->query($sql,"total");
			array_push($allData,$num,$result);
			return $allData;
		}
		
		function del(){
			$filename=trim($_GET['file']);
			$path=PROJECT_PATH."backup/".$filename;
			return Simfile::delete($path);
		}
		function dels($id){
			$num=0;
			$n=count($id);
			foreach($id as $v){
				$filename=trim($v);
				$path=PROJECT_PATH."backup/".$filename;
				if(Simfile::delete($path)){
					$num++;
				}
			}
			if($n==$num){
				return true;
			}
			return false;
		}
		function totals(){
			return count(Simfile::getSubFile(PROJECT_PATH."backup/"));
		}
	}