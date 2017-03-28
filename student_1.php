<?php
// in this page we are going to dispay the attendance of a single student for all the subject
//first
include("dbcon.php");
require_once("functions.php");
//include_once("style.php");
include 'session.php';
if($_SESSION['type']==='1')
header("Location: admin.php");
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="w3.css">
	<title>Attendance</title>
	<div class="w3-container w3-border">
		<table>
			<tr>
				<td align="left" width=20%><img src="nmamit.jpg" style="width: 50%"></td>
				<td  width: 80% align="center"><h1 style="text-align: center;">WELCOME TO NMAMIT STUDENT DATABASE SYSTEM</h1></td>
				<td align="right" width="20%"><img src="nitte-logo.png" style="width:55%" ></td>
			</tr>
		</table>
	</div>
</head>
<Br>
<body class="w3-container">
<div class="w3-bar w3-blue">
		<a href="logout.php" class="w3-bar-item w3-button w3-right w3-hover-red">Logout</a>
		</div>
<?php
if($_SESSION['type']==='0')
{
	//echo "inside";
	$query="select * from data where data.USN='{$_SESSION['USN']}'";
	$result=mysqli_query($connection, $query);
	if(!$result)
		echo "error fetching info about class";
	else
	{
		$row=mysqli_fetch_assoc($result);
		$dept=$row['department'];
		$sec=$row['section'];
		$sem=$row['sem'];
		$year=$row['year'];
		$query="Select serial from class where department='{$dept}' and year={$year} and section='{$sec}' and sem={$sem}";
		$result=mysqli_query($connection, $query);
		$row=mysqli_fetch_assoc($result);
		$class=$row['serial'];
	}
	$query="SELECT subject, count(staff_attendance.subject) as number from staff_attendance where class={$class} group by subject";
	$result=mysqli_query($connection, $query);
	if(!$result)
		echo "error fetching number";
	else
	{
		while($row=mysqli_fetch_assoc($result))
		{
			$subject=$row['subject'];
			$no=$row['number'];
			$number[$subject]=$no;
		}
	}
	$query = "SELECT attendancen.period, COUNT(attendancen.period) as attended from attendancen where attendancen.USN='{$_SESSION['USN']}' and attendancen.period<>'NULL' AND attendancen.period<>'' ";
	$query.=" group by attendancen.period";
	$result=mysqli_query($connection, $query);
	//echo $query;
	if(!$result)
	{
		echo "error";	
	}
	else
	{
?>
		<Br>
		<table class="w3-table w3-bordered">
			<tr>
				<th>Subject</th>
				<th>Count</th>
				<th>Percentage</th>
			</tr>
<?php
		while($row=mysqli_fetch_assoc($result))
		{
			echo "<tr>";
			$subject=$row['period'];
			$count=$row['attended'];
			$Percentage=$count*100/$number[$subject];
			echo "<td>{$subject}</td><td>{$count}</td><td>{$Percentage}</td>";
		}
	}
}
?>
</body>
</html>