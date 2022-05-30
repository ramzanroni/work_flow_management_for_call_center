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
					<h5 class="card-header">Roster</h5>
					<div class="card-body">
						<div class="form-row">
							<div class="form-group col-md-4">
								<input type="date" class="form-control" value="<?php echo $date; ?>" name="startDate" id="startDate">
							</div>

							<div class="form-group col-md-8">
								<label for="inputPassword4"></label>
								<button type="submit" class="btn btn-primary" onclick="viewRoster()">Search</button>
							</div>
						</div>
					</div>
				</div>

			</div>

			<div class="col-md-12">
				<div class="card card-outline card-primary">
					<h5 class="card-header">Roster Report</h5>
					<div class="card-body" style="overflow:scroll;">
						<div id="loadData">


						</div>
					</div>
				</div>
			</div>
			
			
		</div>
	</div>
</section>
</div>

<div class="modal fade bd-example-modal-lg" id="rosterModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div id="sawpInfo">

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

	function  viewRoster()
	{
		var startDate=$("#startDate").val();
		var check="viewRoster";
		Swal.fire('Please Wait. Data Loading.');
		Swal.showLoading();
		$.ajax({
			url: './rosterAction.php',
			method: 'POST',
			data: {
				startDate: startDate,
				check:check
			},
			success: function(response) {
				swal.close();
				$("#loadData").html(response);
			}
		});
	}


	function sawping(id, user_id, date, shift_name, campaign_name)
	{
		$('#rosterModal').modal('show');
		var check="sawpingData";
		$.ajax({
			url: 'rosterSwap.php',
			method: 'POST',
			data: {
				id: id,
				user_id:user_id,
				date:date,
				shift_name:shift_name,
				campaign_name:campaign_name,
				check:check
			},
			success: function(response) {
				$("#sawpInfo").html(response);
			}
		});
	}
		function resuestSchedule()
	{
		var senderId=$("#senderId").val();
		var senderName=$("#senderName").val();
		var senderDate=$("#senderDate").val();
		var senderShift=$("#senderShift").val();
		var senderCamp=$("#senderCamp").val();
		var selectedUser=$("#selectedUser").val();
		var receiverNameInfo=$("#receiverNameInfo").val();
		var check="ScheduleRequestStore";
		Swal.fire('Please Wait. Data Loading.');
		Swal.showLoading();
		$.ajax({
			url: 'rosterSwap.php',
			method: 'POST',
			data: {
				senderId: senderId,
				senderName:senderName,
				senderDate:senderDate,
				senderShift:senderShift,
				senderCamp:senderCamp,
				selectedUser:selectedUser,
				receiverNameInfo:receiverNameInfo,
				check:check
			},
			success: function(response) {
				if (response=='success') {
					sweetAlert('Request Send Successfully Done. Please Wait for Receiver Response...', 'success');
					$('#rosterModal').modal('hide');
				}
				else
				{
					sweetAlert(response, 'error');
				}
				
			}
		});
	}

	function receiverNameData(receiverId) 
	{
		var check="receiverNameFind";
		$.ajax({
			url: 'rosterSwap.php',
			method: 'POST',
			data: {
				receiverId: receiverId,
				check:check
			},
			success: function(response) {
				$("#receiverNameInfo").val(response);
			}
		});
	}
	function  acceptSchedule(status,sender_id, receiver_id, request_id, senderName, receiverName, receiverShift, senderShift) {
		var check ="Updatescheduleresult";
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
				alert(response);
				window.location.reload();
			}
		});
	}

	function selecrExchangeType(exType)
	{
		// alert(exType);
		var check="selecrExchangeType";
		var senderName=$("#senderName").val();
		var senderDate=$("#senderDate").val();
		var senderShift=$("#senderShift").val();
		var senderCamp=$("#senderCamp").val();
		$.ajax({
			url: 'rosterSwap.php',
			method: 'POST',
			data: {
				exType: exType,
				senderName:senderName,
				senderDate:senderDate,
				senderShift:senderShift,
				senderCamp:senderCamp,
				check:check
			},
			success: function(response) {
				$("#loadTypeData").html(response);
			}
		});
	}

	function requestOffDayChange() 
	{
		var senderId=$("#senderId").val();
		var senderName=$("#senderName").val();
		var senderDate=$("#senderDate").val();
		var senderShift=$("#senderShift").val();
		var senderCamp=$("#senderCamp").val();
		var selectOffdateUser=$("#selectOffdateUser").val();
		var senderSwapDate=$("#senderSwapDate").val();
		var check="offdayrequest";
		if (selectOffdateUser=='' || senderSwapDate=='') {
			sweetAlert('Please Provide all needed data...', 'error');
		}
		else
		{
			$.ajax({
			url: 'rosterSwap.php',
			method: 'POST',
			data: {
				senderId: senderId,
				senderName:senderName,
				senderDate:senderDate,
				senderShift:senderShift,
				senderCamp:senderCamp,
				selectOffdateUser:selectOffdateUser,
				senderSwapDate:senderSwapDate,
				check:check
			},
			success: function(response) {
				// alert(response);
				if (response=='success') {
					sweetAlert('Off day exchange request send Successfully..', 'success');
				}
				else
				{
					sweetAlert(response, 'error');
				}
				// $('#rosterModal').modal('hide');
			}
		});
		}
		
	}

	function acceptOfDay(status,id)
	{
		var check="updateoffDate";
		$.ajax({
			url: 'rosterSwap.php',
			method: 'POST',
			data: {
				status: status,
				id: id,
				check:check
			},
			success: function(response) {
				// alert(response);
				if (response=='success') {
					sweetAlert('Successfully done..', 'success');
					window.location.reload();
				}
				else
				{
					sweetAlert(response, 'error');
				}
				
				// $("#receiverNameInfo").val(response);
			}
		});
	}
