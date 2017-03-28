<?php
//echo "functions";
$errors=array();
function haspresence($arg)
{
	return (isset($arg)&&($arg!==""));
}

//checks for presence of certain field
//populates the errors string
function presence($arg)
{
	global $errors;
	foreach($arg as $field)
	{
		$value=trim($_POST[$field]);
		if(!haspresence($value))
			$errors[$field]=ucfirst($field)." cant be blank";
	}
}
function validate($arg)
{
	global $errors;
	foreach($arg as $field)
	{
		$value=$_POST[$field];
		if($field==="RFID")
		{
			$length=12;
		}
		else
		{
			$length=10;
		}
		if(strlen($value)!=$length)
		{
			$errors[$field]=$field." length is not ".$length;
		}
	}
	
}
function disp_errors()
{
	global $errors;
	//echo "inside disp_ero";
	echo "please     fix the following error/s";
	echo "<br />";
	echo "<ul";
	echo "<li></li>";
	foreach($errors as $key=>$field)
	{
		echo "<li>{$field}</li>";
	}
	echo "</ul>";
}

//dbcheck checks whether function arguments exist in db. If exists then fills $errors array with errors else does nothing
function dbcheck($arg)
{
	//echo "inside dbcheck";
	global $errors;
	global $connection;
	foreach($arg as $field)
	{
		$value=trim($_POST[$field]);
		$query="SELECT * from data where {$field}='{$value}'";
		$result=mysqli_query($connection,$query);
		if($result)
		{
			$result=mysqli_fetch_assoc($result);
			if(($result[$field]==$value)&&!($value==null))
				$errors[$field]=ucfirst($field)." already exists";
			//mysqli_free_result($result);
		}
	}
}
//queries db using a single arg. If arg doesnt exists then fills errors array with message
function db_presence($arg)
{
	global $errors;
	global $connection;
	$value=trim($_POST[$arg]);
	$query="SELECT * from data where {$arg}='{$value}'";
	$result=mysqli_query($connection,$query);
	if($result)
	{
		$result=mysqli_fetch_assoc($result);
		if(!$result)
		{
			$errors[$arg]=$arg."doesnt exist";
		}
	}
}

function db_disp($arg)
{
	
}
function form_data()
{
	global $what, $RFID, $name, $usn, $dept, $type, $year, $sec, $sem, $errors;
	$what=isset($_POST['what'])?$_POST['what']:"";
	$RFID=isset($_POST['RFID'])?$_POST['RFID']:"";
	$name=isset($_POST['name'])?$_POST['name']:"";
	$usn=isset($_POST['USN'])?$_POST['USN']:"";
	$dept=$_POST['Dept'];
	$type=$_POST['type'];
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
	if($type==='1')
	{
		$year='0';
		$sec='Z';
	}
	$usn=strtoupper(trim($usn));
	$RFID=trim($RFID);
	$name=ucwords(trim($name));
}
function form_init()
{
	global $what, $RFID, $name, $usn, $dept, $type, $year, $sec, $sem;
	$what=isset($_POST['what'])?$_POST['what']:"";
	$RFID=isset($_POST['RFID'])?$_POST['RFID']:"";
	$name=isset($_POST['name'])?$_POST['name']:"";
	$usn=isset($_POST['USN'])?$_POST['USN']:"";
	$year='3';
	$sec='D';
	$type='0';
	$dept='ENC';
	$sem='6';
}

?>