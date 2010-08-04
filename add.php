<?php
	session_start();
	
	require_once('functions.php');
	
	require_once('calendar/classes/tc_calendar.php');

	//Check whether the session variable SESS_MEMBER_ID is present or not
	if(!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) == '')) {
		header("location: access-denied.php");
		exit();
	}
	
	if( ! isset( $_SESSION['add_start_hour'] ) )
	{
		$_SESSION['add_start_date'] = '1-' . $_GET['month'] . '-' . $_GET['year'];
		$_SESSION['add_start_hour'] = '0';
		$_SESSION['add_start_minute'] = '00';
		$_SESSION['add_end_date'] = '1-' . $_GET['month'] . '-' . $_GET['year'];
		$_SESSION['add_end_hour'] = '0';
		$_SESSION['add_end_minute'] = '00';
		$_SESSION['add_description'] = '';
		$_SESSION['form_back_url'] = 'add.php?month=' . $_GET['month'] . '&year=' . $_GET['year'];
	}

	$bookings = getBookings( mktime( 0, 0, 0, $_GET['month'], 1, $_GET['year'] ), mktime( 0, 0, 0, $_GET['month']+1, 1, $_GET['year'] ) - (86400) );
	$bookedDays = getBookedDays( $bookings );
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>
<title>Omarama Aviation Club Inc.</title>
<meta http-equiv="content-style-type" content="text/css">
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8">
<link href="style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="calendar/calendar.js"></script>
<script language="javascript" src="ajax.js"></script>
<script language="javascript" src="add.js"></script>
</head><body onload='init();'>
<h1>Add New Booking</h1>
<?php echo logininfo(); ?>
<div align='center'>
<table>
<tr>
<td width='400px'>
<?php
	echo calendar( getDate(mktime(0, 0, 0, $_GET['month'], 1, $_GET['year'])), $bookedDays );
?>
</td>
<td>
<form action="add-exec.php" onsubmit="return validate_form(this)" method="post">
<table width='400px' border='0'>
<tr><th>
<table class='box'>
<tr><th colspan='2'>
<h2>Add new booking</h2>
</th><td><img src='images/plus_32x32.png'></td></tr>
<tr><td colspan='3'><div class='err' id='error'></div></td></tr>
<?php
if( isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) >0 ) {
		echo '<tr><td colspan=\'3\'>';		
		echo '<ul class="err">';
		foreach($_SESSION['ERRMSG_ARR'] as $msg) {
			echo '<li>',$msg,'</li>'; 
		}
		echo '</ul>';
		unset($_SESSION['ERRMSG_ARR']);
		echo '</td></tr>';
	}
?>
<tr><th>Start</th>
<td><?php
$myCalendar = new tc_calendar("start_date",true,false);
$myCalendar->setIcon("calendar/images/iconCalendar.gif");
$myCalendar->setDate(date('d', strtotime($_SESSION['add_start_date']))
           , date('m', strtotime($_SESSION['add_start_date']))
           , date('Y', strtotime($_SESSION['add_start_date'])));
$myCalendar->setPath("calendar");
$myCalendar->writeScript();
echo '&nbsp;&nbsp;&nbsp;<input type=\'text\' name=\'start_hour\' id=\'start_hour\'';
echo 'size=\'1\' title=\'Hour 0 - 23\' value=\'' . $_SESSION['add_start_hour'] . '\'/>';
echo '&nbsp;<b>:</b>&nbsp;';
echo '&nbsp;<input type=\'text\' name=\'start_minute\' id=\'start_minute\'';
echo 'size=\'1\' title=\'Minutes 0 - 59\' value=\'' . $_SESSION['add_start_minute'] . '\'/>';
?></td><td><div id='icon_start_date' align='center'></div></td>
</tr>
<tr>
<th>Finish</th>
<td><?php
$myCalendar = new tc_calendar("end_date",true,false);
$myCalendar->setIcon("calendar/images/iconCalendar.gif");
$myCalendar->setDate(date('d', strtotime($_SESSION['add_end_date']))
           , date('m', strtotime($_SESSION['add_end_date']))
           , date('Y', strtotime($_SESSION['add_end_date'])));
$myCalendar->setPath("calendar");
$myCalendar->writeScript();
echo '&nbsp;&nbsp;&nbsp;<input type=\'text\' name=\'end_hour\' id=\'end_hour\'';
echo 'size=\'1\' title=\'Hour 0 - 23\' value=\'' . $_SESSION['add_end_hour'] . '\'/>';
echo '&nbsp;<b>:</b>&nbsp;';
echo '&nbsp;<input type=\'text\' name=\'end_minute\' id=\'end_minute\'';
echo 'size=\'1\' title=\'Minutes 0 - 59\' value=\'' . $_SESSION['add_end_minute'] . '\'/>';
?></td><td><div id='icon_end_date' align='center'></div></td>
</tr>
<tr><th>Description</th>
<td><textarea name="description" rows="4" cols="40" class='textfield' 
		title='Destination, intentions, etc' onblur='description_onblur( this );'><?php echo $_SESSION['add_description']; ?>
</textarea></td><td><div id='icon_description' align='center'></div></td>
</tr>
<tr><td colspan='2'><input name='submit' type='submit' value='Submit'></td><td>&nbsp;</td></tr>
</table>
</form>
<div align='center'><a href='<?php echo $_SESSION['back_url']; ?>'>
<b>Cancel</b></a></div>
</td>
</tr>
</div>
</table>
</body>
</html>
