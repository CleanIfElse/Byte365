<?php
	session_start();
	include('../scripts/include.php');

	if (isset($_SESSION['user']['id']))
	{
		header('Location: ../dashboard/');
	}

	if (isset($_POST['login']))
	{
		include('../scripts/login.php');
		$res = Login::DoLogin($_POST['email'], $_POST['password']);

		if ($res != '')
			print '<script>alert("'.$res.'");</script>';
		else
		{
			header('Location: ../dashboard/');
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php print $settings["sitename"]; ?> - Login</title>
		<meta name="description" content="Login to Byte365. Saving, sharing, and collaborating has never been so easy.">
		<meta name="keywords" content="byte365, encrypted, cloud, storage, security, encryption, encrypt, safe, file, cloud, server, free, cheap">
		<link rel="stylesheet" href="../css/main.css">
		<link rel="stylesheet" href="../css/mobile.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-1.9.0.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>
		<script src="../js/main.js"></script>
	</head>
	<body style="background: #f6f6f6;">
		<div class="nav-buttons">
			<a href="https://byte365.net/login/" class="blue">Login</a>
			<a href="https://byte365.net/register/">Create Account</a>
		</div>
		<div id="container">
			<div style="text-align: center;">
				<img src="../images/logo.png" class="logo"/>
			</div>
			<div class="form-container">
				<div class="form-nav">Login</div>
				<div class="content">
					<form action="" method="POST">
						<input type="text" name="email" id="email" placeholder="Your Email" />
						<input type="password" name="password" id="pass" placeholder="Password" />
						<div style="padding: 4px;"></div>
						<input type="submit" name="login" class="reg-btn" value="Login" />
						<div style="clear: both;"></div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
