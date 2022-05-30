<?php
$date = date("Y-m-d");
include './libs/database.php';
include 'main_sidebar.php';
$startTime  = new \DateTime('2010-01-01 00:00');
$endTime    = new \DateTime('2010-01-01 23:59');
$timeStep   = 15;
$timeArray  = array();

while($startTime <= $endTime)
{
	$timeArray[] = $startTime->format('H:i');
	$startTime->add(new \DateInterval('PT'.$timeStep.'M'));
}
$timeSlot=array();
foreach ($timeArray as  $value) {
	$selectedTime = "9:15:00";
	$endTime = strtotime("+15 minutes", strtotime($value));
	$slot=date('H:i', $endTime);
	if ($slot=="00:00") 
	{
		$slot="23:59";
	}
	array_push($timeSlot, $slot);
}
?>
<section class="content">
	<div class="container-fluid">
		<div class="row">	
			<div class="col-md-12">
				<div class="card card-outline card-primary">
					<h5 class="card-header">Request View Search</h5>
					<div class="card-body">
						<div class="form-row">
							<div class="form-group col-md-4">
								<label>Select Date</label>
								<input type="date" class="form-control" value="<?php echo $date; ?>" name="searchDate" id="searchDate">
							</div>
							<div class="col-md-8">
								<div class="form-group">
									<label>Select Time Slot</label>
									<div class="select2-purple">
										<select class="select2" multiple="multiple" id="timeSlot" placeholder="Select Time Slot" data-dropdown-css-class="select2-info" style="width: 100%;">
											<?php 
												foreach ($timeSlot as $timeValue) {
													?>
													<option value="<?php echo $timeValue; ?>"><?php echo $timeValue; ?></option>
													<?php
												}
											?>
										</select>
									</div>
								</div>
							</div>

							<div class="form-group col-md-8">
								<label for="inputPassword4"></label>
								<button type="submit" class="btn btn-primary" onclick="findData()">Search</button>
							</div>
						</div>
					</div>
				</div>

			</div>

			<div class="col-md-12">
				<div class="card card-outline card-primary">
					<h5 class="card-header">Search Result</h5>
					<div class="card-body">
						<div id="reportData">


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

	
	$( document ).ready(function() {
		$('.select2').select2();
	});

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

	function findData()
	{
		var searchDate=$("#searchDate").val();
		var timeSlot=$("#timeSlot").val();
		var check='requiredSearch';
		if (searchDate=='' || timeSlot=='') 
		{
			sweetAlert("Please Select Search Item.", "error");
		}
		else
		{
			Swal.fire('Please Wait. Data Loading.');
			Swal.showLoading();
			$.ajax({
				url: 'required-report-result.php',
				method: 'POST',
				data: {
					searchDate: searchDate,
					timeSlot:timeSlot,
					check:check
				},
				success: function(response) {
					swal.close();
					console.log(response);
					$("#reportData").html(response);
				}
			});
		}

	}
</script>