<?php
	$mysql_host     = 'localhost';
	$mysql_user     = 'id2438400_root';
	$mysql_pass     = '@lphaHE5';
	$mysql_database = 'id2438400_db_review';
	
	@session_start();

	if($db=mysqli_connect($mysql_host,$mysql_user,$mysql_pass))
	{
		mysqli_select_db($db, $mysql_database);
	}
	else
	{
		echo 'Cannot connect to Database. Try again after a while.';
	}
	
?>