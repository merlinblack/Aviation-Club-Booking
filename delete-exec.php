<?php
	//Start session
	session_start();
	
	//Check whether the session variable SESS_MEMBER_ID is present or not
	if(!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) == '')) {
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
	
	$dead_booking = clean( $_REQUEST['booking'] );
	
	//Check this user is really allowed to delete this booking
	
	if( ! isset( $_SESSION['SESS_ADMIN'] ) )
	{
		$qry  = 'select member_id from bookings where booking_id = ' . $dead_booking;

		$result = @mysql_query($qry);
		$test_id = mysql_fetch_row($result);
		if( $test_id[0] != $_SESSION['SESS_MEMBER_ID'] ) 
		{
			header("location: access-denied.php");
			exit();
		}
	}
	
	//Delete booking.
	$qry =  'delete from bookings where booking_id = ' . $dead_booking;
	echo $qry . '</br>';
				
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
