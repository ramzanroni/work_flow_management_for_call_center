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
					<h5 class="card-header">Erlang</h5>
					<div class="card-body">
						<!-- <div class="col-md-6 float-left">
							<div class="form-group">
								<label>Number of Call <span class="text-danger"> *</span></label>
								<input type="number" name="numberOfCall" id="numberOfCall" class="form-control">
							</div>
						</div> -->
						<div class="col-md-12 p-3 m-1" id="weekDiv">
							<style type="text/css">

								.btnClass:hover {
									cursor: pointer !important;
								}

								.btnClass:active {
									box-shadow: 0 1px #666 !important;
									transform: translateY(2px) !important;
								}
								.btnClass {

									font-size: 15px;
									font-weight: bold;
									height: 35px;
									width: 100px;
									box-shadow: 0 3px #999;
									text-align: center;
								}
								.btn-sık {
									transition: all 0.2s ease;
									background-color: white ;
									border: 2px solid #f44336 !important;
									box-shadow: 0 3px #d32f2f !important;
									min-width: 150px;
									border-radius: 20px;
									margin: 10px;
								}


								btn-sık::selection{
									background: green;
								}

								input[type="radio"] {
									position: absolute;
									visibility: hidden;
								}
								input[type="radio"] + div {
									position: relative;
								}
								input[type="radio"]:checked + div {
									background-color: #ef5350;
								}
								input[type="radio"]:checked + div>span {
									color: white;
								}
								input[type="radio"] + div>span {
									position: relative;
									top: 25%;}

									input[type="radio"]:checked + div::before {
										font-family: FontAwesome;
										content: "\f08d";
										position: absolute;
										bottom: 31px;
										font-size: 21px;
										color: white;
										right: -5px;
										-webkit-transform: rotate(30deg);
										-moz-transform: rotate(30deg);
										-o-transform: rotate(30deg);
										-ms-transform: rotate(30deg);
										transform: rotate(30deg);
										animation: fall 0.5s forwards;
									}

									@keyframes fall {
										100% {
											-webkit-transform: translate(-5px,5px) rotate(30deg);
											-moz-transform: translate(-5px,5px) rotate(30deg);
											-o-transform: translate(-5px,5px) rotate(30deg);
											-ms-transform: translate(-5px,5px) rotate(30deg);
											transform: translate(-5px,5px) rotate(30deg);
										}
									}

									@-moz-keyframes fall {
										100% {
											-webkit-transform: translate(-5px,5px) rotate(30deg);
											-moz-transform: translate(-5px,5px) rotate(30deg);
											-o-transform: translate(-5px,5px) rotate(30deg);
											-ms-transform: translate(-5px,5px) rotate(30deg);
											transform: translate(-5px,5px) rotate(30deg);
										}
									}
								</style>
								<label>Select Day Name:<span class="text-danger"> *</span></label><br>
								<label>
									<input  type="radio"  name="dayName" id="dayName" value="saturday"> 
									<div class="btnClass btn-sık"><span>Saturday</span></div>
								</label>
								<label>
									<input  type="radio"  name="dayName" id="dayName" value="sunday"> 
									<div class="btnClass btn-sık"><span>Sunday</span></div>
								</label>
								<label>
									<input type="radio" name="dayName" id="dayName" value="monday"> 
									<div  class="btnClass btn-sık"><span>Monday</span></div>
								</label>
								<label>
									<input  type="radio"  name="dayName" id="dayName" value="tuesday"> 
									<div class="btnClass btn-sık"><span>Tuesday</span></div>
								</label>
								<label>
									<input  type="radio"  name="dayName" id="dayName" value="wednesday"> 
									<div class="btnClass btn-sık"><span>Wednesday</span></div>
								</label>
								<label>
									<input  type="radio"  name="dayName" id="dayName" value="thursday"> 
									<div class="btnClass btn-sık"><span>Thursday</span></div>
								</label>
								<label>
									<input  type="radio"  name="dayName" id="dayName" value="friday"> 
									<div class="btnClass btn-sık"><span>Friday</span></div>
								</label>
								
							</div>
							<div class="col-md-6 float-left">
								<div class="form-group">
									<label>Period Length (In Sec) <span class="text-danger"> *</span></label>
									<input type="number" readonly name="periodLenth" id="periodLenth" value="15" class="form-control">
								</div>
							</div>
							<div class="col-md-6 float-left">
								<div class="form-group">
									<label>Average Handaling Time (In Seconds) <span class="text-danger"> *</span></label>
									<input type="number" name="averageHandalingTime" id="averageHandalingTime" class="form-control">
								</div>
							</div>
							<div class="col-md-6 float-left">
								<div class="form-group">
									<label>Required Service Level (%) <span class="text-danger"> *</span></label>
									<input type="number" name="serviceLevel" id="serviceLevel" class="form-control">
								</div>
							</div>
							<div class="col-md-6 float-left">
								<div class="form-group">
									<label>Target Answer Time (In Seconds) <span class="text-danger"> *</span></label>
									<input type="number" name="targetAnswerTime" id="targetAnswerTime" class="form-control">
								</div>
							</div>
							<div class="col-md-6 float-left">
								<div class="form-group">
									<label>Maximum Occupancy (%) <span class="text-danger"> *</span></label>
									<input type="number" name="maximumOccupancy" id="maximumOccupancy" class="form-control">
								</div>
							</div>
							<div class="col-md-6 float-left">
								<div class="form-group">
									<label>Shrinkage (In %) <span class="text-danger"> *</span></label>
									<input type="number" name="shrinkage" id="shrinkage" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-6 float-left">

									<input type="button"  class="form-control btn btn-primary" value="Calculate" onclick="erlangCalculationData()">
								</div>
								<div class="col-md-6 float-left" id="saveAgentData" style="display: none;">
									<input type="button"  class="form-control btn btn-primary" value="Save" onclick="getAgentData()">
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="card card-outline card-primary">
							<h5 class="card-header">Forcasting Output</h5>
							<div class="card-body" id="erlangData">

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
		function erlangCalculationData()
		{
		// var numberOfCall= $("#numberOfCall").val();
		var periodLenth= $("#periodLenth").val();
		var averageHandalingTime= $("#averageHandalingTime").val();
		var serviceLevel= $("#serviceLevel").val();
		var targetAnswerTime= $("#targetAnswerTime").val();
		var maximumOccupancy= $("#maximumOccupancy").val();
		var shrinkage= $("#shrinkage").val();
		var dayName = $("input[name='dayName']:checked").val();
		var check="calculateErlang";
		var flag=0;
		if (periodLenth=='') 
		{
			flag=1;
			$("#periodLenth").css({"border": "1px solid red"});
		}
		if (averageHandalingTime=='') 
		{
			flag=1;
			$("#averageHandalingTime").css({"border": "1px solid red"});
		}
		if (serviceLevel=='') 
		{
			flag=1;
			$("#serviceLevel").css({"border": "1px solid red"});
		}
		if (targetAnswerTime=='') 
		{
			flag=1;
			$("#targetAnswerTime").css({"border": "1px solid red"});
		}
		if (maximumOccupancy=='') 
		{
			flag=1;
			$("#maximumOccupancy").css({"border": "1px solid red"});
		}
		if (shrinkage=='') 
		{
			flag=1;
			$("#shrinkage").css({"border": "1px solid red"});
		}
		if (dayName==null) 
		{
			flag=1;
			$("#weekDiv").css({"border": "1px solid red"});
		}
		if (flag==0) 
		{
			Swal.fire('Please Wait. Data Loading.');
			Swal.showLoading();
			$.ajax({
				url: 'erlang-calculation.php',
				method: 'POST',
				data: {
					periodLenth:periodLenth,
					averageHandalingTime:averageHandalingTime,
					serviceLevel:serviceLevel,
					targetAnswerTime:targetAnswerTime,
					maximumOccupancy:maximumOccupancy,
					shrinkage:shrinkage,
					dayName:dayName,
					check:check
				},
				success: function(response) {
					swal.close();
					$("#saveAgentData").css("display", "inline");
					$("#erlangData").html(response);
				}
			});
		}
	}

	// getAgentData
	function getAgentData() 
	{
		var numberOfCall='';
		var numberOfAgent='';
		var serviceLevel='';
		var occupancyData='';
		var mainAgent='';
		var dayName = $("input[name='dayName']:checked").val();
		var check="storeCalculatedData";
		var length=$("#length").val();
		for (var i = 0; i < length; i++) {
			var timeID="#slot_"+i;
			var time=$(timeID).html();
			var numberOfCallID="#numCall_"+i;
			var numberOfCallCount=$(numberOfCallID).val();
			numberOfCall+='"'+time+'":"'+numberOfCallCount+'",';

			var agentID="#numAgent_"+i;
			var numberOfAgentValue=$(agentID).val();
			numberOfAgent+='"'+time+'":"'+numberOfAgentValue+'",';

			var serviceLevelID="#serviceLevel_"+i;
			var serviceLevelValue=$(serviceLevelID).val();
			serviceLevel+='"'+time+'":"'+serviceLevelValue+'",';

			var occupancyID="#occupancy_"+1;
			var occupancyValue=$(occupancyID).val();
			occupancyData+='"'+time+'":"'+occupancyValue+'",';

			var numberOfAgentID="#numberOfAgent_"+i;
			var numberOfMainAgent=$(numberOfAgentID).val();
			mainAgent+='"'+time+'":"'+numberOfMainAgent+'",';

		}
		// console.log(mainAgent);
		Swal.fire('Please Wait. Data Loading.');
		Swal.showLoading();
		$.ajax({
			url: 'erlang-calculation-data.php',
			method: 'POST',
			data: {
				numberOfCall:numberOfCall,
				numberOfAgent:numberOfAgent,
				serviceLevel:serviceLevel,
				occupancyData:occupancyData,
				mainAgent:mainAgent,
				dayName:dayName,
				check:check
			},
			success: function(response) {
				swal.close();
				if (response=="success" || response=="up")
				{
					sweetAlert("Calculation Store Success..", 'success');
					location.reload();
				}
				else
				{
					sweetAlert(response, 'error');
				}
			}
		});
		
	}
</script>