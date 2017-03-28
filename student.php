<?php
// in this page we are going to dispay the attendance of a single student for all the subject
//first
include("dbcon.php");
require_once("functions.php");
include_once("style.php");
include 'session.php';
?>
<?php
if(isset($_POST['Submit']))
{
	$USN=$_POST['USN'];
	$start=$_POST['start'];
	$end=$_POST['end'];
}
else
{
	$USN='';
	$start='';
	$end='';
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Attendance</title>
	<div id=head style="border: solid 1px #333333;">
		<table>
			<tr>
				<td align="left" width=20%><img src="nmamit.jpg" style="width: 50%"></td>
				<td  width: 75% align="center"><h1 style="text-align: center;">WELCOME TO NMAMIT STUDENT DATABASE SYSTEM</h1></td>
				<td align="right" width="20%"><img src="nitte-logo.jpg" style="width:50%" ></td>
			</tr>
		</table>
	</div>
</head>
<body>
<fieldset>
	<legend>Attendance Display</legend>
	<form action="student.php" method ="POST">
		<table id=attendance>
			<tr>
				<td>USN</td>
				<td><input type="text" name="USN" required></td>
			</tr>
			<tr>
				<td>Start date</td>
				<td><input type="date" name="start"></td>
			</tr>
			<tr>
				<td>End date</td>
				<td><input type="date" name="end"></td>
			</tr>
		</table>
		<p>
			<div align="center"><input type="Submit" class="button button1" value="Submit" name="Submit">
			<input type="Reset" class=" button button1" name="Reset"></div>
		</p>
	</form>
</fieldset>
<?php
if(isset($_POST['Submit']))
{
	$query = "SELECT attendancen.period, COUNT(attendancen.period) as attended from attendancen where attendancen.USN='{$USN}' and attendancen.period<>'NULL' AND attendancen.period<>'' ";
	if($start&&$end)
	{
		$query.=" and attendancen.date between '{$start}' and '{$end}'";
	}
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
		<table id=attendance>
			<tr>
				<th>Subject</th>
				<th>Count</th>
			</tr>
<?php
		while($row=mysqli_fetch_assoc($result))
		{
			echo "<tr>";
			$subject=$row['period'];
			$count=$row['attended'];
			echo "<td>{$subject}</td><td>{$count}</td>";
		}
	}
}
?>
</body>
</html>