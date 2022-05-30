<?php
include './libs/database.php';
//$missing_chk = mysql_fetch_array(mysql_query("SELECT `event_date` FROM `db_shooz`.`temp_agent` ORDER BY `event_date` DESC LIMIT 1"));
//$start = $missing_chk['event_date'];
//$start = date('Y-m-d', strtotime('+1 day', strtotime($start)));

$start = date('Y-m-d',strtotime($_POST['start_date']));
$end = $start;
//$today_date = date('Y-m-d');
//if(strtotime($start)==strtotime($today_date)){
	//$end = '2021-07-31';
//} else {
	//$end = $start;
//}

$startdate  = strtotime($start.' 00:00:00');
$enddate    = strtotime($end .' 23:59:59');

echo $select_agent_date_start  = $start.' 00:00:00';
echo "<br>";
echo $select_agent_date_end    = $end .' 23:59:59';
echo "<br>";
echo "(Talk Time+Dispo Time)|Wait Time|Pause Time";
echo "<br>";
/*$missing_chk = mysql_fetch_array(mysql_query("SELECT `event_date_end` FROM `db_shooz`.`temp_agent` ORDER BY `event_date_end` DESC LIMIT 1"));
$start = $missing_chk['event_date_end'];
echo "Last Update date time : ".$start."<br>";
$start = date('Y-m-d H:i:s', strtotime('+1 seconds', strtotime($start)));
echo "Now Updating From : ".$start."<br>";
$end_time_limit = date('Y-m-d H:i:s', strtotime('-1 hour')); //Before 1 hour from current time
$end_hour = date('H', strtotime($end_time_limit));
$end_day = date('Y-m-d', strtotime($end_time_limit));
$end = $end_day." ".$end_hour.":00:00";
$end = date('Y-m-d H:i:s', strtotime('-1 seconds', strtotime($end)));
echo "Update Limit time : ".$end."</br>";

$startdate  = strtotime($start);
$enddate    = strtotime($end);

$select_agent_date_start = $start;
$select_agent_date_end    = date('Y-m-d H:i:s', strtotime('+1 hour', strtotime($end)));
echo "Temp table data insert limit : ".$select_agent_date_start." -- ".$select_agent_date_end."</br>";
*/
$intervaltime = '15';
//$agent_name   = 'syousuf';
//$agent_name   = 'useva';
$agent_name   = 'all';

$nex_date   = date('Y-m-d H:i:s',strtotime('+1 day',strtotime($startdate)));
$incriment  = 3600*24;
$interval   = ($intervaltime-1);

//mysql_query("DELETE FROM dip_vicidial_agent_log_temp");
//mysql_query("INSERT INTO dip_vicidial_agent_log_temp SELECT * FROM vicidial_agent_log WHERE event_time >= '$select_agent_date_start' AND event_time <= '$select_agent_date_end';");
//mysql_query("DELETE FROM dip_vicidial_closer_log_temp");
//mysql_query("INSERT INTO dip_vicidial_closer_log_temp SELECT * FROM vicidial_closer_log WHERE call_date >= '$select_agent_date_start' AND call_date <= '$select_agent_date_end';");
//mysql_query("DELETE FROM dip_vicidial_log_temp");
//mysql_query("INSERT INTO dip_vicidial_log_temp SELECT * FROM vicidial_log WHERE call_date >= '$select_agent_date_start' AND call_date <= '$select_agent_date_end';");
//mysql_query("DELETE FROM dip_park_log_temp");
//mysql_query("INSERT INTO dip_park_log_temp SELECT * FROM park_log WHERE parked_time >= '$select_agent_date_start' AND parked_time <= '$select_agent_date_end';");

