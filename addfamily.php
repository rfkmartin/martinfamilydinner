<?php
session_start();
$SID=session_id();
print "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n";
print "<html><head>\n";
print "<title>Martin Family Dinner</title>\n";
print "<link href=\"/martinfamilydinner/style.css\" rel=\"stylesheet\" type=\"text/css\">\n";
print "<script src=\"js/jquery.js\"></script>\n";
print "<script src=\"js/moment.min.js\"></script>\n";
print "<script src=\"js/combodate.js\"></script>\n";
print "</head>\n";

require_once("login.php");
include("month.php");
include("family.php");
include("logger.php");
date_default_timezone_set("America/Chicago");

//http://www.xlinesoft.com/phprunner/docs/phprunner_session_variables.htm
//http://stackoverflow.com/questions/21954384/changing-a-php-session-value-by-clicking-on-a-div

print "<body>\n";
print "<table width=\"1080\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" id=\"main_table\" class=\"main_table\">\n";
print "  <tr>\n";
print "  <td width=\"90%\" height=\"25\" align=\"left\" valign=\"top\">\n";
print "    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" id=\"header\">\n";
print "    <tr>\n";
print "    <td colspan=\"2\">\n";
print "      <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
print "        <tr><td valign=\"bottom\" class=\"table_header\"><img src=\"Martin-Irish-Crest.jpg\" height=\"150\"/>Martin Family Dinner</td>\n";
print "        <td valign=\"top\">Welcome, <span class=\"person\">Martinopoulos Family</span><br>\n";
print "        Account<br>\n";
print "        Logout<br></td></tr>\n";
print "        <tr><td colspan=\"2\" class=\"menu1\">Home | My Family | RSVP | Address Book</td></tr>\n";
print "      </table>\n";
print "    </td></tr>\n";
print "    <tr><td width=\"81%\" valign=\"top\" align=\"center\">\n";

