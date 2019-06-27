<?php

    $headers = "From: losavenuedesk@gmail.com\r\n";
	$headers .= "Reply-To: armansky94@gmail.com\r\n";
			
	echo mail('armansky94@gmail.com', "Los Avenue", 'content', $headers);

?>