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

	
	$likeId = $_POST['entry_id'];
	
	//who's post is this
		$queryEntryUser = mysqli_query($db, "SELECT * FROM entries WHERE entry_id=$likeId");
		while($r=mysqli_fetch_array($queryEntryUser))
			$entryUploader = $r['entry_uploader'];
	
	if($_POST['action']=='like')
	{
		$queryAddLike = mysqli_query($db, "INSERT INTO likes (like_id, like_user_id, like_entry_id) VALUES (0, $userId, $likeId)");
		
		//fetch current user's name
		if($entryUploader!=$userId) //no notification for self liking
		{
			$queryNotif = mysqli_query($db, "SELECT * FROM users WHERE user_id=$userId");
			while($r=mysqli_fetch_array($queryNotif))
			{
				$notifUserName = $r['user_first_name'].' '.$r['user_last_name'];
			}
			//create notification
			$queryNotifLike = mysqli_query($db, 'INSERT INTO notifications (notif_id, notif_user_id, notif_text, notif_date, notif_status, notif_link, notif_pic_link, notif_type) VALUES (0, '.$entryUploader.', "'.$notifUserName.' liked your entry", NOW(), 1, "comment?sid='.$likeId.'", '.$userId.', "like")');
		}
			
	}
	else
	{
		$queryRemoveLike = mysqli_query($db, "DELETE FROM likes WHERE like_user_id=$userId AND like_entry_id=$likeId");
		$queryRemoveNotif = mysqli_query($db, 'DELETE FROM notifications WHERE notif_user_id='.$entryUploader.' AND notif_link="comment?sid='.$likeId.'" AND notif_type="like"');
	}
	
	$queryLikeCount = mysqli_query($db, "SELECT * FROM likes WHERE like_entry_id=$likeId");
	echo mysqli_num_rows($queryLikeCount);
?>