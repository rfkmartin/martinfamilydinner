<?php
print "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n";
print "<html><head>\n";
print "<title>Martin Family Dinner</title>\n";
print "<link href=\"/martinfamilydinner/style.css\" rel=\"stylesheet\" type=\"text/css\">\n";
print "</head>\n";

require_once("login.php");
include("month.php");
date_default_timezone_set("America/Chicago");

print "<body>\n";
print "<table width=\"1080\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" id=\"main_table\" class=\"main_table\">\n";
print "  <tr>\n";
print "  <td width=\"90%\" height=\"25\" align=\"left\" valign=\"top\">\n";
print "    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" id=\"header\">\n";
print "    <tr>\n";
print "    <td colspan=\"2\">\n";
print "      <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
print "        <tr><td valign=\"bottom\" class=\"table_header\"><img src=\"Martin-Irish-Crest.jpg\" height=\"150\"/>Martin Family Dinner</td>\n";
print "        <td valign=\"top\">Welcome, <span class=\"person\">you</span><br>\n";
print "        Account<br>\n";
print "        Logout<br></td></tr>\n";
print "        <tr><td colspan=\"2\" class=\"menu1\">Home | Families | Events</td></tr>\n";
print "      </table>\n";
print "    </td></tr>\n";
print "    <tr><td width=\"81%\" valign=\"top\" align=\"center\"><h2>April 2016</h2>\n";
print "    <table border=\"1\"><tr><td width=\"25%\">Hosting:</td><td>Corry Family (<a href=\"https://goo.gl/maps/1vDoRby74AB2\">map</a>)</td></tr></table>\n";
print "    <table border=\"1\"><tr><td width=\"25%\">Attending:</td><td>Jefferson Martin</td></tr>\n";
print "    <tr><td></td><td>Patrick</td></tr>\n";
print "    <tr><td></td><td>Rebecca</td></tr></table>\n";
print "    <table border=\"1\"><tr><td width=\"25%\">Dishes:</td><td></td></tr>\n";
print "    <tr><td>Jefferson Martin</td><td>red wine</td></tr>\n";
print "    <tr><td>Martinopoulos</td><td>veggie tray</td></tr></table>\n";
print "<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2824.7069014204376!2d-93.6041456846077!3d44.92929567699617!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x87f60295fd981ca1%3A0xd36b49eb96b36018!2s2695+Pheasant+Rd%2C+Excelsior%2C+MN+55331!5e0!3m2!1sen!2sus!4v1462386611825\" width=\"600\" height=\"450\" frameborder=\"0\" style=\"border:0\" allowfullscreen></iframe>";
print "    </td>\n";
print "    <td>\n";


echo draw_calendar($link,4,2016,4);

print "</td></tr></table>\n";
print "</body>\n";
print "</html>\n";

?>