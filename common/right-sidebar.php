<?php

	//Render new user tab
	$queryGetUsers = mysqli_query($db,"SELECT * FROM users ORDER BY user_id DESC LIMIT 5");
	while($r7=mysqli_fetch_array($queryGetUsers))
	{
		$tabUserName = $r7['user_first_name'].' '.$r7['user_last_name'];
		$panelUserId = $r7['user_id'];
		
		//See if it's not the user themselves
		if($userId!=$panelUserId)
		{	
			//Setting up user DP
			if(file_exists("img/users/$panelUserId.jpg"))
				$dpPath2 = 'img/users/'.$panelUserId.'.jpg';
			else
				$dpPath2 = 'img/blankprofile.png';
			
			//setting follow button
			$queryFollowButton = mysqli_query($db, "SELECT * FROM follow WHERE follow_uid=$panelUserId AND follow_fid=$userId LIMIT 5");
			if(mysqli_num_rows($queryFollowButton)==0)
			{
				$followStyle = "btn btn-danger btn-outline";
				$followText = "Follow";
				$followIcon = "glyphicon-plus-sign";
			}
			else
			{
				$followStyle = "btn btn-success btn-outline";
				$followText = "Following";
				$followIcon="";
			}
			
			if($userId!=0)//user logged in
			{
				$userPanel = $userPanel.
				'<p class="row" style="padding-left:15px; font-size:13px;">
				<a style="text-decoration:none;" href="profile?id='.$panelUserId.'">
					<img align=left src="'.$dpPath2.'?123" width=40px; height=40px; style="border-radius:10px; object-fit: cover;">
					<b>&nbsp;'.$tabUserName.'</b>
				</a> <br>
				&nbsp;<button type="button" id="btn-style-'.$panelUserId.'" class="btn btn-xs '.$followStyle.'" onclick="follow_toggle('.$panelUserId.','.$userId.');"><span class="glyphicon '.$followIcon.'" id="btn-icon-'.$panelUserId.'"></span> <span style="font-weight:bold;" id="follow_text_'.$panelUserId.'">'.$followText.'</span></button>
				</p>';
			}
			else
				$userPanel = $userPanel.
				'<p class="row" style="padding-left:15px; font-size:13px;">
				<a style="text-decoration:none;" href="profile?id='.$panelUserId.'">
					<img src="'.$dpPath2.'?123" width=40px; height=40px; style="border-radius:10px; object-fit: cover;">
					<b>&nbsp;'.$tabUserName.'</b>
				</a>
				</p>';
		}
	}
	
	//set trending items
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
	
	$itemTabBody = "";
	foreach($itemArr as $key => $value):
		$queryItemTrend = mysqli_query($db, "SELECT * FROM entries WHERE entry_id=$key");
		while($r4=mysqli_fetch_array($queryItemTrend))
		{
			$itemTabBody = $itemTabBody.'<div style="padding:5px;"><a href="comment?sid='.$r4['entry_id'].'"><span class="glyphicon glyphicon-fire" style="color:#d9534f;"></span> '.$r4['entry_brand_name'].' '.$r4['entry_name'].'</a></div>';
		}
	endforeach;

?>
