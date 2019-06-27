<?php 

	include 'common/mobile-detect.php';
    session_start();
	if(!isset($_SESSION["user_id"]))
	{
		$ismobile = check_user_agent('mobile');
		if($ismobile) 
		{
			include 'login.php';
		} 
		else 
		{
			include 'discover.php';
		}
	}
	else
		include 'feed.php';

?>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-5911313204936027",
    enable_page_level_ads: true
  });
</script>