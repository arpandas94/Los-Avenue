<?php

	include_once 'connect.php';
	include_once 'timestamp.php';
	
	$totalSpendingUSD = "0";
	$entryBody = "";
	$userPanel="";
	$userTab = "";
	
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
	
	//Emptying New Users Tab for interfering with main follow.js
	$userPanel = "Connect to new people on this page";
	
	//Get New Users
	$queryGetNewUsers = mysqli_query($db, "SELECT * FROM users ORDER BY user_id DESC");
	while($r1=mysqli_fetch_array($queryGetNewUsers))
	{
		$newUserId = $r1['user_id'];
		$newUserName = $r1['user_first_name'].' '.$r1['user_last_name'];
		$newUserDN = $r1['user_dn'];
		
		if($newUserId!=$userId)
		{
			//Setting up user DP
			if(file_exists("img/users/$newUserId.jpg"))
				$dpPath3 = 'img/users/'.$newUserId.'.jpg';
			else
				$dpPath3 = 'img/blankprofile.png';
			
			//setting follow button
			$queryFollowButton = mysqli_query($db, "SELECT * FROM follow WHERE follow_uid=$newUserId AND follow_fid=$userId");
			if(mysqli_num_rows($queryFollowButton)==0)
			{
				$followStyle = "btn btn-danger btn-outline";
				$followText = "Follow";
				$followIcon = "glyphicon-plus-sign";
			}
			else
			{
				$followStyle = "btn btn-success btn-outline";
				$followText = "Following";
				$followIcon="";
			}
		
			$userTab = $userTab.'<b>&nbsp;<img align=left src="'.$dpPath3.'" width="45px;" height="45px" style="border-radius:10px; object-fit: cover;"> <a style="text-decoration:none;" href="profile?id='.$newUserId.'">'.$newUserName.'</a></b>
						<span style="color:#d9534f;">@'.$newUserDN.'</span><br>
						&nbsp;&nbsp;<button type="button" id="btn-style-'.$newUserId.'" class="btn btn-xs '.$followStyle.'" onclick="follow_toggle('.$newUserId.','.$userId.');"><span class="glyphicon '.$followIcon.'" id="btn-icon-'.$newUserId.'"></span> <span style="font-weight:bold;" id="follow_text_'.$newUserId.'">'.$followText.'</span></button>
						<hr style="margin:8px;">';
		}
	}
	
	include 'discover-people.htm';
?>