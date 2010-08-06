<?php

	session_start();

	// Tell login.php where to go after successful login.
	$_SESSION['back_url'] = 'cal-month.php?month=' . $_GET['month'] . '&year=' . $_GET['year'];

	//Check whether the session variable SESS_MEMBER_ID is present or not
	if(!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) == '')) {
		header("location: login.php");
		exit();
	}	

	require_once("functions.php");
	
	unset( $_SESSION['add_start_hour'] );
	
	// url for add / del pages to come back to on cancel. (or completion)	
	$_SESSION['back_url'] = 'cal-month.php?month=' . $_GET['month'] . '&year=' . $_GET['year'];

	$bookings = getBookings( mktime( 0, 0, 0, $_GET['month'], 1, $_GET['year'] ), mktime( 0, 0, 0, $_GET['month']+1, 1, $_GET['year'] ) );
	$bookedDays = getBookedDays( $bookings );
	
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
<h1>Omarama Aviation Club Inc.</h1>
<h2>Bookings for ZK-EKH</h2>
<?php echo logininfo(); ?>
<table width='100%' >
<tr>
<td width='100px' valign='top'>
<div align='center'><a href='cal-year.php?month=<?php echo $_GET['month'] . '&year=' . $_GET['year']; ?>'"><b>Back to year view</b></a></div>
<?php
	echo calendar( getDate(mktime(0, 0, 0, $_GET['month'], 1, $_GET['year'])), $bookedDays );
?>
<div align='center'><a href='cal-year.php?month=<?php echo $_GET['month'] . '&year=' . $_GET['year']; ?>'"><b>Back to year view</b></a></div>
</td>
<td valign='top'>
<?php
	if( $bookings && $bookings != 'None' )
	{
		echo '<table id=\'bookings\'>';
		echo '<tr><th>Start</th><th>End</th><th width=\'30%\'>Description</th><th>Pilot</th>';
		echo '<th>Phone</th><th>Email</th><th>&nbsp;</th></tr>';
		$alternate=true;
		foreach( $bookings as $booking )
		{
			if( $alternate )
				echo '<tr>';
			else
				echo '<tr class=\'alt\'>';
				
			$alternate = ! $alternate;
				
			echo '<td>' . formatDate($booking['start_date']) . '</td>';
			echo '<td>' . formatDate($booking['end_date']) . '</td>';
			echo '<td>' . $booking['description'] . '</td>';
			echo '<td>' . $booking['firstname'] . ' ' . $booking['lastname'] . '</td>';
			echo '<td>' . $booking['phone'] . '</td>';
			echo '<td><a href=\'mailto:' . $booking['email'] . '\'>'  . $booking['email'] .  '</a></td>';
		
			if( isset($_SESSION['SESS_ADMIN']) || $booking['member_id'] == $_SESSION['SESS_MEMBER_ID'] )
			{
				echo '<td><a href=\'delete.php?booking=' . $booking['booking_id'] . '&month=' . $_GET['month'];
				echo '&year=' . $_GET['year'] . '\'><img src="images/minus_32x32.png" width="16" height="16" alt="Delete Booking" title="Delete Booking">';
				echo '</a></td>';
			}
			else
				echo '<td>&nbsp;</td>';
			
			echo '</tr>';
		}
        echo '<tr><td colspan=\'6\' style=\'text-align:right;\'>';
		echo '<a href=\'add.php?month=' . $_GET['month'] . '&year=' . $_GET['year'] . '\'">';
        echo 'Add booking</a></td><td>';
		echo '<a href=\'add.php?month=' . $_GET['month'] . '&year=' . $_GET['year'] . '\'">';
		echo '<img src="images/plus_32x32.png" width="16" height="16" alt="Add Booking" title="Add Booking">';
		echo '</a>';
		echo '</td></tr></table>';
	}
	else
	{
		echo '<p>&nbsp;</p><div align=\'center\'>';
		echo '<a href=\'add.php?month=' . $_GET['month'] . '&year=' . $_GET['year'] . '\'">';
		echo '<img src="images/plus_32x32.png" width="16" height="16" alt="Add Booking" title="Add Booking">';
		echo '</a>';
		echo '&nbsp;';
		echo '<a href=\'add.php?month=' . $_GET['month'] . '&year=' . $_GET['year'] . '\'">';
		echo 'Add Booking';
		echo '</a>';
		
		echo '</div>';
	}
?>
</td>
</tr>
</table>
</body>
</html>
