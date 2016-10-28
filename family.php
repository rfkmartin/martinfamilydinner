<?php
// print table of familes
function family_table($link)
{

   $i=0;
   $sql = "select family_id,name,phone,day,month,year,line1,line2,city,state,zip from family left join date on anniversary_id=date_id left join address on family.address_id=address.address_id order by name";
   $data = mysqli_query($link,$sql);
   while (list($id[$i],$name[$i],$phone[$i],$day[$i],$month[$i],$year[$i],$line1[$i],$line2[$i],$city[$i],$state[$i],$zip[$i])=mysqli_fetch_row($data)) {
      $i++;
   }

	$calendar = '<table border="1" cellpadding="1" cellspacing="1" class="family_table">';

   $calendar.= '<tr><td>Name</td><td>Address</td><td>Phone</td><td>Anniversary</td><td></td></tr>';

   for($x=0;$x<$i;$x++)
   {
      $calendar.='<tr><td>'.$name[$x].'</td>';
      if (!empty($city[$x]))
      {
         $calendar.='<td align="right">'.$line1[$x].'<br>';
         if (!empty($line2[$x]))
         {
         $calendar.=$line2[$x].'<br>';
         }
         $calendar.=$city[$x].', '.$state[$x].' '.$zip[$x].'</td>';
      }
      else
      {
         $calendar.='<td></td>';
      }
      $calendar.='<td>'.format_phone($phone[$x]).'</td>';
      if (empty($day[$x]))
      {
         $calendar.='<td></td>';
      }
      else
      {
         $calendar.='<td>'.$month[$x].'/'.$day[$x].'/'.$year[$x].'</td>';
      }
      $calendar.='<td><form action = "" method = "post"><input type="hidden" name="family_id" value="'.$id[$x].'"><input type="submit" name="delete" value="Delete"></form></td></tr>';
   }
   $calendar.='</table>';

	return $calendar;
}

// print dropdown list of familes
function family_ddl($link)
{
   if (isset($_POST['change']))
   {
      if ($_POST['family']==-1)
      {
         $_SESSION['family_id']=NULL;
      }
      else
      {
         $_SESSION['family_id']=$_POST['family'];
      }
   }
   echo '<form action = "" method = "post"><select name="family">';

   $sql = "select family_id,name from family";
   $data = mysqli_query($link,$sql);
   echo '<option value="-1">Add New</option>';
   while(list($family_id,$name) = mysqli_fetch_row($data))
   {
      echo '<option value="'.$family_id.'"';
      if (!empty($_SESSION['family_id'])&&$family_id==$_SESSION['family_id'])
      {
         echo ' selected';
      }
      echo '>'.$name.'</option>'."\n";
   }
   echo '</select><input type="submit" name="change" value="Change Family"></form>';
}

