<?php
$date = date("Y-m-d");
include './libs/database.php';
include 'main_sidebar.php';
$startDate = date('Y-m-d',strtotime('next monday'));
$mondayArr=array($startDate);
for ($i=0; $i < 10; $i++) { 
	$endDate = date('Y-m-d',strtotime("+7 day", strtotime($startDate)));
	array_push($mondayArr, $endDate);
	$startDate=$endDate;
}
?>
<section class="content">
	<div class="container-fluid">
		<div class="row">	
			<div class="col-md-12">
				<div class="card card-outline card-primary">
					<h5 class="card-header">Roster</h5>
					<div class="card-body">
						<div class="form-row">
							<div class="form-group col-md-4">
								<input type="date" class="form-control" value="<?php echo $date; ?>" name="start_date" id="start_date">
							</div>

							<div class="form-group col-md-8">
								<label for="inputPassword4"></label>
								<button type="submit" id="search" class="btn btn-primary">Search Roster</button>
								<button type="submit" class="btn btn-primary show_modal">Weekly Roster</button>
								<!-- <button type="submit" id="search_data" class="btn btn-primary">Adherence percentage</button> -->
								<!-- <a href="" id="export"><button type="submit" class="btn btn-primary" style="margin-top: 24px;">Export</button></a> -->
							</div>
						</div>
					</div>
				</div>

			</div>

			<div class="col-md-12">
				<div class="card card-outline card-primary">
					<h5 class="card-header">Roster Report</h5>
					<div class="card-body" style="overflow:scroll;">
						<div class="table_content">


						</div>
					</div>
				</div>
			</div>
			
			
		</div>
	</div>
</section>
</div>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Add Roster</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					

					<div class="col-md-6 float-left">
						<div class="form-group">
							<label for="recipient-name">Week:</label>
							<!-- <input type="date"  class="form-control" name="start" id="start"> -->

							<label for="userType">Select Schedule Week</label>
							<select class="form-control" id="start">
								<option value="">Select Roster Week</option>
								<?php 
								foreach ($mondayArr as $scheduleDate) {
									?>
									<option value="<?php echo $scheduleDate; ?>"><?php echo $scheduleDate; ?></option>
									<?php
								}
								?>
							</select>
							<!-- <input type="date" onchange="getUserRequired(this.value)" class="form-control" name="start" id="start"> -->
						</div>
					</div>
					<div class="col-md-6 float-left">
						<div class="form-group">
							<label>User Type</label>
							<select id="userType" class="form-control">
								<option value="">Select Your User Type</option>
								<option value="full_time">Full Time</option>
								<option value="part_time">Part Time</option>
							</select>
						</div>
					</div>
					<div class="col-md-6 float-left">
						<div class="form-group">
							<label for="message-text">Shift:</label>
							<select class="form-control" id="shift_name">
								<option selected value="">Select Shift</option>
								<?php  
								$shift_result=mysql_query("SELECT * FROM `roster`.`tbl_shift`");
								while ($rowShift=mysql_fetch_assoc($shift_result)) 
								{
									$shift_info = json_decode($rowShift['shift_info'], true);
									// var_dump($shift_info);
									$night_flag=0;
									foreach ($shift_info as $key => $value) {
										if ($key=='23:15') 
										{
											$night_flag++;
										}
									}
									?>
									<option value="<?php echo $rowShift['shift_name']; ?>"><?php 
									if ($night_flag!=0) 
									{
										echo $rowShift['shift_name'];
										?>
										<p> (Night Shift)</p>
										<?php

									}else
									{
										echo $rowShift['shift_name']; 
									}


								?></option>
								<?php
							}
							?>

						</select>
					</div>
				</div>
				<div class="col-md-6 float-left">
					<div class="form-group">
						<label>Campaign Name</label>
						<select class="form-control" id="campName" onchange="selectManPower()">
							<option value="">Select Campaign Name</option>
							<?php 
							$selectCampData=mysql_query("SELECT DISTINCT(`campaign_name`) FROM `roster`.`users_info` WHERE `campaign_name`!=''");
							while ($campRow=mysql_fetch_assoc($selectCampData)) {
								?>
								<option value="<?php echo $campRow['campaign_name']; ?>"><?php echo $campRow['campaign_name']; ?></option>
								<?php
							}
							?>
						</select>
					</div>
				</div>
				<div class="col-md-12" id="manPowerRecord">

				</div>
				<div id="userList" class="col-md-12">

				</div>
				<!-- <div class="col-md-12">
					<div class="form-group">
					<label>Number of Agent</label>
					<input type="number" name="numberOfAgent" onkeyup="selectManPower()" id="numberOfAgent" class="form-control">
					</div>

				</div> -->
				
			</div>
		</div>

		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary" id="submit" onclick="setRoster()">Set Roster</button>
		</div>
	</div>
