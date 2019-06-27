<?php

	include 'connect.php';
	$errMsg = "";
	
	if(isset($_SESSION["user_id"]))
	{
		$userId = $_SESSION["user_id"];
	}
	else
	{
		header('location: login');
	}
	
	//fetching user info
	$queryGetUserInfo = mysqli_query($db, "SELECT * FROM users WHERE user_id=$userId");
	while($r=mysqli_fetch_array($queryGetUserInfo))
	{
		$userFirstName = $r['user_first_name'];
		$userLastName = $r['user_last_name'];
		$userGender = $r['user_gender'];
		$userDn = $r['user_dn'];
		$userEmail = $r['user_email'];
		$userPassword = $r['user_password'];
		$userCountry = $r['user_country'];
		$userCurrency = $r['user_currency'];
		$userBio = $r['user_bio'];
	}
	
	//Currency Select List
	$query = "SELECT * FROM currency";
	if($result = mysqli_query($db, $query))
	{
		$currencyInfo = '';
		while($r = mysqli_fetch_array($result))
		{	
			$currencyId =  $r['currency_id'];
			$currencyName =  $r['currency_name'];
			$currencySymbol = $r['currency_symbol'];
			
			//setting user's currency as selected
			if($userCurrency==$currencyId)
				$currencyInfo = $currencyInfo.'<option value="'.$currencyId.'" selected>'.$currencyName.' ( '.$currencySymbol.' )'.'</option>';
			else
				$currencyInfo = $currencyInfo.'<option value="'.$currencyId.'">'.$currencyName.' ( '.$currencySymbol.' )'.'</option>';
		}
	}
	else
	{
		echo 'Cannot Fetch Currency Information';
	}
	
	//Country Select List
	$query = "SELECT * FROM countries";
	if($result = mysqli_query($db, $query))
	{
		$countryInfo = '';
		while($r = mysqli_fetch_array($result))
		{	
			$countryId =  $r['country_id'];
			$countryName =  $r['country_name'];
			
			//setting user currency
			if($userCountry==$countryId)
				$countryInfo = $countryInfo.'<option value="'.$countryId.'" selected>'.$countryName.'</option>';
			else
				$countryInfo = $countryInfo.'<option value="'.$countryId.'">'.$countryName.'</option>';
		}
	}
	else
	{
		echo 'Cannot Fetch Country Information';
	}
	
	//update basic info
	if(isset($_POST['sbt-acct']))
	{
		//Check if username/email exists for other users
		$queryDuplicateEmailCheck = 'SELECT * FROM users WHERE user_email="'.$_POST['txt-email'].'"';
		$resultDuplicateEmail = mysqli_query($db,$queryDuplicateEmailCheck);
		
		$queryDuplicateDnCheck = 'SELECT * FROM users WHERE user_dn="'.$_POST['txt-display-name'].'"';
		$resultDuplicateDn = mysqli_query($db,$queryDuplicateDnCheck);
		
		if(mysqli_num_rows($resultDuplicateEmail) > 1)
		{
			$errMsg = '<div class="alert alert-danger">This email address already exists</div>';
		}
		elseif(mysqli_num_rows($resultDuplicateDn) > 1)
		{
			$errMsg = '<div class="alert alert-danger">This display name already exists</div>';
		}
		else
		{
			//Stripping HTML tags
			$txtFirstName = strip_tags(mysqli_real_escape_string($db, $_POST['txt-first-name']));
			$txtLastName = strip_tags(mysqli_real_escape_string($db, $_POST['txt-last-name']));
			$txtDN = strip_tags(mysqli_real_escape_string($db, $_POST['txt-display-name']));
		    $txtBio = strip_tags(mysqli_real_escape_string($db, $_POST['txt-bio']));
			
			$queryUpdateBasicInfo = "UPDATE users SET user_first_name='$txtFirstName', user_last_name='$txtLastName', user_dn='$txtDN', user_email='{$_POST['txt-email']}', user_gender={$_POST['sel-gender']}, user_country={$_POST['sel-country']}, user_currency={$_POST['sel-currency']}, user_bio='$txtBio' WHERE user_id=$userId";
			if(mysqli_query($db, $queryUpdateBasicInfo))
			{
				header("Refresh:3");
				$errMsg = '<div class="alert alert-success">Account Updated, Refreshing Wait..</div>';
			}
			else
			{
				$errMsg = '<div class="alert alert-danger">Error updating account:'.mysqli_error($db).'</div>';
			}
		}
	}
	
	//update password
	if(isset($_POST['sbt-pwd']))
	{
		//Check if old and new passwords are the same
		if($userPassword!=$_POST['txt-old-password'])
		{
			$errMsg = '<div class="alert alert-warning">Incorrect old password</div>';
		}
		else
		{
			$queryUpdatePassword = "UPDATE users SET user_password='{$_POST['txt-confirm-password']}' WHERE user_id=$userId";
			if(mysqli_query($db, $queryUpdatePassword))
			{
				header("Refresh:3");
				$errMsg = '<div class="alert alert-success">Password Updated, Refreshing Wait..</div>';
			}
			else
			{
				$errMsg = '<div class="alert alert-danger">Error updating password:'.mysqli_error($db).'</div>';
			}
		}
	}
	
	//update profile picture
	if(isset($_POST['sbt-dp']))
	{
		$target_file = "img/users/".$userId.'.jpg';
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	
		// Check if image file is a actual image or fake image
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false)
		{
			$uploadOk = 1;
		} 
		else 
		{
			$errMsg = "File is not an image.";
			$uploadOk = 0;
		}

		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 500000) {
			$errMsg = '<div class="alert alert-danger">Sorry, your file is too large.</div>';
			$uploadOk = 0;
		}

		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			$errMsg = '<div class="alert alert-danger">Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>';
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0 && $check == 0) 
		{
			$errMsg='<div class="alert alert-danger">Sorry, your file was not uploaded.</div>';
		// if everything is ok, try to upload file
		} 
		else 
		{
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
			{
				//Resize Image
				list( $width,$height ) = getimagesize($target_file);
				$newwidth = 500; // It makes the new image width of 500
				$newheight = 500*($height/$width); // It makes the new image height of 500
				$thumb = imagecreatetruecolor( $newwidth, $newheight ); // It loads the images we use jpeg function you can use any function like imagecreatefromjpeg
				//creating by filetype
				switch(strtolower($check['mime']))
				{
					case 'image/png':
						$source = imagecreatefrompng($target_file);
					break;
					case 'image/jpeg':
						$source = imagecreatefromjpeg($target_file);
					break;
					case 'image/jpg':
						$source = imagecreatefromjpeg($target_file);
					break;
					case 'image/gif':
						$source = imagecreatefromgif($target_file);
					break;
					default: die();
				}
				imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height); // Resize the $thumb image.
				imagejpeg( $thumb, $target_file, 90 ); // It then save the new image to the location specified by $resize_image variable

				$out_image=addslashes(file_get_contents($target_file));
				
				$errMsg = '<div class="alert alert-success">Updated DP, Refreshing Wait...</div>';
				header("Refresh:3");
			} 
			else 
			{
				$errMsg = '<div class="alert alert-danger">Sorry, there was an error uploading your file.</div>';
			}
		}
	}
	
	//Setting up user DP
	if(file_exists("img/users/$userId.jpg"))
		$dpPath = 'img/users/'.$userId.'.jpg';
	else
		$dpPath = 'img/blankprofile.png';
	
	include 'edit-account.htm';

?>