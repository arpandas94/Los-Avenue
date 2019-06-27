$(function () {
	$("#chkhide").change(function()
	{
		var st=this.checked;
		if(st)
		{
			$("#txt-price").prop("disabled",true);
			$("#txt-price").val("0");
			$("#sel-currency").prop("disabled",true);
			$("#sel-currency").val("1");
		}
		else
		{
			$("#txt-price").prop("disabled",false);
			$("#txt-price").val("");
			$("#sel-currency").prop("disabled",false);
			$("#sel-currency").val("0");
		}
	});
});


function verify_entry()
{
	var brandName = document.getElementsByName("txt-brand-name")[0].value;
	var itemName = document.getElementsByName("txt-item-name")[0].value;
	var tags = document.getElementsByName("txt-tags")[0].value;
	var currency = document.getElementsByName("sel-currency").value;
	var price = document.getElementsByName("txt-price")[0].value;
	var pic = document.getElementsByName("fileToUpload")[0].value;
	
	if(brandName=="" || itemName=="" || tags=="" || currency=="0" || price=="")
	{
		document.getElementById("errorMsg").innerHTML = '<div class="alert alert-danger">Forgot to fill everything?</div>';
		window.scrollTo(0, 400);
		return false;
	}
	
	if(pic=="")
	{
		document.getElementById("errorMsg").innerHTML = '<div class="alert alert-danger">What is a upload without a picture?</div>';
		window.scrollTo(0, 400);
		return false;
	}
}