// print form to add/edit families
function family_addnew($link)
{
   //todo: if new family added, add to bringing
   if (isset($_POST['addfamily']))
   {
      $name = $_POST['familyname'];
      $line1 = $_POST['line1'];
      $line2 = $_POST['line2'];
      $city = $_POST['city'];
      $state = $_POST['state'];
      $zip = $_POST['zip'];
      $phone =  $_POST['phone'];
      $anniv = $_POST['anniv'];
      $annivY = date('Y', strtotime($_POST['anniv']));
      $annivm = date('m', strtotime($_POST['anniv']));
      $annivd = date('d', strtotime($_POST['anniv']));
      // insert address
      $sql = "insert into address(line1,line2,city,state,zip) values (\"".$line1."\",\"".$line2."\",\"".$city."\",\"".$state."\",\"".$zip."\");";
      logger($link,$sql);
      if (!empty($_POST['city']))
      {
         if (mysqli_query($link,$sql))
         {
            $address_id = mysqli_insert_id($link);
         }
         else
         {
            logger($link,"Error inserting record: " . mysqli_error($link));
         }
      }
      else
      {
         $address_id="NULL";
      }
      if (!empty($_POST['anniv']))
      {
         // insert anniversary
         $sql = "insert into date(day,month,year) values (\"".$annivd."\",\"".$annivm."\",\"".$annivY."\");";
         logger($link,$sql);
         if (mysqli_query($link,$sql))
         {
            $anniv_id = mysqli_insert_id($link);
         }
         else
         {
            logger($link,"Error inserting record: " . mysqli_error($link));
         }
      }
      else
      {
         $anniv_id="NULL";
      }
      $sql = "insert into family (name,phone,anniversary_id,address_id) values (\"".$name."\",\"".$phone."\",".$anniv_id.",".$address_id.");";
      echo $sql;
      logger($link,$sql);
      if (mysqli_query($link,$sql))
      {
         $selected_family = mysqli_insert_id($link);
      }
      else
      {
         logger($link,"Error inserting record: " . mysqli_error($link));
      }
   }
   if (isset($_POST['updatefamily']))
   {
      $selected_family=$_POST['family_id'];
      $sql = "select address_id,anniversary_id from family where family_id=".$selected_family;
      $data = mysqli_query($link,$sql);
      $add_anniv="";
      $add_address="";
      $phone = preg_replace("/[^0-9]/","",$_POST['phone']);
      list($address_id,$anniversary_id)=mysqli_fetch_row($data);
      if (empty($address_id))
      {
         $sql = "insert into address(line1,line2,city,state,zip) values ('".$_POST['line1']."','".$_POST['line2']."','".$_POST['city']."', '".$_POST['state']."', '".$_POST['zip']."')";
         logger($link,$sql);
         if (mysqli_query($link,$sql))
         {
            $address_id = mysqli_insert_id($link);
         }
         else
         {
            logger($link,"Error inserting record: " . mysqli_error($link));
         }
      }
      else
      {
         $sql = "update address set line1='".$_POST['line1']."',line2='".$_POST['line2']."',city='".$_POST['city']."',state='".$_POST['state']."',zip='".$_POST['zip']."' where address_id=".$address_id."";
         logger($link,$sql);
         if (!mysqli_query($link,$sql))
         {
            logger($link,"Error updating record: " . mysqli_error($link));
         }
      }

      if (!empty($_POST['anniv']))
      {
         $annivY = date('Y', strtotime($_POST['anniv']));
         $annivm = date('m', strtotime($_POST['anniv']));
         $annivd = date('d', strtotime($_POST['anniv']));
         if (empty($anniversary_id))
         {
            $sql = "insert into date(day,month,year) values ('".$annivd."','".$annivm."','".$annivY."')";
            logger($link,$sql);
            if (!empty($_POST['anniv']))
            {
               if (mysqli_query($link,$sql))
               {
                  $anniversary_id = mysqli_insert_id($link);
               }
               else
               {
                  logger($link,"Error inserting record: " . mysqli_error($link));
               }
            }
         }
         else
         {
            $sql = "update date set day='".$annivd."',month='".$annivm."',year='".$annivY."' where date_id=".$anniversary_id."";
            logger($link,$sql);
            if (!mysqli_query($link,$sql))
            {
               echo "Error updating record: " . mysqli_error($link);
            }
         }
      }
      if (!empty($address_id))
      {
         $add_address=",address_id='".$address_id."'";
      }
      if (!empty($anniversary_id))
      {
         if (!empty($_POST['anniv']))
         {
            $add_anniv=",anniversary_id='".$anniversary_id."'";
         }
         else
         {
            $add_anniv=",anniversary_id=NULL";
         }
      }
      $sql = "update family set name='".$_POST['familyname']."'".$add_address.$add_anniv.",phone='".$phone."' where family_id=".$selected_family."";
      logger($link,$sql);
      if (!mysqli_query($link,$sql))
      {
         logger($link,"Error updating record: " . mysqli_error($link));
      }
   }
   echo '<form action = "" method = "post">';

   if (!empty($_SESSION['family_id']))
   {
      echo '<h2>Edit Family Info</h2>';
      $sql = "select family_id,name,line1,line2,city,state,zip,phone,day,month,year from family join address on family.address_id=address.address_id left join date on family.anniversary_id=date.date_id where family_id=".$_SESSION['family_id'];
      $data = mysqli_query($link,$sql);
      list($family_id,$name,$line1,$line2,$city,$state,$zip,$phone,$day,$month,$year) = mysqli_fetch_row($data);
      echo '<table border="1"><tr><td>Family Name:</td><td><input type="text" name="familyname" value="'.$name.'"><input type="hidden" name="family_id" value="'.$_SESSION['family_id'].'"></td></tr>';
      echo '<tr><td>Address Line 1:</td><td><input type="text" name="line1" value="'.$line1.'"></td></tr>';
      echo '<tr><td>Address Line 2:</td><td><input type="text" name="line2" value="'.$line2.'"></td></tr>';
      echo '<tr><td>City:</td><td><input type="text" name="city" value="'.$city.'"></td></tr>';
      echo '<tr><td>State:</td><td><input type="text" name="state" value="'.$state.'"></td></tr>';
      echo '<tr><td>ZIP:</td><td><input type="text" name="zip" value="'.$zip.'"></td></tr>';
      echo '<tr><td>Phone:</td><td><input type="text" name="phone" value="'.format_phone($phone).'"></td></tr>';
      if (empty($year))
      {
         echo '<tr><td>Anniversary:</td><td><input type="text" id="date" name="anniv" value="" data-format="DD-MM-YYYY" data-template="D MMM YYYY"></td></tr>';
      }
      else
      {
         echo '<tr><td>Anniversary:</td><td><input type="text" id="date" name="anniv" data-format="DD-MM-YYYY" data-template="D MMM YYYY" value="'.sprintf('%02d',$day).'-'.sprintf('%02d',$month).'-'.$year.'"></td></tr>';
      }
      echo '<tr><td colspan="2" align="center"><input type="submit" name="updatefamily" value="Update"></td></tr></table>';
      echo '<script>$(function(){$(\'#date\').combodate();});</script>';
   }
   else
   {
      echo '<table border="1"><tr><td>Family Name:</td><td><input type="text" name="familyname"></td></tr>';
      echo '<tr><td>Address Line 1:</td><td><input type="text" name="line1"></td></tr>';
      echo '<tr><td>Address Line 2:</td><td><input type="text" name="line2"></td></tr>';
      echo '<tr><td>City:</td><td><input type="text" name="city"></td></tr>';
      echo '<tr><td>State:</td><td><input type="text" name="state"></td></tr>';
      echo '<tr><td>ZIP:</td><td><input type="text" name="zip"></td></tr>';
      echo '<tr><td>Phone:</td><td><input type="text" name="phone"></td></tr>';
      echo '<tr><td>Anniversary:</td><td><input type="text" id="date" name="anniv" data-format="DD-MM-YYYY" data-template="D MMM YYYY"></td></tr>';
      echo '<tr><td colspan="2" align="center"><input type="submit" name="addfamily" value="Add New"></td></tr></table>';
      echo '<script>$(function(){$(\'#date\').combodate();});</script>';
   }
   echo '</form>';
}

