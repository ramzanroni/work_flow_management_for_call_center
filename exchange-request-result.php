<?php 
include './libs/database.php';
$startDay=$_POST['startDate'];
$endDay=$_POST['endDate'];

?>
<div class="col-md-12 mt-2">
    <div class="card card-outline card-primary">
        <h5 class="card-header">Shift Exchange Reports</h5>
        <div class="card-body">
            <table class="table table-bordered table-hover" id="shiftTbl">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Sender Name</th>
                        <th>Sender Shift</th>
                        <th>Sender Campaign</th>
                        <th>Receiver Name</th>
                        <th>Requested Shift</th>
                        <th>Requested Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  
                    $sl=1;
                    $scheduleResult=mysql_query("SELECT * FROM `roster`.`roster_chanage` WHERE `sender_date`>='$startDay' AND sender_date <= '$endDay'");
                    while ($scheduleRow=mysql_fetch_assoc($scheduleResult)) 
                    {
                        ?>
                        <tr>
                            <td><?php echo $sl; ?></td>
                            <td><?php echo $scheduleRow['sender_user_name']; ?></td>
                            <td><?php echo $scheduleRow['sender_shift']; ?></td>
                            <td><?php echo $scheduleRow['sender_camp']; ?></td>
                            <td><?php echo $scheduleRow['receiver_name']; ?></td>
                            <td><?php echo $scheduleRow['requested_shift']; ?></td>
                            <td><?php echo $scheduleRow['sender_date']; ?></td>
                            <td><?php 
                            if ($scheduleRow['status']==2) {
                                ?>
                                <p class="btn btn-danger btn-sm">Reject</p>
                                <?php
                            }
                            if ($scheduleRow['status']==1) 
                            {
                                ?>
                                <p class="btn btn-primary btn-sm">Accept</p>
                                <?php
                            }
                            if ($scheduleRow['status']==0) 
                            {
                                ?>
                                <p class="btn btn-primary btn-sm">Panding</p>
                                <?php
                            }
                        ?></td>
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



<div class="col-md-12 mt-2">
    <div class="card card-outline card-primary">
        <h5 class="card-header">Off Day Exchange Reports</h5>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="offDayTbl">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Sender Name</th>
                        <th>Sender Shift</th>
                        <th>Requested Date</th>
                        <th>Receiver Name</th>
                        <th>Provide Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $agent=$_SESSION['user'];
                    $sl=1;
                    $offDayReport=mysql_query("SELECT * FROM `roster`.`off_day_request` WHERE (`sender_date`>='$startDay' AND `sender_date`<='$endDay') OR (`select_Off_day`>='$startDay' AND `select_Off_day`<='$endDay')");
                    while ($offDayRow=mysql_fetch_assoc($offDayReport)) 
                    {
                        ?>
                        <tr>
                            <td><?php echo $sl; ?></td>
                            <td><?php echo $offDayRow['sender_user_name']; ?></td>
                            <td><?php echo $offDayRow['sender_shift']; ?></td>
                            <td><?php echo $offDayRow['sender_date']; ?></td>
                            <td><?php echo $offDayRow['receiver_name']; ?></td>
                            <td><?php echo $offDayRow['select_Off_day']; ?></td>
                            <td><?php 
                            if ($offDayRow['status']==2) {
                                ?>
                                <p class="btn btn-danger btn-sm">Reject</p>
                                <?php
                            }
                            if ($offDayRow['status']==1) 
                            {
                                ?>
                                <p class="btn btn-primary btn-sm">Accept</p>
                                <?php
                            }
                            if ($offDayRow['status']==0) 
                            {
                                ?>
                                <p class="btn btn-primary btn-sm">Panding</p>
                                <?php
                            }
                        ?></td>
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

<script type="text/javascript">
    $(document).ready( function () {
        $('#shiftTbl').DataTable();
        $('#offDayTbl').DataTable();
    } );
</script>