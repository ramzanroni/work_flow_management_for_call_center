<?php
session_start();
$date = date("Y-m-d");

include './libs/database.php';
include 'main_sidebar.php';
?>
<section class="content">
	<div class="container-fluid">
		<div class="row">	
			<div class="col-md-12">

				<div class="row justify-content-center" id="rosterSawpData">
					<?php  
					$agent=$_SESSION['user'];
					$checkRequest=mysql_query("SELECT * FROM `roster`.`roster_chanage` WHERE `status`= 0 AND `receiver_name`='$agent'");
					$requestInfo=mysql_fetch_assoc($checkRequest);
					$countRequest=mysql_num_rows($checkRequest);
					if ($countRequest>0) 
					{
						?>
						<div class="col-md-12 mt-5 mb-5 pb-5 " style="border: 2px solid red;">
							<p class="text-center text-black pt-2 pb-2 m-1" >Your have a request for change schedule from <?php echo $requestInfo['sender_user_name']; ?> <span class="text-danger">Request Date: <?php echo $requestInfo['sender_date']; ?></span></p>
							<div class="col-md-12 mt-3">
								<div class="col-md-4 ml-2 pt-4 pb-4 text-center float-left bg-primary" onclick="acceptSchedule(1,'<?php echo $requestInfo['sender_id']; ?>', '<?php echo $requestInfo['receiver_id']; ?>', '<?php echo $requestInfo['id']; ?>', '<?php echo $requestInfo['sender_user_name']; ?>', '<?php echo $requestInfo['receiver_name']; ?>', '<?php echo $requestInfo['requested_shift']; ?>', '<?php echo $requestInfo['sender_shift']; ?>')">
									Accept
								</div>
								<div class="col-md-3 ml-5 pt-4 pb-4 float-left">

								</div>
								<div class="col-md-4 ml-2 pt-4 pb-4 text-center float-left bg-danger" onclick="acceptSchedule(2,'<?php echo $requestInfo['sender_id']; ?>', '<?php echo $requestInfo['receiver_id']; ?>', '<?php echo $requestInfo['id']; ?>', '<?php echo $requestInfo['sender_user_name']; ?>', '<?php echo $requestInfo['receiver_name']; ?>', '<?php echo $requestInfo['requested_shift']; ?>', '<?php echo $requestInfo['sender_shift']; ?>')">
									Reject
								</div>
							</div>
						</div>
						<?php
					}
					
					?>
				</div>

				<div class="card card-outline card-primary">
					<h5 class="card-header">Schedule Exchange Report</h5>
					<div class="card-body">
						<table class="table table-hover table-bordered table-sm" id="example">
							<thead>
								<tr>
									<th>SL</th>
									<th>Sender Name</th>
									<th>Sender Shift</th>
									<th>Sender Campaign</th>
									<th>Receiver Name</th>
									<th>Requested Shift</th>
									<th>Requested Date</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>

								<?php  
								$userName=$_SESSION['user'];
								$sl=1;
								$scheduleResult=mysql_query("SELECT * FROM `roster`.`roster_chanage` WHERE `sender_user_name`='$userName' OR `receiver_name`='$userName'");
								while ($scheduleRow=mysql_fetch_assoc($scheduleResult)) 
								{
									?>
									<tr>
										<td><?php echo $sl; ?></td>
										<td><?php echo $scheduleRow['sender_user_name']; ?></td>
										<td><?php echo $scheduleRow['sender_shift']; ?></td>
										<td><?php echo $scheduleRow['sender_camp']; ?></td>
										<td><?php echo $scheduleRow['receiver_name']; ?></td>
										<td><?php echo $scheduleRow['requested_shift']; ?></td>
										<td><?php echo $scheduleRow['sender_date']; ?></td>
										<td><?php 
										if ($scheduleRow['status']==2) {
											?>
											<p class="btn btn-danger btn-sm">Reject</p>
											<?php
										}
										if ($scheduleRow['status']==1) 
										{
											?>
											<p class="btn btn-primary btn-sm">Accept</p>
											<?php
										}
										if ($scheduleRow['status']==0) 
										{
											?>
											<p class="btn btn-primary btn-sm">Panding</p>
											<?php
										}
									?></td>
								</tr>
								<?php
								$sl++;
							}


							?>

						</tbody>
					</table>
				</div>
			</div>

		</div>

		<div class="col-md-12">
			<div class="row justify-content-center" id="rosterSawpData">
				<?php  
				$agent=$_SESSION['user'];
				$checkOffDayRequest=mysql_query("SELECT * FROM `roster`.`off_day_request` WHERE `status`=0 AND `receiver_name`='$agent'");
				$requestInfoData=mysql_fetch_assoc($checkOffDayRequest);
				$countOffDayReq=mysql_num_rows($checkOffDayRequest);
				if ($countOffDayReq>0) 
				{
					?>
					<div class="col-md-12 mt-5 mb-5 pb-5 " style="border: 2px solid red;">
						<p class="text-center text-black pt-2 pb-2 m-1" >Your have a request for change Off Day from <?php echo $requestInfoData['sender_user_name']; ?> <span class="text-danger">Request Date: <?php echo $requestInfoData['sender_date']; ?></span></p>
						<div class="col-md-12 mt-3">
							<div class="col-md-4 ml-2 pt-4 pb-4 text-center float-left bg-primary" onclick="acceptOfDay(1, '<?php echo $requestInfoData['id']; ?>')">
								Accept
							</div>
							<div class="col-md-3 ml-5 pt-4 pb-4 float-left">

							</div>
							<div class="col-md-4 ml-2 pt-4 pb-4 text-center float-left bg-danger" onclick="acceptOfDay(2, '<?php echo $requestInfoData['id']; ?>')">
								Reject
							</div>
						</div>
					</div>
					<?php
				}
				?>
			</div>
			<div class="card card-outline card-primary">
				<h5 class="card-header">Off Day Exchange Report</h5>
				<div class="card-body">
					<table class="table table-hover table-bordered table-sm" id="example1">
						<thead>
							<tr>
								<th>SL</th>
								<th>Sender Name</th>
								<th>Sender Shift</th>
								<th>Requested Date</th>
								<th>Receiver Name</th>
								<th>Provide Date</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$agent=$_SESSION['user'];
							$sl=1;
							$offDayReport=mysql_query("SELECT * FROM `roster`.`off_day_request` WHERE `sender_user_name`='$agent' OR `receiver_name`='$agent'");
							while ($offDayRow=mysql_fetch_assoc($offDayReport)) 
							{
								?>
								<tr>
									<td><?php echo $sl; ?></td>
									<td><?php echo $offDayRow['sender_user_name']; ?></td>
									<td><?php echo $offDayRow['sender_shift']; ?></td>
									<td><?php echo $offDayRow['sender_date']; ?></td>
									<td><?php echo $offDayRow['receiver_name']; ?></td>
									<td><?php echo $offDayRow['select_Off_day']; ?></td>
									<td><?php 
									if ($offDayRow['status']==2) {
										?>
										<p class="btn btn-danger btn-sm">Reject</p>
										<?php
									}
									if ($offDayRow['status']==1) 
									{
										?>
										<p class="btn btn-primary btn-sm">Accept</p>
										<?php
									}
									if ($offDayRow['status']==0) 
									{
										?>
										<p class="btn btn-primary btn-sm">Panding</p>
										<?php
									}
								?></td>
							</tr>
							<?php
							$sl++;
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

	$(document).ready( function () {
		$('#example').DataTable({
			"pageLength": 25
		});
		$('#example1').DataTable({
			"pageLength": 25
		});
	} );
	function sweetAlert(title, type) {
		var Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 5000
		});
		Toast.fire({
			icon: type,
			title: title
		})
	};
	function acceptSchedule(status,sender_id, receiver_id, request_id, senderName, receiverName, receiverShift, senderShift) {
		var check ="Updatescheduleresult";
		Swal.fire('Please Wait. Data Loading.');
		Swal.showLoading();
		$.ajax({
			url: 'rosterSwap.php',
			method: 'POST',
			data: {
				status: status,
				sender_id:sender_id,
				receiver_id:receiver_id,
				request_id:request_id,
				senderName:senderName,
				receiverName:receiverName,
				receiverShift:receiverShift,
				senderShift:senderShift,
				check:check
			},
			success: function(response) {
				swal.close();
				if (response=='success') {
					sweetAlert('Send Your Response Successfully...', 'success');
					window.location.reload();
				}
				else
				{
					sweetAlert(response, 'error');
				}
				
			}
		});
	}
	function acceptOfDay(status,id)
	{
		var check="updateoffDate";
		Swal.fire('Please Wait. Data Loading.');
		Swal.showLoading();
		$.ajax({
			url: 'rosterSwap.php',
			method: 'POST',
			data: {
				status: status,
				id: id,
				check:check
			},
			success: function(response) {
				swal.close();
				if (response=='success') {
					sweetAlert('Send Your Response Successfully...', 'success');
					window.location.reload();
				}
				else
				{
					sweetAlert(response, 'error');
				}
			}
		});
	}


	

</script>