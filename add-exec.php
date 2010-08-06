<?php
//Start session
session_start();

//Check whether the session variable SESS_MEMBER_ID is present or not
if(!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) == '')) {
    header("location: access-denied.php");
    exit();
}

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

$start_date = clean($_POST['start_date']);
$start_hour = clean($_POST['start_hour']);
$start_minute = clean($_POST['start_minute']);

$end_date = clean($_POST['end_date']);
$end_hour = clean($_POST['end_hour']);
$end_minute = clean($_POST['end_minute']);

$description = clean($_POST['description']);

//Input Validations
if($start_hour == '') {
    $errmsg_arr[] = 'Start hour missing';
    $errflag = true;
}
if($start_minute == '') {
    $errmsg_arr[] = 'Start minute missing';
    $errflag = true;
}
if($end_hour == '') {
    $errmsg_arr[] = 'Finish hour missing';
    $errflag = true;
}
if($end_minute == '') {
    $errmsg_arr[] = 'End minute missing';
    $errflag = true;
}
if($description == '' ) {
    $errmsg_arr[] = 'Description/intentions missing';
    $errflag = true;
}

$start = $start_date . ' ' . $start_hour . ':' . $start_minute;
$end = $end_date . ' ' . $end_hour . ':' . $end_minute;

$start = strtotime( $start );
$end = strtotime( $end );

if( $start > $end ) {
    $errmsg_arr[] = 'Start must be before finish';
    $errflag = true;
}

// Check database for conflicting bookings.
// Check start date/time
if( checkStart( $start ) ) {
    $errmsg_arr[] = 'Start date and time falls within another booking';
    $errflag = true;
}

// Check end date/time
if( checkEnd( $end ) ) {
    $errmsg_arr[] = 'Finish date and time falls within another booking';
    $errflag = true;
}

// Check that no bookings lie within the start and end dates
if( checkSpan( $start, $end ) ) {
    $errmsg_arr[] = 'Another booking lies between your selected date and times';
    $errflag = true;
}

//If there are input validations, redirect back to the registration form
if($errflag) {
    $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    $_SESSION['add_start_date'] = $start_date;
    $_SESSION['add_start_hour'] = $start_hour;
    $_SESSION['add_start_minute'] = $start_minute;
    $_SESSION['add_end_date'] = $end_date;
    $_SESSION['add_end_hour'] = $end_hour;
    $_SESSION['add_end_minute'] = $end_minute;
    $description = str_replace( array( "\\r\\n", "\\r" ), "\n", $description );
    $_SESSION['add_description'] = $description;
    session_write_close();
    header("location: " . $_SESSION['form_back_url']);
    exit();
}

unset($_SESSION['ERRMSG_ARR']);
unset($_SESSION['add_start_date']);
unset($_SESSION['add_start_hour']);
unset($_SESSION['add_start_minute']);
unset($_SESSION['add_end_date']);
unset($_SESSION['add_end_hour']);
unset($_SESSION['add_end_minute']);
unset($_SESSION['add_description']);
unset($_SESSION['form_back_url']);

//Insert new booking.
$qry = 'insert into bookings( start_date, end_date, description, member_id )';
$qry .= 'values ( \'' . date( 'Y-m-d H:i', $start ) . '\', \'';
$qry .= date( 'Y-m-d H:i', $end ) . '\', \''. $description . '\',';
$qry .= $_SESSION['SESS_MEMBER_ID'] . ')';

$result = @mysql_query($qry);

//Check whether the query was successful or not
if($result) 
{
    // Email notification to members with notify='1'
    $description = str_replace( array( "\\r\\n", "\\r" ), "\n", $description );
    $message = $_SESSION['SESS_FIRST_NAME'] . " " . $_SESSION['SESS_LAST_NAME'] . " has booked ZK-EKH\n" .
        "Start: " . Date( 'H:i j F Y', $start ) . "\n" .
        "End:   " . Date( 'H:i j F Y', $end ) . "\n" .
        "Description / Intentions:\n" . $description . "\n\n\n" . 
        "www.omaramaaviation.com\n" .
        "secretary@omaramaaviation.com\n";

    sendNotification( $message );

    header("location: " . $_SESSION['back_url']);
    unset($_SESSION['back_url']);

    exit();
}
else 
{
    die("Query failed" . $qry );
}

?>
