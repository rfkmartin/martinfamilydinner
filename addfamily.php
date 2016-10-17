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

echo family_ddl($link);
echo "<br><br>";
echo family_addnew($link);
echo "<br><br>";
echo family_table($link);
echo "<br><br>";
echo member_addnew($link);
echo "<br><br>";
echo add_food($link);
echo "<br><br>";
echo add_food_to_event($link);
echo "<br><br>";
echo add_attendance($link);
echo "<br><br>";
echo bringing($link);
echo "<br><br>";
echo add_events($link);
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