// 	function setRoster() {
// 		var start = $("#start").val();
// 		var shift_name = $("#shift_name").val();
// 		var TYPE = "SET_ROSTER";
// 		var userType=$("#userType").val();
// 		var campName=$("#campName").val();
// 		var userName = '';
// 		$.each($("input[name='checkName']:checked"), function(){            
// 			userName+=$(this).val()+',';
// 		});
// 		if ((start != "" && (userName.length!=0))) {
// 			Swal.fire('Please Wait. Data Loading.');
// 			Swal.showLoading();
// 			$.ajax({
// 				url: 'schedule_setup_result.php',
// 				method: 'POST',
// 				data: {
// 					start: start,
// 					shift_name:shift_name,
// 					userType:userType,
// 					campName:campName,
// 					userName:userName,
// 					'TYPE': 'SET_ROSTER'
// 				},
// 				success: function(response) {
// 					console.log(response);
// 					alert(response);
// 					swal.close();
// 					$('#exampleModal').modal('hide');
// 				}
// 			});
// 		} else {
// 			sweetAlert('Please Provide Required Data','error');
// 		}
// 	}







// 	function changeTeam(u_id, column_name) {
// 		var u_id = u_id;
// 		var column_name = column_name;
// 		var status_field_id = "interval_status_" + column_name + "_" + u_id;
// 		var table_data_id= "tabledata_"+column_name+"_"+u_id;
// 		var shedule_status = "shift_change";
// 		var shift_field_value = document.getElementById(status_field_id).value;

// 		$.ajax({
// 			url: 'update_action.php',
// 			method: 'POST',
// 			data: {
// 				u_id: u_id,
// 				column_name: column_name,
// 				shedule_status: shedule_status,
// 				shift_field_value: shift_field_value
// 			},
// 			success: function(response) {
// 				document.getElementById(table_data_id).style.backgroundColor = response;
// 				calculate_work(u_id);
// 				inbound_active_time(u_id);
// 				outbound_active_time(u_id);
// 				other_active_time(u_id);
// 			}
// 		});
// 	}

// // total work
// function calculate_work(u_id){
// 	var calculate_status="calculate_status";
// 	var tabale_data_id="user_"+u_id;
// 	$.ajax({
// 		url: 'update_action.php',
// 		method: 'POST',
// 		data: {
// 			u_id: u_id,
// 			calculate_status:calculate_status
// 		},
// 		success: function(response) {
// 					// alert(response);
// 					document.getElementById(tabale_data_id).innerHTML=response;
// 				}
// 			});
// }
// // inbound time calculation
// function inbound_active_time(u_id)
// {
// 	var inbound_active_time='inbound_active_time';
// 	var inboudColName="inbound_active_time_"+u_id;
// 	$.ajax({
// 		url: 'update_action.php',
// 		method: 'POST',
// 		data: {
// 			u_id: u_id,
// 			inbound_active_time:inbound_active_time
// 		},
// 		success: function(response) {
// 			document.getElementById(inboudColName).innerHTML=response;
// 		}
// 	});
// }

// // outbound active time
// function outbound_active_time(u_id)
// {
// 	var outbound_active_time='outbound_active_time';
// 	var outboudColName="outbound_active_time_"+u_id;
// 	$.ajax({
// 		url: 'update_action.php',
// 		method: 'POST',
// 		data: {
// 			u_id: u_id,
// 			outbound_active_time:outbound_active_time
// 		},
// 		success: function(response) {
// 			document.getElementById(outboudColName).innerHTML=response;
// 		}
// 	});
// }
// // other active time
// function other_active_time(u_id) 
// {
// 	var other_active_time='other_active_time';
// 	var otherColName="other_active_time_"+u_id;
// 	$.ajax({
// 		url: 'update_action.php',
// 		method: 'POST',
// 		data: {
// 			u_id: u_id,
// 			other_active_time:other_active_time
// 		},
// 		success: function(response) {
// 			document.getElementById(otherColName).innerHTML=response;
// 		}
// 	});
// }


