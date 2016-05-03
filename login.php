<?php
   $db_host = '192.168.10.147'; // don't forget to change
   $db_user = 'root';
   $db_pwd = 'ub6ib9';
   $database = 'mfd';
   $link = mysqli_connect($db_host,$db_user,$db_pwd);
   if (!$link)
   {
      die("Can't connect to database");
   }
   if (!mysqli_select_db($link,$database))
   {
      die("Can't select database");
   }
?>