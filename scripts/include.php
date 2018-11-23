<?php
	include('db.php');

	$settings = array(
		"sitename" => "Byte365"
	);

	$header = '
	<link rel="apple-touch-icon" sizes="57x57" href="https://byte365.net/images/icons/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="https://byte365.net/images/icons/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="https://byte365.net/images/icons/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="https://byte365.net/images/icons/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="https://byte365.net/images/icons/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="https://byte365.net/images/icons/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="https://byte365.net/images/icons/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="https://byte365.net/images/icons/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="https://byte365.net/images/icons/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="https://byte365.net/images/icons/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="https://byte365.net/images/icons/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="https://byte365.net/images/icons/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="https://byte365.net/images/icons/favicon-16x16.png">
	<link rel="manifest" href="https://byte365.net/images/icons/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="https://byte365.net/images/icons/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">';

	// Return the user's current API Key.
	function APIKey()
	{
		global $db;

		$q = $db->prepare("SELECT * FROM `users` WHERE `UserID` = :id LIMIT 1");
		$q->execute([
			":id" => $_SESSION['user']['id']
		]);

		return $q->fetch()['salt'];
	}

	// Check the session and see if the IPs match. If not, log them out.
	function checkLogin()
	{
		if (isset($_SESSION['user']['id']))
		{
			checkOffline();
			if ($_SESSION['user']['ip'] != $_SERVER["HTTP_CF_CONNECTING_IP"] || $_SESSION['user']['country'] != $_SERVER["HTTP_CF_IPCOUNTRY"])
			{
				session_destroy();
				header('Location: https://byte365.net/login/');
			}
		}
		else
			header('Location: https://byte365.net/login/');
	}

	// Change the user's profile picture. TODO: Finish this function.
	function changeProfilePicture($picture)
	{
		$target_dir = '../uploads/pictures/';
		$target_file = $target_dir . basename($picture["name"]);
		$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

		$check = getimagesize($picture["tmp_name"]);

		if ($check !== false)
			print 'Yo this isn\'t a picture';
		else
			return;

		if ($picture["size"] > 5000000)
		    return 'Yo it\'s too big';

		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif")
			return 'yo wrong extensions nigga lol';

		move_uploaded_file($picture["tmp_name"], $target_dir . $_SESSION['user']['id'] . '.' . $fileImageType);

		return 'yo lol';
	}

	// Check if maintenance mode is enabled.
	function checkOffline()
	{
		global $db;
		$user = userInfo();

		$q = $db->prepare("SELECT * FROM `settings`");
		$q->execute();
		$q = $q->fetch();

		if ($q['maintenance'] == '1' && $user['AccountType'] != 'admin')
			header('Location: https://byte365.net/maintenance/');
	}

	// Retrieve the user's information from the database.
	function userInfo()
	{
		global $db;

		$q = $db->prepare("SELECT * FROM `users` WHERE `UserID` = :id LIMIT 1");
		$q->execute([
			":id" => $_SESSION['user']['id']
		]);

		return $q->fetch();
	}

	// Get the user's total uploaded file count.
	function totalFiles()
	{
		global $db;

		$q = $db->prepare("SELECT * FROM `uploads` WHERE `Uploader` = :id");
		$q->execute([ ":id" => $_SESSION['user']['id']]);

		return $q->rowCount();
	}

	// Get the user's used storage on the server.
	function accountUsage($raw = false)
	{
		global $db;

		$usage = $db->prepare("SELECT SUM(`FileSize`) as total FROM `uploads` WHERE `Uploader` = :uid");
		$usage->execute([ ":uid" => $_SESSION['user']['id'] ]);
		$usage = $usage->fetch();

		if ($raw == true)
			return $usage['total'];
		else
			return formatBytes($usage['total']);
	}

	// Get the user's used storage using their User ID. (For the API)
	function accountUsageByID($id)
	{
		global $db;

		$usage = $db->prepare("SELECT SUM(`FileSize`) as total FROM `uploads` WHERE `Uploader` = :uid");
		$usage->execute([ ":uid" => $id ]);
		$usage = $usage->fetch();

		return $usage['total'];
	}

	// Get the user's storage limit using their User ID. (For the API)
	function accountLimitsByID($id)
	{
		global $db;

		$q = $db->prepare("SELECT * FROM `users` WHERE `UserID` = :id LIMIT 1");
		$q->execute([
			":id" => $id
		]);

		$type = $q->fetch()['AccountType'];

		if ($type == 'user')
			return 524288000;
		if ($type == 'premium')
			return 10737418240;
		if ($type == 'business')
			return 107374182400;
		if ($type == 'admin')
			return 75866302316544;
	}

	// Get the user's storage limit
	function accountLimits($raw = false)
	{
		global $db;

		$q = $db->prepare("SELECT * FROM `users` WHERE `UserID` = :id LIMIT 1");
		$q->execute([
			":id" => $_SESSION['user']['id']
		]);

		$type = $q->fetch()['AccountType'];

		if ($raw == true)
		{
			if ($type == 'user')
				return 524288000;
			if ($type == 'premium')
				return 10737418240;
			if ($type == 'business')
				return 107374182400;
			if ($type == 'admin')
				return 75866302316544;
		}

		if ($type == 'user')
			return formatBytes(524288000);
		if ($type == 'premium')
			return formatBytes(10737418240);
		if ($type == 'business')
			return formatBytes(107374182400);
		if ($type == 'admin')
			return formatBytes(75866302316544);
	}

	// Get the percentage usage (account usage with their account limits)
	function percentUsage()
	{
		return round((accountUsage(true) / accountLimits(true)) * 100, 2);
	}

	// Format bytes to their units (B, KB, MB)
	function formatBytes($bytes, $precision = 2) {
		$units = array('B', 'KB', 'MB', 'GB', 'TB');
		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);
		$bytes /= pow(1024, $pow);
		return round($bytes, $precision) . ' ' . $units[$pow];
	}

	if (isset($_SESSION['user']))
	{
		$user = userInfo();

		$admin = '';

		if ($user['AccountType'] == 'admin')
			$admin = '<li><a href="https://byte365.net/dashboard/admin/" class="waves-effect"><i class="material-icons">settings_applications</i>Admin Panel</a></li>';

		$sideNav = '<ul id="slide-out" class="side-nav fixed">
		   <li><div class="user-view">
		     <div class="background">
			 <img src="https://byte365.net/images/parallax.jpg" style="height: 200px; width: 300px;">
		     </div>
		     <div style="margin-top: 70px; z-index: 999; text-align: center;">
			     <a href="#!user"><img class="circle" src="https://byte365.net/uploads/pictures/default.png" style="height: 80px; width: 80px;"></a><br />
			     <a href="#!name" class="user-data" style="position: relative; top: -28px;"><b>'.htmlspecialchars($user['FirstName'] . ' ' . $user['LastName']) .'</b></a><br />
			     <a href="#!email" class="user-data" style="position: relative; top: -56px;">'. htmlspecialchars($user['Email']) .'</a>
		     </div>
		   </div></li>
		   <div style="position: relative; top: -64px;">
			   <li><a href="https://byte365.net/dashboard/" class="waves-effect"><i class="material-icons">cloud</i>My Cloud</a></li>
			   <li><a href="https://byte365.net/dashboard/cdn/"><i class="material-icons">cached</i>ByteCDN</a></li>
			   '.$admin.'
			   <li><div class="divider"></div></li>
			   <li><a class="subheader">My Account</a></li>
			   <li><a class="waves-effect" href="https://byte365.net/dashboard/account"><i class="material-icons">perm_identity</i>My Profile</a></li>
			   <li><a class="waves-effect" href="https://byte365.net/payments"><i class="material-icons">whatshot</i>Upgrade</a></li>
			   <li><a class="waves-effect" href="https://byte365.net/dashboard/security"><i class="material-icons">vpn_key</i>Security</a></li>
			   <li><a class="waves-effect" href="https://byte365.net/dashboard/logout"><i class="material-icons">not_interested</i>Log Out</a></li>
		   </div>
		 </ul>
		  <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
		  <script>
				$(".button-collapse").sideNav();
		  </script>';
	}
?>
