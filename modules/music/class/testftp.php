<pre>
<?php
	
	$ftp_server = "nhaccuateen.info";
	$ftp_user = "nhaccuat";
	$ftp_passwd = "m2yP4#i@{WS_";
	
if ( isset ( $_POST['ok'] ) )
{
	$source_file = $_FILES['file']['tmp_name'];
	$source_file1 = $_FILES['file']['name'];
	/* direct object methods */
	require_once "ftp.class.php";
	$ftp = new FTP();
	if ($ftp->connect($ftp_server)) 
	{
		if ($ftp->login($ftp_user,$ftp_passwd)) 
		{
			//echo "\n".$ftp->sysType() . "\n"; //loai
			//echo $ftp->pwd() . "\n"; //thu muc hien thoi
			//echo date("r",$ftp->mdtm("7juli.txt.gz")) . "\n"; //thoi gian su sau cung
			//echo $ftp->size("7juli.txt.gz")."\n"; //kich thuoc
			//echo $ftp->raw("SYST")."\n"; // gui command toi server
			//$ftp->mkdir("ftp_test"); // tao thu muc
			//$ftp->chmod(777,"ftp_test");
			//$ftp->rename("a/b/","a/ftp__test");
			//$ftp->rename("ftp__test","ftp_test");
			//$ftp->site("CHMOD 777 ftp_test");
			//$ftp->exec("touch ftp_file.txt");
			//$ftp->delete("ftp_file.txt");
			//$ftp->chdir("a/b/"); // doi vi tri hien tai
			//$ftp->cdup(); // di chuyen toi thu muc goc
			//print_r($ftp->nlist());
			//echo "\n";
			//print_r($ftp->rawlist());
			//echo "\n";
			//$ftp->get("Week.exe","Week.exe");
			//$ftp->put($source_file1,$source_file);
			//$ftp->delete("logo.gif");
			//$ftp->rmdir("ftp_test");
		} 
		else 
		{
			echo "login failed: ";
			print_r($ftp->error_no);
			print_r($ftp->error_msg);
		}
		$ftp->disconnect();
		print_r($ftp->lastLines);
	} 
	else 
	{
		echo "connection failed: ";
		print_r($ftp->error_no);
		print_r($ftp->error_msg);
	}



}
echo'
<form action="testftp.php" method="post" enctype="multipart/form-data" name="form1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="14%">server name </td>
<td width="75%"><input name="server" type="text" id="server">Write in the format "ftp.servername.com" </td>
<td width="11%">&nbsp;</td>
</tr>
<tr>
<td>user name </td>
<td><input name="username" type="text" id="username"></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>password</td>
<td><input name="password" type="password" id="password"></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>File name </td>
<td><input type="file" name="file"></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>
<input type="submit" name="Submit" value="Upload">
<input type="hidden" value="1" name="ok" />
</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</table>
</form>';	
	
	
?>
</pre>