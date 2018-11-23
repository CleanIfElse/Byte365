<?php
	session_start();
	include('../scripts/include.php');
	checkLogin();
	$user = userInfo();

	if (isset($_POST['link']))
	{
		$q = $db->prepare("SELECT * FROM `payments` WHERE `PaymentEmail` = :email AND `PaymentUser` = '0' LIMIT 1");
		$q->execute([
			":email" => $_POST['email']
		]);

		if ($q->rowCount() > 0)
		{
			$q = $q->fetch();
			$q2 = $db->prepare("UPDATE `payments` SET `PaymentUser` = :id WHERE `PaymentEmail` = :email AND `PaymentUser` = '0' LIMIT 1");
			$q2->execute([
				":email" => $_POST['email'],
				":id" => $_SESSION['user']['id']
			]);

			if ($q['PaymentFor'] == 'Byte365 Premium')
				$for = 'premium';
			else if ($q['PaymentFor'] == 'Byte365 Business')
				$for = 'business';

			$q3 = $db->prepare("UPDATE `users` SET `AccountType` = :type WHERE `UserID` = :id LIMIT 1");
			$q3->execute([
				":type" => $for,
				":id" => $_SESSION['user']['id']
			]);

			print '<script>alert("Your subscription has been linked to this account.");</script>';
		}
		else
		{
			print '<script>alert("No payments matching this email were found.");</script>';
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php print $settings["sitename"]; ?> - Payments</title>
		<link rel="stylesheet" href="../css/main.css">
		<link rel="stylesheet" href="../css/mobile.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-1.9.0.min.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>
		<script src="../js/notify.js"></script>
	</head>
	<body>
		<div id="container" style="height: 100%;">
			<?php print $sideNav; ?>
			<div class="dashboard">

				<div class="admin-container">
					<div class="admin-nav">Coupons</div>
					<div class="admin-content">
						<!-- <input type="text" class="mod-text" id="coupon" placeholder="Coupon Code" />
						<div style="clear: both;"></div>
						<a href="#!" onclick="checkCoupon()" class="btn" style="float: right;">Apply</a>
						<div style="clear: both;"></div> -->
						<span>This feature is currently being re-worked. Please check back later!</span>
					</div>
				</div>
				<ul class="collapsible" data-collapsible="accordion">
					<li>
						<div class="collapsible-header"><i class="material-icons">filter_drama</i>Byte365 Premium</div>
						<div class="collapsible-body">
							<span>Byte365 Premium increases your maximum storage space to <b>10 GB</b> of fully encrypted storage and the ability to use ByteQR.</span><br /><br />
							<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" style="margin: 0 auto; text-align: center;">
								<input type="hidden" name="cmd" value="_s-xclick">
								<input type="hidden" name="discount_rate" value="0" id="discountamount">
								<input type="hidden" name="hosted_button_id" value="2H4BU3TD5RKB4">
								<input type="submit" class="checkout" value="Purchase using PayPal">
							</form>
						</div>
					</li>
					<li>
						<div class="collapsible-header"><i class="material-icons">whatshot</i>Byte365 Business</div>
						<div class="collapsible-body">
							<span>Byte365 Business increases your storage space to <b>50 GB</b> of fully encrypted storage and the ability to collaborate and share with others in your organization.</span><br /><br />
							<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" style="margin: 0 auto; text-align: center;">
								<input type="hidden" name="cmd" value="_s-xclick">
								<input type="hidden" name="discount_rate" value="0" id="discountamount">
								<input type="hidden" name="hosted_button_id" value="E8ENXBRHZLH9C">
								<input type="submit" class="checkout" value="Purchase using PayPal">
							</form>
						</div>
					</li>
					<li>
						<div class="collapsible-header"><i class="material-icons">whatshot</i>Byte365 Student</div>
						<div class="collapsible-body"><span>Coming soon.</span></div>
					</li>
				</ul>

				<div class="admin-container">
					<div class="admin-nav">Link Your Payment</div>
					<div class="admin-content">
						<span>Sent your payment for your subscription? Enter the email address you paid with to have it linked to your account.</span>
						<form action="" method="POST">
							<input type="text" name="email" placeholder="Payment Email" />
							<div style="clear: both;"></div>
							<input type="submit" class="checkout" name="link" value="Link" style="float: right;" />
							<div style="clear: both;"></div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<script src="../js/main.js"></script>
		<script>
			function checkCoupon(code)
			{
				$.ajax({
					type:"GET",
					dataType: "json",
					url: "https://byte365.net/api/?checkCoupon=" + $("#coupon").val(),
					success: function(data) {
						if (data.status == "success")
						{
							alert(data.message + "% discount applied.");
							$("#discountamount").val(data.message);
						}
						else
						{
							alert("The coupon you entered is not valid.");
						}
					  },
					error: function(jqXHR, textStatus, errorThrown) {

					}
				});
			}
		</script>
	</body>
</html>
