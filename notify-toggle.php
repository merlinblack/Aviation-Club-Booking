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

$sql = "update members set notify = not(notify) where member_id=" . $member;

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
?>
