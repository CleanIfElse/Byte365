<?php
class Security
{
	// Get the user's recent login history
	public static function RecentLogins()
	{
		date_default_timezone_set('America/New_York');
		global $db;

		$q = $db->prepare("SELECT * FROM `logins` WHERE `LoginUser` = :id ORDER BY `LoginID` DESC LIMIT 10");
		$q->execute([ ":id" => $_SESSION['user']['id'] ]);

		$q = $q->fetchAll();

		$logins = '';

		foreach ($q as $login)
		{
			$logins .= '<tr>
						<td class="file first last"><img src="https://byte365.net/images/flags/'.$login['LoginCountry'].'.png" /> &nbsp; <div class="file-name" style="display: inline-block;"><a href="http://whatismyipaddress.com/ip/'.$login['LoginIP'].'"><span style="color: #0099b5;">'.$login['LoginIP'].'</span></a></div> on '.date("F j, Y, g:i a", $login['LoginTime']).'</td>
					</tr>';
		}

		return $logins;
	}
}
