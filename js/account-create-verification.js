function check_inputs()
{
	var firstName = document.getElementsByName("txt-first-name")[0].value;
	var lastName = document.getElementsByName("txt-last-name")[0].value;
	var displayName = document.getElementsByName("txt-display-name")[0].value;
	var gender = document.getElementsByName("sel-gender").value;
	var email = document.getElementsByName("txt-email")[0].value;
	var country = document.getElementsByName("sel-country")[0].value;
	var currency = document.getElementsByName("sel-currency")[0].value;
	var password = document.getElementsByName("txt-password")[0].value;
	var confirmPassword = document.getElementsByName("txt-confirm-password")[0].value;
	var ck_name = /^[A-Za-z0-9 ]{3,20}$/;
	var ck_username = /^[A-Za-z0-9_]{3,20}$/;
	
	if (!ck_name.test(firstName) || !ck_name.test(lastName))
	{
		document.getElementById("errorMsg").innerHTML = '<div class="alert alert-danger">Name not valid</div>';
		window.scrollTo(0, 0);
		return false;
	}
	
	if (!ck_username.test(displayName))
	{
		document.getElementById("errorMsg").innerHTML = '<div class="alert alert-danger">Username not valid</div>';
		window.scrollTo(0, 0);
		return false;
	}
	
	if(firstName=="" || lastName=="" || displayName=="" || gender=="0" || email=="" || country=="0" || currency=="0" || password=="" || confirmPassword=="")
	{
		document.getElementById("errorMsg").innerHTML = '<div class="alert alert-danger">Empty Fields</div>';
		window.scrollTo(0, 0);
		return false;
	}
	else
	{
		if(password != confirmPassword)
		{
			document.getElementById("errorMsg").innerHTML = '<div class="alert alert-danger">Passwords Mismatch</div>';
			window.scrollTo(0, 0);
			return false;
		}
		
		if(password.length < 6)
		{
			document.getElementById("errorMsg").innerHTML = '<div class="alert alert-danger">Password Should be Minimum 6 Characters</div>';
			window.scrollTo(0, 0);
			return false;
		}
	}
	
}