<?php
	//Start session
	session_start();
	
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
	
	//Sanitize the POST values
	$login = clean($_POST['login']);
	$password = clean($_POST['password']);
	
	//Input Validations
	if($login == '') {
		$errmsg_arr[] = 'Login ID missing';
		$errflag = true;
	}
	else {
		$_SESSION['SESS_LOGIN'] = $login;
	}
	if($password == '') {
		$errmsg_arr[] = 'Password missing';
		$errflag = true;
	}
	
	//If there are input validations, redirect back to the login form
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		session_write_close();
		header("location: login.php");
		exit();
	}
	else {
		unset( $_SESSION['ERRMSG_ARR'] );
		unset( $_SESSION['SESS_LOGIN'] );
	}
	
	//Create query
	$qry="SELECT * FROM members WHERE (login='$login' OR email='$login') AND passwd='".md5($_POST['password'])."'";
	$result=mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		if(mysql_num_rows($result) == 1) {
			//Login Successful
			session_regenerate_id();
			$member = mysql_fetch_assoc($result);
			$_SESSION['SESS_MEMBER_ID'] = $member['member_id'];
			$_SESSION['SESS_FIRST_NAME'] = $member['firstname'];
			$_SESSION['SESS_LAST_NAME'] = $member['lastname'];
			$_SESSION['SESS_LOGIN'] = $member['login'];
			if( $member['admin'] == '1' )
				$_SESSION['SESS_ADMIN'] = '1';
			session_write_close();
			if( isset( $_SESSION['back_url'] ) )
			{
				header( "location: " . $_SESSION['back_url'] );
				unset( $_SESSION['back_url'] );
			}
			else
			{
				header("location: cal-year.php");
			}
			exit();
		}else {
			//Login failed
			header("location: login-failed.php");
			exit();
		}
	}else {
		die("Query failed");
	}
?>
