<?php
	include_once 'connect.php';
	include_once 'timestamp.php';
	
	$totalSpendingUSD = "0";
	$entryBody = "";
	$userPanel="";
	$searchTab = "";
	
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
	
	//Set search results
	if(isset($_GET['q']))
	{
		$searchQuery = htmlspecialchars(mysqli_real_escape_string($db, $_GET['q']));
	}
	else
		$searchQuery ="";
	
	if($searchQuery=="")
	{
		//Set random items
		$queryMaxItemNumber = mysqli_query($db, "SELECT * FROM entries");
		while($r=mysqli_fetch_array($queryMaxItemNumber))
			$maxItemNumber = $r['entry_id'];
		
		$randItemArr[] = array();
		
		for($i=0; $i<12; $i++)
		{
			$randItemArr[$i] = intval(rand(1,$maxItemNumber));
		}
		
		//print
		$searchTab = '<!-- Random Purchases --><div class="row" style="padding-left: 5px; padding-right: 5px;">';
		for($i=0; $i<12; $i++)
		{
			$searchTab = $searchTab.'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-4" style="padding:2px;">
				<a href="comment?sid='.$randItemArr[$i].'">
					<img width=100% height=120 class="thumbnail" style="object-fit: cover; border-radius:15px; margin:0px;" src="img/pic-uploads/'.$randItemArr[$i].'.jpg?123" alt="">
				</a>
			</div>';
		}
		$searchTab = $searchTab.'</div>';
	}
	else
	{
		$queryGetUserResults = mysqli_query($db, "SELECT * FROM users WHERE user_first_name LIKE '%$searchQuery%' OR user_last_name LIKE '%$searchQuery%' OR user_dn LIKE '%$searchQuery%'");
		$searchTab .= '<ul class="nav nav-tabs">
		        <li><a data-toggle="tab" href="#menu1"><b>Items</b></a></li>
				<li class="active"><a data-toggle="tab" href="#home"><b>People</b></a></li>
			</ul>
			<div class="tab-content">
				<div id="home" class="tab-pane fade in active">
					<div class="panel"> <!--People Tab-->
						<div class="panel-body">
							<div class="content">';
		while($r=mysqli_fetch_array($queryGetUserResults))
		{
			$uploaderId = $r['user_id'];
			//Setting up entry uploader DP
			if(file_exists("img/users/$uploaderId.jpg"))
				$dpPath3 = 'img/users/'.$uploaderId.'.jpg';
			else
				$dpPath3 = 'img/blankprofile.png?123';
			
			
			$searchTab = $searchTab.'<b><img src="'.$dpPath3.'?123" width="45px;" height="45px;" style="border-radius:10px; object-fit: cover;"> <a style="text-decoration:none;" href="profile?id='.$r['user_id'].'">'.$r['user_first_name'].' '.$r['user_last_name'].'</a></b>
						<span style="color:#d9534f;">@'.$r['user_dn'].'</span><hr style="margin:5px;">';
		}
		$searchTab .= '</div></div></div></div>';
		$queryGetResults = mysqli_query($db, "SELECT * FROM entries WHERE entry_brand_name LIKE '%$searchQuery%' OR entry_name LIKE '%$searchQuery%' OR entry_tags LIKE '%$searchQuery%' ORDER BY entry_id DESC");
		
		if(mysqli_num_rows($queryGetResults)==0 && mysqli_num_rows($queryGetUserResults)==0)
		{
			$searchTab = $searchTab.'<div class="panel"><div class="panel-body"><h3 align=center>No Result Found</h3>';
		}
		else
			$searchTab = $searchTab.'<div id="menu1" class="tab-pane fade">
				<div class="panel"> <!--Item Tab-->
					<div class="panel-body">
						<div class="content">';
		
		while($r=mysqli_fetch_array($queryGetResults))
		{
			$entryId = $r['entry_id'];
			$entryBrandName = $r['entry_brand_name'];
			$entryName = $r['entry_name'];
			$entryPrice = $r['entry_native_price'];
			$entryCurrency = $r['entry_currency'];
		
		//Convert to native currency for green bar (uploader preference)
		$queryGetExchangeRate = mysqli_query($db,"SELECT * FROM currency WHERE currency_id=$entryCurrency");
		while($r4=mysqli_fetch_array($queryGetExchangeRate))
		{
			//convert to USD
			$exchangeRate = $r4['currency_exchange_rate'];
			$priceUSD = $entryPrice/$exchangeRate;
			
			//Convert to user currency
			$queryConvertToUserCurr = mysqli_query($db,"SELECT * FROM currency WHERE currency_id=$userCurrencyId");
			while($r6=mysqli_fetch_array($queryConvertToUserCurr))
			{
				$exchangeRate = $r6['currency_exchange_rate'];
				$greenBoxContent  = $r6['currency_symbol'].' '.number_format($priceUSD*$exchangeRate,2,'.',',');
			}
		}
		
		//Convert to native currency for red bar (user preference)
		$queryGetExchangeRate = mysqli_query($db,"SELECT * FROM currency WHERE currency_id=$entryCurrency");
		while($r5=mysqli_fetch_array($queryGetExchangeRate))
		{
			$nativeCurrencySymbol = $r5['currency_symbol'];
			$redBoxContent = $nativeCurrencySymbol.' '.number_format($entryPrice,2,'.',',');
		}
		
		//Setting for hidden prices
		if($entryPrice==0)
		{
			$greenBoxContent = '<span class="glyphicon glyphicon-qrcode"></span>';
			$redBoxContent = '<span class="glyphicon glyphicon-barcode"></span>';
		}
			//creating entry bodies (html)
			$searchTab = $searchTab.'<p>
				<a href="comment?sid='.$entryId.'" style="text-decoration:none;">
				<img align=left src="img/pic-uploads/'.$entryId.'.jpg?123" height="100" width="100" class="thumbnail" style="object-fit: cover; display:inline; margin:0px; border-radius:12px;">
				&nbsp;
				<b>'.$entryBrandName.'<br>&nbsp;&nbsp;'.$entryName.'</b> 
				<br><br> 
				&nbsp;
				<span class="btn btn-sm btn-primary btn-outline">
				<b>'.$greenBoxContent.'</b>
				|
				<b>'.$redBoxContent.'</b>
				</span>
				</a>
			</p>
			<hr style="border-bottom: 0.01em #ccc solid; margin-bottom:10px;">';
			
		}
		$searchTab = $searchTab.'</div></div></div></div></div>';
		echo mysqli_error($db);
	}
	include 'search.htm';
?>