?>
<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-content" style="overflow: scroll;">            
            <table class="table table-striped table-bordered" id="hourly_call_trands">
                <thead>
                    <tr>
						<th>SL</th>
                        <th>Date</th>
                        <th>Log In ID</th>
                    <?php
					for ($i=$startdate; $i <$enddate ; $i+=$incriment) {
						$start_date = date('Y-m-d H:i:s',$i);
						$end_date   = date('Y-m-d H:i:s',strtotime('+23 hour +59 minutes +59 seconds',strtotime($start_date)));
						$next       = date('Y-m-d H:i:s',strtotime('+'.$interval.'minutes +59 seconds',strtotime($start_date)));
						while ($start_date < $end_date) {
							echo "<th>".date('H:i',strtotime('+1 seconds',strtotime($next)))."</th>";
							$start_date = date('Y-m-d H:i:s',strtotime('1 seconds',strtotime($next)));
							$next=date('Y-m-d H:i:s',strtotime('+'.$interval.' minutes +59 seconds',strtotime($start_date)));
						}
					}
					?> 
						<th>Total Inbound Active Time</th>
                    </tr>
                </thead>   
                <tbody>
                    <?php
                    // echo date('Y-m-d H:i:s',$startdate)."&nbsp;&nbsp;&nbsp;to&nbsp;&nbsp;&nbsp;   ".date("Y-m-d H:i:s",$enddate);
                    $count = 0;
					 if ($agent_name == 'all') {
						//$agentSql = "SELECT DISTINCT(user) as agent FROM dip_vicidial_agent_log_temp WHERE event_time >= '".$select_agent_date_start."' and event_time <= '".$select_agent_date_end."' group by user order by user desc";
						$agentSql = "SELECT DISTINCT(user) as agent FROM vicidial_agent_log WHERE event_time >= '".$select_agent_date_start."' and event_time <= '".$select_agent_date_end."' group by user order by user ASC";
						$result   = mysql_query($agentSql);
					} else{
						$agentSql = "SELECT DISTINCT(user) as agent FROM dip_vicidial_agent_log_temp WHERE event_time >= '".$select_agent_date_start."' and event_time <= '".$select_agent_date_end."' and user = '".$agent_name."' LIMIT 1";
						$result   = mysql_query($agentSql);
					} 
					$sl=1;
					$prev_pause_code = '';
					while ($row = mysql_fetch_assoc($result)) {
						
						$roster_date = date('Y-m-d',strtotime($start));
						$roster_qry = "SELECT * FROM `db_shooz`.`fifteen_minute_schedul` WHERE user_id = '".$row['agent']."' AND create_date = '".$roster_date."'";
						//echo $roster_qry."<br>";
						$roster_sql = mysql_query($roster_qry);
						$roster_result = mysql_fetch_assoc($roster_sql);
						//var_dump($roster_result);
						$roster_start_counter = 0;
						$roster_start_time = "";
						$tot_inbound_ac_tm_percent = 0;
						$inbound_ac_tm_counter = 0;
						
						echo "<tr><td>".$sl++."</td>";
						echo "<td>".date('Y-m-d',strtotime($start))."</td>";
						echo "<td>".$row['agent']."</td>";
						
						$array = array("talk_sec"=>0,"wait_sec"=>0,"pause_sec"=>0,"dispo_sec"=>0);
						$array_temp = array("talk_sec"=>0,"wait_sec"=>0,"pause_sec"=>0,"dispo_sec"=>0);
						$array_tot = array("talk_sec"=>0,"wait_sec"=>0,"pause_sec"=>0,"dispo_sec"=>0,"stuff_time"=>0);
						for ($i=$startdate; $i <$enddate ; $i+=$incriment) {

							$start_date = date('Y-m-d H:i:s',$i);
							$end_date   = date('Y-m-d H:i:s',strtotime('+23 hour +59 minutes +59 seconds',strtotime($start_date)));
							$next       = date('Y-m-d H:i:s',strtotime('+'.$interval.'minutes +59 seconds',strtotime($start_date)));
							
							$count++;

							while ($start_date < $end_date) {

									//$agent_time_sql = "SELECT SUM(`pause_sec`) as pause_sec, SUM(`wait_sec`) as wait_sec, SUM(`talk_sec`) as talk_sec, SUM(`dispo_sec`) as dispo_sec, SUM(`dead_sec`) as dead_sec, campaign_id FROM `dip_vicidial_agent_log_temp` WHERE `event_time` >= '$start_date' AND `event_time` <= '$next' AND `user` = '".$row['agent']."'";
									$agent_time_sql = "SELECT SUM(`pause_sec`) as pause_sec, SUM(`wait_sec`) as wait_sec, SUM(`talk_sec`) as talk_sec, SUM(`dispo_sec`) as dispo_sec, SUM(`dead_sec`) as dead_sec, campaign_id FROM `vicidial_agent_log` WHERE `event_time` >= '$start_date' AND `event_time` <= '$next' AND `user` = '".$row['agent']."'";
									// echo $agent_time_sql."<br>";
									$timeresult = mysql_query($agent_time_sql);
									$tot_ans = mysql_fetch_assoc($timeresult);
									
									$agent_last_pause_code =  mysql_fetch_assoc(mysql_query("SELECT * FROM `vicidial_agent_log` WHERE `event_time` >= '$start_date' AND `event_time` <= '$next' AND `user` = '".$row['agent']."' AND `sub_status` <> '' ORDER BY `agent_log_id` DESC LIMIT 1"));
									if($agent_last_pause_code['sub_status']!=''){
										$now_pause_code = $agent_last_pause_code['sub_status'];
									}
									if($prev_pause_code == ''){
										$prev_pause_code = $now_pause_code;
									}
									
									$tot_ans['talk_sec'] = $tot_ans['talk_sec'] - $tot_ans['dead_sec']; //Its actual talk sec
									$talk_sec = $tot_ans['talk_sec'] + $array['talk_sec'];
									$wait_sec = $tot_ans['wait_sec'] + $array['wait_sec'];
									$pause_sec = $tot_ans['pause_sec'] + $array['pause_sec'];
									$dispo_sec = $tot_ans['dispo_sec'] + $array['dispo_sec'];
									$dead_sec = $tot_ans['dead_sec'];
									$array['talk_sec']=0;
									$array['wait_sec']=0;
									$array['pause_sec']=0;
									$array['dispo_sec']=0;
									//$array = array("talk_sec"=>0,"wait_sec"=>0,"pause_sec"=>0,"dispo_sec"=>0);
									$stuff_time = $talk_sec + $wait_sec + $pause_sec + $dispo_sec;
									
									
									
									$check_date = date('H:i:sa',strtotime($next));
									
									if($stuff_time>900 && $check_date!='23:59:59pm'){
										//$last_row_sql = "SELECT * FROM `vicidial_agent_log` WHERE `event_time` <= '$next' AND `user` ='".$row['agent']."' ORDER BY `event_time` DESC LIMIT 1;";
										//$last_row = mysql_fetch_assoc(mysql_query($last_row_sql));
										//echo date('H:i:sa',strtotime($start_date))."--".$last_row['talk_sec']."--".$last_row['wait_sec']."--".$last_row['pause_sec']."--".$last_row['dispo_sec']."--".$last_row['dead_sec']."<br>";
										
										//$array_temp = array("talk_sec"=>$talk_sec,"wait_sec"=>$wait_sec,"pause_sec"=>$pause_sec,"dispo_sec"=>$dispo_sec);
										$negative_value = 0;
										$extra_time = $stuff_time-900;
										$stuff_time=900;
										while(1){
											
											if($negative_value>0){
												$extra_time = $negative_value;
												$negative_value = 0;
											}
											$array_temp['talk_sec']=$talk_sec;
											$array_temp['wait_sec']=$wait_sec;
											$array_temp['pause_sec']=$pause_sec;
											$array_temp['dispo_sec']=$dispo_sec;

											$value = max($array_temp);
											$key = array_search($value, $array_temp);
											
											//$array[$key]=$stuff_time-900;
											
											
											if($key=='talk_sec'){
												$talk_sec = $talk_sec -$extra_time;
												$array['talk_sec']=$extra_time;
												if($talk_sec<0){
													$negative_value = $talk_sec*(-1);
													$array['talk_sec']=$array['talk_sec']-$negative_value;
													$talk_sec = 0;
												}
											} else if ($key=='wait_sec'){
												$wait_sec = $wait_sec -$extra_time;
												$array['wait_sec']=$extra_time;
												if($wait_sec<0){
													$negative_value = $wait_sec*(-1);
													$array['wait_sec']=$array['wait_sec']-$negative_value;
													$wait_sec = 0;
												}
											}else if ($key=='pause_sec'){
												$pause_sec = $pause_sec -$extra_time;
												$array['pause_sec']=$extra_time;
												if($pause_sec<0){
													$negative_value = $pause_sec*(-1);
													$array['pause_sec']=$array['pause_sec']-$negative_value;
													$pause_sec = 0;
												}
											}else if ($key=='dispo_sec'){
												$dispo_sec = $dispo_sec -$extra_time;
												$array['dispo_sec']=$extra_time;
												if($dispo_sec<0){
													$negative_value = $dispo_sec*(-1);
													$array['dispo_sec']=$array['dispo_sec']-$negative_value;
													$dispo_sec = 0;
												}
											} else{
												$row['agent'] = $row['agent']."(Problem)";
											}
											
											
											if($negative_value == 0)
												break;
												
										}
									}
									$array_tot['talk_sec']+=$talk_sec;
									$array_tot['wait_sec']+=$wait_sec;
									$array_tot['pause_sec']+=$pause_sec;
									$array_tot['dispo_sec']+=$dispo_sec;
									$array_tot['stuff_time']+=$stuff_time;
									
									$roster_shfit_time = date('H:i',strtotime('1 seconds',strtotime($next)));
									if($roster_result[$roster_shfit_time]!=''){
										if($roster_start_counter==0){
											$roster_start_time = $roster_shfit_time;
											$roster_start_counter +=1;
										} else {
											$roster_start_counter +=1;
										}
										if($roster_result[$roster_shfit_time]=='working'){
											$inbound_ac_tm = $talk_sec+$dispo_sec+$wait_sec;
											$inbound_ac_tm_percent = ($inbound_ac_tm/900)*100;
											$tot_inbound_ac_tm_percent +=$inbound_ac_tm_percent;
											$inbound_ac_tm_counter+=1;
											echo "<td>".($talk_sec+$dispo_sec)."|".$wait_sec."|".$pause_sec."</td>";
											//echo "<td>".number_format((float)$inbound_ac_tm_percent, 2, '.', '')."</td>";
										} else {
											echo "<td>".($talk_sec+$dispo_sec)."|".$wait_sec."|".$pause_sec."</td>";
											//echo "<td>-</td>";
										}
									} else {
										echo "<td>".($talk_sec+$dispo_sec)."|".$wait_sec."|".$pause_sec."</td>";
									}
									
									
									//if($stuff_time>0){
									//echo "<td>".($talk_sec+$dispo_sec)."|".$wait_sec."|".$pause_sec."</td>";
										
										$save_event_date = date('Y-m-d',strtotime($start_date));
										$event_date_start = date('Y-m-d H:i:s',strtotime($start_date));
										$event_date_end = date('Y-m-d H:i:s',strtotime($next));
										$now_time = date('Y-m-d H:i:s');
										//mysql_query("INSERT INTO `db_shooz`.`temp_agent` (`name`,`full_name`,`event_date`,`event_date_start`,`event_date_end`,`campaign_id`,`pause_sec`,`wait_sec`,`talk_sec`,`dispo_sec`,`dead_sec`,`hold_sec`,`stuff_time`,`in_call`,`out_call`,`update_time`) VALUES ('".$row['agent']."','".$agent_full_name['full_name']."','".$save_event_date."','".$event_date_start."','".$event_date_end."','".$tot_ans['campaign_id']."','".$pause_sec."','".$wait_sec."','".$talk_sec."','".$dispo_sec."','".$dead_sec."','".$dial_park_times['hold_time']."','".$stuff_time."','".$incoming_call_total['incoming_call']."','".$outgoing_calls['outgoing_call']."','".$now_time."')");
									//}
								$start_date = date('Y-m-d H:i:s',strtotime('1 seconds',strtotime($next)));
								$next=date('Y-m-d H:i:s',strtotime('+'.$interval.' minutes +59 seconds',strtotime($start_date)));
								//$sl++; 								
							}
						}
						echo "<td>".number_format((float)($tot_inbound_ac_tm_percent/$inbound_ac_tm_counter), 2, '.', '')."%</td>";
						echo "</tr>";
					}
					
					//var_dump($array_tot);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
	$(document).ready( function () {
		$('#hourly_call_trands').DataTable();
	} );
</script>