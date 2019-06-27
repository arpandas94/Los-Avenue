  <?php

	include_once 'connect.php';
	include_once 'timestamp.php';
	
	//Navbar Highlight
	$navStyleHome = "color:#d9534f; border-bottom:solid 2px red;";

	$totalSpendingUSD = "0";
	$entryBody = "";
	$userPanel="";
	$entryFilterText = "";
	$itemArr = array();
	
	if(isset($_SESSION["user_id"]))
	{
		$userId = $_SESSION["user_id"];
	}
	else
	{
		header('location: /');
	}
	
	include_once 'common/left-sidebar.php';
	include_once 'common/right-sidebar.php';
	
	//render entries
	$queryGetFollowingIds = mysqli_query($db, "SELECT * FROM follow WHERE follow_fid=$userId"); //Putting Filter
	if(mysqli_num_rows($queryGetFollowingIds)==0)
	{
		$entryFilterText = "entry_uploader=$userId";
	}
	else
	{
		while($r8=mysqli_fetch_array($queryGetFollowingIds))
			$entryFilterText = $entryFilterText."entry_uploader=".$r8['follow_uid']." or ";
		$entryFilterText = substr($entryFilterText, 0, -4); 
	}
	$queryEntries = mysqli_query($db,"SELECT * FROM entries WHERE entry_uploader=$userId OR $entryFilterText ORDER BY entry_time DESC");
	if(mysqli_num_rows($queryEntries)==0)
	{
		$entryBody = "<p align=center style='font-size:17px;'>Feed Empty. Click to upload your first purchase</p> <a href='add-purchase'><button type='button' class='btn btn-danger btn-outline center-block' style='width:auto;'><span class='glyphicon glyphicon-circle-arrow-up'></span> <b>Upload</b></button></a><br>";
		$entryBody = $entryBody."<p align=center style='font-size:17px;'>Or start following people by clicking here</p><a href='discover-people'><span class='btn btn-default center-block' data-toggle='tooltip' style='width:50%;' data-placement='top' title='Discover people' data-original-title='Discover Users'><span class='glyphicon glyphicon-user'></span> Discover People</span></a><br><eop></eop>";
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
		$entryBody = $entryBody.'<div class="panel"><div class="panel-heading"><span class="pull-right"><div class="dropdown"><button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><span class="glyphicon glyphicon-shopping-cart"></span> <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right"><li><a href="https://www.amazon.com/s/field-keywords='.$entryBrandName.' '.$entryName.'" target="_blank"><img src="img/icon-amazon.ico" align="left" width="20px;">&nbsp;Buy on Amazon</a></li><li><a href="https://www.flipkart.com/search?q='.$entryBrandName.' '.$entryName.'" target="_blank"><img src="img/icon-flipkart.png" align="left" width="20px;">&nbsp;Buy on Flipkart</a></li></ul></div></span><b><img align=left src="'.$dpPath2.'" width="45px" height="45px" style="border-radius:10px; object-fit: cover;"> &nbsp;<a href="profile?id='.$uploaderId.'">'.$uploaderName.'</a> <span style="font-size:11px; color:#aaa;">&middot; '.$entryTime.'</span></b><br>&nbsp;<span class="glyphicon glyphicon-globe"></span> '.$uploaderCountryName.'</div><div class="panel-body" style="padding-top:0px;"><p style="margin-top:0px;"> <a href="search?q='.$entryBrandName.'"><span class="btn btn-default-inverse btn-outline"><b>'.$entryBrandName.'</b></span></a> '.$entryName.' </p><a href="comment?sid='.$entryId.'#comment-anchor" class="thumbnail"><img src="img/pic-uploads/'.$entryId.'.jpg?123" alt="leo" style="max-height:400px;"> </a><h4 class="btn btn-success btn-outline" style="font-size:14px; width:49%;"><b>'.$greenBoxContent.'</b></h4> <h4 class="btn btn-danger btn-outline" style="font-size:14px; width:49%;"><b>'.$redBoxContent.'</b></h4><p> </p><p>'.$entryStatus.'</p><hr>Likes: <span class="badge" id="likeCount_'.$entryId.'">'.$likeCount.'</span> Comments: <span class="badge">'.$commentCount.'</span></div><div class="panel-footer center-block"><button type="button" id="btn-style-'.$entryId.'" class="btn btn-sm '.$likeStyle.'" onclick="like_add('.$entryId.');"><span class="glyphicon glyphicon-heart"></span> <span id="like_text_'.$entryId.'">'.$likeText.'</span></button> <a href="comment?sid='.$entryId.'"><button type="button" class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Comment here"><span class="glyphicon glyphicon-comment"></span> Comment</button></a><span class="pull-right">	<a href="https://twitter.com/intent/tweet?counturl=http%3A%2F%2Flosavenue.com%2Fcomment%3Fsid%3D'.$entryId.'&text='.$uploaderName.' buys '.$entryBrandName.' '.$entryName.'&url=http%3A%2F%2Flosavenue.com%2Fcomment%3Fsid%3D'.$entryId.'&via=losavenue" onclick="window.open(this.href,\'child\',\'height=200,width=400\'); return false;"><span type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" data-original-title="Share to Twitter"><span class="fa fa-twitter"></span></button></span></a>&nbsp;<a href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Flosavenue.com%2Fcomment%3Fsid%3D'.$entryId.'&amp;src=sdkpreparse" target="_blank" onclick="window.open(this.href,\'child\',\'height=200,width=400\'); return false;"><span type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="Share to Facebook"><span class="fa fa-facebook"></span>&nbsp;</button></span></a></span></div></div><eop></eop>';
	}	
	
	include 'feed.htm';

?>