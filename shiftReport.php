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
					<h5 class="card-header">Work Day Report</h5>
					<div class="card-body">
						<div class="col-md-4 float-left">
							<div class="form-group">
								<label>Start Date</label>
								<input type="date" class="form-control" value="<?php echo $date; ?>" name="start_date" id="start_date">
							</div>
						</div>
						<div class="col-md-4 float-left">
							<div class="form-group">
								<label>End Date</label>
								<input type="date" class="form-control" value="<?php echo $date; ?>" name="end_date" id="end_date">
							</div>
						</div>
						<div class="col-md-4 float-left">
							<div class="form-group">
								<label for="userType">Select User Type</label>
								<select class="form-control" id="userType">
									<option value="full_time">Full Time</option>
									<option value="part_time">Part Time</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<input type="button" name="searchRoster" id="searchRoster" onclick="searchRoster()" value="Search" class="btn btn-success form-group">
						</div>
					</div>
				</div>
				
			</div>
			<div class="col-md-12">
				<div class="card card-outline card-primary">
					<h5 class="card-header">Work Day Report</h5>
					<div class="card-body">
						<div id="rosterResult">

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
