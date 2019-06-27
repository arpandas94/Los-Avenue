<?php
	
	include_once 'connect.php';
	
	if(isset($_SESSION["user_id"]))
	{
		$userId = $_SESSION["user_id"];
	}
	else
	{
		header('location: login');
	}

	$followee = $_POST['followee'];
	$follower = $_POST['follower'];
	$action = $_POST['action'];
	
	$queryMain = mysqli_query($db, "SELECT * FROM follow WHERE follow_uid=$followee AND follow_fid=$follower");
	if(mysqli_num_rows($queryMain) == 1)//if already following
	{
		//unfollow
		mysqli_query($db, "DELETE FROM follow WHERE follow_uid=$followee AND follow_fid=$follower");
		
		//delete notification
		mysqli_query($db, "DELETE FROM notifications WHERE notif_user_id=$followee AND notif_pic_link=$follower");
	}
	else
	{
		//follow
		mysqli_query($db, "INSERT INTO follow (follow_id, follow_fid, follow_uid) VALUES (0, $follower, $followee)");
		
		//create notification
		$queryNotif = mysqli_query($db, "SELECT * FROM users WHERE user_id=$follower");
		while($r=mysqli_fetch_array($queryNotif))
		{
			$notifUserName = $r['user_first_name'].' '.$r['user_last_name'];
			$queryAddNotif = mysqli_query($db, 'INSERT INTO notifications (notif_id, notif_user_id, notif_text, notif_date, notif_status, notif_link, notif_pic_link) VALUES (0, '.$followee.', "'.$notifUserName.' followed you.", NOW(), 1, "profile?id='.$follower.'", '.$follower.')');
		}
 	}

?>