<?php
	include_once 'connect.php';
 
 if(isset($_POST['query']))
 {
	$searchQuery= mysqli_real_escape_string($db, $_POST['query']);
	$autoList[] = array();
	
	$queryMain = mysqli_query($db, "SELECT * FROM users WHERE user_first_name LIKE '%$searchQuery%' OR user_last_name LIKE '%$searchQuery%' OR user_dn LIKE '%$searchQuery%'");
	while($r=mysqli_fetch_assoc($queryMain))
	{
		$autoList[] = $r['user_first_name'].' '.$r['user_last_name'];
	}
	
	$queryMain = mysqli_query($db, "SELECT * FROM entries WHERE entry_brand_name LIKE '%$searchQuery%' OR entry_name LIKE '%$searchQuery%' OR entry_tags LIKE '%$searchQuery%'");
	while($r=mysqli_fetch_array($queryMain))
	{
		$autoList[] =  $r['entry_brand_name'].' '.$r['entry_name'];
	}
	
	echo json_encode($autoList);
 }
?>