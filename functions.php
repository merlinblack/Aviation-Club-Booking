<?php

require_once( 'config.php' );

//Function to sanitize values received from the form. Prevents SQL injection
function clean($str) 
{
	$str = @trim($str);
	if(get_magic_quotes_gpc()) 
	{
		$str = stripslashes($str);
	}
	return mysql_real_escape_string($str);
}

function sendNotification( $message )
{
	$message = wordwrap( $message, 70 );
	
	$addresses = getEmailRecipients();
	
	echo $addresses;
	
	if( $addresses != 'None' )
		mail( $addresses, 'Omarama Aviation Inc - Booking Notification', $message );
}

function getEmailRecipients()
{
	// Assumes an existing DB connection	
		
	//Create query
	$qry="SELECT email FROM members where notify = 1";
	
	$result=mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		$members = mysql_fetch_assoc($result);
		while( $members != null )
		{
			$ret_array[] = $members['email'];
			$members = mysql_fetch_assoc($result);
		}

	}else {
		die("Query failed" . $qry );
	}

	if( isset( $ret_array ) )
	{
		$ret = implode( ', ', $ret_array );
		return $ret;
	}
	else
		return 'None';
}

function calendar( $date = null, $highlightDays = null )
{
         //If no parameter is passed use the current date.
	if($date == null)
		$date = getDate();
     
	$day = $date["mday"];
	$month = $date["mon"];
	$month_name = $date["month"];
	$year = $date["year"];
     
	$this_month = getDate(mktime(0, 0, 0, $month, 1, $year));
	$next_month = getDate(mktime(0, 0, 0, $month + 1, 1, $year));

 	//Find out when this month starts and ends.
	$first_week_day = $this_month["wday"];
	$days_in_this_month = round(($next_month[0] - $this_month[0]) / (60 * 60 * 24));	
	
	// Calendar grid
	$cal	= "<table id='calendar'>\n";
	$cal .= "<tr><td colspan='7'>" . $month_name;
	if( $year != date('Y') )
		$cal .= ' ' . $year;
	$cal .= "</td></tr>\n";
	$cal .= "<tr><td>S</td><td>M</td><td>T</td><td>W</td>";
	$cal .= "<td>T</td><td>F</td><td>S</td></tr>\n";

	$day_count = 1;
	$blanks = true;

	for( $row = 1; $row < 7; $row++ )
	{
		$cal .= "<tr>";	
		for( $day = 1; $day < 8; $day++ )
		{
			if( $day_count == 1 )
			{			
				if( $day > $first_week_day )
					$blanks = false;
			}
			
			if( $day_count > $days_in_this_month )
				$blanks = true;
			
			if( $blanks == true )
				$cal .= "<td>&nbsp;</td>";
			else
			{
				if( $highlightDays != null && isset($highlightDays[mktime( 0, 0, 0, $month, $day_count, $year ) / 86400 ]) )
					$cal .= "<td class='highlight'>" . $day_count++ . "</td>";
				else
					$cal .= "<td>" . $day_count++ . "</td>";
			}
		}
		$cal .= "</tr>\n";
	}	
	
	$cal .= "</table>\n";
	
	return $cal;
}

function getBookings( $startdate = null, $enddate = null )
{
	$date = getdate();
	$year = $date['year'];

	if( $startdate == null )
		$startdate = mktime( 0, 0, 0, 1, 1 );

	if( $enddate == null )
		$enddate = mktime( 0, 0, 0, 12, 31 );

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
	
	//Create query
	$qry="SELECT * FROM bookings b, members m WHERE start_date between '" . date( 'Y-m-d', $startdate ) . "'";
	$qry .= " and '" . date( 'Y-m-d', $enddate ) . "'";
	$qry .= " and b.member_id = m.member_id";
	$qry .= " order by start_date";

	$result=mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		$bookings = mysql_fetch_assoc($result);
		while( $bookings != null )
		{
			$ret_array[] = $bookings;
			$bookings = mysql_fetch_assoc($result);
		}

	}else {
		die("Query failed");
	}

	if( isset( $ret_array ) )
		return $ret_array;
	else
		return 'None';
}

function splitDate( $date )
{
	$year = substr( $date, 0, 4 );
	$month = substr( $date, 5, 2 );
	$day = substr( $date, 8, 2 );

	return mktime( 0, 0, 0, $month, $day, $year );
}

