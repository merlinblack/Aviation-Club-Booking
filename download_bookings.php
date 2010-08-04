<?php
	session_start();
	
	//Check whether the session variable SESS_ADMIN is present or not and valid
	if(!isset($_SESSION['SESS_ADMIN']) || (trim($_SESSION['SESS_ADMIN']) != '1')) {
		header("location: access-denied.php");
		exit();
	}

	require("functions.php");
	
	// Generate a CSV file of all bookings in the database.
	
	header( 'Content-Type: text/csv' );
	header( 'Content-Disposition: attachment; filename="bookings.csv"' );
	header( 'Cache-Control: no-cache, must-revalidate'); 
	header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT');
	
	$bookings = getBookings();
	
	if( $bookings == 'None' )
	{
		ob_clear();
		echo '<h2>Error there are no bookings to download!</h2>';
	}
	
	$tempname = tempnam(sys_get_temp_dir(), "csv");	
	$fp = fopen( $tempname, "w" );	
	
	fputcsv( $fp, array( "Start", "End", "Description", "Pilot", "Phone", "Email" ) );	
	
	foreach( $bookings as $booking )
	{
		$selectedFields = array( formatDate($booking['start_date']),
										 formatDate($booking['end_date']),
										 $booking['description'],
										 $booking['firstname'] . ' ' . $booking['lastname'],
										 $booking['phone'],
										 $booking['email'] );
										 
		fputcsv( $fp, $selectedFields );
	}
	
	fclose( $fp );
	
	readfile( $tempname );
	unlink( $tempname );
?>