<?php
function print_body($link)
{
	echo '<body>';
	echo '<table width="1080" border="0" align="center" cellpadding="0" cellspacing="0" id="main_table" class="main_table">';
	echo '  <tr>';
	echo '  <td width="90%" height="25" align="left" valign="top">';
	print_banner();
 	echo '    </td></tr>';
 	echo '    <tr><td width="81%" valign="top" align="center">';
 	//print_main();
 	echo print_events($link,'upcoming');
 	echo '    </td>';
 	echo '    <td>';
 	echo draw_calendar($link,4,2016,24);
 	echo '</td></tr></table>';
 	echo '</body>';
}
function print_banner()
{
	echo '    <table width="100%" border="5" cellspacing="0" cellpadding="0" id="header">';
	echo '    <tr>';
	echo '    <td colspan="2">';
	echo '      <table width="100%" border="0" cellpadding="0" cellspacing="0">';
	echo '        <tr><td valign="bottom" class="table_header"><img src="Martin-Irish-Crest.jpg" height="150" alt="Martin Family Crest">Martin Family Dinner</td>';
	echo '        <td valign="top">Welcome, <span class="person">Martinopoulos Family</span><br>';
	echo '        Account<br>';
	echo '        Logout<br></td></tr>';
	echo '        <tr><td colspan="2" class="menu1">Home | My Family | RSVP | Address Book</td></tr>';
	echo '      </table>';
}
function print_main()
{
	print '    <table border="1"><tr><td colspan="2">';
	print '    <b>May 2016</b><br><b>Host:</b> Martinopoulos(<a href="https://goo.gl/maps/1vDoRby74AB2">map</a>)<br><b>Date:</b> Sun, May 29<br><b>Time:</b> 4pm</td></tr> ';
	print '    <tr><td valign="top" width="50%"><table border="1"><tr><td colspan="2"><b>Dishes</b></td></tr>';
	print '    <tr><td valign="top"><b>Martinopoulos</b></td><td>main course</td></tr>';
	print '    <tr><td valign="top"><b>Jefferson Martin Family</b></td><td>pasta salad</td></tr>';
	print '    <tr><td valign="top"><b>Bill Martin Family</b></td><td>white wine</td></tr>';
	print '    <tr><td valign="top"><b>Eide Family</b></td><td>veggie tray</td></tr>';
	print '    </table></td><td valign="top">';
	print '    <table border="1"><tr><td colspan="2"><b>Attending</b></td></tr>';
	print '    <tr><td valign="top"><b>Martinopoulos</b></td><td>Rob<br>Steph<br>Stevie<br>Bobby<br>Teddy<br></td></tr>';
	print '    <tr><td valign="top"><b>Jefferson Martin Family</b></td><td>Patrick<br><Rebecca<br><Finn<br>Brigit</td></tr>';
	print '    <tr><td valign="top"><b>Bill Martin Family</b></td><td>Bill<br>Maripat</td></tr>';
	print '    <tr><td valign="top"><b>Eide Family</b></td><td>Mike<br>Jordan</td></tr>';
	print '    </table></td></tr></table>';
	print '    <table border="1"><tr><td colspan="2">';
	print '    <b>June 2016</b><br><b>Host:</b> Jefferson Martin(<a href="https://goo.gl/maps/1vDoRby74AB2">map</a>)<br><b>Date:</b> Sun, May 29<br><b>Time:</b> 4pm</td></tr> ';
	print '    <tr><td valign="top" width="50%"><table border="1"><tr><td colspan="2"><b>Dishes</b></td></tr>';
	print '    <tr><td valign="top"><b>Jefferson Martin</b></td><td>main course</td></tr>';
	print '    </table></td><td valign="top">';
	print '    <table border="1"><tr><td colspan="2"><b>Attending</b></td></tr>';
	print '    <tr><td valign="top"><b>Jefferson Martin</b></td><td>Patrick<br>Rebecca<br>Finn<br>Brigit<br></td></tr>';
	print '    </table></td></tr></table>';
}
?>