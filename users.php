<?php
$date = date("Y-m-d");
// $date = date("2022-03-07");
$startDate = date('Y-m-d',strtotime('next monday'));
$mondayArr=array($startDate);
for ($i=0; $i < 4; $i++) { 
	$endDate = date('Y-m-d',strtotime("+7 day", strtotime($startDate)));
	array_push($mondayArr, $endDate);
	$startDate=$endDate;
}
include './libs/database.php';
include 'main_sidebar.php';
?>
<section class="content" id="dataUser">
	<div class="container-fluid">
		<div class="row">	
			<div class="col-md-12">
				<div class="card card-outline card-primary">
					<h5 class="card-header">Users</h5>
					<div class="card-body">
						<div class="col-md-12">
							<table class="table table-hover table-bordered" id="userTbl">
								<thead>
									<tr>
										<th>SL</th>
										<th>User</th>
										<th>Full Name</th>
										<th>User Level</th>
										<th>Gender</th>
										<th>Type</th>
										<th>Status</th>

									</tr>
								</thead>
								<tbody>
									<?php 
									$sl=1;
									$userInfo=mysql_query("SELECT `user_id`,`full_name`,`user`,`user_level`,`custom_one`,`custom_two`,`active` FROM `asterisk`.`vicidial_users` WHERE user_level=1");
									while ($userRow=mysql_fetch_assoc($userInfo)) 
									{
										?>
										<tr>
											<td><?php echo $sl; ?></td>
											<td><?php echo $userRow['user']; ?></td>
											<td><?php echo $userRow['full_name']; ?></td>
											<td><?php echo $userRow['user_level']; ?></td>
											<td><?php echo $userRow['custom_one']; ?></td>
											<td><?php echo $userRow['custom_two']; ?></td>
											<td>
												<?php  
												if ($userRow['active']=='N') 
												{
													?>
													<p class="btn btn-warning btn-sm" onclick="UserAction('<?php echo $userRow['user_id']; ?>', 'Y')">Deactive</p>
													<?php
												}
												else
												{
													?>
													<p class="btn btn-primary btn-sm" onclick="UserAction('<?php echo $userRow['user_id']; ?>', 'N')">Active</p>
													<?php
												}
												?>
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
	function UserAction(userId, status)
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
				var check='ActiveDeacctive';
				$.ajax({
					url: './reports/userAction.php',
					method: 'POST',
					data: {
						check: check,
						userId:userId,
						status:status
					},
					success: function(response) {
						if (response=='success') {
							sweetAlert('User Action Successfully Done..','success');
							$("#dataUser").load(" #dataUser > *");
							setTimeout(dataTblLoad, 1000);
							function dataTblLoad() {
								$('#userTbl').DataTable({
									"pageLength": 25
								});
							}

						}else{
							sweetAlert(response,'error');
						}
					}
				});
			}
		})
	}
	$(document).ready( function () {
		$('#userTbl').DataTable({
			"pageLength": 25
		});
	} );
</script>
