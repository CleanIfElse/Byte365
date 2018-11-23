<?php
	session_start();
	include('../../scripts/include.php');
	checkLogin();
	$user = userInfo();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php print $settings["sitename"]; ?> - Chat</title>
		<link rel="stylesheet" href="../../css/main.css">
		<link rel="stylesheet" href="../../css/chat.css">
		<link rel="stylesheet" href="../../css/mobile.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-1.9.0.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>
	</head>
	<body>
		<div id="container" style="height: 100%; position: relative;">
			<?php print $sideNav; ?>
			<div class="dashboard">
				<a href="#!" data-target="modal3" class="upload-btn"><i class="fa fa-comments" aria-hidden="true"></i> &nbsp;New Chat</a><br /><br />
				<iframe id="download" style="display:none;"></iframe>
				<table width="100%" cellspacing="0" class="highlight" cellpadding="0">
					<tr>
						<td class="nav" colspan="5">My Chats</td>
					</tr>
					<?php
						include('../../scripts/chat.php');
						$chat = new ChatClient();
						print $chat->GetChatList();
					?>
				</table>
			</div>
		</div>

		<script src="../../js/main.js"></script>

		<script>
			function openChat(chatID)
			{
				alert(chatID);
			}
		</script>
</html>
