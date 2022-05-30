<?php 
include './libs/database.php';
function Factorial($number){
	$factorial = 1;
	for ($i = 1; $i <= $number; $i++){
		$factorial = $factorial * $i;
	}
	return $factorial;
}

if ($_POST['check']=="calculateErlang") {

	$e=2.71828;
	// $numberOfCall=$_POST['numberOfCall'];
	$periodLenth=$_POST['periodLenth'];
	$averageHandalingTime=$_POST['averageHandalingTime'];
	$serviceLevel=$_POST['serviceLevel'];
	$targetAnswerTime=$_POST['targetAnswerTime'];
	$maximumOccupancy=$_POST['maximumOccupancy'];
	$shrinkage=$_POST['shrinkage'];

	$starttime = '00:15:00'; 
	$endtime = '24:00:00'; 
	$duration = '15';  
	$claculateDate='last '.$_POST['dayName'];
	$preDay=date('Y-m-d', strtotime($claculateDate));
	// $currentDate= date('Y-m-d');
	// $preDay=date('Y-m-d', strtotime($currentDate. ' - 1 days'));
	$array_of_time = array ();
	$start_time    = strtotime ($starttime);
	$end_time      = strtotime ($endtime);

	$add_mins  = $duration * 60;

while ($start_time <= $end_time) // loop between time
{
	$array_of_time[] = date ("H:i:s", $start_time);
   $start_time += $add_mins; // to check endtie=me
} 

// date array
$last7Day=array($preDay);
for ($i=1; $i < 7 ; $i++) { 
	$range=7*$i;
	$date='-'.$range." day";
	$previousDate= date('Y-m-d', strtotime($date,strtotime($preDay)));
	array_push($last7Day, $previousDate);	
}
?>
<table class="table table-hover table-brodered">
	<thead>
		<tr>
			<th>Interval</th>
			<th>Number of Call</th>
			<th>Number of Agent</th>
			<th>Service Level</th>
			<th>Probability a call has to wait</th>
			<th>Average Speed of Answer</th>
			<th>Answered Immediately</th>
			<th>Occupancy</th>
			<th>Number of Agents Required</th>
		</tr>
	</thead>
	<tbody>

		<?php
		$timeString='';
		$numberCallStr='';
		for ($i=0; $i < count($array_of_time); $i++) { 
			if ($array_of_time[$i]=="00:00:00") 
			{
				
			}
			?>
			<tr>
				<td id="slot_<?php echo $i; ?>"><?php
				if ($array_of_time[$i]=="00:00:00") {
					echo "23:59";
				}
				else
				{
					echo date("H:i",strtotime($array_of_time[$i])); 
				}

				?></td>

				<?php
				$countCall=0;

				foreach ($last7Day  as $datePre) {
					$startTime=$datePre." ".$array_of_time[$i];
					$timeSlotChange=date("H:i:s", strtotime('-1 sec',strtotime($array_of_time[$i+1])));
					$endTimeData=$datePre." ".$timeSlotChange;
					$callCount=mysql_fetch_assoc(mysql_query("SELECT SUM(`in_call`) AS 'totalCall' FROM `db_shooz`.`temp_15_interval` WHERE `event_date_start`='$startTime' AND `event_date_end`='$endTimeData'"));

					$countCall+=$callCount['totalCall'];
				}
				$numberOfCallCount=round($countCall/7);
				?>
				<td>
					<?php echo $numberOfCallCount; ?>
					<input type="hidden" name="numCall" value="<?php echo $numberOfCallCount; ?>" id="numCall_<?php echo $i; ?>">
				</td>
				<?php
				$numberOfCall=round($countCall/7);
				$numberofCallinHour=round(($numberOfCall*60)/$periodLenth);

				$trafficIntencity=round(($numberofCallinHour*($averageHandalingTime/60))/60);
				$A=$trafficIntencity;
				$N=$trafficIntencity+1;
				$lob=(pow($A, $N)/Factorial($N))*($N/($N-$A));

				$sumOfSerise=0;
				for ($j=0; $j < $N; $j++) { 
					$sumOfSerise+=pow($A, $i)/Factorial($i);
					
				}

				$sumOfSerise= number_format($sumOfSerise,2)."<br>";

				$Pw=$lob/($sumOfSerise+$lob);


				$partOfserviceLevelCal=-($N-$A)*($targetAnswerTime/$averageHandalingTime);


				$serviceLevelCalculate=1-($Pw*pow($e, $partOfserviceLevelCal));

				while ($serviceLevelCalculate*100 < $serviceLevel) 
				{
					$N++;
					$lob=(pow($A, $N)/Factorial($N))*($N/($N-$A));

					$sumOfSerise=0;
					for ($k=0; $k < $N; $k++) { 
						$sumOfSerise+=pow($A, $k)/Factorial($k);
					}

					$Pw=$lob/($sumOfSerise+$lob);
					$serviceLevelCalculate=$serviceLevelCalculate+80;
					$partOfserviceLevelCal=-($N-$A)*($targetAnswerTime/$averageHandalingTime);

					$serviceLevelCalculate=1-($Pw*pow($e, $partOfserviceLevelCal));
				}


				$averageSpeedOfAnswer=($Pw*$averageHandalingTime)/($N-$trafficIntencity);
				$immediateAnswer=(1-$Pw)*100;
				$occupancy=($trafficIntencity/$N)*100;

				$numberofAgentRequired=$N/(1-($shrinkage/100));

				?>
				<td><?php echo $N; ?>
					<input type="hidden" value="<?php echo $N; ?>" id="numAgent_<?php echo $i; ?>">
				</td>
				<td><?php echo number_format(($serviceLevelCalculate*100),2)."%"; ?>
					<input type="hidden" value="<?php echo number_format(($serviceLevelCalculate*100),2); ?>" id="serviceLevel_<?php echo $i; ?>">
				</td>
				<td><?php echo number_format($Pw*100,2); ?>
					<input type="hidden" value="<?php echo number_format($Pw*100,2); ?>" id="probabilityofCall_<?php echo $i; ?>">
				</td>
				<td><?php echo number_format($averageSpeedOfAnswer,2); ?>
					<input type="hidden" value="<?php echo number_format($averageSpeedOfAnswer,2); ?>" id="avgAnswer_<?php echo $i; ?>">
				</td>
				<td><?php echo number_format($immediateAnswer,2)."%"; ?>
					<input type="hidden" value="<?php echo number_format($immediateAnswer,2); ?>" id="numImmediateAns_<?php echo $i; ?>">
				</td>
				<td><?php echo number_format($occupancy,2)."%"; ?>
					<input type="hidden" value="<?php echo number_format($occupancy,2); ?>" id="occupancy_<?php echo $i; ?>">
				</td>
				<td><?php echo round($numberofAgentRequired); ?>
					<input type="hidden" value="<?php echo round($numberofAgentRequired); ?>" id="numberOfAgent_<?php echo $i; ?>">
				</td>
				<?php
				// echo "Number of Agent:  ".$N."<br>";
				// echo "Achived service level:  ".($serviceLevelCalculate*100)."<br>";
				// echo "Probability a call has to wait : ".($Pw*100)."<br>";
				// echo "Average Speed of Answer: ".$averageSpeedOfAnswer."<br>";

				// echo "Percentage of Calls Answered Immediately : ".$immediateAnswer."<br>";
				// echo "Occupancy : ".$occupancy."<br>";

				// echo "Number of Agents Required: ".$numberofAgentRequired;


			}














	
				?>
			</tr>
		</tbody>
	</table>
	<input type="hidden" name="" id="length" value="<?php echo $i; ?>">
	<?php
}











