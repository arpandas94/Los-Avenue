<?php
	include_once 'connect.php';
	include_once 'timestamp.php';
	
	//Navbar Highlight
	$navStyleBrands = "color:#d9534f; border-bottom:solid 2px red;";
	
	$totalSpendingUSD = "0";
	$entryBody = "";
	$userPanel="";
	$brandsTab = "";
	
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
	
	//Set brands page
	$queryAddBrands = mysqli_query($db, "SELECT DISTINCT entry_brand_name FROM entries ORDER BY entry_brand_name ASC");
	while($r4=mysqli_fetch_array($queryAddBrands))
	{
		$brandName = $r4['entry_brand_name'];
		
		//Setting up brand icon
		if(file_exists("img/brands/$brandName.jpg"))
			$dpBrandIconPath = 'img/brands/'.$brandName.'.jpg';
		else
			$dpBrandIconPath = 'img/brands/default.jpg';
		
	//Calculating number of items
		$queryNumberOfItems = mysqli_num_rows(mysqli_query($db, "SELECT * FROM entries WHERE entry_brand_name='$brandName'"));
		$brandsTab = $brandsTab.'<p>
									<a href="search?q='.$brandName.'" style="text-decoration:none;">
									<img align=left src="'.$dpBrandIconPath.'" height="65" class="thumbnail" style="object-fit: cover; display:inline; margin:0px; border-radius:10px;">
									&nbsp;
									<b>'.$brandName.'</b> 
									<br> 
									&nbsp;<span style="color:#999;">
									'.$queryNumberOfItems.' Item(s)
									</span></a>
								</p>
								<br>';
	}
	
	include 'brands.htm';
?>