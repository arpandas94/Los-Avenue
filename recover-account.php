<?php

	include 'connect.php';
	
	$errMsg="";
	
	if(isset($_SESSION["user_id"]))
	{
		header('location: feed');
	}
	
	if(!isset($_GET['rid']))
	{
		header('location: /');
	}
	else
	{
		$recCode = $_GET['rid'];
		$queryCheckCode = mysqli_query($db, "SELECT * FROM forgotpassword WHERE fpass_code='$recCode'");
		
		if(mysqli_num_rows($queryCheckCode)==0)
			header('location: /');
		
		while($r=mysqli_fetch_array($queryCheckCode))
		{
			$recUserId = $r['fpass_user_id'];
			$queryGetUserDetails = mysqli_query($db, "SELECT * FROM users WHERE user_id=$recUserId");
			while($r2=mysqli_fetch_array($queryGetUserDetails))
			{
				$recUserName = $r2['user_first_name'].' '.$r2['user_last_name'];
				
				//Setting up user DP
				if(file_exists("img/users/$recUserId.jpg"))
					$dpPath = 'img/users/'.$recUserId.'.jpg';
				else
					$dpPath = 'img/blankprofile.png';
			}
		}
	}
	
	if(isset($_POST['sbt-rec']))
	{
		if((strlen($_POST['txt-pass'])<6)||(strlen($_POST['txt-pass-2'])<6))
		{
			$errMsg = '<div class="alert alert-danger">Password must be 6 characters minimum</div>';
		}
		else
		{
			if($_POST['txt-pass']==$_POST['txt-pass-2'])
			{
				$queryChangePassword = mysqli_query($db, "UPDATE users SET user_password='{$_POST['txt-pass-2']}' WHERE user_id=$recUserId");
				$queryRemoveCode = mysqli_query($db, "UPDATE forgotpassword SET fpass_code='Recovered' WHERE fpass_code='$recCode'");
				$_SESSION["user_id"] = $recUserId; 
				header("Location: /");
			}
			else
			{
				$errMsg = '<div class="alert alert-danger">Password mismatch</div>';
			}
		}
	}
	
	include 'recover-account.htm';
?>