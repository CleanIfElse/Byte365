<?php
	class Login
	{
		public static function DoLogin($email, $password)
		{
			global $db;

			$q = $db->prepare("SELECT * FROM `users` WHERE `Email` = :email LIMIT 1");
			$q->execute([ ":email" => $email ]);

			if ($q->rowCount() != 1)
				return 'There is no account associated with this email.';
			else
			{
				$q = $q->fetch();
				$thepassword = $q['Password'];
				$salt = $q['salt'];

				include('bcrypt.php');

				$bcrypt = new Bcrypt(15);
				$isgood = $bcrypt->verify($password . 'area51', $thepassword);

				if ($isgood != 1)
					return 'Incorrect email/password combination.';

				$disabled = $db->prepare("SELECT * FROM `settings`");
				$disabled->execute();
				if ($disabled->fetch()['logins'] == '1')
					return 'Logins are currently disabled. Check back later!';

				$_SESSION['user'] = array(
					"ip" => $_SERVER["HTTP_CF_CONNECTING_IP"],
					"country" => $_SERVER["HTTP_CF_IPCOUNTRY"],
					"id" => $q['UserID']
				);

				$q2 = $db->prepare("INSERT INTO `logins` (`LoginIP`, `LoginCountry`, `LoginTime`, `LoginUser`) VALUES (:ip, :country, :ltime, :user)");
				$q2->execute([
					":ip" => $_SERVER["HTTP_CF_CONNECTING_IP"],
					":country" => $_SERVER["HTTP_CF_IPCOUNTRY"],
					":ltime" => time(),
					":user" => $q['UserID']
				]);

				return '';
			}
		}
	}
?>
