
<?php
include './libs/database.php';
session_start();
$counter = 1;
$start_date = $_POST['startDate'];
$endDate = date('Y-m-d',strtotime("+10 day", strtotime($start_date)));
// $sql = mysql_query("SELECT * FROM `db_shooz`.`fifteen_minute_schedul` WHERE `create_date` = '$start_date' ORDER BY user_id ASC");
$sql = mysql_query("SELECT * FROM `db_shooz`.`fifteen_minute_schedul` WHERE `create_date` >= '$start_date' AND `create_date`<= '$endDate' ORDER BY `create_date` ASC");

?>
<table class="table table-striped table-bordered bootstrap-datatable datatable table-sm" id="example">
 <thead>
  <tr>
   <?php
      // Database configuration
   $dbHost     = "localhost";
   $dbUsername = "root";
   $dbPassword = "iHelpBD@2017";
   $dbName     = "db_shooz";

      // Create database connection
   $db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

      // Check connection
   if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }


      // Query to get columns from table
  $query = $db->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'db_shooz' AND TABLE_NAME = 'fifteen_minute_schedul'");

  while ($row = $query->fetch_assoc()) {
    $result[] = $row;
  }

      // Array of all column names
  $columnArr = array_column($result, 'COLUMN_NAME');
  $i = 0;
  ?>
  <th>SL</th>
  <th>User ID</th>
  <th>User</th>
  <th>Date</th>
  <th>Shift</th>
  <th >Total Work Hour</th>
   <!--    <th>Team</th>
   	<th>Type</th> -->
   	<?php
   	foreach ($columnArr as  $value) {
   		++$i;
   		if ($i > 7) {

   			?>
   			<th><?php echo $value; ?></th>
   			<?php

   		}
   	}
   	?>
     <th>Working Time</th> 
     <th>Swap</th>
   </tr>
 </thead>
 <tbody>
   <?php while ($row = mysql_fetch_assoc($sql)) {
    ?>
    <tr>
     <td><?php echo $counter++; ?></td>
     <td><?php echo $row['id']; ?></td>
     <td><?php echo $row['user_id']; ?></td>
     <td><?php echo $row['create_date']; ?></td>
     <td><?php echo $row['shift_name']; ?></td>
     <td id="<?php echo "user_".$row['id']; ?>">
      <?php 
      $count=0;
      $j=0;
      foreach ($columnArr as  $c_name) {
       ++$j;
       if ($j > 7) {
        if($row[$c_name]!=''){
         ++$count;
       }

     }
   }
   $work_min= $count*15;
   $hours = floor($work_min / 60);
   $min = $work_min - ($hours * 60);
   echo $hours.":".$min;

   ?>
 </td>

 <?php
 $i = 0;
 foreach ($columnArr as $key) {
   ++$i;
   if ($i > 7) {
    $field_id = $key . "_" . $row['id'];
    $shift_name = $row[$key];
    if ($shift_name != '') {
     $sql_shift = mysql_query("SELECT `color_code` FROM `db_shooz`.`shift` WHERE `shifts_type`='$shift_name'");
     $shift_color_result = mysql_fetch_assoc($sql_shift);
     $color_code = $shift_color_result['color_code'];
   }

   ?>
   <td style="<?php if ($color_code != '') echo "background-color: " . $color_code;  ?>" id="tabledata_<?php echo $field_id; ?>">
    <?php
    include './libs/database.php';
    $check_shift = mysql_query("SELECT `" . $key . "` FROM `db_shooz`.`fifteen_minute_schedul` WHERE `id`='" . $row['id'] . "' AND `create_date`='".$row['create_date']."'");
    $check_shift_result = mysql_fetch_assoc($check_shift);
    ?>

    <p style="color: black;"><?php echo $check_shift_result[$key]; ?></p>
    <!-- <p style="color: black;"><?php echo $key; ?></p> -->
  </td>
  <?php
}
$color_code = 'white';
}

?>
<td id="<?php echo 'other_active_time_'.$row['id']; ?>">
  <?php 

  $count=0;
  $j=0;
  foreach ($columnArr as  $c_name) {
   ++$j;
   if ($j > 7) {
    if($row[$c_name]=='working'){
     ++$count;
   }

 }
}
$work_min= $count*15;
$hours = floor($work_min / 60);
$min = $work_min - ($hours * 60);
echo $hours.":".$min;


?>
</td>
<td>
  <?php 
  if ($_SESSION['user']==$row['user_id']) 
  {
    ?>
    <a  class="btn btn-info" onclick="sawping('<?php echo $row['id']; ?>', '<?php echo $row['user_id']; ?>', '<?php echo $row['create_date']; ?>','<?php echo $row['shift_name']; ?>','<?php echo $row['campaign_name']; ?>')">Swap</a>
    <?php
  }
  ?>
  
</td> 
</tr>
<?php } ?>
</tbody>
</table>
<script>
	$(document).ready( function () {
		$('#example').DataTable();
	} );

</script>