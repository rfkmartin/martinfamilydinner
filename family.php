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
function familymem_table($link,$selected){

   $i=0;
   // mysql> select person_id,first_name,last_name,month,day,year from person join family on person.family_id=family.family_id join date on person.birthday_id=date.date_id where person.family_id=4;

   $sql = "select person_id,first_name,last_name,day,month,year from person join family on person.family_id=family.family_id join date on person.birthday_id=date.date_id where person.family_id=".$selected;
   $data = mysqli_query($link,$sql);
   while (list($id[$i],$fname[$i],$lname[$i],$day[$i],$month[$i],$year[$i])=mysqli_fetch_row($data)) {
      $i++;
   }
	$calendar = '<table border="1" cellpadding="1" cellspacing="1" class="family_table">';

   $calendar.= '<tr><td>Name</td><td>Birthday</td></tr>';

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
function family_ddl($link,$selected){
   echo '<form action = "" method = "post"><select name="family">';

   $sql = "select family_id,name from family";
   $data = mysqli_query($link,$sql);
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

/* print form for new families */
function family_addnew($link,$selected){
   echo '<form action = "" method = "post">';

   if ($selected>=0)
   {
      $sql = "select family_id,name,line1,line2,city,state,zip,phone,day,month,year from family join address on family.address_id=address.address_id left join date on family.anniversary_id=date.date_id where family_id=".$selected;
      $data = mysqli_query($link,$sql);
      echo $sql;
      list($family_id,$name,$line1,$line2,$city,$state,$zip,$phone,$day,$month,$year) = mysqli_fetch_row($data);
      echo "name: ".$name;
//      echo "name2: "$data['name'];
      echo '<table border="1"<tr><td>Family Name:</td><td><input type="text" name="familyname" value="'.$name.'"></td></tr>';
      echo '<tr><td>Address Line 1:</td><td><input type="text" name="address1" value="'.$line1.'"></td></tr>';
      echo '<tr><td>Address Line 2:</td><td><input type="text" name="address2" value="'.$line2.'"></td></tr>';
      echo '<tr><td>City:</td><td><input type="text" name="address1" value="'.$city.'"></td></tr>';
      echo '<tr><td>State:</td><td><input type="text" name="address2" value="'.$state.'"></td></tr>';
      echo '<tr><td>ZIP:</td><td><input type="text" name="address1" value="'.$zip.'"></td></tr>';
      echo '<tr><td>Phone:</td><td><input type="text" name="address2" value="'.$phone.'"></td></tr>';
      echo '<tr><td colspan="2" align="center"><input type="submit" name="addnew" value="Add New"></td></tr></table>';
   }
//-print "    <tr><td>City:</td><td><input type=\"text\" name=\"city\"></td></tr>\n";
//-print "    <tr><td>State:</td><td><input type=\"text\" name=\"state\"></td></tr>\n";
//-print "    <tr><td>ZIP:</td><td><input type=\"text\" name=\"zip\"></td></tr>\n";
//-print "    <tr><td>Phone:</td><td><input type=\"text\" name=\"phone\"></td></tr>\n";
//-print "    <tr><td>Anniversary:</td><td><input type=\"date\" name=\"anniv\"></td></tr>\n";
//   if ($selected >0
}

//-print "    <form action = \"\" method = \"post\">\n";
//-print "    <table border='1'><tr><td>Family Name:</td><td><input type=\"text\" name=\"familyname\"></td></tr>\n";
//-print "    <tr><td>Address Line 1:</td><td><input type=\"text\" name=\"address1\"></td></tr>\n";
//-print "    <tr><td>Address Line 2:</td><td><input type=\"text\" name=\"address2\"></td></tr>\n";
//-print "    <tr><td>City:</td><td><input type=\"text\" name=\"city\"></td></tr>\n";
//-print "    <tr><td>State:</td><td><input type=\"text\" name=\"state\"></td></tr>\n";
//-print "    <tr><td>ZIP:</td><td><input type=\"text\" name=\"zip\"></td></tr>\n";
//-print "    <tr><td>Phone:</td><td><input type=\"text\" name=\"phone\"></td></tr>\n";
//-print "    <tr><td>Anniversary:</td><td><input type=\"date\" name=\"anniv\"></td></tr>\n";
//-print "    <tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"addnew\" value=\"Add New\"></td></tr></table>\n";
//-print "    </form>\n";

?>