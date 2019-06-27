<?php
	include_once 'connect.php';
	
	if(isset($_SESSION["user_id"]))
		$userId = $_SESSION["user_id"];
	else
		header('location: login');
	
	if(isset($_GET['pid']))
		$entryId = $_GET['pid'];
	else
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	
	//check if entry belongs to the user
	$queryCheck = mysqli_query($db,"SELECT * FROM entries WHERE entry_id=$entryId");
	//check if entry exists
	if(mysqli_num_rows($queryCheck)==0)
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	else
	{
		while($r=mysqli_fetch_array($queryCheck))
		$entryUploader = $r['entry_uploader'];
	}
	
	if($entryUploader==$userId)
	{
		$queryDeletePost = "DELETE FROM entries WHERE entry_id=$entryId LIMIT 1"; //Delete Post
		$queryDeletePostLikes = "DELETE FROM likes WHERE like_entry_id=$entryId"; //Delete post likes
		$queryDeletePostComment = "DELETE FROM comments WHERE comment_entry=$entryId"; //Delete post comment
		$queryDeleteNotification = 'DELETE FROM notifications WHERE notif_link="comment?sid='.$entryId.'"'; //Delete post notifications
		
		if(mysqli_query($db, $queryDeletePost) && mysqli_query($db, $queryDeletePostLikes) && mysqli_query($db, $queryDeletePostComment) && mysqli_query($db, $queryDeleteNotification))
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		else
			echo "Error Deleting:".mysqli_error($db);
	}
	else
		header('Location: /');
?> 