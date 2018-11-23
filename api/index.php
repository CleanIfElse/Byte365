<?php
	session_start();
	include('../scripts/include.php');
	include('../scripts/files.php');
	include('../scripts/encryption.php');
	include('../scripts/db.php');
	set_include_path('../phpseclib');
	include('Net/SFTP.php');


	if (isset($_GET['nightMode']))
	{
		if (isset($_SESSION['nightMode']))
			print 'dark';
			else {
				print 'white';
			}
	}

	if (isset($_GET['nightModeSet']))
	{
		if (!isset($_SESSION['nightMode']))
		{
			$_SESSION['nightMode'] = 'true';
		}
		else
		{
			unset($_SESSION['nightMode']);
		}
	}

	// CDN //
	if (isset($_GET['cdn']) && isset($_FILES))
	{
		// Check if the API key is specified and valid. Pull information from the user.
		if (isset($_GET['key']))
		{
			$infoGrab = $db->prepare("SELECT * FROM `users` WHERE `salt` = :salt LIMIT 1");
			$infoGrab->execute([
				":salt" => $_GET['key']
			]);

			$infoGrab = $infoGrab->fetch();

			// This user is not allowed to access ByteCDN.
			if ($infoGrab['AccountType'] == 'user' || $infoGrab['AccountType'] == 'banned')
			{
				die(json_encode([
					"status" => "error",
					"message" => "You are not allowed to access this tool."
				]));
			}
			else
			{
				$userID = $infoGrab['UserID'];

				$server = $db->prepare("SELECT * FROM `servers` LIMIT 1");
				$server->execute();
				$server = $server->fetch();

				$fileName = time() . '_' . basename($_FILES["file"]["name"]);
				$fileContents = file_get_contents($_FILES['file']['tmp_name']);
				$fileType = pathinfo($fileName, PATHINFO_EXTENSION);

				$sftp = new Net_SFTP($server['ServerIP']);
				if (!$sftp->login($server['ServerUser'], $server['ServerPassword']))
				{
					die(json_encode([
						"status" => "error",
						"message" => "Unable to login to the destination server."
					]));
				}
				else
				{
					if ((accountUsageByID($userID) + $_FILES["file"]["size"]) > accountLimitsByID($userID))
					{
						die(json_encode([
							"status" => "error",
							"message" => "You have exceeded your account's limits. Limit: ".accountLimitsByID($userID)
						]));
					}

					$sftp->put('/var/www/html/'.$fileName, $fileContents);

					$q = $db->prepare("INSERT INTO `cdn` (`Uploader`, `FileName`, `FileLocation`, `Size`) VALUES (:uploader, :name, :location, :size)");
					$q->execute([
						":uploader" => $userID,
						":location" => $server['ServerIP'] . ':' .$fileName,
						":size" => $_FILES["file"]["size"],
						":name" => basename($_FILES["file"]["name"]),
					]);

					$q2 = $db->prepare("UPDATE `servers` SET `UsedStorage` = `UsedStorage` + :used WHERE `ServerIP` = :ip");
					$q2->execute([
						":used" => $_FILES["file"]["size"],
						":ip" => $server['ServerIP']
					]);

					die(json_encode([
						"status" => "success",
						"message" => "https://cdn".$server['ServerID'].".byte365.net/".$fileName
					]));
				}
			}
		}
		else
		{
			die(json_encode([
				"status" => "error",
				"message" => "API key not specified."
			]));
		}

		$server = $db->prepare("SELECT * FROM `servers` LIMIT 1");
		$server->execute();
		$server = $server->fetch();

		$fileName = time() . '_' . basename($_FILES["file"]["name"]);
		$fileContents = file_get_contents($_FILES['file']['tmp_name']);
		$fileType = pathinfo($fileName, PATHINFO_EXTENSION);

		set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');
		include('Net/SFTP.php');

		$sftp = new Net_SFTP($server['ServerIP']);
		if (!$sftp->login($server['ServerUser'], $server['ServerPassword']))
				$alert->Create('Server Login Failed!', 'error', $_SESSION['user']['id']);
		else
		{
			if ((accountUsage(true) + $_FILES["file"]["size"]) > accountLimits(true))
			{
				$alert->Create('You have exceeded your maximum upload limit.', 'error', $_SESSION['user']['id']);
				die();
			}

			$alert->Create('Uploading File.', 'upload', $_SESSION['user']['id']);
			$sftp->put('/var/www/html/'.$fileName, $fileContents);

			$q = $db->prepare("INSERT INTO `cdn` (`Uploader`, `FileName`, `FileLocation`, `Size`) VALUES (:uploader, :name, :location, :size)");
			$q->execute([
				":uploader" => $_SESSION['user']['id'],
				":location" => $server['ServerIP'] . ':' .$fileName,
				":size" => $_FILES["file"]["size"],
				":name" => basename($_FILES["file"]["name"]),
			]);

			$q2 = $db->prepare("UPDATE `servers` SET `UsedStorage` = `UsedStorage` + :used WHERE `ServerIP` = :ip");
			$q2->execute([
				":used" => $_FILES["file"]["size"],
				":ip" => $server['ServerIP']
			]);

			$alert->Create('Your file has been uploaded.', 'upload', $_SESSION['user']['id']);
		}
	}



	if (isset($_GET['agree']))
	{
		$q = $db->prepare("UPDATE `users` SET `AcceptedTerms` = 'yes' WHERE `UserID` = :id");
		$q->execute([
			":id" => $_SESSION['user']['id']
		]);
	}

	if (isset($_FILES['imagevalue']))
		changeProfilePicture($_FILES['imagevalue']);

	if (isset($_GET['fileBrowser']))
	{
		print Files::Get($_GET['fileBrowser']);
	}

	if (isset($_GET['checkKey']))
	{
		$userSel = $db->prepare("SELECT * FROM `users` WHERE `salt` = :salt LIMIT 1");
		$userSel->execute([
			":salt" => $_GET['updateFileList']
		]);

		if ($userSel->rowCount() == 0)
		{
			print 'false';
		}
		else {
			print 'true';
		}
	}

	if (isset($_GET['updateFileList']))
	{
		$userSel = $db->prepare("SELECT * FROM `users` WHERE `salt` = :salt LIMIT 1");
		$userSel->execute([
			":salt" => $_GET['updateFileList']
		]);

		if ($userSel->rowCount() == 0)
		{
			print json_encode([
				"status" => "error",
				"message" => "Invalid API Key."
			]);
		}

		$files = $db->prepare("SELECT * FROM `uploads` WHERE `Uploader` = :uid");
		$files->execute([
			":uid" => $userSel->fetch()['UserID']
		]);

		$fileOutput = array();

		$num = 0;

		foreach ($files as $file)
		{
			$fileOutput[$num] = $file['FileName'] . '|' . gmdate("m-d-Y", $file['UploadTime']) . '|' . formatBytes($file['FileSize']);
			$num++;
		}

		print json_encode($fileOutput);
	}

	if (isset($_GET['getMyFiles']))
	{
		$userSel = $db->prepare("SELECT * FROM `users` WHERE `UserID` = :id LIMIT 1");
		$userSel->execute([
			":id" => '1'
		]);

		if ($userSel->rowCount() == 0)
		{
			print json_encode([
				"status" => "error",
				"message" => "Invalid API Key."
			]);
		}

		$files = $db->prepare("SELECT * FROM `uploads` WHERE `Uploader` = :uid");
		$files->execute([
			":uid" => $userSel->fetch()['UserID']
		]);

		$num = 0;

		$count = 0;

		$counter = $files->rowCount();
		$files = $files->fetchAll();

		date_default_timezone_set('America/Los_Angeles');

		for ($i = 0; $i <= $counter - 1; $i++)
		{
			$files[$i]['FileSize'] = formatBytes($files[$i]['FileSize']);
			$files[$i]['UploadTime'] = date('m/d/Y', $files['UploadTime']);
		}

		print json_encode($files);
	}

	if (isset($_GET['checkCoupon']))
	{
		$q = $db->prepare("SELECT * FROM `coupons` WHERE `Name` = :name");
		$q->execute([
			":name" => $_GET['checkCoupon']
		]);

		if ($q->rowCount() > 0)
		{
			$q = $q->fetch();
			print json_encode([
				"status" => "success",
				"message" => $q['PercentOff']
			]);
		}
		else {
			print json_encode([
				"status" => "error",
				"message" => '0'
			]);
		}
	}

	if (isset($_GET['currentDir']))
	{
		print $_SESSION['user']['dir'];
	}

	if (isset($_GET['deleteFolder']) && is_numeric($_GET['deleteFolder']))
	{
		$q = $db->prepare("SELECT * FROM `folders` WHERE `FolderUser` = :id AND `FolderID` = :folder LIMIT 1");
		$q->execute([
			":id" => $_SESSION['user']['id'],
			":folder" => $_GET['deleteFolder']
		]);

		if ($q->rowCount() > 0)
		{
			$folderDir = $q->fetch()['FolderGoesTo'];
			$q2 = $db->prepare("DELETE FROM `folders` WHERE `FolderID` = :folder LIMIT 1");
			$q2->execute([
				":folder" => $_GET['deleteFolder']
			]);


			$q3 = $db->prepare("SELECT * FROM `uploads` WHERE `Folder` = :dir AND `Uploader` = :user LIMIT 1");
			$q3->execute([
				":folder" => $folderDir,
				":user" => $_SESSION['user']['id']
			]);

			foreach ($q3->fetchAll() as $toDelete)
			{
				Files::DeleteFile($toDelete['UploadID']);
			}
		}
	}

	if (isset($_GET['addFolder']))
	{
		if (strlen($_GET['addFolder']) == 0)
		{
			print json_encode([
				"status" => "error",
				"message" => "Folder name can not be empty."
			]);

			die();
		}


		if (strpos($_GET['addFolder'], '/') !== false)
		{
			print json_encode([
  			    "status" => "error",
  			    "message" => "Folder name can not contain the character '/'."
  		    ]);

		    die();
		}

		if (strpos($_GET['addFolder'], '\\') !== false)
		{
			print json_encode([
  			    "status" => "error",
  			    "message" => "Folder name can not contain the character '\\'."
  		    ]);

		    die();
		}

		$q = $db->prepare("SELECT * FROM `folders` WHERE `FolderGoesTo` = :goesto AND `FolderUser` = :user");
		$q->execute([
			":goesto" => $_SESSION['user']['dir'] . $_GET['addFolder'] . '/',
			":user" => $_SESSION['user']['id']
		]);

		if ($q->rowCount() > 0)
		{
			print json_encode([
  			    "status" => "error",
  			    "message" => "This folder already exists."
  		    ]);

		    die();
		}

		$add = $db->prepare("INSERT INTO `folders` (`FolderUser`, `FolderName`, `FolderDir`, `FolderGoesTo`) VALUES (:id, :name, :dir, :goesto)");
		$add->execute([
			":id" => $_SESSION['user']['id'],
			":name" => $_GET['addFolder'],
			":dir" => $_SESSION['user']['dir'],
			":goesto" => $_SESSION['user']['dir'] . $_GET['addFolder'] . '/'
		]);

		print json_encode([
		    "status" => "success",
		    "message" => $_SESSION['user']['dir'] . $_GET['addFolder'] . '/'
	    ]);
	}

	if (isset($_GET['moveFile']) && isset($_GET['new']))
	{
		$q = $db->prepare("SELECT * FROM `folders` WHERE `FolderGoesTo` = :fpath AND `FolderUser` = :user LIMIT 1");
		$q->execute([
			":fpath" => $_GET['new'],
			":user" => $_SESSION['user']['id']
		]);

		$newDir = '';
		if ($q->rowCount() > 0)
		{
			$newDir = $_GET['new'];
		}
		else if ($_GET['new'] == '/')
		{
			$newDir = '/';
		}
		else
		{
			die(json_encode([
  			    "status" => "error",
  			    "message" => "The path '".$_GET['new']."' does not exist."
  		    	]));
		}
			$q2 = $db->prepare("UPDATE `uploads` SET `Folder` = :folder WHERE `ShareKey` = :key AND `Uploader` = :user");
			$q2->execute([
				":folder" => $_GET['new'],
				":key" => $_GET['moveFile'],
				":user" => $_SESSION['user']['id']
			]);

			print json_encode([
  			    "status" => "success",
  			    "message" => $_GET['new']
  		    	]);
	}




if (isset($_GET['friendly']))
{
	include('../scripts/toxicity.php');
	$percent = round(Toxicity::Check($_GET['friendly'], 3));

	if ($percent > 100)
		$percent = 100;

	print $percent;
}


if (isset($_GET['pc']))
{
	include ('../scripts/login.php');
	$res = Login::DoLogin($_GET['email'], $_GET['password']);

	if ($res == '')
	{
		$q = $db->prepare("SELECT * FROM `users` WHERE `Email` = :email LIMIT 1");
		$q->execute([
			":email" => $_GET['email']
		]);
		$q = $q->fetch();
		print json_encode([
			"status" => "success",
			"message" => "You have been logged in.",
			"apikey" => $q['salt']
		]);
	}
	else
	{
		print json_encode([
			"status" => "error",
			"message" => "There is a problem with your login!"
		]);
	}
}
?>
