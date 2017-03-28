<?php
// in this page we are going to dispay the attendance of the student subject wise i.e., only one subject at one time
//first
include("dbcon.php");
require_once("functions.php");
//include_once("style.php");
include 'session.php';
$type=$_SESSION['type'];
if($type=='0')
	header("location:student_1.php");
?>
<?php
if(isset($_POST['Submit']))
{
	//print_r($_POST);
	$year=$_POST['year'];
	$sec=$_POST['sec'];
	$dept=$_POST['Dept'];
	$start=$_POST['start'];
	$end=$_POST['end'];
	$sem=$_POST['sem'];
	$subject=$_POST['period'];
}
else
{
	form_init();
}


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
				<td  width: 75% align="center"><h1 style="text-align: center;">WELCOME TO NMAMIT STUDENT DATABASE SYSTEM</h1></td>
				<td align="right" width="20%"><img src="nitte-logo.png" style="width:55%" ></td>
			</tr>
		</table>
	</div>
</head>
</div>
<body class="w3-container">
<Br>
<div class="w3-bar w3-blue">
	<a href="admin.php" class="w3-bar-item w3-button w3-hover-red">Attendance</a>
	<a href="entry_f.php" class="w3-bar-item w3-button w3-hover-red">Database</a>
	<a href="advanced_display.php" class="w3-bar-item w3-button w3-hover-red">Batch Operations</a>
	<a href="logout.php" class="w3-bar-item w3-button w3-right w3-hover-red">Logout</a>
</div> 

	<fieldset>
		<legend>Attendance Display</legend>
		<form action="admin.php" method="POST">
			<table class="w3-table w3-bordered">
				<tr>
					<td>Dept</td>
					<td width="50%">
						<select name=Dept class="w3-select w3-border">
							<option value=BT <?php if($dept=="BT"){echo "selected";} ?>>BT</option>
							<option value=CS <?php if($dept=="CS"){echo "selected";} ?>>CS</option>
							<option value=CV <?php if($dept=="CV"){echo "selected";} ?>>CV</option>
							<option value=ENC <?php if($dept=="ENC"){echo "selected";} ?>>ENC</option>
							<option value=IS <?php if($dept=="IS"){echo "selected";} ?>>IS</option>
							<option value=ME <?php if($dept=="ME"){echo "selected";} ?>>ME</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Year</td>
					<td>
						<select name="year" class="w3-select w3-border">
							<option value=1 <?php if($year==='1'){echo "selected";} ?>>1</option>
							<option value=2 <?php if($year==='2'){echo "selected";} ?>>2</option>
							<option value=3 <?php if($year==='3'){echo "selected";} ?>>3</option>
							<option value=4 <?php if($year==='4'){echo "selected";} ?>>4</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Sem</td>
					<td>
						<select name="sem" class="w3-select w3-border">
							<option value=1 <?php if($sem==='1'){echo "selected";} ?>>1</option>
							<option value=2 <?php if($sem==='2'){echo "selected";} ?>>2</option>
							<option value=3 <?php if($sem==='3'){echo "selected";} ?>>3</option>
							<option value=4 <?php if($sem==='4'){echo "selected";} ?>>4</option>
							<option value=5 <?php if($sem==='5'){echo "selected";} ?>>5</option>
							<option value=6 <?php if($sem==='6'){echo "selected";} ?>>6</option>
							<option value=7 <?php if($sem==='7'){echo "selected";} ?>>7</option>
							<option value=8 <?php if($sem==='8'){echo "selected";} ?>>8</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Section</td>
					<td>
						<select name="sec" class="w3-select w3-border">
							<option value=A <?php if($sec=='A'){echo "selected";} ?>>A</option>
							<option value=B <?php if($sec=='B'){echo "selected";} ?>>B</option>
							<option value=C <?php if($sec=='C'){echo "selected";} ?>>C</option>
							<option value=D <?php if($sec=='D'){echo "selected";} ?>>D</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Select the subject</td>
					<td>
						<input list="period" name="period" class="w3-input w3-border" required>
						<datalist id="period">
							<?php
							$query="Select subject from subjects where department='{$dept}'";
							$result=mysqli_query($connection, $query);
							while($row=mysqli_fetch_assoc($result))
							{
								echo "<option value='{$row['subject']}'></option>";
							}
							?>
						</datalist>
					</td>
				</tr>
				<tr>
					<td>Start</td>
					<td><input type="date" class="w3-input w3-border" name="start"></td>
				</tr>
				<tr>
					<td>End</td>
					<td><input type="date" class="w3-input w3-border" name="end"></td>
				</tr>
			</table>
			<p>
				<div align="center"><input type="Submit"  height="35px" value="Submit" class="w3-btn w3-blue" name="Submit">
					<input type="Reset" class="w3-btn w3-blue" name="Reset"></div>
				</p>
			</form>
		</fieldset>
		<?php
		if(isset($_POST['Submit']))
		{
			echo"<h3>Showing Attendance for subject {$subject} of Section {$sec}, Year {$year}, Sem {$sem}, Department {$dept}</h3>";
			if($start&&$end)
			{
				echo " from {$start} to {$end} </h3>";
			}
			?>
			<table class="w3-table w3-striped">
				<tr>
					<th>Name</th>
					<th>USN</th>
					<th>No. of class attended</th>
					<th>%</th>
				</tr>
				<?php
				$query="select serial from class where department='{$dept}' and year={$year} and sem={$sem} and section='{$sec}'";
				$result=mysqli_query($connection, $query);
				if(!$result)
					echo "error in serial";
				$row=mysqli_fetch_assoc($result);
				$class=$row['serial'];
				$query="select COUNT(staff_attendance.subject) as number from staff_attendance where class={$class} and subject='{$subject}'";
				if($start&&$end)
				{
					$query.=" AND `date` between '{$start}' and '{$end}' ";
				}
				$result=mysqli_query($connection, $query);
				//echo $query;
				echo "<br>";
				if(!$result)
					echo "error in number";
				$row=mysqli_fetch_assoc($result);
				$number=$row['number'];
				$query="Select data.Name, data.USN,COUNT(attendancen.period) AS attended FROM data LEFT JOIN attendancen ON data.USN=attendancen.USN  WHERE attendancen.period='{$subject}' AND data.type='0' and data.department='{$dept}' AND data.year='{$year}' and data.sem='{$sem}' and data.section='{$sec}' ";
				if($start&&$end)
				{
					$query.=" AND `date` between '{$start}' and '{$end}' ";
				}
				$query.="GROUP BY data.USN ";



	//echo "<Br>";
	//echo $query;
				$result=mysqli_query($connection, $query);
				if(!$result)
				{
					echo "error while fetching attendance";
				}
				else
				{
					while($row=mysqli_fetch_assoc($result))
					{
						echo "<tr>";
						$name=$row['Name'];
						$USN=$row['USN'];
						$attended=$row['attended'];
						$percentage=$row['attended']*100/$number;
						echo "<td>{$name}</td><td>{$USN}</td><td>{$attended}</td><td>{$percentage}</td>";
					}

				}

			}

			?>
		</table>
	</body>
	</html>
