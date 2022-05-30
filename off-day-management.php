<?php
include './libs/database.php';
 // $userType="full_time";
$userType="part_time";
// $startDate = date('Y-m-d',strtotime('2022-03-07'));
$startDate = date('Y-m-d',strtotime('next monday'));
$endDate = date('Y-m-d',strtotime("+6 day", strtotime($startDate)));
$successCounter=0;
$errorCounter=0;

if ($_POST['userType']!='' && $_POST['genderType']!='' && $_POST['scheduleDate']) {
	$userType=$_POST['userType'];
	$genderType=$_POST['genderType'];
	$startDate=$_POST['scheduleDate'];
	$weekName=$_POST['weekName'];
	$userGroup=$_POST['userGroup'];
	// schedule Check
	$checkDateStart=$startDate;
	
	$checkActivity=mysql_fetch_assoc(mysql_query("SELECT COUNT(*) as 'total' FROM `roster`.`users_info` WHERE `schedule_date`='".$checkDateStart."' AND `user_type`='".$userType."' "));
	$checkName=mysql_fetch_assoc(mysql_query("SELECT COUNT(*) as 'total' FROM `roster`.`users_info` WHERE `weekName`='".$weekName."'"));
	if ($checkActivity['total']>0) 
	{
		echo "This weekend already added in our database..";
	}
	elseif ($checkName['total']>0) 
	{
		echo "Similer weekend name already exist in our database";
	}
	else
	{
		// echo "SELECT COUNT(*) as 'total' FROM `users_info` WHERE `schedule_date`='".$checkDateStart."' ";
		if ($genderType=='all') {
			$user = mysql_query("SELECT * FROM `asterisk`.`vicidial_users` WHERE `user_level` = '1' AND `user` != 'VDCL' AND `user` != 'VDAD' AND `user_group`='$userGroup'  AND custom_two='$userType' ORDER BY RAND()");
		}
		else
		{

		 $user = mysql_query("SELECT * FROM `asterisk`.`vicidial_users` WHERE `user_level` = '1' AND `user` != 'VDCL' AND `user` != 'VDAD' AND `user_group`='$userGroup' AND custom_one='$genderType' AND custom_two='$userType' ORDER BY RAND()");
		}
		
		while ($rowUser=mysql_fetch_assoc($user)) {
			for ($i=0; $i < 7; $i++) { 
				$day="+".$i. "day";
				$endDate = date('Y-m-d',strtotime($day, strtotime($startDate)));
				$userAdd=mysql_query("INSERT INTO `roster`.`users_info`( `user_name`, `user_type`, `schedule_date`, `weekName`, `active_status`) VALUES ('".$rowUser['user']."', '$userType', '".$endDate."', '$weekName', '1')");
			}
		}

	// offDayDistribution

		$userData=mysql_query("SELECT DISTINCT(`user_name`), `user_type` FROM `roster`.`users_info` WHERE `schedule_date`>='".$startDate."' AND `schedule_date`<='".$endDate."' AND `user_type`='$userType' AND `weekName`='$weekName'");
		$userCount=mysql_num_rows($userData);

		$userTypeArr=array();
		while ($rowUserDara=mysql_fetch_assoc($userData)) 
		{
			array_push($userTypeArr, $rowUserDara['user_type']);
		}
		$userTypeArrFilter=array_unique($userTypeArr);

		$currnet=0;
		if ($userType=="full_time") {
			$perSlotUser=round($userCount/7);
			if($userCount<7){
				$loop_count = $userCount;
			} else {
				$loop_count = 7;
			}
			for ($i=0; $i < $loop_count; $i++) { 
				$day="+".$i. "day";
				$endDate = date('Y-m-d',strtotime($day, strtotime($startDate)));


				if (($currnet+$perSlotUser)>$userCount) 
				{
					$perSlotUser=$userCount-$currnet;
		//$currnet=$userCount;
				}
				offDayManagement($userType, $endDate, $perSlotUser,$currnet, $userCount,$weekName);

				$currnet+=$perSlotUser;
			}
		}

		if ($userType=="part_time") {
			$perSlotUser=round($userCount/3);
			for ($i=0; $i < 6; $i+=2) { 
				$day="+".$i. "day";
				$endDate = date('Y-m-d',strtotime($day, strtotime($startDate)));

				if (($currnet+$perSlotUser)>$userCount) 
				{
					$perSlotUser=$userCount-$currnet;
		//$currnet=$userCount;
				}
			echo $counter."--".$currnet."--".$perSlotUser;
				offDayManagement($userType, $endDate, $perSlotUser,$currnet, $userCount);
				$currnet+=$perSlotUser;
			}
		}

		if ($successCounter!=0 && $errorCounter==0) 
		{
			echo 'success';
		}
		else
		{
			echo "Something is wrong..!";
		}
	}


	

}


function offDayManagement($userType, $scheduleDate, $perSlotUser,$currnet, $totalUser, $weekName)
{
	include './libs/database.php';
	if($userType=="full_time")
	{
		$userUpdateData=mysql_query("SELECT * FROM `roster`.`users_info` WHERE `schedule_date`='$scheduleDate' AND `user_type`='$userType' AND `weekName`='$weekName' ORDER BY `id` ASC LIMIT $currnet,$perSlotUser");
		$id_list=array();
		while ($row=mysql_fetch_assoc($userUpdateData)) 
		{
			array_push($id_list,$row['id']);
		}
		$offDaySet = mysql_query("UPDATE `roster`.`users_info` SET `active_status` = '0'  WHERE `id` IN (" . implode(",", $id_list) . ") AND `schedule_date`='$scheduleDate' ");
			if ($offDaySet) {
				$GLOBALS['successCounter']++;
			}
			else
			{
				$GLOBALS['errorCounter']++;
			}
		}
		if ($userType=="part_time") 
		{
			// $day="+1 day";
			// $endDate = date('Y-m-d',strtotime($day, strtotime($scheduleDate)));

			// print_r($id_list);
			for ($i=0; $i < 2 ; $i++) { 
				$day="+".$i. "day";
				$endDate = date('Y-m-d',strtotime($day, strtotime($scheduleDate)));
				$userUpdateData=mysql_query("SELECT * FROM `roster`.`users_info` WHERE `schedule_date`='$endDate' AND user_type='$userType' ORDER BY `id` ASC LIMIT $currnet,$perSlotUser");
				$id_list=array();
				while ($row=mysql_fetch_assoc($userUpdateData)) 
				{
					array_push($id_list,$row['id']);
				}
				$offDaySet = mysql_query("UPDATE `roster`.`users_info` SET `active_status` = '0'  WHERE `id` IN (" . implode(",", $id_list) . ") AND `schedule_date`='$endDate'");
					if ($offDaySet) {
						$GLOBALS['successCounter']++;
					}
					else
					{
						$GLOBALS['errorCounter']++;
					}
				}

			}
		}

		





	?>