<?php
$date = date("Y-m-d");
include './libs/database.php';
include 'main_sidebar.php';
?>
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-outline card-primary">
					<h5 class="card-header">Weekly Forcasting Report</h5>
					<div class="card-body">
						<div id="rosterResult" style="overflow: scroll;">
							<table class="table table-hover table-bordered" >
								<!-- <thead>
									<tr>
										<th>Saturday</th>
										<th>Saturday</th>
										<th>Saturday</th>
										<th>Saturday</th>
										<th>Saturday</th>
										<th>Saturday</th>
										<th>Saturday</th>
									</tr>
								</thead> -->
								<tbody>
									<?php  
									$forcastingReport=mysql_query("SELECT * FROM `db_shooz`.`erlang_calculation`");
									while ($rowForcasting=mysql_fetch_assoc($forcastingReport)) 
									{
										?>
										<tr>
											<td><?php echo $rowForcasting['dayName']; ?></td>
											<td>
												<table>
													<thead>
														<tr>
															<?php
															$timeArray=json_decode($rowForcasting['numberOfCall']);
															foreach ($timeArray as $key => $value) {
																?>
																<th><?php echo $key; ?></th>
																<?php
															}
															?>
														</tr>
													</thead>
													<tbody>
														<tr>
															<?php
															$agentArr=json_decode($rowForcasting['mainAgent']);
															foreach ($agentArr as $timeAgent => $agentValue) {
																?>
																<td><?php echo $agentValue; ?></td>
																<?php
															}
															?>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
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
</div>
</section>
</div>
<?php 
include "footer.php";
?>

<script type="text/javascript">
	function searchRoster() {
		var startDate=$("#start_date").val();
		var end_date=$("#end_date").val();
		var userType=$("#userType").val();
		Swal.fire('Please Wait. Data Loading.');
		Swal.showLoading();
		$.ajax({
			url: 'shiftResult.php',
			method: 'POST',
			data: {
				startDate: startDate,
				end_date:end_date,
				userType:userType
			},
			success: function(response) {
				swal.close();
				$("#rosterResult").html(response);
			}
		});
	}
</script>
