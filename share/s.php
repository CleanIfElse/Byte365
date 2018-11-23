
<?php
	session_start();
	include('../scripts/include.php');
	checkLogin();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php print $settings["sitename"]; ?> - Download</title>
		<link rel="stylesheet" href="../css/main.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-1.9.0.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>
	</head>
	<body onload="downloadFile('<?php print $_GET['k'] . '\',\'' . $_GET['e']; ?>')">
		<div id="nav">
			<div class="btn-container">
				<a href="#" class="btn">My Account</a>
			</div>
		</div>
		<div id="container" style="height: 100%; position: relative;">
			<div class="sidebar">
				<?php print $sidenav; ?>
			</div>
			<div class="dashboard">
				<iframe id="download" style="display:none;"></iframe>
				<div class="downloading">
					Your file is downloading..
				</div>
			</div>
		</div>
		<script src="../js/main.js"></script>
	</body>
</html>
