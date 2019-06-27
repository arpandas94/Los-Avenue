<?php

	include_once 'connect.php';
	
	$errMsg="";
	
	if(isset($_SESSION["user_id"]))
	{
		header('location: /');
	}
	
	if(isset($_POST['txt-forgot-email']))
	{
		$recEmail = $_POST['txt-forgot-email'];
		$queryFindUser = mysqli_query($db, "SELECT * FROM users WHERE user_email='$recEmail'");
		echo mysqli_error($db);
		if(mysqli_num_rows($queryFindUser)==0)
		{
			$errMsg='<div class="alert alert-danger">No user found with that email address</div>';
		}
		else
		{
			while($r=mysqli_fetch_array($queryFindUser))
			{
				$recUserId = $r['user_id'];
			}
			
			$recCode = uniqid('bn'); //generate unique code
			include_once 'email-template.php';
			$query = mysqli_query($db, "INSERT INTO forgotpassword (fpass_id, fpass_code, fpass_user_id) VALUES (0, '$recCode', $recUserId)");
			
			//Sending the mail, creating headers for html format
			$headers = "From: losavenuedesk@gmail.com\r\n";
			$headers .= "Reply-To: armansky94@gmail.com\r\n";
			$headers .= 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			
			if (mail($recEmail, "Los Avenue", $emailBody, $headers))
				$errMsg='<div class="alert alert-success">Recovery link sent check email.</div>';
			else
				$errMsg='<div class="alert alert-danger">Email not sent. Please try again later.</div>';
			
		}	
	}
	
	include 'forgot-password.htm';
?>