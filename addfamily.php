<?php
print "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n";
print "<html><head>\n";
print "<title>Martin Family Dinner</title>\n";
print "<link href=\"/martinfamilydinner/style.css\" rel=\"stylesheet\" type=\"text/css\">\n";
print "</head>\n";

require_once("login.php");
include("month.php");
include("family.php");
date_default_timezone_set("America/Chicago");
session_start();

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
print "        <tr><td colspan=\"2\" class=\"menu1\">Home | Families | Events</td></tr>\n";
print "      </table>\n";
print "    </td></tr>\n";
print "    <tr><td width=\"81%\" valign=\"top\" align=\"center\">\n";

if (isset($_POST['addnew']))
{
   $name = $_POST['familyname'];
   $phone =  $_POST['phone'];
   //$sql = "insert into family (name,phone) values (\"".$name."\",\"".$phone."\");";
   $sql = "insert into family (name,phone) values (\"".$name."\",\"".$phone."\");";
   echo $sql;
   if (mysqli_query($link,$sql))
   {
      $id = mysqli_insert_id($link);
      echo "good family id: ".$id;
   }
   $annivY = date('Y', strtotime($_POST['anniv']));
   $annivm = date('m', strtotime($_POST['anniv']));
   $annivd = date('d', strtotime($_POST['anniv']));
   echo ' XXX '.$annivY.' XXX '.$annivm.' XXX '.$annivd;
}
if (isset($_POST['change']))
{
   echo $_POST['family'];
   $selected_family=$_POST['family'];
}

echo family_ddl($link,$selected_family);
echo "<br><br>";
echo family_addnew($link,$selected_family);
echo "<br><br>";
echo family_table($link);
echo "<br><br>";
echo familymem_table($link,$selected_family);
print "    </td>\n";
print "    <td>\n";

echo draw_calendar($link,4,2016,6);

print "</td></tr></table>\n";
print "</body>\n";
print "</html>\n";

?>