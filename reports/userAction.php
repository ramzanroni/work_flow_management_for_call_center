<?php 
session_start();
include '../libs/database.php';
if ($_POST['check']=='userLogin') 
{
	$userName=$_POST['userName'];
	$password=$_POST['password'];
	$userInfo=mysql_query("SELECT `user_id`,`user`,`full_name`,`user_level`, `user_group` FROM `asterisk`.`vicidial_users` WHERE `user`='$userName' AND `pass`='$password'");
	if (mysql_num_rows($userInfo)>0) 
	{
		$userData=mysql_fetch_assoc($userInfo);
		$_SESSION['user']=$userData['user'];
		$_SESSION['userId']=$userData['user_id'];
		$_SESSION['full_name']=$userData['full_name'];
		$_SESSION['user_level']=$userData['user_level'];
		$_SESSION['user_group']=$userData['user_group'];
		echo "success";
	}
	else
	{
		echo "Please Provide valid User Information";
	}
}

if ($_POST['check']=="ActiveDeacctive") 
{
	$userId= $_POST['userId'];
	$status=$_POST['status'];
	$updateResult=mysql_query("UPDATE `vicidial_users` SET `active`='$status' WHERE `user_id`='$userId'");
	if ($updateResult) 
	{
		echo 'success';
	}
	else{
		echo "Something is wrong...!";
	}
}

if ($_POST['check']=="deleteRosterData") 
{
	$groupId=$_POST['groupId'];
	$findRoster=mysql_query("SELECT `id` FROM `db_shooz`.`fifteen_minute_schedul` WHERE `group_uid`='$groupId'");
	$count=mysql_num_rows($findRoster);
	$countDelete=0;
	while ($rosterRow=mysql_fetch_assoc($findRoster)) 
	{
		$deleteResult=mysql_query("DELETE FROM `db_shooz`.`fifteen_minute_schedul` WHERE `id`='".$rosterRow['id']."'");
		if ($deleteResult) 
		{
			$countDelete++;
		}
	}
	if ($count==$countDelete) 
	{
		echo 'success';
	}
	else
	{
		echo "Something is wrong...!";
	}
}
?>