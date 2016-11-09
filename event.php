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
				$eventm=$_POST['month'];
				$eventY=$_POST['year'];
				list($eventd)=explode("-",$_POST['eday']);
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
	$i=0;
	$sql = "select * from (select e.event_id,f.family_id,f.name,d.month,d.day,d.year,str_to_date(concat(concat(month,'/',greatest(day,1)),'/',year),'%m/%d/%Y') dt from date d join event e on d.date_id=e.date_id join family f on f.family_id=e.family_id where e.cancel=0) as a where a.dt>curdate() and a.family_id=".$_SESSION['family_id'].' limit 1';
	logger($link,$sql);
	$data = mysqli_query($link,$sql);
	if (list($e_id,$family_id,$fam_name,$month,$day,$year,$date)=mysqli_fetch_row($data)) {
	   echo '<table border="1"><tr>';
	   echo '<td width="175px"><b>Family</b></td>';
	   echo '<td width="175px"><b>Date</b></td>';
	   echo '<td width="100px"></td></tr></table>';
	   echo '<form action = "" method = "post">';
		echo '<table border="1"><tr>';
		echo '<td width="175px">'.$fam_name.'</td>';
		echo '<td width="175px">';
		if ($day==-1)
		{
			echo date("F", mktime(null, null, null, $month)).' <input type="text" id="edate" name="eday" data-format="DD-MM-YYYY" data-template="DD" value="'.sprintf('%02d',$day).'-'.sprintf('%02d',$month).'-'.$year.'"> '.$year.'</td>';
		}
		else
		{
		   echo date("F", mktime(null, null, null, $month)).' <input type="text" id="edate" name="eday" data-format="DD-MM-YYYY" data-template="DD" value="'.sprintf('%02d',$day).'-'.sprintf('%02d',$month).'-'.$year.'"> '.$year.'</td>';
		}
		echo '<td width="100px"><input type="hidden" name="e_id" value="'.$e_id.'">';
		echo '<input type="hidden" name="month" value='.$month.'>';
		echo '<input type="hidden" name="year" value='.$year.'>';
		echo '<input type="submit" name="updateevent" value="Update">';
		echo '</td></tr></table>';
		echo '<script>$(function(){$(\'#edate\').combodate({smartDays:true,minYear:2016,maxYear:2018});});</script>';
		echo '</form>';
		$i++;
	}
}
function add_food_to_event($link)
{
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
// print all upcoming events with food and attendance
function select_event($link)
{
   $year = date('Y',strtotime("+1 year"));
   if (isset($_POST['eventdrop']))
   {
      $event_id = $_POST['event_id'];
      // delete from attending
      $sql = 'delete from attending where event_id='.$event_id;
      logger($link,$sql);
      if (!mysqli_query($link,$sql))
      {
         logger($link,"Error deleting record: " . mysqli_error($link));
      }
   	  // delete from food_for_event
      $sql = 'delete from food_for_event where event_id='.$event_id;
      logger($link,$sql);
      if (!mysqli_query($link,$sql))
      {
         logger($link,"Error deleting record: " . mysqli_error($link));
      }
      // delete from event
      $sql = 'select date_id from event where event_id='.$event_id;
      logger($link,$sql);
      $data = mysqli_query($link, $sql);
      if (list($date_id)=mysqli_fetch_row($data))
      {
      	// do nothing
      }
      $sql = 'delete from event where event_id='.$event_id;
      logger($link,$sql);
      if (!mysqli_query($link,$sql))
      {
         logger($link,"Error deleting record: " . mysqli_error($link));
      }
      // delete from date
      $sql = 'delete from date where date_id='.$date_id;
      logger($link,$sql);
      if (!mysqli_query($link,$sql))
      {
         logger($link,"Error deleting record: " . mysqli_error($link));
      }
   }
   if (isset($_POST['eventselected']))
   {
      $sql = "insert into date(day,month,year) values (-1,'".$_POST['month']."','".$year."')";
      logger($link,$sql);
      if (mysqli_query($link,$sql))
      {
         $date_id = mysqli_insert_id($link);
      }
      else
      {
         logger($link,"Error inserting record: " . mysqli_error($link));
      }
      $sql = "insert into event(family_id,date_id) values (".$_SESSION['family_id'].",".$date_id.")";
      logger($link,$sql);
      if (!mysqli_query($link,$sql))
      {
         logger($link,"Error inserting record: " . mysqli_error($link));
      }
      else
      {
         $event_id = mysqli_insert_id($link);
      }
      // for each food, add entry into food_for_event table
      $sql = 'select food_id from food';
      logger($link,$sql);
      $data = mysqli_query($link,$sql);
      while (list($food_id)=mysqli_fetch_row($data))
      {
         $sql = "insert into food_for_event(event_id,food_id) values (".$event_id.",".$food_id.")";
         logger($link,$sql);
         if (!mysqli_query($link,$sql))
         {
            logger($link,"Error inserting record: " . mysqli_error($link));
         }
      }
      // for each person, add entry into attending table
      $sql = 'select person_id from person';
      logger($link,$sql);
      $data = mysqli_query($link,$sql);
      while (list($person_id)=mysqli_fetch_row($data))
      {
      	 $sql = 'insert into attending (event_id,person_id) values ('.$event_id.','.$person_id.')';
         logger($link,$sql);
         if (!mysqli_query($link,$sql))
         {
            logger($link,"Error inserting record: " . mysqli_error($link));
         }
      }
   }
   echo '<h2>Pick your month to host for '.$year.'</h2>';
   $sql = 'select month from event e join date d on e.date_id=d.date_id where year='.$year.' and family_id='.$_SESSION['family_id'];
   logger($link,$sql);
   $data = mysqli_query($link,$sql);
   $month=-1;
   if (mysqli_num_rows($data)>=1)
   {
      list($month)=mysqli_fetch_row($data);
   }
   for ($i=1; $i<=12;$i++)
   {
      $sql = 'select name,event_id from family f join event e on e.family_id=f.family_id join date d on e.date_id=d.date_id where year='.$year.' and month='.$i;
      logger($link,$sql);
      $data = mysqli_query($link,$sql);
      $name='';
      if (mysqli_num_rows($data)>=1)
      {
         list($name,$event_id)=mysqli_fetch_row($data);
      }
      echo '<form action = "" method = "post">';
      echo '<table border="1" width="75%"><tr height="29px">';
      echo '<td width="45%">'.date('F', mktime(0, 0, 0, $i, 10)).' '.$year.'</td><td width="45%">'.$name.'</td><td align="center">';
      if ($month==-1 && $name=='')
      {
         echo '<input type="hidden" name="month" value='.$i.'><input type="submit" name="eventselected" value="Select"></td></tr></table></form>';
      }
      elseif ($month==-1 && $name!='')
      {
         echo '</td></tr></table></form>';
      }
      elseif ($month==$i)
      {
         echo '<input type="hidden" name="event_id" value='.$event_id.'><input type="submit" name="eventdrop" value="Drop"></td></tr></table></form>';
      }
      else
      {
         echo '</td></tr></table></form>';
      }
   }
}
?>