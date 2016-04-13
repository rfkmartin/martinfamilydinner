<?php
include("login.php");

/* draws a calendar */
function draw_calendar($month,$year,$number){

   $i=0;
   $sql = "select month,day,year,first_name,show_age from date join person on date.date_id=person.birthday_id order by month,day";
   $data = mysql_query($sql);
   while (list($mnth[$i],$day[$i],$age[$i],$name[$i],$show_age[$i])=mysql_fetch_row($data)) {
      $i++;
   }
   $j=0;
   while ($mnth[$j]<$month) $j++;
	/* draw table */
	$calendar = '<table cellpadding="0" cellspacing="0" class="month_table">';

   for ($i=0;$i<$number;$i++)
   {
      $thismonth=$month+$i;
      if ($thismonth>12)
      {
         if ($thismonth==13)
         {
            $year++;
            $j=0;
         }
         $thismonth-=12;
      }
      $calendar.= '<tr><td colspan="7" align="center" class="month_table_header">'.date('F',mktime(0,0,0,$thismonth,1,$year)).' '.	$year.'</td></tr><tr>';

      /* days and weeks vars now ... */
      $running_day = date('w',mktime(0,0,0,$thismonth,1,$year));
      $days_in_month = date('t',mktime(0,0,0,$thismonth,1,$year));
      $days_in_this_week = 1;
      $day_counter = 0;
      $dates_array = array();

      /* print "blank" days until the first of the current week */
      for($x = 0; $x < $running_day; $x++)
      {
         $calendar.= '<td width="30px" align="center"></td>';
         $days_in_this_week++;
      }

      /* keep going with days.... */
      for($list_day = 1; $list_day <= $days_in_month; $list_day++)
      {
         $calendar.= '<td width="30px" align="center"';
         /* add in the day number */
         //while ($day[$i]<$list_day) $i++;
         //<td width="30px" align="center" class="event"><div class="eventdata">5<span class="eventdatatext">Kate(40)<br>John(46)<br>Sarah(3)</span></div></td>
         if ($day[$j]==$list_day&&$thismonth==$mnth[$j])
         {
            $age_visible="";
            $age[$j]=$year-$age[$j];
            if ($show_age[$j])
            {
               $age_visible="(".$age[$j].")";
            }
            $calendar.=" class='event'><div class='eventdata'>".$list_day."<span class='eventdatatext'>";
            $calendar.= $name[$j++].$age_visible."<br>";
            while ($day[$j]==$list_day) {
               $age_visible="";
               $age[$j]=$year-$age[$j];
               if ($show_age[$j])
               {
                  $age_visible="(".$age[$j].")";
               }
               $calendar.= $name[$j++].$age_visible."<br>";
            }
            $calendar.="</span></div>";
         }
         else
         {
            $calendar.= ">".$list_day;
         }

			/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
                  // select month,day,2016-year,first_name from date join person on date.date_id=person.birthday_id order by month,day;
			//$calendar.= str_repeat('<p> </p>',2);

         $calendar.= '</td>';
         if($running_day == 6)
         {
            $calendar.= '</tr>';
            if(($day_counter+1) != $days_in_month)
            {
               $calendar.= '<tr>';
            }
            $running_day = -1;
            $days_in_this_week = 0;
         }
         $days_in_this_week++; $running_day++; $day_counter++;
      }

      /* finish the rest of the days in the week */
      if($days_in_this_week < 8)
      {
         for($x = 1; $x <= (8 - $days_in_this_week); $x++)
         {
            $calendar.= '<td width="30px" align="center"></td>';
         }
      }

      /* final row */
      $calendar.= '</tr>';
   }
	/* end the table */
	$calendar.= '</table>';

	/* all done, return result */
	return $calendar;
}

/* sample usages */
echo draw_calendar(4,2016,4);

?>