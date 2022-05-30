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
					<h5 class="card-header">Roster Manage</h5>
					<div class="card-body">
						<table class="table table-hover table-bordered" id="rosterTbl">
							<thead>
								<tr>
									<th>Sl</th>
									<th>Roster Slot ID</th>
									<th>Shift</th>
									<th>Camp</th>
									<th>Create Date</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php  

								$rosterData=mysql_query("SELECT group_uid, shift_name, campaign_name, create_date FROM `db_shooz`.`fifteen_minute_schedul`GROUP BY `group_uid` ORDER BY create_date DESC");
								$sl=1;
								while ($rosterRow=mysql_fetch_assoc($rosterData)) 
								{
									?>
									<tr>
										<td><?php echo $sl; ?></td>
										<td><?php echo $rosterRow['group_uid']; ?></td>
										<td><?php echo $rosterRow['shift_name']; ?></td>
										<td><?php echo $rosterRow['campaign_name']; ?></td>
										<td><?php echo $rosterRow['create_date']; ?></td>
										<td><a class="btn btn-warning btn-sm" onclick="deleteRoster('<?php echo $rosterRow["group_uid"]; ?>')">Delete</a></td>
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
<?php 
include "footer.php";
?>

<script type="text/javascript">
	$(document).ready( function () {
		$('#rosterTbl').DataTable({
			"pageLength": 25
		});
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

function deleteRoster(groupId)
{
	Swal.fire({
			title: 'Are you sure?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes'
		}).then((result) => {
			if (result.isConfirmed) {
				var check='deleteRosterData';
				$.ajax({
					url: './reports/userAction.php',
					method: 'POST',
					data: {
						check: check,
						groupId:groupId
					},
					success: function(response) {
						if (response=='success') {
							sweetAlert('User Action Successfully Done..','success');
							$("#rosterTbl").load(" #rosterTbl > *");

						}else{
							sweetAlert(response,'error');
						}
					}
				});
			}
		})
}
</script>