// function changeShift(id, field_id) {
// 	var data = $("#shift_" + id + "_" + field_id).val();
// 	var pre = $("#shift_" + id + "_" + field_id).attr("data-field");

// 	$.ajax({
// 		'url': '../reports/roster_setup_result.php',
// 		'method': 'POST',
// 		'data': {
// 			'id': id,
// 			'field': "day_" + field_id,
// 			'data': data,
// 			'TYPE': 'UPDATE_ROSTER',
// 		},
// 		dataType: "json",
// 		success: function(result) {
// 			$(".col_class").removeClass("badge badge-success badge-danger");
// 			var pre_val = $("." + data + "_" + field_id).text();
// 			var add = (parseInt(pre_val) + 1);
// 			$("." + data + "_" + field_id).html('<span class="">' + add + '</span>');
// 			$("." + data + "_" + field_id).addClass("badge badge-success");

// 			$("#shift_" + id + "_" + field_id).attr("data-field", data);

// 			var after = $("." + pre + "_" + field_id).text();
// 			var sub = (parseInt(after) - 1);
// 			$("." + pre + "_" + field_id).html('<span class="">' + sub + '</span>');
// 			$("." + pre + "_" + field_id).addClass("badge badge-danger");

// 		}
// 	});
// }


// function changeType(id) {
// 	var data = $("#type_" + id).val();

// 	$.ajax({
// 		'url': '../reports/roster_setup_result.php',
// 		'method': 'POST',
// 		'data': {
// 			'id': id,
// 			'data': data,
// 			'TYPE': 'UPDATE_TYPE',
// 		},
// 		dataType: "json",
// 		'success': function(data) {

// 		}
// 	});
// }

// function type_select(id) {
// 	var type = $("#type_selection").val();
// 	var type_status = "type_status";
// 	$.ajax({
// 		url: 'update_action.php',
// 		method: 'POST',
// 		data: {
// 			id: id,
// 			type_status: type_status,
// 			type: type
// 		},
// 		success: function(response) {
// 			alert(response);
// 					// $(".table_content").html(response);
// 					// $("p").css("background-color", "yellow");
// 				}
// 			});
// }

// function team_select(id) {
// 	var team = $("#team").val();
// 	var team_status = "team_status";
// 	$.ajax({
// 		url: 'update_action.php',
// 		method: 'POST',
// 		data: {
// 			id: id,
// 			team_status: team_status,
// 			team: team
// 		},
// 		success: function(response) {
// 			alert(response);
// 					// $(".table_content").html(response);
// 					// $("p").css("background-color", "yellow");
// 				}
// 			});
// }

// function selectUser(campaignName) 
// {
// 	var check = "selectUsers";
// 	var start=$("#start").val();
// 	$.ajax({
// 		url: 'update_action.php',
// 		method: 'POST',
// 		data: {
// 			check: check,
// 			campaignName: campaignName,
// 			start: start
// 		},
// 		success: function(response) {
// 			$("#userList").html(response);
// 		}
// 	});	
// }



// $(document).ready(function() {

// 	$('#ckbCheckAll').click(function(e) {
// 		alert("clicked");
// 		/*let isChecked = $('#ckbCheckAll').is(':checked');
// 		if (isChecked){
// 			alert("checked");
// 			//$(".checkBoxClass").attr('checked', "checked");
// 		} else{
// 			alert("not checked");
// 			//$(".checkBoxClass").removeAttr('checked');
// 		}
// 	  //var c = this.checked;
// 	  //$(':checkbox').prop('checked', c);*/
// 	});

// 	$('.show_modal').click(function(e) {
// 		$("#exampleModal").modal("show");
// 	});
// 	$('#search').click(function(e) {
// 		e.preventDefault();
// 		var start_date = $('#start_date').val();
// 		if ((start_date != "")) {
// 			Swal.fire('Please Wait. Data Loading.');
// 			Swal.showLoading();
// 			$.ajax({
// 				url: 'schedule_setup_view.php',
// 				method: 'POST',
// 				data: {
// 					start_date: start_date
// 				},
// 				success: function(response) {
// 					swal.close();
// 					$(".table_content").html(response);

// 				}
// 			});
// 		} else {
// 			alert("Field Can Not Be Blank");
// 		}
// 	});

// 	$('#search_data').click(function(e) {
// 		e.preventDefault();
// 		var start_date = $('#start_date').val();
// 		if ((start_date != "")) {
// 			Swal.fire('Please Wait. Data Loading.');
// 			Swal.showLoading();
// 			$.ajax({
// 				url: 'schedule_data.php',
// 				method: 'POST',
// 				data: {
// 					start_date: start_date
// 				},
// 				success: function(response) {
// 					swal.close();
// 					$(".table_content").html(response);

// 				}
// 			});
// 		} else {
// 			sweetAlert("Field Can Not Be Blank", 'error');
// 		}
// 	});
//})
</script>

