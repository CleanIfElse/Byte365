<?php
	session_start();
	include('../../scripts/include.php');
	checkLogin();
	$user = userInfo();

	if ($user['AccountType'] != 'admin')
		header('Location: ../');

		include('../../scripts/coupons.php');
		if (isset($_GET['deleteCoupon']))
		{
			Coupons::DeleteCoupon($_GET['deleteCoupon']);
		}

		if (isset($_POST['savecoupon']))
		{
			Coupons::SaveCoupon($_POST['coupon'], $_POST['percent']);
		}

		if (isset($_POST['save']))
		{
			if ($_POST['accounttype'] == 'admin' && $_SESSION['user']['id'] != '1')
				print '<script>alert("You are not allowed to admin other users.");</script>';
			else
			{
				$q = $db->prepare("UPDATE `users` SET `FirstName` = :first, `LastName` = :last, `Email` = :email, `AccountType` = :accounttype WHERE `Email` = :oldemail");
				$q->execute([
					":oldemail" => $_POST['oldemail'],
					":first" => $_POST['first'],
					":last" => $_POST['last'],
					":email" => $_POST['email'],
					":accounttype" => $_POST['accounttype']
				]);

				if (isset($_POST['password']) && $_POST['password'] != '')
				{
					include('../../scripts/bcrypt.php');
					$bcrypt = new Bcrypt(15);
					$password = $bcrypt->hash($_POST['password'] . 'area51');
					$q = $db->prepare("UPDATE `users` SET `Password` = :password WHERE `Email` = :oldemail");
					$q->execute([
						":oldemail" => $_POST['oldemail'],
						":password" => $password,
					]);
				}

				print '<script>alert("User has been updated.");</script>';
			}
		}

		if (isset($_POST['savesettings']))
		{
			if (isset($_POST['maintenance']))
				$m = 1;
			else
				$m = 0;

			if (isset($_POST['logins']))
				$l = 1;
			else
				$l = 0;

			if (isset($_POST['registrations']))
				$r = 1;
			else
				$r = 0;

			if (isset($_POST['uploads']))
				$u = 1;
			else
				$u = 0;

			$q = $db->prepare("UPDATE `settings` SET `maintenance` = :m, `logins` = :l, `registrations` = :r, `uploads` = :u");
			$q->execute([
				":m" => $m,
				":l" => $l,
				":r" => $r,
				":u" => $u
			]);
		}

		$settings = $db->prepare("SELECT * FROM `settings`");
		$settings->execute();

		$settings = $settings->fetch();

		if ($settings['maintenance'] == '1')
			$mc = 'checked';
		else
			$mc = '';

		if ($settings['logins'] == '1')
			$lc = 'checked';
		else
			$lc = '';

		if ($settings['registrations'] == '1')
			$rc = 'checked';
		else
			$rc = '';

		if ($settings['uploads'] == '1')
			$uc = 'checked';
		else
			$uc = '';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Byte365 - Admin Panel</title>
		<link rel="stylesheet" href="../../css/main.css">
		<link rel="stylesheet" href="../../css/mobile.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-1.9.0.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>
		<script src="../../js/notify.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php print $header; ?>
	</head>
	<body>
		<div id="container" style="height: 100%;">
			<?php print $sideNav; ?>
			<div class="dashboard">
				<table width="100%" cellspacing="0" class="highlight" cellpadding="0">
					<tr>
						<td class="nav" colspan="4">Cloud Servers</td>
					</tr>
					<?php
						include('../../scripts/servers.php');
						print Server::FetchServers();
					?>
				</table><br />

				<div style="admin-container">
					<div class="admin-nav">Statistics</div>
					<div class="admin-content">

						<?php
							$q = $db->prepare("SELECT * FROM `users`");
							$q->execute();
							$users = $q->rowCount();

							$q2 = $db->prepare("SELECT * FROM `uploads`");
							$q2->execute();
							$uploads = $q2->rowCount();

							$q3 = $db->prepare("SELECT SUM(PaymentAmount) AS revenue FROM payments");
							$q3->execute();
							$revenue = $q3->fetch()['revenue'];
						?>

						<div class="row" style="margin: 0 auto !important; text-align: center !important;">
							<div class="col s4 green"><h3>Users</h3><?php print $users; ?></div>&nbsp;
							<div class="col s4 purple"><h3>Uploads</h3><?php print $uploads; ?></div>&nbsp;
							<div class="col s4 blue-tab"><h3>Revenue</h3><?php print '$'.$revenue; ?></div>
						</div>
					</div>
				</div> <br />
				<div style="admin-container">
					<div class="admin-nav">Edit User</div>

					<div class="admin-content">
						<form action="" method="POST">
							<?php
								if (isset($_POST['email']) && !isset($_POST['save']))
								{
									$q = $db->prepare("SELECT * FROM `users` WHERE `Email` = :email LIMIT 1");
									$q->execute([
										":email" => $_POST['email']
									]);
									$q = $q->fetch();

									$edit = '<input type="text" name="email" placeholder="Email Address" value="'.$q['Email'].'"/>
									<input type="text" name="first" placeholder="First Name" value="'.$q['FirstName'].'"/>
									<input type="text" name="last" placeholder="Last Name" value="'.$q['LastName'].'"/>
									<input type="hidden" name="oldemail" value="'.$_POST['email'].'">
									<div class="input-field">
										<select name="accounttype">
											<option value="user">Free User</option>
											<option value="premium">Premium User</option>
											<option value="business">Business User</option>
											<option value="banned">Banned</option>
											<option value="admin">Admin</option>
										</select>
									</div>
									<input type="text" name="newpass" placeholder="New Password (Optional)" />
									<input type="submit" class="reg-btn" name="save" value="Save User" />';

									$edit = str_replace($q['AccountType'] . '"', $q['AccountType'] . '" selected', $edit);
									print $edit;
								}
								else
								{
									print '<input type="text" name="email" placeholder="Email Address" />
									<input type="submit" class="reg-btn" name="search" value="Search" />';
								}
							?>
						</form>
							<div style="clear: both;"></div>
					</div>
				</div><br />

				<div style="admin-container">
					<div class="admin-nav">Site Options</div>
					<div class="admin-content">
						<form action="" method="POST">
							<div class="switch">
								<label>
								Off
								<input type="checkbox" name="maintenance" <?php print $mc; ?>>
								<span class="lever"></span>
								On
								</label>
								&nbsp; &nbsp; Maintenance Mode
							</div>
							<br />
							<div class="switch">
								<label>
								Off
								<input type="checkbox" name="logins" <?php print $lc; ?>>
								<span class="lever"></span>
								On
								</label>
								&nbsp; &nbsp; Disable Logins
							</div>
							<br />
							<div class="switch">
								<label>
								Off
								<input type="checkbox" name="registrations" <?php print $rc; ?>>
								<span class="lever"></span>
								On
								</label>
								&nbsp; &nbsp; Disable Registrations
							</div>
							<br />
							<div class="switch">
								<label>
								Off
								<input type="checkbox" name="uploads" <?php print $uc; ?>>
								<span class="lever"></span>
								On
								</label>
								&nbsp; &nbsp; Disable Uploads
							</div>

							<input type="submit" class="reg-btn" name="savesettings" value="Save" />
							<div style="clear: both;"></div>
						</form>
					</div>
				</div>
				<br />
				<div style="admin-container">
					<div class="admin-nav">Coupons</div>
					<div class="admin-content" style="padding: 0px !important;">
						<table>
							<tr>
								<form action="" method="POST">
									<td width="30%">
										<input type="text" style="display: inline;" name="coupon" class="mod-text" placeholder="Coupon Name" />
									</td>
									<td>
										<input type="text" style="display: inline; width: 50px; text-align: center;" name="percent" class="mod-text" placeholder="0" />
										<span style="position: relative; top: 15px; font-size: 17px; left: 6px;">%</span>
										<input type="submit" name="savecoupon" class="btn" style="position: relative; top: 8px; left: 10px;" value="Save Coupon" />
									</td>
								</form>
							</tr>
						</table>

						<?php print Coupons::GetCoupons(); ?>
					</div>
				</div><br />
				<div style="admin-container">
					<div class="admin-nav">Login As</div>
					<div class="admin-content">
						<p>Only use this tool to access accounts when a copyright email is made against them.</p>
						<form action="" method="POST">
							<input type="text" name="loginasid" placeholder="User ID" />
							<input type="submit" name="loginasuser" value="Login">
						</form>
					</div>
				</div>
				<br />
				<div style="admin-container">
					<div class="admin-nav">Recount</div>
					<div class="admin-content">
						<a href="#!" class="btn" onclick="recount()">Recount Server Usage</a>
					</div>
				</div>
			</div>
		</div>
		<script src="../../js/main.js"></script>
		<script>
			setInterval(GetAlerts, 2000);

			<?php print Server::initiatePings(); ?>

			function pingThis(ip, id)
			{
				$.ajax({
				    	type:"GET",
					dataType: "text",
				    	url: "https://byte365.net/dashboard/admin/ping.php?ip=" + ip,
				    	success: function(data) {
						if (data == "1")
						{
							$("#ping-" + id).html('<div style="border-radius: 50%; width: 10px; height: 10px; background: #00c300; display: inline-block;"></div>&nbsp;');
						}
						else
						{
							$("#ping-" + id).html('<div style="border-radius: 50%; width: 10px; height: 10px; background: red; display: inline-block;"></div>&nbsp;');
						}
				        },
				    	error: function(jqXHR, textStatus, errorThrown) {

					}
				});
			}

			$(document).ready(function() {
  				$('select').material_select();
			});

		</script>
	</body>
</html>
