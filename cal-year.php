<?php

	session_start();

	$_SESSION['back_url'] = 'cal-year.php';

	require_once("functions.php");
	
	define('NUM_MONTHS', '4');	
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>
<title>Omarama Aviation Club Inc.</title>
<meta http-equiv="content-style-type" content="text/css">
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8">
<link href="style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
#calendar td {
	padding: 5px;
	text-align: center;
}
td {
	padding: 5px;
	text-align: center;
}
.box th {
	background-color: #EEEEEE;
}
.box td, .box th {
	text-align: left;
	padding: 5px 15px 5px 15px;
}
.box tr {
    background-color: #F4FCDD;
}
.box tr.alt
{
	background-color:#E5EDCE;
}
.box {
	border: 1px solid black;	
}
pre {
	font: 11px Verdana, Arial, Helvetica, sans-serif;
	color: #666666;
	margin: 0px;
}

</style>
</head><body>
<h1>Omarama Aviation Club Inc.</h1>
<h2>Bookings for ZK-EKH</h2>
<h3>Click on the month in which you wish to place a booking</h3>
<?php echo logininfo(); ?>
<table width='850px' border='0'>
<?php
	if( isset( $_GET['month'] ) )
		$month = $_GET['month'];
	else
		$month = date('m');

	if( isset( $_GET['year'] ) )
		$year = $_GET['year'];
	else
		$year = date('Y');

	$back_month = $month - 1;
	if( $back_month < 1 )
	{
		$back_month += 12;
		$back_year = $year - 1;
	}
	else
		$back_year = $year;
		
	$forward_month = $month + 1;
	if( $forward_month > 12 )
	{
		$forward_month -= 12;
		$forward_year = $year + 1;
	}
	else
		$forward_year = $year;

	$back_url = 'cal-year.php?month=' . $back_month . '&year=' . $back_year;
	$forward_url = 'cal-year.php?month=' . $forward_month . '&year=' . $forward_year;
	
	$end_month = $month + NUM_MONTHS;
	$end_year = date('Y');
	if( $end_month > 12 )
	{
		$end_month -= 12;
		$end_year++;
	}
	$bookings = getBookings( mktime( 0, 0, 0, $month, 1, $year ), mktime( 0, 0, 0, $end_month, 1, $end_year ) - (86400) );
	$bookedDays = getBookedDays( $bookings );
			
	echo '<tr>';
	for ($i = 1; $i <= NUM_MONTHS; $i++) {
		echo '<td>';
		echo '<button type="button" onclick="window.location.href=\'cal-month.php?month=';
		echo $month . '&year=' . $year . '\'">';
		echo calendar( getDate(mktime(0, 0, 0, $month,   1, $year )), $bookedDays );
		echo '</button></td>';
		$month++;
		if( $month == 13 )
		{
			$month = 1;
			$year++;
		}
	}
	
	echo '</tr>';
?>
<tr>
<td>
<table><tr>
<td><a href='<?php echo $back_url; ?>'><img src="images/arrow-left_32x32.png" width="16" height="16"></a></td>
<td><a href='<?php echo $back_url; ?>'>Back</a></td>
</tr></table>
</td>
<td>&nbsp;</td><td>&nbsp;</td>
<td>
<table align='right'><tr>
<td><a href='<?php echo $forward_url; ?>'>Forward</a></td>
<td><a href='<?php echo $back_url; ?>'><img src="images/arrow-right_32x32.png" width="16" height="16"></a></td>
</tr></table>
</td>
</tr>
</table>
<h2>Notices</h2>
<?php
    if( ! isset( $_SESSION['SESS_MEMBER_ID'] )  )
    {
        echo "<h3><i>Login to see notices</i></h3>";
    }
    else
    {
?>
<table class='box' width='850px'>
<tr><th width='15%'>Date</th><th>Notice</th><th>&nbsp;</th></tr>
<?php
    $notices = getNotices();
    //$notices = 'None';

    if( $notices == 'None' )
    {
        echo '<tr><td>&nbsp;</td><td><i>No notices at this time</i></td><td>&nbsp;</td></tr>';
    }
    else
    {
		$alternate=true;	
		foreach( $notices as $notice )
		{
			if( $alternate )
				echo '<tr>';
			else
				echo '<tr class=\'alt\'>';
				
			$alternate = ! $alternate;
				
            echo '<td valign=\'top\'>' . formatDate2($notice['date']) . '</td>';
				echo '<td><pre>' . $notice['notice'] . '</pre></td>';
            if( isset( $_SESSION['SESS_ADMIN'] ) )
            {
                echo '<td>';
                echo '<a href=\'delete-notice-exec.php?id=' . $notice['id'] . '\'><img src="images/minus_32x32.png" title="Delete Notice"></a>';
                echo '</td>';
            }
            else
            {
                echo '<td>&nbsp;</td>';
            }
			echo '</tr>';
		}
    }

    echo "</table>";

    }
    if( isset( $_SESSION['SESS_ADMIN'] ) )
    {
?>
    <form method='post' action='add-notice-exec.php'>
    <table>
        <tr>
            <td>
                <textarea class='textarea' id='notice' name='notice' 
                	value='New Notice' cols='90' rows='3'
                	onfocus="this.innerHTML='';"
                	>New Notice</textarea>
            </td>
            <td>
                <input name='submit' type='submit' value='Submit'></input>
            </td>
        </tr>
    </table>
    </form>
<?php
    }
?>
</body>
</html>
