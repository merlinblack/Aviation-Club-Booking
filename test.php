<?php
	//Start session
	session_start();
	
	//Check whether the session variable SESS_MEMBER_ID is present or not
	if(!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) == '')) {
		header("location: access-denied.php");
		exit();
	}
	
	//Include database connection details
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
	
	
//	for( $hour=0; $hour < 24; $hour++ )
//	{
//		echo $hour . '</br>';		
//		$qry  = 'insert into bookings ( start_date, end_date, member_id, description )';
//		$qry .= ' values( \'2010-3-5 '. $hour . ':00\', \'2010-3-5 ' . ($hour+1) . ':00\',1,\'Test!!\' )';
//		echo $qry . '</br>';
//		$result = @mysql_query($qry);
//		echo $result . '</br>';
//	}

	echo 'Sending:</br>';
	
	$message = "Test message\n" .
				  "Mr Wiggles has booked ZK-EKH\n" .
				  "\n".
				  "From: 10-Sep-2010 9:00\n" .
				  "To:   10-Sep-2010 17:00\n";
				  
	echo $message . "</br>";
				  
	sendNotification( $message );

    echo "<h1>Sent</h1>";
?>
