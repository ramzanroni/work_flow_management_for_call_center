<?php
session_start();
include './libs/database.php';

if ($_POST['check']=="manageWorkFlow") 
{
	$totay_date = date('Y-m-d');
	$tomorrow_date = date('Y-m-d',strtotime('+1 day'));
	$startTime=$_POST['startTime'];
	$endTime=$_POST['endTime'];
	if(strtotime($startTime)>strtotime($endTime)){
		$startTime = $totay_date." ".$startTime;
		$endTime = $tomorrow_date." ".$endTime;
	}
	$timeArr=array();
	$range=range(strtotime($startTime),strtotime($endTime),15*60);

	// var_dump($range);

	foreach($range as $time){
		$timeInt= date("H:i",$time);
		if ($timeInt=="00:00") {
			$timeInt="23:59";
		}
		array_push($timeArr, $timeInt);
	}
	$timelength=count($timeArr);
	$idRef=0;
	?>
	<input type="hidden" name="timeArray" id="timeArray" value="<?php echo implode(",",$timeArr); ?>">
	<p class="text-center col-md-12 pt-1 pb-1 mt-1 mb-1 bg-warning">Select your work flow</p>
	<?php
	array_pop($timeArr);
	foreach ($timeArr as $value) {
		?>

		<div class="col-md-2 float-left m-1" style="border: 2px solid red; border-radius: 10px;">
			<legend><?php echo $value; ?></legend>
			<div class="form-group">
				<select class="form-control" id="<?php echo $value."_".$idRef; ?>">
					<?php 
					$workingStatus=mysql_query("SELECT * FROM `db_shooz`.`shift`");
					while ($row=mysql_fetch_assoc($workingStatus)) {
						?>
						<option><?php echo $row['shifts_type']; ?></option>
						<?php
					}
					?>
				</select>
			</div>
		</div>
		
		<?php
		$idRef++;
	}
}

if ($_POST['check']=="addShift") {
	$shiftName=$_POST['shiftName'];
	$startTime=$_POST['startTime'];
	$endTime=$_POST['endTime'];
	// $shiftName=$_POST['shiftName'];
	if ($shiftName!='' && $endTime!='' && $startTime!='') 
	{
		$shiftInfo="{".rtrim($_POST['shittArray'],",")."}";
		$checkShiftName=mysql_num_rows(mysql_query("SELECT * FROM `roster`.`tbl_shift` WHERE shift_name='$shiftName'"));
		if ($checkShiftName>0) 
		{
			echo "Shift Name Already Exist in Our Database";
		}
		else{
			$shiftInsert=mysql_query("INSERT INTO `roster`.`tbl_shift`(`shift_name`, `start_time`, `end_time`, `shift_info`) VALUES ('$shiftName','$startTime','$endTime','$shiftInfo')");
			if ($shiftInsert) 
			{
				echo "Add Shift Successfully Done..";
			}
			else
			{
				echo "Something is wrong...!";
			}
		}
	}
}

if ($_POST['check']=="deleteShift") 
{
	$id=$_POST['id'];
	$deleteResult=mysql_query("DELETE FROM `roster`.`tbl_shift` WHERE `id`='".$id."'");
	if ($deleteResult) 
	{
		echo "success";
	}
	else{
		echo "Something is wrong..!";
	}
}

if ($_POST['check']=="updateShiftData") 
{
	$shiftId=$_POST['id'];
	$shiftData=mysql_fetch_assoc(mysql_query("SELECT * FROM `roster`.`tbl_shift` WHERE `id`='$shiftId'"));
	$shiftInfo=json_decode($shiftData['shift_info']);
	?>

	<div class="modal-body">
		<div class="row">
			<div class="col-md-12">
				<input type="hidden" name="updateid" id="updateid" value='<?php echo $shiftData['shift_info']; ?>'>

				<?php
				$idRef=1;
				foreach ($shiftInfo as $key => $value) {
					?>
					<div class="col-md-2 float-left m-1" style="border: 2px solid red; border-radius: 10px;">
						<legend><?php echo $key; ?></legend>

						<div class="form-group">
							<select class="form-control" id="<?php echo $key."_".$idRef; ?>">
								<?php
								$workingStatus=mysql_query("SELECT * FROM `db_shooz`.`shift`");
								while ($workRow=mysql_fetch_assoc($workingStatus)) 
								{
									?>
									<option <?php if ($value==$workRow['shifts_type']) {
										echo "selected";
									} ?> value="<?php echo $workRow['shifts_type']; ?>" ><?php echo $workRow['shifts_type']; ?></option>
									<?php
								}
								?>
							</select>
						</div>

					</div>
					<?php
					$idRef++;
				}
				?>

			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary float-right" onclick="updateShiftInfo('<?php echo $shiftId; ?>')">Shift Update</button>
			</div>
		</div>
	</div>
	
	<?php
}


if ($_POST['check']=="updateShiftResult") 
{
	$shiftId=$_POST['shiftId'];
	$shiftInfoUp="{".rtrim($_POST['shittArray'],",")."}";
	$updateShift=mysql_query("UPDATE `roster`.`tbl_shift` SET `shift_info`='$shiftInfoUp' WHERE `id`='$shiftId'");
	if ($updateShift) 
	{
		echo "success";
	}
	else
	{
		echo "Something is wrong...";
	}
}
?>