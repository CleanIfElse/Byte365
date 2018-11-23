<?php
	include('../scripts/include.php');

	if (isset($_POST['register']))
	{
		include('../scripts/register.php');

		$post_data = http_build_query(
		    array(
		        'secret' => '6LfzxCQUAAAAAK_4g6pjGNe9KwBVNB_N-H1XB0Lv',
		        'response' => $_POST['g-recaptcha-response'],
		        'remoteip' => $_SERVER["HTTP_CF_CONNECTING_IP"]
		    )
		);

		$opts = array('http' =>
		    array(
		        'method'  => 'POST',
		        'header'  => 'Content-type: application/x-www-form-urlencoded',
		        'content' => $post_data
		    )
		);
		$context  = stream_context_create($opts);
		$response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
		$result = json_decode($response);
		if (!$result->success)
			print '<script>alert("Captcha incorrect. Please try again!");</script>';
		else
		{
			$res = Register::DoRegister($_POST['first'], $_POST['last'], $_POST['email'], $_POST['password'], $_POST['repassword']);
			if ($res != '' && $res != 'success')
				print '<script>alert("'.$res.'");</script>';
			else if ($res == 'success')
				header('Location: ../login');
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php print $settings["sitename"]; ?> - Registration</title>
		<link rel="stylesheet" href="../css/main.css">
		<link rel="stylesheet" href="../css/mobile.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-1.9.0.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>
		<script src="../js/main.js"></script>
		<script src='https://www.google.com/recaptcha/api.js'></script>
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
				<div class="form-nav">Registration</div>
				<div class="content">
					<?php
						$q = $db->prepare("SELECT * FROM `settings`");
						$q->execute();
						$q = $q->fetch();

						if ($q['registrations'] == '1')
							print 'Registrations are currently disabled. Please check again later!';
						else {
							?>
							<form action="" method="POST">
								<div class="two-textbox">
									<input type="text" name="first" id="first" maxlength="50" placeholder="First Name" />
									<input type="text" name="last" id="last" maxlength="50" placeholder="Last Name" style="float: right; !important;"/>
									<div style="clear: both;"></div>
								</div>
								<input type="text" name="email" id="email" placeholder="Your Email" />
								<input type="password" name="password" id="pass" placeholder="Password" />
								<input type="password" name="repassword" id="repass" placeholder="Re-Enter Password" />
								<div><div class="g-recaptcha" data-sitekey="6LfzxCQUAAAAAMNJOk0_3314TxNOGuGYfssKrTuN"></div></div>
								<div class="tos">By registering, you agree to Byte365's <a href="https://byte365.net/terms.html">Terms of Service</a>.</div><br />
								<input type="submit" name="register" class="reg-btn" value="Create Account" style="width: 160px !important;" />
								<div style="clear: both;"></div>
							</form>
							<?php
						}
					?>
				</div>
			</div>
		</div>
	</body>
</html>
