<?php

	include_once 'connect.php';
	ob_start();
	if(isset($_GET['uid'])&&isset($_GET['fid']))
	{
		$followerId = $_GET['fid'];
		$followeeId = $_GET['uid'];
		
		$queryMain = mysqli_query($db, "SELECT * FROM follow WHERE follow_uid=$followeeId AND follow_fid=$followerId");
		if(mysqli_num_rows($queryMain) == 1)//if already following
		{
			//unfollow
			mysqli_query($db, "DELETE FROM follow WHERE follow_uid=$followeeId AND follow_fid=$followerId");
			echo "unfollowed";
			header("Location: ".$_SERVER['HTTP_REFERER']);
		}
		else
		{
			//follow
			mysqli_query($db, "INSERT INTO follow (follow_id, follow_fid, follow_uid) VALUES (0, $followerId, $followeeId)");
			echo "followed";
			header("Location: ".$_SERVER['HTTP_REFERER']);
		}
	}
	else
		header('location: login');

?>