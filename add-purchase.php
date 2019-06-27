<?php

	include_once 'connect.php';
	$errMsg  = "";
	$userPanel="";
	
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
	
	//render currencies
	$query = 'SELECT * FROM currency';
	if($result = mysqli_query($db, $query))
	{
		$currencyInfo = '';
		while($r = mysqli_fetch_array($result))
		{	
			$currencyInfo = $currencyInfo.'<option value="'.$r['currency_id'].'">'.$r['currency_name'].' ('.$r['currency_symbol'].')</option>';
		}
	}
	else
	{
		echo 'Cannot Fetch Currency Information';
	}
	
	//Adding Entry
	if(isset($_POST['sbt-acct']))
	{
		//Setting Disabled field values
		if(isset($_POST['txt-price']))
			$txtPrice = $_POST['txt-price'];
		else
			$txtPrice = "0";
		
		if(isset($_POST['sel-currency']))
			$selCurrency = $_POST['sel-currency'];
		else
			$selCurrency = "1";
		
		//Strip HTML tags
		$txtBrandName = strip_tags(mysqli_real_escape_string($db, $_POST['txt-brand-name']));
		$txtItemName = strip_tags(mysqli_real_escape_string($db, $_POST['txt-item-name']));
		$txtTags = strip_tags(mysqli_real_escape_string($db, $_POST['txt-tags']));
		$txtStatus = strip_tags(mysqli_real_escape_string($db, $_POST['txt-status']));
	
		$query = "INSERT INTO entries (entry_id, entry_uploader, entry_brand_name, entry_name, entry_tags, entry_native_price, entry_status, entry_time, entry_currency) VALUES (0,$userId,'$txtBrandName','$txtItemName','$txtTags', $txtPrice, '$txtStatus', NOW(),'$selCurrency')";
		if(mysqli_query($db, $query))
		{
			//update profile picture
			$lastId = mysqli_insert_id($db); //get recent id to match entry id to pic file name for easier fetch
			
			$target_file = "img/pic-uploads/".$lastId.'.jpg';
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
				$errMsg = '<div class="alert alert-danger">File is not an image.</div>';
				$uploadOk = 0;
			}

			// Check file size
			if ($_FILES["fileToUpload"]["size"] > 500000) 
			{
				$errMsg = '<div class="alert alert-danger">Sorry, your file is too large.</div>';
				$uploadOk = 0;
			}

			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) 
			{
				$errMsg = '<div class="alert alert-danger">Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>';
				$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0 && $check == 0) 
			{
				//$errMsg='<div class="alert alert-danger">Sorry, your file was not uploaded.</div>';
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
				} 
				else 
				{
					$errMsg = '<div class="alert alert-danger">Sorry, there was an error uploading your picture.</div>';
				}
			}
		
			header('location: feed');
		}
		
	}
	
	
	include 'add-purchase.htm';

?>