<?php  
session_start();
include './libs/database.php';
if ($_POST['check']=="manpowerprediction") 
{
	$date=$_POST['date'];
	$shift=$_POST['shift'];
	$dateArr=array();
	for ($d=0; $d < 7; $d++) { 
		$dateDif="+".$d." day";
		$endDate = date('Y-m-d',strtotime($dateDif, strtotime($date)));
		array_push($dateArr, $endDate);
	}
	// print_r($dateArr);

	// $numberOfNewMan=$_POST['numberOfNewMan'];
	$userCount=$_POST['userCount'];
	$shiftData=mysql_fetch_assoc(mysql_query("SELECT * FROM `roster`.`tbl_shift` WHERE `shift_name`='$shift'"));
	$timeSlot=json_decode($shiftData['shift_info']);
	$manPowerArrCurrent=array();
	$man=0;


	foreach ($timeSlot as $key => $value) {
		$agent=0;
		foreach ($dateArr as $date) {
			$rosterData=mysql_num_rows(mysql_query("SELECT * FROM `db_shooz`.`fifteen_minute_schedul` WHERE `create_date`='$date' AND `".$key."`!=''"));
			$agent=$agent+$rosterData;
		}
		array_push($manPowerArrCurrent, round($agent/7)+$userCount);	

	}


	// print_r($manPowerArrCurrent);
	// erneeded

	$agentArray=array();
	$maxAgent=array();
	foreach ($dateArr as $date) {
		$neededMan=array();
		$timeName=array();
		$dayName=strtolower(date("l", $date));
		$erLog=mysql_fetch_assoc(mysql_query("SELECT `mainAgent` FROM `db_shooz`.`erlang_calculation` WHERE `dayName`='$dayName'"));
		$erManPower=(array) json_decode($erLog['mainAgent']);


		 array_push($maxAgent,max($erManPower));
		// foreach ($erManPower as $time => $man) 
		// {
		// 	foreach ($timeSlot as $timeArea => $workcode) 
		// 	{
		// 		if($time==$timeArea)
		// 		{
		// 			array_push($neededMan, $man);
		// 			array_push($timeName, $timeArea);
		// 		}
		// 	}
		// }
		// array_push($agentArray, $neededMan);
		// unset($neededMan);
		// unset($timeName);

	}


// print_r($erManPower);

	// print_r($agentArray);
	$sumArray=array();
	$counterrr = count($agentArray[0]);
	for($kk = 0;$kk<$counterrr;$kk++){
		$sumArray[$kk]=0;
	}
	foreach ($agentArray as $agents) 
	{
		foreach ($agents as $key => $value) {
			$sumArray[$key] += $value;
		}

	}
	// print_r($sumArray);

// availablecalculatio

	$campName=$_POST['campName'];
	$userType=$_POST['userType'];
	$workingAgent=array();
	foreach ($dateArr as $startDate) {		
		$selectUser=mysql_query("SELECT `user_id` FROM `db_shooz`.`fifteen_minute_schedul` WHERE `create_date`='$startDate'");
		$userInRosterStr='';
		while ($selectUserRow=mysql_fetch_assoc($selectUser)) 
		{
			$userInRosterStr.="'".$selectUserRow['user_id']."',";

		}
		$userInRosterStr = rtrim($userInRosterStr, ",");
		array_push($workingAgent, $userInRosterStr);
	}


	
	

	

	?>
	<div class="col-md-12">
		<?php 
		for ($k=0; $k < count($neededMan) ; $k++) { 
			?>
			<div class="float-left m-1" style="border: 2px solid;">
				<div class="col-md-12 p-1 bg-info">					
					<p class="text-center" style="margin: 0px !important;"><?php echo $timeName[$k]; ?></p>
				</div>
				
				<div class="col-md-12 mt-1">
					<div class="col-md-5 float-left bg-primary" style="margin: 2px;">
						<p class="text-center" style="margin: 0px !important;"><?php echo $neededMan[$k]; ?></p>

					</div>
					<div class="col-md-5 float-left" style="margin: 2px; background: <?php if (($neededMan[$k]==$sumArray[$k]) ||($neededMan[$k]<$sumArray[$k]) ) {
						echo "#198754";
					} else{ echo "#ffcc00"; } ?>;">
					<p class="text-center" style="margin: 0px !important;"><?php echo $sumArray[$k]; ?></p>
				</div>
			</div>
		</div>
		<?php
	}
	?>

