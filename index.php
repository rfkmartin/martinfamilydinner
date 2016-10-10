<?php
print "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n";
print "<html><head>\n";
print "<title>Martin Family Dinner</title>\n";
print "<link href=\"/martinfamilydinner/style.css\" rel=\"stylesheet\" type=\"text/css\">\n";
print "</head>\n";

require_once("login.php");
include("month.php");
date_default_timezone_set("America/Chicago");
// header
//login
//calendar

//header w/ user&logout
//welcome user
//links to family editing
//calendar
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
print "    <table border='1'><tr><td colspan='2'>\n";
print "    <b>May 2016</b><br><b>Host:</b> Martinopoulos(<a href=\"https://goo.gl/maps/1vDoRby74AB2\">map</a>)<br><b>Date:</b> Sun, May 29<br><b>Time:</b> 4pm</td></tr>\n ";
print "    <tr><td valign='top' width=\"50%\"><table border='1'><tr><td colspan='2'><b>Dishes</b></td></tr>\n";
print "    <tr><td valign='top'><b>Martinopoulos</b></td><td>main course</td></tr>\n";
print "    <tr><td valign='top'><b>Jefferson Martin Family</b></td><td>pasta salad</td></tr>\n";
print "    <tr><td valign='top'><b>Bill Martin Family</b></td><td>white wine</td></tr>\n";
print "    <tr><td valign='top'><b>Eide Family</b></td><td>veggie tray</td></tr>\n";
print "    </table></td><td valign='top'>\n";
print "    <table border='1'><tr><td colspan='2'><b>Attending</b></td></tr>\n";
print "    <tr><td valign='top'><b>Martinopoulos</b></td><td>Rob<br>Steph<br>Stevie<br>Bobby<br>Teddy<br></td></tr>\n";
print "    <tr><td valign='top'><b>Jefferson Martin Family</b></td><td>Patrick<br><Rebecca<br><Finn<br>Brigit</td></tr>\n";
print "    <tr><td valign='top'><b>Bill Martin Family</b></td><td>Bill<br>Maripat</td></tr>\n";
print "    <tr><td valign='top'><b>Eide Family</b></td><td>Mike<br>Jordan</td></tr>\n";
print "    </table></td></tr></table>\n";
print "    <table border='1'><tr><td colspan='2'>\n";
print "    <b>June 2016</b><br><b>Host:</b> Jefferson Martin(<a href=\"https://goo.gl/maps/1vDoRby74AB2\">map</a>)<br><b>Date:</b> Sun, May 29<br><b>Time:</b> 4pm</td></tr>\n ";
print "    <tr><td valign='top' width=\"50%\"><table border='1'><tr><td colspan='2'><b>Dishes</b></td></tr>\n";
print "    <tr><td valign='top'><b>Jefferson Martin</b></td><td>main course</td></tr>\n";
print "    </table></td><td valign='top'>\n";
print "    <table border='1'><tr><td colspan='2'><b>Attending</b></td></tr>\n";
print "    <tr><td valign='top'><b>Jefferson Martin</b></td><td>Patrick<br>Rebecca<br>Finn<br>Brigit<br></td></tr>\n";
print "    </table></td></tr></table>\n";
print "    </td>\n";
print "    <td>\n";


echo draw_calendar($link,4,2016,6);

print "</td></tr></table>\n";
print "</body>\n";
print "</html>\n";

?>