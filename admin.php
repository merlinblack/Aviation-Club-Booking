<?php

	session_start();
	
	//Check whether the session variable SESS_ADMIN is present or not and valid
	if(!isset($_SESSION['SESS_ADMIN']) || (trim($_SESSION['SESS_ADMIN']) != '1')) {
		header("location: access-denied.php");
		exit();
	}

	require("functions.php");
	
	$_SESSION['back_url'] = 'admin.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>
<title>Omarama Aviation Club Inc.</title>
<meta http-equiv="content-style-type" content="text/css">
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8">
<link href="style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
</style>
</head>
<body>
<h1>Omarama Aviation Club Inc.</h1>
<h2>Bookings for ZK-EKH - Administration</h2>
<?php echo logininfo(); ?>
<table align="center" width="350px" class='box' border='0'>
<tr><th>Add new member</th><td><button type="button" onclick="window.location.href='add-member.php'">--&gt;</button></td></tr>
<tr><th>Administer members</th><td><button type="button" onclick="window.location.href='admin-members.php'">--&gt;</button></td></tr>
<tr><th>Download bookings</th><td><button type="button" onclick="window.location.href='download_bookings.php'">--&gt;</button></td></tr>
<tr><th><i>TODO</i> Delete historical bookings</th><td><button type="button">--&gt;</button></td></tr>
<tr><th>&nbsp;</th><td>&nbsp;</td></tr>
<tr><th>Back to main page</th><td><button type="button" onclick="window.location.href='cal-year.php'" >--&gt;</button></td></tr>
</table>
</body>
</html>
