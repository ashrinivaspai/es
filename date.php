<?php
function login_time()
{
	$t1="09:15:00";
	$t2="10:10:00";
	$t3="11:25:00";
	$t4="12:20:00";
	$t5="13:15:00";
	$t6="14:10:00";
	$t7="15:05:00";
	$t8="15:50:00";
	//$t9="17:15:00";
	if(time()<=strtotime($t1))
	{
		return "1";
	}
	else if(time()<=strtotime($t2))
		return "2";
	else if(time()<=strtotime($t3))
		return "3";
	else if(time()<=strtotime($t4))
		return "4";
	else if(time()<=strtotime($t5))
		return "5";
	else if(time()<=strtotime($t6))
		return "6";
	else if(time()<=strtotime($t7))
		return "7";
	else if(time()<=strtotime($t8))
		return "8";
	else
		return "8";
}

function logout_time()
{
	$t1="09:40:00";
	$t2="10:35:00";
	$t3="11:50:00";
	$t4="12:40:00";
	$t5="13:40:00";
	$t6="14:35:00";
	$t7="15:25:00";
	$t8="16:15:00";
	$t9="17:15:00";
	if(time()>=strtotime($t9))
	{
		return "8";//something is wrong one have to logout before 5:15. who stays that long
	}
	else if(time()>=strtotime($t8))//b/w 4:15 and 5:15
		return "8";
	else if(time()>=strtotime($t7))//''''
		return "7";
	else if(time()>=strtotime($t6))
		return "6";
	else if(time()>=strtotime($t5))
		return "5";
	else if(time()>=strtotime($t4))
		return "4";
	else if(time()>=strtotime($t3))
		return "3";
	else if(time()>=strtotime($t2))
		return "2";
	else if(time()>=strtotime($t1))
		return "1";
	else
		return "-1";//something is wrong why is he logging out before 9:45?
}
?>
