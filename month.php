<?php

/* draws a calendar */
function draw_calendar($link,$month,$year,$number){

   $i=0;
   // mysql> (select month,day,year,'a' as type,name as xtra1,'' as xtra2 from date join family on date.date_id=family.anniversary_id) union (select month,day,year,'b' as type,first_name as xtra1,show_age as xtra2 from date join person on date.date_id=person.birthday_id) order by month,day;

   //           bday    anniv    event
   // xtra1     name    name     family
   // xtra2     showage NULL
   $sql = "(select month,day,year,'a' as type,name as xtra1,'' as xtra2 from date join family on date.date_id=family.anniversary_id) union (select month,day,year,'b' as type,first_name as xtra1,show_age as xtra2 from date join person on date.date_id=person.birthday_id) order by month,day,year,type";
   //select month,day,year,first_name,show_age from date join person on date.date_id=person.birthday_id order by month,day";
   $data = mysqli_query($link,$sql);
   while (list($mnth[$i],$dy[$i],$yr[$i],$type[$i],$xtra1[$i],$xtra2[$i])=mysqli_fetch_row($data)) {
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
         if ($dy[$j]==$list_day&&$thismonth==$mnth[$j])
         {
            $calendar.=" class='event'><div class='eventdata'>".$list_day."<span class='eventdatatext'>";
            if ($type[$j]=="a")
            {
               $age[$j]=$year-$yr[$j];
               $age_visible="(".$age[$j].")";
               $calendar.=$xtra1[$j].$age_visible."<br>";
            }
            if ($type[$j]=="b")
            {
               $age_visible="";
               $age[$j]=$year-$yr[$j];
               if ($xtra2[$j])
               {
                  $age_visible="(".$age[$j].")";
               }
               $calendar.= $xtra1[$j].$age_visible."<br>";
            }
            $j++;
            while ($dy[$j]==$list_day) {
               if ($type[$j]=="a")
               {
                  $age[$j]=$year-$yr[$j];
                  $age_visible="(".$age[$j].")";
                  $calendar.=$xtra1[$j].$age_visible."<br>";
               }
               if ($type[$j]=="b")
               {
                  $age_visible="";
                  $age[$j]=$year-$yr[$j];
                  if ($xtra2[$j])
                  {
                     $age_visible="(".$age[$j].")";
                  }
                  $calendar.= $xtra1[$j].$age_visible."<br>";
               }
               $j++;
            }
            $calendar.="</span></div>";
         }
         else
         {
            $calendar.= ">".$list_day;
         }

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
//require_once("login.php");
//echo draw_calendar($link,4,2016,4);
?>