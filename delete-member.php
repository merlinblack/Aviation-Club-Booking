<?php
	session_start();
	
	require_once( 'functions.php' );
	
	//Check whether the session variable SESS_ADMIN is present or not and valid	
	
	if(!isset($_SESSION['SESS_ADMIN']) || (trim($_SESSION['SESS_ADMIN']) != '1')) {
		header("location: access-denied.php");
		exit();
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>
<title>Omarama Aviation Club Inc.</title>
<meta http-equiv="content-style-type" content="text/css">
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8">
<link href="style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
</style>
</head><body>
<h1>Delete Member</h1>
<?php echo logininfo(); ?>
<div align="center">
<table class='box'>
<tr><th>
<table class='heading'><tr>
<th><h2>Delete this member?</h2></th>
<td><img src='images/minus_32x32.png'></td></tr>
<tr><th colspan='2'><h3>NOTE: Also deletes all of their bookings!</h3></th>
</tr></table>
</th></tr>
<tr><th>
<?php
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
	
	// Retreive previous values.
	//Create query
	$qry  ="SELECT * FROM members WHERE ";
	$qry .= " member_id = '" . $_GET['member'] . "'";

	$result=mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) 
	{
		$member = mysql_fetch_assoc($result);
		if( $member['admin'] == '0' )
		{
			echo '<table id=\'bookings\' width=\'100%\'>';
			echo '<tr><th>Login</th><th>Name</th></tr>';
			echo '<tr>';
			echo '<td>' . $member['login'] . '</td>';
			echo '<td>' . $member['firstname'] . ' ' . $member['lastname'] . '</td>';
			echo '</tr>';
			echo '</table>';
		}
		else
		{
			echo '<h3>Whoops!  Can not delete this user.  They are marked as an administrator.</h3>';
		}		
	}
	else 
	{
		die("Query failed");
	}
?>
</th></tr>
<tr><th>
<div align='center'>
<table width='300px'>
<tr>
<th><a href="<?php echo $_SESSION['back_url']; ?>">Cancel</a></th>
<td>
<?php
	if( $member['admin'] == '0' ) {
?>
<a href="delete-member-exec.php?<?php echo 'member=' . $_GET['member']; ?>">
<b>Delete</b></a>
<?php
	} else 
	{
		echo '&nbsp;';
	}		
?>
</td>
</tr>
</table>
</div>
</th></tr>
</table>
</div>
</body>
</html>
