<?php
	include('../scripts/include.php');
	include('../scripts/encryption.php');
	include('../scripts/sftp.php');

	$q = $db->prepare("SELECT * FROM `uploads` WHERE `ShareKey` = :skey AND `EncryptionKey` = :key LIMIT 1");
	$q->execute([
		":skey" => $_GET['k'],
		":key" => $_GET['e']
	]);

	if ($q->rowCount() > 0)
	{
		$file = $q->fetch();
		$ip = explode(':', $file['Location'])[0];
		$path = explode(':', $file['Location'])[1];

		try
		{
			$server = $db->prepare("SELECT * FROM `servers` WHERE `ServerIP` = :ip LIMIT 1");
			$server->execute([ ":ip" => $ip ]);
			$server = $server->fetch();
			$sftp = new SFTPConnection($server['ServerIP'], intval($server['ServerPort']));
			$sftp->login($server['ServerUser'], $server['ServerPassword']);
			$fileData = $sftp->receiveFile($path);

			//print $fileData;

			header('Content-type: text/plain');
			$fileName = $file['FileName'];
			header("Content-Disposition: attachment; filename=$fileName");
			$fileData = Encryption::unbyte365($fileData);

			//print $fileData;
			$fileData = Encryption::openssl_decrypt($fileData, $file['EncryptionKey']);

			print $fileData;
		}
		catch (Exception $e)
		{
		    echo $e->getMessage() . "\n";
		}
	}
	else
	{
		print 'no';
	}
?>
