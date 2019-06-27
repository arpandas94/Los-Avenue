function check_inputs()
{
	var email = document.getElementsByName("txt-email")[0].value;
	var password = document.getElementsByName("txt-password")[0].value;
	
	if(email=="" || password=="")
	{
		document.getElementById("errorMsg").innerHTML = '<div class="alert alert-danger">Empty Fields</div>';
		return false;
	}
}