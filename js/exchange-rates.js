function verify_entry()
{
		var toCurrency = document.getElementsByName("sel-to-currency")[0].value;
		var fromCurrency = document.getElementsByName("sel-from-currency")[0].value;
		var itemValue = document.getElementsByName("txt-price")[0].value;
		
		if(toCurrency==0 || fromCurrency==0)
		{
			document.getElementById("errorMsg").innerHTML = '<div class="alert alert-danger">Select Currencies</div>';
			return false;
		}
		
		if(itemValue==0)
		{
			document.getElementById("errorMsg").innerHTML = '<div class="alert alert-danger">Enter Value</div>';
			return false;
		}
}