</div>
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

// getUserRequired
function getUserRequired(date)
{
	var check="getUserRequired";
	$.ajax({
		url: 'update_action.php',
		method: 'POST',
		data: {
			date: date,
			check:check
		},
		success: function(response) {
			$("#manPowerRecord").html(response);
		}
	});
}

function setRoster() {
	var start = $("#start").val();
	var shift_name = $("#shift_name").val();
	var TYPE = "SET_ROSTER";
	var userType=$("#userType").val();
	var campName=$("#campName").val();
	var userName = '';
	var dayWiseAgent0_error=$("#dayWiseAgent0_error").val();
	var dayWiseAgent1_error=$("#dayWiseAgent1_error").val();
	var dayWiseAgent2_error=$("#dayWiseAgent2_error").val();
	var dayWiseAgent3_error=$("#dayWiseAgent3_error").val();
	var dayWiseAgent4_error=$("#dayWiseAgent4_error").val();
	var dayWiseAgent5_error=$("#dayWiseAgent5_error").val();
	var dayWiseAgent6_error=$("#dayWiseAgent6_error").val();

	var dayWiseAgent0=$("#dayWiseAgent0").val();
	var dayWiseAgent1=$("#dayWiseAgent1").val();
	var dayWiseAgent2=$("#dayWiseAgent2").val();
	var dayWiseAgent3=$("#dayWiseAgent3").val();
	var dayWiseAgent4=$("#dayWiseAgent4").val();
	var dayWiseAgent5=$("#dayWiseAgent5").val();
	var dayWiseAgent6=$("#dayWiseAgent6").val();

	if (dayWiseAgent0_error!='' || dayWiseAgent1_error != '' || dayWiseAgent2_error != '' || dayWiseAgent3_error != '' || dayWiseAgent4_error != '' || dayWiseAgent5_error != '' || dayWiseAgent6_error != '') 
	{
		sweetAlert("Your required agent and abailable agnet are not match. Please follow available and required agent limit.", "error");
	}
	else
	{

		if (start != "" && shift_name!="" &&  userType!="" && campName!="" && dayWiseAgent0 !='' && dayWiseAgent1 !='' && dayWiseAgent2 !='' && dayWiseAgent3 !='' && dayWiseAgent4 !='' && dayWiseAgent5 !='' && dayWiseAgent6 !='') {
			Swal.fire('Please Wait. Data Loading.');
			Swal.showLoading();
			$.ajax({
				url: 'schedule_setup_result_weekly_auto.php',
				method: 'POST',
				data: {
					start: start,
					shift_name:shift_name,
					userType:userType,
					campName:campName,
					dayWiseAgent0:dayWiseAgent0,
					dayWiseAgent1:dayWiseAgent1,
					dayWiseAgent2:dayWiseAgent2,
					dayWiseAgent3:dayWiseAgent3,
					dayWiseAgent4:dayWiseAgent4,
					dayWiseAgent5:dayWiseAgent5,
					dayWiseAgent6:dayWiseAgent6,
					'TYPE': 'SET_ROSTER'
				},
				success: function(response) {
					console.log(response);
					swal.close();
					$('#exampleModal').modal('hide');
				// console.log(response);
			}
		});
		} else {
			sweetAlert('Please Provide Required Data','error');
		}
	}
}







