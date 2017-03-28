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
if(isset($_POST['Submit']))// executes only if submit button is pressed
{
	form_data();// fills variables with post data
	switch ($what) {
		case 'entry':
		{
			$required_fields=array('RFID','name','USN');
			presence($required_fields);
			$required_fields=array('RFID','USN');
			validate($required_fields);
			$required_fields=array('USN','RFID');
			dbcheck($required_fields);
			if(!empty($errors))
			{
				//echo "inside empty";
				disp_errors();
				$errors=array();
			}
			else
			{
				//echo "query";
				$query="INSERT INTO data (USN, Name, RFID, type, department, year, section, sem) VALUES ('{$usn}', '{$name}', '{$RFID}', {$type}, '{$dept}', {$year}, '{$sec}', '{$sem}' )";
				$result=mysqli_query($connection, $query);
				echo $query;
				if(!$result)
				{
					//die("failed");
					echo "failed inside result";
				}
				else
				{
					echo "entry  of '{$name}' is success";
				}
			}
			break;
		}
		case 'delete':
		{
			//echo "inside    delete";
			$required_fields=array('USN');
			presence($required_fields);
			db_presence('USN');
			if(!empty($errors))
			{
				//echo "inside empty";
				disp_errors();
				$errors=array();
			}
			else
			{
				$query="DELETE from data where USN='{$usn}'";
				$result=mysqli_query($connection, $query);
				if(!$result)
				{
					//die("failed");
					echo "failed inside result";
				}
				else
				{
					echo "deletion  of '{$usn}' is success";
				}
			}

			break;
		}

		case 'display':
		{
			$required_fields=array('USN');
			presence($required_fields);
			db_presence('USN');
			if(!empty($errors))
			{
				//echo "inside empty";
				disp_errors();
				$errors=array();
			}
			else
			{
				$query=" SELECT * from data where USN='{$usn}'";
				$result=mysqli_query($connection, $query);
				if(!$result)
				{
					//die("failed");
					//echo "failed inside result";
				}
				else
				{
					//echo "display  of '{$usn}' is success";
					$fetched=mysqli_fetch_assoc($result);
					//var_dump($fetched);
					$USN=$fetched['USN'];
					$name=htmlentities($fetched['Name']);
					//echo $name;
					$RFID=$fetched['RFID'];
					$sec=$fetched['section'];
					//$what=$fetched['what'];
					$year=$fetched['year'];
					$dept=$fetched['department'];
					$type=$fetched['type'];
					$sem=$fetched['sem'];

				}

			}	
			break;
		}
		case 'update':
		{
			echo 'inside update';
			$required_fields=array('RFID','name','USN');
			presence($required_fields);
			$required_fields=array('RFID','USN');
			validate($required_fields);
			$required_fields=array('RFID');
			dbcheck($required_fields);//cheks whether RFID supplied already exists
			db_presence('USN');//returns error if USN doesnt exists. THIS CAN CHANGE USN. TAHT SHOULDNT BE THE CASE BUUUUUUG
			if(!empty($errors))
			{
				disp_errors();
				$errors=array();
			}
			else
			{
				$query="UPDATE data SET Name='{$name}', RFID='{$RFID}', type='{$type}', department='{$dept}', year='{$year}', section='{$sec}', sem='{$sem}' WHERE USN='{$usn}'";$result=mysqli_query($connection, $query);
				if(!$result)
				{
					//die("failed");
					echo "failed inside result";
				}
				else
				{
					echo "success updating '{$usn}'";
				}
			}
			
			break;
		}
		
		default:
			# code...
		break;
	}
}
else// assigns default values to variables
{
	form_init();
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
</head>
<Br>
	<body class="w3-container">
		<div class="w3-bar w3-blue">
			<a href="admin.php" class="w3-bar-item w3-button w3-hover-red">Attendance</a>
			<a href="entry_f.php" class="w3-bar-item w3-button w3-hover-red">Database</a>
			<a href="advanced_display.php" class="w3-bar-item w3-button w3-hover-red">Batch Operations</a>
			<a href="logout.php" class="w3-bar-item w3-button w3-right w3-hover-red">Logout</a>
		</div> 
		<fieldset>
			<legend>Student/Staff Entry</legend>
			<form action="entry_f.php" method="post">
				<input type="radio" name="what" value="entry" class="w3-radio" checked>Entry
				<input type="radio" name="what" value="delete" class="w3-radio">Deletion
				<input type="radio" name="what" value="update" class="w3-radio">Update
				<input type="radio" name="what" value="display" class="w3-radio">Display
				<div align="center">
					<table class="w3-table w3-bordered" style="width: 100%;">
						<tr>
							<td width="50%">
								USN 
							</td>
							<td><input type="text" class="w3-input w3-border" name="USN" value=<?php echo "'".$usn."'"?> autofocus required>
							</td>
						</tr>
						<tr>
							<td>
								RFID No.
							</td>
							<td>
								<input type="text" name="RFID" class="w3-input w3-border" value=<?php echo "'".$RFID."'"?>>
							</td>
						</tr>
						<tr>
							<td>Student/ Staff Name</td>
							<td><input type="text" name="name" class="w3-input w3-border" value=<?php echo "'".$name."'"?></td>
						</tr>
						<tr>
							<td>Dept</td>
							<td>
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
							<td>Student/Staff</td>
							<td>
								<select name="type" class="w3-select w3-border">
									<option value=0 <?php if($type==='0'){echo "selected";} ?>>Student</option>
									<option value=1 <?php if($type==='1'){echo "selected";} ?>>Staff</option>
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
									<option value=0 <?php if($year==='0'){echo "selected";} ?>>NA</option>
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
									<option value=Z <?php if($sec=='Z'){echo "selected";} ?>>NA</option>
								</select>
							</td>
						</tr>
					</table>
				</div>
				<span>
					<p>
						<div class="w3-center"><input type="Submit" class="w3-button w3-blue" value="Submit" name="Submit">
							<input type="Reset" class="w3-button w3-blue" name="Reset"></div>
						</p>
					</span>
				</form>
			</fieldset>
			<b>NOTE</b>
			<ul>
				<li>While entering make sure that USN and RFID No. is unique. Otherwise page will display error</li>
				<li>For <b>staff entry</b> let the <b>year and section to its default</b> values. The program will take care of it internally</li>
				<li><b>For updation of and entry first choose display and enter the USN of the entry and then click on submit.<br> Modify the the required fields and click submit</b></li>
				<li>One can not change the USN of the member once entered</li>
				<li><b>For deletion of entry only USN is sufficient </b></li>
			</ul>

		</body>
		</html>
