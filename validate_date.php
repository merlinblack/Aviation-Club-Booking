<?php
// start PHP session
session_start();

require_once( 'functions.php' );

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

function Validate( $timestamp, $field )
{
	//sleep(5);

	if( $field == 'start_date' )
	{
		if( checkStart( $timestamp ) )
			return 'Start date and time falls within another booking';
	}
	
	if( $field == 'end_date' )
	{
		if( checkEnd( $timestamp ) )
			return 'End date and time falls within another booking';
	}	
	
	return 'valid';
}

// AJAX validation is performed by the ValidateAJAX method. The results
// are used to form an XML document that is sent back to the client
$response =
        '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
        '<response>' .
        '<result>' .
        Validate($_REQUEST['inputValue'], $_REQUEST['fieldID']) .
        '</result>' .
        '<fieldid>' .
        $_REQUEST['fieldID'] .
        '</fieldid>' .
        '</response>';

// generate the response
if(ob_get_length()) 
	ob_clean();
	    
header('Content-Type: text/xml');
echo $response;

?>
