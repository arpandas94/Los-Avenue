function like_add(entry_id)
{
	if(document.getElementById('like_text_'+entry_id).innerHTML=='Like')
	{
		$.post('like.php', {entry_id:entry_id, action:'like'}, function(data) {
		$('#likeCount_'+entry_id).text(data); $('#like_text_'+entry_id).text('Liked');  $('#btn-style-'+entry_id).removeClass('btn-default').addClass('btn-danger');});
	}
	else
	{
		$.post('like.php', {entry_id:entry_id, action:'unlike'}, function(data) {
		$('#likeCount_'+entry_id).text(data); $('#like_text_'+entry_id).text('Like'); $('#btn-style-'+entry_id).removeClass('btn-danger').addClass('btn-default');});
	}
}

$("[data-toggle='tooltip']").tooltip();