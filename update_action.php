<?php
include './libs/database.php';
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

if ($_POST['shedule_status'] == "shift_change") {
    $u_id = $_POST['u_id'];
    $column_name = $_POST['column_name'];
    $shift_field_value = $_POST['shift_field_value'];
    $shift_update_result = mysql_query("UPDATE `db_shooz`.`fifteen_minute_schedul` SET `" . $column_name . "`='" . $shift_field_value . "' WHERE `id`='" . $u_id . "'");
    if ($shift_update_result) {
        // echo "Shift Update successfully.";
        $color_find=mysql_query("SELECT `color_code` FROM `db_shooz`.`shift` WHERE `shifts_type`='".$shift_field_value."'");
        $color_data=mysql_fetch_assoc($color_find);
        echo $color_data['color_code'];
    } else {
        echo "Something is wrong...!";
    }
}
if ($_POST['type_status'] == "type_status") {
    $user_id = $_POST['id'];
    $type = $_POST['type'];
    $type_update_result = mysql_query("UPDATE `db_shooz`.`fifteen_minute_schedul` SET `type`='" . $type . "' WHERE `id`='" . $user_id . "'");
    if ($type_update_result) {
        echo "Type Update successfully.";
    } else {
        echo "Something is wrong...!";
    }
}
if ($_POST['team_status'] == "team_status") {
    $user_id = $_POST['id'];
    $team = $_POST['team'];
    $team_update_result = mysql_query("UPDATE `db_shooz`.`fifteen_minute_schedul` SET `team`='" . $team . "' WHERE `id`='" . $user_id . "'");
    if ($team_update_result) {
        echo "Team Update successfully.";
    } else {
        echo "Something is wrong...!";
    }
}
if($_POST['calculate_status']=="calculate_status")
{
    $u_id=$_POST['u_id'];
    // Database configuration

    $i = 0;

    $calculate_data=mysql_fetch_assoc(mysql_query("SELECT * FROM `db_shooz`.`fifteen_minute_schedul` WHERE `id` = '" . $u_id . "'"));
    
    $count=0;
    $j=0;
    foreach ($columnArr as  $c_name) {
      ++$j;
      if ($j > 5) {
        if($calculate_data[$c_name]!=''){
          ++$count;
      }

  }
}
$work_min= $count*15;
$hours = floor($work_min / 60);
$min = $work_min - ($hours * 60);
echo $hours.":".$min;
}

// inbound_active_time Inbound active time
if ($_POST['inbound_active_time']=="inbound_active_time") {
    $user_id= $_POST['u_id'];
    $i = 0;

    $calculate_data=mysql_fetch_assoc(mysql_query("SELECT * FROM `db_shooz`.`fifteen_minute_schedul` WHERE `id` = '".$user_id."'"));
    
    $count=0;
    $j=0;
    foreach ($columnArr as  $c_name) {
      ++$j;
      if ($j > 5) {
        if($calculate_data[$c_name]=='Inbound active time'){
          ++$count;
      }

  }
}
$work_min= $count*15;
$hours = floor($work_min / 60);
$min = $work_min - ($hours * 60);
echo $hours.":".$min;
}

// outbound_active_time
if ($_POST['outbound_active_time']=="outbound_active_time") 
{
    $user_id= $_POST['u_id'];
    $i = 0;

    $calculate_data=mysql_fetch_assoc(mysql_query("SELECT * FROM `db_shooz`.`fifteen_minute_schedul` WHERE `id` = '".$user_id."'"));
    
    $count=0;
    $j=0;
    foreach ($columnArr as  $c_name) {
      ++$j;
      if ($j > 5) {
        if($calculate_data[$c_name]=='Outbound active time'){
          ++$count;
      }

  }
}
$work_min= $count*15;
$hours = floor($work_min / 60);
$min = $work_min - ($hours * 60);
echo $hours.":".$min;
}
// other_active_time
if ($_POST['other_active_time']=="other_active_time") 
{
    $user_id= $_POST['u_id'];
    $i = 0;

    $calculate_data=mysql_fetch_assoc(mysql_query("SELECT * FROM `db_shooz`.`fifteen_minute_schedul` WHERE `id` = '".$user_id."'"));
    
    $count=0;
    $j=0;
    foreach ($columnArr as  $c_name) {
      ++$j;
      if ($j > 5) {
        if($calculate_data[$c_name] !='Outbound active time' && $calculate_data[$c_name] !='Inbound active time' &&  $calculate_data[$c_name] !=''){
          ++$count;
      }

  }
}
$work_min= $count*15;
$hours = floor($work_min / 60);
$min = $work_min - ($hours * 60);
echo $hours.":".$min;
}

