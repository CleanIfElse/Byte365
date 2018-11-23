<?php
	session_start();
	include('../../scripts/include.php');
	include('../../scripts/encryption.php');
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
		<script src="../../js/dropzone.js"></script>
		<script src="../../js/notify.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>
		<link rel="stylesheet" href="../../css/main.css">
		<link rel="stylesheet" href="../../css/mobile.css">
		<link rel="stylesheet" href="../../css/upload.css">
		<script src="../../js/notify.js"></script>
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
							<form action="../../upload.php" class="dropzone dz-clickable">
								<input type="hidden" value="<?php print Encryption::generateRandomStringChars(); ?>" name="<?php print ini_get('session.upload_progress.name'); ?>">
								<div class="dz-default dz-message">
									<span>Drop Files Here (Up to 50 files)</span>
								</div>
							</form>
						<?php
					}
				?>

				<h2 class="upload-info">All uploads are <i>encrypted</i></h2>
				<div class="upload-text-container">
					<span class="upload-text">All files are securely stored using our end-to-end encryption modules. Files are non-accessable without a special encryption and share key, which only you know.</span>
				</div>
			</div>
		</div>
		<script src="../../js/main.js"></script>

		<script>
			setInterval(function() {
			  GetAlerts();
		  }, 2000);
		</script>
	</body>
</html>
