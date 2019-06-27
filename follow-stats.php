<?php

	include_once 'connect.php';
	
	$totalSpendingUSD = "0";
	$userPanel="";
	$entryFilterText = "";
	$followingTab ="";
	$followerTab ="";
	$followingCount = '0';
	$followerCount = '0';
	
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
	
	//get subject details
	if(isset($_GET['sid']))
	{
		$subjectId = $_GET['sid'];
		$querySubjectDetails = mysqli_query($db,"SELECT * FROM users WHERE user_id=$subjectId");
		if(mysqli_num_rows($querySubjectDetails)==0)
		{
			header('location: login');
		}
		while($r4=mysqli_fetch_array($querySubjectDetails))
		{
			$subjectName = $r4['user_first_name'].' '.$r4['user_last_name'];
			$subjectDN = $r4['user_dn'];
			
			//Setting up user DP
			if(file_exists("img/users/$subjectId.jpg"))
				$subjectDP = 'img/users/'.$subjectId.'.jpg';
			else
				$subjectDP = 'img/blankprofile.png';
		}
	}
	else
		header('location: login');
	
	//get subject following
	$querySubjectFollowing = mysqli_query($db,"SELECT * FROM follow WHERE follow_fid=$subjectId");
	$followingTabCount = mysqli_num_rows($querySubjectFollowing);
	while($r5=mysqli_fetch_array($querySubjectFollowing))
	{
		$followingId = $r5['follow_uid'];
		$queryFollowingInfo = mysqli_query($db, "SELECT * FROM users WHERE user_id=$followingId");
		while($r8=mysqli_fetch_array($queryFollowingInfo))
		{
			$followingUserId = $r8['user_id'];
			
			//Setting Blank DP
			if(file_exists("img/users/$followingUserId.jpg"))
				$dpPath3 = 'img/users/'.$followingUserId.'.jpg';
			else
				$dpPath3 = 'img/blankprofile.png';
			
			$followingUserName = $r8['user_first_name'].' '.$r8['user_last_name'];
			$followingTab = $followingTab.'<p><a style="text-decoration:none;" href="profile?id='.$followingUserId.'">
									<img src="'.$dpPath3.'" width=40px; height=40px; style="border-radius:10px; object-fit: cover;">
									<b>'.$followingUserName.'</b>
								</a></p>';
		}
	}
	
	//get subject follower
	$querySubjectFollower = mysqli_query($db,"SELECT * FROM follow WHERE follow_uid=$subjectId");
	$followerTabCount = mysqli_num_rows($querySubjectFollower);
	while($r5=mysqli_fetch_array($querySubjectFollower))
	{
		$followerId = $r5['follow_fid'];
		$queryFollowerInfo = mysqli_query($db, "SELECT * FROM users WHERE user_id=$followerId");
		while($r8=mysqli_fetch_array($queryFollowerInfo))
		{
			$followerUserId = $r8['user_id'];
			$followerUserName = $r8['user_first_name'].' '.$r8['user_last_name'];
			
			//Setting Blank DP
			if(file_exists("img/users/$followerUserId.jpg"))
				$dpPath3 = 'img/users/'.$followerUserId.'.jpg';
			else
				$dpPath3 = 'img/blankprofile.png';
			
			$followerTab = $followerTab.'<p><a style="text-decoration:none;" href="profile?id='.$followerUserId.'">
									<img src="'.$dpPath3.'" width=40px; height=40px; style="border-radius:10px; object-fit: cover;">
									<b>'.$followerUserName.'</b>
								</a></p>';				
		}
	}
	
	include 'follow-stats.htm';

?>