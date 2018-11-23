<?php
	session_start();
	include('../scripts/include.php');
	checkLogin();
	$user = userInfo();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php print $settings["sitename"]; ?> - Dashboard</title>
		<link rel="stylesheet" href="../css/main.css">
		<link rel="stylesheet" href="../css/mobile.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-1.9.0.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>
		<script src="../../js/notify.js"></script>
		<script>
			$(document).ready(function() {
			    $('#modal1').modal();
			    $('#modal2').modal();
			    $('#modal3').modal();
			    $('#modal4').modal();
			    $('#modal1').modal('open');
			});
		</script>
	</head>
	<body>
		<div id="container" style="height: 100%; position: relative;">
			<?php print $sideNav; ?>
			<div class="dashboard">
				<a href="upload/" class="upload-btn"><i class="fa fa-cloud-upload" aria-hidden="true"></i> &nbsp;Upload</a>
				<a href="#!" data-target="modal3" class="upload-btn"><i class="fa fa-folder-open" aria-hidden="true"></i> &nbsp;Add Folder</a><br /><br />
				<iframe id="download" style="display:none;"></iframe>
				<table width="100%" cellspacing="0" class="highlight" cellpadding="0">
					<tr>
						<td class="nav" colspan="5">My Files</td>
					</tr>
					<tr class="mobilehide">
						<td class="file-header-first">File Name</td>
						<td class="file-header mobilehide" style="padding-left: 14px;">File Size</td>
						<td class="file-header mobilehide" style="padding-left: 12px;">File Type</td>
						<td class="file-header mobilehide" style="padding-left: 12px;">Upload Date</td>
						<td class="file-header-last"></td>
					</tr>
					<tbody id="fileBrowser">
						<!-- File Browser -->
					</tbody>
				</table>
				<?php
					if ($user['AcceptedTerms'] == 'no')
					{
						print '<div id="modal1" class="modal">
		    <div class="modal-content">
			<h4>Welcome.</h4>
			<p>By using Byte365, you agree that all files you upload are either owned by you, or you have permission by the file\'s owner to store and or share the file. By violating our terms, your account may be permanently suspended.</p><br /><br />
			<p>Click <a href="https://byte365.net/terms.html">here</a> to re-read our Terms and Conditions, Privacy Policy, and Acceptable Use Policy.</p>
		    </div>
		    <div class="modal-footer">
			<a href="#!" onclick="agree()" class="modal-action modal-close waves-effect waves-green btn-flat">Agree</a>
		    </div>
		  </div>';
					}
				?>
				<div id="modal2" class="modal">
					<div class="modal-content">
						<div style="width: 100%; box-sizing: border-box; text-align: center; padding: 6px; background: #efefef; border-radius: 3px; -moz-border-radius: 3px; color: #9c9c9c; border: 1px solid #e0e0e0;" id="share-link">{share-link}</div>
					</div>
					<div class="modal-footer">
						<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">OK</a>
					</div>
				</div>
			</div>
		</div>

		<div id="modal3" class="modal">
			<div class="modal-content">
			<h4>New Folder</h4>
			<input type="text" id="folder-name" name="folder-name" placeholder="Folder Name" />
			</div>
			<div class="modal-footer">
			<a href="#!" onclick="addFolder()" class="modal-action modal-close waves-effect waves-green btn-flat">Add Folder</a>
			</div>
		</div>

		<div id="modal4" class="modal">
			<div class="modal-content">
			<h4>Move File</h4>
			<p>Don't forget to include the ending forward slash (/).</p>
			<input type="text" id="f-folder-name" name="directory-name" placeholder="Directory" />
			<input type="hidden" id="f-share-key" name="file-share">
			</div>
			<div class="modal-footer">
			<a href="#!" onclick="finallyMoveFile()" class="modal-action modal-close waves-effect waves-green btn-flat">Move File</a>
			</div>
		</div>

		<script src="../js/main.js"></script>
		<script>
		fileBrowser("/");

		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-105864798-1', 'auto');
		ga('send', 'pageview');

			function agree()
			{
				$.ajax({
				    	type:"GET",
					dataType: "json",
				    	url: "https://byte365.net/api/?agree=true",
				    	success: function(data) {

				        },
				    	error: function(jqXHR, textStatus, errorThrown) {

				        },
				});
			}

			function currentDir()
			{
				$.ajax({
					type:"GET",
					dataType: "text",
					url: "https://byte365.net/api/?currentDir",
					success: function(data) {
						return data;
					  },
					error: function(jqXHR, textStatus, errorThrown) {

					  },
				});
			}

			function deleteFolder(folderID)
			{
				if (confirm('This will delete the folder and all it\'s contents. Are you sure?'))
				{
					$.ajax({
						type:"GET",
						dataType: "json",
						url: "https://byte365.net/api/?deleteFolder=" + folderID,
						success: function(data) {
							if (data.status == "success")
							{
								fileBrowser('/');
							}
							else
							{

							}
						  },
						error: function(jqXHR, textStatus, errorThrown) {

						  },
					});

					location.reload();
				}
				else
				{

				}
			}

			function moveFile(shareKey)
			{
				$('#f-share-key').val(shareKey);
				$('#modal4').modal('open');
			}

			function finallyMoveFile()
			{
				$.ajax({
					type:"GET",
					dataType: "json",
					url: "https://byte365.net/api/?moveFile=" + $("#f-share-key").val() + "&new=" + $("#f-folder-name").val(),
					success: function(data) {
						if (data["status"] == "error")
						{
							alert(data["message"]);
						}
						else
						{
							fileBrowser(data["message"]);
						}
					  },
					error: function(jqXHR, textStatus, errorThrown) {

					  },
				});
			}

			function addFolder()
			{
				$.ajax({
				    	type:"GET",
					dataType: "json",
				    	url: "https://byte365.net/api/?addFolder=" + $("#folder-name").val(),
				    	success: function(data) {
						if (data["status"] == "error")
						{
							alert(data["message"]);
						}
						else
						{
							fileBrowser(data["message"]);
						}
				        },
				    	error: function(jqXHR, textStatus, errorThrown) {

				        },
				});
			}
		</script>
	</body>
</html>
