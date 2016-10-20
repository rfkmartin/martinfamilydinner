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
	echo 'Home | My Family | <button>RSVP</button> | Address Book';
}
function print_logon()
{
	$_SESSION['user']=1;
	$_SESSION['family_id']=7;
	$_SESSION['family_name']="Martinopoulos";
	$_SESSION['is_admin']=1;
	$_SESSION['page']="";
	if (empty($_SESSION['user']))
	{
		print_logon_form();
	}
	else
	{
		echo 'Welcome, <span class="person">'.$_SESSION['family_name'].'</span><br>';
		echo '        Logout<br>';
	}
}
function print_logon_form()
{
	echo 'Welcome, <span class="person">Nobody</span><br>';
	echo '        Account<br>';
	echo '        Logout<br>';
}
?>