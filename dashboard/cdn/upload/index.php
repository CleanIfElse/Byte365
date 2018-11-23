<?php
	session_start();
	include('../../../scripts/include.php');
	include('../../../scripts/encryption.php');
	checkLogin();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php print $settings["sitename"]; ?> - Upload</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php print $header; ?>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-1.9.0.min.js"></script>
		<script src="../../../js/dropzone.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>
		<link rel="stylesheet" href="../../../css/main.css">
		<link rel="stylesheet" href="../../../css/mobile.css">
		<link rel="stylesheet" href="../../../css/upload.css">
	</head>
	<body>
		<div id="container" style="height: 100%;">
			<?php print $sideNav; ?>
			<div class="dashboard">
				<?php
					$disabled = $db->prepare("SELECT * FROM `settings`");
					$disabled->execute();
					if ($disabled->fetch()['uploads'] == '1')
						print 'Uploads are currently disabled. Check back later!';
					else
					{
						?>
							<form action="../../../upload.php?cdn" class="dropzone dz-clickable">
								<input type="hidden" value="<?php print Encryption::generateRandomStringChars(); ?>" name="<?php print ini_get('session.upload_progress.name'); ?>">
								<div class="dz-default dz-message">
									<span>Drop Files Here</span>
								</div>
							</form>
						<?php
					}
				?>
			</div>
		</div>
		<script src="../../../js/notify.js"></script>
		<script>
			setInterval(function() {
			  GetAlerts();
		  }, 2000);
		</script>
		<script src="../../../js/main.js"></script>
	</body>
</html>
