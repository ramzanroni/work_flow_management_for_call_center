<?php 
$date = date("Y-m-d");
include './libs/database.php';

if($_POST['check']=="requiredSearch")
{
	$searchDate=$_POST['searchDate'];
	$timeSlot=$_POST['timeSlot'];
	$agentWorkRecord=array();

	// print_r($agentWorkRecord);


	?>
	<table class="table table-hover table-bordered"   id="agentRecord">
		<thead>
			<tr>
				<th>Date</th>
				<th>Time Slot</th>
				<th>Working</th>
				<th>First Break</th>
				<th>Last Break</th>
				<th>Long Break</th>
				<th>Others</th>
				<th>Current</th>
				<th>Required</th>
			</tr>
		</thead>
		<tbody>
			<?php  
			// for ($i=0; $i < count($timeSlot); $i++) 
			foreach ($timeSlot as $timeValue) 
			{ 
				?>
				<tr>
					<td><?php echo $searchDate; ?></td>
					<td><?php echo $timeValue; ?></td>
					<?php

					$dateName=strtolower(date("l", $searchDate));
					$findNeedAgent=mysql_fetch_assoc(mysql_query("SELECT `mainAgent` FROM `db_shooz`.`erlang_calculation` WHERE `dayName`='$dateName'"));
					$AgentNeededData=json_decode($findNeedAgent['mainAgent']);

					foreach ($AgentNeededData as $key => $value) {
						if ($key==$timeValue) 
						{
							$agentNeed=$value;
						}
					}

					$findData=mysql_query("SELECT COUNT(`$timeValue`) AS 'item', `$timeValue` AS 'timesRangeSlot' FROM `db_shooz`.`fifteen_minute_schedul` WHERE `create_date`='$searchDate' AND `$timeValue`!='' GROUP BY `$timeValue`");
					$working=0;
					$first_sort_break=0;
					$last_short_break=0;
					$long_break=0;
					$working=0;
					$others=0;
					while ($findDataRow=mysql_fetch_assoc($findData)) 
					{	
						if ($findDataRow['timesRangeSlot']=="working") 
						{
							$working=$findDataRow['item'];
						}
						elseif ($findDataRow['timesRangeSlot']=="first_short_break") 
						{
							$first_sort_break=$findDataRow['item'];
						}
						elseif ($findDataRow['timesRangeSlot']=="last_short_break") 
						{
							$last_short_break=$findDataRow['item'];
						}
						elseif ($findDataRow['timesRangeSlot']=="lunch_break") 
						{
							$long_break=$findDataRow['item'];
						}
						else
						{
							$others=$findDataRow['item'];
						}
					}
					?>
					<td><?php echo $working; ?></td>
					<td><?php echo $first_sort_break; ?></td>
					<td><?php echo $last_short_break; ?></td>
					<td><?php echo $long_break; ?></td>
					<td><?php echo $others; ?></td>
					<td><?php echo ($working+$first_sort_break+$last_short_break+$long_break+$others); ?></td>
					<td><?php echo $agentNeed; ?></td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
	<?php 
}

?>
<script type="text/javascript">
	$(document).ready( function () {
		$('#agentRecord').DataTable({
			dom: 'Bfrtip',
			buttons: [
			'copyHtml5',
			'excelHtml5',
			'csvHtml5',
			'pdfHtml5'
			]
		});
	} );
</script>