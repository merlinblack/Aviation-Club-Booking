<?php

	session_start();

		//Unset the variables stored in session
	unset($_SESSION['SESS_MEMBER_ID']);
	unset($_SESSION['SESS_FIRST_NAME']);
	unset($_SESSION['SESS_LAST_NAME']);
	unset($_SESSION['SESS_LOGIN']);
	unset($_SESSION['SESS_ADMIN']);
	
	header( 'location: cal-year.php' );
	exit();
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>
<title>Omarama Aviation Club Inc.</title>
<meta http-equiv="content-style-type" content="text/css">
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8">
<link href="style.css" rel="stylesheet" type="text/css" />
<body>
<div align="center">
<h1>Logged out!</h1>
<p><a href="cal-year.php">Return to year calendar</a></p>
<p><a href="login.php">Login again</a></p>
</div>
</body>
</html>
