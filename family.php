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

// print table of family members
function familymem_table($link)
{
   if (empty($_SESSION['family_id']))
   {
      return "";
   }
   $i=0;

   $sql = "select person_id,first_name,last_name,day,month,year from person join family on person.family_id=family.family_id join date on person.birthday_id=date.date_id where person.family_id=".$_SESSION['family_id'];
   $data = mysqli_query($link,$sql);
   while (list($id[$i],$fname[$i],$lname[$i],$day[$i],$month[$i],$year[$i])=mysqli_fetch_row($data)) {
      $i++;
   }
	$calendar = '<table border="1" cellpadding="1" cellspacing="1" class="family_table">';

   $calendar.= '<tr><td>Name</td><td>Birthday</td><td></td></tr>';

   for($x=0;$x<$i;$x++)
   {
      $calendar.='<tr><td>'.$fname[$x].' '.$lname[$x].'</td>';
      if (empty($day[$x]))
      {
         $calendar.='<td></td></tr>';
      }
      else
      {
         $calendar.='<td>'.$month[$x].'/'.$day[$x].'/'.$year[$x].'</td>';
      }
      $calendar.='<td><form action = "" method = "post"><input type="hidden" name="family_id" value="'.$id[$x].'"><input type="submit" name="edit" value="Edit"><input type="submit" name="delete" value="Delete"></form></td></tr>';
   }
   $calendar.='</table>';

	return $calendar;
}

// print dropdown list of familes
function family_ddl($link)
{
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
   echo '<form action = "" method = "post">';

   if (!empty($_SESSION['family_id']))
   {
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

   $i=1;
   if (!empty($_SESSION['family_id']))
   {
      $sql = "select person_id,first_name,last_name,show_age,day,month,year,birthday_id from person left join date on person.birthday_id=date.date_id where family_id=".$_SESSION['family_id'];
      $data = mysqli_query($link,$sql);
      echo '<table border="1"><tr><td width="175px">First Name</td><td width="175px">Last Name</td><td width="100px">Show Age?</td><td width="175px">Birthdate</td><td width="75px"></td></tr></table>';
      while(list($id,$first,$last,$show,$day,$month,$year,$bdayid) = mysqli_fetch_row($data))
      {
         $show_age="";
         if ($show==1)
         {
            $show_age=' checked';
         }
         echo '<form action = "" method = "post">';
         echo '<table border="1"><tr>';
         echo '<td width="175px"><input type="text" name="first" value="'.$first.'"></td>';
         echo '<td width="175px"><input type="text" name="last" value="'.$last.'"></td>';
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
         echo '<td width="175px"><input type="text" name="first"></td>';
         echo '<td width="175px"><input type="text" name="last"></td>';
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
// print current food options and form to add more
function add_food($link)
{
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
   $calendar.='<table><td><td>Add New Food</td><td><input type="text" name="food"><input type="submit" name="addfood" value="Add"></td></table>';
   $calendar.='</form>';
   return $calendar;
}
// print all upcoming events with food and attendance
function print_dinners($link)
{
   $sql = "select * from (select f.name,e.family_id,d.month,d.day,d.year,str_to_date(concat(concat(month,'/',day),'/',year),'%m/%d/%Y') dt from date d join event e on d.date_id=e.date_id join family f on f.family_id=e.family_id) as a where a.dt>curdate()";
   logger($link,$sql);
   $data = mysqli_query($link,$sql);
   $i=0;
   while (list($fam_name[$i],$fam_id[$i],$month[$i],$day[$i],$year[$i],$date[$i])=mysqli_fetch_row($data)) {
      $i++;
   }

   for ($j=0;$j<$i;$j++)
   {
      echo '<table border="1"><tr><td colspan="2">';
      echo '<b>'.date("F",strtotime($date[$j])).' '.$year[$j].'</b><br><b>Host:</b> '.$fam_name[$j].'<br><b>Date:</b>'.date("D",strtotime($date[$j])).', '.$month[$j].' '.$day[$j].'<br><b>Time:</b> 4pm</td></tr>';
      echo '<tr><td valign="top" width="50%"><table border="1"><tr><td colspan="2"><b>Dishes</b></td></tr>';
      echo '<tr><td valign="top"><b>Martinopoulos</b></td><td>main course</td></tr>';
      echo '<tr><td valign="top"><b>Jefferson Martin Family</b></td><td>pasta salad</td></tr>';
      echo '<tr><td valign="top"><b>Bill Martin Family</b></td><td>white wine</td></tr>';
      echo '<tr><td valign="top"><b>Eide Family</b></td><td>veggie tray</td></tr>';
      echo '</table></td><td valign="top">';
      echo '<table border="1><tr><td colspan="2"><b>Attending</b></td></tr>';
      echo '<tr><td valign="top"><b>Martinopoulos</b></td><td>Rob<br>Steph<br>Stevie<br>Bobby<br>Teddy<br></td></tr>';
      echo '<tr><td valign="top"><b>Jefferson Martin Family</b></td><td>Patrick<br><Rebecca<br><Finn<br>Brigit</td></tr>';
      echo '<tr><td valign="top"><b>Bill Martin Family</b></td><td>Bill<br>Maripat</td></tr>';
      echo '<tr><td valign="top"><b>Eide Family</b></td><td>Mike<br>Jordan</td></tr>';
      echo '</table></td></tr></table><br>';
   }
}
?>