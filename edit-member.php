<?php
	session_start();
	
	//Check whether the session variable SESS_MEMBER_ID is present or not
	if(!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) == '')) {
		header("location: access-denied.php");
		exit();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Edit Member Details</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
</style>
</head>
<body>
<?php include( 'header.php' ); ?>
<?php
	if( isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) >0 ) {
		echo '<table align="center" width="300"><tr><td>';		
		echo '<ul class="err">';
		foreach($_SESSION['ERRMSG_ARR'] as $msg) {
			echo '<li>',$msg,'</li>'; 
		}
		echo '</ul>';
		unset($_SESSION['ERRMSG_ARR']);
		echo '</td></tr></table>';
	}
	
	//Include database connection details
	require_once('config.php');
	
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
	
	if( ! isset( $_GET['member'] ) )
		$memberID = $_SESSION['SESS_MEMBER_ID'];
	else
		$memberID = $_GET['member'];	
	
	// Retreive previous values.
	//Create query
	$qry="SELECT * FROM members WHERE member_id='" . $memberID . "'";
	$result=mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		$member = mysql_fetch_assoc($result);
		$fname = $member['firstname'];
		$lname = $member['lastname'];
		$email = $member['email'];
		$phone = $member['phone'];
		$login = $member['login'];
	}else {
		die("Query failed");
	}
?>
<table class='box' width='400px' align='center'>
<tr>
<th><h3>Edit Member Details: <?php echo $login; ?></h3></th></tr>
<tr><td>
<form id="loginForm" name="loginForm" method="post" action="edit-member-exec.php">
  <input name="member" type="hidden" id="member" value="<?php echo $memberID; ?>">
  <table width="300" border="0" align="center" cellpadding="2" cellspacing="0">
    <tr>
      <th>First Name</th>
      <td><input name="fname" type="text" class="textfield" id="fname" value="<?php echo $fname; ?>" /></td>
    </tr>
    <tr>
      <th>Last Name</th>
      <td><input name="lname" type="text" class="textfield" id="lname" value="<?php echo $lname; ?>" /></td>
    </tr>
    <tr>
    <tr>
      <th>Email Address</th>
      <td><input name="email" type="text" class="textfield" id="email" value="<?php echo $email; ?>" /></td>
    </tr>
    <tr>
    <tr>
      <th>Contact phone</th>
      <td><input name="phone" type="text" class="textfield" id="phone" value="<?php echo $phone; ?>" /></td>
    </tr>
    <tr>
    <tr>
      <th>Password</th>
      <td><input name="password" type="password" class="textfield" id="password" /></td>
    </tr>
    <tr>
      <th>Confirm Password </th>
      <td><input name="cpassword" type="password" class="textfield" id="cpassword" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="Save" /></td>
    </tr>
  </table>
  <p align="center" class="err">Leave password blank if you do not want to change it.</p>
</form>
</td></tr>
</table>
</body>
</html>
