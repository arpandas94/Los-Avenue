<?php

	include_once 'connect.php';
	$errMsg  = "";
	
	if(isset($_SESSION["user_id"]))
	{
		header('location: login');
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
			$countryInfo = $countryInfo.'<option value="'.$countryId.'">'.$countryName.'</option>';
		}
	}
	else
	{
		echo 'Cannot Fetch Country Information';
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
			$currencyInfo = $currencyInfo.'<option value="'.$currencyId.'">'.$currencyName.' ( '.$currencySymbol.' )'.'</option>';
			
		}
	}
	else
	{
		echo 'Cannot Fetch Currency Information';
	}
	
	//Add user
	if(isset($_POST['sbt-acct']))
	{
		//Check if username/email exists
		$queryDuplicateEmailCheck = 'SELECT * FROM users WHERE user_email="'.$_POST['txt-email'].'"';
		$resultDuplicateEmail = mysqli_query($db,$queryDuplicateEmailCheck);
		
		$queryDuplicateDnCheck = 'SELECT * FROM users WHERE user_dn="'.$_POST['txt-display-name'].'"';
		$resultDuplicateDn = mysqli_query($db,$queryDuplicateDnCheck);
		
		if(mysqli_num_rows($resultDuplicateEmail) > 0)
		{
			$errMsg = '<div class="alert alert-danger">This email address already exists</div>';
		}
		elseif(mysqli_num_rows($resultDuplicateDn) > 0)
		{
			$errMsg = '<div class="alert alert-danger">This display name already exists</div>';
		}
		else
		{
			//Strip HTML tags
			$txtFirstName = strip_tags(mysqli_real_escape_string($db, $_POST['txt-first-name']));
			$txtLastName = strip_tags(mysqli_real_escape_string($db, $_POST['txt-last-name']));
			$txtDN = strip_tags(mysqli_real_escape_string($db, $_POST['txt-display-name']));
			$txtConfirmPassword = strip_tags(mysqli_real_escape_string($db, $_POST['txt-confirm-password']));
			
			$query = "INSERT INTO users (user_id, user_first_name, user_last_name, user_dn, user_gender, user_email, user_country, user_currency, user_password) VALUES (0, '$txtFirstName', '$txtLastName', '$txtDN','{$_POST['sel-gender']}','{$_POST['txt-email']}','{$_POST['sel-country']}','{$_POST['sel-currency']}', '$txtConfirmPassword')";
			
			if(mysqli_query($db, $query))
			{
				//Fetching new user id and creating session and finally redirecting to edit account page
				$query2 = "SELECT * FROM users WHERE user_email = '{$_POST['txt-email']}'";
				$result = mysqli_query($db, $query2);
				$op = mysqli_fetch_array($result);
				$id = $op['user_id'];
				$_SESSION["user_id"] = $id;
				header("Location: edit-account#changeDP");
			}
			else
			{
				echo mysqli_error($db);
			}
		}
	}
	
	include 'create-account.htm';
?>