$selected_family=-1;
if (empty($_SESSION['user']))
{
   echo 'nobody home<br><br>';
}
if (isset($_POST['delete']))
{
   echo 'do i need to delete a family? what about orphaned records?<br><br>';
}
if (isset($_POST['addfamily']))
{
   $name = $_POST['familyname'];
   $line1 = $_POST['line1'];
   $line2 = $_POST['line2'];
   $city = $_POST['city'];
   $state = $_POST['state'];
   $zip = $_POST['zip'];
   $phone =  $_POST['phone'];
   $anniv = $_POST['anniv'];
   $annivY = date('Y', strtotime($_POST['anniv']));
   $annivm = date('m', strtotime($_POST['anniv']));
   $annivd = date('d', strtotime($_POST['anniv']));
   // insert address
   $sql = "insert into address(line1,line2,city,state,zip) values (\"".$line1."\",\"".$line2."\",\"".$city."\",\"".$state."\",\"".$zip."\");";
   logger($link,$SID,1,$sql);
   if (!empty($_POST['city']))
   {
      if (mysqli_query($link,$sql))
      {
         $address_id = mysqli_insert_id($link);
      }
      else
      {
         logger($link,$SID,1,"Error inserting record: " . mysqli_error($link));
      }
   }
   else
   {
      $address_id="NULL";
   }
   if (!empty($_POST['anniv']))
   {
      // insert anniversary
      $sql = "insert into date(day,month,year) values (\"".$annivd."\",\"".$annivm."\",\"".$annivY."\");";
      logger($link,$SID,1,$sql);
      if (mysqli_query($link,$sql))
      {
         $anniv_id = mysqli_insert_id($link);
      }
      else
      {
         logger($link,$SID,1,"Error inserting record: " . mysqli_error($link));
      }
   }
   else
   {
      $anniv_id="NULL";
   }
   $sql = "insert into family (name,phone,anniversary_id,address_id) values (\"".$name."\",\"".$phone."\",".$anniv_id.",".$address_id.");";
   echo $sql;
   logger($link,$SID,1,$sql);
   if (mysqli_query($link,$sql))
   {
      $selected_family = mysqli_insert_id($link);
   }
   else
   {
      logger($link,$SID,1,"Error inserting record: " . mysqli_error($link));
   }
}
//~ if (isset($_POST['updatefamily']))
//~ {
   //~ $selected_family=$_POST['family_id'];
   //~ $sql = "select address_id,anniversary_id from family where family_id=".$selected_family;
   //~ $data = mysqli_query($link,$sql);
   //~ list($address_id,$anniversary_id)=mysqli_fetch_row($data);
   //~ if (empty($address_id))
   //~ {
      //~ $sql = "insert into address(line1,line2,city,state,zip) values ('".$_POST['line1']."','".$_POST['line2']."','".$_POST['city']."', '".$_POST['state']."', '".$_POST['zip']."')";
      //~ if (mysqli_query($link,$sql))
      //~ {
         //~ $address_id = mysqli_insert_id($link);
      //~ }
   //~ }
   //~ else
   //~ {
      //~ $sql = "update address set line1='".$_POST['line1']."',line2='".$_POST['line2']."',city='".$_POST['city']."',state='".$_POST['state']."',zip='".$_POST['zip']."' where address_id=".$address_id."";
      //~ if (!mysqli_query($link,$sql))
      //~ {
         //~ echo "Error updating record: " . mysqli_error($link);
      //~ }
   //~ }

   //~ $annivY = date('Y', strtotime($_POST['anniv']));
   //~ $annivm = date('m', strtotime($_POST['anniv']));
   //~ $annivd = date('d', strtotime($_POST['anniv']));
   //~ if (empty($anniversary_id))
   //~ {
      //~ $sql = "insert into date(day,month,year) values ('".$annivd."','".$annivm."','".$annivY."')";
      //~ if (!empty($_POST['anniv']))
      //~ {
         //~ if (mysqli_query($link,$sql))
         //~ {
            //~ $anniversary_id = mysqli_insert_id($link);
         //~ }
      //~ }
   //~ }
   //~ else
   //~ {
      //~ $sql = "update date set day='".$annivd."',month='".$annivm."',year='".$annivY."' where date_id=".$anniversary_id."";
      //~ echo $sql;
      //~ if (!mysqli_query($link,$sql))
      //~ {
         //~ echo "Error updating record: " . mysqli_error($link);
      //~ }
   //~ }
   //~ if (!empty($address_id))
   //~ {
      //~ $add_address=",address_id='".$address_id."'";
   //~ }
   //~ if (!empty($anniversary_id))
   //~ {
      //~ $add_anniv=",anniversary_id='".$anniversary_id."'";
   //~ }
   //~ if (empty($_POST['phone']))
   //~ {
      //~ $_POST['phone']="null";
   //~ }
   //~ $sql = "update family set name='".$_POST['familyname']."'".$add_address.$add_anniversary.",phone='".$_POST['phone']."' where family_id=".$selected_family."";
   //~ echo $sql;
   //~ if (!mysqli_query($link,$sql))
   //~ {
      //~ echo "Error updating record: " . mysqli_error($link);
   //~ }
