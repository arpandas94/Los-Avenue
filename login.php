<?php
	include 'connect.php';
	$errMsg="";
	
	if(isset($_SESSION["user_id"]))
	{
		header('location: /');
	}
	
	//See if already logged in
	if(isset($_COOKIE['ckEmail']) && $_COOKIE['ckPass'])
	{
		$query = 'SELECT * FROM users WHERE user_email="'.$_COOKIE['ckEmail'].'"';
		if($result = mysqli_query($db, $query))
		{
			$r = mysqli_fetch_array($result);
			if($r['user_password']==$_COOKIE['ckPass'])
			{	
				$_SESSION["user_id"] = $r['user_id']; 
		        @header('location:/');
			}
			else
			{
				$errMsg = '<div class="alert alert-danger">Wrong Cookie Information</div>';
			}				
		}
	}
	
	if(isset($_POST['sbt-acct']))
	{
		$query = 'SELECT * FROM users WHERE user_email="'.$_POST['txt-email'].'"';
		if($result = mysqli_query($db, $query))
		{
			$r = mysqli_fetch_array($result);
			if($r['user_password']==$_POST['txt-password'])
			{
				//Stay logged In or not
				if(isset($_POST['chk-remember']))
				{
					setcookie("ckEmail", $_POST['txt-email'], time()+(86400 * 30));
					setcookie("ckPass", $_POST['txt-password'], time()+(86400 * 30));
				}
				else
				{
					setcookie("ckEmail", '', time()-(86400 * 30));
					setcookie("ckPass", '', time()-(86400 * 30));
				}
				
				$_SESSION["user_id"] = $r['user_id']; 
				header('location:/');
			}
			else
			{
				$errMsg = '<div class="alert alert-danger">Email/Password Incorrect</div>';
			}				
		}
	}
	
	//Trending Tab
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
			
			$itemTrendBody = $itemTrendBody.'<p><a href="comment?sid='.$r4['entry_id'].'" style="text-decoration:none;"><img align=left src="img/pic-uploads/'.$r4['entry_id'].'.jpg?123" height="100" width="100" class="thumbnail" style="object-fit: cover; display:inline; margin:0px; border-radius:12px;">&nbsp;<b>'.$r4['entry_brand_name'].'<br>&nbsp;'.$r4['entry_name'].'</b> <br><br> &nbsp;<span class="btn btn-sm btn-danger btn-outline"><b>'.$entryPrice.'</b></span></a></p><hr style="border-bottom: 0.01em #ccc solid; margin-bottom:10px;">';
		}
		
		if($trendItemCounter==5)
			break;
		else
			$trendItemCounter ++;
	endforeach;
	
	$trendingTab = '<hr class="colorgraph">'.$itemTrendBody;
	
	include 'login.htm';
?>