<?php

	include_once 'connect.php';
	
	if(isset($_GET['cid'])){
	$commentId = intval($_GET['cid']);	
	$query = "DELETE FROM comments WHERE comment_id=$commentId";
	if(mysqli_query($db, $query))
	{
		//delete notification
		
		header('location:'.$_SERVER['HTTP_REFERER']);
	}
	else
		echo "Error Deleting";
	}

?>