<?php
// print all upcoming events with food and attendance
function set_event($link)
{
	$error="";
	if (isset($_POST['updateevent']))
	{
		if (!empty($_POST['cancel']))
		{
			$e_id=$_POST['e_id'];
			$sql = "update event set cancel=1 where event_id=".$e_id;
			logger($link,$sql);
			if (!mysqli_query($link,$sql))
			{
				logger($link,"Error updating record: " . mysqli_error($link));
			}
		}
		else
		{
			if ($_POST['eday']=="")
			{
				$error.='<br><font color="red">Please select a valid date</font>';
			}
			else
			{
				$eventm=date('m', strtotime($_POST['eday']));
				$eventY=date('Y', strtotime($_POST['eday']));
				$eventd=date('d', strtotime($_POST['eday']));
				$e_id=$_POST['e_id'];
				$family_id=$_SESSION['family_id'];
				$sql = 'update date d join event e on d.date_id=e.date_id set month='.$eventm.',day='.$eventd.',year='.$eventY.' where e.event_id='.$e_id;
				logger($link,$sql);
				if (!mysqli_query($link,$sql))
				{
					logger($link,"Error updating record: " . mysqli_error($link));
				}
				$sql = 'update event e set family_id='.$family_id.' where e.event_id='.$e_id;
				logger($link,$sql);
				if (!mysqli_query($link,$sql))
				{
					logger($link,"Error updating record: " . mysqli_error($link));
				}
			}
		}
	}
	echo '<h2>Edit Event</h2>';
	echo $error;
	echo '<table border="1"><tr>';
	echo '<td width="175px"><b>Family</b></td>';
	echo '<td width="175px"><b>Tentative Date</b></td>';
	echo '<td width="175px"><b>Solid Date</b></td>';
	echo '<td width="100px"></td></tr></table>';
	$i=0;
	$sql = "select * from (select e.event_id,f.family_id,f.name,d.month,d.day,d.year,str_to_date(concat(concat(month,'/',greatest(day,1)),'/',year),'%m/%d/%Y') dt from date d join event e on d.date_id=e.date_id join family f on f.family_id=e.family_id where e.cancel=0) as a where a.dt>curdate() and a.family_id=".$_SESSION['family_id'].' limit 1';
	logger($link,$sql);
	$data = mysqli_query($link,$sql);
	while (list($e_id,$family_id,$fam_name,$month,$day,$year,$date)=mysqli_fetch_row($data)) {
		echo '<form action = "" method = "post">';
		echo '<table border="1"><tr>';
		echo '<td width="175px">'.$fam_name.'</td>';
		echo '<td width="175px">';
		if ($day==-1)
		{
			echo '<input type="text" id="etdate'.$i.'" name="etday" data-format="YYYY-MM" data-template="MMM YYYY" value="'.$year.'-'.sprintf('%02d',$month).'"></td>';
			echo '<td width="175px"><input type="text" id="edate'.$i.'" name="eday" data-format="DD-MM-YYYY" data-template="D MMM YYYY" value="'.sprintf('%02d',$day).'-'.sprintf('%02d',$month).'-'.$year.'"></td>';
		}
		else
		{
			echo '</td>';
			echo '<td width="175px"><input type="text" id="edate'.$i.'" name="eday" data-format="DD-MM-YYYY" data-template="D MMM YYYY" value="'.sprintf('%02d',$day).'-'.sprintf('%02d',$month).'-'.$year.'"></td>';
		}
		echo '<td width="100px"><input type="hidden" name="e_id" value="'.$e_id.'">';
		echo '<input type="submit" name="updateevent" value="Update">';
		echo '</td></tr></table>';
		echo '<script>$(function(){$(\'#etdate'.$i.'\').combodate({minYear:2016,maxYear:2018});});</script>';
		echo '<script>$(function(){$(\'#edate'.$i.'\').combodate({minYear:2016,maxYear:2018});});</script>';
		echo '</form>';
		$i++;
	}
}
function add_food_to_event($link)
{
// 	if (empty($_SESSION['foods']))
// 	{
// 		$_SESSION['foods']=9;
// 		$_SESSION['events']=3;
// 		$_SESSION['event_id']=-1;
// 	}
	$error="";
	if (isset($_POST['addfoodtoevent']))
	{
	   $event_id=$_POST['e_id'];
		if (isset($_POST['foods']))
		{
			$foodArray = $_POST['foods'];
			$j=0;
			for ($i=1; $i<=$_SESSION['foods']; $i++)
			{
				if ($j<count($foodArray)&&$foodArray[$j]==$i)
				{
					$sql = "update food_for_event set on_menu=1 where event_id=".$event_id." and food_id=".$i;
					$j++;
					logger($link,$sql);
					if (!mysqli_query($link,$sql))
					{
						logger($link,"Error inserting record: " . mysqli_error($link));
					}
				}
				else
				{
					$sql = "update food_for_event set on_menu=0 where event_id=".$event_id." and food_id=".$i;
					logger($link,$sql);
					if (!mysqli_query($link,$sql))
					{
						logger($link,"Error inserting record: " . mysqli_error($link));
					}
				}
			}
		}
	}
	$sql = "select * from (select e.event_id,f.family_id,d.day,str_to_date(concat(concat(month,'/',greatest(day,1)),'/',year),'%m/%d/%Y') dt from date d join event e on d.date_id=e.date_id join family f on f.family_id=e.family_id where e.cancel=0) as a where a.dt>curdate() and a.day!=-1 and a.family_id=".$_SESSION['family_id'].' limit 1';
 	logger($link,$sql);
 	$data = mysqli_query($link,$sql);
 	if (mysqli_num_rows($data)>0)
 	{
 	   list($event_id,$family_id,$day,$dt)=mysqli_fetch_row($data);
   	echo '<h2>Add Food to Event</h2>';
   	echo $error;
   	echo '<form action = "" method = "post"><table border="1"><tr>';
   	$sql = "select f.food_id,food,on_menu from food f left join (select * from food_for_event where event_id=".$event_id.") as e on f.food_id=e.food_id order by f.food_id";
   	$data = mysqli_query($link,$sql);
   	logger($link,$sql);
   	$i=0;
   	while (list($food_id,$food,$selected)=mysqli_fetch_row($data))
   	{
   		$checked='';
   		if (!empty($selected))
   		{
   			$checked=' checked';
   		}
   		if ($i==0)
   		{
   			echo '<tr><td align="left"><input type="checkbox" name="foods[]" value='.$food_id.$checked.'>'.$food.'</td>';
   		}
   		else
   		{
   			echo '<td align="left"><input type="hidden" name="e_id" value="'.$event_id.'">';
   			echo '<input type="checkbox" name="foods[]" value='.$food_id.$checked.'>'.$food.'</td></tr>';
   		}
   		$i=1-$i;
   	}
   	if ($i==1)
   	{
   		echo '<td align="left"></td></tr>';
   	}
   	echo '<tr><td colspan="2" align="center"><input type="submit" name="addfoodtoevent" value="Update">';
   	echo '</td></tr></table></form>';
 	}
}
?>