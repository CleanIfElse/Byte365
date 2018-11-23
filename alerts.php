<?php
	session_start();
	include('scripts/include.php');
	include('scripts/alerts.php');

	$h = new AlertHandler;
	print $h->GetAlert();
?>
