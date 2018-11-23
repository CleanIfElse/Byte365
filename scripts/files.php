<?php
	class Files
	{
		public static function Get($dir = '/')
		{
			global $db;

			$_SESSION['user']['dir'] = $dir;
			$files = $db->prepare("SELECT * FROM `uploads` WHERE `Uploader` = :uid AND `Folder` = :folder");
			$files->execute([
				":uid" => $_SESSION['user']['id'],
				":folder" => $dir
			]);

			$count = 1;
			$allFiles = '';

			$folders = $db->prepare("SELECT * FROM `folders` WHERE `FolderUser` = :user AND `FolderDir` = :dir");
			$folders->execute([
				":dir" => $dir,
				":user" => $_SESSION['user']['id']
			]);

			$folders = $folders->fetchAll();
			$folderCount = 1;

			if ($dir != '/')
			{
				$allFiles .= '<tr id="folder-0">
							<td class="file first"><div class="file-name"><i style="color: #a0a0a0;" class="fa fa-folder-open" aria-hidden="true"></i> <a href="#!" onclick="fileBrowser(\'/\')">../</a></div></td>
							<td class="file mobilehide"></td>
							<td class="file mobilehide"></td>
							<td class="file mobilehide"></td>
							<td class="file last"></td>
						  </tr>';
			}
			foreach ($folders as $folder)
			{
				$allFiles .= '<tr id="folder-'.$folder['FolderID'].'">
							<td class="file first"><div class="file-name"><i style="color: #a0a0a0;" class="fa fa-folder-open" aria-hidden="true"></i> <a href="#!" onclick="fileBrowser(\''.$folder['FolderGoesTo'].'\')">' . $folder['FolderName'] . '</a></div></td>
							<td class="file mobilehide"></td>
							<td class="file mobilehide"></td>
							<td class="file mobilehide"></td>
							<td class="file last"><div style="float: right;"> <a class="dropdown-button btn" href="#" data-activates="dropdown-folder'.$folderCount.'">Options</a></div>
							<ul id="dropdown-folder'.$folderCount.'" class="dropdown-content">
							<li><a href="#!" onclick="deleteFolder(\''.$folder['FolderID'].'\')">Delete</a></li>
							</ul>
						  </tr>';

				$folderCount++;
			}

			$files = $files->fetchAll();
			date_default_timezone_set('America/Los_Angeles');
			foreach ($files as $file)
			{
				$allFiles .= '
				<tr id="file-'.$file['UploadID'].'">
							<td class="file first"><div class="file-name">' . $file['FileName'] . '</div></td>
							<td class="file mobilehide">' . formatBytes($file['FileSize']) . '</td>
							<td class="file mobilehide">' . Files::FormatType(strtolower($file['FileType'])) . '</td>
							<td class="file mobilehide">'. date('m/d/Y', $file['UploadTime']) . '</td>
							<td class="file last"><div style="float: right;"> <a class="dropdown-button btn" href="#" data-activates="dropdown'.$count.'">Options</a></div>

							<ul id="dropdown'.$count.'" class="dropdown-content">
								<li><a href="#!" onclick="downloadFile(\''.$file['ShareKey'].'\', \''.$file['EncryptionKey'].'\')">Download</a></li>
								<li><a href="#!" onclick="showShareLink(\'https://byte365.net/share/?page&k='.$file['ShareKey'].'\')">Share</a></li>
								<li class="divider"></li>
								<li><a onclick="moveFile(\''.$file['ShareKey'].'\')">Move</a></li>
								<li><a onclick="deleteFile(\''.$file['ShareKey'].'\', \''.$file['EncryptionKey'].'\', \''.$file['UploadID'].'\')">Delete</a></li>
							</ul>
							<!-- <a onclick="downloadFile(\''.$file['ShareKey'].'\', \''.$file['EncryptionKey'].'\')">Download</a></td> -->
						  </tr>';
						  $count++;
			}
			return $allFiles . '
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">
			<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
			<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>';
		}

		public static function FormatType($type)
		{
				if ($type == 'txt')
				 	return 'Text File';
				if ($type == 'tmp')
					return 'Temporary File';
				if ($type == 'dll')
					return 'DLL File';
				if ($type == 'mp3')
					return 'Music File';
				if ($type == 'mp4')
					return 'MP4 File';
				if ($type == 'png')
				 	return 'PNG File';
				if ($type == 'jpg')
				 	return 'JPG File';
				if ($type == 'jpeg')
				 	return 'JPEG File';
				if ($type == 'sql')
				 	return 'SQL Database';
				if ($type == 'exe')
				 	return 'Executable';
				if ($type == 'dmg')
				 	return 'DMG Executable';
				if ($type == 'docx')
				 	return 'Word Document';
				if ($type == 'js')
				 	return 'JavaScript Document';
				if ($type == 'cpp')
				 	return 'C++ File';
				if ($type == 'cs')
				 	return 'C# File';
				if ($type == 'vb')
				 	return 'Visual Basic File';
				if ($type == 'html')
				 	return 'HTML Document';
				if ($type == 'php')
				 	return 'PHP File';
				if ($type == 'kdb')
				 	return 'KeePass Database';
				if ($type == 'zip')
				 	return 'Compressed Archive';
				if ($type == 'rar')
				 	return 'Compressed Archive';
				if ($type == 'jar')
				 	return 'Java Executable';
				else
					return $type;
		}

		public static function DeleteFile($fileID)
		{
			$q = $db->prepare("SELECT * FROM `uploads` WHERE `UploadID` = :id LIMIT 1");
			$q->execute([ ":id" => $_SESSION['user']['id'] ]);
			$q = $q->fetch();

			$file = $q->fetch();
			$ip = explode(':', $file['Location'])[0];
			$path = explode(':', $file['Location'])[1];

			try
			{
				$server = $db->prepare("SELECT * FROM `servers` WHERE `ServerIP` = :ip LIMIT 1");
				$server->execute([ ":ip" => $ip ]);
				$server = $server->fetch();

				$sftp = new Net_SFTP($server['ServerIP']);
				if (!$sftp->login($server['ServerUser'], $server['ServerPassword']))
				    die('Server login failed.');

				$sftp->delete('/var/byte365/'.$path, false);

				$d = $db->prepare("UPDATE `servers` SET `UsedStorage` = `UsedStorage` - :used WHERE `ServerIP` = :ip");
				$d->execute([
					":used" => $file['FileSize'],
					":ip" => $ip
				]);

				$q = $db->prepare("DELETE FROM `uploads` WHERE `UploadID` = :id LIMIT 1");
				$q->execute([
					":id" => $_SESSION['user']['id']
				]);

				print json_encode([
					"status" => "success",
					"message" => "Folder deleted."
				]);
			}
			catch (Exception $e)
			{
			    echo $e->getMessage() . "\n";
			}
		}
	}
?>
