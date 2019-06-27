<?php

	include '../connect.php';
	include 'main.html';
	
	echo $emailBody;
	
	if(isset($_POST['sbt-upd']))
	{
		$queryGetEmails = mysqli_query($db, "SELECT * FROM mailinglist");
		while($r=mysqli_fetch_array($queryGetEmails))
		{
			//Sending the mail, creating headers for html format
			$headers = "From: losavenuedesk@gmail.com\r\n";
			$headers .= "Reply-To: armansky94@gmail.com\r\n";
			$headers .= 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			
			if (mail($r['email_address'], "Los Avenue", $emailBody, $headers))
				echo 'Email sent to: '.$r['email_address'].'<br>';
			else
				echo '<span style="color:red;">Email Not sent to: '.$r['email_address'].'</span><br>';
		}
	}
	
	if(isset($_POST['sbt-add']))
	{
		if($_POST['txt-email'] != "")
		{
			$emailAddress = $_POST['txt-email'];
			if($queryAddNewCurrency = mysqli_query($db, "INSERT INTO mailinglist (serial_no, email_address) VALUES (0, '$emailAddress')"))
				echo 'New Email Address Added';
			else
				echo mysqli_error($db);
		}
	}

?>

<form action="#" method="post" name="frm-add-purchase" target="_self">
		<p align=center>
			<input name="txt-email" class="form-control" type="email" size="20" placeholder="Email Address"/>
			<input type="submit" name="sbt-add" value="Add"><br><br>
		</p>
		<p align=center><input type="submit" name="sbt-upd" value="Send Emails" style="background-color:red; color:white;"></p>
</form>