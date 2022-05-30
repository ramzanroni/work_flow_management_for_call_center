<?php 
include './libs/database.php';

if ($_POST['check']=="storeCalculatedData") 
{
	$numberOfCalltr=rtrim($_POST['numberOfCall'], ",");
	$numberOfCall="{".$numberOfCalltr."}";

	$numberOfAgenttr=rtrim($_POST['numberOfAgent'], ",");
	$numberOfAgent="{".$numberOfAgenttr."}";

	$serviceLeveltr=rtrim($_POST['serviceLevel'], ",");
	$serviceLevel="{".$serviceLeveltr."}";

	$occupancyDatatr=rtrim($_POST['occupancyData'], ",");
	$occupancyData="{".$occupancyDatatr."}";

	$mainAgent=rtrim($_POST['mainAgent'], ",");
	$mainAgent="{".$mainAgent."}";
	$dayName=$_POST['dayName'];

	$checkDay=mysql_num_rows(mysql_query("SELECT * FROM `db_shooz`.`erlang_calculation` WHERE `dayName`='$dayName'"));
	if ($checkDay==0) 
	{
		$addDayLog=mysql_query("INSERT INTO `db_shooz`.`erlang_calculation`(`dayName`, `numberOfCall`, `numberOfAgent`, `serviceLevel`, `occupancyData`, `mainAgent`) VALUES ('$dayName','$numberOfCall','$numberOfAgent','$serviceLevel','$occupancyData','$mainAgent')");
		if ($addDayLog) 
		{
			echo "success";
		}
		else
		{
			echo "Something is wrong..!";
		}
	}
	else
	{
		$updateDayLog=mysql_query("UPDATE `db_shooz`.`erlang_calculation` SET `numberOfCall`='$numberOfCall',`numberOfAgent`='$numberOfAgent',`serviceLevel`='$serviceLevel',`occupancyData`='$occupancyData',`mainAgent`='$mainAgent' WHERE `dayName`='$dayName'");
		if ($updateDayLog) 
		{
			echo "up";
		}
		else
		{
			echo "Something is wrong for update..!";
		}
	}

	

}
?>