<?php
	define('flag', false);
	if(file_exists("./install_lock.txt")){
		$mess=urldecode("已安装，如重新安装，请删除install_lock.txt");
		echo "<script>window.location.href='error.php?mess=".$mess."'</script>";
		exit;	
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>安装向导-乐尚商城</title>
<link href="css/global.css" rel="stylesheet" type="text/css" />
<link href="css/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.pngfix.js"></script>
<script type='text/javascript'>
function mess(m){
    alert(m);
}
$(document).ready(function() {
    $("form").submit(
		function(){
			$("#config").hide();
			$("#ing").show();
		}
	);
	$("#is_default").click(function(){
		if($(this).attr("checked")){
			$("#db_list").show(100);
		} else {
			$("#db_list").hide(100);
		}
	})
});
</script>
<?php
	require("../classes/simfile.class.php");
	$filelist=Simfile::getSubFile("../backup/");
	$filelist=str_replace("/","\\",$filelist);
	if(isset($_POST['submit'])){
		$dbhost=trim($_POST['dbhost']);
		$dbuser=trim($_POST['dbuser']);
		$dbname=trim($_POST['dbname']);
		$dbpass=trim($_POST['dbpass']);
		$dbpre=trim($_POST['dbpre']);
		if(!isset($_POST['is_default'])){
			 $_POST['db_list']="default_db.sql";
		} 
		if($dbhost == '' || $dbname == ''|| $dbuser== ''|| $dbpre == ''|| $_POST['admin_password'] == '' || $_POST['confirm_pass'] == '' ){
			$m="参数不完整，请填写完整";
			echo "<script>mess('{$m}');</script>";
		} elseif($_POST['admin_password']!=$_POST['confirm_pass']){
			$m="两次密码不一致";
			echo "<script>mess('{$m}');</script>";
		}/*elseif (mysql_get_server_info()<5.0) {
			$m="请选择MYSQL5以上版本";
			echo "<script>mess('{$m}');</script>";
		}*/elseif(!$db = @mysql_connect($dbhost, $dbuser, $dbpass)){
			$m="连接数据库错误，请核对信息是否正确";
			echo "<script>mess('{$m}');</script>";
		}else{
			if(change_config()){
				$bd=build_database($_POST);
				if($bd['status']){
					file_put_contents("./install_lock.txt", "CMS INSTALL OK ...");
					echo "<script>window.location.href='success.php'</script>";
				} else {
					echo "<script>window.location.href='error.php?mess=".$bd['mess']."'</script>";
				}
			} else {
				$mess=urlencode("写入配置文件错误");
				echo "<script>window.location.href='error.php?mess=".$mess."'</script>";
			}
		}
		
		
	}
	
	function change_config(){
			$configArray=array("HOST"=>trim($_POST['dbhost']),
							   "USER"=>trim($_POST['dbuser']),
							   "PASS"=>trim($_POST['dbpass']),
							   "DBNAME"=>trim($_POST['dbname']),
							   "TABPREFIX"=>trim($_POST['dbpre'])
							   );
			$filename="../config.inc.php";
			$configText = file_get_contents($filename);
			foreach($configArray as $key => $val) {
				$pattern[]='/define\(\"'.$key.'\",\s*.+\);/';
				$repContent[]='define("'.$key.'", "'.$val.'");';
			}
			$configText = preg_replace($pattern, $repContent, $configText);
			return file_put_contents($filename, $configText);
	}
	function build_database($var){
			if(!$db = @mysql_connect($var['dbhost'], $var['dbuser'], $var['dbpass'])){
				$res['mess']=urlencode("连接数据库错误，请核对信息是否正确");
				$res['status']=false;
				return $res;
			}
			mysql_query('create database if not exists '.$var['dbname']." DEFAULT CHARACTER SET UTF8",$db);
			if(!mysql_select_db($var['dbname'])){
				$res['mess']=urlencode("选择数据库错误");
				$res['status']=false;
				return $res;
			}
			if(!$sql_file=file_get_contents($var['db_list'])){
				$res['mess']=urlencode("打开文件".$var['db_list']."失败");
				$res['status']=false;
				return $res;
			}
			
			$sql_file=str_replace("\r", "\n", str_replace('ls_', trim($var['dbpre']), $sql_file));
			$sql_file = preg_replace('/^\s*(?:--|#).*/m', '', $sql_file);
			$sql_file = preg_replace('/^\s*\/\*.*?\*\//ms', '', $sql_file);
			foreach(explode(";\n", trim($sql_file)) as $query) {
				if($query!=''){
					$result=mysql_query($query);
				}
				if(!$result){
					$res['mess']=urlencode("执行数据结构{$query}语句失败<br>错误代码为:".mysql_error());
					$res['status']=false;
					return $res;
				} 
				
			}
			$time=time();
			$admin_sql="INSERT INTO ".trim($var['dbpre'])."admin VALUES ('1','admin','".md5($var['admin_password'])."','".time()."','::1','1','2');";
			if(mysql_query($admin_sql)){
				$res['status']=true;
				return $res;
			} else {
				$res['mess']=urlencode("插入管理员帐户失败");
				$res['status']=false;
				return $res;
			}
	}
?>

</head>

<body>
<div id="layout">
	<div class="top_line">
    	<div class="logo"></div>
        <div class="title">乐尚商城安装向导<br />www.leesuntech.com</div>
    </div>
    <form enctype="multipart/form-data" action="<?php $PHP_SELF ?>" method="post">
    <div class="body_line" id="config">
    	<div class="title">参数设置</div>
		<div class="database">
        
       	  <table width="80%" border="0" cellspacing="0" cellpadding="3" align="center">
              <tr>
                <td width="33%" align="right">数据库主机：</td>
                <td width="67%" colspan="2"><input name="dbhost" type="text" id="dbhost"  class="step_text" value="localhost"/></td>
              </tr>
              <tr>
                <td align="right">数据库用户名：</td>
                <td colspan="2"><input name="dbuser" type="text" id="dbuser"  class="step_text" /></td>
              </tr>
              <tr>
                <td align="right">数据库密码：</td>
                <td colspan="2"><input name="dbpass" type="text" id="dbpass"  class="step_text" /></td>
              </tr>
              <tr>
                <td align="right">数据库名称：</td>
                <td><input name="dbname" type="text" id="dbname"  class="step_text" /></td>
				<td width="200"><?php if($filelist) {?><input type="checkbox" id="is_default" name="is_default" checked>用已备份数据<?php } ?></td>
              </tr>
              <tr>
                <td align="right">数据表前缀：</td>
                <td><input type="text" name="dbpre"  id="pre" value="ls_"  class="step_text" /></td>
				<td>
				<?php if($filelist) {?>
					<select name="db_list" id="db_list" >
						<?php foreach($filelist as $k=>$v){ ?>
						<option value="<?php echo $v;?>"><?php echo basename($v);?></option>
						<?php } ?>
					</select>
				<?php } ?>
			    </td>
              </tr>
			  
            </table>
         
      </div>
      <div class="admin">
      	<table width="80%" border="0" cellspacing="0" cellpadding="3" align="center">
              <tr>
                <td width="33%" align="right">管理员账号：</td>
                <td width="67%"><input name="adm_name" type="text" id="adm_name" value="admin" class="step_text" /></td>
              </tr>
              <tr>
                <td align="right">登录密码：</td>
                <td><input name="admin_password" type="password" id="admin_password"  class="step_text" /></td>
              </tr>
              <tr>
                <td align="right">密码确认：</td>
                <td><input name="confirm_pass" type="password" id="confirm_pass"  class="step_text" /></td>
              </tr>
            </table>
      </div>
    </div>
    <div class="body_line" id="ing" style="display:none">
    	<div class="title">正在安装</div>
		<div class="process">
        	请稍等，安装正在运行...<br /><img src="images/loading.gif" />
      	</div>
    </div>
    
    <div class="bottom_line">
    	<input type="button" value="上一步" class="button" onclick="window.location.href='index.php'"/><input type="submit" value="下一步" id="next_step"  class="button" name="submit"/>
    </div></form>
    
    
</div>
</body>
</html>
