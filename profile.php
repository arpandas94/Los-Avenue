<?php

	include_once 'connect.php';
	include_once 'timestamp.php';
	$totalSpending = "0";
	$entryBody = "";
	$tagBody="";
	$userPanel="";

	if(isset($_SESSION["user_id"]))
	{
		$userId = $_SESSION["user_id"];
	}
	else
	{
		header('location: /');
	}
	
	if(isset($_GET['id']))
	{
		$profileId = $_GET['id'];
	}
	else
		header('location: /');

	include_once 'common/right-sidebar.php';
	
	//Setting up user DP
	if(file_exists("img/users/$userId.jpg"))
		$dpPath = 'img/users/'.$userId.'.jpg';
	else
		$dpPath = 'img/blankprofile.png?123';
	
	//Setting up user first name for DP
	$queryUserDetails = mysqli_query($db,"SELECT * FROM users WHERE user_id=$userId");
	while($r=mysqli_fetch_array($queryUserDetails))
		$userFirstName = $r['user_first_name'];
	
	//Render user details
	$queryUserDetails = mysqli_query($db,"SELECT * FROM users WHERE user_id=$profileId");
	//see if user exists
	if(mysqli_num_rows($queryUserDetails)==0)
		header('location:/');
	
	while($r=mysqli_fetch_array($queryUserDetails))
	{
		$userName = $r['user_first_name'].' '.$r['user_last_name'];
		$profileUserFirstName = $r['user_first_name'];
		$userDN = $r['user_dn'];
		$userCurrencyId = $r['user_currency'];
		$userCountryId = $r['user_country'];
		$userBio = $r['user_bio'];

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
		$queryTotalSpending = mysqli_query($db,"SELECT * FROM entries WHERE entry_uploader=$profileId");
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

		//Adding Symbol
		$queryTotalSpending = mysqli_query($db,"SELECT * FROM currency WHERE currency_id=$userCurrencyId");
		while($r6=mysqli_fetch_array($queryTotalSpending))
		{
			$currencySymbol= $r6['currency_symbol'];
			$totalSpending = '<b>'.$currencySymbol.'</b> '.number_format($totalSpending,2,'.',',');
		}
	}
	
	//render entries
	$queryEntries = mysqli_query($db,"SELECT * FROM entries WHERE entry_uploader=$profileId ORDER BY entry_time DESC");
	
	//blank feed
	if(mysqli_num_rows($queryEntries)==0)
	{
		$entryBody = "<p align=center style='font-size:17px;'>Nothing uploaded by the User</p><br><eop></eop>";
	}
		
	
	while($r=mysqli_fetch_array($queryEntries))
	{
		$entryId = $r['entry_id'];
		$uploaderId = $r['entry_uploader'];
		$entryBrandName = $r['entry_brand_name'];
		$entryName = $r['entry_name'];
		$entryTags = $r['entry_tags'];
		$entryTime = time_elapsed_string($r['entry_time']);
		$entryStatus = $r['entry_status'];
		$entryPrice = $r['entry_native_price'];
		$entryCurrency = $r['entry_currency'];
		
		//Getting country
		$queryGetCountryId = mysqli_query($db, "SELECT * FROM users where user_id=$uploaderId");
		while($r=mysqli_fetch_array($queryGetCountryId))
		{
			$uploaderCountryId = $r['user_country'];
			$queryGetCountryName = mysqli_query($db,"SELECT * FROM countries WHERE country_id=$uploaderCountryId");
			while($r5=mysqli_fetch_array($queryGetCountryName))
			{
				$uploaderCountryName = $r5['country_name'];
			}
		}
		
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
		
		//render user name from user id
		$queryGetUploaderName = mysqli_query($db, "SELECT * FROM users WHERE user_id=$uploaderId");
		while($r= mysqli_fetch_array($queryGetUploaderName))
		{
			$uploaderName = $r['user_first_name'].' '.$r['user_last_name'];
		}
		
		//Setting up entry uploader DP
		if(file_exists("img/users/$uploaderId.jpg"))
			$dpPath2 = 'img/users/'.$uploaderId.'.jpg';
		else
			$dpPath2 = 'img/blankprofile.png';
		
		//Fetching tags
		$tagArray = explode(',', $entryTags);
		foreach($tagArray as $tagValue)
		{
			$tagBody = $tagBody.'<span class="label label-danger" style="font-size:12px;">#'.$tagValue.'</span> ';
		}
		
		//Counting Comments
		$queryCountComments = mysqli_query($db, "SELECT * FROM comments WHERE comment_entry=$entryId");
		$commentCount = mysqli_num_rows($queryCountComments);
		
		//Setting Likes
		$queryLikeSearch = mysqli_query($db, "SELECT * FROM likes WHERE like_user_id=$userId AND like_entry_id=$entryId");
		if(mysqli_num_rows($queryLikeSearch)=='0')
		{
			$likeText = "Like";
			$likeStyle = "btn-default";
		}
		else
		{
			$likeText = "Liked";
			$likeStyle = "btn-danger";
		}
		
		$queryLikeCount = mysqli_query($db, "SELECT * FROM likes WHERE like_entry_id=$entryId");
		$likeCount = mysqli_num_rows($queryLikeCount);
		
		//creating entry bodies (html)
		$entryBody = $entryBody.'<div class="panel"><div class="panel-heading"><span class="pull-right"><div class="dropdown"><button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><span class="glyphicon glyphicon-shopping-cart"></span> <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right"><li><a href="https://www.amazon.com/s/field-keywords='.$entryBrandName.' '.$entryName.'" target="_blank"><img src="img/icon-amazon.ico" align="left" width="20px;">&nbsp;Buy on Amazon</a></li><li><a href="https://www.flipkart.com/search?q='.$entryBrandName.' '.$entryName.'" target="_blank"><img src="img/icon-flipkart.png" align="left" width="20px;">&nbsp;Buy on Flipkart</a></li></ul></div></span><b><img align=left src="'.$dpPath2.'?123" width="45px" height="45px" style="border-radius:10px; object-fit: cover;"> &nbsp;<a href="profile?id='.$uploaderId.'">'.$uploaderName.'</a> <span style="font-size:11px; color:#aaa;">&middot; '.$entryTime.'</span></b><br>&nbsp;<span class="glyphicon glyphicon-globe"></span> '.$uploaderCountryName.'</div><div class="panel-body" style="padding-top:0px;"><p style="margin-top:0px;"> <a href="search?q='.$entryBrandName.'"><span class="btn btn-default-inverse btn-outline"><b>'.$entryBrandName.'</b></span></a> '.$entryName.' </p><a href="comment?sid='.$entryId.'#comment-anchor" class="thumbnail"><img src="img/pic-uploads/'.$entryId.'.jpg?123" alt="leo" style="max-height:400px;"> </a><h4 class="btn btn-success btn-outline" style="font-size:14px; width:49%;"><b>'.$greenBoxContent.'</b></h4> <h4 class="btn btn-danger btn-outline" style="font-size:14px; width:49%;"><b>'.$redBoxContent.'</b></h4><p> </p><p>'.$entryStatus.'</p><hr>Likes: <span class="badge" id="likeCount_'.$entryId.'">'.$likeCount.'</span> Comments: <span class="badge">'.$commentCount.'</span></div><div class="panel-footer"><button type="button" id="btn-style-'.$entryId.'" class="btn btn-sm '.$likeStyle.'" onclick="like_add('.$entryId.');"><span class="glyphicon glyphicon-heart"></span> <span id="like_text_'.$entryId.'">'.$likeText.'</span></button> <a href="comment?sid='.$entryId.'"><button type="button" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-comment"></span> Comment</button></a><span class="pull-right">	<a href="https://twitter.com/intent/tweet?counturl=http%3A%2F%2Flosavenue.com%2Fcomment%3Fsid%3D'.$entryId.'&text='.$uploaderName.' buys '.$entryBrandName.' '.$entryName.'&url=http%3A%2F%2Flosavenue.com%2Fcomment%3Fsid%3D'.$entryId.'&via=losavenue" onclick="window.open(this.href,\'child\',\'height=200,width=400\'); return false;"><span type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" data-original-title="Share to Twitter"><span class="fa fa-twitter"></span></button></span></a>&nbsp;<a href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Flosavenue.com%2Fcomment%3Fsid%3D'.$entryId.'&amp;src=sdkpreparse" target="_blank" onclick="window.open(this.href,\'child\',\'height=200,width=400\'); return false;"><span type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="Share to Facebook"><span class="fa fa-facebook"></span>&nbsp;</button></span></a></span></div></div><eop></eop>';
				
				$tagBody="";
	}	
	
	//Setting up user DP
	if(file_exists("img/users/$profileId.jpg"))
		$dpPath2 = 'img/users/'.$profileId.'.jpg';
	else
		$dpPath2 = 'img/blankprofile.png';
	
	//Setting up follow button
	if($profileId==$userId)
	{
		$followBtn = "";
	}
	else
	{
		$queryMain = mysqli_query($db, "SELECT * FROM follow WHERE follow_uid=$profileId AND follow_fid=$userId");
		if(mysqli_num_rows($queryMain) == 1)//if already following
		{
			$followBtn = '<button type="button" class="btn btn-sm btn-success">Following</button>';
		}
		else
		{
			$followBtn = '<button type="button" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-plus-sign"></span> Follow</button>';
		}
	}
	
	//set follower following
	$queryFollowing = mysqli_query($db, "SELECT * FROM follow WHERE follow_fid=$profileId");
	$followingCount = mysqli_num_rows($queryFollowing);
	
	$queryFollower = mysqli_query($db, "SELECT * FROM follow WHERE follow_uid=$profileId");
	$followerCount = mysqli_num_rows($queryFollower);
	
	//Set Bio
	if($userBio!="")
		$bioHTML = '<div class="panel">
					<div class="panel-body" style="padding: 5px;">
						<span><b>Bio:</b></span><br>
						<hr class="colorgraph" style="margin:2px;">
						<p style="margin-bottom: 0px;">'.$userBio.'</p>
					</div>
				</div>';
	else
		$bioHTML = '';
	
	include_once 'profile.htm';

?>