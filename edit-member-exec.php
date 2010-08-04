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
	$fname = clean($_POST['fname']);
	$lname = clean($_POST['lname']);
	$email = clean($_POST['email']);
	$phone = clean($_POST['phone']);
	$password = clean($_POST['password']);
	$cpassword = clean($_POST['cpassword']);
	$memberID = clean($_POST['member']);
	
	//Input Validations
	if($fname == '') {
		$errmsg_arr[] = 'First name missing';
		$errflag = true;
	}
	if($lname == '') {
		$errmsg_arr[] = 'Last name missing';
		$errflag = true;
	}
	if($email == '') {
		$errmsg_arr[] = 'Email address missing';
		$errflag = true;
	}
	if($phone == '') {
		$errmsg_arr[] = 'Contact phone missing';
		$errflag = true;
	}
	if($password == '' && $cpassword != '' ) {
		$errmsg_arr[] = 'Password missing';
		$errflag = true;
	}
	if($cpassword == '' && $password != '' ) {
		$errmsg_arr[] = 'Confirm password missing';
		$errflag = true;
	}
	if( strcmp($password, $cpassword) != 0 ) {
		$errmsg_arr[] = 'Passwords do not match';
		$errflag = true;
	}
	
	//If there are input validations, redirect back to the registration form
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		session_write_close();
		header("location: edit-member.php");
		exit();
	}

	//Create UPDATE query
	if( $password != '' ) {
		$qry = "update members set firstname='$fname', lastname='$lname', email='$email', phone='$phone', passwd='";
		$qry .= md5($_POST['password']) . "' where member_id='" . $memberID ."'";
	}
	else {
		$qry = "update members set firstname='$fname', lastname='$lname', email='$email', phone='$phone' ";
		$qry .= " where member_id='" . $memberID ."'";
	}		
	$result = @mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		if( $memberID == $_SESSION['SESS_MEMBER_ID'] )
		{
			$_SESSION['SESS_FIRST_NAME'] = $fname;
			$_SESSION['SESS_LAST_NAME'] = $lname;
		}
		if( isset( $_SESSION['back_url'] ) )
		{
			header("location: " . $_SESSION['back_url']);
			unset($_SESSION['back_url']);
		}
		else
			header("location: cal-year.php");
			
		exit();
	}else {
		die("Query failed");
	}
?>
