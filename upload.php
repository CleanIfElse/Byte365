<?php
	session_start();
	include('scripts/include.php');
	include('scripts/encryption.php');
	include('scripts/alerts.php');

	set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');
	include('Net/SFTP.php');

	$alert = new AlertHandler();

	$server = $db->prepare("SELECT * FROM `servers` WHERE `MaxStorage` > :filesize LIMIT 1");
	$server->execute([
		":filesize" => $_FILES["file"]["size"]
	]);
	$server = $server->fetch();

	$fileName = time() . '_' . basename($_FILES["file"]["name"]);
	$fileContents = file_get_contents($_FILES['file']['tmp_name']);
	$fileType = pathinfo($fileName, PATHINFO_EXTENSION);

	if (isset($_GET['cdn']))
	{
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
	else
	{

			$fileKey = Encryption::generateRandomStringChars();
			$fileContents = Encryption::encryptData($fileContents, $fileKey);

			if ($_FILES["file"]["size"] > 50000000)
			{
				$alert->Create('Your file is too large!', 'error', $_SESSION['user']['id']);
				die();
			}

			if (1 > $_FILES["file"]["size"])
			{
				$alert->Create('Oops, something happened with your upload! Try again later.', 'error', $_SESSION['user']['id']);
				die();
			}

			if ((accountUsage(true) + $_FILES["file"]["size"]) > accountLimits(true))
			{
				$alert->Create('You have exceeded your maximum upload limit.', 'error', $_SESSION['user']['id']);
				die();
			}

			$sftp = new Net_SFTP($server['ServerIP']);
			if (!$sftp->login($server['ServerUser'], $server['ServerPassword']))
					$alert->Create('Server Login Failed!', 'error', $_SESSION['user']['id']);
			else
			{
				$alert->Create('Uploading File.', 'upload', $_SESSION['user']['id']);
				$sftp->put('/var/byte365/'.$fileName, $fileContents);

				$q = $db->prepare("INSERT INTO `uploads` (`Uploader`, `EncryptionKey`, `Location`, `FileType`, `FileSize`, `FileName`, `ShareKey`, `UploadTime`, `Folder`) VALUES (:uploader, :ekey, :location, :type, :size, :name, :skey, :utime, '/')");
				$q->execute([
					":uploader" => $_SESSION['user']['id'],
					":ekey" => $fileKey,
					":location" => $server['ServerIP'] . ':' .$fileName,
					":type" => $fileType,
					":size" => $_FILES["file"]["size"],
					":name" => basename($_FILES["file"]["name"]),
					":skey" => Encryption::generateRandomStringChars(),
					":utime" => time()
				]);

				$q2 = $db->prepare("UPDATE `servers` SET `UsedStorage` = `UsedStorage` + :used WHERE `ServerIP` = :ip");
				$q2->execute([
					":used" => $_FILES["file"]["size"],
					":ip" => $server['ServerIP']
				]);

				$alert->Create('Your file has been uploaded.', 'upload', $_SESSION['user']['id']);
			}
	}
?>
