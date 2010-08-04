<?php

	session_start();
	
	//Check whether the session variable SESS_ADMIN is present or not and valid
	if(!isset($_SESSION['SESS_ADMIN']) || (trim($_SESSION['SESS_ADMIN']) != '1')) {
		header("location: access-denied.php");
		exit();
	}

	require("functions.php");
	
	//Connect to mysql server
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}
	
	//Select database
	$db = mysql_select_db(DB_DATABASE);
	if(!$db) {
		die("Unable to select database");
	}
	
	$member = clean( $_REQUEST['member'] );
	
	if( $member != $_SESSION['SESS_MEMBER_ID'] )
	{
		$sql = "update members set admin = not(admin) where member_id=" . $member;
		
		$result=@mysql_query( $sql );		
		
		//Check whether the query was successful or not
		if($result) 
		{
			header( 'location: ' . $_SESSION['back_url'] );
			exit();
		}
		else
		{
			die("Query failed: " . $sql );		
		}	
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Omarama Aviation Club Inc.</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
</style>
</head>
<body>
<h1>Omarama Aviation Club Inc.</h1>
<h2>Members Administration</h2>
<?php echo logininfo(); ?>
<table align="center" width="500" class='box'>
<tr><th><h2 class='err'>You can not revoke your own admin rights.</h2></th></tr>
<tr><th><h3>This is to prevent the last admin from being deleted.</h3></th></tr>
<tr><th><a href='<?php echo $_SESSION['back_url']; ?>'>Back</a></th></tr>
</table>
</body>
</html>
