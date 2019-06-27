function check_comment()
{
	var commentContent = document.getElementsByName("txt-comment-name")[0].value;
	
	commentContent = commentContent.trim();
	if(commentContent=="")
	{
		document.getElementById("errorMsg").innerHTML = '<div class="alert alert-danger">Empty Fields</div>';
		return false;
	}
		
	
}