<pre>
<?php
	
	$ftp_server = "nhaccuateen.info";
	$ftp_user = "nhaccuat";
	$ftp_passwd = "m2yP4#i@{WS_";


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
			//$ftp->rename("ftp_test","ftp__test");
			//$ftp->rename("ftp__test","ftp_test");
			//$ftp->site("CHMOD 777 ftp_test");
			//$ftp->exec("touch ftp_file.txt");
			//$ftp->delete("ftp_file.txt");
			//$ftp->chdir("ftp_test"); // doi vi tri hien tai
			//$ftp->cdup(); // di chuyen toi thu muc goc
			//print_r($ftp->nlist());
			//echo "\n";
			//print_r($ftp->rawlist());
			//echo "\n";
			//$ftp->get("Week.exe","Week.exe");
			//$ftp->put("logo.gif","logo.gif");
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
		//print_r($ftp->lastLines);
	} 
	else 
	{
		echo "connection failed: ";
		print_r($ftp->error_no);
		print_r($ftp->error_msg);
	}
	
?>
</pre>