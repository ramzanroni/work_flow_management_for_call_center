<?php 
session_start();
unset($_SESSION['user'], $_SESSION['userId'], $_SESSION['full_name'], $_SESSION['user_level'], $_SESSION['user_group']);
	if(!isset($_SESSION['user']))
	{
		header("Location: index.php");
	}
	else
	{
		echo "Session don't destroy..!";
	}
?>