if ($_POST['check']=="selectUsers") 
{
    $campaignName=$_POST['campaignName'];
    $date=$_POST['start'];
    $next = date('Y-m-d',strtotime("+1 day", strtotime($date)));
    $userType=$_POST['userType'];
    $shift_name=$_POST['shift_name'];
    $selectUser=mysql_query("SELECT * FROM `roster`.`users_info` WHERE `schedule_date`='$date' AND `user_type`='$userType' AND `campaign_name`='$campaignName' AND `active_status`=1 ");
    ?>
    <!-- <div class="col-md-12">
     <p class="bg-info pt-1 pb-1 text-center text-white">Select user for make shift</p>
 </div> -->

 <div class="col-md-12">
    <!-- <p class="bg-info pt-1 pb-1 text-center text-white">Select user for make shift</p> -->

    <div class="form-check">
        <input type="checkbox"  class="form-check-input" id="ckbCheckAll"  >
        <label class="text-danger">Select All</label>
    </div>

    <?php
    while ($rowUser=mysql_fetch_assoc($selectUser)) 
    {
        $checkRoster=mysql_query(" SELECT * FROM `db_shooz`.`fifteen_minute_schedul` WHERE `user_id`='".$rowUser['user_name']."' AND `campaign_name`='$campaignName' AND `create_date`='$date'");
        $countRoster=mysql_num_rows($checkRoster);
        $rosterData=mysql_fetch_assoc($checkRoster);
        $shiftFind=mysql_fetch_assoc(mysql_query("SELECT * FROM `roster`.`tbl_shift` WHERE `shift_name`='$shift_name'"));
        $shift_info = json_decode($shiftFind['shift_info'], true);
        $offDay_flag=0;
        foreach ($shift_info as $key => $value) {
            if ($key=='23:15') 
            {
                $offDayCheck=mysql_num_rows(mysql_query("SELECT * FROM `roster`.`users_info` WHERE `user_name`='".$rowUser['user_name']."' AND `active_status`=1 AND `schedule_date`='$next' "));
            }
        }

        ?>
        <div class="col-md-3 float-left">
            <div class="form-check">
                <input type="checkbox" class="form-check-input checkBoxClass" name="checkName" value="<?php echo $rowUser['id']; ?>" onclick="Power()">
                <label class="form-check-label" for="exampleCheck1"><?php echo $rowUser['user_name']; ?> <span class="text-danger"><?php  

                if ($countRoster>0) {
                    echo "(".$rosterData['shift_name'].")";
                }
            ?></span>
            <?php  
            if ($offDayCheck!=0) 
            {
                ?>
                <span class="text-primary">(Suggest)</span>
                <?php
            }
            else
            {
                $offDayCheck=0;
            }
            ?>

        </label>
    </div>
</div>
<script type="text/javascript">
    function Power()
    {
        var date=$("#start").val();
    var shift=$("#shift_name").val();
    var check="NewManPower";
    var userCount = 0;
    $.each($("input[name='checkName']:checked"), function(){            
        userCount=userCount+1;
    });
    $.ajax({
        url: 'manpower-calculation.php',
        method: 'POST',
        data: {
             date: date,
            shift:shift,
            userCount:userCount,
            check:check
        },
        success: function(response) {
            $("#manPowerRecord").html(response);

        }
    });
    // $.ajax({
    //     url: 'manpower-calculation.php',
    //     method: 'POST',
    //     data: {
    //         date: date,
    //         shift:shift,
    //         userCount:userCount,
    //         check:check
    //     },
    //     success: function(response) {
    //         $("#manPowerRecord").html(response);

    //     }
    // });
    }
</script>
<?php
}
?>
</div>
<script type="text/javascript">
    $('#ckbCheckAll').click(function(e) {
        let isChecked = $('#ckbCheckAll').is(':checked');
        if (isChecked){
            $(".checkBoxClass").attr('checked', "checked");
        } else{
            $(".checkBoxClass").removeAttr('checked');
        }
    });
</script>
<?php
}



