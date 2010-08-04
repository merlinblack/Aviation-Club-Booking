<?php
	//Start session
	session_start();
	
	//Check whether the session variable SESS_ADMIN is present or not and valid	
	
	if(!isset($_SESSION['SESS_ADMIN']) || (trim($_SESSION['SESS_ADMIN']) != '1')) {
		header("location: access-denied.php");
		exit();
	}
	
	require_once('functions.php');
	
	//Array to store validation errors
	$errmsg_arr = array();
	
	//Validation error flag
	$errflag = false;
	
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
	
	//Delete members bookings.
	$qry =  'delete from bookings where member_id = ' . $member;
				
	$result = @mysql_query($qry);
	
	//Check whether the query was successful or not
	if( ! $result)
	{ 
		die("Query failed");
	}	
	
	//Delete members bookings.
	$qry =  'delete from members where member_id = ' . $member;
				
	$result = @mysql_query($qry);	
	
	//Check whether the query was successful or not
	if($result) 
	{
		header("location: " . $_SESSION['back_url']);
		unset($_SESSION['back_url']);
		exit();
	}
	else 
	{
		die("Query failed");
	}
?>