</div>
<div class="col-md-12" style="background: #424242 !important; width: 100%;">
	<?php 
	for ($da=0; $da < count($dateArr); $da++) 
	{ 
		if ($workingAgent[$da]=='') 
		{
			$user =mysql_fetch_assoc(mysql_query("SELECT COUNT(*) as 'available' FROM `roster`.`users_info` WHERE `schedule_date`='".$dateArr[$da]."' AND `active_status`=1 AND `user_type`='$userType' AND `campaign_name`='$campName'"));
		}
		else
		{
			$user =mysql_fetch_assoc(mysql_query("SELECT COUNT(*) as 'available' FROM `roster`.`users_info` WHERE `schedule_date`='".$dateArr[$da]."' AND `active_status`=1 AND `user_type`='$userType' AND `campaign_name`='$campName' AND `user_name` NOT IN (" .$workingAgent[$da]. ")"));
		}

		
		?>
		<div style="width: 13.5%; float: left; margin: 3px;" class="bg-success">

			<p class="text-center text-white bg-warning "><?php echo date("l",strtotime($dateArr[$da])); ?></p>
			<p class="text-center text-white">Available: <br><span id="dayWiseAgent<?php echo $da; ?>_available"><?php echo $user['available']; ?></span></p>
			<p class="text-center text-white">Need Maximum: <br><?php echo $maxAgent[$da]; ?></p>
			<p class="text-white p-2">( <?php 
				$checkAgentPerData=mysql_query("SELECT COUNT(`user_id`) AS 'numberOfAgent', `shift_name` FROM `db_shooz`.`fifteen_minute_schedul` WHERE `create_date`='".$dateArr[$da]."' GROUP BY `shift_name`");
				while($checkAgentRow=mysql_fetch_assoc($checkAgentPerData))
				{
					echo $checkAgentRow['shift_name']." -- ".$checkAgentRow['numberOfAgent'].", ";
				}
			 ?> )</p>
			<div class="form-group p-1">
				<input class="form-control" type="number" placeholder="# of Agent" name="dayWiseAgent" onkeyup="checkAgent('dayWiseAgent<?php echo $da; ?>')" id="dayWiseAgent<?php echo $da; ?>">
				<input type="hidden" name="" id="dayWiseAgent<?php echo $da; ?>_error">
			</div>
		</div>
		<?php
		
	}
	?>
</div>
<?php

}

if ($_POST['check']=="NewManPower") {
	$date=$_POST['date'];
	$shift=$_POST['shift'];
	// $numberOfNewMan=$_POST['numberOfNewMan'];
	$userCount=$_POST['userCount'];
	$shiftData=mysql_fetch_assoc(mysql_query("SELECT * FROM `roster`.`tbl_shift` WHERE `shift_name`='$shift'"));
	$timeSlot=json_decode($shiftData['shift_info']);
	$manPowerArrCurrentArr=array();
	$man=0;
	foreach ($timeSlot as $key => $value) {
		$rosterData=mysql_num_rows(mysql_query("SELECT * FROM `db_shooz`.`fifteen_minute_schedul` WHERE `create_date`='$date' AND `".$key."`!=''"));
		array_push($manPowerArrCurrentArr, $rosterData+$userCount);		
	}
	// print_r($manPowerArrCurrentArr);
	$dayName=strtolower(date("l", $date));
	$erLog=mysql_fetch_assoc(mysql_query("SELECT `mainAgent` FROM `db_shooz`.`erlang_calculation` WHERE `dayName`='$dayName'"));
	$erManPower=json_decode($erLog['mainAgent']);
	$neededMan=array();
	$timeName=array();
	foreach ($erManPower as $time => $man) 
	{
		foreach ($timeSlot as $timeArea => $workcode) 
		{
			if($time==$timeArea)
			{
				array_push($neededMan, $man);
				array_push($timeName, $timeArea);
			}
		}
	}
// print_r($timeName);
	?>


	<div class="col-md-12">
		<?php
		for ($i=0; $i < count($timeName) ; $i++) { 
			?>

			<div class="float-left m-1" style="border: 2px solid;">
				<div class="col-md-12 p-1 bg-info">					
					<p class="text-center" style="margin: 0px !important;"><?php echo $timeName[$i]; ?></p>
				</div>
				
				<div class="col-md-12 mt-1">
					<div class="col-md-5 float-left bg-primary" style="margin: 2px;">
						<p class="text-center" style="margin: 0px !important;"><?php echo $neededMan[$i]; ?></p>

					</div>
					<div class="col-md-5 float-left" style="margin: 2px; background: <?php if (($neededMan[$i]==$manPowerArrCurrentArr[$i]) ||($neededMan[$i]<$manPowerArrCurrentArr[$i]) ) {
						echo "#198754";
					} else{ echo "#ffcc00"; } ?>;">
					<p class="text-center" style="margin: 0px !important;"><?php echo $manPowerArrCurrentArr[$i]; ?></p>
				</div>
			</div>
		</div>
		<?php
	}
	?>
</div>
<?php
}
?>