if ($_POST['check']=="selectUsersWeekly") 
{
    $campaignName=$_POST['campaignName'];
    $date=$_POST['start'];
    $next = date('Y-m-d',strtotime("+1 day", strtotime($date)));
    $endDate=date('Y-m-d',strtotime("+6 day", strtotime($date)));
    $userType=$_POST['userType'];
    $shift_name=$_POST['shift_name'];
    $selectUser=mysql_query("SELECT * FROM `roster`.`users_info` WHERE `schedule_date`>='$date' AND `schedule_date`<='$endDate' AND `user_type`='$userType' AND `campaign_name`='$campaignName' GROUP BY user_name");
    ?>
    <!-- <div class="col-md-12">
     <p class="bg-info pt-1 pb-1 text-center text-white">Select user for make shift</p>
 </div> -->

 <div class="col-md-12">
    <!-- <p class="bg-info pt-1 pb-1 text-center text-white">Select user for make shift</p> -->

    <div class="form-check">
        <input type="checkbox"  class="form-check-input" id="ckbCheckAll"  >
        <label class="text-danger">Select All</label>
    </div>

    <?php
    while ($rowUser=mysql_fetch_assoc($selectUser)) 
    {
        $checkRoster=mysql_query(" SELECT * FROM `db_shooz`.`fifteen_minute_schedul` WHERE `user_id`='".$rowUser['user_name']."' AND `campaign_name`='$campaignName' AND `create_date`='$date'");
        $countRoster=mysql_num_rows($checkRoster);
        $rosterData=mysql_fetch_assoc($checkRoster);
        $shiftFind=mysql_fetch_assoc(mysql_query("SELECT * FROM `roster`.`tbl_shift` WHERE `shift_name`='$shift_name'"));
        $shift_info = json_decode($shiftFind['shift_info'], true);
        $offDay_flag=0;
        foreach ($shift_info as $key => $value) {
            if ($key=='23:15') 
            {
                $offDayCheck=mysql_num_rows(mysql_query("SELECT * FROM `roster`.`users_info` WHERE `user_name`='".$rowUser['user_name']."' AND `active_status`=1 AND `schedule_date`='$next' "));
            }
        }

        ?>
        <div class="col-md-3 float-left">
            <div class="form-check">
                <input type="checkbox" class="form-check-input checkBoxClass" name="checkName" value="<?php echo $rowUser['user_name']; ?>" onclick="Power()">
                <label class="form-check-label" for="exampleCheck1"><?php echo $rowUser['user_name']; ?> <span class="text-danger"><?php  

                if ($countRoster>0) {
                    echo "(".$rosterData['shift_name'].")";
                }
            ?></span>
            <?php  
            if ($offDayCheck!=0) 
            {
                ?>
                <span class="text-primary">(Suggest)</span>
                <?php
            }
            else
            {
                $offDayCheck=0;
            }
            ?>

        </label>
    </div>
</div>
<script type="text/javascript">
    function Power()
    {
        var date=$("#start").val();
    var shift=$("#shift_name").val();
    var check="NewManPower";
    var userCount = 0;
    $.each($("input[name='checkName']:checked"), function(){            
        userCount=userCount+1;
    });
    $.ajax({
        url: 'manpower-calculation.php',
        method: 'POST',
        data: {
             date: date,
            shift:shift,
            userCount:userCount,
            check:check
        },
        success: function(response) {
            $("#manPowerRecord").html(response);

        }
    });
    // $.ajax({
    //     url: 'manpower-calculation.php',
    //     method: 'POST',
    //     data: {
    //         date: date,
    //         shift:shift,
    //         userCount:userCount,
    //         check:check
    //     },
    //     success: function(response) {
    //         $("#manPowerRecord").html(response);

    //     }
    // });
    }
</script>
<?php
}
?>
</div>
<script type="text/javascript">
    $('#ckbCheckAll').click(function(e) {
        let isChecked = $('#ckbCheckAll').is(':checked');
        if (isChecked){
            $(".checkBoxClass").attr('checked', "checked");
        } else{
            $(".checkBoxClass").removeAttr('checked');
        }
    });
</script>
<?php
}




if ($_POST['check']=="getUserRequired") 
{
    $date=$_POST['date'];
    $dayName=strtolower(date('l', strtotime($data)));
    $selectedData=mysql_fetch_assoc(mysql_query("SELECT * FROM `db_shooz`.`erlang_calculation` WHERE `dayName`='$dayName'"));
    $dayLogArr = json_decode($selectedData['mainAgent'], true);
    
    foreach ($dayLogArr as $key => $value) {
        ?>
        <div class="col-md-1 m-1 float-left">
            <p><?php echo $key; ?></p>
            <p><?php echo $value; ?></p>
        </div>
        <?php
    }
}