//~ }
if (isset($_POST['editmem']))
{
   echo 'in editmem';
   $selected_family = $_POST['family_id'];
   $first = $_POST['first'];
   $last = $_POST['last'];
   $show = $_POST['show'];
   $bday = $_POST['bday'];
   $family_id = $_POST['family_id'];
   $member_id = $_POST['person_id'];
   $bdayY = date('Y', strtotime($_POST['bday']));
   $bdaym = date('m', strtotime($_POST['bday']));
   $bdayd = date('d', strtotime($_POST['bday']));
   echo $first." l ".$last." s ".$show." b ".$bday." f ".$family_id." m ".$member_id." y ".$bdayY." m ".$bdaym." d ".$bdayd;
   // insert or edit bday
   if (empty($member_id))
   {
      //todo
      echo 'new member';
      $sql = "insert into date(day,month,year) values ('".$annivd."','".$annivm."','".$annivY."')";
      logger($link,$SID,1,$sql);
      if (!empty($_POST['anniv']))
      {
         if (mysqli_query($link,$sql))
         {
            $anniversary_id = mysqli_insert_id($link);
         }
         else
         {
            logger($link,$SID,1,"Error inserting record: " . mysqli_error($link));
         }
      }
   }
   else
   {
      //todo
      echo "current member";
      $sql = "update date set day='".$bdayd."',month='".$bdaym."',year='".$bdayY."' where date_id=".$anniversary_id."";
      logger($link,$SID,1,$sql);
      if (!mysqli_query($link,$sql))
      {
         echo "Error updating record: " . mysqli_error($link);
      }
   }
   $sql = "insert into address(line1,line2,city,state,zip) values (\"".$line1."\",\"".$line2."\",\"".$city."\",\"".$state."\",\"".$zip."\");";
   logger($link,$SID,1,$sql);
   if (!empty($_POST['city']))
   {
      if (mysqli_query($link,$sql))
      {
         $address_id = mysqli_insert_id($link);
      }
   }
   else
   {
      $address_id="NULL";
   }
   if (!empty($_POST['anniv']))
   {
      // insert anniversary
      $sql = "insert into date(day,month,year) values (\"".$annivd."\",\"".$annivm."\",\"".$annivY."\");";
      logger($link,$SID,1,$sql);
      if (mysqli_query($link,$sql))
      {
         $anniv_id = mysqli_insert_id($link);
      }
   }
   else
   {
      $anniv_id="NULL";
   }
   $sql = "insert into family (name,phone,anniversary_id,address_id) values (\"".$name."\",\"".$phone."\",".$anniv_id.",".$address_id.");";
   logger($link,$SID,1,$sql);
   echo $sql;
   if (mysqli_query($link,$sql))
   {
      $selected_family = mysqli_insert_id($link);
   }
   else
   {
      echo "Error inserting record: " . mysqli_error($link);
   }
}
if (isset($_POST['updatefamily']))
{
   $selected_family=$_POST['family_id'];
   $sql = "select address_id,anniversary_id from family where family_id=".$selected_family;
   $data = mysqli_query($link,$sql);
   $add_anniv="";
   $add_address="";
   list($address_id,$anniversary_id)=mysqli_fetch_row($data);
   if (empty($address_id))
   {
      $sql = "insert into address(line1,line2,city,state,zip) values ('".$_POST['line1']."','".$_POST['line2']."','".$_POST['city']."', '".$_POST['state']."', '".$_POST['zip']."')";
      logger($link,$SID,1,$sql);
      if (mysqli_query($link,$sql))
      {
         $address_id = mysqli_insert_id($link);
      }
      else
      {
         logger($link,$SID,1,"Error inserting record: " . mysqli_error($link));
      }
   }
   else
   {
      $sql = "update address set line1='".$_POST['line1']."',line2='".$_POST['line2']."',city='".$_POST['city']."',state='".$_POST['state']."',zip='".$_POST['zip']."' where address_id=".$address_id."";
      logger($link,$SID,1,$sql);
      if (!mysqli_query($link,$sql))
      {
         logger($link,$SID,1,"Error updating record: " . mysqli_error($link));
      }
   }

   if (!empty($_POST['anniv']))
   {
      $annivY = date('Y', strtotime($_POST['anniv']));
      $annivm = date('m', strtotime($_POST['anniv']));
      $annivd = date('d', strtotime($_POST['anniv']));
      if (empty($anniversary_id))
      {
         $sql = "insert into date(day,month,year) values ('".$annivd."','".$annivm."','".$annivY."')";
         logger($link,$SID,1,$sql);
         if (!empty($_POST['anniv']))
         {
            if (mysqli_query($link,$sql))
            {
               $anniversary_id = mysqli_insert_id($link);
            }
            else
            {
               logger($link,$SID,1,"Error inserting record: " . mysqli_error($link));
            }
         }
      }
      else
      {
         $sql = "update date set day='".$annivd."',month='".$annivm."',year='".$annivY."' where date_id=".$anniversary_id."";
         logger($link,$SID,1,$sql);
         if (!mysqli_query($link,$sql))
         {
            echo "Error updating record: " . mysqli_error($link);
         }
      }
   }
   if (!empty($address_id))
   {
      $add_address=",address_id='".$address_id."'";
   }
   if (!empty($anniversary_id))
   {
      $add_anniv=",anniversary_id='".$anniversary_id."'";
   }
   $sql = "update family set name='".$_POST['familyname']."'".$add_address.$add_anniv.",phone='".$_POST['phone']."' where family_id=".$selected_family."";
   logger($link,$SID,1,$sql);
   if (!mysqli_query($link,$sql))
   {
      logger($link,$SID,1,"Error updating record: " . mysqli_error($link));
   }
}
if (isset($_POST['change']))
{
   $selected_family=$_POST['family'];
}

echo family_ddl($link,$selected_family);
echo "<br><br>";
echo family_addnew($link,$selected_family);
echo "<br><br>";
echo family_table($link);
echo "<br><br>";
echo familymem_table($link,$selected_family);
echo "<br><br>";
echo member_addnew($link,$selected_family);
print "    </td>\n";
print "    <td valign=\"top\">\n";

echo draw_calendar($link,4,2016,6);

print "</td></tr></table>\n";
print "</body>\n";
print "</html>\n";

?>