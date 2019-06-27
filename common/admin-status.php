<?php

	$trendingAdminStatus ='';
	for($i=0; $i<5; $i++)
	{
		//Setting up brand DP
		if(file_exists("img/brands/".$brandArrTab[$i]["name"].".jpg"))
			$dpPathAdminStatus = 'img/brands/'.$brandArrTab[$i]["name"].'.jpg';
		else
			$dpPathAdminStatus = 'img/brands/default.jpg';
		
		$trendingAdminStatus = $trendingAdminStatus.'<span data-toggle="tooltip" data-placement="top" title="" data-original-title="'.$brandArrTab[$i]["name"].'"><a href="search?q='.$brandArrTab[$i]["name"].'"><img src="'.$dpPathAdminStatus.'?123" width="18%" class="thumbnail" style="display:inline; margin:2px;"></a></span>';
	}

?>

<div class="panel panel-danger"> <!-- admin msg -->
	<div class="panel-body">
		<div class="content">
			<p><b>Trending Now</b></p>
			<hr class="colorgraph" style="margin:10px;">
			<p align=center><?php echo $trendingAdminStatus;?></p>
		</div>
	</div>
</div>