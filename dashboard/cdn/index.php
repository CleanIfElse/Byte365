<?php
	session_start();
	include('../../scripts/include.php');
	include('../../scripts/cdn.php');
	checkLogin();
	$user = userInfo();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php print $settings["sitename"]; ?> - CDN</title>
		<link rel="stylesheet" href="../../css/main.css">
		<link rel="stylesheet" href="../../css/mobile.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-1.9.0.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>
		<script src="../../../js/notify.js"></script>
		<script>
		$(document).ready(function() {
		    $('#modal1').modal();
		    $('#modal2').modal();
	    	});
		</script>
	</head>
	<body>
		<div id="container" style="height: 100%; position: relative;">
			<?php print $sideNav; ?>
			<div class="dashboard">
				<a href="upload/" class="upload-btn"><i class="fa fa-cloud-upload" aria-hidden="true"></i> &nbsp;New File</a> &nbsp;<a href="#!" onclick="showHelp()" class="upload-btn"> &nbsp;API Help</a> <br /><br />
				<table width="100%" cellspacing="0" class="highlight" cellpadding="0">
					<tr>
						<td class="nav" colspan="5">My Files</td>
					</tr>
					<tr class="mobilehide">
						<td class="file first">File Name</td>
						<td class="file" style="padding-left: 14px;">File Size</td>
						<td class="file">URL</td>
						<td class="file last">Options</td>
					</tr>
					<tbody id="fileBrowser">
						<?php print CDN::GetFiles(); ?>
					</tbody>
				</table>
			</div>

			<div id="modal1" class="modal">
				<div class="modal-content">
				<h4>URL</h4>
				<p id="url" style="background: #e2e2e2; color: #848484; padding: 6px; border-radius: 2px; -moz-border-radius: 2px;"></p>
				</div>
				<div class="modal-footer">
				<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">OK</a>
				</div>
			</div>

			<div id="modal2" class="modal">
				<div class="modal-content">
					<h4>ByteCDN API Usage</h4>
					<b>Your API Key:</b>
					<p id="url" style="background: #e2e2e2; color: #848484; padding: 6px; border-radius: 2px; -moz-border-radius: 2px;"><?php print APIKey(); ?></p>
					<br />
					<h5>API Calls</h5>
					<p>You are able to upload files to ByteCDN using Byte365's API. An example call to this API would be as follows: <br />
					<p id="url" style="background: #e2e2e2; color: #848484; padding: 6px; border-radius: 2px; -moz-border-radius: 2px;">https://byte365.net/api/?cdn&key=<?php print APIKey(); ?></p> <br />
					<p>Please make sure that the file you are sending is being sent with the name "file".</p>
					<h5>Help</h5>
					<p>You can either use Ajax to upload files, but please be aware that this does not work on all browsers. If you want 100% reliability with uploads, we recommend using <a href="http://www.ajaxf1.com/tutorial/ajax-file-upload-tutorial.html#">this</a> method.</p>

				</div>
				<div class="modal-footer">
					<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">OK</a>
				</div>
			</div>
		</div>

		<script src="../js/main.js"></script>
		<script>

		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-105864798-1', 'auto');
		ga('send', 'pageview');

			function viewURL(url)
			{
				$("#url").text(url);
				$('#modal1').modal('open');
			}

			function showHelp()
			{
				$('#modal2').modal('open');
			}
		</script>
	</body>
</html>
