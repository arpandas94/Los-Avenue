<?php

	include_once 'connect.php';
	include_once 'timestamp.php';
	
	$totalSpendingUSD = "0";
	$entryBody = "";
	$userPanel="";
	$notifTab = "";
	
	if(isset($_SESSION["user_id"]))
	{
		$userId = $_SESSION["user_id"];
	}
	else
	{
		header('location: login');
	}
	
	include_once 'common/left-sidebar.php';
	include_once 'common/right-sidebar.php';
	
	//Reset read notifications
	$queryResetRead = mysqli_query($db, "UPDATE notifications SET notif_status=0 WHERE notif_user_id=$userId");
	
	//set Notifications
	$queryNotif = mysqli_query($db, "SELECT * FROM notifications WHERE notif_user_id=$userId ORDER BY notif_id DESC");
	while($r1=mysqli_fetch_array($queryNotif))
	{
		$notifText = $r1['notif_text'];
		$notifDate = time_elapsed_string($r1['notif_date']);
		$notifLink = $r1['notif_link'];
		$notifStatus = $r1['notif_status'];
		$dpId = 'img/users/'.$r1['notif_pic_link'].'.jpg'; 
		
		//Setting up user DP
		if(!file_exists($dpId))
			$dpId = 'img/blankprofile.png';
		
		$notifTab = $notifTab.'<hr><p><img  width="45px;" height="45px;" style="border-radius:10px; object-fit: cover;" src="'.$dpId.'"><a style="text-decoration:none;" href="'.$notifLink.'"> '.$notifText.'</a><span style="font-size:11px; color:#aaa;" class="pull-right">'.$notifDate.'</span></p>';
	}
	
	include 'notifications.htm';

?>