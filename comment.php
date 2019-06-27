<?php

	include 'connect.php';
	include 'delete-comment.php';
	include 'timestamp.php';
	
	$errMsg = "";
	$deleteOption="";
	$commentBody= "";
	$tagBody="";
	$userPanel="";
	$totalSpendingUSD=0;
	
	if(isset($_SESSION["user_id"]))
	{
		$userId = $_SESSION["user_id"];
	}
	else
	{
		$userId = "0";
	}
	
	if(!isset($_GET['sid']))
	{
		header('location: login');
	}
	
	include_once 'common/left-sidebar.php';
	include_once 'common/right-sidebar.php';
	
	//get entry
	$queryGetEntry = mysqli_query($db,"SELECT * FROM entries WHERE entry_id={$_GET['sid']}");
	
	//check if entry exists
	if(mysqli_num_rows($queryGetEntry)==0)
		header('location: login');
	
	while($r=mysqli_fetch_array($queryGetEntry))
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
		
		
		//render follow button
		if($uploaderId!=$userId)
			$followButton = '<button type="button" id="btn-style-'.$uploaderId.'" class="pull-right btn btn-sm '.$followStyle.'" onclick="follow_toggle('.$uploaderId.','.$userId.');"><span class="glyphicon '.$followIcon.'" id="btn-icon-'.$uploaderId.'"></span> <span style="font-weight:bold;" id="follow_text_'.$uploaderId.'">'.$followText.'</span></button>';
		else
			$followButton = '';
		
		if($userId=="0")
			$followButton = '<a href="/"><button type="button" class="pull-right btn btn-sm btn btn-danger btn-outline"><span class="glyphicon glyphicon-plus-sign"></span> <span style="font-weight:bold;">Follow</span></button></a>';
		
		//render user name from user id
		$queryGetUploaderName = mysqli_query($db, "SELECT * FROM users WHERE user_id=$uploaderId");
		while($r= mysqli_fetch_array($queryGetUploaderName))
		{
			$uploaderName = $r['user_first_name'].' '.$r['user_last_name'];
		}
		
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
		
		
		if(isset($userCurrencyId))
		{
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
		}
		else
			$greenBoxContent= '<span class="glyphicon glyphicon-qrcode"></span>';
		
		//Convert to native currency for red bar (user preference)
		$queryGetExchangeRate = mysqli_query($db,"SELECT * FROM currency WHERE currency_id=$entryCurrency");
		while($r5=mysqli_fetch_array($queryGetExchangeRate))
		{
			$nativeCurrencySymbol = $r5['currency_symbol'];
			$redBoxContent = $nativeCurrencySymbol.' '.number_format($entryPrice,2,'.',',');
		}
	}
	
	//Setting for hidden prices
	if($entryPrice==0)
	{
		$greenBoxContent = '<span class="glyphicon glyphicon-qrcode"></span>';
		$redBoxContent = '<span class="glyphicon glyphicon-barcode"></span>';
	}
	
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
		
	if($userId==0)
		$likeButton='<a href="/"><button type="button" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-heart"></span> Like</button></a>';
	else
		$likeButton='<button type="button" id="btn-style-'.$entryId.'" class="btn btn-sm '.$likeStyle.'" onclick="like_add('.$entryId.');"><span class="glyphicon glyphicon-heart"></span> <span id="like_text_'.$entryId.'">'.$likeText.'</span></button>';	
		
	$queryLikeCount = mysqli_query($db, "SELECT * FROM likes WHERE like_entry_id=$entryId");
	$likeCount = mysqli_num_rows($queryLikeCount);
	
	//Setting up uploader DP
	if(file_exists('img/users/'.$uploaderId.'.jpg'))
		$dpPath3 = 'img/users/'.$uploaderId.'.jpg';
	else
		$dpPath3 = 'img/blankprofile.png';
	
	//Fetching tags
	$tagArray = explode(',', $entryTags);
	foreach($tagArray as $tagValue)
	{
		$tagBody = $tagBody.'<span class="label label-default" style="font-size:12px;">#'.$tagValue.'</span> ';
	}
	
	//fetching comments
	$queryFetchComment = mysqli_query($db,"SELECT * FROM comments WHERE comment_entry={$_GET['sid']} ORDER BY comment_time DESC");
	
	//Counting Comments
	$queryCountComments = mysqli_query($db, "SELECT * FROM comments WHERE comment_entry={$_GET['sid']}");
	$commentCount = mysqli_num_rows($queryCountComments);
	
	if(mysqli_num_rows($queryFetchComment)==0)
		$commentBody = '<p>No Comments</p>';
	
	while($r=mysqli_fetch_array($queryFetchComment))
	{
		//get commenter's name from user id
		$query_commenter_name = mysqli_query($db,'SELECT * FROM users WHERE user_id='.$r['comment_sender']);
		while($r2=mysqli_fetch_array($query_commenter_name))
		{
			$commenterName = $r2['user_first_name'].' '.$r2['user_last_name'];
			echo mysqli_error($db);
		}
		
		//check if comment is the user's so they can delete it
		if($userId==$r['comment_sender'])
		{
			$deleteOption='<a href="delete-comment?cid='.$r['comment_id'].'"><button class="btn btn-danger btn-xs">Delete</button></a>';
			//deleteComment($r['comment_id']); Learn JQuery
		}
		else
			$deleteOption="";
		
		//Setting up commenter DP
		if(file_exists('img/users/'.$r['comment_sender'].'.jpg'))
			$dpPath2 = 'img/users/'.$r['comment_sender'].'.jpg';
		else
			$dpPath2 = 'img/blankprofile.png';
		
		//creating the comment html body
		$commentBody = $commentBody.'<hr><p><img width="40px" height="40px" style="border-radius:10px; object-fit: cover;" src="'.$dpPath2.'">&nbsp;<b>'.$commenterName.'</b> &middot; <span style="color:#aaa; size:10px;">'.time_elapsed_string($r['comment_time']).'</span></p>
						<p>'.$r['comment_content'].'</p>
						<p>'.$deleteOption.'</p>';
	}
	
	
	//adding comments
	if(isset($_POST['sub-comment']))
	{
		if($userId!=0) //User logged in
		{
			$commentContent = strip_tags(mysqli_real_escape_string($db, $_POST['txt-comment-name']));
		
			if(mysqli_query($db, "INSERT INTO comments (comment_id, comment_sender, comment_entry, comment_time, comment_content) VALUES (0, $userId, {$_GET['sid']}, NOW(), '$commentContent')"))
			{
				$errMsg = '<div class="alert alert-success">Comment Added!</div>';
				//create notification
				if($uploaderId!=$userId) //no notification for self commenting
				{
					$queryNotif = mysqli_query($db, "SELECT * FROM users WHERE user_id=$userId");
					while($r=mysqli_fetch_array($queryNotif))
					{
						$notifUserName = $r['user_first_name'].' '.$r['user_last_name'];
					$queryAddNotif = mysqli_query($db, 'INSERT INTO notifications (notif_id, notif_user_id, notif_text, notif_date, notif_status, notif_link, notif_pic_link) VALUES (0, '.$uploaderId.', "'.$notifUserName.' commented on your post", NOW(), 1, "comment?sid='.$_GET['sid'].'", '.$userId.')');
					}
				}
				header( "refresh:0;" );
			}
		else
			$errMsg = '<div class="alert alert-danger">Error adding comment</div>';	
		}
		else
		{
			header('location:login');
		}
		
	}
	
	
	include 'comment.htm';
?>