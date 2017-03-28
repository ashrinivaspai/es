<?php
	//database connection
	$dbhost="localhost";
	$dbuser="root";
	$dbpassword="Amunje*9";
	$dbname="temp";
	$connection=mysqli_connect($dbhost,$dbuser,$dbpassword,$dbname);
	if(mysqli_connect_errno())
	{
		die("failed".mysqli_connect_error());
	}
?>