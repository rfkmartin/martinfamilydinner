<?php
   $db_host = '127.0.0.1'; // don't forget to change
   $db_user = 'root';
   $db_pwd = 'Luv2Drnk';
   $database = 'mfd';
   $link = mysql_connect($db_host,$db_user,$db_pwd);
   if (!$link)
   {
      die("Can't connect to database");
   }

   if (!mysql_select_db($database))
   {
      die("Can't select database");
   }
?>