function changeTeam(u_id, column_name) {
	var u_id = u_id;
	var column_name = column_name;
	var status_field_id = "interval_status_" + column_name + "_" + u_id;
	var table_data_id= "tabledata_"+column_name+"_"+u_id;
	var shedule_status = "shift_change";
	var shift_field_value = document.getElementById(status_field_id).value;

	$.ajax({
		url: 'update_action.php',
		method: 'POST',
		data: {
			u_id: u_id,
			column_name: column_name,
			shedule_status: shedule_status,
			shift_field_value: shift_field_value
		},
		success: function(response) {
			document.getElementById(table_data_id).style.backgroundColor = response;
			calculate_work(u_id);
			inbound_active_time(u_id);
			outbound_active_time(u_id);
			other_active_time(u_id);
		}
	});
}

// total work
function calculate_work(u_id){
	var calculate_status="calculate_status";
	var tabale_data_id="user_"+u_id;
	$.ajax({
		url: 'update_action.php',
		method: 'POST',
		data: {
			u_id: u_id,
			calculate_status:calculate_status
		},
		success: function(response) {
					// alert(response);
					document.getElementById(tabale_data_id).innerHTML=response;
				}
			});
}
// inbound time calculation
function inbound_active_time(u_id)
{
	var inbound_active_time='inbound_active_time';
	var inboudColName="inbound_active_time_"+u_id;
	$.ajax({
		url: 'update_action.php',
		method: 'POST',
		data: {
			u_id: u_id,
			inbound_active_time:inbound_active_time
		},
		success: function(response) {
			document.getElementById(inboudColName).innerHTML=response;
		}
	});
}

// outbound active time
function outbound_active_time(u_id)
{
	var outbound_active_time='outbound_active_time';
	var outboudColName="outbound_active_time_"+u_id;
	$.ajax({
		url: 'update_action.php',
		method: 'POST',
		data: {
			u_id: u_id,
			outbound_active_time:outbound_active_time
		},
		success: function(response) {
			document.getElementById(outboudColName).innerHTML=response;
		}
	});
}
// other active time
function other_active_time(u_id) 
{
	var other_active_time='other_active_time';
	var otherColName="other_active_time_"+u_id;
	$.ajax({
		url: 'update_action.php',
		method: 'POST',
		data: {
			u_id: u_id,
			other_active_time:other_active_time
		},
		success: function(response) {
			document.getElementById(otherColName).innerHTML=response;
		}
	});
}


function changeShift(id, field_id) {
	var data = $("#shift_" + id + "_" + field_id).val();
	var pre = $("#shift_" + id + "_" + field_id).attr("data-field");

	$.ajax({
		'url': '../reports/roster_setup_result.php',
		'method': 'POST',
		'data': {
			'id': id,
			'field': "day_" + field_id,
			'data': data,
			'TYPE': 'UPDATE_ROSTER',
		},
		dataType: "json",
		success: function(result) {
			$(".col_class").removeClass("badge badge-success badge-danger");
			var pre_val = $("." + data + "_" + field_id).text();
			var add = (parseInt(pre_val) + 1);
			$("." + data + "_" + field_id).html('<span class="">' + add + '</span>');
			$("." + data + "_" + field_id).addClass("badge badge-success");

			$("#shift_" + id + "_" + field_id).attr("data-field", data);

			var after = $("." + pre + "_" + field_id).text();
			var sub = (parseInt(after) - 1);
			$("." + pre + "_" + field_id).html('<span class="">' + sub + '</span>');
			$("." + pre + "_" + field_id).addClass("badge badge-danger");

		}
	});
}


function changeType(id) {
	var data = $("#type_" + id).val();

	$.ajax({
		'url': '../reports/roster_setup_result.php',
		'method': 'POST',
		'data': {
			'id': id,
			'data': data,
			'TYPE': 'UPDATE_TYPE',
		},
		dataType: "json",
		'success': function(data) {

		}
	});
}