// erlang calculation
// if ($_POST['check']=="calculateErlang") 
// {
// 	$e=2.71828;
// 	$numberOfCall=$_POST['numberOfCall'];
// 	$periodLenth=$_POST['periodLenth'];
// 	$averageHandalingTime=$_POST['averageHandalingTime'];
// 	$serviceLevel=$_POST['serviceLevel'];
// 	$targetAnswerTime=$_POST['targetAnswerTime'];
// 	$maximumOccupancy=$_POST['maximumOccupancy'];
// 	$shrinkage=$_POST['shrinkage'];




// 	$numberofCallinHour=($numberOfCall*60)/$periodLenth;

// 	$trafficIntencity=($numberofCallinHour*($averageHandalingTime/60))/60;
// 	$A=$trafficIntencity;
// 	$N=$trafficIntencity+1;
// 	$lob=(pow($A, $N)/Factorial($N))*($N/($N-$A));

// 	$sumOfSerise=0;
// 	for ($i=0; $i < $N; $i++) { 
// 		$sumOfSerise+=pow($A, $i)/Factorial($i);
// 	}



// 	$Pw=$lob/($sumOfSerise+$lob);

// 	$partOfserviceLevelCal=-($N-$A)*($targetAnswerTime/$averageHandalingTime);


// 	$serviceLevelCalculate=1-($Pw*pow($e, $partOfserviceLevelCal));

// 	while ($serviceLevelCalculate*100 < $serviceLevel) 
// 	{
// 		$N++;
// 		$lob=(pow($A, $N)/Factorial($N))*($N/($N-$A));

// 		$sumOfSerise=0;
// 		for ($i=0; $i < $N; $i++) { 
// 			$sumOfSerise+=pow($A, $i)/Factorial($i);
// 		}

// 		$Pw=$lob/($sumOfSerise+$lob);
// 		$serviceLevelCalculate=$serviceLevelCalculate+80;
// 		$partOfserviceLevelCal=-($N-$A)*($targetAnswerTime/$averageHandalingTime);

// 		$serviceLevelCalculate=1-($Pw*pow($e, $partOfserviceLevelCal));
// 	}


// $averageSpeedOfAnswer=($Pw*$averageHandalingTime)/($N-$trafficIntencity);
// $immediateAnswer=(1-$Pw)*100;
// $occupancy=($trafficIntencity/$N)*100;

// $numberofAgentRequired=$N/(1-($shrinkage/100));
// 	echo "Number of Agent:  ".$N."<br>";
// 	echo "Achived service level:  ".($serviceLevelCalculate*100)."<br>";
// 	echo "Probability a call has to wait : ".($Pw*100)."<br>";
// 	echo "Average Speed of Answer: ".$averageSpeedOfAnswer."<br>";

// 	echo "Percentage of Calls Answered Immediately : ".$immediateAnswer."<br>";
// 	echo "Occupancy : ".$occupancy."<br>";

// 	echo "Number of Agents Required: ".$numberofAgentRequired;
// }

?>