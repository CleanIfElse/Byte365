<?php
class Register
{
	public static function DoRegister($first, $last, $email, $password, $repassword)
	{
		global $db;

		if (strlen($first) < 2 || strlen($first) > 50)
			return 'Your first name must be between 2 and 50 characters.';

		if (strlen($last) < 2 || strlen($last) > 50)
			return 'Your last name must be between 2 and 50 characters.';

		if(preg_match("/^[a-zA-Z0-9]+$/", $first) != 1)
    			return 'Your first name may only contain alphabetical characters.';

		if(preg_match("/^[a-zA-Z0-9]+$/", $last) != 1)
			return 'Your last name may only contain alphabetical characters.';

		if (strlen($email) < 5 || strlen($email) > 100)
			return 'Your email must be between 5 and 100 characters.';

		if ($password != $repassword)
			return 'Your passwords do not match.';

		$e = $db->prepare("SELECT * FROM `users` WHERE `Email` = :email");
		$e->execute([":email" => $email ]);

		if ($e->rowCount() > 0)
			return 'This email is already in use.';

		include('bcrypt.php');
		$salt = uniqid(mt_rand(), true);
		$bcrypt = new Bcrypt(15);
		$password = $bcrypt->hash($password . 'area51');

		$q = $db->prepare("INSERT INTO `users` (`FirstName`, `LastName`, `Email`, `Password`, `salt`, `regdate`, `LoginIP`) VALUES (:first, :last, :email, :password, :salt, :regdate, :ip)");
		$q->execute([
			":first" => $first,
			":last" => $last,
			":email" => $email,
			":password" => $password,
			":salt" => $salt,
			":regdate" => time(),
			":ip" => $_SERVER["HTTP_CF_CONNECTING_IP"]
		]);

		return 'success';
	}
}
?>
