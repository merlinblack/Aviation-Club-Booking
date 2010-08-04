<?php
	session_start();
	
	//Check whether the session variable SESS_ADMIN is present or not and valid
	if(!isset($_SESSION['SESS_ADMIN']) || (trim($_SESSION['SESS_ADMIN']) != '1')) {
		header("location: access-denied.php");
		exit();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Add New Member</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include( 'header.php' ); ?>
<?php
	if( isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) >0 ) {
		echo '<table align="center" width="300"><tr><td>';		
		echo '<ul class="err">';
		foreach($_SESSION['ERRMSG_ARR'] as $msg) {
			echo '<li>',$msg,'</li>'; 
		}
		echo '</ul>';
		unset($_SESSION['ERRMSG_ARR']);
		echo '</td></tr></table>';
	}
?>
<form id="loginForm" name="loginForm" method="post" action="add-member-exec.php">
  <table class='box' width="300px" align="center" cellpadding="2" cellspacing="0">
    <tr>
      <th width="124">New Login ID</th>
      <td width="168"><input name="login" type="text" class="textfield" id="login" /></td>
    </tr>
    <tr>
      <th width="124">First Name</th>
      <td width="168"><input name="fname" type="text" class="textfield" id="fname" /></td>
    </tr>
    <tr>
      <th width="124">Last Name</th>
      <td width="168"><input name="lname" type="text" class="textfield" id="lname" /></td>
    </tr>
    <tr>
      <th width="124">Email Address</th>
      <td width="168"><input name="email" type="text" class="textfield" id="email" /></td>
    </tr>
    <tr>
      <th width="124">Contact Phone</th>
      <td width="168"><input name="phone" type="text" class="textfield" id="phone" /></td>
    </tr>
    <tr>
      <th>Temp Password</th>
      <td><input name="password" type="password" class="textfield" id="password" /></td>
    </tr>
    <tr>
      <th>Confirm Temp Password </th>
      <td><input name="cpassword" type="password" class="textfield" id="cpassword" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="Add Member" /></td>
    </tr>
  </table>
  <div align='center'><a href='<?php echo $_SESSION['back_url']; ?>'>Back</a></div>
</form>
</body>
</html>
