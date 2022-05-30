<?php
session_start();
include './libs/database.php';
$startTime  = new \DateTime('2010-01-01 00:00');
$endTime    = new \DateTime('2010-01-01 23:59');
$timeStep   = 15;
$timeArray  = array();

while($startTime <= $endTime)
{
	$timeArray[] = $startTime->format('H:i');
	$startTime->add(new \DateInterval('PT'.$timeStep.'M'));
}

// var_dump($timeArray);

?>
<?php
include 'main_sidebar.php';
?>
<section class="content">
	<div class="container-fluid">
		<div class="card card-outline card-primary">
			<h5 class="card-header">Add Schedule</h5>
			<div class="card-body">
				<div class="col-md-12">
					<div class="row">		
						<div class="col-md-4 float-left">
							<div class="form-group">
								<label for="userType">Shift Name</label>
								<input type="text" name="shiftName" id="shiftName" class="form-control">
							</div>
						</div>
						<div class="col-md-8 float-left">
							<div class="col-md-3 float-left">
								<div class="form-group">
									<label for="userType">Start Time</label><br>
									<select id="startTime" class="form-control">
										<option value="">Select Start Time</option>
										<?php 
										foreach ($timeArray as $value) {
											?>
											<option value="<?php echo $value; ?>"><?php echo $value; ?></option>	
											<?php
										}
										?>
									</select>
								</div>
							</div>
							<div class="col-md-3 float-left">
								<div class="form-group">
									<label for="userType">End Time</label><br>
									<select id="endTime" class="form-control">
										<option value="">Select Start Time</option>
										<?php 
										foreach ($timeArray as $value) {
											?>
											<option value="<?php echo $value; ?>"><?php echo $value; ?></option>	
											<?php
										}
										?>
									</select>
								</div>
							</div>
							<div class="col-md-6 float-left">
								<br>
								<input type="button" onclick="manageWorkFlow()" class="btn btn-primary" value="Manage Work">
							</div>
						</div>
						<div class="row">
							<div class="col-md-12" id="workFlowResult">

							</div>
							<div class="ml-3">
								<input type="button" name="addShift" id="addShift" class="btn btn-info" value="Add Shift" onclick="addShift()">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="card card-outline card-primary">
			<h5 class="card-header">Schedules</h5>
			<div class="card-body">
				<div class="row" id="shiftDiv" style="overflow: scroll;">
					<table class="table table-hover table-bordered table-sm"   id="shiftData">
						<thead>
							<tr>
								<th>SL</th>
								<th>Shift Name</th>
								<th>Start Time</th>
								<th>End Time</th>
								<th>Work Flow</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$shiftRecord=mysql_query("SELECT * FROM `roster`.`tbl_shift`");
							$sl=1;
							while ($rowShhift=mysql_fetch_assoc($shiftRecord)) 
							{
								?>
								<tr>
									<td><?php echo $sl; ?></td>
									<td><?php echo $rowShhift['shift_name']; ?></td>
									<td><?php echo $rowShhift['start_time']; ?></td>
									<td><?php echo $rowShhift['end_time']; ?></td>
									<td><?php 
									$shift_info = json_decode($rowShhift['shift_info'], true);
									$thead=array();
									$tbody=array();
									foreach ($shift_info as $key => $value) {
										array_push($thead, $key);
										array_push($tbody, $value);
									}
									?>
									<table class="table" >
										<thead>
											<tr>
												<?php 
												foreach ($thead as  $value) {
													?>
													<th><?php echo $value; ?></th>
													<?php
												}
												?>
											</tr>
										</thead>
										<tbody>
											<tr>
												<?php
												foreach ($tbody as  $value) {
													$color=mysql_fetch_assoc(mysql_query("SELECT `color_code` FROM `db_shooz`.`shift` WHERE `shifts_type`='$value'"));
													?>
													<td style="background-color: <?php echo $color['color_code'];?>"><?php echo $value; ?></td>
													<?php
												}
												?>
											</tr>
										</tbody>
									</table>
								</td>
								<td>
									<a class="m-1 p-1" onclick="deleteShift('<?php echo $rowShhift["id"]; ?>')"><i class="btn btn-danger btn-sm fas fa-trash-alt"></i></a>
									<a class="m-1 p-1" onclick="UpdateShift('<?php echo $rowShhift["id"]; ?>')"><i class=" btn btn-info fas fa-edit btn-sm"></i></a>
								</td>
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
</section>
<div class="modal fade" id="modal-lg">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Shift Update</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div id="shiftUpData">
				
			</div>
			
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<?php 
include "footer.php";
?>
<script type="text/javascript">
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


	function manageWorkFlow(){
		var startTime=$("#startTime").val();
		var endTime=$("#endTime").val();
		var check="manageWorkFlow";
		if (startTime!='' && endTime !='') 
		{
			Swal.fire('Please Wait. Data Loading.');
			Swal.showLoading();
			$.ajax({
				url: 'shift-action.php',
				method: 'POST',
				data: {
					startTime: startTime,
					endTime:endTime,
					check:check
				},
				success: function(response) {
					swal.close();
					$("#workFlowResult").html(response);
				}
			});
		}
		else
		{
			sweetAlert("Please provide required field...", 'error');
		}

	}
	function  addShift(){
		var shiftName=$("#shiftName").val();
		var timeArray=$("#timeArray").val();
		var startTime=$("#startTime").val();
		var endTime=$("#endTime").val();
		var timeArr=timeArray.split(",");
		var timelength=timeArr.length;
		var check="addShift";
		var shittArray='';
		for (var i = 0; i < timelength-1; i++) {
			var id=timeArr[i]+'_'+i;
			var workStatus=document.getElementById(id).value;
			shittArray+='"'+timeArr[i]+'"'+':'+'"'+workStatus+'"'+',';
		}
		var lunchCount=(shittArray.match(new RegExp("lunch_break", "g")) || []).length;
		if (lunchCount%2==0) {
			Swal.fire('Please Wait. Data Loading.');
			Swal.showLoading();
			$.ajax({
				url: 'shift-action.php',
				method: 'POST',
				data: {
					shittArray: shittArray,
					shiftName:shiftName,
					startTime:startTime,
					endTime:endTime,
					check:check
				},
				success: function(response) {
					swal.close();
					if (response=="Add Shift Successfully Done..") {
						sweetAlert("Add Shift Successfully Done..", 'success');
						$("#shiftName").val('');
						$("#timeArray").val('');
						$("#endTime").val('');
						$("#shiftDiv").load(" #shiftDiv > *");
						$("#workFlowResult").load(" #workFlowResult > *");
					}
					else
					{
						sweetAlert(response, 'error');
					}

				}
			});
		}
		else
		{
			sweetAlert("Please give lunch brake slot number of double factor...!", 'error');
		}

	}

	function deleteShift(id)
	{
		const check="deleteShift";
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
				Swal.fire('Please Wait. Data Loading.');
				Swal.showLoading();
				$.ajax({
					url: 'shift-action.php',
					method: 'POST',
					data: {
						id: id,
						check:check
					},
					success: function(response) {
						swal.close();
						if (response=='success') {
							sweetAlert("Delete Shift Successfully Done..", 'success');

							$("#shiftDiv").load(" #shiftDiv > *");
						}
						else{
							sweetAlert(response, 'error');
						}

					}
				});
			}
		})

	}
	function UpdateShift(id) 
	{
		var check="updateShiftData";
		$('#modal-lg').modal('show');
		Swal.fire('Please Wait. Data Loading.');
		Swal.showLoading();
		$.ajax({
			url: 'shift-action.php',
			method: 'POST',
			data: {
				id: id,
				check:check
			},
			success: function(response) {
				swal.close();
				$("#shiftUpData").html(response);
					// console.log(response);
				}
			})
	}

	function updateShiftInfo(shiftId)
	{
		var check="updateShiftResult";
		var previousShift=$("#updateid").val();
		previousShift=previousShift.substring(1);
		var shiftArray = previousShift.split(",");
		var indexArray=[];
		for (var i =0; i<shiftArray.length; i++) {
			var arrayIndexData=shiftArray[i];
			var data=arrayIndexData.split('":"')[0];
			var filterData=data.substring(1);
			indexArray.push(filterData);
		}
		var shittArray='';
		for(var j=1; j<=indexArray.length; j++)
		{
			var inputId=indexArray[j-1]+'_'+j;
			var workStatusData=document.getElementById(inputId).value;
			shittArray+='"'+indexArray[j-1]+'"'+':'+'"'+workStatusData+'"'+',';
		}
		var lunchCount=(shittArray.match(new RegExp("lunch_break", "g")) || []).length;
		if (lunchCount%2==0) {
			Swal.fire('Please Wait. Data Loading.');
			Swal.showLoading();
			$.ajax({
				url: 'shift-action.php',
				method: 'POST',
				data: {
					shittArray: shittArray,
					shiftId:shiftId,
					check:check
				},
				success: function(response) {
					swal.close();
					if (response=='success') {
						sweetAlert("Update Shift Successfully", 'success');
						$('#modal-lg').modal('hide');
						$("#shiftDiv").load(" #shiftDiv > *");
					}
					else{
						sweetAlert(response, 'error');
					}

				}
			});
		}
		else
		{
			sweetAlert("Please give lunch brake slot number of double factor...!", 'error');
		}
	}
	$(document).ready( function () {
		$('#shiftData').DataTable();
	} );

</script>
