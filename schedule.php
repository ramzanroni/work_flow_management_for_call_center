<?php
$date = date("Y-m-d");
// $date = date("2022-03-07");
$startDate = date('Y-m-d',strtotime('next monday'));
$mondayArr=array($startDate);
for ($i=0; $i < 10; $i++) { 
	$endDate = date('Y-m-d',strtotime("+7 day", strtotime($startDate)));
	array_push($mondayArr, $endDate);
	$startDate=$endDate;
}
include './libs/database.php';
include 'main_sidebar.php';
?>
<section class="content">
	<div class="container-fluid">
		<div class="row">	

			<div class="col-md-12">
				<div class="card card-outline card-primary" id="bodyDiv">
					<h5 class="card-header">Schedule</h5>
					<div class="card-body">
						<div class="col-md-12">
							<div class="col-md-2 float-left">
								<div class="form-group">
									<label>Weekend Name</label>
									<input type="text" name="weekName" id="weekName" placeholder="Weekend Name" class="form-control">
								</div>
							</div>
							<div class="col-md-2 float-left">
								<div class="form-group">
									<label for="userType">Select User Type</label>
									<select class="form-control" id="userType">
										<option value="full_time">Full Time</option>
										<option value="part_time">Part Time</option>
									</select>
								</div>
							</div>
							<div class="col-md-2 float-left">
								<div class="form-group">
									<label>Select User Group</label>
									<select class="form-control" id="userGroup">
										<?php  
										$selectUserGroup=mysql_query("SELECT `user_group` FROM `asterisk`.`vicidial_users` WHERE user_group!='ADMIN' AND user_group!='---ALL---' GROUP BY user_group");
										while($rowGroup=mysql_fetch_assoc($selectUserGroup))
										{
											?>
											<option value="<?php echo $rowGroup['user_group']; ?>"><?php echo $rowGroup['user_group']; ?></option>
											<?php
										}
										?>
									</select>
								</div>
							</div>
							<div class="col-md-2 float-left">
								<div class="form-group">
									<label for="userType">Select User Gender</label>
									<select class="form-control" id="genderType">
										<option value="all">All</option>
										<option value="male">Male</option>
										<option value="female">Female</option>
									</select>
								</div>
							</div>
							<div class="col-md-2 float-left">
								<div class="form-group">
									<label for="userType">Select Schedule Date</label>
									<select class="form-control" id="scheduleDate">
										<option value="">Select Schedule Week</option>
										<?php 
										foreach ($mondayArr as $scheduleDate) {
											?>
											<option value="<?php echo $scheduleDate; ?>"><?php echo $scheduleDate; ?></option>
											<?php
										}
										?>
									</select>
								</div>
							</div>

						</div>
						<input type="button" name="searchRoster" id="searchRoster" onclick="addSchedule()" value="Add Schedule" class="btn btn-success form-group mt-4">
					</div>
				</div>
			</div>
			<div class="row" id="rosterResult">
				
			</div>
			<div class="col-md-12 mt-2" id="weekData">
				<div class="card card-outline card-primary">
					<h5 class="card-header">Weeks</h5>

					<div class="card-body" id="scheduleRecord">
						<table class="table table-hover table-bordered" id="agentRecord">
							<thead>
								<tr>
									<th>Weekend Name</th>
									<th>User Selection</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>

								<?php 
								$reportDate=$date;
								$reportEndDate= date('Y-m-d',strtotime("+200 day", strtotime($reportDate)));
								$weekendName=mysql_query("SELECT DISTINCT(`weekName`) FROM `roster`.`users_info` WHERE schedule_date >='$reportDate' AND schedule_date <='$reportEndDate' GROUP BY `weekName` ORDER BY `id` DESC");
								while ($weekendRow=mysql_fetch_assoc($weekendName)) {
									?>
									<!-- <div class="col-md-2 float-left m-1 bg-primary rounded" onclick="assignCamp('<?php echo $weekendRow["weekName"]; ?>')">
										<p class="p-1 h3 text-center text-white"><?php echo $weekendRow['weekName']; ?></p>
									</div> -->
									<tr>
										<td><?php echo $weekendRow['weekName']; ?></td>
										<td>
											<p class="btn btn-warning text-white p-1" onclick="assignCamp('<?php echo $weekendRow["weekName"]; ?>')">User Selection</p>
											<!-- <div class="col-md-2 float-left m-1 bg-primary rounded" onclick="assignCamp('<?php echo $weekendRow["weekName"]; ?>')">
												<p class="p-1 h3 text-center text-white">User Selection</p>
											</div> -->
										</td>
										<td><p class="btn btn-danger p-2" onclick="deleteSchedule('<?php echo $weekendRow['weekName']; ?>')"><i class="fas fa-trash"></i></p></td>
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


<div class="modal fade bd-example-modal-lg" id="dataModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div id="userData">

			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	$(document).ready( function () {
		$('#agentRecord').DataTable();
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
	function addSchedule() {
		var userType=$("#userType").val();
		var genderType=$("#genderType").val();
		var scheduleDate=$("#scheduleDate").val();
		var weekName=$("#weekName").val();
		var userGroup=$("#userGroup").val();
		var flag=0;
		if (weekName=='') {
			flag=1;
			$("#weekName").css({ "border": "1px solid red" });
		}
		if (flag==0) {
			Swal.fire('Please Wait. Data Loading.');
			Swal.showLoading();
			$.ajax({
				url: 'off-day-management.php',
				method: 'POST',
				data: {
					userType: userType,
					genderType:genderType,
					scheduleDate:scheduleDate,
					weekName:weekName,
					userGroup:userGroup
				},
				success: function(response) {
					swal.close();
					console.log(response);
					if (response=='success') {
						sweetAlert('Add Weekend Success..', 'success');
						$("#weekData").load(" #weekData > *");
					}
					else
					{
						sweetAlert(response, 'error');
					}
				}
			});
		}

	}

	function selectUser(campName) {
		var check="campAssign";
		Swal.fire('Please Wait. Data Loading.');
		Swal.showLoading();
		$.ajax({
			url: 'campaign-management.php',
			method: 'POST',
			data: {
				campName: campName,
				check:check
			},
			success: function(response) {
				swal.close();
				$("#userDataField").html(response);
			}
		});
	}

	function assignCamp(weekName)
	{
		$('#dataModal').modal('show');
		var check="userAssign";
		Swal.fire('Please Wait. Data Loading.');
		Swal.showLoading();
		$.ajax({
			url: 'campaign-management.php',
			method: 'POST',
			data: {
				check: check,
				weekName:weekName
			},
			success: function(response) {
				swal.close();
				$("#userData").html(response);
					// alert(response);
				}
			});
	}

	function saveAssign(weekName)
	{
		var camaignName=$("#camaignName").val();
		var userName = '';
		var check="AssignCamaignName";
		$.each($("input[name='checkName']:checked"), function(){            
			userName+=$(this).val()+',';
		});
		Swal.fire('Please Wait. Data Loading.');
		Swal.showLoading();
		$.ajax({
			url: 'campaign-management.php',
			method: 'POST',
			data: {
				check: check,
				userName:userName,
				weekName:weekName,
				camaignName:camaignName
			},
			success: function(response) {
				swal.close();
				if (response=='success') {
					sweetAlert('User campaign assign success..', 'success');
					$('#dataModal').modal('hide');
					$("#weekData").load(" #weekData > *");
					location.reload();
				}
				else
				{
					sweetAlert("Something is wrong..!", 'error');
				}

			}
		});
	}


	function deleteSchedule(deleteWeekName)
	{
		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			if (result.isConfirmed) {
				var check="deleteSchedule";
				Swal.fire('Please Wait. Data Loading.');
				Swal.showLoading();
				$.ajax({
					url: 'campaign-management.php',
					method: 'POST',
					data: {
						check: check,
						deleteWeekName:deleteWeekName
					},
					success: function(response) {
						swal.close();
						if (response=='success') 
						{
							sweetAlert('Schedule delete success...', 'success');
							$("#scheduleRecord").load(" #scheduleRecord > *");
							$("#bodyDiv").load(" #bodyDiv > *");
						}
						else
						{
							sweetAlert(response, 'error');
						}
					}
				});
			}
		})
	}
</script>
