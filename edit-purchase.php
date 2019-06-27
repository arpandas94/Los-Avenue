<?php

	include_once 'connect.php';
	$errMsg = "";
	$entryBody = "";
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
	
	if(!isset($_GET['pid']))
	{
		header('location: login');
	}
	
	//Check if entry belongs to the user
	$queryCheckUser = mysqli_query($db,'SELECT * FROM entries WHERE entry_id='.$_GET['pid']);
	
	while($r=mysqli_fetch_array($queryCheckUser))
	{
		if($r['entry_uploader']!=$userId)
			header('location: login');
	}
	
	//check if entry exists
	if(mysqli_num_rows($queryCheckUser)==0)
			header('location: login');
	
	//Setting pre-existing values
	$querySetInputs = mysqli_query($db, "SELECT * FROM entries WHERE entry_id={$_GET['pid']}");
	while($r=mysqli_fetch_array($querySetInputs))
	{
		$txtId = $r['entry_id'];
		$txtBrandName = htmlspecialchars($r['entry_brand_name']);
		$txtItemName = htmlspecialchars($r['entry_name']);
		$txtTags = htmlspecialchars($r['entry_tags']);
		$txtPrice = $r['entry_native_price'];
		$txtStatus = htmlspecialchars($r['entry_status']);
		$txtCurrency = $r['entry_currency'];
	}
	
	//render currencies
	$query = 'SELECT * FROM currency';
	if($result = mysqli_query($db, $query))
	{
		$currencyInfo = '';
		while($r = mysqli_fetch_array($result))
		{	
			//selecting set currency
			if($txtCurrency==$r['currency_id'])
				$currencyInfo = $currencyInfo.'<option value="'.$r['currency_id'].'" selected>'.$r['currency_name'].' ('.$r['currency_symbol'].')</option>';
			else
				$currencyInfo = $currencyInfo.'<option value="'.$r['currency_id'].'">'.$r['currency_name'].' ('.$r['currency_symbol'].')</option>';
		}
	}
	else
	{
		echo 'Cannot Fetch Currency Information';
	}
	
	//Updating Entries
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
		$txtItemName =  strip_tags(mysqli_real_escape_string($db, $_POST['txt-item-name']));
		$txtTags = strip_tags(mysqli_real_escape_string($db, $_POST['txt-tags']));
		$txtStatus = strip_tags(mysqli_real_escape_string($db, $_POST['txt-status']));
		$txtPrice = $_POST['txt-price'];
		
		$queryUpdateEntries = "UPDATE entries SET entry_brand_name='$txtBrandName', entry_name='$txtItemName', entry_tags='$txtTags', entry_native_price=$txtPrice, entry_status='$txtStatus', entry_currency='$selCurrency' WHERE entry_id={$_GET['pid']}";
		if(mysqli_query($db, $queryUpdateEntries))
		{
			$errMsg = '<div class="alert alert-success">Purchase Updated</div>';
		}
		else
		{
			$errMsg = '<div class="alert alert-danger">Error updating purchase:'.mysqli_error($db).'</div>';
		}
	}
	
	//Updating picture
	if(isset($_POST['sbt-dp']))
	{
		$target_file = "img/pic-uploads/".$txtId.'.jpg';
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
				
				$errMsg = '<div class="alert alert-success">Updated DP</div>';
			}
			else 
			{
				$errMsg = '<div class="alert alert-danger">Sorry, there was an error uploading your file.</div>';
			}
		}
	}
	
	include 'edit-purchase.htm';

?>