<?php
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
	$what=isset($_POST['what'])?$_POST['what']:"";
	$dept=$_POST['Dept'];
	$year=$_POST['year'];
	$sec=$_POST['sec'];
	$sem=$_POST['sem'];
	if($what=='entry'||$what=='update')
	{
		if(($sem>($year*2))||($sem<(($year*2)-1)))
		{
			$errors['sem']="how can sem be '{$sem}' when year is '{$year}'";
		}
	}
}
else
{
	$what=isset($_POST['what'])?$_POST['what']:"";
	$year='3';
	$sec='D';
	$dept='ENC';
	$sem='6';	
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="w3.css">
	<title>Database Entry</title>
	<div class="w3-container w3-border">
		<table>
			<tr>
				<td align="left" width=20%><img src="nmamit.jpg" style="width: 50%"></td>
				<td  width: 75% align="center"><h1 style="text-align: center;">WELCOME TO NMAMIT STUDENT DATABASE SYSTEM</h1></td>
				<td align="right" width="20%"><img src="nitte-logo.png" style="width:55%" ></td>
			</tr>
		</table>
	</div>
	<Br>
<body class="w3-container">
<div class="w3-bar w3-blue">
<a href="admin.php" class="w3-bar-item w3-button w3-hover-red">Attendance</a>
<a href="entry_f.php" class="w3-bar-item w3-button w3-hover-red">Database</a>
<a href="advanced_display.php" class="w3-bar-item w3-button w3-hover-red">Batch Operations</a>
<a href="logout.php" class="w3-bar-item w3-button w3-right w3-hover-red">Logout</a>
<Br>
</div>
	<fieldset>
		<legend>Batch Operations</legend>
		<form action="advanced_display.php" method="post">
		<input type="radio" name="what" value="delete" <?php if($what=="delete") echo "checked";?>>Deletion
		<input type="radio" name="what" value="update" <?php if($what=="update") echo "checked";?>>Update
		<input type="radio" name="what" value="display" <?php if($what=="display") echo "checked";?>>Display
		<br>
		<p>
			<a href="entry_f.php">Basic data operations</a>
		</p>
		<table class="w3-table w3-bordered">
		<tr>
		<td width="50%">Dept</td>
		<td>
		<select name=Dept class="w3-select w3-border">
			<option value=BT <?php if($dept=="BT"){echo "selected";} ?>>BT</option>
			<option value=CS <?php if($dept=="CS"){echo "selected";} ?>>CS</option>
			<option value=CV <?php if($dept=="CV"){echo "selected";} ?>>CV</option>
			<option value=ENC <?php if($dept=="ENC"){echo "selected";} ?>>ENC</option>
			<option value=IS <?php if($dept=="IS"){echo "selected";} ?>>IS</option>
			<option value=ME <?php if($dept=="ME"){echo "selected";} ?>>ME</option>
			<option value=0  <?php if($dept=="0"){echo "selected";} ?> >All</option>
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
			<option value=0 <?php if($year==='0'){echo "selected";} ?>>All</option>
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
			<option value=0 <?php if($sem==='0'){echo "selected";} ?>>All</option>
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
			<option value=0 <?php if($sec==='0'){echo "selected";} ?>>All</option>
		</select>
		</td>
		</tr>
		</table>
		<p align="center">
			<input type="Submit" value="Submit" class="w3-button w3-blue" name="Submit">
		</p>
		
		</form>
	</fieldset>
</body>
<?php
if(isset($_POST['Submit']))
{
	switch ($what) 
	{
		case 'display':
		{
			$query="SELECT * from data where ";
			if($dept!=='0')
				$query.="department='{$dept}' AND ";
			if($year!=='0')
				$query.="year='{$year}' AND ";
			if($sem!=='0')
				$query.="sem='{$sem}' AND ";
			if($sec!=='0')
				$query.="section='{$sec}' AND ";
			$query.="type='0'";
			$result=mysqli_query($connection, $query);
			if(!$result)
			{
				echo "failed";
			}
			else
			{
				?>
				<Br>
				<table class="w3-table-all w3-hoverable ">
				<thead>
					<tr>
						<th>Sl.No</th>
						<th>Name</th>
						<th>USN</th>
						<th>RFID</th>
						<th>Dept</th>
						<th>Year</th>
						<th>Sem</th>
						<th>section</th>
					</tr>
				</thead>
					
				<?php
				$count=0;
				while($row=mysqli_fetch_assoc($result))
				{
					$count++;
					echo "<tr>";
					$name=$row['Name'];
					$USN=$row['USN'];
					$RFID=$row['RFID'];
					$year=$row['year'];
					$sem=$row['sem'];
					$sec=$row['section'];
					$dept=$row['department'];
					echo "<td>{$count}</td><td>{$name}</td><td>{$USN}</td><td>{$RFID}</td><td>{$dept}</td><td>{$year}</td><td>{$sem}</td><td>{$sec}</tr>";
				}
			}
			break;
		}
		case 'update':
		{

			# code...
			break;
		}
		
		default:
			# code...
			break;
	}
}





?>
</head>
</html>