function type_select(id) {
	var type = $("#type_selection").val();
	var type_status = "type_status";
	$.ajax({
		url: 'update_action.php',
		method: 'POST',
		data: {
			id: id,
			type_status: type_status,
			type: type
		},
		success: function(response) {
			alert(response);
					// $(".table_content").html(response);
					// $("p").css("background-color", "yellow");
				}
			});
}

function team_select(id) {
	var team = $("#team").val();
	var team_status = "team_status";
	$.ajax({
		url: 'update_action.php',
		method: 'POST',
		data: {
			id: id,
			team_status: team_status,
			team: team
		},
		success: function(response) {
			alert(response);
					// $(".table_content").html(response);
					// $("p").css("background-color", "yellow");
				}
			});
}

function selectUser(campaignName) 
{
	var check = "selectUsersWeekly";
	var start=$("#start").val();
	var shift_name=$("#shift_name").val();
	var userType=$("#userType").val();
	if (shift_name=='' || userType=='') {
		sweetAlert('Please provide required data..', 'error');
	}
	else{
		$.ajax({
			url: 'update_action.php',
			method: 'POST',
			data: {
				check: check,
				campaignName: campaignName,
				shift_name:shift_name,
				userType:userType,
				start: start
			},
			success: function(response) {
				$("#userList").html(response);
			}
		});
	}

}



$(document).ready(function() {

	$('#ckbCheckAll').click(function(e) {
		alert("clicked");
		/*let isChecked = $('#ckbCheckAll').is(':checked');
		if (isChecked){
			alert("checked");
			//$(".checkBoxClass").attr('checked', "checked");
		} else{
			alert("not checked");
			//$(".checkBoxClass").removeAttr('checked');
		}
	  //var c = this.checked;
	  //$(':checkbox').prop('checked', c);*/
	});

	$('.show_modal').click(function(e) {
		$("#exampleModal").modal("show");
	});
	$('#search').click(function(e) {
		e.preventDefault();
		var start_date = $('#start_date').val();
		if ((start_date != "")) {
			Swal.fire('Please Wait. Data Loading.');
			Swal.showLoading();
			$.ajax({
				url: 'schedule_setup_view.php',
				method: 'POST',
				data: {
					start_date: start_date
				},
				success: function(response) {
					swal.close();
					$(".table_content").html(response);

				}
			});
		} else {
			alert("Field Can Not Be Blank");
		}
	});

	$('#search_data').click(function(e) {
		e.preventDefault();
		var start_date = $('#start_date').val();
		if ((start_date != "")) {
			Swal.fire('Please Wait. Data Loading.');
			Swal.showLoading();
			$.ajax({
				url: 'schedule_data.php',
				method: 'POST',
				data: {
					start_date: start_date
				},
				success: function(response) {
					swal.close();
					$(".table_content").html(response);

				}
			});
		} else {
			sweetAlert("Field Can Not Be Blank", 'error');
		}
	});
})

function selectManPower()
{
	var date=$("#start").val();
	var shift=$("#shift_name").val();
	var check="manpowerprediction";
	var userCount = $("#numberOfAgent").val();
	var userType=$("#userType").val();
	var campName=$("#campName").val();
	// $.each($("input[name='checkName']:checked"), function(){            
	// 	userCount=userCount+1;
	// });
	// alert(numberOfNewMan);
	$.ajax({
		url: 'manpower-calculation-auto.php',
		method: 'POST',
		data: {
			date: date,
			shift:shift,
			userCount:userCount,
			check:check,
			userType:userType,
			campName:campName
		},
		success: function(response) {
			$("#manPowerRecord").html(response);

		}
	});
}

function checkAgent(inputID)
{
	var abailableId="#"+inputID+"_available";
	var availableAgent=parseInt($(abailableId).html());
	var inputAgnetID="#"+inputID;
	var needAgent=$(inputAgnetID).val();
	var errorInput="#"+inputID+"_error";
	
	if (needAgent>availableAgent) 
	{
		$(errorInput).val("limit cross");
		sweetAlert("Your required agent crosses the available agent limit",  'error');
	}
	else
	{
		$(errorInput).val('');
	}
}
</script>

