<?php
include("dbcon.php");
require_once("functions.php");
require_once("date.php");
?>
<?php
//$RFID="789456123232";
$RFID=$_GET['RFID'];
$room="LH308";
$USN="XXXXXXXXXX";//in case RFID doesnt exist
$query="SELECT  USN, type, section, year, department, Name, sem from data where RFID='{$RFID}'";
$message_to_arduino="";
$result=mysqli_query($connection,$query);
if(!$result)
{
	$message_to_arduino="error";
}
else//if query succeeds
{
	$rows=mysqli_fetch_assoc($result);
	$USN=$rows['USN'];
	$date=date("Y-m-d");//current time
	//echo $date;
	$day=date("D");
	$year=$rows['year'];
	$sem=$rows['sem'];
	$dept=$rows['department'];
	$sec=$rows['section'];
	$time=time();//current date
	$type=$rows['type'];
	if ($USN)//if usn is found 
	{
		if($type=='1')//if staff
		{
			staff_login();
		}
		else//if student
		{
			$query="SELECT  * from attendancen where date='{$date}' AND USN='{$USN}' order by `serial` DESC limit 1";//checking for existence of the entry for the date for the user
			$result=mysqli_query($connection,$query);
			if(!$result)
			{
				$message_to_arduino="error";
			}
			else
			{
				$rows=mysqli_fetch_assoc($result);
				if($date=$rows['date'])//if entry for day exists
				{
					$logout=$rows['logout'];
					if($logout=='0')//he has not logged out
					{
						$login=$rows['login'];//get login data
						logout_function();
						//logout function
					}
					else//he has logged out so log him in
					{
						//login function
						login_function();
					}
				}
				else//if entry for day doesnt exist
				{
					//log in function
					//echo "inside no date";
					login_function();
					
				}
			}
		}
	}
	else//if USN not found
	{
		$message_to_arduino="not_found";
	}
}





?>
<?php
function login_function()
{
	global $USN, $connection, $message_to_arduino;
	$login=login_time();
	$date=date("Y-m-d");
	$query="INSERT into attendancen ( `Date`, `USN`, `login`) values ('{$date}', '{$USN}', {$login})";
	//echo $query;
	$result=mysqli_query($connection, $query);
	if(!$result)
		$message_to_arduino="error";
	else
		$message_to_arduino="logging_in";
}
function staff_login()
{
	global $USN, $room, $connection, $message_to_arduino;
	$login=login_time();
	$date=date("Y-m-d");
	$day=date("D");
	//get the class in that room now
	$query="SELECT `{$login}` from rooms where `day`='{$day}' and room='{$room}'";//`{$login}` will give a serial number which corresponds to a particular class in the class database
	$result=mysqli_query($connection, $query);
	if(!$result)
		$message_to_arduino= "Error";
	$row=mysqli_fetch_assoc($result);
	$class=$row[$login];
	//get the class details from that serial
	$query="SELECT department, year, sem, section from class where serial=$class";
	$result=mysqli_query($connection, $query);
	if(!$result)
		$message_to_arduino= "eRrro";
	$row=mysqli_fetch_assoc($result);
	$dept=$row['department'];
	$year=$row['year'];
	$sem=$row['sem'];
	$sec=$row['section'];
	//get the subject for that teacher for that class right now
	$query="select subject from subject_staff where USN='{$USN}' and class={$class}";
	//echo $query;
	$result=mysqli_query($connection, $query);
	if(!$result)
		$message_to_arduino= "eRrror";
	$row=mysqli_fetch_assoc($result);
	$subject=$row['subject'];
	//now get the time_table and lets update that 
	$query="select * from time_table where year={$year} and sem={$sem} and department='{$dept}' and section='{$sec}' and date='{$date}'";
	//echo "<Br>";
	//echo $query;
	$result=mysqli_query($connection, $query);
	$row=mysqli_fetch_assoc($result);
	$count=mysqli_num_rows($result);
	if($count==1)//if entry exists for that date
	{
		$query="UPDATE time_table set `{$login}`='{$subject}' where department='{$dept}' and year='{$year}' and section='{$sec}' and sem='{$sem}' and `date`='{$date}'";
		$result=mysqli_query($connection, $query);
		if(!$result)
			$message_to_arduino= "error";
	}
	else//if entry doesnt exist for that date
	{
		$query="INSERT into time_table (`date`, `year`,`sem`, `department`, `section`, `{$login}`) values ('{$date}', {$year}, {$sem}, '{$dept}', '{$sec}', '{$subject}')";
		$result=mysqli_query($connection, $query);
		//echo $query;
		if(!$result)
			$message_to_arduino= "errorror";
	}
	//make entry for that day in staff_attendance for that subject
	$query="INSERT into staff_attendance (`USN`, `room`, `date`, `class`,`subject` ) values ('{$USN}', '{$room}', '{$date}','{$class}', '{$subject}')";
	$result=mysqli_query($connection, $query);
	if(!$result)
		$message_to_arduino="error";
	else
		$message_to_arduino="staff_login";
}
function logout_function()
{
	global $USN, $connection, $login, $year, $sem, $sec, $dept,$message_to_arduino;
	$logout=logout_time();
	$date=date("Y-m-d");
	$day=date("D");
	//echo $logout;
	$query="SELECT * from time_table where year='{$year}' AND `sem`='{$sem}' AND department='{$dept}' AND section='{$sec}' AND date='{$date}'";
	$result=mysqli_query($connection, $query);
	#echo $query;
	if(!$result)
		$message_to_arduino="error";
	else
	{
		$periods=mysqli_fetch_assoc($result);
		$attendance=$logout-$login+1;
		//echo $attendance;
		if($attendance>1)
		{
			//echo $login;
			//echo "inside greater than 1";
			$query="UPDATE attendancen set `logout`={$logout}, `period`='{$periods[$login]}' where `date`='{$date}' AND `USN`='{$USN}' AND `login`={$login}";
			//echo "<Br>";
			//echo $query;
			$result=mysqli_query($connection, $query);
			if(!$result)
				$message_to_arduino="error";
			else
				$message_to_arduino="logged_out";
			$login++;
			$query="INSERT into attendancen (`login`, `logout`, `period`, `USN`, `date`) values ({$login}, {$logout},'{$periods[$login]}', '{$USN}', '{$date}' )";
			for($i=$login+1;$i<=$logout;$i++)
			{
				$query.=", ({$i}, {$logout}, '{$periods[$i]}', '{$USN}','{$date}')";
			}
			//echo "<Br>";
			echo $query;
			$result=mysqli_query($connection, $query);
			if(!$result)
				$message_to_arduino="error";
			else
				$message_to_arduino="attendance={$attendance}";
		}
		elseif ($attendance===1) 
		{
			$query="UPDATE attendancen set `logout`={$logout}, `period`='{$periods[$login]}' where `date`='{$date}' AND `USN`='{$USN}' AND `login`={$login}";
			$result=mysqli_query($connection, $query);
			if(!$result)
				$message_to_arduino="error";
			else
				$message_to_arduino="1_attendance";
		}
		else
		{
			$query="UPDATE attendancen set `logout`={$logout} where `date`='{$date}' AND `USN`='{$USN}' AND `login`={$login}";
			$result=mysqli_query($connection, $query);
			if(!$result)
				$message_to_arduino="error";
			else
				$message_to_arduino="no attendance";
		}
	}



}
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<?php
	echo "USN={$USN}, message={$message_to_arduino},";
	?>
</body>
</html>