function formatDate( $date )
{
	$year = substr( $date, 0, 4 );
	$month = substr( $date, 5, 2 );
	$day = substr( $date, 8, 2 );
	
	$hour = substr( $date, 11, 2 );
	$minute = substr( $date, 14, 2 );
	
	$timestamp = mktime( $hour, $minute, 0, $month, $day, $year );
	
	return date( 'H:i j F Y', $timestamp );
} 	

function formatDate2( $date )
{
	$year = substr( $date, 0, 4 );
	$month = substr( $date, 5, 2 );
	$day = substr( $date, 8, 2 );
	
	$hour = substr( $date, 11, 2 );
	$minute = substr( $date, 14, 2 );
	
	$timestamp = mktime( $hour, $minute, 0, $month, $day, $year );
	
	return date( 'j F Y', $timestamp );
} 	

function getBookedDays( $bookings = null )
{
	if( $bookings == null )
		$bookings = getBookings();

	if( $bookings == 'None' )
	{
		$booked[0] = false;
		return $booked;
	}
	// 60*60*24 = 86400 - convert from seconds to days.

	foreach( $bookings as $booking )
	{
		$start = splitDate($booking['start_date']) / 86400;
		$end = splitDate($booking['end_date']) / 86400;

		for( $day = $start; $day < $end+1; $day++ )
		{
			$booked[$day] = true;
		}		
	}
	return $booked;
}

function logininfo()
{
	if( isset( $_SESSION["SESS_MEMBER_ID"] ) )
	{
		$info = "<div class='logininfo'><b>Welcome "; 
		$info .= $_SESSION["SESS_FIRST_NAME"];
		$info .= " " . $_SESSION["SESS_LAST_NAME"] . "</b>&nbsp;&nbsp;";
		if( isset($_SESSION["SESS_ADMIN"]) && isset( $_SESSION["SESS_ADMIN"] ) == '1' )
			$info .= " <button type=\"button\" onclick=\"window.location.href='admin.php'\">Administration</button>";
		$info .= " <button type=\"button\" onclick=\"window.location.href='edit-member.php'\">Change my details</button>";
		$info .= " <button type=\"button\" onclick=\"window.location.href='logout.php'\"><b>Logout</b></button></div>";
	}
	else
		$info = "<div class='logininfo'><button type=\"button\" onclick=\"window.location.href='login.php'\"><b>Login</b></button></div>";
		
	return $info;
}

function getNotices()
{
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
	
	//Create query
	$qry="SELECT * FROM notices order by date";

	$result=mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		$has_results = false;
		$bookings = mysql_fetch_assoc($result);
		while( $bookings != null )
		{
			$ret_array[] = $bookings;
			$has_results = true;
			$bookings = mysql_fetch_assoc($result);
		}

	}else {
		die("Query failed");
	}

	if( $has_results )
		return $ret_array;
	else
		return 'None';
}

// Check database for conflicting bookings.

function checkStart( $start )
{	
	// Check start date/time
	$qry  = 'select count(*) from bookings where start_date <= \''; 
	$qry .= date( 'Y-m-d H:i', $start ) . '\' and ';
	$qry .= 'end_date > \'' . date( 'Y-m-d H:i', $start ) . '\'';
	
	$result = @mysql_query($qry);
	$count = mysql_fetch_row($result);
	
	if( $count[0] > 0 ) {
		return true;
	}

	return false;
}
function checkEnd( $end )
{	
	// Check end date/time
	$qry  = 'select count(*) from bookings where start_date < \''; 
	$qry .= date( 'Y-m-d H:i', $end ) . '\' and ';
	$qry .= 'end_date >= \'' . date( 'Y-m-d H:i', $end ) . '\'';
	
	$result = @mysql_query($qry);
	$count = mysql_fetch_row($result);
	if( $count[0] > 0 ) {
		return true;
	}

	return false;
}

function checkSpan( $start, $end )
{	
	// Check that no bookings lie within the start and end dates
	$qry  = 'select count(*) from bookings where start_date > \'';
	$qry .= date( 'Y-m-d H:i', $start ) . '\' and ';
	$qry .= 'end_date  < \'' . date( 'Y-m-d H:i', $end ) . '\'';
	
	$result = @mysql_query($qry);
	$count = mysql_fetch_row($result);
	if( $count[0] > 0 ) {
		return true;
	}
	return false;
}

function getMembers()
{
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
	
	//Create query
	$qry="SELECT * FROM members";
	
	$result=mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		$members = mysql_fetch_assoc($result);
		while( $members != null )
		{
			$ret_array[] = $members;
			$members = mysql_fetch_assoc($result);
		}

	}else {
		die("Query failed");
	}

	if( isset( $ret_array ) )
		return $ret_array;
	else
		return 'None';
}
?>
