<?php
$date = date("Y-m-d");
include './libs/database.php';

if (isset($_POST['startDate']) && isset($_POST['end_date']) && isset($_POST['userType'])) {
	$startDate=$_POST['startDate'];
	$end_date=$_POST['end_date'];
	$userType=$_POST['userType'];
	$userDate=mysql_query("SELECT *  FROM `roster`.`users_info` WHERE `user_type`='$userType' AND `schedule_date` >= '$startDate' AND `schedule_date`<='$end_date' ORDER BY `schedule_date` ASC");
	?>
	<table class="table table-striped table-bordered bootstrap-datatable datatable" id="example">
		<thead>
			<tr>
				<th>SL</th>
				<th>User Name</th>
				<th>User Type</th>
				<th>Date</th>
				<th>Work Status</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$sl=1;
			while ($row=mysql_fetch_assoc($userDate)) 
			{
				?>
				<tr>
					<td><?php echo $sl; ?></td>
					<td><?php echo $row['user_name']; ?></td>
					<td><?php echo $row['user_type']; ?></td>
					<td><?php echo $row['schedule_date']; ?></td>
					<?php 
					if ($row['active_status']==1) {
						?>
						<td class="bg-success">Work Day</td>
						<?php
					}
					else
					{
						?>
						<td class="bg-danger">Off Day</td>
						<?php
					}
					?>
				</tr>
				<?php
				$sl++;
			}
			?>
		</tbody>
	</table>
	<script>
		$(document).ready( function () {
			$('#example').DataTable({
				"pageLength": 25
			});
		} );
	</script>
	<?php

}
?>
