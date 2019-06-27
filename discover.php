<?php

	include_once 'connect.php';
	include_once 'timestamp.php';
	
	$errMsg="";
	
	//Setting up login
	if(!isset($_SESSION["user_id"]))
	{
		$cardDisplay = '<div class="item"> 
						<div class="well" style="padding: 15px;">
						<img alt="Brand" src="img/logo-emblem.jpg" width="50px;" class="center-block">
					<p align=center style="font-weight:bold; font-size:16px;">Log in to your account</p>
					<div id="errorMsg">'.$errMsg.'</div> 
					<form action="login.php" method="post" enctype="multipart/form-data" name="frm-create-account" target="_self">
                            <fieldset>
                                <div class="input-group mb-2 mr-sm-2 mb-sm-0">
										<div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
                                   <input class="form-control" placeholder="E-mail" name="txt-email" type="email" autofocus="">
                                </div><br>
                                <div class="input-group mb-2 mr-sm-2 mb-sm-0">
										<div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
                                   <input class="form-control" placeholder="Password" name="txt-password" type="password" value="">
                                </div><br>
									<input type="submit" name="sbt-acct" value="Sign In" class="btn btn-default-inverse btn-outline" onclick="return check_inputs();" style="width:100%; font-weight:bold; font-size:15px;">
                                <div class="checkbox">
                                    <label>
                                        <input name="chk-remember" type="checkbox" value="Remember Me">Stay signed in	
                                    </label>
									<a href="forgot-password" style="text-decoration:none;" class="pull-right">Forgot Password</a>
                                </div>
                            </fieldset>
					</form>
					<hr style="margin:5px;">
					<p align=center style="color:#767676; font-size:12px; font-weight:bold;">New to Los Avenue?</p>
					<a href="create-account"><button type="button" class="btn btn-danger" style="width:100%; font-size:15px;"><b> Join the Community</b></button></a>
						</div>
					</div>';
	}
	else
	{
		$cardDisplay = '';
	}
	
	//render cards
	$queryCards = mysqli_query($db, "SELECT * FROM entries ORDER BY entry_id DESC");
	while($r=mysqli_fetch_array($queryCards))
	{
		$entryId = $r['entry_id'];
		$uploaderId = $r['entry_uploader'];
		$entryBrandName = $r['entry_brand_name'];
		$entryName = $r['entry_name'];
		$entryPriceUsd = $r['entry_native_price'];
		
		//render user name from user id
		$queryGetUploaderName = mysqli_query($db, "SELECT * FROM users WHERE user_id=$uploaderId");
		while($r1= mysqli_fetch_array($queryGetUploaderName))
		{
			$uploaderName = $r1['user_first_name'].' '.$r1['user_last_name'];
		}
		
		//Currency Symbol
		$queryGetCurrencySymbol = mysqli_query($db, "SELECT * FROM currency WHERE currency_id={$r['entry_currency']}");
			while($r5=mysqli_fetch_array($queryGetCurrencySymbol))
				$currSym = $r5['currency_symbol'];
		
		//Setting for hidden prices
		if($entryPriceUsd==0)
			$entryPriceUsd='<span class="glyphicon glyphicon-qrcode"></span>';
		else
			$entryPriceUsd = $currSym.' '. number_format($entryPriceUsd,2,'.',',');
		
		$cardDisplay .= '<div class="item"> 
							<div class="well">
								<a href="comment?sid='.$entryId.'"><img src="img/pic-uploads/'.$entryId.'.jpg" alt="item" style="width:100%;"></a>
								<div class="well-head" style="padding:10px;">
									<p><a href="profile?id='.$uploaderId.'"><img src="img/users/'.$uploaderId.'.jpg" alt="userDP" style="width:30px; height:30px; border-radius:10px; object-fit: cover;">
									<b>'.$uploaderName.'</b></p></a>
									<p><a href="search?q='.$entryBrandName.'"><span class="btn btn-sm btn-default-inverse btn-outline"><b>'.$entryBrandName.'</b></span></a> '.$entryName.'</p>
									<hr style="border-bottom: 0.01em #ccc solid; margin-top:5px; margin-bottom:5px;">
									<p class="btn btn-default-inverse btn-outline"><b>'.$entryPriceUsd.'</b></p>
									<span class="pull-right">	
										<a href="https://twitter.com/intent/tweet?counturl=http%3A%2F%2Flosavenue.com%2Fcomment%3Fsid%3D'.$entryId.'&text='.$uploaderName.' buys '.$entryBrandName.' '.$entryName.'&url=http%3A%2F%2Flosavenue.com%2Fcomment%3Fsid%3D'.$entryId.'&via=losavenue" onclick="window.open(this.href,\'child\',\'height=200,width=400\'); return false;"><span type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" data-original-title="Share to Twitter"><span class="fa fa-twitter"></span></button></span></a>&nbsp;
										<a href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Flosavenue.com%2Fcomment%3Fsid%3D'.$entryId.'&amp;src=sdkpreparse" target="_blank" onclick="window.open(this.href,\'child\',\'height=200,width=400\'); return false;"><span type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="Share to Facebook"><span class="fa fa-facebook"></span>&nbsp;</button></span></a>
									</span>
								</div>
							</div>
						</div>';
	}
	
	include 'discover.htm';

?>