<?php
include './libs/database.php';
$counter = 1;
$start_date = $_POST['start_date'];
$sql = mysql_query("SELECT * FROM `db_shooz`.`fifteen_minute_schedul` WHERE `create_date` = '" . $start_date . "' ORDER BY user_id ASC");

?>

<table class="table table-striped table-bordered bootstrap-datatable datatable" id="example" width="50%">
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
      array_pop($columnArr);
      $i = 0;
      ?>
      <th>SL</th>
      <th>User</th>
      <th>Shift</th>
      <th>Campaign</th>
      <th>Date</th>
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
      <!-- <th>Inbound Active Time</th>
        <th>Out Bound Active Time</th>-->
        <th>Group ID</th>
        <th>Working Time</th> 

      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysql_fetch_assoc($sql)) {
        ?>
        <tr>
          <td><?php echo $counter++; ?></td>
          <td><?php echo $row['user_id']; ?></td>
          <td><?php echo $row['shift_name']; ?></td>
          <td><?php echo $row['campaign_name']; ?></td>
          <td><?php echo $row['create_date']; ?></td>
         <!--  <td id="<?php echo "user_".$row['id']; ?>">
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
          </td> -->
        <!-- <td>
          <select name="team" class="form-select" required="" id="team" onchange="team_select('<?php echo $row['id']; ?>')" style="width: 87px;">
            <option value="">Select Team</option>
            <?php
            include '../libs/database.php';
            $shift_sql = mysql_query('SELECT * FROM `db_shooz`.`team`');
            $result = mysql_query($query);
            while ($shift_row = mysql_fetch_assoc($shift_sql)) { ?>
              <option value="<?php echo $shift_row['team_name']; ?>" <?php if($row['team']==$shift_row['team_name']){ echo 'selected';} ?>><?php echo $shift_row['team_name']; ?></option>
            <?php } ?>
          </select>
        </td> -->
       <!--  <td>
          <select class="form-select" aria-label="Default select example" id="type_selection" onchange="type_select('<?php echo $row['id']; ?>')" style="width: 86px;">
            <option selected>Select Type</option>
            <option value="part time" <?php if($row['type']=='part time'){ echo 'selected';} ?> >Part Time</option>
            <option value="full time" <?php if($row['type']=='full time'){ echo 'selected';} ?>>Full Time</option>
          </select>
        </td> -->

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
              <select name="type" class="form-control" id="interval_status_<?php echo $field_id; ?>" required onchange="changeTeam('<?php echo $row['id']; ?>','<?php echo $key; ?>')" id="type" style="width: 114px;">
                <option value="">Select Shift</option>
                <?php
                include '../libs/database.php';
                $shift_sql = mysql_query('SELECT * FROM `db_shooz`.`shift`');
                while ($shift_row = mysql_fetch_assoc($shift_sql)) {
                  $check_shift = mysql_query("SELECT `" . $key . "` FROM `db_shooz`.`fifteen_minute_schedul` WHERE `id`='" . $row['id'] . "' AND `create_date`='$start_date'");
                  $check_shift_result = mysql_fetch_assoc($check_shift);
                  ?>
                  <option style="background-color: <?php echo $shift_row['color_code']; ?>;" value="<?php echo $shift_row['shifts_type']; ?>" <?php if ($shift_row['shifts_type'] == $check_shift_result[$key]) echo "selected"; ?>><?php echo $shift_row['shifts_type']; ?></option>
                <?php } ?>
              </select>
            </td>

            <?php
          }
          $color_code = 'white';
        }

        ?>
    <!--     <td id="<?php echo 'inbound_active_time_'.$row['id']; ?>">
          <?php 

          $count=0;
          $j=0;
          foreach ($columnArr as  $c_name) {
           ++$j;
           if ($j > 7) {
             if($row[$c_name]=='Inbound active time'){
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
       <td id="<?php echo 'outbound_active_time_'.$row['id']; ?>">
         <?php 

         $count=0;
         $j=0;
         foreach ($columnArr as  $c_name) {
           ++$j;
           if ($j > 7) {
             if($row[$c_name]=='Outbound active time'){
               ++$count;
             }

           }
         }
         $work_min= $count*15;
         $hours = floor($work_min / 60);
         $min = $work_min - ($hours * 60);
         echo $hours.":".$min;


         ?>
       </td>-->
       <td><?php echo $row['group_uid']; ?></td>
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
   </tr>
 <?php } ?>
</tbody>
</table>

<script>
	$(document).ready( function () {
		// $('#example').DataTable({
  //     "pageLength": 50,
  //     fixedHeader: true
  //   });
  var table = $('#example').DataTable( {
        "pageLength": 10,
    } );
 
    // new $.fn.dataTable.FixedHeader( table );
	} );
</script>