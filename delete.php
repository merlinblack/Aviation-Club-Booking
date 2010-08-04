<?php
	session_start();
	
	require_once( 'functions.php' );
	
	//Check whether the session variable SESS_MEMBER_ID is present or not
	if(!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) == '')) {
		header("location: access-denied.php");
		exit();
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>
<title>Omarama Aviation Club Inc.</title>
<meta http-equiv="content-style-type" content="text/css">
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8">
<link href="style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
</style>
</head><body>
<h1>Delete Booking</h1>
<?php echo logininfo(); ?>
<div align="center">
<table class='box'>
<tr><th>
<table class='heading'><tr>
<th><h2>Delete this booking?</h2></th>
<td><img src='images/minus_32x32.png'></td>
</tr></table>
</th></tr>
<tr><th>
<?php
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
	
	// Retreive previous values.
	//Create query
	$qry  ="SELECT * FROM bookings WHERE ";
	$qry .= " booking_id = '" . $_GET['booking'] . "'";

	$result=mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) 
	{
		$booking = mysql_fetch_assoc($result);
		echo '<table id=\'bookings\'>';
		echo '<tr><th>Start</th><th>End</th><th>Description</th></tr>';
		echo '<tr>';
		echo '<td>' . formatDate( $booking['start_date'] ) . '</td>';
		echo '<td>' . formatDate( $booking['end_date'] ) . '</td>';
		echo '<td>' . $booking['description'] . '</td>';
		echo '</tr>';
		echo '</table>';		
	}
	else 
	{
		die("Query failed");
	}
?>
</th></tr>
<tr><th>
<div align='center'>
<table width='300px'>
<tr>
<th><a href="<?php echo $_SESSION['back_url']; ?>">Cancel</a></th>
<td><a href="delete-exec.php?<?php echo 'booking=' . $_GET['booking'] . '&month=' . $_GET['month']; ?>&year=<?php echo $_GET['year']; ?>">
<b>Delete</b></a></td>
</tr>
</table>
</div>
</th></tr>
</table>
</div>
</body></html>
