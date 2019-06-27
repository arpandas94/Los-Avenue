<?php

	include 'connect.php';
	
	$jsonData = file_get_contents("http://api.fixer.io/latest?base=USD");
	$jsonArray = json_decode($jsonData,true);
	foreach($jsonArray['rates'] as $jsonCurrencySymbol => $jsonCurrencyRate)
	{
		if($queryUpdateExchangeRate = mysqli_query($db, "UPDATE currency SET currency_exchange_rate='$jsonCurrencyRate' WHERE currency_short_form='$jsonCurrencySymbol'"))
			echo "<span style='color:green;'>Rate Updated</span>: $jsonCurrencySymbol = $jsonCurrencyRate<br>";
		else
			echo "<span style='color:red;'>Error</span>: $jsonCurrencySymbol = $jsonCurrencyRate<br>";
	}

?>