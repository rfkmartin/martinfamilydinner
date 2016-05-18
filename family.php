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
         $calendar.='<td>&nbsp;</td></tr>';
      }
      else
      {
         $calendar.='<td>'.$month[$x].'/'.$day[$x].'/'.$year[$x].'</tr></td>';
      }
   }
   $calendar.='</table>';

	return $calendar;
}
?>