// print form to add/edit members
function member_addnew($link)
{
   //todo: if new member added, add to attending
   if (isset($_POST['editmem']))
   {
      $first = $_POST['first'];
      $last = $_POST['last'];
      $email = $_POST['email'];
      (isset($_POST['show']))?$show = $_POST['show']:$show = "off";
      $bday = $_POST['bday'];
      (isset($_POST['bday_id']))?$bday_id = $_POST['bday_id']:$bday_id = 0;
      $family_id = $_POST['family_id'];
      (isset($_POST['person_id']))?$member_id = $_POST['person_id']:$member_id = 0;
      $bdayY = date('Y', strtotime($_POST['bday']));
      $bdaym = date('m', strtotime($_POST['bday']));
      $bdayd = date('d', strtotime($_POST['bday']));
      if ($show=="on")
      {
         $show=1;
      }
      else
      {
         $show=0;
      }
      // insert or edit bday
      if ($member_id==0)
      {
         $sql = "insert into date(day,month,year) values ('".$bdayd."','".$bdaym."','".$bdayY."')";
         logger($link,$sql);
         if (mysqli_query($link,$sql))
         {
            $bday_id = mysqli_insert_id($link);
         }
         else
         {
            logger($link,"Error inserting record: " . mysqli_error($link));
         }
         $sql = "insert into person(first_name,last_name,email,family_id,birthday_id,show_age) values ('".$first."','".$last."','".$email."','".$family_id."','".$bday_id."','".$show."')";
         logger($link,$sql);
         if (mysqli_query($link,$sql))
         {
            $bday_id = mysqli_insert_id($link);
         }
         else
         {
            logger($link,"Error inserting record: " . mysqli_error($link));
         }
      }
      else
      {
         $sql = "update person set first_name='".$first."',last_name='".$last."',email='".$email."',show_age='".$show."' where person_id=".$member_id;
         logger($link,$sql);
         if (!mysqli_query($link,$sql))
         {
            logger($link,"Error updating record: " . mysqli_error($link));
         }
         $sql = "update date set day='".$bdayd."',month='".$bdaym."',year='".$bdayY."' where date_id=".$bday_id;
         logger($link,$sql);
         if (!mysqli_query($link,$sql))
         {
            logger($link,"Error updating record: " . mysqli_error($link));
         }
      }
   }
   $i=1;
   if (!empty($_SESSION['family_id']))
   {
      echo '<center><h2>Add a Family Member</h2></center>';
      $sql = "select person_id,first_name,last_name,email,show_age,day,month,year,birthday_id from person left join date on person.birthday_id=date.date_id where family_id=".$_SESSION['family_id'];
      $data = mysqli_query($link,$sql);
      echo '<table border="1"><tr><td width="150px">First Name</td><td width="150px">Last Name</td><td width="150px">Email</td><td width="100px">Show Age?</td><td width="175px">Birthdate</td><td width="75px"></td></tr></table>';
      while(list($id,$first,$last,$email,$show,$day,$month,$year,$bdayid) = mysqli_fetch_row($data))
      {
         $show_age="";
         if ($show==1)
         {
            $show_age=' checked';
         }
         echo '<form action = "" method = "post">';
         echo '<table border="1"><tr>';
         echo '<td width="150px"><input type="text" name="first" size="15" value="'.$first.'"></td>';
         echo '<td width="150px"><input type="text" name="last" size="15" value="'.$last.'"></td>';
         echo '<td width="150px"><input type="text" name="email" size="15" value="'.$email.'"></td>';
         echo '<td width="100px"><input type="checkbox" name="show"'.$show_age.'></td>';
         echo '<td width="175px"><input type="text" id="bdate'.$i.'" name="bday" data-format="DD-MM-YYYY" data-template="D MMM YYYY" value="'.sprintf('%02d',$day).'-'.sprintf('%02d',$month).'-'.$year.'"></td>';
         echo '<td width="75px"><input type="hidden" name="bday_id" value="'.$bdayid.'"><input type="hidden" name="family_id" value="'.$_SESSION['family_id'].'"><input type="hidden" name="person_id" value="'.$id.'">';
         echo '<input type="submit" name="editmem" value="Update">';
         echo '</td></tr></table>';
         echo '<script>$(function(){$(\'#bdate'.$i.'\').combodate();});</script>';
         echo '</form>';
         $i++;
      }
         echo '<form action = "" method = "post">';
         echo '<table border="1"><tr>';
         echo '<td width="150px"><input type="text" size="15" name="first"></td>';
         echo '<td width="150px"><input type="text" size="15" name="last"></td>';
         echo '<td width="150px"><input type="text" size="15" name="email"></td>';
         echo '<td width="100px"><input type="checkbox" name="show"></td>';
         echo '<td width="175px"><input type="text" id="bdate'.$i.'" name="bday" data-format="DD-MM-YYYY" data-template="D MMM YYYY" ></td>';
         echo '<td width="75px"><input type="hidden" name="family_id" value="'.$_SESSION['family_id'].'"><input type="submit" name="editmem" value="Add New"></td></tr>';
         echo '</table>';
         echo '<script>$(function(){$(\'#bdate'.$i.'\').combodate();});</script></form>';
   }
}
// format phone numbers according to user definition
function format_phone($phone)
{
   if (empty($phone))
   {
      return "";
   }
   else
   {
      // 123.456.7890
      //return substr($phone,0,3).'.'.substr($phone,3,3).'.'.substr($phone,6,4);
      // 123-456-7890
      //return substr($phone,0,3).'-'.substr($phone,3,3).'-'.substr($phone,6,4);
      // (123) 456-7890
      return '('.substr($phone,0,3).') '.substr($phone,3,3).'-'.substr($phone,6,4);
   }
}
// print ddl of upcoming events and checkboxes of possible foods
function add_food_to_event($link)
{
   if (empty($_SESSION['foods']))
   {
      $_SESSION['foods']=9;
      $_SESSION['events']=3;
      $_SESSION['event_id']=-1;
   }
   $error="";
   if (isset($_POST['changeevent']))
   {
      if ($_POST['event']==-1)
      {
         $_SESSION['event_id']=-1;
         $error='<br><font color="red">You must select an event.</font>';
      }
      else
      {
         $_SESSION['event_id']=$_POST['event'];
      }
   }
   if (isset($_POST['addfoodtoevent']))
   {
      if (isset($_POST['foods']))
      {
         if ($_POST['event']==-1)
         {
            $error='<br><font color="red">You must select an event.</font>';
         }
         else
         {
            $foodArray = $_POST['foods'];
            $j=0;
            for ($i=1; $i<=$_SESSION['foods']; $i++)
            {
               if ($j<count($foodArray)&&$foodArray[$j]==$i)
               {
                  $sql = "update food_for_event set on_menu=1 where event_id=".$_POST['event']." and food_id=".$i;
                  $j++;
                  logger($link,$sql);
                  if (!mysqli_query($link,$sql))
                  {
                    logger($link,"Error inserting record: " . mysqli_error($link));
                  }
               }
               else
               {
                  $sql = "update food_for_event set on_menu=0 where event_id=".$_POST['event']." and food_id=".$i;
                  logger($link,$sql);
                  if (!mysqli_query($link,$sql))
                  {
                    logger($link,"Error inserting record: " . mysqli_error($link));
                  }
               }
            }
         }
      }
   }
   echo '<h2>Add Food to Event</h2>';
   echo $error;
   echo '<form action = "" method = "post"><table border="1"><tr><td valign="top"><select name="event">';
   echo '<option value="-1">Select Event</option>';

   $sql = "select * from (select f.name,e.event_id,d.month,d.year,str_to_date(concat(concat(month,'/',greatest(day,1)),'/',year),'%m/%d/%Y') dt from date d join event e on d.date_id=e.date_id join family f on f.family_id=e.family_id where e.cancel=0) as a where a.dt>curdate()";
   $data = mysqli_query($link,$sql);
   while (list($fam_name,$event_id,$month,$year,$date)=mysqli_fetch_row($data))
   {
      echo '<option value="'.$event_id.'"';
      if ($event_id==$_SESSION['event_id'])
      {
         echo ' selected';
      }
      echo '>'.date("M",strtotime($date)).' '.date("Y",strtotime($date)).'--'.$fam_name.'</option>'."\n";
   }
   echo '</select><input type="submit" name="changeevent" value="Change Event"></td><td><table border="1">';
   $sql = "select f.food_id,food,on_menu from food f left join (select * from food_for_event where event_id=".$_SESSION['event_id'].") as e on f.food_id=e.food_id order by f.food_id";
   $data = mysqli_query($link,$sql);
   logger($link,$sql);
   $i=0;
   while (list($food_id,$food,$selected)=mysqli_fetch_row($data))
   {
      $checked='';
      if (!empty($selected))
      {
         $checked=' checked';
      }
      if ($i==0)
      {
         echo '<tr><td align="left"><input type="checkbox" name="foods[]" value='.$food_id.$checked.'>'.$food.'</td>';
      }
      else
      {
         echo '<td align="left"><input type="checkbox" name="foods[]" value='.$food_id.$checked.'>'.$food.'</td></tr>';
      }
      $i=1-$i;
   }
   if ($i==1)
   {
      echo '<td align="left"></td></tr>';
   }
   echo '</table><input type="submit" name="addfoodtoevent" value="Update">';
   echo '</td></tr></table></form>';
}
// print current food options and form to add more
function add_food($link)
{
   if (isset($_POST['addfood']))
   {
      $addfood=$_POST['food'];
      $sql = "insert into food(food) values ('".$addfood."')";
      logger($link,$sql);
      if (!mysqli_query($link,$sql))
      {
         logger($link,"Error inserting record: " . mysqli_error($link));
      }
      else
      {
         $food_id = mysqli_insert_id($link);
      }
      $_SESSION['foods']++;
      for ($j=1;$j<=$_SESSION['events'];$j++)
      {
         $sql = "insert into food_for_event(event_id,food_id) values (".$j.",".$food_id.")";
         logger($link,$sql);
         if (!mysqli_query($link,$sql))
         {
            logger($link,"Error inserting record: " . mysqli_error($link));
         }
      }
   }
   echo '<h2>Add Food to List</h2>';
   $i=0;
   $sql = "select food from food";
   $data = mysqli_query($link,$sql);
   while (list($food[$i])=mysqli_fetch_row($data)) {
      $i++;
   }
   $calendar='<table border="1" cellpadding="1" cellspacing="1">';
   for ($j=0;$j<$i;$j+=4)
   {
      $calendar.='<tr><td>'.$food[$j].'</td><td>';
      if ($j+1<$i)
      {
         $calendar.=$food[$j+1];
      }
      $calendar.='</td><td>';
      if ($j+2<$i)
      {
         $calendar.=$food[$j+2];
      }
      $calendar.='</td><td>';
      if ($j+3<$i)
      {
         $calendar.=$food[$j+3];
      }
      $calendar.='</td></tr>';
   }
   $calendar.='</table><br>';
   $calendar.='<form action = "" method = "post">';
   $calendar.='<table><tr><td>Add New Food</td><td><input type="text" name="food"><input type="submit" name="addfood" value="Add"></td></tr></table>';
   $calendar.='</form>';
   return $calendar;
}
// print current family members and select for attending selected event
function add_attendance($link)
{
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
   echo '<h2>Attending</h2>';
   if (!empty($_SESSION['family_id'])&&!empty($_SESSION['event_id']))
   {
      echo '<form action = "" method = "post"><table border="1">';
      $sql = "select p.person_id,first_name,coming from person p join family f on p.family_id=f.family_id join attending a on p.person_id=a.person_id where f.family_id=".$_SESSION['family_id'].' and a.event_id='.$_SESSION['event_id'];
      $data = mysqli_query($link,$sql);
      logger($link,$sql);
      while (list($person_id,$firstname,$coming)=mysqli_fetch_row($data))
      {
         $checked='';
         if (!empty($coming))
         {
            $checked=' checked';
         }
         echo '<tr><td><input type="checkbox" name="persons[]" value='.$person_id.$checked.'>'.$firstname.'</td></tr>';
      }
      echo '<tr><td><input type="submit" name="addpersonevent" value="Update"></td></tr></table>';
      echo '</form>';
   }
   else
   {
      echo 'No currently scheduled events';
   }
}
// print unselected foods for event
function bringing($link)
{
   // add none option
   if (isset($_POST['bringingfood']))
   {
      //clear food if changing pick
      $sql = 'update food_for_event set family_id=null where event_id='.$_SESSION['event_id'].' and family_id='.$_SESSION['family_id'];
      logger($link,$sql);
      if (!mysqli_query($link,$sql))
      {
         logger($link,"Error deleting record: " . mysqli_error($link));
      }
      $food_id=$_POST['bringing'];
      if ($food_id!=-1)
      {
         $sql = 'update food_for_event set family_id='.$_SESSION['family_id'].' where food_id='.$food_id.' and event_id='.$_SESSION['event_id'];
         logger($link,$sql);
         if (!mysqli_query($link,$sql))
         {
            logger($link,"Error inserting record: " . mysqli_error($link));
         }
      }
   }
   echo '<h2>Bringing</h2>';
   if (!empty($_SESSION['family_id'])&&!empty($_SESSION['event_id']))
   {
      // check to make sure at least one family member is attending
      $sql = 'select sum(coming) from attending a join person p on p.person_id=a.person_id where a.event_id='.$_SESSION['event_id'].' and p.family_id='.$_SESSION['family_id'];
      $data = mysqli_query($link,$sql);
      logger($link,$sql);
      list($sum)=mysqli_fetch_row($data);
      if ($sum>0)
      {
         echo '<form action = "" method = "post"><table border="1">';
         $sql = 'select f.food_id,family_id,food from food_for_event ff join food f on ff.food_id=f.food_id where event_id='.$_SESSION['event_id'].' and on_menu=1 and (family_id='.$_SESSION['family_id'].' or family_id is null)';
         $data = mysqli_query($link,$sql);
         logger($link,$sql);
         $was_checked=0;
         while (list($food_id,$family_id,$food)=mysqli_fetch_row($data))
         {
            $checked='';
            if ($family_id==$_SESSION['family_id'])
            {
               $checked=' checked';
               $was_checked=1;
            }
            echo '<tr><td><input type="radio" name="bringing" value='.$food_id.$checked.'>'.$food.'</td></tr>';
         }
         if ($was_checked==0)
         {
            $checked=' checked';
         }
         echo '<tr><td><input type="radio" name="bringing" value=-1'.$checked.'>none</td></tr>';
         echo '<tr><td><input type="submit" name="bringingfood" value="Update"></td></tr></table>';
         echo '</form>';
      }
   }
}
// print all upcoming events with food and attendance
function print_events($link,$type)
{
   if ($type=='upcoming')
   {
      $title='Upcoming Events';
      $timearrow='>';
      $cancelled=' where e.cancel=0';
   }
   elseif ($type=='cancelled')
   {
      $title='Cancelled Events';
      $timearrow='>';
      $cancelled=' where e.cancel=1';
   }
   elseif ($type=='past')
   {
      $title='Past Events';
      $timearrow='<';
      $cancelled='';
   }
   echo '<h2>'.$title.'</h2>';
   $sql = "select * from (select e.event_id,f.name,e.family_id,ad.line1,ad.city,ad.state,d.month,d.day,d.year,str_to_date(concat(concat(month,'/',greatest(day,1)),'/',year),'%m/%d/%Y') dt from date d join event e on d.date_id=e.date_id join family f on f.family_id=e.family_id join address ad on ad.address_id=f.address_id".$cancelled.") as a where a.dt".$timearrow."curdate()";
   logger($link,$sql);
   $data = mysqli_query($link,$sql);
   while (list($event_id,$fam_name,$fam_id,$line1,$city,$state,$month,$day,$year,$date)=mysqli_fetch_row($data))
   {
      echo '<table border="1" width="80%"><tr><td colspan="2">';
      echo '<b>'.date("F",strtotime($date)).' '.$year.'</b><br><b>Host:</b> '.$fam_name.'<br><b>Date:</b>';
      if ($day<1)
      {
         echo '<b>TBD</b>';
      }
      else
      {
         echo date("D",strtotime($date)).', '.date("M",strtotime($date)).' '.$day;
      }
      echo '<br><b>Time:</b> 4pm';
      echo '<br><b>Location:</b> '.$line1.' '.$city.', '.$state.'</td></tr>';
      echo '<tr><td valign="top" width="50%"><table border="1" width="100%"><tr><td colspan="2" align="center"><b>Dishes</b></td></tr>';
      $sql1 = 'select food,e.event_id,fa.name from food f left join (select * from food_for_event where event_id='.$event_id.' and on_menu=1) as e on f.food_id=e.food_id left join event ev on e.event_id=ev.event_id left join family fa on fa.family_id=e.family_id order by f.food_id';
      logger($link,$sql1);
      $data1 = mysqli_query($link,$sql1);
      while (list($food,$on_menu,$family_name)=mysqli_fetch_row($data1))
      {
         if ($on_menu!="")
         {
            echo '<tr><td width="67%">';
            if ($family_name!="")
            {
               echo '<b>'.$family_name.'</b>';
            }
            echo '</td><td>'.$food.'</td></tr>';
         }
      }
      echo '</table></td><td valign="top">';
      echo '<table border="1" width="100%"><tr><td colspan="2" align="center"><b>Attending</b>';
      $sql1 = 'select name,first_name from person p join family f on p.family_id=f.family_id join attending a on p.person_id=a.person_id where a.event_id='.$event_id.' and coming=1';
      logger($link,$sql1);
      $data1 = mysqli_query($link,$sql1);
      $prev_name="";
      while (list($family_name,$first)=mysqli_fetch_row($data1))
      {
         if ($prev_name!=$family_name)
         {
            $prev_name=$family_name;
            echo '</td></tr><tr><td width="75%">';
            echo '<b>'.$family_name.'</b>';
            echo '</td><td>'.$first;
         }
         else
         {
            echo '<br>'.$first;
         }
      }
      echo '</td></tr></table>';
      echo '</td></tr></table><br>';
      if (empty($_SESSION['user']) && $type=='upcoming')
      {
      	break;
      }
   }
}
// print all upcoming events with food and attendance
function add_events($link)
{
   //todo: if event added, add to bringing
   $error="";
   if (isset($_POST['addevent']))
   {
      $family_id=$_POST['family'];
      //check for valid month year
      if ($_POST['eday']=="")
      {
         $eventm=date('m', strtotime($_POST['etday']));
         $eventY=date('Y', strtotime($_POST['etday']));
         $eventd=-1;
      }
      else
      {
         $eventm=date('m', strtotime($_POST['eday']));
         $eventY=date('Y', strtotime($_POST['eday']));
         $eventd=date('d', strtotime($_POST['eday']));
      }
      $sql = "insert into date(day,month,year) values ('".$eventd."','".$eventm."','".$eventY."')";
      logger($link,$sql);
      if (mysqli_query($link,$sql))
      {
         $date_id = mysqli_insert_id($link);
      }
      else
      {
         logger($link,"Error inserting record: " . mysqli_error($link));
      }
      $sql = "insert into event(family_id,date_id) values (".$family_id.",".$date_id.")";
      logger($link,$sql);
      if (!mysqli_query($link,$sql))
      {
         logger($link,"Error inserting record: " . mysqli_error($link));
      }
      else
      {
         $event_id = mysqli_insert_id($link);
      }
      $_SESSION['events']++;
      for ($j=1;$j<=$_SESSION['foods'];$j++)
      {
         $sql = "insert into food_for_event(event_id,food_id) values (".$event_id.",".$j.")";
         logger($link,$sql);
         if (!mysqli_query($link,$sql))
         {
            logger($link,"Error inserting record: " . mysqli_error($link));
         }
      }
      // for each person, add entry into attending table
      $sql = 'select person_id from person';
      logger($link,$sql);
      $data = mysqli_query($link,$sql);
      while (list($person_id)=mysqli_fetch_row($data))
      {
         $sql1 = 'insert into attending (event_id,person_id) values ('.event_id.','.person_id.')';
         logger($link,$sql);
         if (!mysqli_query($link,$sql))
         {
            logger($link,"Error inserting record: " . mysqli_error($link));
         }
      }
   }
   if (isset($_POST['updateevent']))
   {
      if (!empty($_POST['cancel']))
      {
         $e_id=$_POST['e_id'];
         $sql = "update event set cancel=1 where event_id=".$e_id;
         logger($link,$sql);
         if (!mysqli_query($link,$sql))
         {
            logger($link,"Error updating record: " . mysqli_error($link));
         }
      }
      else
      {
         if ($_POST['eday']=="")
         {
            $error.='<br><font color="red">Please select a valid date</font>';
         }
         else
         {
            $eventm=date('m', strtotime($_POST['eday']));
            $eventY=date('Y', strtotime($_POST['eday']));
            $eventd=date('d', strtotime($_POST['eday']));
            $e_id=$_POST['e_id'];
            $family_id=$_POST['family'];
            $sql = 'update date d join event e on d.date_id=e.date_id set month='.$eventm.',day='.$eventd.',year='.$eventY.' where e.event_id='.$e_id;
            logger($link,$sql);
            if (!mysqli_query($link,$sql))
            {
               logger($link,"Error updating record: " . mysqli_error($link));
            }
            $sql = 'update event e set family_id='.$family_id.' where e.event_id='.$e_id;
            logger($link,$sql);
            if (!mysqli_query($link,$sql))
            {
               logger($link,"Error updating record: " . mysqli_error($link));
            }
         }
      }
   }
   echo '<h2>Add/Edit Events</h2>';
   echo $error;
   echo '<table border="1"><tr>';
   echo '<td width="175px"><b>Family</b></td>';
   echo '<td width="175px"><b>Tentative Date</b></td>';
   echo '<td width="175px"><b>Solid Date</b></td>';
   echo '<td width="60px"><b><b>Cancel</b></b></td>';
   echo '<td width="100px"></td></tr></table>';
   $sql = "select * from (select e.event_id,f.name,e.family_id,d.month,d.day,d.year,str_to_date(concat(concat(month,'/',greatest(day,1)),'/',year),'%m/%d/%Y') dt from date d join event e on d.date_id=e.date_id join family f on f.family_id=e.family_id where e.cancel=0) as a where a.dt>curdate()";
   logger($link,$sql);
   $data = mysqli_query($link,$sql);
   $i=0;
   while (list($e_id,$fam_name,$fam_id,$month,$day,$year,$date)=mysqli_fetch_row($data)) {
      $sql1 = "select family_id,name from family";
      $data1 = mysqli_query($link,$sql1);
      $ddl='<select name="family"><option value="-1">Choose a family</option>';
      while(list($family_id,$name) = mysqli_fetch_row($data1))
      {
         $ddl.='<option value="'.$family_id.'"';
         if ($fam_id==$family_id)
         {
            $ddl.=' selected';
         }
         $ddl.='>'.$name.'</option>'."\n";
      }
      $ddl.='</select>';
      echo '<form action = "" method = "post">';
      echo '<table border="1"><tr>';
      echo '<td width="175px">'.$ddl.'</td>';
      echo '<td width="175px"><input type="text" id="etdate'.$i.'" name="etday" data-format="YYYY-MM" data-template="MMM YYYY" value="'.$year.'-'.sprintf('%02d',$month).'"></td>';
      echo '<td width="175px"><input type="text" id="edate'.$i.'" name="eday" data-format="DD-MM-YYYY" data-template="D MMM YYYY" value="'.sprintf('%02d',$day).'-'.sprintf('%02d',$month).'-'.$year.'"></td>';
      echo '<td width="60px"><input type="checkbox" name="cancel"></td>';
      echo '<td width="100px"><input type="hidden" name="e_id" value="'.$e_id.'">';
      echo '<input type="submit" name="updateevent" value="Update">';
      echo '</td></tr></table>';
      echo '<script>$(function(){$(\'#etdate'.$i.'\').combodate({minYear:2016,maxYear:2018});});</script>';
      echo '<script>$(function(){$(\'#edate'.$i.'\').combodate({minYear:2016,maxYear:2018});});</script>';
      echo '</form>';
      $i++;
   }
   $sql = "select family_id,name from family";
   $data = mysqli_query($link,$sql);
   $ddl='<select name="family"><option value="-1" selected>Choose a family</option>';
   while(list($family_id,$name) = mysqli_fetch_row($data))
   {
      $ddl.='<option value="'.$family_id.'"';
      $ddl.='>'.$name.'</option>'."\n";
   }
   $ddl.='</select>';
   echo '<form action = "" method = "post">';
   echo '<table border="1"><tr>';
   echo '<td width="175px">'.$ddl.'</td>';
   echo '<td width="175px"><input type="text" id="etdate'.$i.'" name="etday" data-format="YYYY-MM" data-template="MMM YYYY"></td>';
   echo '<td width="175px"><input type="text" id="edate'.$i.'" name="eday" data-format="DD-MM-YYYY" data-template="D MMM YYYY"></td>';
   echo '<td width="60px"></td>';
   echo '<td width="100px">';
   echo '<input type="submit" name="addevent" value="Add New">';
   echo '</td></tr></table>';
   echo '<script>$(function(){$(\'#etdate'.$i.'\').combodate({minYear:2016,maxYear:2018});});</script>';
   echo '<script>$(function(){$(\'#edate'.$i.'\').combodate({minYear:2016,maxYear:2018});});</script>';
   echo '</form>';
}
?>