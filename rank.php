<?php

	include_once 'connect.php';
	include_once 'timestamp.php';
	
	//Navbar Highlight
	$navStyleRates = "color:#d9534f; border-bottom:solid 2px red;";
	
	$totalSpendingUSD = "0";
	$totalSpendingRankUSD = "0";
	$userPanel = "";
	$rankBody = "";
	$list = array();
	$serialNumber = 1;
	
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
	
	//Render Ranking
	$queryGetEntries = mysqli_query($db, "SELECT * FROM users");
	while($r=mysqli_fetch_array($queryGetEntries))
	{
		$entryUserId = $r['user_id'];
		$queryTotalSpendingUSD = mysqli_query($db,"SELECT * FROM entries WHERE entry_uploader=$entryUserId");
		while($r2=mysqli_fetch_array($queryTotalSpendingUSD))
		{
			//convert to USD
			$queryGetExchangeRate = mysqli_query($db,"SELECT * FROM currency WHERE currency_id=".$r2['entry_currency']);
			while($r4=mysqli_fetch_array($queryGetExchangeRate))
			{
				$exchangeRate = $r4['currency_exchange_rate'];
				$pUSD = $r2['entry_native_price']/$exchangeRate;
			}
			
			$totalSpendingRankUSD = $totalSpendingRankUSD + $pUSD;		
		}

		$list[$entryUserId] = $totalSpendingRankUSD;
		$totalSpendingRankUSD = 0;
	}
	
	arsort($list);
	
	foreach($list as $key => $value):
		$queryDisplayUserDetails = mysqli_query($db, "SELECT * FROM users WHERE user_id=$key");
		while($r3=mysqli_fetch_array($queryDisplayUserDetails))
		{
			$displayUserName = $r3['user_first_name'].' '.$r3['user_last_name'];
			$displayUserDN = $r3['user_dn'];
			$displayUserId = $r3['user_id'];
			
			//setting default dpPath
			if(file_exists("img/users/$displayUserId.jpg"))
				$dpPath3 = 'img/users/'.$displayUserId.'.jpg';
			else
				$dpPath3 = 'img/blankprofile.png';
		}
		$rankBody = $rankBody.'<p>&nbsp;'.$serialNumber.'. <a href="profile?id='.$displayUserId.'" style="text-decoration:none;"><img align=left src="'.$dpPath3.'"  width="45px;" height="45px;" style="border-radius:10px; object-fit: cover; display:inline; margin:0px;">
						<span class=""><b>'.$displayUserName.'</b>(<span style="color:#d9534f;">@'.$displayUserDN.'</span>)</a> <br>
						&nbsp;&nbsp;<span class="btn btn-success btn-outline btn-xs" style="font-size:12px;"><b>$'.number_format($value,2,'.',',').'</b></span></span></p><hr style="margin:7px;">';
		$serialNumber++;
		
	endforeach;
	
	include 'rank.htm';
?>