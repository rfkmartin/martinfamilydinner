<?php
session_start();
$_SESSION['SID']=session_id();
print "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n";
print "<html><head>\n";
print "<title>Martin Family Dinner</title>\n";
print "<link href=\"/martinfamilydinner/style.css\" rel=\"stylesheet\" type=\"text/css\">\n";
print "<script src=\"js/jquery.js\" type=\"text/javascript\"></script>\n";
print "<script src=\"js/moment.min.js\" type=\"text/javascript\"></script>\n";
print "<script src=\"js/combodate.js\" type=\"text/javascript\"></script>\n";
print "</head>\n";

require_once("login.php");
include("month.php");
include("family.php");
include("logger.php");
$SHOW_MONTHS=24;
date_default_timezone_set("America/Chicago");

//todo
// new login if not definer user
// format phone numbers format options(put in user table)
// find orphaned dates: select * from date d left join family f on d.date_id=f.anniversary_id left join person p on p.birthday_id=d.date_id where f.family_id is null and p.person_id is null;

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
print "        <tr><td valign=\"bottom\" class=\"table_header\"><img src=\"Martin-Irish-Crest.jpg\" height=\"150\" alt=\"Martin Family Crest\">Martin Family Dinner</td>\n";
print "        <td valign=\"top\">Welcome, <span class=\"person\">Martinopoulos Family</span><br>\n";
print "        Account<br>\n";
print "        Logout<br></td></tr>\n";
print "        <tr><td colspan=\"2\" class=\"menu1\">Home | My Family | RSVP | Address Book</td></tr>\n";
print "      </table>\n";
print "    </td></tr>\n";
print "    <tr><td width=\"81%\" valign=\"top\" align=\"center\">\n";

