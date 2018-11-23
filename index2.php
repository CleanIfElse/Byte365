<?php
	session_start();
	$user = '';
	include('scripts/include.php');

	if (isset($_POST['login']))
	{
		include('scripts/login.php');
		$res = Login::DoLogin($_POST['email'], $_POST['password']);

		if ($res != '')
			print '<script>alert("'.$res.'");</script>';
		else
		{
			header('Location: dashboard/');
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php print $settings["sitename"]; ?> - Encrypted Cloud Storage</title>
		<meta name="description" content="We make security simple with our easy-to-use encrypted cloud storage. Sign up today for 500 MB free cloud storage on our platform!">
		<meta name="keywords" content="byte365, encrypted, cloud, storage, security, encryption, encrypt, safe, file, cloud, server, free, cheap">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/index.css">
		<link rel="stylesheet" href="css/morphext.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
		<script src="js/morphext.min.js"></script>
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	</head>
	<body>
		<div class="nav">
			<div class="left">
				<img src="images/logo2.png" />
			</div>
			<div class="right">
				<a href="#!">Home</a>
				<a href="#!">Pricing</a>
			</div>
		</div>
		<div id="container">
			<div id="bg">
				<div class="header-text mobile-hide">
					<h1>Feel secure with</h1>
					<h1>Byte365</h1>
					<span class="header-desc">A secure file hosting solution for everyone.</span>
				</div>

				<div class="header-text mobile-show">
					<h1>Feel secure with Byte365</h1>
				</div>

				<div class="quick-login">
					<h3>Login</h3>
					<form action="" method="POST">
						<input type="text" name="email" placeholder="Your Email" />
						<input type="password" name="password" placeholder="Your Password" style="margin-bottom: 0px !important;"/>
						<div class="space"></div>
						<a href="#!" class="reg-link">Create a free account</a>
						<div class="space"></div>
						<input class="btn" type="submit" name="login" value="Login" />
					</form>
				</div>
			</div>
			<div class="block-two">
				<h3>Developed with your security in mind</h3>

				<table class="features mobile-hide">
					<tr>
						<td>
							<i class="material-icons icon" style="font-size: 65px; color: #6b6b6b;">fingerprint</i> <br />
							<p>End to end <br />Encryption</p>
						</td>
						<td>
							<i class="material-icons" style="font-size: 65px; color: #6b6b6b;">visibility_off</i>
							<p>Complete<br />Privacy</p>
						</td>
						<td>
							<i class="material-icons" style="font-size: 65px; color: #6b6b6b;">face</i>
							<p>Dedicated</br>Support</p>
						</tr>
					</tr>
				</table>

				<table class="features mobile-show-table features-mobile">
					<tr>
						<td>
							<i class="material-icons icon" style="font-size: 65px; color: #6b6b6b;">fingerprint</i> <br />
							<p>End to end <br />Encryption</p>
						</td>
						<td>
							<i class="material-icons" style="font-size: 65px; color: #6b6b6b;">visibility_off</i>
							<p>Complete<br />Privacy</p>
						</td>
						<td>
							<i class="material-icons" style="font-size: 65px; color: #6b6b6b;">face</i>
							<p>Dedicated</br>Support</p>
						</tr>
					</tr>
				</table>

				<table class="pricing mobile-hide-table">
					<tr>
						<td>
							<div class="price-container">
								<div class="price-title gray">Free</div>
								<div class="price-content">
									<span class="price">$0</span><span class="month">/mo</span><div class="space"></div>
									<span class="price-desc">500 MB encrypted storage with easy-to-use organization and sharing tools.</span>
									<a href="#!"><div class="reg-btn">Try now</div></a>
								</div>
							</div>
						</td>
						<td>
							<div class="price-container">
								<div class="price-title blue">Advanced</div>
								<div class="price-content">
									<span class="price">$10</span><span class="month">/mo</span><div class="space"></div>
									<span class="price-desc">10 GB encrypted storage with easy-to-use organization, sharing and hosting tools.</span>
									<a href="#!"><div class="reg-btn">Try now</div></a>
								</div>
							</div>
						</td>
						<td>
							<div class="price-container">
								<div class="price-title gray">Business</div>
								<div class="price-content">
									<span class="price">$50</span><span class="month">/mo</span><div class="space"></div>
									<span class="price-desc">100 GB encrypted storage with easy-to-use organization, sharing and hosting tools.</span>
									<a href="#!"><div class="reg-btn">Try now</div></a>
								</div>
							</div>
						</td>
					</tr>
				</table>

				<div class="pricing-mobile mobile-show">
					<div class="price-container">
						<div class="price-title gray">Free</div>
						<div class="price-content">
							<span class="price">$0</span><span class="month">/mo</span><div class="space"></div>
							<span class="price-desc">500 MB encrypted storage with easy-to-use organization and sharing tools.</span>
							<a href="#!"><div class="reg-btn">Try now</div></a>
						</div>
					</div> <br />

					<div class="price-container">
						<div class="price-title blue">Advanced</div>
						<div class="price-content">
							<span class="price">$10</span><span class="month">/mo</span><div class="space"></div>
							<span class="price-desc">10 GB encrypted storage with easy-to-use organization, sharing and hosting tools.</span>
							<a href="#!"><div class="reg-btn">Try now</div></a>
						</div>
					</div> <br />

					<div class="price-container">
						<div class="price-title gray">Business</div>
						<div class="price-content">
							<span class="price">$50</span><span class="month">/mo</span><div class="space"></div>
							<span class="price-desc">100 GB encrypted storage with easy-to-use organization, sharing and hosting tools.</span>
							<a href="#!"><div class="reg-btn">Try now</div></a>
						</div>
					</div>
				</div>

			<div class="block-three">
				<h3>What our users are saying</h3>

				<div class="review-box">
					<i class="material-icons" style="width: 25px; color: #1d80f9;">star_rate</i>
					<i class="material-icons" style="width: 25px; color: #1d80f9;">star_rate</i>
					<i class="material-icons" style="width: 25px; color: #1d80f9;">star_rate</i>
					<i class="material-icons" style="width: 25px; color: #1d80f9;">star_rate</i>
					<i class="material-icons" style="width: 25px; color: #1d80f9;">star_rate</i> <br /><div class="space"></div>
					<span class="review-text"><span id="js-rotating">Best file sharing site I've used in ages. Professionally designed and definitely of professional caliber.|Awesome service, very professionally designed, owned, and operated. Trustworthy service with secure servers and little-to-no down time! Easily would recommend to others as an alternative to Dropbox or similar websites!|Great site! Secure and professional. Neat design.</span></span>
					<div class="review-name" id="js-rotating2">
						Michael Coen|CJ Swift|Abood Arthur
					</div>
				</div>
			</div>
		</div>

		<script>
			$("#js-rotating").Morphext({
			    // The [in] animation type. Refer to Animate.css for a list of available animations.
			    animation: "fadeIn",
			    // An array of phrases to rotate are created based on this separator. Change it if you wish to separate the phrases differently (e.g. So Simple | Very Doge | Much Wow | Such Cool).
			    separator: "|",
			    // The delay between the changing of each phrase in milliseconds.
			    speed: 8000,
			    complete: function () {
				  // Called after the entrance animation is executed.
			    }
			});

			$("#js-rotating2").Morphext({
			    // The [in] animation type. Refer to Animate.css for a list of available animations.
			    animation: "fadeIn",
			    // An array of phrases to rotate are created based on this separator. Change it if you wish to separate the phrases differently (e.g. So Simple | Very Doge | Much Wow | Such Cool).
			    separator: "|",
			    // The delay between the changing of each phrase in milliseconds.
			    speed: 8000,
			    complete: function () {
				  // Called after the entrance animation is executed.
			    }
			});
		</script>
	</body>
</html>
