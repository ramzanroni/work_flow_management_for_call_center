<?php
include 'main_sidebar.php';
include './libs/database.php';
?>
<section class="content">
	<div class="container-fluid">
		<div class="row">	
			<div class="col-md-12">
				<div class="card card-outline card-primary">
					<h5 class="card-header">Campaigns <button type="button" class="btn btn-info float-right" data-toggle="modal" data-target="#modal-lg">
						Add Campaign
					</button></h5>

					<div class="card-body" id="campaignData">
						<table class="table table-hover table-bordered" id="campTable">
							<thead>
								<tr>
									<th>Sl</th>
									<th>Campaign Name</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$sl=1;
									$campData=mysql_query("SELECT * FROM `roster`.`campaigns`");
									while ($campRow=mysql_fetch_assoc($campData)) 
									{
										?>
										<tr>
											<td><?php echo $sl; ?></td>
											<td><?php echo $campRow['campaign_name']; ?></td>
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
<div class="modal fade" id="modal-lg">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Campaign</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<input type="text" name="campaign" id="campaign" class="form-control">
				</div>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="addCampaign()">Add Campaign</button>
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
	$(document).ready( function () {
		$('#campTable').DataTable();
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
	function addCampaign()
	{
		var campaign=$("#campaign").val();
		var check="addCampaignName";
		var flag=1;
		if (campaign=='') {
			flag=0;
			$("#campaign").css({ "border": "1px solid red" });
		}
		if (flag==1) {
			$.ajax({
				url: 'campaign-management.php',
				method: 'POST',
				data: {
					check: check,
					campaign:campaign
				},
				success: function(response) {
					if (response=='success') {
						sweetAlert('Campaign Added Successfully Done..','success');
						$("#campaign").val('');
						$('#modal-lg').modal('hide');
						$("#campaignData").load(" #campaignData > *");

					}else{
						sweetAlert(response,'error');
						$("#campaign").val('');
					}
				}
			});
		}

	}
</script>