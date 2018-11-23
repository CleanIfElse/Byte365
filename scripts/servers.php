<?php
class Server{
	public static function FetchServers()
	{
		global $db;

		$servers = '';

		$q = $db->prepare("SELECT * FROM `servers`");
		$q->execute();

		$count = 1;
		foreach ($q->fetchAll() as $server)
		{
			$servers .= '<tr id="server-'.$server['ServerID'].'">
						<td class="file first"><div style="display: inline-block;" id="ping-'.$server['ServerID'] . '"><div style="border-radius: 50%; width: 10px; height: 10px; background: #dcdcdc; display: inline-block;"></div>&nbsp;</div> ' . $server['ServerName'] . ' ('.$server['ServerIP'].')</td>
						<td class="file mobilehide"> Used: ' . formatBytes($server['UsedStorage']) . ' / '. formatBytes($server['MaxStorage']).'</td>
						<td class="file last"><div style="float: right;"> <a class="dropdown-button btn" href="#" data-activates="dropdown'.$count.'">Options</a></div>

						<ul id="dropdown'.$count.'" class="dropdown-content">
							<li><a href="#!" onclick="deleteServer()">Delete</a></li>
						</ul>
					  </tr>';
					  $count++;
		}

		return $servers;
	}

	public static function initiatePings()
	{
		global $db;
		$toReturn = '';
		$q = $db->prepare("SELECT * FROM `servers`");
		$q->execute();

		foreach ($q->fetchAll() as $server)
		{
			$toReturn .= 'pingThis("' . $server['ServerIP'] . '", "' .$server['ServerID'] . '");';
		}

		return $toReturn;
	}
}
?>
