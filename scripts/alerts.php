<?php
class AlertHandler
{
	public function Create($text, $type, $uid)
	{
		global $db;

		$q = $db->prepare("INSERT INTO `alerts` (`AlertType`, `AlertText`, `AlertUID`) VALUES (:type, :atext, :uid)");
		$q->execute([
			":type" => $type,
			":atext" => $text,
			":uid" => $uid
		]);
	}

	public function Delete($id)
	{
		global $db;

		$q = $db->prepare("DELETE FROM `alerts` WHERE `AlertID` = :id");
		$q->execute([
			":id" => $id
		]);
	}

	public function GetAlert()
	{
		global $db;

		$q = $db->prepare("SELECT * FROM `alerts` WHERE `AlertUID` = :id LIMIT 1");
		$q->execute([
			":id" => $_SESSION['user']['id']
		]);

		$q = $q->fetch();

		$this->Delete($q['AlertID']);

		return json_encode([
			"type" => $q['AlertType'],
			"text" => $q['AlertText']
		]);
	}
}
?>
