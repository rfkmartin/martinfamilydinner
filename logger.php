<?php

$OUTFILE="mfd.log";

function logger($link,$message)
{
   //echo '$session: '.$session;
   //echo '$user: '.$user;
   //echo '$sql: '.mysqli_real_escape_string($link,$message);
   $user=1;//$_SESSION['user']
   $sql = "insert into logger (msg_dt,session_id,user_id,message) values (now(),\"".$_SESSION['SID']."\",".$user.",\"".mysqli_real_escape_string($link,$message)."\")";
   //echo 'realsql: '.$sql;
   if (mysqli_query($link,$sql))
   {
      //echo ' after logger insert ';
   }
   else
   {
         echo "Error inserting logger: " . mysqli_error($link);
   }

   // Format the date and time
   $date = date("Y-m-d H:i:s", time());

   //if ($fd = @fopen($OUTFILE, "a"))
   //{
   //   $result = fputcsv($fd, array($date, $session, $user, $message));
   //   fclose($fd);
   //}
   //else
   //{
   //   echo ' had trouble opening file ';
   //}
}

?>