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
      echo print_events($link,'next');
 		echo '<br>';
 	}
 	elseif ($_SESSION['page']=="families")
 	{
      echo family_table($link);
 		echo '<br>';
 	}
 	elseif ($_SESSION['page']=="next")
 	{
      echo print_events($link,'next');
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
 	elseif ($_SESSION['page']=="managefam")
 	{
 		echo '<table><tr align="center"><td>';
 		echo family_addnew($link);
 		echo '</td></tr><tr><td>';
 		echo member_addnew($link);
 		echo '</td></tr></table>';
 	}
 	elseif ($_SESSION['page']=="manageev")
 	{
      $sql = "select * from (select e.event_id,f.family_id,f.name,d.month,d.day,d.year,str_to_date(concat(concat(month,'/',greatest(day,1)),'/',year),'%m/%d/%Y') dt from date d join event e on d.date_id=e.date_id join family f on f.family_id=e.family_id where e.cancel=0) as a where a.dt>curdate() and a.family_id=".$_SESSION['family_id'].' limit 1';
 		logger($link,$sql);
 		$data = mysqli_query($link,$sql);
 		if (mysqli_num_rows($data)<1)
 		{
 			echo '<h3>You have no upcoming events</h3>';
 		}
 		else
 		{
         echo '<table><tr align="center"><td>';
         echo set_event($link);
         echo '</td></tr><tr><td align="center">';
         echo add_food_to_event($link);
         echo '</td></tr></table>';
 		}
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