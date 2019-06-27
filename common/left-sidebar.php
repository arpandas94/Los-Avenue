<?php
	
	$totalSpending = "0";
	
	if($userId!=0)
	{
	//Render user details
	$queryUserDetails = mysqli_query($db,"SELECT * FROM users WHERE user_id=$userId");
	while($r=mysqli_fetch_array($queryUserDetails))
	{
		$userFirstName = $r['user_first_name'];
		$userName = $r['user_first_name'].' '.$r['user_last_name'];
		$userDN = $r['user_dn'];
		$userCurrencyId = $r['user_currency'];
		$userCountryId = $r['user_country'];
		
		//render currency and country details
		$queryRenderCurrency = mysqli_query($db, 'SELECT * FROM currency WHERE currency_id='.$r['user_currency']);
		while($r2=mysqli_fetch_array($queryRenderCurrency))
		{
			$userCurrencyName = $r2['currency_name'];
			$userCurrencySymbol = $r2['currency_symbol'];
		}
		
		$queryRenderCountry = mysqli_query($db, 'SELECT * FROM countries WHERE country_id='.$r['user_country']);
		while($r3=mysqli_fetch_array($queryRenderCountry))
		{
			$userCountryName = $r3['country_name'];
		}
		
		//Add total spendings in native currency
		$queryTotalSpending = mysqli_query($db,"SELECT * FROM entries WHERE entry_uploader=$userId");
		while($r=mysqli_fetch_array($queryTotalSpending))
		{
			$initNativePrice = $r['entry_native_price'];
			
			//converting in case user currency <> product currency
			$queryGetExchangeRate = mysqli_query($db,"SELECT * FROM currency WHERE currency_id=".$r['entry_currency']);
			while($r2=mysqli_fetch_array($queryGetExchangeRate))
				$exchangeRate = $r2['currency_exchange_rate'];
			$priceUSD = $initNativePrice/$exchangeRate; //convert to USD
			
			$queryGetExchangeRate = mysqli_query($db,"SELECT * FROM currency WHERE currency_id=".$userCurrencyId);
			while($r2=mysqli_fetch_array($queryGetExchangeRate))
				$exchangeRate = $r2['currency_exchange_rate'];
			
			$finNativePrice = $priceUSD*$exchangeRate;
			
			$totalSpending = $totalSpending + $finNativePrice;
		}
		
		//Adding symbol
		$queryTotalSpending = mysqli_query($db,"SELECT * FROM currency WHERE currency_id=$userCurrencyId");
		while($r6=mysqli_fetch_array($queryTotalSpending))
		{
			$currencySymbol= $r6['currency_symbol'];
			$totalSpending = '<b>'.$currencySymbol.'</b> '.number_format($totalSpending,2,'.',',');
		}
		
		//set follower following
		$queryFollowing = mysqli_query($db, "SELECT * FROM follow WHERE follow_fid=$userId");
		$followingCount = mysqli_num_rows($queryFollowing);
	
		$queryFollower = mysqli_query($db, "SELECT * FROM follow WHERE follow_uid=$userId");
		$followerCount = mysqli_num_rows($queryFollower);
	}

	//Setting up user DP
	if(file_exists("img/users/$userId.jpg"))
		$dpPath = 'img/users/'.$userId.'.jpg';
	else
		$dpPath = 'img/blankprofile.png?123';
	
	//set notification alert
	$notifCount = mysqli_num_rows(mysqli_query($db,"SELECT * FROM notifications WHERE notif_user_id=$userId AND notif_status=1"));
	if($notifCount==0)
		$notifStat = '';
	else
		$notifStat = '<span class="badge" style="background-color:red;">'.$notifCount.'</span>';
	}
	
	//Set trending brands
	$brandArrTab = array();
	$queryBrandTrend = mysqli_query($db, "SELECT DISTINCT entry_brand_name FROM entries");
	while($r1=mysqli_fetch_array($queryBrandTrend))
	{
		$entryBrandName = $r1['entry_brand_name'];
		$queryBrandTrend2 = mysqli_query($db, 'SELECT * FROM entries WHERE entry_brand_name="'.$entryBrandName.'"');
		while($r2=mysqli_fetch_array($queryBrandTrend2))
		{
			$queryBrandTrend3 = mysqli_query($db, "SELECT * FROM likes WHERE like_entry_id=".$r2['entry_id']);
			$nL = mysqli_num_rows($queryBrandTrend3);
			$queryBrandTrend4 = mysqli_query($db, "SELECT * FROM comments WHERE comment_entry=".$r2['entry_id']);
			$nC = mysqli_num_rows($queryBrandTrend4);
			if((2*$nC+$nL)==0)
				$rI = 1;
			else
				$rI = 1/(2*$nC+$nL);
			array_push($brandArrTab, array("name" => $entryBrandName, "ri" => $rI));
		}
	}
	
	array_multisort(array_column($brandArrTab, 'ri'), SORT_ASC, $brandArrTab);
	
	//setting max value of array till 20
	if(sizeof($brandArrTab) > 20)
		$brandArraySize = 20;
	else
		$brandArraySize = sizeof($brandArrTab);
	
	$trendingTopBrandsTab = "";
	for($i=0; $i<$brandArraySize; $i++)
	{
		$trendingTopBrandsTab .= '<a href="search?q='.$brandArrTab[$i]["name"].'"><p><i class="fa fa-tag" aria-hidden="true" style="color:#d9534f;"></i>&nbsp;'.$brandArrTab[$i]["name"].'</p></a>';
	}
?>