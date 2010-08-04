<?php
	require_once( 'config.php' );

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
	
	$qry  = "INSERT INTO members(firstname, lastname, email, phone, login, passwd, admin, notify) ";
	$qry .= "VALUES('Icarus','Vikare','info@oac.org.nz','__','websiteadmin','";
	$qry .= md5('password')."', 1, 1)";
	$result = @mysql_query($qry);				
	
	//Check whether the query was successful or not
	if( ! $result)
	{
		die("Query failed " . $qry );
	}
	echo '<h1>Admin user created</h1>';
?>
