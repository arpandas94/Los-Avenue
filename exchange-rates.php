<?php	
	include 'connect.php';
	
	$errMsg = "";
	$reqResult = "";
	$totalSpendingUSD=0;
	$userPanel="";
	$exchangeRateTableContent = "";
	
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
	
	//render currencies
	$query = 'SELECT * FROM currency';
	if($result = mysqli_query($db, $query))
	{
		$currencyInfo = '';
		while($r = mysqli_fetch_array($result))
		{	
			$currencyInfo = $currencyInfo.'<option value="'.$r['currency_id'].'">'.$r['currency_name'].' ('.$r['currency_symbol'].')</option>';
		}
	}
	else
	{
		echo 'Cannot Fetch Currency Information';
	}
	
	//calculator
	if(isset($_POST['sbt-acct']))
	{
		//fetch form elements
		$fromCurrency = $_POST['sel-from-currency'];
		$toCurrency = $_POST['sel-to-currency'];
		$price = $_POST['txt-price'];
		
		//convert value to USD
		$queryToUSD = mysqli_query($db,"SELECT * FROM currency WHERE currency_id=$fromCurrency");
		while($r4=mysqli_fetch_array($queryToUSD))
		{
			$exchangeRateToUSD = $r4['currency_exchange_rate'];
			$fromCurrencyName = $r4['currency_name'];
		}
		$valueInUSD = $price/$exchangeRateToUSD;
		
		//Convert to required currency
		$queryToReq = mysqli_query($db,"SELECT * FROM currency WHERE currency_id=$toCurrency");
		while($r4=mysqli_fetch_array($queryToReq))
		{
			$exchangeRateToReq = $r4['currency_exchange_rate'];
			$toCurrencyName = $r4['currency_name'];
		}
		$reqResult = '<span style="color:green;">'.$price.'</span> '.$fromCurrencyName.' = <span style="color:#C9302C;">'.number_format($valueInUSD*$exchangeRateToReq,2,'.',',').'</span> '.$toCurrencyName;
	}
	
	//Exchange Rate Table
	$queryExchangeRateTable = mysqli_query($db,"SELECT * FROM currency");
	while($r7=mysqli_fetch_array($queryExchangeRateTable))
	{
		$currencyName = $r7['currency_name'];
		$currencySymbol = $r7['currency_symbol'];
		$currencyExchangeRate = $r7['currency_exchange_rate'];
		
		$exchangeRateTableContent = $exchangeRateTableContent.'<p style="font-size: 17px;">'.$currencyName.' (<b>'.$currencySymbol.'</b>)<span style="color:green;" class="pull-right">'.$currencyExchangeRate.'</span></p>';
	}
	
	include 'exchange-rates.htm';
?>