<?php  
session_start();
include './libs/database.php';
if ($_POST['check']=="manpowerprediction") 
{
	$date=$_POST['date'];
	$shift=$_POST['shift'];
	// $numberOfNewMan=$_POST['numberOfNewMan'];
	$userCount=$_POST['userCount'];
	$shiftData=mysql_fetch_assoc(mysql_query("SELECT * FROM `roster`.`tbl_shift` WHERE `shift_name`='$shift'"));
	$timeSlot=json_decode($shiftData['shift_info']);
	$manPowerArrCurrent=array();
	$man=0;
	foreach ($timeSlot as $key => $value) {
		$rosterData=mysql_num_rows(mysql_query("SELECT * FROM `db_shooz`.`fifteen_minute_schedul` WHERE `create_date`='$date' AND `".$key."`!=''"));
		array_push($manPowerArrCurrent, $rosterData+$userCount);		
	}
	// erneeded
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
					<div class="col-md-5 float-left" style="margin: 2px; background: <?php if (($neededMan[$k]==$manPowerArrCurrent[$k]) ||($neededMan[$k]<$manPowerArrCurrent[$k]) ) {
						echo "#198754";
					} else{ echo "#ffcc00"; } ?>;">
					<p class="text-center" style="margin: 0px !important;"><?php echo $manPowerArrCurrent[$k]; ?></p>
				</div>
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