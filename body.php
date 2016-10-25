<?php
function print_body($link)
{
	// move to constants or as session var
	$SHOW_MONTHS=24;
	echo '<body>';
	echo '<table width="1080" border="0" align="center" cellpadding="0" cellspacing="0" id="main_table" class="main_table">';
	echo '  <tr>';
	echo '  <td width="90%" height="25" align="left" valign="top">';
	print_banner();
 	echo '    </td></tr>';
 	echo '    <tr><td width="81%" valign="top" align="center">';
 	if (empty($_SESSION['page'])||$_SESSION['page']=="")
 	{
 		echo '<br>';
 	}
 	elseif ($_SESSION['page']=="RSVP")
 	{
 		echo add_attendance($link);
 		echo "<br><br>";
 		echo bringing($link);
 	}
 	//print_main();
 	echo print_events($link,'upcoming');
 	echo '    </td>';
 	echo '    <td>';
 	echo draw_calendar($link,date('n'),date('Y'),$SHOW_MONTHS);
 	echo '</td></tr></table>';
 	echo '</body>';
}
function print_banner()
{
	echo '    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="header">';
	echo '    <tr>';
	echo '    <td colspan="2">';
	echo '      <table width="100%" border="0" cellpadding="0" cellspacing="0">';
	echo '        <tr><td valign="bottom" class="table_header" width="70%"><img src="Martin-Irish-Crest.jpg" height="150" alt="Martin Family Crest">Martin Family Dinner</td>';
	echo '        <td valign="top">';
	print_logon();
	echo '</td></tr>';
	echo '        <tr><td colspan="2" class="menu1">';
	print_sub_menu();
	echo '</td></tr>';
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