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
					<h5 class="card-header">Request View Search</h5>
					<div class="card-body">
						<div class="form-row">
							<div class="form-group col-md-4">
								<input type="date" class="form-control" value="<?php echo $date; ?>" name="startDate" id="startDate">
							</div>
							<div class="form-group col-md-4">
								<input type="date" class="form-control" value="<?php echo $date; ?>" name="endDate" id="endDate">
							</div>

							<div class="form-group col-md-8">
								<label for="inputPassword4"></label>
								<button type="submit" class="btn btn-primary" onclick="viewRequest()">Search</button>
							</div>
						</div>
					</div>
				</div>

			</div>

			<div class="col-md-12">
				<div class="card card-outline card-primary">
					<h5 class="card-header">Exchange Reports</h5>
					<div class="card-body">
						<div id="loadData">


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
	function viewRequest()
	{
		var startDate=$('#startDate').val();
		var endDate=$('#endDate').val();
		$.ajax({
			url: './exchange-request-result.php',
			method: 'POST',
			data: {
				startDate: startDate,
				endDate:endDate
			},
			success: function(response) {
				$("#loadData").html(response);
			}
		});
	}
</script>