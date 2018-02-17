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
      echo ' | <button name="selectevent">Pick Upcoming Schedule</button>';
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
		echo 'Welcome, <span class="person">'.$_SESSION['family_name'].'</span><br>';
		echo '<form action = "" method = "post"><button name="account">My Account</button><br>';
		echo '<button name="logout">Logout</button><form>';
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
function update_account($link)
{
   echo '<h3><font color="red">'.$_SESSION['error'].'</font>'.$_SESSION['message'].'</h3>';
	echo '<h2>Update Username</h2>';
	echo '<form action = "" method = "post">';
	echo '<table border="0"><tr>';
	echo '<td width="175px"><b>New Username</b></td>';
	echo '<td width="175px"><input type="text" name="username" size="45" value="'.$_SESSION['username'].'"></td>';
	echo '</tr></table>';
	echo '<input type="submit" name="updateusername" value="Update">';
	echo '<h2>Update Password</h2>';
	echo '<form action = "" method = "post">';
	echo '<table border="0"><tr>';
	echo '<td width="200px"><b>Old Password</b></td>';
	echo '<td width="200px"><input type="text" name="orig_pwd" size="45"></td></tr>';
	echo '<tr><td width="200px"><b>New Password</b></td>';
	echo '<td width="200px"><input type="text" name="new_pwd" size="45"></td></tr>';
	echo '<tr><td width="200px"><b>New Password Again</b></td>';
	echo '<td width="200px"><input type="text" name="new_pwd1" size="45"></td></tr>';
	echo '</tr></table>';
	echo '<input type="submit" name="updatepassword" value="Update">';
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
		logger($link,$sql);
		$result = mysqli_query($link,$sql);
		list($user,$family_id,$family_name,$is_admin,$hashed) = mysqli_fetch_row($result);

		if(password_verify($mypassword,$hashed)) {
			$_SESSION['username']=$_POST['username'];
			$_SESSION['user']=$user;
			$_SESSION['family_id']=$family_id;
			$_SESSION['family_name']=$family_name;
			$_SESSION['is_admin']=$is_admin;
			$_SESSION['page']="";
			$_SESSION['error']="";
			$_SESSION['message']="";
				$sql = "select e.event_id from event e join date d on d.date_id=e.date_id where day!=-1 and str_to_date(concat(concat(month,'/',day),'/',year),'%m/%d/%Y')>=curdate() order by year, month limit 1";
			logger($link,$sql);
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
			logger($link,$sql);
			$result = mysqli_query($link,$sql);
         list($foods) = mysqli_fetch_row($result);
			$_SESSION['foods']=$foods;
		}
		else
		{
			$error = "Your Login Name or Password is invalid";
		}
	}
	if (isset($_POST['addpersonevent']))
    {
       $personArray = [];
       if (isset($_POST['persons']))
       {
          $personArray = $_POST['persons'];
       }
       $j=0;
       $sql = 'select person_id from person p join family f on p.family_id=f.family_id where f.family_id='.$_SESSION['family_id'];
       logger($link,$sql);
       $data = mysqli_query($link,$sql);
       while (list($person_id)=mysqli_fetch_row($data))
       {
          if ($j<count($personArray)&&$personArray[$j]==$person_id)
          {
             $sql = "update attending set coming=1 where event_id=".$_SESSION['event_id']." and person_id=".$person_id;
             logger($link,$sql);
             if (!mysqli_query($link,$sql))
             {
                logger($link,"Error inserting record: " . mysqli_error($link));
             }
             $j++;
          }
          else
          {
             $sql = "update attending set coming=0 where event_id=".$_SESSION['event_id']." and person_id=".$person_id;
             logger($link,$sql);
             if (!mysqli_query($link,$sql))
             {
                logger($link,"Error inserting record: " . mysqli_error($link));
             }
          }
       }
    }
    if (isset($_POST['updateusername']))
    {
       $_SESSION['error']='';
       $_SESSION['message']='';
        // check for duplicate username
       $sql = 'select * from user u where u.username="'.$_POST['username'].'"';
       logger($link,$sql);
       $data = mysqli_query($link,$sql);
       if (mysqli_num_rows($data)>0)
       {
          $_SESSION['error']='Username already taken';
          return;
       }
       // change username
       $sql = 'update user set username="'.$_POST['username'].'" where user_id='.$_SESSION['user'];
       logger($link,$sql);
       $data = mysqli_query($link,$sql);
       if (!mysqli_query($link,$sql))
       {
          logger($link,"Error updating record: " . mysqli_error($link));
       }
       $_SESSION['message']='Username successfully changed';
       $_SESSION['username']=$_POST['username'];
    }
    if (isset($_POST['updatepassword']))
    {
       $_SESSION['error']='';
       $_SESSION['message']='';
        // check for matching password old
       $sql = "select passcode from user where username='".$_SESSION['username']."'";
       logger($link,$sql);
       $result = mysqli_query($link,$sql);
       list($hashed) = mysqli_fetch_row($result);
       
       if(!password_verify(mysqli_real_escape_string($link,$_POST['orig_pwd']),$hashed))
       {
          $_SESSION['error']='Old password does not match';
          return;
       }
       if ($_POST['new_pwd']=="")
       {
          $_SESSION['error']='New password cannot be blank';
          return;
       }
       // check for matching new passwords
       if (mysqli_real_escape_string($link,$_POST['new_pwd']) != mysqli_real_escape_string($link,$_POST['new_pwd1']))
       {
          $_SESSION['error']='New passwords do not match';
          return;          
       }
       // change password
       $sql = 'update user set passcode="'.password_hash($_POST['new_pwd1'],PASSWORD_DEFAULT).'" where user_id='.$_SESSION['user'];
       logger($link,$sql);
       $data = mysqli_query($link,$sql);
       if (!mysqli_query($link,$sql))
       {
          logger($link,"Error updating record: " . mysqli_error($link));
       }
       $_SESSION['message']='Password successfully updated';
    }
    if (isset($_POST['account']))
    {
       $_SESSION['page']='account';
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
	if (isset($_POST['selectevent']))
	{
		$_SESSION['page']='selectev';
	}
	// add none option
	if (isset($_POST['bringingfood']))
	{
	   //clear food if changing pick
	   $sql = 'update food_for_event set family_id=null,notes=null where event_id='.$_SESSION['event_id'].' and family_id='.$_SESSION['family_id'];
	   logger($link,$sql);
	   if (!mysqli_query($link,$sql))
	   {
	      logger($link,"Error deleting record: " . mysqli_error($link));
	   }
	   $food_id=$_POST['bringing'];
	   if ($food_id!=-1)
	   {
	      $sql = 'update food_for_event set family_id='.$_SESSION['family_id'].',notes="'.$_POST['notes'].'" where food_id='.$food_id.' and event_id='.$_SESSION['event_id'];
	      logger($link,$sql);
	      if (!mysqli_query($link,$sql))
	      {
	         logger($link,"Error inserting record: " . mysqli_error($link));
	      }
	   }
	}
}
?>