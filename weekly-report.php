<?php
session_start();
include './libs/database.php';
$startDate = date('Y-m-d',strtotime('next monday'));
$nextWeek = date('Y-m-d',strtotime("+7 day", strtotime($startDate)));
$preWeek = date('Y-m-d',strtotime("-7 day", strtotime($startDate)));
$mondayArr=array($startDate);
for ($i=0; $i < 6; $i++) { 
	$endDate = date('Y-m-d',strtotime("+1 day", strtotime($startDate)));
	array_push($mondayArr, $endDate);
	$startDate=$endDate;
}
$offCounter=array();
foreach ($mondayArr as $value) {
	$offCounter[$value] = 0; //Initilize all counter as zero
}


      // Database configuration
$dbHost     = "localhost";
$dbUsername = "root";
$dbPassword = "iHelpBD@2017";
$dbName     = "db_shooz";

      // Create database connection
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

      // Check connection
if ($db->connect_error) {
	die("Connection failed: " . $db->connect_error);
}


      // Query to get columns from table
$query = $db->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'db_shooz' AND TABLE_NAME = 'fifteen_minute_schedul'");

while ($row = $query->fetch_assoc()) {
	$result[] = $row;
}

      // Array of all column names
$columnArr = array_column($result, 'COLUMN_NAME');
include 'main_sidebar.php';
?>
<section class="content" id="weekData">
	<div class="container-fluid">
		<div class="row">	
			<div class="col-md-12">
				<div class="card card-outline card-primary">
					<h5 class="card-header">Weekly Report <button class="btn btn-info float-right m-1" onclick="nextWeek('<?php echo $nextWeek; ?>')">Next Week</button> <button class="btn btn-info float-right m-1" onclick="nextWeek('<?php echo $preWeek; ?>')">Previous Week</button></h5>

					<div class="card-body" id="campaignData">
						<table class="table table-hover table-bordered table-sm" id="campTable">
							<thead>
								<tr>
									<th>Time Interval</th>
									<?php 
									foreach ($mondayArr as $value) {
										?>
										<th><?php echo $value; ?></th>
										<?php
									}
									?>
								</tr>
							</thead>
							<tbody>
								
								<?php 
								$i=0;
								foreach ($columnArr as  $value) {
									++$i;
									if ($i > 7) {

										?>
										<tr>
											<td><?php echo $value; ?></td>
											<?php 
											foreach ($mondayArr as $date) {
												$offDayCheck=mysql_fetch_assoc(mysql_query("SELECT COUNT(*) as 'offday' FROM `roster`.`users_info` WHERE `user_name`='".$_SESSION['user']."' AND `schedule_date`='$date' AND `active_status`='0'"));
												if ($offDayCheck['offday']!=0) 
												{
													if($offCounter[$date] == 0){
														$offCounter[$date] +=1;	
													?>
													<td class="bg-warning text-center" rowspan="96">Day Off</td>
													<?php
													} else {
														$offCounter[$date] +=1;
													}
												}
												else
												{
													$scheduleReport=mysql_fetch_assoc(mysql_query("SELECT * FROM `db_shooz`.`fifteen_minute_schedul` WHERE `user_id`='".$_SESSION['user']."' AND `create_date`='$date'"));
													
													if ($scheduleReport[$value]!='') 
													{
														$colorManage=mysql_fetch_assoc(mysql_query("SELECT `color_code` FROM `db_shooz`.`shift` WHERE `shifts_type`='".$scheduleReport[$value]."'"));
														?>
														<td style="background-color: <?php echo $colorManage['color_code']; ?>;"><?php echo $scheduleReport[$value]; ?></td>
														<?php
													}
													else
													{
														?>
														<td></td>
														<?php
													}
													

												}
											}
										}
										?>
									</tr>
									<?php

								}

								?>

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
</div>

<?php 
include "footer.php";
?>
<script type="text/javascript">
	function nextWeek(weekDate) 
	{
		Swal.fire('Please Wait. Data Loading.');
			Swal.showLoading();
			$.ajax({
				url: 'nextWeek.php',
				method: 'POST',
				data: {
					weekDate: weekDate
				},
				success: function(response) {
					swal.close();
					$("#weekData").html(response);
				}
			});
	}
</script>