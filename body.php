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
      echo print_events($link,'upcoming');
 		echo '<br>';
 	}
 	elseif ($_SESSION['page']=="RSVP")
 	{
      echo '<table><tr align="center"><td valign="top">';
 		echo add_attendance($link);
 		echo '</td><td width="30%"></td><td valign="top">';
 		echo bringing($link);
      echo '</td></tr></table>';
 	}
 	elseif ($_SESSION['page']=="manage")
 	{
      echo '<table><tr align="center"><td>';
      echo family_addnew($link);
 		echo '</td></tr><tr><td>';
      echo member_addnew($link);
      echo '</td></tr></table>';
 	}
 	elseif ($_SESSION['page']=="upcoming")
 	{
      echo print_events($link,'upcoming');
 	}
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
?>