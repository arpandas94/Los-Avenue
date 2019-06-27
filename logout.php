<?php

	include 'connect.php';
	unset($_SESSION["user_id"]);
	setcookie("ckEmail", '', time()-(86400 * 30));
	setcookie("ckPass", '', time()-(86400 * 30));
	header("Location: /");
?>