<?php
	try
	{
	    $db = new PDO('mysql:host=localhost;dbname=byte365', 'root', 'HE@ZSMqJRoZ8ekFAqWVdZnnOtNn8WQ8PL!9#@');
	}
	catch (PDOException $e)
	{
	    die('Unable to connect to the Byte365 database.');
	}
?>
