<?php 
session_start();
include './libs/database.php';
$date = date("Y-m-d");
if ($_POST['check']=="sawpingData") 
{
	$id=$_POST['id'];
	$user_id=$_POST['user_id'];
	$date=$_POST['date'];
	$shift_name=$_POST['shift_name'];
	$campaign_name=$_POST['campaign_name'];

	?>
	<div class="col-md-12">
		<p class="text-center text-white pt-2 pb-2 bg-info">Swap Info</p>
		<div class="col-md-6 float-left">
			<p class="text-center text-white bg-warning mt-2 p-2">Sender Info</p>
			<input type="hidden" name="senderId" id="senderId" value="<?php echo $id; ?>">
			<div class="form-group">
				<label>Sender Name</label>
				<input type="text" class="form-control" name="senderName" id="senderName" value="<?php echo $user_id; ?>" readonly>
			</div>
			<div class="form-group">
				<label>Schedule Date</label>
				<input type="date" class="form-control" name="senderDate" id="senderDate" value="<?php echo $date; ?>" readonly>
			</div>
			<div class="form-group">
				<label>Shift Name</label>
				<input type="text" class="form-control" name="senderShift" id="senderShift" value="<?php echo $shift_name; ?>" readonly>
			</div>
			<div class="form-group">
				<label>Campaign Name</label>
				<input type="text" class="form-control" name="senderCamp" id="senderCamp" value="<?php echo $campaign_name; ?>" readonly>
			</div>
		</div>
		<div class="col-md-6 float-left">
			<p class="text-center text-white bg-warning mt-2 p-2">Receiver Info</p>
			
			<?php  
			if ($_SESSION['user']==$user_id)
			{
				
				?>
				<div class="form-group">
					<label for="exampleFormControlSelect1">Select Other User</label>
					<select class="form-control" onchange="selecrExchangeType(this.value)" id="selecteExchange">
						<option value="">Select Your Exchange Request</option>
						<option value="schedule">Schedule Exchange</option>
						<option value="off_day">Off Day Exchange</option>
						
					</select>
				</div>
				<div id="loadTypeData">
					
				</div>
				
				<?php
			}else
			{

				?>
				<p class="text-center text-white bg-danger pt-5 pb-5 mt-2">You Can not change other schedule</p>
				<?php
			}

			?>
		</div>
	</div>
	<?php
}

if ($_POST['check']=="receiverNameFind") 
{
	$receiverId=$_POST['receiverId'];
	$userName=mysql_fetch_assoc(mysql_query("SELECT `user_id` FROM `db_shooz`.`fifteen_minute_schedul` WHERE `id`='$receiverId'"));
	echo $userName['user_id'];
}

