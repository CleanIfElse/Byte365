<?php
	class CDN
	{
		public static function GetFiles()
		{
			global $db;

			$q = $db->prepare("SELECT * FROM `cdn` WHERE `Uploader` = :uid");
			$q->execute([
				":uid" => $_SESSION['user']['id']
			]);

			$toPrint = '';

			foreach ($q->fetchAll() as $item)
			{
				$url = explode(':', $item['FileLocation'])[1];

				$serverSel = $db->prepare("SELECT * FROM `servers` WHERE `ServerIP` = :ip");
				$serverSel->execute([
					":ip" => explode(':', $item['FileLocation'])[0]
				]);


				$toPrint .= '<tr>
					<td class="file first">
						'.htmlspecialchars($item['FileName']).'
					</td>
					<td class="file">
						'.formatBytes($item['Size']).'
					</td>
					<td class="file">
						<a onclick="viewURL(\'https://cdn'.$serverSel->fetch()['cdnID'].'.byte365.net/'.$url.'\')">View</a>
					</td>
					<td class="file last">
						<a href="#">Delete</a>
					</td>
				</tr>';
			}

			return $toPrint;
		}
	}
?>
