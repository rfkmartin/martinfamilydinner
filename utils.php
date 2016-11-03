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
   echo '<form action = "" method = "post">';
   if (!empty($_SESSION['user']))
   {
      echo '<button name="myfamily">Manage My Family</button>';
      echo ' | <button name="families">Family Roster</button>';
      echo ' | <button name="rsvp">RSVP</button>';
      echo ' | <button name="next">Next Event</button>';
      echo ' | <button name="upcoming">Upcoming Events</button>';
      echo ' | <button name="myevent">Manage Your Event</button>';
      if ($_SESSION['is_admin']==1)
      {
         echo '<br>';
         echo '<button name="admin">Admin menu 1</button>';
         echo ' | <button name="admin">Admin menu 2</button>';
      }
   }
   echo '</form>&nbsp;';
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
	echo '<table><tr><td align="left">Username:</td><td><input type = "text" name = "username"></td></tr>';
    echo '<tr><td align="left">Password:</td><td><input type = "password" name = "password"></td></tr></table>';
	//echo 'Welcome, <span class="person">Nobody</span><br>';
	echo '<input type="submit" name="login" value="Log In"><form>';
}
function process_forms($link)
{
	if (isset($_POST['logout']))
	{
		session_destroy();
		unset($_SESSION['user']);
		unset($_SESSION['family_id']);
		unset($_SESSION['family_name']);
		unset($_SESSION['is_admin']);
		unset($_SESSION['page']);
		unset($_SESSION['foods']);
	}
	if (isset($_POST['login']))
	{
		$myusername = mysqli_real_escape_string($link,$_POST['username']);
		$mypassword = mysqli_real_escape_string($link,$_POST['password']);
		$sql = "select user_id,u.family_id,name,is_admin,passcode from user u join family f on u.family_id=f.family_id where username='".$myusername."'";
		$result = mysqli_query($link,$sql);
		list($user,$family_id,$family_name,$is_admin,$hashed) = mysqli_fetch_row($result);

		if(password_verify($mypassword,$hashed)) {
			$_SESSION['user']=$user;
			$_SESSION['family_id']=$family_id;
			$_SESSION['family_name']=$family_name;
			$_SESSION['is_admin']=$is_admin;
			$_SESSION['page']="";
			$sql = "select e.event_id from event e join date d on d.date_id=e.date_id where day!=-1 and str_to_date(concat(concat(month,'/',day),'/',year),'%m/%d/%Y')>curdate() order by year, month limit 1";
			$result = mysqli_query($link,$sql);
			if (!empty($result))
			{
				list($event_id) = mysqli_fetch_row($result);
				$_SESSION['event_id']=$event_id;
			}
			else
			{
				$_SESSION['event_id']=-1;
			}
			$sql = "select count(*) from food";
			$result = mysqli_query($link,$sql);
         list($foods) = mysqli_fetch_row($result);
			$_SESSION['foods']=$foods;
		}
		else
		{
			$error = "Your Login Name or Password is invalid";
		}
	}
	if (isset($_POST['rsvp']))
	{
		$_SESSION['page']='RSVP';
	}
	if (isset($_POST['myfamily']))
	{
		$_SESSION['page']='managefam';
	}
	if (isset($_POST['upcoming']))
	{
		$_SESSION['page']='upcoming';
	}
	if (isset($_POST['next']))
	{
		$_SESSION['page']='next';
	}
	if (isset($_POST['families']))
	{
		$_SESSION['page']='families';
	}
	if (isset($_POST['myevent']))
	{
		$_SESSION['page']='manageev';
	}
}
?>