if ($_POST['check']=="ScheduleRequestStore") 
{

	$senderId=$_POST['senderId'];
	$senderName=$_POST['senderName'];
	$senderDate=$_POST['senderDate'];
	$senderShift=$_POST['senderShift'];
	$senderCamp=$_POST['senderCamp'];
	$selectedUser=$_POST['selectedUser'];
	$receiverNameInfo=$_POST['receiverNameInfo'];
	$checkData=mysql_num_rows(mysql_query("SELECT * FROM `roster`.`roster_chanage` WHERE `sender_id`='$senderId' AND `sender_date`='$senderDate' OR `receiver_id`='$selectedUser'"));

	if ($checkData>0) 
	{
		echo "You Already send a same request..";
	}
	else
	{
		$selectReciverShift=mysql_fetch_assoc(mysql_query("SELECT `shift_name` FROM `db_shooz`.`fifteen_minute_schedul` WHERE `id`='$selectedUser'"));
		$requestSend=mysql_query("INSERT INTO `roster`.`roster_chanage`( `sender_id`, `sender_user_name`, `sender_date`, `sender_shift`, `sender_camp`, `receiver_id`,`receiver_name`,`requested_shift`) VALUES ('$senderId','$senderName','$senderDate','$senderShift','$senderCamp','$selectedUser', '$receiverNameInfo', '".$selectReciverShift['shift_name']."')");
			if ($requestSend) {
				echo "success";
			}
			else
			{
				echo "Something is wrong here..!";
			}
		}
	}

	if ($_POST['check']=="Updatescheduleresult") 
	{
		$status=$_POST['status'];
		$sender_id=$_POST['sender_id'];
		$receiver_id=$_POST['receiver_id'];
		$request_id=$_POST['request_id'];
		$senderName=$_POST['senderName'];
		$receiverName=$_POST['receiverName'];
		$receiverShift=$_POST['receiverShift'];
		$senderShift=$_POST['senderShift'];
		if ($status==1) 
		{
			$updateRequest=mysql_query("UPDATE `roster`.`roster_chanage` SET `status`='$status' WHERE `id`='$request_id'");
			$senderUpdate=mysql_query("UPDATE `db_shooz`.`fifteen_minute_schedul` SET `user_id`='$receiverName' WHERE `id`='$sender_id'");
			$receiverUpdate=mysql_query("UPDATE `db_shooz`.`fifteen_minute_schedul` SET `user_id`='$senderName' WHERE `id`='$receiver_id'");
			if ($updateRequest && $senderUpdate && $receiverUpdate) 
			{
				echo "success";
			}
			else
			{
				echo "Something is wrong";
			}
		}
		if ($status==2) 
		{
			$updateRequest=mysql_query("UPDATE `roster`.`roster_chanage` SET `status`='$status' WHERE `id`='$request_id'");
			if ($updateRequest) 
			{
				echo 'success';
			}
			else
			{
				echo "Something is wrong..!";
			}
		}

	}

	if ($_POST['check']=="selecrExchangeType") 
	{
		$senderName=$_POST['senderName'];
		$senderDate=$_POST['senderDate'];
		$shift_name=$_POST['senderShift'];
		$campaign_name=$_POST['senderCamp'];
		if ($_POST['exType']=="schedule") {
			?>
			<div class="form-group">
				<label for="exampleFormControlSelect1">Select Other User</label>

				<select class="form-control" onchange="receiverNameData(this.value)" id="selectedUser">
					<option>Select User</option>
					<?php 
					$userSelect=mysql_query("SELECT `user_id`,`id`,`shift_name`,`campaign_name`,`create_date` FROM `db_shooz`.`fifteen_minute_schedul` WHERE `campaign_name`='$campaign_name' AND `shift_name`!='$shift_name' AND `create_date`='$senderDate' AND `user_id` !='$senderName'");

					while ($userRow=mysql_fetch_assoc($userSelect)) 
					{
						?>
						<option value="<?php echo $userRow['id']; ?>"><?php echo $userRow['user_id']."( ".$userRow['shift_name']." )"; ?></option>
						<?php
					}
					?>
				</select>
			</div>
			<input type="hidden" name="receiverNameInfo" id="receiverNameInfo">
			<input type="button" class="btn btn-primary" onclick="resuestSchedule()" value="Send Request">
			<?php
		}
		if ($_POST['exType']=="off_day") 
		{
			?>
			<input type="hidden" name="senderId" id="senderId" value="<?php echo $id; ?>">
			<div class="form-group">
				<label>Select User</label>
				<select class="form-control" id="selectOffdateUser">
					<option value="">Select Available User</option>
					<?php 
					// $selectAvailable=mysql_query("SELECT `user_name`,`id`,`user_type`,`campaign_name`,`gender`,`weekName` FROM `roster`.`users_info` WHERE `schedule_date`='$senderDate' AND `active_status`=0 AND `campaign_name`='$campaign_name'");
					$selectAvailable=mysql_query("SELECT `user_name`,`id`,`user_type`,`campaign_name`,`gender`,`weekName`, `active_status`, `schedule_date` FROM `roster`.`users_info` WHERE `schedule_date`='$senderDate' AND `active_status`=0 AND campaign_name='$campaign_name'");
					while ($rowAvailable=mysql_fetch_assoc($selectAvailable)) 
					{
						?>
						<option value="<?php  echo $rowAvailable['user_name']; ?>"><?php  echo $rowAvailable['user_name']; ?></option>
						<?php
					}
					?>
				</select>
			</div>
			<div class="form-group">
				<label>Select Swap Date</label>
				<select class="form-control" id="senderSwapDate">
					<option value="">Select Your Exchange Off Day</option>
					<?php 
					$senderoffDay=mysql_query("SELECT * FROM `roster`.`users_info` WHERE `schedule_date` > '$date' AND `active_status`=0 AND `user_name`='$senderName'");
					while ($offDayRow=mysql_fetch_assoc($senderoffDay)) 
					{
						$checkdate=mysql_fetch_assoc(mysql_query("SELECT COUNT(`create_date`) as 'total' FROM `db_shooz`.`fifteen_minute_schedul` WHERE `create_date`='".$offDayRow['schedule_date']."' AND "));
						if ($checkdate['total']>0) 
						{
							?>
							<option value="<?php echo $offDayRow['schedule_date']; ?>"><?php echo $offDayRow['schedule_date']; ?></option>
							<?php
						}
						
					}
					?>
				</select>
			</div>
			<input type="button" onclick="requestOffDayChange()" class="btn btn-outline-primary" value="Send Request">
			<?php
		}
	}

	if ($_POST['check']=="offdayrequest") 
	{
		$senderId=$_POST['senderId'];
		$senderName=$_POST['senderName'];
		$senderDate=$_POST['senderDate'];
		$senderShift=$_POST['senderShift'];
		$senderCamp=$_POST['senderCamp'];
		$receiverNameInfo=$_POST['receiverNameInfo'];
		$selectOffdateUser=$_POST['selectOffdateUser'];
		$senderSwapDate=$_POST['senderSwapDate'];
		$checkOffDay=mysql_num_rows(mysql_query("SELECT * FROM `off_day_request` WHERE `sender_id`='$senderId' AND `sender_date`='$senderDate' AND `receiver_name`='$selectOffdateUser'"));
		if ($checkOffDay>0) {
			echo "You Already Send A Request";
		}
		else
		{
			$offDayRequest=mysql_query("INSERT INTO `roster`.`off_day_request`(`sender_id`, `sender_user_name`, `sender_date`, `sender_shift`, `sender_camp`, `select_Off_day`, `receiver_name`) VALUES ('$senderId','$senderName','$senderDate','$senderShift','$senderCamp','$senderSwapDate','$selectOffdateUser')");
			if ($offDayRequest) 
			{
				echo "success";
			}
			else
			{
				echo "Something is wrong";
			}
		}
	}

	if ($_POST['check']=="updateoffDate") 
	{
		$id=$_POST['id'];
		$status=$_POST['status'];
		$selectOffData=mysql_fetch_assoc(mysql_query("SELECT * FROM `roster`.`off_day_request` WHERE id='$id'"));

		if ($status==1) 
		{
			$workUpdate=mysql_query("UPDATE `db_shooz`.`fifteen_minute_schedul` SET `user_id`='".$selectOffData['receiver_name']."' WHERE `user_id`='".$selectOffData['sender_user_name']."' AND `shift_name`='".$selectOffData['sender_shift']."' AND `campaign_name`='".$selectOffData['sender_camp']."' AND `create_date`='".$selectOffData['sender_date']."'");



			$updateReceiverOffDay=mysql_query("UPDATE `roster`.`users_info` SET `user_name`='".$selectOffData['receiver_name']."' WHERE `user_name`='".$selectOffData['sender_user_name']."' AND `campaign_name` ='".$selectOffData['sender_camp']."' AND `schedule_date`='".$selectOffData['sender_date']."'");

			$updateSenderOffDay=mysql_query("UPDATE `roster`.`users_info` SET `user_name`='".$selectOffData['sender_user_name']."' WHERE `user_name`='".$selectOffData['receiver_name']."' AND `campaign_name` ='".$selectOffData['sender_camp']."' AND `schedule_date`='".$selectOffData['select_Off_day']."'");



			$workReverseUpdate=mysql_query("UPDATE `db_shooz`.`fifteen_minute_schedul` SET `user_id`='".$selectOffData['sender_user_name']."' WHERE `user_id`='".$selectOffData['receiver_name']."' AND `shift_name`='".$selectOffData['sender_shift']."' AND `campaign_name`='".$selectOffData['sender_camp']."' AND `create_date`='".$selectOffData['select_Off_day']."'");

			$worklateUpdate=mysql_query("UPDATE `db_shooz`.`fifteen_minute_schedul` SET `user_id`='".$selectOffData['sender_user_name']."' WHERE `user_id`='".$selectOffData['receiver_name']."' AND `shift_name`='".$selectOffData['sender_shift']."' AND `campaign_name`='".$selectOffData['sender_camp']."' AND `create_date`='".$selectOffData['select_Off_day']."'");

		// ofdaysawp
		// sender
			$updatelateReceiverOffDay=mysql_query("UPDATE `roster`.`users_info` SET `user_name`='".$selectOffData['receiver_name']."' WHERE `user_name`='".$selectOffData['sender_user_name']."' AND `campaign_name` ='".$selectOffData['sender_camp']."' AND `schedule_date`='".$selectOffData['select_Off_day']."'");

			$updateOffDayTbl=mysql_query("UPDATE `roster`.`off_day_request` SET `status`='1' WHERE `id`='".$id."'");


			if ($workUpdate && $updateReceiverOffDay && $updateSenderOffDay && $workReverseUpdate && $worklateUpdate && $updateOffDayTbl) {
				echo "success";
			}
			else
			{
				echo "Something is wrong..!";
			}

		}
		else
		{
			$updateOffDayTbl=mysql_query("UPDATE `roster`.`off_day_request` SET `status`='2' WHERE `id`='".$id."'");
			if ($updateOffDayTbl) {
				echo "Reject Success";
			}
			else
			{
				echo "Something wrong";
			}
		}


	}
?>