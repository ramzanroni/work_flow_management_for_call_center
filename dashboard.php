<?php  
session_start();
include './libs/database.php';
$date = date("Y-m-d");
?>
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
          <div class="inner">
            <?php  
              $totalAgent=mysql_fetch_assoc(mysql_query("SELECT COUNT(*) AS 'total_agent' FROM `db_shooz`.`fifteen_minute_schedul` WHERE `create_date`='$date'"));
            ?>
            <h3><?php echo $totalAgent['total_agent']; ?></h3>

            <p>Today Agents</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
          <a href="./roster-report.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
          <div class="inner">
            <?php  
              $countOffDay=mysql_fetch_assoc(mysql_query("SELECT COUNT(*) as 'total' FROM `roster`.`users_info` WHERE `schedule_date`='$date' AND `active_status`='0'"));
            ?>
            <h3><?php echo $countOffDay['total']; ?></h3>

            <p>Off Day Agents</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <a href="./shiftReport.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
          <div class="inner">
            <?php 
              $countCamp=mysql_num_rows(mysql_query("SELECT `campaign_name` FROM `db_shooz`.`fifteen_minute_schedul` WHERE `create_date`='$date' GROUP BY `campaign_name`"));
            ?>
            <h3><?php echo $countCamp; ?></h3>

            <p>Total Campaign</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
          <div class="inner">
            <?php 
              $lastDate=mysql_fetch_assoc(mysql_query("SELECT `create_date` FROM `db_shooz`.`fifteen_minute_schedul` ORDER BY id DESC LIMIT 1"));
            ?>
            <h3><?php echo $lastDate['create_date']; ?></h3>

            <p>Last Schedule Date</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
    </div>
  </div>
</div>
</section>
