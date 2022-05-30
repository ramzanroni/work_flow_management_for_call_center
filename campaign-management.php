<?php 
include './libs/database.php';
if ($_POST['check']=="campAssign") {
	$userIdArray=array();
	$counter=1;
	$userData=mysql_query("SELECT DISTINCT(`user_name`) FROM `roster`.`users_info`");
	?>
	<div class="border border-primary p-2">
		

		<label class="text-center">Assign Users</label>
		<?php
		while ($userRow=mysql_fetch_assoc($userData)) 
		{
			?>
			<div class="col-md-2 float-left">
				<div class="form-check">
					<input type="checkbox" class="form-check-input" name="checkName" value="<?php echo $userRow['user_name']; ?>">
					<label class="form-check-label" for="exampleCheck1"><?php echo $userRow['user_name']; ?></label>
				</div>
			</div>
			<?php
			array_push($userIdArray, $userRow['user_name']);
		}

		?>
		<input type="text" name="userNameArr" id="userNameArr" value="<?php echo implode(",",$userIdArray); ?>">
	</div>
	<div class="col-md-2">
		<input type="button" onclick="assignCamp()" class="btn btn-info mt-2" value="Assign">
	</div>
	
	<?php
}

if ($_POST['check']=="userAssign") {
	$weekName=$_POST['weekName'];
	$userList=mysql_query("SELECT DISTINCT(user_name) FROM `roster`.`users_info` WHERE weekName='$weekName'");
	?>
	<p class="text-center text-white p-2 btn-info">Users Assign</p>
	<div class="col-md-12">
		<div class="form-group">
			<label>Select Campaign Name</label>
			<select class="form-control" aria-label="Default select example" name="camaignName" id="camaignName">
				<?php 
				$campData=mysql_query("SELECT * FROM `roster`.`campaigns`");
				while ($rowCamp=mysql_fetch_assoc($campData)) {
					?>
					<option><?php echo $rowCamp['campaign_name']; ?></option>
					<?php
				}
				?>
				<option value="">Remove Campaing</option>
			</select>
		</div>
		<p class="text-center text-white bg-primary h4">Select Users</p>
		<div class="col-md-12">
			<div class="form-check">
				<input type="checkbox"  class="form-check-input" id="ckbCheckAll" >
				<label class="text-danger">Select All</label>
			</div>
		</div>
		<?php
		while ($userListRow=mysql_fetch_assoc($userList)) 
		{
			$checkCamp=mysql_fetch_assoc(mysql_query("SELECT * FROM `roster`.`users_info` WHERE `weekName`='$weekName' AND user_name='".$userListRow['user_name']."'"));
			?>
			<div class="col-md-3 float-left">
				<div class="form-check">
					<input type="checkbox" class="form-check-input checkBoxClass" name="checkName" value="<?php echo $userListRow['user_name']; ?>" <?php if ($checkCamp['campaign_name']!='') {
						echo "disabled";
					}  ?>>
					<label class="form-check-label" for="exampleCheck1"><?php echo $userListRow['user_name']; ?><span class="text-danger"><?php if ($checkCamp['campaign_name']!='') {
						echo "(".$checkCamp['campaign_name'].")";
					}  ?></span></label>
				</div>
			</div>
			<?php
		}
		?>
		<input type="button" onclick="saveAssign('<?php echo $weekName; ?>')" class="btn btn-success m-3 p-1" value="Add Assign">
	</div>

	<script type="text/javascript">
		$('#ckbCheckAll').click(function(e) {
			let isChecked = $('#ckbCheckAll').is(':checked');
			if (isChecked){
				$(".checkBoxClass").attr('checked', "checked");
			} else{
				$(".checkBoxClass").removeAttr('checked');
			}
		});
	</script>

	<?php
}

if ($_POST['check']=="AssignCamaignName") 
{
	$weekName=$_POST['weekName'];
	$userName=$_POST['userName'];
	$camaignName=$_POST['camaignName'];
	$userString=rtrim($userName, ",");
	$userArr=explode(",",$userString);
	foreach ($userArr as  $value) {
		$updateAssing=mysql_query("UPDATE `roster`.`users_info` SET`campaign_name`='$camaignName' WHERE `weekName`='$weekName' AND `user_name`='$value'");
	}
	echo "success";
}

if ($_POST['check']=="addCampaignName") 
{
	$campaign=$_POST['campaign'];
	$checkCampData=mysql_fetch_assoc(mysql_query("SELECT COUNT(*) as 'total' FROM `roster`.`campaigns` WHERE `campaign_name`='$campaign'"));
	if ($checkCampData['total']>0) {
		echo "This campaign name already in out database.Please try another campaign name.";
	}
	else
	{
		$addCamp=mysql_query("INSERT INTO `roster`.`campaigns`(`campaign_name`) VALUES ('".$campaign."')");
			if($addCamp) 
			{
				echo "success";
			}
			else
			{
				echo "Something is wrong...!";
			}			
		}

	}

	if ($_POST['check']=="deleteSchedule") 
	{
		$deleteWeekName=$_POST['deleteWeekName'];
		$deleteSchedule=mysql_query("DELETE FROM `roster`.`users_info` WHERE `weekName`='$deleteWeekName'");
		if ($deleteSchedule) 
		{
			echo 'success';
		}
		else
		{
			echo 'Something is wrong...!';
		}
	}
?>