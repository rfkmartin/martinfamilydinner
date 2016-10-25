<?php
function print_header()
{
	echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
	echo '<html><head>';
	echo '<title>Martin Family Dinner</title>';
	echo '<link href="/martinfamilydinner/style.css" rel="stylesheet" type="text/css">';
	echo '<script src="js/jquery.js" type="text/javascript"></script>';
	echo '<script src="js/moment.min.js" type="text/javascript"></script>';
	echo '<script src="js/combodate.js" type="text/javascript"></script>';	
	echo '</head>';
}
function print_footer()
{
	echo '</html>';
}
function set_timezone()
{
	date_default_timezone_set("America/Chicago");
}
function print_sub_menu()
{
	echo '<form action = "" method = "post"><button name="manage">Manage My Family</button> | <button name="rsvp">RSVP</button></form>';
}
function print_logon()
{
	if (empty($_SESSION['user']))
	{
		print_logon_form();
	}
	else
	{
		print_r($_POST);
		echo 'Welcome, <span class="person">'.$_SESSION['family_name'].'</span><br>';
		echo '<form action = "" method = "post"><button name="logout">Logout<button><form>';
	}
}
function print_logon_form()
{
	echo '<form action = "" method = "post">';
	echo 'UserName  :<input type = "text" name = "username"><br>';
    echo 'Password  :<input type = "password" name = "password"><br>';
	//echo 'Welcome, <span class="person">Nobody</span><br>';
	echo '<input type="submit" name="login" value="Log In"><form>';
}
function process_forms()
{
	if (isset($_POST['logout']))
	{
		session_destroy();
		unset($_SESSION['user']);
		unset($_SESSION['family_id']);
		unset($_SESSION['family_name']);
		unset($_SESSION['is_admin']);
		unset($_SESSION['page']);
	}
	if (isset($_POST['login']))
	{
		$_SESSION['user']=1;
		$_SESSION['family_id']=7;
		$_SESSION['family_name']="Martinopoulos";
		$_SESSION['is_admin']=1;
		$_SESSION['page']="";
	}
}
?>