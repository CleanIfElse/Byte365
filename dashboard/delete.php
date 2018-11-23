<?php
session_start();
	include('../scripts/db.php');
	error_reporting(E_ALL);

	$shareKey = $_GET['k'];
	$encryptionKey = $_GET['e'];

	$q = $db->prepare("SELECT * FROM `uploads` WHERE `ShareKey` = :key AND `EncryptionKey` = :e AND `Uploader` = :uid LIMIT 1");
	$q->execute([
		":key" => $shareKey,
		":e" => $encryptionKey,
		":uid" => $_SESSION['user']['id']
	]);

	if ($q->rowCount() > 0)
	{
		$file = $q->fetch();
		unlink('../uploads/' . $file['Location']);
		$del = $db->prepare("DELETE FROM `uploads` WHERE `ShareKey` = :key AND `EncryptionKey` = :e AND `Uploader` = :uid");
		$del->execute([
			":key" => $shareKey,
			":e" => $encryptionKey,
			":uid" => $_SESSION['user']['id']
		]);

		include('../scripts/alerts.php');
		$h = new AlertHandler();
		$h->Create($file['FileName'] . ' has been deleted!', 'upload', $_SESSION['user']['id']);
		print 'deleted';
	}
	else
	{
		print 'file no exist';
	}
?>
