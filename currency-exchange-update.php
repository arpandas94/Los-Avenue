<?php
	
	include_once 'connect.php';
	set_time_limit(600);
	
	//Adding new currencies
	if(isset($_POST['sbt-add']))
	{
		$currencyName = $_POST['txt-name'];
		$currencySymbol = $_POST['txt-symbol'];
		$currencyShort = $_POST['txt-short-form'];
		$queryAddNewCurrency = mysqli_query($db, "INSERT INTO currency (currency_id, currency_name, currency_symbol, currency_short_form, currency_exchange_rate) VALUES (0, '$currencyName', '$currencySymbol', '$currencyShort', '1')");
		echo 'New Currency Added';
	}
	
	//Updating exchange rates
	if(isset($_POST['sbt-upd']))
	{
		$queryUpdateCurrencyExchangeRates = mysqli_query($db, "SELECT * FROM currency WHERE currency_id=1 OR currency_id=2 OR currency_id=10 OR currency_id=11 OR currency_id=17 OR currency_id=19 OR currency_id=20 OR currency_id=24 OR currency_id=33 OR currency_id=40 OR currency_id=41 OR currency_id=57");
		while($r=mysqli_fetch_array($queryUpdateCurrencyExchangeRates))
		{
			$currencySymbol = $r['currency_short_form'];
			
			$from   = 'USD'; /*change it to your required currencies */
			$to     = $currencySymbol;
			$url = 'http://finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s='. $from . $to .'=X';
			$handle = @fopen($url, 'r');
 
			if ($handle) 
			{
				$result = fgets($handle, 4096);
				fclose($handle);
				$allData = explode(',',$result); /* Get all the contents to an array */
				$dollarValue = $allData[1];
			}
			
			$queryUpdateExRate = mysqli_query($db, "UPDATE currency SET currency_exchange_rate='$dollarValue' WHERE currency_short_form='$currencySymbol'");
			echo mysqli_error($db);
		}
	}
	
	
?>

<html>
<head></head>
<body>

	<form action="#" method="post" name="frm-add-purchase" target="_self">
		<input name="txt-name" class="form-control" type="text" size="20" placeholder="Currency Name"/>
		<input name="txt-symbol" class="form-control" type="text" size="20" placeholder="Currency Symbol"/>
		<input name="txt-short-form" class="form-control" type="text" size="20" placeholder="Currency Short Form"/>
		<input type="submit" name="sbt-add" value="Add"><br><br>
		<p align=center>PRESS ONLY WHEN NEEDED <input type="submit" name="sbt-upd" value="Update" style="background-color:red; color:white;"></p>
	</form>

</body>
</html>