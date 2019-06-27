function follow_toggle(mfollowee, mfollower)
{
	if(document.getElementById('follow_text_'+mfollowee).innerHTML=='Follow')
	{
		$.post('follow-action.php', {followee:mfollowee, follower:mfollower, action:'follow'}, function(data) {
		$('#follow_text_'+mfollowee).text('Following');  $('#btn-style-'+mfollowee).removeClass('btn btn-danger btn-outline').addClass('btn btn-success btn-outline');$('#btn-icon-'+mfollowee).removeClass('glyphicon-plus-sign');});
	}
	else
	{
		$.post('follow-action.php', {followee:mfollowee, follower:mfollower, action:'unfollow'}, function(data) {
		$('#follow_text_'+mfollowee).text('Follow'); $('#btn-style-'+mfollowee).removeClass('btn btn-success btn-outline').addClass('btn btn-danger btn-outline'); $('#btn-icon-'+mfollowee).addClass('glyphicon-plus-sign');});
	}
}

function follow_single(mfollowee, mfollower)
{
	
}