if (!empty($_SESSION['user']))
{
   echo 'Welcome, '.$_SESSION['user'];
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
   logger($link,$sql);
   if (!empty($_POST['city']))
   {
      if (mysqli_query($link,$sql))
      {
         $address_id = mysqli_insert_id($link);
      }
      else
      {
         logger($link,"Error inserting record: " . mysqli_error($link));
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
      logger($link,$sql);
      if (mysqli_query($link,$sql))
      {
         $anniv_id = mysqli_insert_id($link);
      }
      else
      {
         logger($link,"Error inserting record: " . mysqli_error($link));
      }
   }
   else
   {
      $anniv_id="NULL";
   }
   $sql = "insert into family (name,phone,anniversary_id,address_id) values (\"".$name."\",\"".$phone."\",".$anniv_id.",".$address_id.");";
   echo $sql;
   logger($link,$sql);
   if (mysqli_query($link,$sql))
   {
      $selected_family = mysqli_insert_id($link);
   }
   else
   {
      logger($link,"Error inserting record: " . mysqli_error($link));
   }
}
if (isset($_POST['editmem']))
{
   $first = $_POST['first'];
   $last = $_POST['last'];
   (isset($_POST['show']))?$show = $_POST['show']:$show = "off";
   $bday = $_POST['bday'];
   (isset($_POST['bday_id']))?$bday_id = $_POST['bday_id']:$bday_id = 0;
   $family_id = $_POST['family_id'];
   (isset($_POST['person_id']))?$member_id = $_POST['person_id']:$member_id = 0;
   $bdayY = date('Y', strtotime($_POST['bday']));
   $bdaym = date('m', strtotime($_POST['bday']));
   $bdayd = date('d', strtotime($_POST['bday']));
   if ($show=="on")
   {
      $show=1;
   }
   else
   {
      $show=0;
   }
   // insert or edit bday
   if ($member_id==0)
   {
      $sql = "insert into date(day,month,year) values ('".$bdayd."','".$bdaym."','".$bdayY."')";
      logger($link,$sql);
      if (mysqli_query($link,$sql))
      {
         $bday_id = mysqli_insert_id($link);
      }
      else
      {
         logger($link,"Error inserting record: " . mysqli_error($link));
      }
      $sql = "insert into person(first_name,last_name,family_id,birthday_id,show_age) values ('".$first."','".$last."','".$family_id."','".$bday_id."','".$show."')";
      logger($link,$sql);
      if (mysqli_query($link,$sql))
      {
         $bday_id = mysqli_insert_id($link);
      }
      else
      {
         logger($link,"Error inserting record: " . mysqli_error($link));
      }
   }
   else
   {
      $sql = "update person set first_name='".$first."',last_name='".$last."',show_age='".$show."' where person_id=".$member_id;
      logger($link,$sql);
      if (!mysqli_query($link,$sql))
      {
         logger($link,"Error updating record: " . mysqli_error($link));
      }
      $sql = "update date set day='".$bdayd."',month='".$bdaym."',year='".$bdayY."' where date_id=".$bday_id;
      logger($link,$sql);
      if (!mysqli_query($link,$sql))
      {
         logger($link,"Error updating record: " . mysqli_error($link));
      }
   }
}
if (isset($_POST['updatefamily']))
{
   $selected_family=$_POST['family_id'];
   $sql = "select address_id,anniversary_id from family where family_id=".$selected_family;
   $data = mysqli_query($link,$sql);
   $add_anniv="";
   $add_address="";
   $phone = preg_replace("/[^0-9]/","",$_POST['phone']);
   list($address_id,$anniversary_id)=mysqli_fetch_row($data);
   if (empty($address_id))
   {
      $sql = "insert into address(line1,line2,city,state,zip) values ('".$_POST['line1']."','".$_POST['line2']."','".$_POST['city']."', '".$_POST['state']."', '".$_POST['zip']."')";
      logger($link,$sql);
      if (mysqli_query($link,$sql))
      {
         $address_id = mysqli_insert_id($link);
      }
      else
      {
         logger($link,"Error inserting record: " . mysqli_error($link));
      }
   }
   else
   {
      $sql = "update address set line1='".$_POST['line1']."',line2='".$_POST['line2']."',city='".$_POST['city']."',state='".$_POST['state']."',zip='".$_POST['zip']."' where address_id=".$address_id."";
      logger($link,$sql);
      if (!mysqli_query($link,$sql))
      {
         logger($link,"Error updating record: " . mysqli_error($link));
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
         logger($link,$sql);
         if (!empty($_POST['anniv']))
         {
            if (mysqli_query($link,$sql))
            {
               $anniversary_id = mysqli_insert_id($link);
            }
            else
            {
               logger($link,"Error inserting record: " . mysqli_error($link));
            }
         }
      }
      else
      {
         $sql = "update date set day='".$annivd."',month='".$annivm."',year='".$annivY."' where date_id=".$anniversary_id."";
         logger($link,$sql);
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
      if (!empty($_POST['anniv']))
      {
         $add_anniv=",anniversary_id='".$anniversary_id."'";
      }
      else
      {
         $add_anniv=",anniversary_id=NULL";
      }
   }
   $sql = "update family set name='".$_POST['familyname']."'".$add_address.$add_anniv.",phone='".$phone."' where family_id=".$selected_family."";
   logger($link,$sql);
   if (!mysqli_query($link,$sql))
   {
      logger($link,"Error updating record: " . mysqli_error($link));
   }
}
if (isset($_POST['change']))
{
   if ($_POST['family']==-1)
   {
      $_SESSION['family_id']=NULL;
   }
   else
   {
      $_SESSION['family_id']=$_POST['family'];
   }
}
if (isset($_POST['addfood']))
{
   $food=$_POST['food'];
   $sql = "insert into food(food) values ('".$food."')";
   logger($link,$sql);
   if (!mysqli_query($link,$sql))
   {
      logger($link,"Error inserting record: " . mysqli_error($link));
   }
}
echo family_ddl($link);
echo "<br><br>";
echo family_addnew($link);
echo "<br><br>";
echo family_table($link);
echo "<br><br>";
echo familymem_table($link);
echo "<br><br>";
echo member_addnew($link);
echo "<br><br>";
echo add_food($link);
echo "<br><br>";
echo add_events($link,$_POST);
echo "<br><br>";
echo print_events($link,'upcoming');
echo "<br><br>";
echo print_events($link,'cancelled');
echo "<br><br>";
echo print_events($link,'past');
print "    </td>\n";
print "    <td valign=\"top\">\n";

echo draw_calendar($link,date('n'),date('Y'),$SHOW_MONTHS);

print "</td></tr></table>\n";
print "</body>\n";
print "</html>\n";

?>