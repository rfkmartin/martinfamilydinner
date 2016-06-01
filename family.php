<?php
/* print table of familes */
function family_table($link){

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
         $calendar.='<td align=\"right\">'.$line1[$x].'<br>';
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
      $calendar.='<td>'.$phone[$x].'</td>';
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

/* print table of family members */
function familymem_table($link,$selected){

   $i=0;

   $sql = "select person_id,first_name,last_name,day,month,year from person join family on person.family_id=family.family_id join date on person.birthday_id=date.date_id where person.family_id=".$selected;
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

/* print dropdown list of familes */
function family_ddl($link,$selected){
   echo '<form action = "" method = "post"><select name="family">';

   $sql = "select family_id,name from family";
   $data = mysqli_query($link,$sql);
   echo '<option value="-1">Add New</option>';
   while(list($family_id,$name) = mysqli_fetch_row($data))
   {
      echo '<option value="'.$family_id.'"';
      if ($family_id==$selected)
      {
         echo ' selected';
      }
      echo '>'.$name.'</option>'."\n";
   }
   echo '</select><input type="submit" name="change" value="Change Family"></form>';
}

/* print form to add/edit families */
function family_addnew($link,$selected){
   echo '<form action = "" method = "post">';

   if ($selected>=0)
   {
      $sql = "select family_id,name,line1,line2,city,state,zip,phone,day,month,year from family join address on family.address_id=address.address_id left join date on family.anniversary_id=date.date_id where family_id=".$selected;
      $data = mysqli_query($link,$sql);
      list($family_id,$name,$line1,$line2,$city,$state,$zip,$phone,$day,$month,$year) = mysqli_fetch_row($data);
      echo '<table border="1"<tr><td>Family Name:</td><td><input type="text" name="familyname" value="'.$name.'"><input type="hidden" name="family_id" value="'.$selected.'"></td></tr>';
      echo '<tr><td>Address Line 1:</td><td><input type="text" name="line1" value="'.$line1.'"></td></tr>';
      echo '<tr><td>Address Line 2:</td><td><input type="text" name="line2" value="'.$line2.'"></td></tr>';
      echo '<tr><td>City:</td><td><input type="text" name="city" value="'.$city.'"></td></tr>';
      echo '<tr><td>State:</td><td><input type="text" name="state" value="'.$state.'"></td></tr>';
      echo '<tr><td>ZIP:</td><td><input type="text" name="zip" value="'.$zip.'"></td></tr>';
      echo '<tr><td>Phone:</td><td><input type="text" name="phone" value="'.$phone.'"></td></tr>';
      if (empty($year))
      {
         echo '<tr><td>Anniversary:</td><td><input type="text" id="date" name="anniv" value="" data-format="DD-MM-YYYY" data-template="D MMM YYYY"></td></tr>';
         //<input name="date_of_birth" value="15-05-1984" data-format="DD-MM-YYYY" data-template="D MMM YYYY">
      }
      else
      {
         //echo '<tr><td>Anniversary:</td><td><input type="date" name="anniv" value="'.$year.'-'.sprintf('%02d',$month).'-'.sprintf('%02d',$day).'"></td></tr>';
         echo '<tr><td>Anniversary:</td><td><input type="text" id="date" name="anniv" data-format="DD-MM-YYYY" data-template="D MMM YYYY" value="'.sprintf('%02d',$day).'-'.sprintf('%02d',$month).'-'.$year.'"></td></tr>';
      }
      echo '<tr><td colspan="2" align="center"><input type="submit" name="updatefamily" value="Update"></td></tr></table>';
      echo '<script>$(function(){$(\'#date\').combodate();});</script>';
   }
   else
   {
      echo '<table border="1"<tr><td>Family Name:</td><td><input type="text" name="familyname"></td></tr>';
      echo '<tr><td>Address Line 1:</td><td><input type="text" name="line1"></td></tr>';
      echo '<tr><td>Address Line 2:</td><td><input type="text" name="line2"></td></tr>';
      echo '<tr><td>City:</td><td><input type="text" name="city"></td></tr>';
      echo '<tr><td>State:</td><td><input type="text" name="state"></td></tr>';
      echo '<tr><td>ZIP:</td><td><input type="text" name="zip"></td></tr>';
      echo '<tr><td>Phone:</td><td><input type="text" name="phone"></td></tr>';
      //echo '<tr><td>Anniversary:</td><td><input type="date" name="anniv"></td></tr>';
      echo '<tr><td>Anniversary:</td><td><input type="text" id="date" name="anniv" data-format="DD-MM-YYYY" data-template="D MMM YYYY"></td></tr>';
      echo '<tr><td colspan="2" align="center"><input type="submit" name="addfamily" value="Add New"></td></tr></table>';
      echo '<script>$(function(){$(\'#date\').combodate();});</script>';
   }
   echo '</form>';
}

/* print form to add/edit members */
function member_addnew($link,$selected){

   if ($selected>=0)
   {
      $sql = "select person_id,first_name,last_name,show_age,day,month,year from person left join date on person.birthday_id=date.date_id where family_id=".$selected;
      $data = mysqli_query($link,$sql);
      echo '<table border="1"<tr><td>First Name</td><td>Last Name</td><td>Show Age?</td><td>Birthdate</td><td></td></tr>';
      while(list($id,$first,$last,$show,$day,$month,$year) = mysqli_fetch_row($data))
      {
         if ($show==1)
         {
            $show_age=' checked';
         }
         echo '<form action = "" method = "post">';
         echo '<td><input type="text" name="first" value="'.$first.'"></td>';
         echo '<td><input type="text" name="last" value="'.$last.'"></td>';
         echo '<td><input type="checkbox" name="show"'.$show_age.'></td>';
         //echo '<td><input type="checkbox" name="show" value="Show Age?"'.$show_age.'></td>';
         echo '<td><input type="date" name="bday" value="'.$year.'-'.sprintf('%02d',$month).'-'.sprintf('%02d',$day).'"></td>';
         echo '<td><input type="submit" name="editmem" value="Edit"><input type="hidden" name="family_id" value="'.$selected.'"><input type="hidden" name="person_id" value="'.$id.'"></td></tr>';
         echo '</form>';
      }
         echo '<form action = "" method = "post">';
         echo '<td><input type="text" name="first"></td>';
         echo '<td><input type="text" name="last"></td>';
         echo '<td><input type="checkbox" name="show"></td>';
         echo '<td><input type="date" name="bday"></td>';
         echo '<td><input type="submit" name="editmem" value="Add New"><input type="hidden" name="family_id" value="'.$selected.'"></td></tr>';
         echo '</form>';
         echo '</table>';
   }
}
?>