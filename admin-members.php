<?php

	session_start();
	
	//Check whether the session variable SESS_ADMIN is present or not and valid
	if(!isset($_SESSION['SESS_ADMIN']) || (trim($_SESSION['SESS_ADMIN']) != '1')) {
		header("location: access-denied.php");
		exit();
	}

	require("functions.php");
	
	$_SESSION['back_url'] = 'admin-members.php';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Omarama Aviation Club Inc.</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
</style>
</head>
<body>
<h1>Omarama Aviation Club Inc.</h1>
<h2>Members Administration</h2>
<?php echo logininfo(); ?>
<table align="center" width="500" class='box'><tr><td>
<table align="center" width="450" id="bookings">
<?php
	$members = getMembers();
	
	echo '<tr><th>Login</th><th>Name</th><th>Admin</th><th title="Email when a booking is made">Notify</th><th>Edit</th><th>Delete</th></tr>';
	
	foreach( $members as $member )
	{
		echo '<tr>';
		echo '<td>' . $member['login'] . '</td>';
		echo '<td>' . $member['firstname'] . ' ' . $member['lastname'] . '</td>';
		if( $member['admin'] == '1' )
			echo '<td><a href="admin-toggle.php?member=' . $member['member_id'] . '"><img src="images/correct_32x32.png" title="Revoke admin rights"></a></td>';
		else
			echo '<td><a href="admin-toggle.php?member=' . $member['member_id'] . '"><img src="images/wrong_32x32.png"  title="Give admin rights"></a></td>';
		if( $member['notify'] == '1' )
			echo '<td><a href="notify-toggle.php?member=' . $member['member_id'] . '"><img src="images/correct_32x32.png" title="Stop emailing bookings"></a></td>';
		else
			echo '<td><a href="notify-toggle.php?member=' . $member['member_id'] . '"><img src="images/wrong_32x32.png"  title="Email notification of bookings"></a></td>';
		echo '<td><a href="edit-member.php?member=' . $member['member_id'] . '"><img src="images/settings_32x32.png" title="Edit member"></a></td>';
		echo '<td><a href="delete-member.php?member=' . $member['member_id'] . '"><img src="images/minus_32x32.png" title="Delete member"></a></td>';
		echo '</tr>';
	}
?>
</table>
<div align='center'>
<table><tr>
<td><a href='add-member.php'>Add member</a></td>
<td><a href='admin.php'>Back</a></td>
</tr></table>
</div>
</td></tr></table>
</body>
</html>
