<?php
	//Start session
	session_start();
	
	//Check whether the session variable SESS_MEMBER_ID is present or not
	if(!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) == '')) {
		header("location: access-denied.php");
		exit();
	}
	
	//Check this user is really allowed to delete this notice
	if( ! isset( $_SESSION['SESS_ADMIN'] ) )
	{
		header("location: access-denied.php");
		exit();
	}

	require_once('functions.php');
	
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
	
	$dead_notice = clean( $_REQUEST['id'] );

	$qry  = 'delete from notices where id = ' . $dead_notice;

	$result = @mysql_query($qry);
	header("location: cal-year.php");
	exit();
?>
