<?php

	include_once 'connect.php';
	include_once 'timestamp.php';
	
	//Navbar Highlight
	$navStyleTrending = "color:#d9534f; border-bottom:solid 2px red;";
	
	$totalSpendingUSD = "0";
	$entryBody = "";
	$userPanel="";
	$entryFilterText = "";
	$brandArr = array();
	$itemArr = array();
	
	if(isset($_SESSION["user_id"]))
	{
		$userId = $_SESSION["user_id"];
	}
	else
	{
		header('location: login.php');
	}
	
	include_once 'common/left-sidebar.php';
	include_once 'common/right-sidebar.php';
	
	//Trending Tab
	//Trending Brands
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
			array_push($brandArr, array("name" => $entryBrandName, "ri" => $rI));
		}
	}
	
	array_multisort(array_column($brandArr, 'ri'), SORT_ASC, $brandArr);
	
	$trendingTopBrandsBody = "";
	for($i=0; $i<5; $i++)
	{
		//Setting up brand DP
		if(file_exists("img/brands/".$brandArr[$i]["name"].".jpg"))
			$dpPath2 = 'img/brands/'.$brandArr[$i]["name"].'.jpg';
		else
			$dpPath2 = 'img/brands/default.jpg';
		
		$trendingTopBrandsBody = $trendingTopBrandsBody.'<span data-toggle="tooltip" data-placement="top" title="" data-original-title="'.$brandArr[$i]["name"].'"><a href="search?q='.$brandArr[$i]["name"].'"><img src="'.$dpPath2.'?123" width="18%" class="thumbnail" style="display:inline; margin:2px;"></a></span>';
	}
	
	//Trending Items
	$queryItemTrend = mysqli_query($db, 'SELECT * FROM entries');
	while($r3=mysqli_fetch_array($queryItemTrend))
	{
		$queryBrandTrend3 = mysqli_query($db, "SELECT * FROM likes WHERE like_entry_id=".$r3['entry_id']);
		$nL = mysqli_num_rows($queryBrandTrend3);
		$queryBrandTrend4 = mysqli_query($db, "SELECT * FROM comments WHERE comment_entry=".$r3['entry_id']);
		$nC = mysqli_num_rows($queryBrandTrend4);
		$pNat = $r3['entry_native_price'];
		$natCurr = $r3['entry_currency'];
		
		//convert native to usd for equalizing trending items
		$queryGetExchangeRate = mysqli_query($db,"SELECT * FROM currency WHERE currency_id=$natCurr");
		while($r4=mysqli_fetch_array($queryGetExchangeRate))
		{
			$exchangeRate = $r4['currency_exchange_rate'];
			$pUSD = $pNat/$exchangeRate;
		}
		
		if($pUSD==0)
			$pUSD=1;
		
		if((2*$nC+$nL)==0)
			$rI = $pUSD;
		else
			$rI = 1/((2*$nC+$nL)*$pUSD);
		$itemArr[$r3['entry_id']] = $rI;
	}
	
	asort($itemArr);
	
	$itemTrendBody = "";
	$trendItemCounter = 0;
	foreach($itemArr as $key => $value):
		$queryItemTrend = mysqli_query($db, "SELECT * FROM entries WHERE entry_id=$key");
		while($r4=mysqli_fetch_array($queryItemTrend))
		{
			//get native price symbol
			$queryGetCurrencySymbol = mysqli_query($db, "SELECT * FROM currency WHERE currency_id={$r4['entry_currency']}");
			while($r5=mysqli_fetch_array($queryGetCurrencySymbol))
				$currSym = $r5['currency_symbol'];
			
			if($r4['entry_native_price']==0)
				$entryPrice = '<span class="glyphicon glyphicon-barcode"></span>';
			else
				$entryPrice = $currSym.' '.number_format($r4['entry_native_price'],2,'.',',');
			
			$itemTrendBody = $itemTrendBody.
			'<p>
				<a href="comment?sid='.$r4['entry_id'].'" style="text-decoration:none;">
				<img align=left src="img/pic-uploads/'.$r4['entry_id'].'.jpg?123" height="100" width="100" class="thumbnail" style="object-fit: cover; display:inline; margin:0px; border-radius:12px;">
				&nbsp;
				<b>'.$r4['entry_brand_name'].'<br>&nbsp;'.$r4['entry_name'].'</b> 
				<br><br> 
				&nbsp;<span class="btn btn-sm btn-danger btn-outline">
				<b>'.$entryPrice.'</b>
				</span></a>
			</p>
			<hr style="border-bottom: 0.01em #ccc solid; margin-bottom:10px;">';
		}
		
		if($trendItemCounter==20)
			break;
		else
			$trendItemCounter ++;
		
	endforeach;
	
	$trendingTab = '<p class="text-center">
					'.$trendingTopBrandsBody.'
					</p>
					<br>
					<h4>Trending Items</h4>
					<hr>'.$itemTrendBody;
	
	
	include 'trending.htm';

?>