<?php

$OUTFILE="/var/log/mfd.log";

function logger($link,$session,$user,$message)
{
   echo ' before logger insert ';
   echo '$session: '.$session;
   echo '$user: '.$user;
   echo '$sql: '.mysqli_real_escape_string($link,$message);
   $sql = "insert into logger (msg_dt,user_id,session_id,message) values (now(),\"".$session."\",\"".$user."\",\"".mysqli_real_escape_string($link,$message)."\")";
   if (mysqli_query($link,$sql))
   {
      echo ' after logger insert ';
   }
   else
   {
         echo "Error inserting logger: " . mysqli_error($link);
   }

   // Format the date and time
   $date = date("Y-m-d H:i:s", time());

   if ($fd = @fopen($OUTFILE, "a"))
   {
      $result = fputcsv($fd, array($date, $session, $user, $message));
      fclose($fd);
   }
   else
   {
   echo ' had trouble opening file ';
   }
}

?>