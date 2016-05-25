<?php

/* print table of familes */
function family_table($link){

   $i=0;
   $sql = "select family_id,name,phone,day,month,year from family left join date on anniversary_id=date_id order by name";
   $data = mysqli_query($link,$sql);
   while (list($id[$i],$name[$i],$phone[$i],$day[$i],$month[$i],$year[$i])=mysqli_fetch_row($data)) {
      $i++;
   }
	$calendar = '<table border="1" cellpadding="1" cellspacing="1" class="family_table">';

   $calendar.= '<tr><td>Name</td><td>Phone</td><td>Anniversary</td></tr>';

   for($x=0;$x<$i;$x++)
   {
      $calendar.='<tr><td>'.$name[$x].'</td>';
      $calendar.='<td>'.$phone[$x].'</td>';
      if (empty($day[$x]))
      {
         $calendar.='<td></td></tr>';
      }
      else
      {
         $calendar.='<td>'.$month[$x].'/'.$day[$x].'/'.$year[$x].'</td></tr>';
      }
   }
   $calendar.='</table>';

	return $calendar;
}


/* print table of familes */
function familymem_table($link){

   $i=0;
   // mysql> select person_id,first_name,last_name,month,day,year from person join family on person.family_id=family.family_id join date on person.birthday_id=date.date_id where person.family_id=4;

   $sql = "select person_id,first_name,last_name,day,month,year from person join family on person.family_id=family.family_id join date on person.birthday_id=date.date_id where person.family_id=7";
   $data = mysqli_query($link,$sql);
   while (list($id[$i],$fname[$i],$lname[$i],$day[$i],$month[$i],$year[$i])=mysqli_fetch_row($data)) {
      $i++;
   }
	$calendar = '<table border="1" cellpadding="1" cellspacing="1" class="family_table">';

   $calendar.= '<tr><td>Name</td><td>Anniversary</td></tr>';

   for($x=0;$x<$i;$x++)
   {
      $calendar.='<tr><td>'.$fname[$x].' '.$lname[$x].'</td>';
      if (empty($day[$x]))
      {
         $calendar.='<td></td></tr>';
      }
      else
      {
         $calendar.='<td>'.$month[$x].'/'.$day[$x].'/'.$year[$x].'</td></tr>';
      }
   }
   $calendar.='</table>';

	return $calendar;
}

/* print table of familes */
function family_ddl($link){
   echo '<form><select name="family">';

   $sql = "select family_id,name from family";
   $data = mysqli_query($link,$sql);
   while(list($family_id,$name) = mysqli_fetch_row($data))
   {
      echo '<option value="'.$family_id.'"';
      if ($family_id==7)
      {
         echo ' selected';
      }
      echo '>'.$name.'</option>'."\n";
   }
   echo '</select></form>';
}
?>